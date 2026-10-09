<?php

namespace App\Http\Controllers\Hr;

use App\Services\Hr\AssociateScope;
use App\Exports\TableExport;
use App\Models\Hr\AuditLog;
use App\Models\Hr\Employee;
use App\Models\Hr\PayrollRun;
use App\Models\Hr\Notification;
use App\Models\Hr\Payslip;
use App\Models\Hr\PayslipRequest;
use App\Models\Hr\SalaryStructure;
use App\Models\Hr\SystemSetting;
use App\Services\Hr\PayrollCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $module = $this->abortUnlessModuleAllowed('payroll');
        $employee = $this->currentEmployee();
        $role = $this->currentRole();

        $viewedEmployee = $employee;
        $directory = collect();

        if ($this->canManagePayroll()) {
            $directory = Employee::with('user')->get();
            if ($request->filled('employee')) {
                $viewedEmployee = Employee::with('user')->find($request->integer('employee')) ?? $employee;
            } elseif (! $viewedEmployee) {
                $viewedEmployee = $directory->first();
            }
        }

        $salaryStructure = $viewedEmployee ? $viewedEmployee->salaryStructures()->orderByDesc('effective_from')->first() : null;
        // $payslips = $viewedEmployee ? $viewedEmployee->payslips()->with('payrollRun')->orderByDesc('id')->get() : collect();
        $payslips = $viewedEmployee
    ? Payslip::query()
        ->where('employee_id', $viewedEmployee->id)
        ->with('payrollRun')
        ->orderByDesc('id')
        ->get()
    : collect();

        // Everyone else only ever sees payslips of paid runs, and needs Finance's approval to open one.
        if (! $this->canManagePayroll()) {
            $payslips = $payslips->filter(fn ($p) => optional($p->payrollRun)->status === 'paid')->values();
        }
        $requestMap = $payslips->isEmpty() ? collect() : PayslipRequest::whereIn('payslip_id', $payslips->pluck('id'))
            ->orderBy('id')->get()->keyBy('payslip_id');

        $runs = collect();
        $runsByDepartment = collect();
        if ($this->isHrOrAbove()) {
            $runs = PayrollRun::orderByDesc('year')->orderByDesc('month')->get();
            $latestRun = $runs->first();
            if ($latestRun) {
                $runsByDepartment = Payslip::where('payroll_run_id', $latestRun->id)
                    ->join('employees', 'employees.id', '=', 'payslips.employee_id')
                    ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
                    ->selectRaw('COALESCE(departments.name, "Unassigned") as department, count(*) as headcount, sum(payslips.gross_pay) as gross, sum(payslips.net_pay) as net')
                    ->groupBy('departments.name')
                    ->orderByDesc('gross')
                    ->get();
            }
        }

        // HR only: Salary Structure / Run Payroll / Payslip History tabs.
        $canManagePayroll = $this->canManagePayroll();
        $superAdminData = [];
        if ($canManagePayroll) {
            $activeEmployees = Employee::with(['user', 'currentSalaryStructure'])
                ->whereIn('employment_status', ['active', 'on_notice'])
                ->where(fn ($q) => AssociateScope::exclude($q))   // associates are paid commission, not salary
                ->orderBy('employee_code')->get();

            $lastRun = $runs->first();

            $slipQuery = Payslip::with(['employee.user', 'payrollRun'])->orderByDesc('id');
            $filterRun = $request->integer('run') ?: null;
            if ($filterRun) {
                $slipQuery->where('payroll_run_id', $filterRun);
            }

            // Preview: nothing is saved, it only shows what a run would produce.
            $preview = null;
            $activeTab = $request->query('tab', 'salary');
            if ($request->filled('preview_month') && $request->filled('preview_year')) {
                $pm = (int) $request->query('preview_month');
                $py = (int) $request->query('preview_year');
                if ($pm >= 1 && $pm <= 12 && $py >= 2020 && $py <= 2100) {
                    $preview = app(PayrollCalculator::class)->calculate($pm, $py);
                    $preview['already_run'] = PayrollRun::where('month', $pm)->where('year', $py)->first();
                    $activeTab = 'run';
                }
            }

            $superAdminData = [
                'activeEmployees' => $activeEmployees,
                'activeEmployeeCount' => $activeEmployees->count(),
                'lastCycleNetPay' => $lastRun ? (float) $lastRun->total_net ?: $lastRun->payslips()->sum('net_pay') : 0,
                'cyclesFinalized' => $runs->count(),
                'currentCycleMonth' => now()->format('F'),
                'totalPayslips' => Payslip::count(),
                'allPayslips' => $slipQuery->paginate(20, ['*'], 'payslipsPage')->withQueryString(),
                'filterRun' => $filterRun,
                'preview' => $preview,
                'activeTab' => $filterRun ? 'history' : $activeTab,
                'previewMonth' => $request->query('preview_month', now()->subMonth()->month),
                'previewYear' => $request->query('preview_year', now()->subMonth()->year),
            ];
        }

        // COO / MD / Finance: the approval queue (and Finance's payslip requests + payslip history).
        $isCoo = $this->hasWorkflowRole('coo');
        $isMd = $this->hasWorkflowRole('md');
        $isFinance = $this->hasWorkflowRole('finance');
        $workflowRuns = collect();
        $pendingRequests = collect();
        $financeSlips = null;
        if ($isCoo || $isMd || $isFinance) {
            $workflowRuns = PayrollRun::orderByDesc('year')->orderByDesc('month')->limit(12)->get();
        }
        if ($isFinance) {
            $pendingRequests = PayslipRequest::with(['payslip.payrollRun', 'employee.user'])
                ->where('status', 'pending')->orderBy('id')->get();
            $financeSlips = Payslip::with(['employee.user', 'payrollRun'])->orderByDesc('id')
                ->paginate(20, ['*'], 'payslipsPage')->withQueryString();
        }

        return view('hr.modules.payroll', array_merge($this->baseViewData(), [
            'module' => $module,
            'moduleKey' => 'payroll',
            'viewedEmployee' => $viewedEmployee,
            'directory' => $directory,
            'salaryStructure' => $salaryStructure,
            'payslips' => $payslips,
            'runs' => $runs,
            'latestRun' => $runs->first(),
            'runsByDepartment' => $runsByDepartment,
            'canManagePayroll' => $canManagePayroll,
            'requestMap' => $requestMap,
            'isCoo' => $isCoo,
            'isMd' => $isMd,
            'isFinance' => $isFinance,
            'workflowRuns' => $workflowRuns,
            'pendingRequests' => $pendingRequests,
            'financeSlips' => $financeSlips,
        ], $superAdminData));
    }

    /** Process a payroll cycle (HR, COO, MD and Finance). */
    public function runPayroll(Request $request)
    {
        $this->abortUnlessModuleAllowed('payroll');
        abort_unless($this->canManagePayroll(), 403);

        $data = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2100',
            'notes' => 'nullable|string|max:255',
        ]);

        // never pay a month that has not started
        if (Carbon::create($data['year'], $data['month'], 1)->gt(now()->endOfMonth())) {
            return back()->with('status', 'You cannot run payroll for a future month.');
        }

        if (PayrollRun::where('month', $data['month'])->where('year', $data['year'])->exists()) {
            return back()->with('status', 'Payroll for that month has already been run. Discard it first if it needs to be redone.');
        }

        try {
            $run = app(PayrollCalculator::class)->process((int) $data['month'], (int) $data['year'], $this->currentUser()->id, $data['notes'] ?? null);
        } catch (\RuntimeException $e) {
            return back()->with('status', $e->getMessage());
        }

        $this->audit('PAYROLL_RUN', $run->id, null, [
            'period' => $run->monthLabel(), 'employees' => $run->employee_count, 'net' => $run->total_net,
        ]);

        return redirect()->route('hr.payroll.index', ['tab' => 'run'])
            ->with('status', 'Payroll for '.$run->monthLabel().' processed for '.$run->employee_count.' employees. Review it, then send it to the COO for approval.');
    }

    /** HR discards a run that has not been sent for approval yet (or was returned); Finance discards once it reaches them. */
    public function discard(PayrollRun $run)
    {
        $this->abortUnlessModuleAllowed('payroll');
        $stage = $run->approval_stage ?: 'draft';
        abort_unless(
            ($this->canManagePayroll() && in_array($stage, ['draft', 'returned'], true))
            || ($this->hasWorkflowRole('finance') && $stage === 'pending_finance'),
            403
        );

        $label = $run->monthLabel();

        try {
            app(PayrollCalculator::class)->discard($run);
        } catch (\RuntimeException $e) {
            return back()->with('status', $e->getMessage());
        }

        $this->audit('PAYROLL_DISCARD', $run->id, ['period' => $label], null);

        return redirect()->route('hr.payroll.index', ['tab' => 'run'])->with('status', 'Payroll for '.$label.' was discarded. HR can run it again.');
    }

    /** Finance marks a fully approved run as paid. */
    public function markPaid(PayrollRun $run)
    {
        $this->abortUnlessModuleAllowed('payroll');
        abort_unless($this->hasWorkflowRole('finance') && $run->approval_stage === 'pending_finance', 403);

        try {
            app(PayrollCalculator::class)->markPaid($run);
        } catch (\RuntimeException $e) {
            return back()->with('status', $e->getMessage());
        }

        $run->update(['approval_stage' => 'completed']);
        $this->audit('PAYROLL_PAID', $run->id, ['status' => 'processed'], ['status' => 'paid']);

        return back()->with('status', 'Payroll for '.$run->monthLabel().' marked as paid.');
    }

    /** HR sends a processed run to the COO. */
    public function submit(PayrollRun $run)
    {
        $this->abortUnlessModuleAllowed('payroll');
        abort_unless($this->canManagePayroll(), 403);

        if (! in_array($run->approval_stage ?: 'draft', ['draft', 'returned'], true) || $run->status !== 'processed') {
            return back()->with('status', 'This payroll cannot be sent for approval right now.');
        }

        $run->update([
            'approval_stage' => 'pending_coo',
            'submitted_by' => $this->currentUser()->id,
            'submitted_at' => now(),
            'coo_by' => null, 'coo_at' => null, 'md_by' => null, 'md_at' => null,
            'returned_by' => null, 'returned_at' => null, 'workflow_remarks' => null,
        ]);

        $this->audit('PAYROLL_SUBMIT', $run->id, null, ['stage' => 'pending_coo']);
        $this->notifyRole('coo', 'Payroll for '.$run->monthLabel().' is waiting for your approval.');

        return back()->with('status', 'Payroll for '.$run->monthLabel().' sent to the COO for approval.');
    }

    /** COO approves -> MD. */
    public function approveCoo(PayrollRun $run)
    {
        $this->abortUnlessModuleAllowed('payroll');
        abort_unless($this->hasWorkflowRole('coo') && $run->approval_stage === 'pending_coo', 403);

        $run->update(['approval_stage' => 'pending_md', 'coo_by' => $this->currentUser()->id, 'coo_at' => now()]);

        $this->audit('PAYROLL_COO_APPROVE', $run->id, ['stage' => 'pending_coo'], ['stage' => 'pending_md']);
        $this->notifyRole('md', 'Payroll for '.$run->monthLabel().' was approved by the COO and needs your approval.');

        return back()->with('status', 'Approved. Payroll for '.$run->monthLabel().' is now with the MD.');
    }

    /** MD approves -> Finance. */
    public function approveMd(PayrollRun $run)
    {
        $this->abortUnlessModuleAllowed('payroll');
        abort_unless($this->hasWorkflowRole('md') && $run->approval_stage === 'pending_md', 403);

        $run->update(['approval_stage' => 'pending_finance', 'md_by' => $this->currentUser()->id, 'md_at' => now()]);

        $this->audit('PAYROLL_MD_APPROVE', $run->id, ['stage' => 'pending_md'], ['stage' => 'pending_finance']);
        $this->notifyRole('finance', 'Payroll for '.$run->monthLabel().' is approved. Please process the payment and mark it paid.');

        return back()->with('status', 'Approved. Payroll for '.$run->monthLabel().' is now with Finance.');
    }

    /** COO or MD sends a run back to HR with a reason. */
    public function returnToHr(Request $request, PayrollRun $run)
    {
        $this->abortUnlessModuleAllowed('payroll');
        $stage = $run->approval_stage;
        abort_unless(
            ($stage === 'pending_coo' && $this->hasWorkflowRole('coo')) || ($stage === 'pending_md' && $this->hasWorkflowRole('md')),
            403
        );

        $data = $request->validate(['remarks' => 'required|string|max:255']);

        $run->update([
            'approval_stage' => 'returned',
            'returned_by' => $this->currentUser()->id,
            'returned_at' => now(),
            'workflow_remarks' => $data['remarks'],
        ]);

        $this->audit('PAYROLL_RETURNED', $run->id, ['stage' => $stage], ['stage' => 'returned', 'remarks' => $data['remarks']]);
        $this->notifyRole('hr', 'Payroll for '.$run->monthLabel().' was returned: '.\Illuminate\Support\Str::limit($data['remarks'], 120));

        return back()->with('status', 'Payroll for '.$run->monthLabel().' returned to HR.');
    }

    /** Employee asks Finance to release one of their payslips. */
    public function requestPayslip(Request $request, Payslip $payslip)
    {
        $this->abortUnlessModuleAllowed('payroll');
        $employee = $this->currentEmployee();
        abort_unless($employee && $payslip->employee_id === $employee->id, 403);

        $data = $request->validate(['reason' => 'nullable|string|max:255']);
        $payslip->loadMissing('payrollRun');

        if (optional($payslip->payrollRun)->status !== 'paid') {
            return back()->with('status', 'This payslip can be requested once the month\'s payroll is paid.');
        }

        $latest = PayslipRequest::where('payslip_id', $payslip->id)->orderByDesc('id')->first();
        if ($latest && in_array($latest->status, ['pending', 'approved'], true)) {
            return back()->with('status', $latest->status === 'pending' ? 'You already have a pending request for this payslip.' : 'This payslip is already available to you.');
        }

        PayslipRequest::create([
            'payslip_id' => $payslip->id,
            'employee_id' => $employee->id,
            'status' => 'pending',
            'reason' => $data['reason'] ?? null,
            'created_at' => now(),
        ]);

        $this->notifyRole('finance', ($this->currentUser()->name ?? 'An employee').' requested the '.$payslip->payrollRun->monthLabel().' payslip.');

        return back()->with('status', 'Payslip requested. Finance will make it available or reply with a reason.');
    }

    /** Finance makes the payslip available for printing, or rejects the request. */
    public function decideRequest(Request $request, PayslipRequest $payslipRequest)
    {
        $this->abortUnlessModuleAllowed('payroll');
        abort_unless($this->hasWorkflowRole('finance'), 403);

        $data = $request->validate([
            'decision' => 'required|in:approve,reject',
            'remarks' => 'nullable|string|max:255|required_if:decision,reject',
        ]);

        if ($payslipRequest->status !== 'pending') {
            return back()->with('status', 'That request has already been decided.');
        }

        $approved = $data['decision'] === 'approve';
        $payslipRequest->update([
            'status' => $approved ? 'approved' : 'rejected',
            'remarks' => $data['remarks'] ?? null,
            'decided_by' => $this->currentUser()->id,
            'decided_at' => now(),
        ]);

        $payslipRequest->loadMissing(['payslip.payrollRun', 'employee']);
        $period = optional(optional($payslipRequest->payslip)->payrollRun)->monthLabel() ?? 'requested';
        if ($payslipRequest->employee) {
            $this->notifyUser($payslipRequest->employee->user_id, $approved
                ? 'Your '.$period.' payslip is now available to view and print.'
                : 'Your '.$period.' payslip request was rejected'.($payslipRequest->remarks ? ': '.$payslipRequest->remarks : '.'));
        }

        return back()->with('status', $approved ? 'Payslip made available to the employee.' : 'Payslip request rejected.');
    }

    /** Excel payroll register for a run (HR, COO, MD and Finance). */
    public function register(PayrollRun $run)
    {
        $this->abortUnlessModuleAllowed('payroll');
        abort_unless($this->canManagePayroll() || $this->hasWorkflowRole('coo') || $this->hasWorkflowRole('md') || $this->hasWorkflowRole('finance'), 403);

        $slips = $run->payslips()->with(['employee.user', 'employee.department', 'employee.designation'])->get()
            ->sortBy(fn ($p) => $p->employee->employee_code);

        $headings = ['Emp Code', 'Name', 'Department', 'Designation', 'Days in Month', 'Paid Days', 'LOP Days',
            'Basic', 'HRA', 'Allowances', 'Variable', 'Incentive', 'Gross', 'PF', 'ESI', 'Professional Tax', 'TDS',
            'Other Deductions', 'Total Deductions', 'Net Pay', 'Employer PF', 'Employer ESI', 'PF Admin + EDLI',
            'Cost to Company', 'Bank', 'Account No', 'IFSC'];

        $rows = $slips->map(function ($p) {
            $e = $p->employee;
            $ded = $p->pf_deduction + $p->esi_deduction + $p->professional_tax + $p->tds_deduction + $p->other_deductions;

            return [
                $e->employee_code, $e->user->name ?? '', $e->department->name ?? '', $e->designation->title ?? '',
                (int) $p->days_in_month, (float) $p->paid_days, (float) $p->lop_days,
                (float) $p->basic_earned, (float) $p->hra_earned, (float) $p->allowances_earned, (float) $p->variable_earned,
                (float) $p->incentive_pay, (float) $p->gross_pay, (float) $p->pf_deduction, (float) $p->esi_deduction,
                (float) $p->professional_tax, (float) $p->tds_deduction, (float) $p->other_deductions, round($ded, 2),
                (float) $p->net_pay, (float) $p->employer_pf, (float) $p->employer_esi, (float) $p->employer_admin_charges,
                round($p->gross_pay + $p->employer_pf + $p->employer_esi + $p->employer_admin_charges, 2),
                $e->bank_name, (string) $e->bank_account_number, $e->bank_ifsc,
            ];
        })->values()->all();

        $money = ['H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X'];

        return Excel::download(
            new TableExport($headings, $rows, ['Z', 'AA'], $money),
            sprintf('payroll-register-%04d-%02d.xlsx', $run->year, $run->month)
        );
    }

    /** Bank transfer sheet (CSV) with net pay per employee. Finance only. */
    public function bankFile(PayrollRun $run)
    {
        $this->abortUnlessModuleAllowed('payroll');
        abort_unless($this->hasWorkflowRole('finance'), 403);

        $slips = $run->payslips()->with('employee.user')->get()->sortBy(fn ($p) => $p->employee->employee_code);
        $label = $run->monthLabel();

        return response()->streamDownload(function () use ($slips, $label) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Employee Code', 'Name', 'Bank', 'Account Number', 'IFSC', 'Net Pay', 'Narration']);
            foreach ($slips as $p) {
                $e = $p->employee;
                fputcsv($out, [
                    $e->employee_code, $e->user->name ?? '', $e->bank_name, $e->bank_account_number, $e->bank_ifsc,
                    number_format((float) $p->net_pay, 2, '.', ''), 'Salary '.$label,
                ]);
            }
            fclose($out);
        }, sprintf('salary-transfer-%04d-%02d.csv', $run->year, $run->month), ['Content-Type' => 'text/csv']);
    }

    public function updateSalary(Request $request, Employee $employee)
    {
        $this->abortUnlessModuleAllowed('payroll');
        // Compensation changes are HR only.
        abort_unless($this->canManagePayroll(), 403);

        if (AssociateScope::isAssociateMaster($employee->employee_master_id)) {
            return back()->with('status', 'Farm Care Advisers and Tele Callers are associates and are paid commission, not salary.');
        }

        $data = $request->validate([
            'basic' => 'required|numeric|min:0',
            'hra' => 'required|numeric|min:0',
            'other_allowances' => 'required|numeric|min:0',
            'variable_pay' => 'required|numeric|min:0',
            'other_deduction' => 'nullable|numeric|min:0',
            'tds_monthly_override' => 'nullable|numeric|min:0',
            'effective_from' => 'nullable|date',
        ]);

        $effectiveFrom = Carbon::parse($data['effective_from'] ?? now()->toDateString())->toDateString();
        unset($data['effective_from']);

        $data['other_deduction'] = $data['other_deduction'] ?? 0;
        $data['tds_monthly_override'] = ($data['tds_monthly_override'] ?? '') === '' ? null : $data['tds_monthly_override'];
        $data['pf_applicable'] = $request->boolean('pf_applicable');
        $data['esi_applicable'] = $request->boolean('esi_applicable');
        $data['pt_applicable'] = $request->boolean('pt_applicable');

        $gross = $data['basic'] + $data['hra'] + $data['other_allowances'] + $data['variable_pay'];

        $current = $employee->salaryStructures()->orderByDesc('effective_from')->orderByDesc('id')->first();

        if ($current && Carbon::parse($effectiveFrom)->lt(Carbon::parse($current->effective_from))) {
            return back()->with('status', 'The effective date cannot be earlier than the current structure ('.Carbon::parse($current->effective_from)->format('d M Y').').');
        }

        if ($current && Carbon::parse($current->effective_from)->toDateString() === $effectiveFrom) {
            $current->update(array_merge($data, ['gross_monthly' => $gross]));
        } else {
            if ($current) {
                $current->update(['effective_to' => Carbon::parse($effectiveFrom)->subDay()->toDateString()]);
            }
            SalaryStructure::create(array_merge($data, [
                'employee_id' => $employee->id,
                'effective_from' => $effectiveFrom,
                'gross_monthly' => $gross,
                'created_at' => now(),
            ]));
        }

        $this->audit('SALARY_UPDATE', $employee->id, $current ? $current->only(['basic', 'hra', 'other_allowances', 'variable_pay', 'gross_monthly']) : null, $data + ['gross_monthly' => $gross, 'effective_from' => $effectiveFrom]);

        return back()->with('status', 'Salary structure saved for '.$employee->employee_code.'.');
    }

    public function payslip(Payslip $payslip)
    {
        $this->abortUnlessModuleAllowed('payroll');
        $employee = $this->currentEmployee();

        $own = $employee && $payslip->employee_id === $employee->id;
        $released = $own && PayslipRequest::where('payslip_id', $payslip->id)->where('status', 'approved')->exists();
        abort_unless($this->canManagePayroll() || $this->hasWorkflowRole('finance') || $released, 403,
            $own ? 'Request this payslip from Finance first.' : null);

        $payslip->load(['employee.user', 'employee.department', 'employee.designation', 'payrollRun']);

        return view('hr.modules.payslip-print', [
            'payslip' => $payslip,
            'companyName' => SystemSetting::where('setting_key', 'company_name')->value('setting_value') ?: 'SPC Enterprises',
        ]);
    }

    /** Salary structures, payroll runs and payslip history belong to HR (hr_admin) only. */
    private function canManagePayroll(): bool
    {
        return $this->currentRole() === 'hr_admin';
    }

    /** COO / MD / Finance are matched by designation title (config/payroll_workflow.php). */
    private function hasWorkflowRole(string $key, ?Employee $employee = null): bool
    {
        $employee ??= $this->currentEmployee();
        if (! $employee) {
            return false;
        }

        $title = strtolower(trim($employee->designation->title ?? ''));
        $allowed = array_map('strtolower', config('payroll_workflow.'.$key, []));

        return $title !== '' && in_array($title, $allowed, true);
    }

    private function notifyUser(?int $userId, string $message): void
    {
        if (! $userId) {
            return;
        }
        try {
            Notification::create([
                'user_id' => $userId,
                'type' => 'payroll',
                'message' => \Illuminate\Support\Str::limit($message, 250),
                'link' => route('hr.payroll.index', [], false),
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // a failed notification must never block the payroll step itself
        }
    }

    /** $key: coo | md | finance | hr */
    private function notifyRole(string $key, string $message): void
    {
        if ($key === 'hr') {
            $ids = \App\Models\Hr\User::where('role', 'hr_admin')->where('is_active', 1)->pluck('id');
        } else {
            $ids = Employee::with('designation')->whereIn('employment_status', ['active', 'on_notice'])->get()
                ->filter(fn ($e) => $this->hasWorkflowRole($key, $e))->pluck('user_id');
        }
        foreach ($ids as $id) {
            $this->notifyUser($id, $message);
        }
    }

    private function audit(string $action, ?int $recordId, $old, $new): void
    {
        AuditLog::create([
            'user_id' => $this->currentUser()->id,
            'action' => $action,
            'module' => 'payroll',
            'record_id' => $recordId,
            'old_value' => $old === null ? null : json_encode($old),
            'new_value' => $new === null ? null : json_encode($new),
            'ip_address' => request()->ip(),
            'created_at' => now(),
        ]);
    }
}
