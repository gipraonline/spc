<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use Illuminate\Database\Seeder;

class CompanySettingSeeder extends Seeder
{
    public function run(): void
    {
        CompanySetting::updateOrCreate(
            
            ['id' => 1],
            [
                'company_name' => 'SPICES PRODUCER COMPANY LIMITED',

                'gst_number' => '33AASCS9554J1ZL',

                'address' => 'SF No. 790/1A1, SF No. 808/1B
K M P Thottam, Opp RTO Check Post,
Palakkad Road,
Madukkarai,
Coimbatore - 641105',

                'phone' => '8592841999',

                'email' => 'info@spcuinversal.com',

                'website' => 'https://spcuniversal.com',

                'bank_name' => null,

                'account_name' => 'SPICES PRODUCER COMPANY LIMITED',

                'account_number' => null,

                'ifsc_code' => null,

                'branch' => null,
            ]
        );
    }
}
