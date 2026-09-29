<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Sidebar menu structure:
 *
 *   Administration (untouched)
 *   Home · Activity · HR · Field Operations · Sales · Finance · Settings
 *
 * Safe to run any number of times:
 *  - Groups are matched by name, items by route_name (no hard-coded IDs).
 *  - Existing items only get their group + order updated, so names, icons,
 *    status and role assignments (role_menu) are never overwritten.
 *  - Missing items are created. New rows have no role assigned yet — tick
 *    them for the right roles in Role Management.
 *  - "Administration" and its items are not touched.
 *
 * Run:  php artisan db:seed --class=MenuSeeder
 */
class MenuSeeder extends Seeder
{
    /** Group name => [icon, sort_order]  (Administration stays at 100) */
    private array $groups = [
        'Home'             => ['home',               101],
        'Activity'         => ['activity',           102],
        'HR'               => ['users',              103],
        'Field Operations' => ['map-pin',            104],
        'Sales'            => ['shopping-cart',      105],
        'Finance'          => ['wallet',             106],
        'Settings'         => ['sliders-horizontal', 107],
    ];

    /**
     * Group => list of [route_name, name, icon]  (order = position in group)
     */
    private function items(): array
    {
        return [
            'Home' => [
                ['dashboard',              'Dashboard',     'layout-dashboard'],
                ['hr.profile.index',       'My Profile',    'user'],
                ['hr.announcements.index', 'Announcements', 'megaphone'],
            ],

            'Activity' => [
                ['hr.attendance.index', 'Attendance',     'clock'],
                ['hr.wfh.index',        'Work From Home', 'home'],
                ['hr.leave.index',      'Leave',          'calendar-days'],
                ['hr.support.index',    'HR Support',     'help-circle'],
                ['hr.appraisal.index',  'Performance',    'trophy'],
            ],

            'HR' => [
                ['admin.employees.index',    'Employee Records', 'user-round'],
                ['admin.franchises.index',   'Franchises',       'warehouse'],
                ['hr.recruitment.index',     'Recruitment',      'user-plus'],
                ['hr.reports.index',         'HR Reports',       'bar-chart-3'],
                ['admin.designations.index', 'Designations',     'briefcase'],
            ],

            'Field Operations' => [
                ['admin.admin-log.index', 'Field Activity', 'clock'],
                ['admin.field-log.index', 'Field Log',      'clipboard-check'],
            ],

            'Sales' => [
                ['admin.leads.index',              'Leads',              'target'],
                ['admin.salesorders.index',        'Sales Orders',       'shopping-cart'],
                ['admin.customers.index',          'Customers',          'users'],
                ['admin.payment-management.index', 'Payment Management', 'receipt'],
                ['admin.products.index',           'Products',           'package'],
            ],

            'Finance' => [
                ['hr.payroll.index',   'Payroll',        'wallet'],
                ['hr.pf.index',        'PF & Gratuity',  'piggy-bank'],
                ['hr.incentive.index', 'Incentives',     'medal'],
            ],

            'Settings' => [
                ['hr.organization.index', 'Organization', 'building-2'],
                ['hr.settings.index',     'HR Settings',  'settings'],
                ['hr.system.index',       'HR System',    'shield'],
            ],
        ];
    }

    public function run(): void
    {
        DB::transaction(function () {
            $now = now();

            // The old "HR Management" group becomes "HR" (keeps its id).
            if (! DB::table('menus')->whereNull('parent_id')->where('name', 'HR')->exists()) {
                DB::table('menus')->whereNull('parent_id')
                    ->where('name', 'HR Management')
                    ->update(['name' => 'HR', 'updated_at' => $now]);
            }

            // 1) Groups
            $parentIds = [];
            foreach ($this->groups as $name => [$icon, $sort]) {
                $row = DB::table('menus')->whereNull('parent_id')->where('name', $name)->first();

                if ($row) {
                    DB::table('menus')->where('id', $row->id)->update([
                        'icon'       => $row->icon ?: $icon,
                        'sort_order' => $sort,
                        'updated_at' => $now,
                    ]);
                    $parentIds[$name] = $row->id;
                } else {
                    $parentIds[$name] = DB::table('menus')->insertGetId([
                        'name'       => $name,
                        'route_name' => null,
                        'icon'       => $icon,
                        'parent_id'  => null,
                        'sort_order' => $sort,
                        'status'     => 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }

            // Keep the (empty) Commission group after the new groups.
            DB::table('menus')->whereNull('parent_id')->where('name', 'Commission')
                ->update(['sort_order' => 108, 'updated_at' => $now]);

            // 2) Items
            foreach ($this->items() as $group => $items) {
                foreach ($items as $i => [$route, $name, $icon]) {
                    $position = $i + 1;

                    $existing = DB::table('menus')
                        ->where('route_name', $route)
                        ->whereNotNull('parent_id')
                        ->first();

                    if ($existing) {
                        DB::table('menus')->where('id', $existing->id)->update([
                            'parent_id'  => $parentIds[$group],
                            'sort_order' => $position,
                            'updated_at' => $now,
                        ]);
                    } else {
                        DB::table('menus')->insert([
                            'name'       => $name,
                            'route_name' => $route,
                            'icon'       => $icon,
                            'parent_id'  => $parentIds[$group],
                            'sort_order' => $position,
                            'status'     => 1,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }
                }
            }
        });
    }
}
