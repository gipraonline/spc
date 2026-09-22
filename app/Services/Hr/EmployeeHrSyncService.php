<?php

namespace App\Services\Hr;

use App\Models\EmployeeMaster;
use App\Models\Hr\Department;
use App\Models\Hr\Designation;
use App\Models\Hr\Employee as HrEmployee;
use App\Models\Hr\User as HrUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * SPC's employee module (Admin\EmployeeController) is the single place an
 * employee is created or edited. This service mirrors that record into the
 * HR module's own database (spc_hr) so the HR module keeps working off its
 * own tables without needing its own "Add Employee" screen. It matches SPC
 * and HR records by email — the same key the SPC<->HR single sign-on
 * bridge uses — and is safe to call repeatedly: every call is an upsert.
 */
class EmployeeHrSyncService
{
    /** @var array<int,true> employee_masters ids currently being synced, to stop reporting-line cycles */
    protected static array $inProgress = [];

    public static function sync(EmployeeMaster $employee): ?HrEmployee
    {
        if (! $employee->c_employee_email) {
            // No work email means no login identity to bridge into HR —
            // nothing to sync until one is set.
            return null;
        }

        if (isset(static::$inProgress[$employee->n_employee_id])) {
            // Reporting chain looped back on itself; don't recurse forever.
            return HrEmployee::whereHas(
                'user',
                fn ($q) => $q->whereRaw('LOWER(email) = ?', [Str::lower(trim($employee->c_employee_email))])
            )->first();
        }

        static::$inProgress[$employee->n_employee_id] = true;

        try {
            $hrUser = static::syncUser($employee);
            $hrEmployee = static::syncEmployee($employee, $hrUser);
        } finally {
            unset(static::$inProgress[$employee->n_employee_id]);
        }

        return $hrEmployee;
    }

    protected static function syncUser(EmployeeMaster $employee): HrUser
    {
        $email = trim($employee->c_employee_email);

        $hrUser = HrUser::whereRaw('LOWER(email) = ?', [Str::lower($email)])->first();

        $attributes = [
            'name' => $employee->c_employee_name,
            'email' => $email,
            'role' => in_array($employee->c_hr_role, ['employee', 'manager', 'hr_admin', 'super_admin'], true)
                ? $employee->c_hr_role
                : 'employee',
            'is_active' => $employee->c_status === 'Y',
        ];

        if ($hrUser) {
            $hrUser->update($attributes);

            return $hrUser;
        }

        return HrUser::create($attributes + [
            // HR login for synced employees happens only through the SPC
            // single sign-on bridge (matching email) — this password is
            // random and is never shown or meant to be used directly.
            'password' => Hash::make(Str::random(40)),
        ]);
    }

    protected static function syncEmployee(EmployeeMaster $employee, HrUser $hrUser): HrEmployee
    {
        $departmentId = $employee->department_id && Department::find($employee->department_id)
            ? $employee->department_id
            : null;

        $reportingManagerId = null;
        if ($employee->reporting_to) {
            $manager = $employee->reportingManager;
            if ($manager) {
                $reportingManagerId = static::sync($manager)?->id;
            }
        }

        $hrEmployee = HrEmployee::firstOrNew(['user_id' => $hrUser->id]);

        $hrEmployee->fill([
            'employee_code' => $employee->c_employee_code,
            'department_id' => $departmentId,
            'designation_id' => static::matchingHrDesignationId($employee, $departmentId),
            'reporting_manager_id' => $reportingManagerId,
            'date_of_joining' => $employee->date_of_joining
                ?? $hrEmployee->date_of_joining
                ?? $employee->created_at?->toDateString()
                ?? now()->toDateString(),
            'employment_status' => $employee->c_status === 'Y' ? 'active' : 'exited',
            'phone' => $employee->n_employee_phone,
            'personal_email' => $employee->personal_email,
            'address' => $employee->c_employee_address,
            'city' => $employee->city,
            'date_of_birth' => $employee->date_of_birth,
            'gender' => $employee->gender,
            'bank_name' => $employee->bank_name,
            'bank_account_number' => $employee->bank_account_number,
            'bank_ifsc' => $employee->bank_ifsc,
        ])->save();

        return $hrEmployee;
    }

    /**
     * HR keeps its own designation list, scoped under a department. Find
     * one with a matching title (under the mapped department), or create
     * it, so SPC's designation always has an HR-side counterpart.
     */
    protected static function matchingHrDesignationId(EmployeeMaster $employee, ?int $departmentId): ?int
    {
        if (! $employee->n_designation_id || ! $employee->designation) {
            return null;
        }

        $title = $employee->designation->c_designation;

        $designation = Designation::where('title', $title)
            ->when($departmentId, fn ($q) => $q->where('department_id', $departmentId))
            ->first();

        if (! $designation) {
            $designation = Designation::create([
                'title' => $title,
                'department_id' => $departmentId,
            ]);
        }

        return $designation->id;
    }
}
