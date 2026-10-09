<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TableExport;
use App\Http\Controllers\Controller;
use App\Models\DesignationMaster;
use App\Models\EmployeeEditLog;
use App\Models\EmployeeMaster;
use App\Models\Hr\Department as HrDepartment;
use App\Models\Hr\Employee as HrEmployee;
use App\Models\Hr\EmployeeExit;
use App\Models\KycSubmission;
use App\Services\Hr\EmployeeExitService;
use App\Services\Hr\EmployeeHrSyncService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    public function search(Request $request)
    {
        session([
            'employee_search' => $request->employee_search,
            'designation_filter' => $request->n_designation_id,
            'employee_status_filter' => $request->employee_status,
        ]);

        return redirect()->route('admin.employees.index');
    }

    public function clearSearch()
    {
        session()->forget([
            'employee_search',
            'designation_filter',
            'employee_status_filter',
        ]);

        return redirect()->route('admin.employees.index');
    }

    /** Role identifiers treated as HR (see every employee). */
    private const HR_ROLE_IDENTIFIERS = ['HRM', 'HR_TEAM'];

    /**
     * How much of the employee list the signed-in user may see.
     *
     * @return array{0: 'all'|'direct', 1: ?int} mode, and (for 'direct')
     *                                           the user's own employee id
     */
    private function employeeScope(): array
    {
        $user = auth()->user();

        if ($user->hasAnyRole(['Super Admin', 'Gipra Admin'])) {
            return ['all', null];
        }

        $isHr = $user->roles->contains(
            fn ($role) => $role->hr_access === 'hr_admin'
                || in_array($role->identifier, self::HR_ROLE_IDENTIFIERS, true)
        );

        if ($isHr) {
            return ['all', null];
        }

        return ['direct', $user->n_employee_id ? (int) $user->n_employee_id : null];
    }

    /** 403 unless the signed-in user may see/manage this employee. */
    private function authorizeEmployeeAccess(EmployeeMaster $employee): void
    {
        [$mode, $ownId] = $this->employeeScope();

        if ($mode === 'all') {
            return;
        }

        abort_unless(
            $ownId && (int) $employee->reporting_to === $ownId,
            403,
            'You can only access employees who report to you.'
        );
    }

    /**
     * Employee list query (session filters + what the logged-in user may
     * see — see employeeScope()). Shared by the list page and the Excel export.
     *
     * @return array{0: Builder, 1: string}
     */
    private function filteredEmployees(): array
    {
        // Get filters from session
        $search = session('employee_search');
        $designation = session('designation_filter');
        $statusFilter = session('employee_status_filter') === 'former' ? 'former' : 'current';

        // "Former" = deleted employees (resigned / terminated). They stay
        // reachable so their history can still be opened.
        $query = EmployeeMaster::with(['designation']);
        $statusFilter === 'former'
            ? $query->onlyTrashed()
            : $query->whereNull('deleted_at');

        /*
         * Who may see whom:
         *   - Super Admin / Gipra Admin / HR  -> every employee, including
         *     higher grades.
         *   - Everyone else (FCO, managers, ...) -> only the employees who
         *     report directly to them (reporting_to = their employee id),
         *     not everyone in the designations below them.
         */
        [$scopeMode, $scopeEmployeeId] = $this->employeeScope();

        if ($scopeMode === 'direct') {
            $scopeEmployeeId
                ? $query->where('reporting_to', $scopeEmployeeId)
                : $query->whereRaw('1 = 0');
        }

        // Search by employee code or employee name
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('c_employee_code', 'LIKE', "%{$search}%")
                    ->orWhere('c_employee_name', 'LIKE', "%{$search}%");
            });
        }

        // Designation filter (already limited to the allowed employees above).
        if (! empty($designation)) {
            $query->where('n_designation_id', $designation);
        }

        return [$query, $statusFilter];
    }

    public function export()
    {
        [$query, $statusFilter] = $this->filteredEmployees();

        $employees = $query
            ->with(['designation', 'reportingManager'])
            ->orderBy('c_employee_code')
            ->get();

        $rows = [];
        $i = 0;
        foreach ($employees as $e) {
            $rows[] = [
                ++$i,
                $e->c_employee_code,
                $e->c_employee_name,
                $e->designation?->c_designation,
                $e->c_employee_email,
                $e->n_employee_phone,
                $e->city,
                $e->reportingManager?->c_employee_name,
                $e->date_of_joining ? Carbon::parse($e->date_of_joining)->format('d-m-Y') : null,
                $statusFilter === 'former' ? 'Former' : ($e->c_status === 'Y' ? 'Active' : 'Inactive'),
                $e->isAssociate() ? 'Associate' : 'Employee',
            ];
        }

        return Excel::download(
            new TableExport(
                ['Sl No', 'Employee Code', 'Name', 'Designation', 'Email', 'Phone', 'City', 'Reporting To',
                    'Date of Joining', 'Status', 'Type'],
                $rows,
                ['B', 'F']
            ),
            ($statusFilter === 'former' ? 'former-employees-' : 'employees-').now()->format('Ymd-His').'.xlsx'
        );
    }

    public function index(Request $request)
    {
        [$query, $statusFilter] = $this->filteredEmployees();

        $employees = $query->paginate(10);

        [$scopeMode, $scopeEmployeeId] = $this->employeeScope();

        // Employees this user is allowed to see (for the dropdown + search).
        $allowedEmployees = EmployeeMaster::query()
            ->whereNull('deleted_at')
            ->when($scopeMode === 'direct', function ($q) use ($scopeEmployeeId) {
                $scopeEmployeeId
                    ? $q->where('reporting_to', $scopeEmployeeId)
                    : $q->whereRaw('1 = 0');
            });

        /*
         * Designation dropdown: every active designation for Super Admin /
         * HR; only the designations of one's direct reports otherwise.
         */
        $designations = DesignationMaster::where('c_status', 'Y')
            ->when($scopeMode === 'direct', function ($q) use ($allowedEmployees) {
                $q->whereIn('n_designation_id', (clone $allowedEmployees)->select('n_designation_id'));
            })
            ->orderBy('hierarchy_level')
            ->get();

        // Employee autocomplete: only the allowed employees.
        $employeesForSearch = (clone $allowedEmployees)
            ->select('n_employee_id', 'c_employee_name', 'c_employee_code')
            ->where('c_status', 'Y')
            ->orderBy('c_employee_name')
            ->get();

        return view(
            'admin.employees.index',
            compact(
                'employees',
                'designations',
                'employeesForSearch',
                'statusFilter'
            )
        );
    }

    public function generateEmployeeCode($designationId)
    {
        $designation = DesignationMaster::findOrFail($designationId);

        $prefix = strtoupper(trim($designation->identifier));

        // Find the latest employee code with this designation identifier
        $lastEmployee = EmployeeMaster::where(
            'c_employee_code',
            'LIKE',
            $prefix.'%'
        )
            ->orderByDesc('n_employee_id')
            ->first();

        if ($lastEmployee) {

            preg_match('/(\d+)$/', $lastEmployee->c_employee_code, $matches);

            $nextNumber = isset($matches[1])
                ? ((int) $matches[1]) + 1
                : 1;

        } else {
            $nextNumber = 1;
        }

        $employeeCode = $prefix.str_pad(
            $nextNumber,
            3,
            '0',
            STR_PAD_LEFT
        );

        return response()->json([
            'employee_code' => $employeeCode,
        ]);
    }

    public function create(Request $request)
    {
        // Coming from Recruitment: prefill from the hired candidate and their requisition.
        $found = $request->filled('candidate')
            ? \App\Models\Hr\Candidate::with('requisition.designation')->find($request->integer('candidate'))
            : null;

        // Only a hired candidate with no employee profile yet can be linked on save.
        $candidate = $found && $found->stage === 'hired' && ! $found->converted_employee_id ? $found : null;

        $prefill = [];
        if ($found) {
            $phone = substr(preg_replace('/\D+/', '', (string) $found->phone), -10);
            $prefill = [
                'name' => $found->name,
                'email' => $found->email,
                'phone' => preg_match('/^[6-9]\d{9}$/', $phone) ? $phone : '',
                'department_id' => $found->requisition?->department_id,
                'designation_title' => $found->requisition?->designation?->title,
                'requisition_title' => $found->requisition?->title,
            ];
        } elseif ($request->filled('name')) {
            $prefill = ['name' => $request->query('name'), 'email' => $request->query('email')];
        }

        $employees = EmployeeMaster::where('c_status', 'Y')
            ->orderBy('c_employee_name')
            ->get();

        $user = auth()->user();

        /*
         * Super Admin and Gipra Admin
         * can create employees for all designations
         */
        if ($user->hasAnyRole(['Super Admin', 'Gipra Admin'])) {

            $designations = DesignationMaster::where('c_status', 'Y')
                ->orderBy('hierarchy_level')
                ->get();

            /*
             * Farm Care Officer can create
             * employee only for Farm Care Advisor
             */
        } elseif ($user->hasRole('Farm Care Officer')) {

            $designations = DesignationMaster::where('c_status', 'Y')
                ->where('identifier', 'FCA')
                ->get();

        } elseif ($candidate && (
            $user->hasAnyRole(['HR Manager', 'HR Team', 'HR Department'])
            || $user->roles->contains(fn ($r) => in_array($r->identifier, ['HRM', 'HR_TEAM'], true))
        )) {

            /*
             * HR hiring from Recruitment: can pick any designation while
             * creating the employee for a hired candidate.
             */
            $designations = DesignationMaster::where('c_status', 'Y')
                ->orderBy('hierarchy_level')
                ->get();

        } else {

            /*
             * No allowed designation
             */
            $designations = collect();
        }

        // Match the requisition's designation to the SPC list. Names are compared
        // ignoring case, spaces and punctuation; if there is no exact match, a single
        // designation that contains (or is contained in) the name is accepted.
        if (array_key_exists('requisition_title', $prefill)) {
            $norm = fn ($v) => preg_replace('/[^a-z0-9]+/', '', mb_strtolower((string) $v));
            $wanted = $prefill['designation_title'] ?: null;
            $source = $wanted ?: $prefill['requisition_title'];
            $key = $norm($source);

            $match = null;
            if ($key !== '') {
                $match = $designations->first(fn ($d) => $norm($d->c_designation) === $key);

                if (! $match) {
                    $partial = $designations->filter(function ($d) use ($norm, $key) {
                        $n = $norm($d->c_designation);

                        return $n !== '' && (str_contains($key, $n) || str_contains($n, $key));
                    });
                    $match = $partial->count() === 1 ? $partial->first() : null;
                }
            }

            $prefill['n_designation_id'] = $match?->n_designation_id;
            if (! $match) {
                $prefill['designation_note'] = $wanted
                    ? 'The requisition\'s designation "'.$wanted.'" is not in the list you can assign. Please select one.'
                    : 'The requisition has no designation set. Please select one.';
            }
        }

        return view(
            'admin.employees.create',
            compact('designations', 'employees', 'candidate', 'prefill')
                + [
                    'hrDepartments' => HrDepartment::orderBy('name')->get(),
                    // department id => normalised designation titles, used to filter the designation list
                    'deptDesignations' => \App\Models\Hr\Designation::whereNotNull('department_id')->get()
                        ->groupBy('department_id')
                        ->map(fn ($rows) => $rows->map(fn ($d) => preg_replace('/[^a-z0-9]+/', '', mb_strtolower((string) $d->title)))->unique()->values())
                        ->all(),
                ]
        );
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'c_employee_code' => [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Za-z0-9_-]+$/',
                'unique:employee_masters,c_employee_code',
            ],

            'c_employee_name' => 'required|string|max:255',
            'c_employee_address' => 'nullable|string|max:500',

            // A work email is needed for the HR profile, so it is required when
            // the employee is being created from a hired candidate.
            'c_employee_email' => ($request->filled('candidate_id') ? 'required' : 'nullable').'|email|max:255|unique:employee_masters,c_employee_email|unique:employee_masters,c_username',

            'candidate_id' => 'nullable|integer',

            'n_employee_phone' => 'nullable|regex:/^[6-9]\d{9}$/',

            'n_designation_id' => 'required|exists:designation_masters,n_designation_id',
            'reporting_to' => 'nullable|exists:employee_masters,n_employee_id',

            'c_status' => 'required|in:Y,N',

            'account_number' => 'nullable|digits_between:8,18',

            'ifsc_code' => 'nullable|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',

            'bank_name' => 'nullable|string|max:255',

            'branch_name' => 'nullable|string|max:255',

            // HR-facing fields — the SPC employee form is now the single
            // place these are captured; EmployeeHrSyncService pushes them
            // into the HR module.
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'personal_email' => 'nullable|email|max:255',
            'city' => 'nullable|string|max:100',
            'department_id' => 'nullable|integer',
            'date_of_joining' => 'nullable|date',

        ], [

            'c_employee_code.regex' => 'Employee Code can contain only letters, numbers, hyphens (-), and underscores (_).',

            'c_employee_name.required' => 'Employee Name is required.',

            'c_employee_email.email' => 'Please enter a valid email address.',
            'c_employee_email.unique' => 'This Email/Username already exists.',

            'n_employee_phone.regex' => 'Please enter a valid 10-digit mobile number.',

            'n_designation_id.required' => 'Please select a designation.',

            'c_status.required' => 'Please select employee status.',

            'account_number.required' => 'Account Number is required.',
            'account_number.digits_between' => 'Account Number must be between 8 and 18 digits.',

            'ifsc_code.required' => 'IFSC Code is required.',
            'ifsc_code.regex' => 'Please enter a valid IFSC Code.',

            'bank_name.required' => 'Bank Name is required.',
            'branch_name.required' => 'Branch Name is required.',
        ]);

        DB::beginTransaction();

        try {

            // Employee
            $employee = EmployeeMaster::create([
                'c_employee_code' => $validated['c_employee_code'],
                'c_username' => $validated['c_employee_code'],
                'c_password' => Hash::make('Password@123'),
                'c_employee_name' => $validated['c_employee_name'],
                'c_employee_address' => $validated['c_employee_address'] ?? null,
                'c_employee_email' => $validated['c_employee_email'] ?? null,
                'n_employee_phone' => $validated['n_employee_phone'] ?? null,
                'n_designation_id' => $validated['n_designation_id'] ?? null,
                'reporting_to' => $validated['reporting_to'] ?? null,
                'c_status' => $validated['c_status'],

                // FCA / Tele Caller start as associates (not employees)
                // until promoted to Farm Care Officer.
                'engagement_type' => EmployeeMaster::startsAsAssociate(
                    DesignationMaster::find($validated['n_designation_id'])?->identifier
                ) ? EmployeeMaster::TYPE_ASSOCIATE : EmployeeMaster::TYPE_EMPLOYEE,

                // HR-facing fields
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'personal_email' => $validated['personal_email'] ?? null,
                'city' => $validated['city'] ?? null,
                'department_id' => $validated['department_id'] ?? null,
                'date_of_joining' => $validated['date_of_joining'] ?? null,
                'bank_name' => $validated['bank_name'] ?? null,
                'bank_account_number' => $validated['account_number'] ?? null,
                'bank_ifsc' => $validated['ifsc_code'] ?? null,
                // HR access tier (employee/manager/hr_admin/super_admin) is no
                // longer picked here — EmployeeHrSyncService derives it from
                // this person's actual SPC role and reporting structure.
            ]);

            // Bank Details

            // KycSubmission::create([
            //     'n_employee_id' => $employee->n_employee_id,
            //     'bank_name' => $validated['bank_name'],
            //     'bank_branch' => $validated['branch_name'],
            //     'account_number' => $validated['account_number'],
            //     'ifsc_code' => $validated['ifsc_code'],
            //     'document_path' => '',
            //     'status' => 'Active',
            // ]);

            DB::commit();

            // Mirror this employee into the HR module (spc_hr database) so
            // it shows up there without a separate "Add Employee" step.
            // This is deliberately outside the SPC transaction above (it's
            // a different database) and deliberately non-fatal: if the HR
            // database is unreachable, the SPC employee is still created,
            // and the sync will catch up next time this record is saved.
            $hrEmployee = null;
            try {
                $hrEmployee = EmployeeHrSyncService::sync($employee);
            } catch (\Throwable $e) {
                report($e);

                return redirect()
                    ->route('admin.employees.index')
                    ->with('warning', 'Employee created, but could not be synced to the HR module: '.$e->getMessage());
            }

            // Created from a hired candidate: link them, which moves the candidate
            // from the Candidates / Onboarding tabs to History in Recruitment.
            if (! empty($validated['candidate_id']) && $hrEmployee) {
                try {
                    \App\Models\Hr\Candidate::where('id', $validated['candidate_id'])
                        ->where('stage', 'hired')->whereNull('converted_employee_id')
                        ->first()?->update(['converted_employee_id' => $hrEmployee->id]);

                    return redirect()
                        ->route('hr.recruitment.index', ['tab' => 'history'])
                        ->with('status', $employee->c_employee_name.' is now an employee and has moved to History.');
                } catch (\Throwable $e) {
                    report($e);
                }
            }

            return redirect()
                ->route('admin.employees.index')
                ->with('success', 'Employee created successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function edit(EmployeeMaster $employee)
    {
        $this->authorizeEmployeeAccess($employee);

        $designations = DesignationMaster::where('c_status', 'Y')->get();
        $employees = EmployeeMaster::where('c_status', 'Y')
            ->where('n_employee_id', '!=', $employee->n_employee_id)
            ->orderBy('c_employee_name')
            ->get();

        $kyc = KycSubmission::where('n_employee_id', $employee->n_employee_id)
            ->where('status', 'Active')
            ->first();

        return view('admin.employees.edit', compact('employees', 'employee', 'designations', 'kyc')
            + ['hrDepartments' => HrDepartment::orderBy('name')->get()]);
    }

    public function update(Request $request, EmployeeMaster $employee)
    {
        $this->authorizeEmployeeAccess($employee);

        $validator = Validator::make(
            $request->all(), [
                'c_employee_name' => 'required|string|max:255',
                'c_employee_address' => 'nullable|string|max:500',

                'c_employee_email' => 'nullable|email|max:255|',

                'n_employee_phone' => 'nullable|regex:/^[6-9]\d{9}$/',

                'n_designation_id' => 'required|exists:designation_masters,n_designation_id',

                'reporting_to' => 'nullable|exists:employee_masters,n_employee_id',

                'c_status' => 'required|in:Y,N',

                'account_number' => 'nullable|digits_between:8,18',

                'ifsc_code' => 'nullable|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',

                'bank_name' => 'nullable|string|max:255',

                'branch_name' => 'nullable|string|max:255',

                'date_of_birth' => 'nullable|date|before:today',
                'gender' => 'nullable|in:male,female,other',
                'personal_email' => 'nullable|email|max:255',
                'city' => 'nullable|string|max:100',
                'department_id' => 'nullable|integer',
                'date_of_joining' => 'nullable|date',

                'password' => [
                    'nullable',
                    'confirmed',
                    Password::min(8)->letters()->numbers()->symbols(),
                ],
            ], [
                'c_employee_email.unique' => 'This email already exists.',
                'n_employee_phone.regex' => 'Please enter a valid 10-digit mobile number.',
                'ifsc_code.regex' => 'Please enter a valid IFSC code.',
                'account_number.digits_between' => 'Account number must be between 8 and 18 digits.',
            ]);

        if ($validator->fails()) {
            /*  return back()
                 ->withErrors($validator)
                 ->withInput(); */
            dd($validator->errors()->toArray());
        }

        $validated = $validator->validated();
        DB::beginTransaction();

        try {

            $previousStatus = $employee->c_status;

            EmployeeEditLog::create([
                'n_employee_id' => $employee->n_employee_id,
                'n_pre_designation_id' => $employee->n_designation_id,
                'n_new_designation_id' => $request->n_designation_id,
            ]);

            // Moving an associate to any designation other than FCA / TC
            // (e.g. Farm Care Officer) makes them an employee.
            $newType = $employee->engagement_type;
            if ($employee->isAssociate() && ! EmployeeMaster::startsAsAssociate(
                DesignationMaster::find($request->n_designation_id)?->identifier
            )) {
                $newType = EmployeeMaster::TYPE_EMPLOYEE;
            }

            // Update employee
            $employee->update([
                'engagement_type' => $newType,
                'c_employee_name' => $request->c_employee_name,
                'c_employee_address' => $request->c_employee_address,
                'c_employee_email' => $request->c_employee_email,
                'n_employee_phone' => $request->n_employee_phone,
                'n_designation_id' => $request->n_designation_id,
                'reporting_to' => $request->reporting_to,
                'c_status' => $request->c_status,

                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'personal_email' => $validated['personal_email'] ?? null,
                'city' => $validated['city'] ?? null,
                'department_id' => $validated['department_id'] ?? null,
                'date_of_joining' => $validated['date_of_joining'] ?? $employee->date_of_joining,
                'bank_name' => $validated['bank_name'] ?? null,
                'bank_account_number' => $validated['account_number'] ?? null,
                'bank_ifsc' => $validated['ifsc_code'] ?? null,
                // HR access tier is derived automatically by
                // EmployeeHrSyncService — see the note in store().
            ]);

            // Update password only if entered
            if ($request->filled('password')) {
                $employee->update([
                    'c_password' => Hash::make($request->password),
                ]);
            }

            // Update or create bank details
            // $employee->kycSubmission()->updateOrCreate(
            //     ['n_employee_id' => $employee->n_employee_id],
            //     [
            //         'bank_name' => $request->bank_name,
            //         'bank_branch' => $request->branch_name,
            //         'account_number' => $request->account_number,
            //         'ifsc_code' => $request->ifsc_code,
            //         'document_path' => '',
            //         'status' => 'Active',
            //     ]
            // );

            DB::commit();

            try {
                EmployeeHrSyncService::sync($employee->fresh());
                $this->recordStatusChange($employee->fresh(), $previousStatus, $request->c_status);
            } catch (\Throwable $e) {
                report($e);

                return redirect()
                    ->route('admin.employees.index')
                    ->with('warning', 'Employee updated, but could not be synced to the HR module: '.$e->getMessage());
            }

            return redirect()
                ->route('admin.employees.index')
                ->with('success', 'Employee updated successfully.'
                    .($previousStatus === 'Y' && $request->c_status === 'N'
                        ? ' Their history was saved — open History to add the resignation / termination details.'
                        : ''));

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Promote a Farm Care Adviser / Tele Caller (associate) to Farm Care
     * Officer. They become an employee: designation + login role change,
     * and the normal HR sync now creates their HR record.
     */
    public function promote(Request $request, EmployeeMaster $employee)
    {
        [$mode] = $this->employeeScope();
        abort_unless($mode === 'all', 403, 'Only HR or admin can promote an associate.');

        if (! $employee->isAssociate()) {
            return back()->with('error', $employee->c_employee_name.' is already an employee.');
        }

        $fco = DesignationMaster::where('identifier', 'FCO')->where('c_status', 'Y')->first();

        if (! $fco) {
            return back()->with('error', 'Farm Care Officer designation was not found.');
        }

        $previousDesignation = $employee->designation?->c_designation ?? '—';

        DB::beginTransaction();

        try {
            EmployeeEditLog::create([
                'n_employee_id' => $employee->n_employee_id,
                'n_pre_designation_id' => $employee->n_designation_id,
                'n_new_designation_id' => $fco->n_designation_id,
            ]);

            $employee->update([
                'n_designation_id' => $fco->n_designation_id,
                'engagement_type' => EmployeeMaster::TYPE_EMPLOYEE,
                // Employment starts on the promotion date.
                'date_of_joining' => now()->toDateString(),
            ]);

            // Move their SPC login to the Farm Care Officer role, if they have one.
            $role = \App\Models\Role::where('identifier', 'FCO')->first();
            $admin = \App\Models\Admin::where('n_employee_id', $employee->n_employee_id)->first();

            if ($role && $admin) {
                $admin->syncRoles([$role->name]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->with('error', 'Could not promote: '.$e->getMessage());
        }

        try {
            $hr = EmployeeHrSyncService::sync($employee->fresh());

            if ($hr) {
                \App\Models\Hr\EmployeeHistory::log(
                    $hr->id, 'promotion', 'Promoted to employee',
                    $previousDesignation.' (associate)', $fco->c_designation,
                    EmployeeHrSyncService::actingHrUserId()
                );
            }
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('admin.employees.index')
                ->with('warning', 'Promoted, but could not be synced to the HR module: '.$e->getMessage());
        }

        return redirect()->route('admin.employees.index')
            ->with('success', $employee->c_employee_name.' is now a Farm Care Officer and an employee.'
                .($employee->c_employee_email ? '' : ' Add a work email so they can be linked to HR.'));
    }

    public function destroy($id)
    {
        $employee = EmployeeMaster::findOrFail($id);
        $this->authorizeEmployeeAccess($employee);
        $previousStatus = $employee->c_status;

        // Update employee status to 'D' (Deleted)

        $employee->update([
            'c_status' => 'D',
        ]);

        // Soft delete the employee by setting the deleted_at timestamp

        $employee->delete();

        // Keep the person's history: mark them exited in the HR module and
        // make sure an exit record exists (HR completes the details later
        // from Employee History > "Former employees").
        try {
            EmployeeHrSyncService::sync($employee);
            $this->recordStatusChange($employee, $previousStatus, 'D');
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee deleted successfully. Their history is kept under Status > Former employees.');
    }

    /**
     * Keep HR history in step with SPC status changes made on this screen:
     * going inactive/deleted creates (or completes) the exit record;
     * coming back to Active reinstates and keeps the earlier exit on file.
     */
    protected function recordStatusChange(EmployeeMaster $employee, ?string $old, ?string $new): void
    {
        if ($old === $new) {
            return;
        }

        $hr = HrEmployee::where('employee_master_id', $employee->n_employee_id)->first();

        if (! $hr) {
            return;
        }

        $by = EmployeeHrSyncService::actingHrUserId();

        if ($new !== 'Y') {
            // Inactive or deleted. Idempotent: does nothing if an exit
            // record already exists (e.g. inactive first, deleted later).
            $onNotice = EmployeeExit::where('employee_id', $hr->id)->where('status', 'on_notice')->first();

            $onNotice
                ? EmployeeExitService::finalize($onNotice, $by)
                : EmployeeExitService::ensureExitRecord($hr, $by);
        } elseif ($old !== 'Y' && $new === 'Y') {
            EmployeeExitService::reinstate($hr, $by, 'Re-activated from SPC Employee Records', 'exited');
        }
    }

    public function getReportingManagers($designationId)
    {
        $designation = DesignationMaster::findOrFail($designationId);

        /*
         * Reporting managers are now the employees holding the exact
         * parent designation in this designation's own branch of the org
         * chart (parent_designation_id), not just "anyone one hierarchy
         * level up" — which used to pull in people from unrelated
         * branches (e.g. Finance or Marketing) at the same level.
         */
        if (! $designation->parent_designation_id) {
            return response()->json([]);
        }

        $reportingEmployees = EmployeeMaster::join(
            'designation_masters',
            'employee_masters.n_designation_id',
            '=',
            'designation_masters.n_designation_id'
        )
            ->where('designation_masters.n_designation_id', $designation->parent_designation_id)
            ->where('employee_masters.c_status', 'Y')
            ->select()
            ->get();

        return response()->json($reportingEmployees);
    }
}
