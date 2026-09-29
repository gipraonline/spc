<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Aligns the `roles` (access control) and `designation_masters` (reporting
 * hierarchy) tables with the 23-role organizational chart supplied by the
 * client, WITHOUT deleting or re-creating anything the app already depends
 * on by id/identifier.
 *
 * Why not a hard delete-and-recreate:
 *  - `roles.id` is referenced by `model_has_roles` (every logged-in user's
 *    role assignment) and `role_has_permissions`. Deleting rows would
 *    unassign every employee's role and wipe their permissions.
 *  - Several controllers hardcode role identifiers directly
 *    (SUPER_ADMIN, GIPRA_ADMIN, NSH, RSH, TL, FCO, FCA, TC) — e.g.
 *    SalesController, LeadsController, EmployeeController. Recreating
 *    those roles with new ids is harmless (identifier lookups still
 *    work), but deleting them first would break any employee currently
 *    assigned that role.
 *  - `designation_masters.hierarchy_level` drives "who can be picked as a
 *    reporting manager" (EmployeeController::getReportingManagers and the
 *    employee list filters) via relative comparisons (>, <, -1). Existing
 *    levels are renumbered in place to fit the new 1-9 scale rather than
 *    dropped, so those relative comparisons keep working for employees
 *    already assigned a designation.
 *
 * What this migration does:
 *  1. Renumbers the 5 existing hierarchy designations (NSH, RSH, TL, FCO,
 *     FCA) onto the new 9-level scale so they line up with the org chart.
 *  2. Adds the "Tele Caller" role with identifier TC — code in
 *     SalesController already checks for this identifier, but the role
 *     row never existed, so this switches on already-written logic.
 *  3. Adds every other new role from the org chart (Chairman down to
 *     Franchise) that isn't already in the system.
 *  4. Adds a matching designation_masters row for every new role that has
 *     a level in the org chart, so reporting-hierarchy filtering extends
 *     to them automatically. Super Admin, Gipra Admin, and Tele Caller
 *     have no level in the chart (org chart shows "—"), so — matching the
 *     current treatment of Super Admin/Gipra Admin — they get no
 *     designation row, which means they aren't hierarchy-filtered.
 *
 * Everything is done with firstOrCreate/updateOrCreate style checks so
 * running this twice (or on a database that already has some of these
 * rows) is safe.
 */
return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        // ------------------------------------------------------------------
        // 1. Renumber existing hierarchy designations onto the 1-9 scale.
        //    (identifier => new hierarchy_level)
        // ------------------------------------------------------------------
        $levelUpdates = [
            'NSH' => 6,
            'RSH' => 6,
            'TL'  => 7,
            'FCO' => 8,
            'FCA' => 9,
        ];

        foreach ($levelUpdates as $identifier => $level) {
            DB::table('designation_masters')
                ->where('identifier', $identifier)
                ->update([
                    'hierarchy_level' => $level,
                    'updated_at' => $now,
                ]);
        }

        // ------------------------------------------------------------------
        // 2 & 3. New roles from the org chart. `level` is the corresponding
        //    designation_masters hierarchy_level, or null for roles that
        //    sit outside the numbered hierarchy (org chart shows "—").
        // ------------------------------------------------------------------
        $newRoles = [
            ['name' => 'Chairman',                 'identifier' => 'CHAIRMAN',     'level' => 1],
            ['name' => 'Managing Director',         'identifier' => 'MD',           'level' => 2],
            ['name' => 'Chief Executive Officer',   'identifier' => 'CEO',          'level' => 3],
            ['name' => 'COO / CFO',                 'identifier' => 'COO_CFO',      'level' => 4],
            ['name' => 'GM',                        'identifier' => 'GM',           'level' => 5],
            ['name' => 'Finance',                   'identifier' => 'FINANCE',      'level' => 5],
            ['name' => 'Marketing Manager',         'identifier' => 'MKT_MGR',      'level' => 5],
            ['name' => 'HR Team',                   'identifier' => 'HR_TEAM',      'level' => 6],
            ['name' => 'Senior Accountant',         'identifier' => 'SR_ACCT',      'level' => 6],
            ['name' => 'AGM / Operational Manager', 'identifier' => 'AGM_OM',       'level' => 6],
            ['name' => 'Office Administration',     'identifier' => 'OFFICE_ADMIN', 'level' => 6],
            ['name' => 'Accountant',                'identifier' => 'ACCOUNTANT',   'level' => 7],
            ['name' => 'Franchise Manager',         'identifier' => 'FR_MGR',       'level' => 7],
            ['name' => 'Agro Clinic',               'identifier' => 'AGRO_CLINIC',  'level' => 8],
            ['name' => 'Franchise',                 'identifier' => 'FRANCHISE',    'level' => 9],
            ['name' => 'Tele Caller',               'identifier' => 'TC',           'level' => null],
        ];

        // HR Manager (HRM) already exists as a role, but never had a spot
        // in the reporting hierarchy. Give it one now (Level 5) without
        // touching the existing role row.
        $existingHrmDesignation = DB::table('designation_masters')->where('identifier', 'HRM')->exists();
        if (! $existingHrmDesignation) {
            DB::table('designation_masters')->insert([
                'identifier' => 'HRM',
                'c_designation' => 'HR Manager',
                'hierarchy_level' => 5,
                'c_status' => 'Y',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        foreach ($newRoles as $role) {
            $roleExists = DB::table('roles')
                ->where('identifier', $role['identifier'])
                ->orWhere('name', $role['name'])
                ->exists();

            if (! $roleExists) {
                DB::table('roles')->insert([
                    'name' => $role['name'],
                    'guard_name' => 'web',
                    'identifier' => $role['identifier'],
                    'hr_access' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            if ($role['level'] !== null) {
                $designationExists = DB::table('designation_masters')
                    ->where('identifier', $role['identifier'])
                    ->exists();

                if (! $designationExists) {
                    DB::table('designation_masters')->insert([
                        'identifier' => $role['identifier'],
                        'c_designation' => $role['name'],
                        'hierarchy_level' => $role['level'],
                        'c_status' => 'Y',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }

    /**
     * Reversing only removes the rows this migration added (matched by
     * identifier) and restores the original hierarchy_level numbers for
     * the pre-existing designations. It never deletes Super Admin, Gipra
     * Admin, HR Manager, or any role/designation that existed before this
     * migration.
     */
    public function down(): void
    {
        $addedIdentifiers = [
            'CHAIRMAN', 'MD', 'CEO', 'COO_CFO', 'GM', 'FINANCE', 'MKT_MGR',
            'HR_TEAM', 'SR_ACCT', 'AGM_OM', 'OFFICE_ADMIN', 'ACCOUNTANT',
            'FR_MGR', 'AGRO_CLINIC', 'FRANCHISE', 'TC',
        ];

        DB::table('designation_masters')->whereIn('identifier', $addedIdentifiers)->delete();
        DB::table('roles')->whereIn('identifier', $addedIdentifiers)->delete();
        DB::table('designation_masters')->where('identifier', 'HRM')->delete();

        $originalLevels = [
            'NSH' => 1,
            'RSH' => 2,
            'TL'  => 3,
            'FCO' => 4,
            'FCA' => 5,
        ];

        foreach ($originalLevels as $identifier => $level) {
            DB::table('designation_masters')
                ->where('identifier', $identifier)
                ->update(['hierarchy_level' => $level, 'updated_at' => now()]);
        }
    }
};
