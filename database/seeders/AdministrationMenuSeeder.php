<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class AdministrationMenuSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Menu::updateOrCreate(
            ['name' => 'Administration'],
            [
                'route_name' => null,
                'icon' => 'settings',
                'parent_id' => null,
                'sort_order' => 999,
                'status' => 1,
            ]
        );

        Menu::updateOrCreate(
            ['route_name' => 'admin.menus.index'],
            [
                'name' => 'Menu Management',
                'icon' => 'menu',
                'parent_id' => $admin->id,
                'sort_order' => 1,
                'status' => 1,
            ]
        );

        Menu::updateOrCreate(
            ['route_name' => 'admin.roles.index'],
            [
                'name' => 'Role Management',
                'icon' => 'shield',
                'parent_id' => $admin->id,
                'sort_order' => 2,
                'status' => 1,
            ]
        );

        Menu::updateOrCreate(
            ['route_name' => 'admin.users.index'],
            [
                'name' => 'User Management',
                'icon' => 'user-cog',
                'parent_id' => $admin->id,
                'sort_order' => 3,
                'status' => 1,
            ]
        );
    }
}