<?php

namespace App\Http\Controllers\Hr;


use App\Models\Hr\AuditLog;
use App\Models\Hr\LeaveBalance;
use App\Models\Hr\LeaveRequest;
use App\Models\Hr\LeaveType;
use App\Services\Hr\LeaveBalanceService;
use App\Models\Hr\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    /**
     * Keys editable from the Settings screen, with a human label and
     * the input type to render. Anything in system_settings but not
     * listed here (there shouldn't be any) simply won't show a field.
     */
    private const EDITABLE = [
        'company_name' => ['label' => 'Company name', 'type' => 'text'],
        'pf_contribution_rate' => ['label' => 'PF contribution rate (%)', 'type' => 'number'],
        'esi_threshold' => ['label' => 'ESI threshold (monthly gross)', 'type' => 'number'],
        'leave_year_start_month' => ['label' => 'Leave year start month (1-12)', 'type' => 'number'],
        'payroll_cutoff_day' => ['label' => 'Payroll cutoff day of month', 'type' => 'number'],
        'attendance_grace_minutes' => ['label' => 'Attendance grace period (minutes)', 'type' => 'number'],
        'wfh_max_days_per_month' => ['label' => 'Max WFH days per employee / month', 'type' => 'number'],
        'weekly_off_days' => ['label' => 'Weekly off days for payroll (0=Sun ... 6=Sat, comma separated)', 'type' => 'text'],
        'missing_attendance_as' => ['label' => 'Working day with no attendance record counts as (present / absent)', 'type' => 'text'],
        'pf_wage_ceiling' => ['label' => 'PF wage ceiling (monthly)', 'type' => 'number'],
        'pf_cap_wages' => ['label' => 'Cap PF at the wage ceiling (1 = yes, 0 = no)', 'type' => 'number'],
        'esi_employee_rate' => ['label' => 'ESI employee rate (%)', 'type' => 'number'],
        'esi_employer_rate' => ['label' => 'ESI employer rate (%)', 'type' => 'number'],
        'pf_admin_edli_rate' => ['label' => 'PF admin + EDLI employer rate (%)', 'type' => 'number'],
        'pt_enabled' => ['label' => 'Deduct professional tax (1 = yes, 0 = no)', 'type' => 'number'],
        'pt_deduction_mode' => ['label' => 'Professional tax deduction (monthly / half_yearly)', 'type' => 'text'],
        'pt_slabs' => ['label' => 'Professional tax slabs, JSON [[half-yearly gross from, tax], ...]', 'type' => 'text'],
        'tds_enabled' => ['label' => 'Deduct income tax / TDS (1 = yes, 0 = no)', 'type' => 'number'],
        'tds_standard_deduction' => ['label' => 'TDS standard deduction (annual)', 'type' => 'number'],
    ];

    public function index()
    {
        $module = $this->abortUnlessModuleAllowed('settings');

        $existing = SystemSetting::pluck('setting_value', 'setting_key');

        $settings = collect(self::EDITABLE)->map(function ($meta, $key) use ($existing) {
            return array_merge($meta, ['key' => $key, 'value' => $existing[$key] ?? '']);
        })->values();

        return view('hr.modules.settings', array_merge($this->baseViewData(), [
            'module' => $module,
            'moduleKey' => 'settings',
            'settings' => $settings,
            'leaveTypes' => LeaveType::orderBy('id')->get(),
            // leave_type_id => number of leave requests ever made (types with history can't be deleted)
            'leaveTypeUsage' => LeaveRequest::selectRaw('leave_type_id, COUNT(*) as total')->groupBy('leave_type_id')->pluck('total', 'leave_type_id'),
        ]));
    }

    /**
     * Leave entitlements: how many days each leave type gives per year,
     * carry-forward rules, and (optionally) pushing the new number onto
     * existing employees' current-year balances. Unpaid leave is unlimited
     * by design, so it has no days field and cannot be made paid here.
     */
    public function updateLeaveTypes(Request $request)
    {
        $this->abortUnlessModuleAllowed('settings');
        abort_unless($this->isHrOrAbove(), 403);

        $data = $request->validate([
            'types' => 'required|array',
            'types.*.days' => 'nullable|numeric|min:0|max:365',
            'types.*.max_carry_forward' => 'nullable|numeric|min:0|max:365',
            'types.*.carry_forward' => 'nullable|boolean',
            'apply_to_existing' => 'nullable|boolean',
        ]);

        $service = app(LeaveBalanceService::class);
        $year = now()->year;
        $changedTypes = 0;
        $applyAll = $request->boolean('apply_to_existing');

        foreach (LeaveType::orderBy('id')->get() as $type) {
            $row = $data['types'][$type->id] ?? null;
            if (! $row || ! $type->is_paid) {
                continue;
            }

            $new = [
                'default_annual_days' => round((float) ($row['days'] ?? 0), 2),
                'carry_forward' => ! empty($row['carry_forward']),
                'max_carry_forward' => ! empty($row['carry_forward']) ? round((float) ($row['max_carry_forward'] ?? 0), 2) : 0,
            ];

            $old = $type->only(array_keys($new));
            $dirty = collect($new)->contains(fn ($v, $k) => (float) $old[$k] !== (float) $v);

            if (! $dirty && ! $applyAll) {
                continue;
            }

            if ($dirty) {
                $type->update($new);
                $changedTypes++;
            }

            // "Everyone, right now": push this type's entitlement onto every
            // current employee's balance for this year (used days are kept).
            if ($applyAll) {
                $service->applyEntitlementToAll($type->fresh(), $year);
            }

            if ($dirty) {
                AuditLog::create([
                    'user_id' => $this->currentUser()->id,
                    'action' => 'UPDATE',
                    'module' => 'settings',
                    'old_value' => json_encode(['leave_type' => $type->name] + $old),
                    'new_value' => json_encode(['leave_type' => $type->name] + $new + ['applied_to_existing' => $applyAll]),
                    'created_at' => now(),
                ]);
            }
        }

        if ($applyAll) {
            return back()->with('status', 'Leave entitlements saved and applied to every employee\'s current balance.');
        }

        return back()->with('status', $changedTypes
            ? 'Leave entitlements saved - they apply to new employees and from next year.'
            : 'No changes to save.');
    }

    /**
     * Delete a leave type. The database cascades deletes from leave_types into
     * leave_requests and leave_balances, so a type that anybody has ever
     * applied for is refused - deleting it would silently erase their leave
     * history. Unpaid Leave is protected too (it's the unlimited fallback).
     */
    public function destroyLeaveType(LeaveType $leaveType)
    {
        $this->abortUnlessModuleAllowed('settings');
        abort_unless($this->isHrOrAbove(), 403);

        if (! $leaveType->is_paid && LeaveType::where('is_paid', false)->count() <= 1) {
            return back()->withErrors(['leave_type' => 'Unpaid Leave cannot be deleted - at least one unpaid, unlimited leave type must remain.']);
        }

        $requests = LeaveRequest::where('leave_type_id', $leaveType->id)->count();
        $usedDays = (float) LeaveBalance::where('leave_type_id', $leaveType->id)->sum('used');

        if ($requests > 0 || $usedDays > 0) {
            return back()->withErrors(['leave_type' => $leaveType->name.' cannot be deleted because employees have leave records under it ('
                .$requests.' request'.($requests === 1 ? '' : 's').'). Deleting it would erase that history. Set its days per year to 0 to stop new allocations instead.']);
        }

        $snapshot = $leaveType->only(['name', 'is_paid', 'default_annual_days', 'carry_forward', 'max_carry_forward', 'applicable_to']);

        DB::connection('spc_hr')->transaction(function () use ($leaveType) {
            LeaveBalance::where('leave_type_id', $leaveType->id)->delete();
            $leaveType->delete();
        });

        AuditLog::create([
            'user_id' => $this->currentUser()->id,
            'action' => 'DELETE',
            'module' => 'settings',
            'old_value' => json_encode($snapshot),
            'new_value' => null,
            'created_at' => now(),
        ]);

        return back()->with('status', $snapshot['name'].' deleted.');
    }

    public function storeLeaveType(Request $request)
    {
        $this->abortUnlessModuleAllowed('settings');
        abort_unless($this->isHrOrAbove(), 403);

        $data = $request->validate([
            'name' => 'required|string|max:60|unique:spc_hr.leave_types,name',
            'is_paid' => 'required|in:0,1',
            'default_annual_days' => 'nullable|numeric|min:0|max:365',
            'applicable_to' => 'required|in:all,female,male',
        ]);

        $paid = (bool) $data['is_paid'];

        $type = LeaveType::create([
            'name' => trim($data['name']),
            'is_paid' => $paid,
            'default_annual_days' => $paid ? round((float) ($data['default_annual_days'] ?? 0), 2) : 0,
            'carry_forward' => false,
            'max_carry_forward' => 0,
            'applicable_to' => $data['applicable_to'],
        ]);

        if ($paid) {
            app(LeaveBalanceService::class)->applyEntitlementToAll($type, now()->year);
        }

        AuditLog::create([
            'user_id' => $this->currentUser()->id,
            'action' => 'CREATE',
            'module' => 'settings',
            'old_value' => null,
            'new_value' => json_encode($type->only(['name', 'is_paid', 'default_annual_days', 'applicable_to'])),
            'created_at' => now(),
        ]);

        return back()->with('status', $type->name.' added.');
    }

    public function update(Request $request)
    {
        $this->abortUnlessModuleAllowed('settings');
        abort_unless($this->isHrOrAbove(), 403);

        $keys = array_keys(self::EDITABLE);
        $data = $request->validate(
            collect($keys)->mapWithKeys(fn ($k) => [$k => 'nullable|string|max:255'])->all()
        );

        // Payroll reads these on every run, so refuse values the engine cannot interpret.
        $errors = [];
        $v = fn (string $k) => trim((string) ($data[$k] ?? ''));

        if ($v('missing_attendance_as') !== '' && ! in_array($v('missing_attendance_as'), ['present', 'absent'], true)) {
            $errors['missing_attendance_as'] = 'Use "present" or "absent".';
        }
        if ($v('pt_deduction_mode') !== '' && ! in_array($v('pt_deduction_mode'), ['monthly', 'half_yearly'], true)) {
            $errors['pt_deduction_mode'] = 'Use "monthly" or "half_yearly".';
        }
        if ($v('weekly_off_days') !== '' && ! preg_match('/^[0-6](\s*,\s*[0-6])*$/', $v('weekly_off_days'))) {
            $errors['weekly_off_days'] = 'Use day numbers 0-6 separated by commas, e.g. 0 or 0,6.';
        }
        foreach (['pf_cap_wages', 'pt_enabled', 'tds_enabled'] as $flag) {
            if ($v($flag) !== '' && ! in_array($v($flag), ['0', '1'], true)) {
                $errors[$flag] = 'Use 1 or 0.';
            }
        }
        foreach (['pf_wage_ceiling', 'esi_employee_rate', 'esi_employer_rate', 'pf_admin_edli_rate', 'tds_standard_deduction', 'pf_contribution_rate', 'esi_threshold'] as $num) {
            if ($v($num) !== '' && (! is_numeric($v($num)) || (float) $v($num) < 0)) {
                $errors[$num] = 'Enter a number that is zero or more.';
            }
        }
        if ($v('pt_slabs') !== '') {
            $slabs = json_decode($v('pt_slabs'), true);
            $ok = is_array($slabs) && count($slabs) > 0;
            foreach ((array) $slabs as $row) {
                if (! is_array($row) || count($row) !== 2 || ! is_numeric($row[0] ?? null) || ! is_numeric($row[1] ?? null)) {
                    $ok = false;
                }
            }
            if (! $ok) {
                $errors['pt_slabs'] = 'Must be JSON like [[0,0],[12000,320],[18000,450]].';
            }
        }
        if ($errors) {
            return back()->withErrors($errors)->withInput();
        }

        foreach ($keys as $key) {
            if (! array_key_exists($key, $data)) {
                continue;
            }

            $setting = SystemSetting::where('setting_key', $key)->first();
            $oldValue = $setting->setting_value ?? null;
            $newValue = $data[$key] ?? '';

            if ($setting) {
                $setting->update(['setting_value' => $newValue, 'updated_at' => now()]);
            } else {
                SystemSetting::create(['setting_key' => $key, 'setting_value' => $newValue, 'updated_at' => now()]);
            }

            if ($oldValue !== $newValue) {
                AuditLog::create([
                    'user_id' => $this->currentUser()->id,
                    'action' => 'UPDATE',
                    'module' => 'settings',
                    'old_value' => json_encode([$key => $oldValue]),
                    'new_value' => json_encode([$key => $newValue]),
                    'created_at' => now(),
                ]);
            }
        }

        return back()->with('status', 'Settings saved.');
    }
}
