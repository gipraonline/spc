<?php

namespace App\Http\Controllers\Hr;

use App\Exports\TableExport;
use App\Models\Hr\AuditLog;
use App\Models\Hr\CommissionLine;
use App\Models\Hr\CommissionRate;
use App\Models\Hr\CommissionRun;
use App\Models\Hr\Employee;
use App\Models\Hr\Notification;
use App\Models\Hr\User;
use App\Services\Hr\CommissionEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Commission for Farm Care Advisers and Tele Callers (associates).
 *
 *   Office Administration  sets the commission percentage per designation
 *   HR                     calculates the month, sees every advisor's commission, downloads Excel,
 *                          sends it to the COO
 *   COO -> MD              approve (or return to HR with a reason)
 *   Finance                marks it paid
 */
class CommissionController extends Controller
{
    public function index(Request $request)
    {
        $module = $this->abortUnlessModuleAllowed('commission');
        abort_unless($this->canAccess(), 403);

        $designations = config('commission.designations');

        // Current rate per designation = latest effective on or before today.
        $allRates = CommissionRate::orderByDesc('effective_from')->orderByDesc('id')->get();
        $currentRates = [];
        foreach (array_keys($designations) as $code) {
            $currentRates[$code] = $allRates
                ->first(fn ($r) => $r->designation_code === $code && $r->effective_from->lte(now()));
        }

        $runs = collect();
        $selectedRun = null;
        $lines = collect();
        if ($this->canViewStatements()) {
            $runs = CommissionRun::orderByDesc('year')->orderByDesc('month')->get();
            // Statements still with HR (draft / returned) are HR's working copy; COO, MD and Finance only see them once sent.
            if (! $this->isHr()) {
                $runs = $runs->whereIn('approval_stage', ['pending_coo', 'pending_md', 'pending_finance', 'completed'])->values();
            }
            $selectedRun = $request->filled('run')
                ? $runs->firstWhere('id', $request->integer('run'))
                : $runs->first();
            if ($selectedRun) {
                $lines = CommissionLine::where('commission_run_id', $selectedRun->id)
                    ->orderBy('employee_code')->get();
            }
        }

        return view('hr.modules.commission', array_merge($this->baseViewData(), [
            'module' => $module,
            'moduleKey' => 'commission',
            'designations' => $designations,
            'currentRates' => $currentRates,
            'rateHistory' => $allRates->take(20),
            'runs' => $runs,
            'selectedRun' => $selectedRun,
            'lines' => $lines,
            'isHr' => $this->isHr(),
            'isOfficeAdmin' => $this->hasWorkflowRole('office_admin'),
            'isCoo' => $this->hasWorkflowRole('coo'),
            'isMd' => $this->hasWorkflowRole('md'),
            'isFinance' => $this->hasWorkflowRole('finance'),
            'canViewStatements' => $this->canViewStatements(),
            'runMonth' => $request->query('month', now()->subMonth()->format('Y-m')),
            'eligibility' => [
                'order_status' => config('spc.incentive.order_status'),
                'payment_status' => implode(', ', (array) config('spc.incentive.payment_status')),
            ],
        ]));
    }

    /** Office Administration sets / changes the percentage. */
    public function storeRate(Request $request)
    {
        $this->abortUnlessModuleAllowed('commission');
        abort_unless($this->hasWorkflowRole('office_admin'), 403);

        $data = $request->validate([
            'designation_code' => 'required|in:'.implode(',', array_keys(config('commission.designations'))),
            'percent' => 'required|numeric|min:0|max:100',
            'effective_from' => 'required|date',
        ]);

        $rate = CommissionRate::create([
            'designation_code' => $data['designation_code'],
            'percent' => $data['percent'],
            'effective_from' => Carbon::parse($data['effective_from'])->toDateString(),
            'set_by' => $this->currentUser()->id,
            'created_at' => now(),
        ]);

        $this->audit('COMMISSION_RATE_SET', $rate->id, null, $rate->only(['designation_code', 'percent', 'effective_from']));
        $this->notifyRole('hr', 'Commission rate for '.(config('commission.designations')[$data['designation_code']] ?? $data['designation_code']).' set to '.$data['percent'].'% by Office Administration.');

        return back()->with('status', 'Commission rate saved.');
    }

