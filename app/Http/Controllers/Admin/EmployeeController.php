<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DesignationMaster;
use App\Models\EmployeeEditLog;
use App\Models\EmployeeMaster;
use App\Models\KycSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EmployeeController extends Controller
{
    public function search(Request $request)
    {
        session([
            'employee_search' => $request->employee_search,
            'designation_filter' => $request->n_designation_id,
        ]);

        return redirect()->route('admin.employees.index');
    }

    public function clearSearch()
    {
        session()->forget([
            'employee_search',
            'designation_filter',
        ]);

        return redirect()->route('admin.employees.index');
    }

    public function index(Request $request)
    {
        $query = EmployeeMaster::with(['designation'])
            ->whereNull('deleted_at');

        // Get filters from session
        $search = session('employee_search');
        $designation = session('designation_filter');

        /*
         * Get logged-in user's role identifier from Spatie.
         *
         * Example:
         * Farm Care Officer -> FCO
         * National Sales Head -> NSH
         */
        $role = auth()->user()->roles->first();

        $userDesignation = null;

        if ($role) {
            $userDesignation = DesignationMaster::where(
                'identifier',
                $role->identifier
            )
                ->where('c_status', 'Y')
                ->first();
        }

        /*
         * Get employees only from designations below
         * the logged-in user's designation.
         */
        if ($userDesignation) {
            $query->whereHas('designation', function ($q) use ($userDesignation) {
                $q->where('hierarchy_level', '>', $userDesignation->hierarchy_level)
                    ->where('c_status', 'Y');
            });
        }

        // Search by employee code or employee name
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('c_employee_code', 'LIKE', "%{$search}%")
                    ->orWhere('c_employee_name', 'LIKE', "%{$search}%");
            });
        }

        /*
         * Apply designation filter only if it belongs
         * to the logged-in user's allowed hierarchy.
         */
        if (! empty($designation) && $userDesignation) {
            $query->whereHas('designation', function ($q) use ($designation, $userDesignation) {
                $q->where('n_designation_id', $designation)
                    ->where(
                        'hierarchy_level',
                        '>',
                        $userDesignation->hierarchy_level
                    );
            });
        }

        $employees = $query->paginate(10);

        /*
         * Designation dropdown:
         * Show only designations below the logged-in user.
         */
        $designations = DesignationMaster::where('c_status', 'Y')
            ->when($userDesignation, function ($q) use ($userDesignation) {
                $q->where(
                    'hierarchy_level',
                    '>',
                    $userDesignation->hierarchy_level
                );
            })
            ->orderBy('hierarchy_level')
            ->get();

        /*
         * Employee autocomplete:
         * Show only employees from allowed designations.
         */
        $employeesForSearch = EmployeeMaster::select(
            'n_employee_id',
            'c_employee_name',
            'c_employee_code'
        )
            ->where('c_status', 'Y')
            ->when($userDesignation, function ($q) use ($userDesignation) {
                $q->whereHas('designation', function ($designationQuery) use ($userDesignation) {
                    $designationQuery->where(
                        'hierarchy_level',
                        '>',
                        $userDesignation->hierarchy_level
                    );
                });
            })
            ->orderBy('c_employee_name')
            ->get();

        return view(
            'admin.employees.index',
            compact(
                'employees',
                'designations',
                'employeesForSearch'
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

    public function create()
    {
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

        } else {

            /*
             * No allowed designation
             */
            $designations = collect();
        }

        return view(
            'admin.employees.create',
            compact('designations', 'employees')
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

            'c_employee_email' => 'nullable|email|max:255|unique:employee_masters,c_employee_email|unique:employee_masters,c_username',

            'n_employee_phone' => 'nullable|regex:/^[6-9]\d{9}$/',

            'n_designation_id' => 'required|exists:designation_masters,n_designation_id',
            'reporting_to' => 'nullable|exists:employee_masters,n_employee_id',

            'c_status' => 'required|in:Y,N',

            'account_number' => 'nullable|digits_between:8,18',

            'ifsc_code' => 'nullable|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',

            'bank_name' => 'nullable|string|max:255',

            'branch_name' => 'nullable|string|max:255',

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

        $designations = DesignationMaster::where('c_status', 'Y')->get();
        $employees = EmployeeMaster::where('c_status', 'Y')
            ->where('n_employee_id', '!=', $employee->n_employee_id)
            ->orderBy('c_employee_name')
            ->get();

        $kyc = KycSubmission::where('n_employee_id', $employee->n_employee_id)
            ->where('status', 'Active')
            ->first();

        return view('admin.employees.edit', compact('employees', 'employee', 'designations', 'kyc'));
    }

    public function update(Request $request, EmployeeMaster $employee)
    {
        $validated = $request->validate([
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
        DB::beginTransaction();

        try {

            EmployeeEditLog::create([
                'n_employee_id' => $employee->n_employee_id,
                'n_pre_designation_id' => $employee->n_designation_id,
                'n_new_designation_id' => $request->n_designation_id,
            ]);

            // Update employee
            $employee->update([
                'c_employee_name' => $request->c_employee_name,
                'c_employee_address' => $request->c_employee_address,
                'c_employee_email' => $request->c_employee_email,
                'n_employee_phone' => $request->n_employee_phone,
                'n_designation_id' => $request->n_designation_id,
                'reporting_to' => $request->reporting_to,
                'c_status' => $request->c_status,
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

            return redirect()
                ->route('admin.employees.index')
                ->with('success', 'Employee updated successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $employee = EmployeeMaster::findOrFail($id);

        // Update employee status to 'D' (Deleted)

        $employee->update([
            'c_status' => 'D',
        ]);

        // Soft delete the employee by setting the deleted_at timestamp

        $employee->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee deleted successfully.');
    }

    public function getReportingManagers($designationId)
    {
        $designation = DesignationMaster::findOrFail($designationId);

        /*  $employees = EmployeeMaster::join(
                 'designation_masters',
                 'employee_masters.n_designation_id',
                 '=',
                 'designation_masters.n_designation_id'
             )
             ->where('designation_masters.hierarchy_level', '<', $designation->hierarchy_level)
             ->where('employee_masters.c_status', 'Y')
             ->select(
                 'employee_masters.n_employee_id',
                 'employee_masters.c_employee_name',
                 'designation_masters.c_designation'
             )
             ->orderBy('designation_masters.hierarchy_level')
             ->get(); */
        $reportingEmployees = EmployeeMaster::join(
            'designation_masters',
            'employee_masters.n_designation_id',
            '=',
            'designation_masters.n_designation_id'
        )
            ->where('designation_masters.hierarchy_level', $designation->hierarchy_level - 1)
            ->select()
            ->get();

        return response()->json($reportingEmployees);
    }
}
