<?php

namespace App\Services\Hr;

use App\Models\Hr\Employee;
use App\Models\Hr\LeaveBalance;
use App\Models\Hr\LeaveRequest;
use App\Models\Hr\LeaveType;
use Illuminate\Support\Collection;

/**
 * Single source of truth for leave entitlements.
 *
 * Entitlements are configured per leave type in HR Settings
 * (leave_types.default_annual_days / carry_forward / max_carry_forward /
 * applicable_to). This service turns them into per-employee, per-year
 * leave_balances rows the first time they're needed, so a new joiner or a new
 * year never ends up with "no balance allocated".
 *
 * Unpaid leave is unlimited: it never gets a balance row and is never capped.
 */
class LeaveBalanceService
{
    /** Leave types this employee may use (gender-restricted types are filtered). */
    public function eligibleTypes(?Employee $employee): Collection
    {
        return LeaveType::orderBy('id')->get()->filter(function (LeaveType $t) use ($employee) {
            $rule = $t->applicable_to ?? 'all';

            return $rule === 'all' || ($employee && $employee->gender === $rule);
        })->values();
    }

    public function isEligible(?Employee $employee, LeaveType $type): bool
    {
        return $this->eligibleTypes($employee)->contains('id', $type->id);
    }

    /** Create any missing balance rows (paid, eligible types) for the year. */
    public function ensureFor(Employee $employee, int $year): void
    {
        foreach ($this->eligibleTypes($employee)->where('is_paid', true) as $type) {
            $exists = LeaveBalance::where('employee_id', $employee->id)
                ->where('leave_type_id', $type->id)
                ->where('year', $year)
                ->exists();

            if ($exists) {
                continue;
            }

            LeaveBalance::create([
                'employee_id' => $employee->id,
                'leave_type_id' => $type->id,
                'year' => $year,
                'opening_balance' => 0,
                'accrued' => (float) $type->default_annual_days,
                'used' => 0,
                'carried_forward' => $this->carryForwardFor($employee->id, $type, $year),
            ]);
        }
    }

    /** Days carried in from last year, capped by the type's max_carry_forward. */
    private function carryForwardFor(int $employeeId, LeaveType $type, int $year): float
    {
        if (! $type->carry_forward || (float) $type->max_carry_forward <= 0) {
            return 0.0;
        }

        $previous = LeaveBalance::where('employee_id', $employeeId)
            ->where('leave_type_id', $type->id)
            ->where('year', $year - 1)
            ->first();

        if (! $previous) {
            return 0.0;
        }

        return max(0.0, min((float) $previous->remaining, (float) $type->max_carry_forward));
    }

    /**
     * One row per eligible leave type for the "my balances" tiles and the
     * apply form. Paid types have a numeric remaining; unpaid is unlimited
     * (remaining = null) and reports the days taken instead.
     *
     * @return Collection<int,array>
     */
    public function summaryFor(Employee $employee, int $year): Collection
    {
        $this->ensureFor($employee, $year);

        $balances = LeaveBalance::where('employee_id', $employee->id)
            ->where('year', $year)->get()->keyBy('leave_type_id');

        $requests = LeaveRequest::where('employee_id', $employee->id)
            ->whereYear('start_date', $year)
            ->whereIn('status', ['pending', 'approved'])
            ->get()
            ->groupBy('leave_type_id');

        return $this->eligibleTypes($employee)->map(function (LeaveType $type) use ($balances, $requests) {
            $rows = $requests->get($type->id, collect());
            $pending = (float) $rows->where('status', 'pending')->sum('days');
            $approved = (float) $rows->where('status', 'approved')->sum('days');

            if (! $type->is_paid) {
                return [
                    'type' => $type,
                    'paid' => false,
                    'unlimited' => true,
                    'entitled' => null,
                    'used' => $approved,
                    'pending' => $pending,
                    'remaining' => null,
                    'available' => null,
                ];
            }

            $b = $balances->get($type->id);
            $entitled = $b ? (float) $b->opening_balance + (float) $b->accrued + (float) $b->carried_forward : 0.0;
            $remaining = $b ? (float) $b->remaining : 0.0;

            return [
                'type' => $type,
                'paid' => true,
                'unlimited' => false,
                'entitled' => $entitled,
                'used' => $b ? (float) $b->used : 0.0,
                'pending' => $pending,
                'remaining' => $remaining,
                'available' => max(0.0, $remaining - $pending),
            ];
        });
    }

    /**
     * Push a leave type's current entitlement onto existing employees for a
     * year: sets accrued to the new days (used/carry-forward untouched) and
     * creates rows for active employees that don't have one yet.
     */
    public function applyEntitlementToAll(LeaveType $type, int $year): int
    {
        if (! $type->is_paid) {
            return 0;
        }

        $touched = LeaveBalance::where('leave_type_id', $type->id)
            ->where('year', $year)
            ->update(['accrued' => (float) $type->default_annual_days]);

        Employee::where('employment_status', '!=', 'exited')->get()->each(
            fn (Employee $e) => $this->ensureFor($e, $year)
        );

        return $touched;
    }
}