    /** HR calculates (or recalculates) a month. */
    public function calculate(Request $request, CommissionEngine $engine)
    {
        $this->abortUnlessModuleAllowed('commission');
        abort_unless($this->isHr(), 403);

        $data = $request->validate(['month' => ['required', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/']]);
        [$year, $month] = array_map('intval', explode('-', $data['month']));

        if (Carbon::create($year, $month, 1)->gt(now()->endOfMonth())) {
            return back()->with('status', 'You cannot calculate commission for a future month.');
        }

        if (! CommissionRate::exists()) {
            return back()->with('status', 'No commission percentage has been set yet. Office Administration must set it first.');
        }

        $existing = CommissionRun::where('month', $month)->where('year', $year)->first();
        if ($existing && ! in_array($existing->approval_stage, ['draft', 'returned'], true)) {
            return back()->with('status', 'That month is already '.strtolower($existing->stageLabel()).' and can no longer be recalculated.');
        }

        $run = $engine->generate($month, $year, $this->currentUser()->id);
        $this->audit('COMMISSION_CALCULATED', $run->id, null, ['period' => $run->monthLabel(), 'total' => $run->total_commission]);

        $msg = 'Commission for '.$run->monthLabel().' calculated for '.$run->advisor_count.' people.';
        $missing = $engine->calculate($month, $year)['missing_rates'];
        if ($missing) {
            $msg .= ' No rate is set for: '.implode(', ', $missing).' (their commission shows as 0).';
        }

        return redirect()->route('hr.commission.index', ['run' => $run->id])->with('status', $msg);
    }

    public function submit(CommissionRun $commissionRun)
    {
        $this->abortUnlessModuleAllowed('commission');
        abort_unless($this->isHr(), 403);

        if (! in_array($commissionRun->approval_stage, ['draft', 'returned'], true)) {
            return back()->with('status', 'This statement cannot be sent right now.');
        }
        if ($commissionRun->advisor_count < 1 || (float) $commissionRun->total_commission <= 0) {
            return back()->with('status', 'There is no commission in this statement to send.');
        }

        $commissionRun->update([
            'approval_stage' => 'pending_coo',
            'submitted_by' => $this->currentUser()->id,
            'submitted_at' => now(),
            'coo_by' => null, 'coo_at' => null, 'md_by' => null, 'md_at' => null,
            'returned_by' => null, 'returned_at' => null, 'workflow_remarks' => null,
        ]);

        $this->audit('COMMISSION_SUBMIT', $commissionRun->id, null, ['stage' => 'pending_coo']);
        $this->notifyRole('coo', 'Commission for '.$commissionRun->monthLabel().' is waiting for your approval.');

        return back()->with('status', 'Commission for '.$commissionRun->monthLabel().' sent to the COO for approval.');
    }

    public function approveCoo(CommissionRun $commissionRun)
    {
        $this->abortUnlessModuleAllowed('commission');
        abort_unless($this->hasWorkflowRole('coo') && $commissionRun->approval_stage === 'pending_coo', 403);

        $commissionRun->update(['approval_stage' => 'pending_md', 'coo_by' => $this->currentUser()->id, 'coo_at' => now()]);

        $this->audit('COMMISSION_COO_APPROVE', $commissionRun->id, ['stage' => 'pending_coo'], ['stage' => 'pending_md']);
        $this->notifyRole('md', 'Commission for '.$commissionRun->monthLabel().' was approved by the COO and needs your approval.');

        return back()->with('status', 'Approved. Commission for '.$commissionRun->monthLabel().' is now with the MD.');
    }

    public function approveMd(CommissionRun $commissionRun)
    {
        $this->abortUnlessModuleAllowed('commission');
        abort_unless($this->hasWorkflowRole('md') && $commissionRun->approval_stage === 'pending_md', 403);

        $commissionRun->update(['approval_stage' => 'pending_finance', 'md_by' => $this->currentUser()->id, 'md_at' => now()]);

        $this->audit('COMMISSION_MD_APPROVE', $commissionRun->id, ['stage' => 'pending_md'], ['stage' => 'pending_finance']);
        $this->notifyRole('finance', 'Commission for '.$commissionRun->monthLabel().' is approved. Please pay it and mark it paid.');

        return back()->with('status', 'Approved. Commission for '.$commissionRun->monthLabel().' is now with Finance.');
    }

    public function returnToHr(Request $request, CommissionRun $commissionRun)
    {
        $this->abortUnlessModuleAllowed('commission');
        $stage = $commissionRun->approval_stage;
        abort_unless(
            ($stage === 'pending_coo' && $this->hasWorkflowRole('coo')) || ($stage === 'pending_md' && $this->hasWorkflowRole('md')),
            403
        );

        $data = $request->validate(['remarks' => 'required|string|max:255']);

        $commissionRun->update([
            'approval_stage' => 'returned',
            'returned_by' => $this->currentUser()->id,
            'returned_at' => now(),
            'workflow_remarks' => $data['remarks'],
        ]);

        $this->audit('COMMISSION_RETURNED', $commissionRun->id, ['stage' => $stage], ['stage' => 'returned', 'remarks' => $data['remarks']]);
        $this->notifyRole('hr', 'Commission for '.$commissionRun->monthLabel().' was returned: '.Str::limit($data['remarks'], 120));

        return back()->with('status', 'Commission for '.$commissionRun->monthLabel().' returned to HR.');
    }

    public function markPaid(CommissionRun $commissionRun)
    {
        $this->abortUnlessModuleAllowed('commission');
        abort_unless($this->hasWorkflowRole('finance') && $commissionRun->approval_stage === 'pending_finance', 403);

        $commissionRun->update(['approval_stage' => 'completed', 'paid_by' => $this->currentUser()->id, 'paid_at' => now()]);

        $this->audit('COMMISSION_PAID', $commissionRun->id, ['stage' => 'pending_finance'], ['stage' => 'completed']);
        $this->notifyRole('hr', 'Commission for '.$commissionRun->monthLabel().' has been paid by Finance.');

        return back()->with('status', 'Commission for '.$commissionRun->monthLabel().' marked as paid.');
    }

    /** HR throws away a statement that has not been sent (or was returned). */
    public function discard(CommissionRun $commissionRun)
    {
        $this->abortUnlessModuleAllowed('commission');
        abort_unless($this->isHr() && in_array($commissionRun->approval_stage, ['draft', 'returned'], true), 403);

        $label = $commissionRun->monthLabel();
        CommissionLine::where('commission_run_id', $commissionRun->id)->delete();
        $commissionRun->delete();

        $this->audit('COMMISSION_DISCARD', $commissionRun->id, ['period' => $label], null);

        return redirect()->route('hr.commission.index')->with('status', 'Commission statement for '.$label.' discarded.');
    }

    public function export(CommissionRun $commissionRun)
    {
        $this->abortUnlessModuleAllowed('commission');
        abort_unless($this->canViewStatements(), 403);
        abort_unless($this->isHr() || ! in_array($commissionRun->approval_stage, ['draft', 'returned'], true), 403);

        $lines = CommissionLine::where('commission_run_id', $commissionRun->id)->orderBy('employee_code')->get();
        $designations = config('commission.designations');

        $headings = ['Emp Code', 'Name', 'Role', 'Orders', 'Net Sales', 'Commission %', 'Commission'];
        $rows = $lines->map(fn ($l) => [
            $l->employee_code,
            $l->employee_name,
            $designations[$l->designation_code] ?? $l->designation_code,
            (int) $l->orders_count,
            (float) $l->sales_amount,
            (float) $l->rate_percent,
            (float) $l->commission_amount,
        ])->values()->all();

        $rows[] = ['', 'Total', '', (int) $lines->sum('orders_count'), (float) $lines->sum('sales_amount'), '', (float) $lines->sum('commission_amount')];

        return Excel::download(
            new TableExport($headings, $rows, ['A'], ['E', 'G']),
            sprintf('commission-%04d-%02d.xlsx', $commissionRun->year, $commissionRun->month)
        );
    }

    // ---------------------------------------------------------------- access

    private function isHr(): bool
    {
        return $this->currentRole() === 'hr_admin';
    }

    /** Office Administration, COO, MD, Finance by designation title (config/commission.php). */
    private function hasWorkflowRole(string $key, ?Employee $employee = null): bool
    {
        $employee ??= $this->currentEmployee();
        if (! $employee) {
            return false;
        }

        $title = strtolower(trim($employee->designation->title ?? ''));
        $allowed = array_map('strtolower', config('commission.roles.'.$key, []));

        return $title !== '' && in_array($title, $allowed, true);
    }

    private function canViewStatements(): bool
    {
        return $this->isHr() || $this->hasWorkflowRole('coo') || $this->hasWorkflowRole('md') || $this->hasWorkflowRole('finance');
    }

    private function canAccess(): bool
    {
        return $this->canViewStatements() || $this->hasWorkflowRole('office_admin');
    }

    // ---------------------------------------------------------------- helpers

    private function notifyUser(?int $userId, string $message): void
    {
        if (! $userId) {
            return;
        }
        try {
            Notification::create([
                'user_id' => $userId,
                'type' => 'commission',
                'message' => Str::limit($message, 250),
                'link' => route('hr.commission.index', [], false),
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // a failed notification must never block the commission step itself
        }
    }

    /** $key: hr | coo | md | finance */
    private function notifyRole(string $key, string $message): void
    {
        if ($key === 'hr') {
            $ids = User::where('role', 'hr_admin')->where('is_active', 1)->pluck('id');
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
            'module' => 'commission',
            'record_id' => $recordId,
            'old_value' => $old === null ? null : json_encode($old),
            'new_value' => $new === null ? null : json_encode($new),
            'ip_address' => request()->ip(),
            'created_at' => now(),
        ]);
    }
}