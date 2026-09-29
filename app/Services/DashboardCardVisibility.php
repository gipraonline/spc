<?php

namespace App\Services;

use App\Models\Admin;
use Illuminate\Support\Facades\DB;

/**
 * Which unified-dashboard cards a user may see, decided by the designation
 * on their SPC employee record (employee_masters.n_designation_id).
 *
 * Return value convention for forUser()/forDesignation():
 *   null        -> no restriction, show every card
 *   string[]    -> only these card keys are shown
 *
 * This only ever HIDES cards. Data access is still decided by the existing
 * role / HR-role logic in UnifiedDashboardController.
 */
class DashboardCardVisibility
{
    private const TABLE = 'dashboard_card_designation';

    public static function catalog(): array
    {
        return config('dashboard_cards.cards', []);
    }

    public static function groups(): array
    {
        return config('dashboard_cards.groups', []);
    }

    public static function forUser(?Admin $admin): ?array
    {
        if (! $admin) {
            return null;
        }

        // Super/Gipra admins always see everything (no lock-out possible).
        if ($admin->hasAnyRole(['Super Admin', 'Gipra Admin'])) {
            return null;
        }

        $designationId = optional($admin->employee)->n_designation_id;

        if (! $designationId) {
            return null;
        }

        return self::forDesignation((int) $designationId);
    }

    public static function forDesignation(int $designationId): ?array
    {
        $rows = DB::table(self::TABLE)
            ->where('n_designation_id', $designationId)
            ->pluck('is_visible', 'card_key');

        if ($rows->isEmpty()) {
            return null; // never configured -> show all
        }

        $known = array_keys(self::catalog());

        return array_values(array_filter(
            $known,
            fn ($key) => (bool) ($rows[$key] ?? false)
        ));
    }

    /** Replace a designation's settings with the ticked card keys. */
    public static function save(int $designationId, array $visibleKeys): void
    {
        $visibleKeys = array_values(array_intersect($visibleKeys, array_keys(self::catalog())));
        $now = now();

        DB::transaction(function () use ($designationId, $visibleKeys, $now) {
            DB::table(self::TABLE)->where('n_designation_id', $designationId)->delete();

            $rows = [];
            foreach (array_keys(self::catalog()) as $key) {
                $rows[] = [
                    'n_designation_id' => $designationId,
                    'card_key' => $key,
                    'is_visible' => in_array($key, $visibleKeys, true),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            DB::table(self::TABLE)->insert($rows);
        });
    }
}
