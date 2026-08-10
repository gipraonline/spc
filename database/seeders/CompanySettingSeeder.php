<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanySetting;

class CompanySettingSeeder extends Seeder
{
    public function run(): void
    {
        CompanySetting::create([
            'company_name' => 'SPC Spices Producers Company',

            'address' => '123, Spice Valley Road, Kochi, Kerala, India - 682018',

            'phone' => '+91 98765 43210',

            'email' => 'info@spcspices.com',

            'website' => 'www.spcspices.com',

            'bank_name' => 'State Bank of India',

            'account_name' => 'SPC Spices Producers Company',

            'account_number' => '1234567890',

            'ifsc_code' => 'ABCD0123456',

            'branch' => 'Kochi',
        ]);
    }
}