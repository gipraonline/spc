<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

/**
 * Creates the permissions used by the new Excel exports and the franchise-wise
 * sales summary. Nothing is granted automatically: tick them for the roles that
 * should be allowed to export (Roles screen), because an export contains the
 * whole filtered list, not just one page.
 */
return new class extends Migration
{
    private array $permissions = [
        'sales-orders.export',
        'customers.export',
        'franchises.export',
        'employees.export',
        'franchise-sales-report.view',
        'franchise-sales-report.export',
    ];

    public function up(): void
    {
        foreach ($this->permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        Permission::whereIn('name', $this->permissions)->where('guard_name', 'web')->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
