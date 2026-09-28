<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Full org-chart role list. Existing installs get these via the
        // 2026_09_23_060000_align_roles_and_designations_with_org_chart
        // migration; this seeder covers fresh installs.
        $roles = [
            ['name' => 'Super Admin',              'identifier' => 'SUPER_ADMIN'],
            ['name' => 'Gipra Admin',               'identifier' => 'GIPRA_ADMIN'],
            ['name' => 'Chairman',                  'identifier' => 'CHAIRMAN'],
            ['name' => 'Managing Director',         'identifier' => 'MD'],
            ['name' => 'Chief Executive Officer',   'identifier' => 'CEO'],
            ['name' => 'COO / CFO',                 'identifier' => 'COO_CFO'],
            ['name' => 'GM',                        'identifier' => 'GM'],
            ['name' => 'HR Manager',                'identifier' => 'HRM'],
            ['name' => 'Finance',                   'identifier' => 'FINANCE'],
            ['name' => 'Marketing Manager',         'identifier' => 'MKT_MGR'],
            ['name' => 'HR Team',                   'identifier' => 'HR_TEAM'],
            ['name' => 'Senior Accountant',         'identifier' => 'SR_ACCT'],
            ['name' => 'AGM / Operational Manager', 'identifier' => 'AGM_OM'],
            ['name' => 'Office Administration',     'identifier' => 'OFFICE_ADMIN'],
            ['name' => 'Regional Sales Head',       'identifier' => 'RSH'],
            ['name' => 'National Sales Head',       'identifier' => 'NSH'],
            ['name' => 'Accountant',                'identifier' => 'ACCOUNTANT'],
            ['name' => 'Franchise Manager',         'identifier' => 'FR_MGR'],
            ['name' => 'Team Lead',                 'identifier' => 'TL'],
            ['name' => 'Agro Clinic',               'identifier' => 'AGRO_CLINIC'],
            ['name' => 'Farm Care Officer',         'identifier' => 'FCO'],
            ['name' => 'Franchise',                 'identifier' => 'FRANCHISE'],
            ['name' => 'Farm Care Advisor',         'identifier' => 'FCA'],
            ['name' => 'Tele Caller',               'identifier' => 'TC'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['identifier' => $role['identifier']],
                ['name' => $role['name'], 'guard_name' => 'web']
            );
        }
    }
}