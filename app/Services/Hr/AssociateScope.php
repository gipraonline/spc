<?php

namespace App\Services\Hr;

use Illuminate\Support\Facades\DB;

/**
 * Associates are not employees: Farm Care Advisers and Tele Callers (and anyone
 * flagged engagement_type = 'associate') earn commission on sales, not salary
 * or incentives. This is the single place that says who they are, so payroll
 * and the incentive engine can leave them out.
 */
class AssociateScope
{
    /** @var array<int,int>|null */
    private static ?array $masterIds = null;

    /** employee_masters.n_employee_id of every associate. */
    public static function masterIds(): array
    {
        return self::$masterIds ??= DB::table('employee_masters as m')
            ->leftJoin('designation_masters as d', 'd.n_designation_id', '=', 'm.n_designation_id')
            ->where(function ($q) {
                $q->where('m.engagement_type', 'associate')
                    ->orWhereIn('d.identifier', array_keys(config('commission.designations', ['FCA' => 1, 'TC' => 1])));
            })
            ->pluck('m.n_employee_id')->map(fn ($id) => (int) $id)->all();
    }

    /** spc_hr employees.id of every associate that still has an HR record. */
    public static function hrEmployeeIds(): array
    {
        $ids = self::masterIds();

        return $ids === [] ? [] : DB::connection('spc_hr')->table('employees')
            ->whereIn('employee_master_id', $ids)->pluck('id')->map(fn ($id) => (int) $id)->all();
    }

    public static function isAssociateMaster(?int $masterId): bool
    {
        return $masterId !== null && in_array((int) $masterId, self::masterIds(), true);
    }

    /** Add "is not an associate" to an Eloquent or query-builder query on employees. */
    public static function exclude($query, string $column = 'employee_master_id')
    {
        $ids = self::masterIds();

        return $query->where(function ($w) use ($ids, $column) {
            $w->whereNull($column)->orWhereNotIn($column, $ids);
        });
    }
}
