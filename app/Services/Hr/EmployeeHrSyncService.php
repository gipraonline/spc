<?php

namespace App\Services\Hr;

use App\Models\Admin;
use App\Models\EmployeeMaster;
use App\Models\Hr\Department;
use App\Models\Hr\Designation;
use App\Models\Hr\Employee as HrEmployee;
use App\Models\Hr\EmployeeHistory;
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
        if ($employee->isAssociate()) {
            // Farm Care Advisers / Tele Callers are not employees until they
            // are promoted, so they get no HR record, payroll, leave or PF.
            // They keep their SPC login, sales and leads untouched.
            return null;
        }

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
            'role' => static::deriveRole($employee),
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
        $isNew = ! $hrEmployee->exists;

        // Someone serving notice is still working (their SPC status stays
        // Active); only the exit flow moves them on to "exited".
        $employmentStatus = $employee->c_status === 'Y'
            ? ($hrEmployee->employment_status === 'on_notice' ? 'on_notice' : 'active')
            : 'exited';

        $hrEmployee->fill([
            'employee_master_id' => $employee->n_employee_id,
            'employee_code' => $employee->c_employee_code,
            'department_id' => $departmentId,
            'designation_id' => static::matchingHrDesignationId($employee, $departmentId),
            'reporting_manager_id' => $reportingManagerId,
            'date_of_joining' => $employee->date_of_joining
                ?? $hrEmployee->date_of_joining
                ?? $employee->created_at?->toDateString()
                ?? now()->toDateString(),
            'employment_status' => $employmentStatus,
            'phone' => $employee->n_employee_phone,
            'personal_email' => $employee->personal_email,
            'address' => $employee->c_employee_address,
            'city' => $employee->city,
            'date_of_birth' => $employee->date_of_birth,
            'gender' => $employee->gender,
            'bank_name' => $employee->bank_name,
            'bank_account_number' => $employee->bank_account_number,
            'bank_ifsc' => $employee->bank_ifsc,
        ]);

        // Capture what actually changed (before saving) so the career
        // timeline records promotions / transfers made from the SPC screen.
        $changed = $isNew ? [] : array_intersect_key(
            $hrEmployee->getDirty(),
            array_flip(['designation_id', 'department_id', 'reporting_manager_id'])
        );
        $original = $hrEmployee->getOriginal();

        $hrEmployee->save();

        // History is a bonus on top of the sync — never let a logging
        // problem (e.g. employee_history.sql not applied yet) block a save.
        try {
            static::logTimeline($hrEmployee, $isNew, $changed, $original);
        } catch (\Throwable $e) {
            report($e);
        }

        return $hrEmployee;
    }

    /** Who is making this change, as an HR users.id (null for console/jobs). */
    public static function actingHrUserId(): ?int
    {
        $admin = auth()->user();

        return $admin instanceof Admin ? HrUser::findForSpcAdmin($admin)?->id : null;
    }

    protected static function logTimeline(HrEmployee $hr, bool $isNew, array $changed, array $original): void
    {
        $by = static::actingHrUserId();

        if ($isNew) {
            $hr->loadMissing(['designation', 'department']);

            EmployeeHistory::log(
                $hr->id, 'joined', 'Joined company', null,
                trim(($hr->designation->title ?? 'Employee').' — '.($hr->department->name ?? '—'), ' —'),
                $by, null, $hr->date_of_joining ? (string) $hr->date_of_joining : null
            );

            return;
        }

        if (array_key_exists('designation_id', $changed)) {
            EmployeeHistory::log(
                $hr->id, 'promotion', 'Designation',
                Designation::find($original['designation_id'] ?? null)?->title ?? '—',
                Designation::find($changed['designation_id'])?->title ?? '—',
                $by
            );
        }

        if (array_key_exists('department_id', $changed)) {
            EmployeeHistory::log(
                $hr->id, 'transfer', 'Department',
                Department::find($original['department_id'] ?? null)?->name ?? '—',
                Department::find($changed['department_id'])?->name ?? '—',
                $by
            );
        }

        if (array_key_exists('reporting_manager_id', $changed)) {
            $name = fn ($id) => $id ? (HrEmployee::with('user')->find($id)?->user?->name ?? '—') : '—';

            EmployeeHistory::log(
                $hr->id, 'change', 'Reporting manager',
                $name($original['reporting_manager_id'] ?? null),
                $name($changed['reporting_manager_id']),
                $by
            );
        }
    }

    /**
     * Some SPC admin accounts aren't tied to any employee at all (e.g. a
     * standalone admin-only login like "Gipra Admin") — there's no
     * EmployeeMaster to run through sync(), so nothing above ever touches
     * their HR row. This updates just their role directly, matched by
     * email (the same key the SSO bridge itself uses), so a change to a
     * role's HR Portal Access still reaches them.
     */
    public static function syncRoleForAdmin(Admin $admin): void
    {
        if ($admin->n_employee_id) {
            // Has a real employee record — let the full sync handle it,
            // rather than partially updating just the role here.
            return;
        }

        $email = trim((string) $admin->c_username);

        if ($email === '') {
            return;
        }

        $hrUser = HrUser::whereRaw('LOWER(email) = ?', [Str::lower($email)])->first();

        if (! $hrUser) {
            return;
        }

        $accessLevels = $admin->roles->pluck('hr_access')->filter();

        foreach (['super_admin', 'hr_admin', 'manager', 'employee'] as $tier) {
            if ($accessLevels->contains($tier)) {
                $hrUser->update(['role' => $tier]);

                return;
            }
        }
    }

    /**
     * The HR module's access tier — employee, manager, hr_admin or
     * super_admin — is no longer a separate field anyone has to pick by
     * hand. It's read straight from SPC's own Role records:
     *
     *   - each SPC role can have an "HR Portal Access" tier set on it
     *     (Admin > Role Management > Edit Role), stored as roles.hr_access
     *   - if this person holds a role with that set, the highest tier among
     *     their roles wins (super_admin > hr_admin > manager > employee)
     *   - if none of their roles have a tier set, fall back to whether they
     *     have direct reports in SPC (manager) or not (employee)
     *
     * There is deliberately one set of roles, defined once in SPC (Role
     * Management); HR just reads them — nothing is hardcoded by role name.
     */
    protected static function deriveRole(EmployeeMaster $employee): string
    {
        $admin = Admin::where('n_employee_id', $employee->n_employee_id)->first();

        if ($admin) {
            $accessLevels = $admin->roles->pluck('hr_access')->filter();

            foreach (['super_admin', 'hr_admin', 'manager', 'employee'] as $tier) {
                if ($accessLevels->contains($tier)) {
                    return $tier;
                }
            }
        }

        if ($employee->subordinates()->exists()) {
            return 'manager';
        }

        return 'employee';
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
