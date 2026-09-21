<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CompanySettingSeeder::class,
        ]);

        $this->call([
            CategoryMasterSeeder::class,
        ]);

        $this->call([
            PanchaytahSeeder::class,
        ]);
    }
}
