<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\EmployeeMaster;

/**
 * Who can a signed-in SPC user see, based on the reporting line
 * (employee_masters.reporting_to)?
 *
 *   null   -> everyone (Super Admin, Gipra Admin, National Sales Head,
 *             or anyone holding "dashboard.view-all-orders")
 *   int[]  -> the person themself plus everyone below them
 *   []     -> nobody (user has no linked employee record)
 *
 * The same rule is used by the follow-up cockpit, the coverage map and the
 * incentive engine so they never disagree about who belongs to whom.
 */
class HierarchyScope
{
    private const SEE_ALL_ROLES = ['Super Admin', 'Gipra Admin', 'National Sales Head'];

    public static function seesAll(?Admin $admin): bool
    {
        return $admin
            && ($admin->hasAnyRole(self::SEE_ALL_ROLES) || $admin->can('dashboard.view-all-orders'));
    }

    /** @return int[]|null */
    public static function employeeIds(?Admin $admin): ?array
    {
        if (! $admin) {
            return [];
        }

        if (self::seesAll($admin)) {
            return null;
        }

        if (! $admin->n_employee_id) {
            return [];
        }

        return array_merge([(int) $admin->n_employee_id], self::descendants((int) $admin->n_employee_id));
    }

    /**
     * parent employee id => [child employee ids], for active (non-deleted) employees.
     *
     * @return array<int, int[]>
     */
    public static function childrenMap(): array
    {
        $map = [];

        EmployeeMaster::query()
            ->whereNull('deleted_at')
            ->whereNotNull('reporting_to')
            ->get(['n_employee_id', 'reporting_to'])
            ->each(function ($e) use (&$map) {
                $map[(int) $e->reporting_to][] = (int) $e->n_employee_id;
            });

        return $map;
    }

    /**
     * Everyone below $employeeId at any depth (cycle-safe).
     *
     * @param  array<int, int[]>|null  $map  pass a pre-built childrenMap() when calling in a loop
     * @return int[]
     */
    public static function descendants(int $employeeId, ?array $map = null): array
    {
        $map ??= self::childrenMap();
        $seen = [$employeeId => true];
        $queue = [$employeeId];
        $out = [];

        while ($queue) {
            $current = array_shift($queue);
            foreach ($map[$current] ?? [] as $child) {
                if (isset($seen[$child])) {
                    continue;
                }
                $seen[$child] = true;
                $out[] = $child;
                $queue[] = $child;
            }
        }

        return $out;
    }
}
