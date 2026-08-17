<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Panchayath;
use App\Models\State;
use Illuminate\Database\Seeder;

class PanchayathSeeder extends Seeder
{
    public function run(): void
    {
        $kerala = State::where('name', 'Kerala')->first();

        if (! $kerala) {
            $this->command->error('Kerala state not found.');

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Panchayaths from Franchise Data
        |--------------------------------------------------------------------------
        |
        | code = official/local code where currently known
        | null = code not available yet
        |
        */

        $panchayaths = [

            // =================================================================
            // THIRUVANANTHAPURAM
            // =================================================================

            [
                'district' => 'Thiruvananthapuram',
                'name' => 'PALLIKKAL',
                'code' => 'G010205',
            ],
            [
                'district' => 'Thiruvananthapuram',
                'name' => 'ANDOORKONAM',
                'code' => 'G010701',
            ],
            [
                'district' => 'Thiruvananthapuram',
                'name' => 'NEMOM',
                'code' => null,
            ],
            [
                'district' => 'Thiruvananthapuram',
                'name' => 'CHENNILODE',
                'code' => null,
            ],
            [
                'district' => 'Thiruvananthapuram',
                'name' => 'ELAKAMON',
                'code' => 'G010103',
            ],
            [
                'district' => 'Thiruvananthapuram',
                'name' => 'POOVACHAL',
                'code' => 'G010502',
            ],
            [
                'district' => 'Thiruvananthapuram',
                'name' => 'KALLARA',
                'code' => 'G010401',
            ],
            [
                'district' => 'Thiruvananthapuram',
                'name' => 'PALLICHAL',
                'code' => 'G010802',
            ],

            // =================================================================
            // KOLLAM
            // =================================================================

            [
                'district' => 'Kollam',
                'name' => 'CHAVARA',
                'code' => 'G020201',
            ],
            [
                'district' => 'Kollam',
                'name' => 'MELILA',
                'code' => 'G020801',
            ],
            [
                'district' => 'Kollam',
                'name' => 'VILAKKUDI',
                'code' => 'G020802',
            ],
            [
                'district' => 'Kollam',
                'name' => 'OCHIRA',
                'code' => 'G020103',
            ],
            [
                'district' => 'Kollam',
                'name' => 'PANMANA',
                'code' => 'G020202',
            ],
            [
                'district' => 'Kollam',
                'name' => 'POOYAPPALLY',
                'code' => 'G020605',
            ],
            [
                'district' => 'Kollam',
                'name' => 'KOLLAM DT FRANCHISE',
                'code' => null,
            ],
            [
                'district' => 'Kollam',
                'name' => 'KULAKKADA',
                'code' => 'G020804',
            ],
            [
                'district' => 'Kollam',
                'name' => 'SOORANADU SOUTH',
                'code' => 'G020404',
            ],
            [
                'district' => 'Kollam',
                'name' => 'SOORANADU NORTH',
                'code' => 'G020403',
            ],
            [
                'district' => 'Kollam',
                'name' => 'EZHUKONE',
                'code' => 'G020803',
            ],
            [
                'district' => 'Kollam',
                'name' => 'VETTIKAVALA',
                'code' => 'G020805',
            ],

            // =================================================================
            // PATHANAMTHITTA
            // =================================================================

            [
                'district' => 'Pathanamthitta',
                'name' => 'KOIPURAM',
                'code' => 'G030502',
            ],
            [
                'district' => 'Pathanamthitta',
                'name' => 'THIRUVALLA',
                'code' => null,
            ],
            [
                'district' => 'Pathanamthitta',
                'name' => 'KADAPRA',
                'code' => 'G030402',
            ],
            [
                'district' => 'Pathanamthitta',
                'name' => 'CHERUKOLE',
                'code' => 'G030503',
            ],
            [
                'district' => 'Pathanamthitta',
                'name' => 'KAVIYOOR',
                'code' => 'G030403',
            ],
            [
                'district' => 'Pathanamthitta',
                'name' => 'KODUMON',
                'code' => 'G030806',
            ],
            [
                'district' => 'Pathanamthitta',
                'name' => 'MEZHUVELI',
                'code' => 'G030705',
            ],
            [
                'district' => 'Pathanamthitta',
                'name' => 'KONNI',
                'code' => 'G030601',
            ],

            // =================================================================
            // ALAPPUZHA
            // =================================================================

            [
                'district' => 'Alappuzha',
                'name' => 'CHEPPAD',
                'code' => 'G040801',
            ],
            [
                'district' => 'Alappuzha',
                'name' => 'PANDANAD',
                'code' => 'G040807',
            ],
            [
                'district' => 'Alappuzha',
                'name' => 'KANJIKUZHI',
                'code' => 'G040602',
            ],
            [
                'district' => 'Alappuzha',
                'name' => 'THIRUVANVANDOOR',
                'code' => 'G040808',
            ],
            [
                'district' => 'Alappuzha',
                'name' => 'MAVELIKKARA',
                'code' => null,
            ],
            [
                'district' => 'Alappuzha',
                'name' => 'THURAVOOR',
                'code' => 'G040301',
            ],
            [
                'district' => 'Alappuzha',
                'name' => 'THALAVADY',
                'code' => null,
            ],

            // =================================================================
            // KOTTAYAM
            // =================================================================

            [
                'district' => 'Kottayam',
                'name' => 'AKALAKKUNNAM',
                'code' => 'G050701',
            ],
            [
                'district' => 'Kottayam',
                'name' => 'PARATHODU',
                'code' => 'G050802',
            ],
            [
                'district' => 'Kottayam',
                'name' => 'NEENDOOR',
                'code' => 'G050503',
            ],
            [
                'district' => 'Kottayam',
                'name' => 'KUMARAKOM',
                'code' => 'G050601',
            ],
            [
                'district' => 'Kottayam',
                'name' => 'KOTTAYAM',
                'code' => null,
            ],
            [
                'district' => 'Kottayam',
                'name' => 'ETTUMANOOR',
                'code' => null,
            ],
            [
                'district' => 'Kottayam',
                'name' => 'KANJIRAPPALLY',
                'code' => 'G050801',
            ],
            [
                'district' => 'Kottayam',
                'name' => 'VAIKKOM',
                'code' => 'G050201',
            ],
            [
                'district' => 'Kottayam',
                'name' => 'VAKATHANAM',
                'code' => 'G050302',
            ],
            [
                'district' => 'Kottayam',
                'name' => 'MANEED',
                'code' => null,
            ],
            [
                'district' => 'Kottayam',
                'name' => 'ELANJI',
                'code' => null,
            ],

            // =================================================================
            // ERNAKULAM
            // =================================================================

            [
                'district' => 'Ernakulam',
                'name' => 'NORTH PARAVOOR',
                'code' => null,
            ],
            [
                'district' => 'Ernakulam',
                'name' => 'RAYAMANGALAM',
                'code' => 'G070903',
            ],
            [
                'district' => 'Ernakulam',
                'name' => 'VADAVUKODE PUTHENKURISU',
                'code' => 'G070902',
            ],
            [
                'district' => 'Ernakulam',
                'name' => 'VENGOLA',
                'code' => 'G071001',
            ],
            [
                'district' => 'Ernakulam',
                'name' => 'AMBALLOOR',
                'code' => 'G070905',
            ],
            [
                'district' => 'Ernakulam',
                'name' => 'VENGOOR',
                'code' => 'G071004',
            ],
            [
                'district' => 'Ernakulam',
                'name' => 'THIRUVANIYOOR',
                'code' => 'G070904',
            ],
            [
                'district' => 'Ernakulam',
                'name' => 'KANJIRAMATTOM PO AMBALOOR',
                'code' => null,
            ],

            // =================================================================
            // THRISSUR
            // =================================================================

            [
                'district' => 'Thrissur',
                'name' => 'KATTAKAMPAL',
                'code' => 'G080302',
            ],
            [
                'district' => 'Thrissur',
                'name' => 'ANTHIKKAD',
                'code' => 'G080201',
            ],
            [
                'district' => 'Thrissur',
                'name' => 'VELLANGALLOOR',
                'code' => null,
            ],
            [
                'district' => 'Thrissur',
                'name' => 'NENMENIKKARA',
                'code' => 'G080703',
            ],
            [
                'district' => 'Thrissur',
                'name' => 'POYYA',
                'code' => 'G080903',
            ],
            [
                'district' => 'Thrissur',
                'name' => 'THANNIYAM',
                'code' => 'G080303',
            ],
            [
                'district' => 'Thrissur',
                'name' => 'KUZHUR',
                'code' => 'G080902',
            ],
            [
                'district' => 'Thrissur',
                'name' => 'ALAGAPPANAGAR',
                'code' => 'G080601',
            ],
            [
                'district' => 'Thrissur',
                'name' => 'PARALAM',
                'code' => 'G080504',
            ],
            [
                'district' => 'Thrissur',
                'name' => 'VELUR',
                'code' => 'G080404',
            ],
            [
                'district' => 'Thrissur',
                'name' => 'CHOONDAL',
                'code' => 'G080301',
            ],
            [
                'district' => 'Thrissur',
                'name' => 'VENKITANGU',
                'code' => 'G080202',
            ],
            [
                'district' => 'Thrissur',
                'name' => 'MADAKKATHARA',
                'code' => 'G080701',
            ],
            [
                'district' => 'Thrissur',
                'name' => 'TRISSUR DISTRICT FRANCHISE',
                'code' => null,
            ],
            [
                'district' => 'Thrissur',
                'name' => 'VALAPPAD',
                'code' => 'G080101',
            ],
            [
                'district' => 'Thrissur',
                'name' => 'AVANUR',
                'code' => 'G080102',
            ],
            [
                'district' => 'Thrissur',
                'name' => 'PUTHUR',
                'code' => 'G080704',
            ],
            [
                'district' => 'Thrissur',
                'name' => 'KAIPPAMANGALAM',
                'code' => 'G080401',
            ],
            [
                'district' => 'Thrissur',
                'name' => 'PUDUKKAD',
                'code' => 'G080803',
            ],
            [
                'district' => 'Thrissur',
                'name' => 'CHALAKKUDY',
                'code' => 'G081001',
            ],
            [
                'district' => 'Thrissur',
                'name' => 'KODASSERY',
                'code' => 'G081002',
            ],
            [
                'district' => 'Thrissur',
                'name' => 'PUNNAYUR',
                'code' => 'G080403',
            ],
            [
                'district' => 'Thrissur',
                'name' => 'DHESAMANGALAM',
                'code' => 'G080503',
            ],
            [
                'district' => 'Thrissur',
                'name' => 'ANNAMANADA',
                'code' => 'G080901',
            ],
            [
                'district' => 'Thrissur',
                'name' => 'CHELAKKARA',
                'code' => 'G080501',
            ],
            [
                'district' => 'Thrissur',
                'name' => 'PANANCHERY',
                'code' => 'G080702',
            ],
            [
                'district' => 'Thrissur',
                'name' => 'KODUNGALLUR',
                'code' => 'G080402',
            ],
            [
                'district' => 'Thrissur',
                'name' => 'KADUKKUTTY',
                'code' => 'G081101',
            ],

            // =================================================================
            // PALAKKAD
            // =================================================================

            [
                'district' => 'Palakkad',
                'name' => 'THIRUMITTACODE',
                'code' => 'G090106',
            ],
            [
                'district' => 'Palakkad',
                'name' => 'MALAMPUZHA',
                'code' => 'G090601',
            ],
            [
                'district' => 'Palakkad',
                'name' => 'KIZHAKKANCHERY',
                'code' => 'G090801',
            ],
            [
                'district' => 'Palakkad',
                'name' => 'ANAKKARA',
                'code' => 'G090101',
            ],
            [
                'district' => 'Palakkad',
                'name' => 'PATTITHARA',
                'code' => 'G090105',
            ],
            [
                'district' => 'Palakkad',
                'name' => 'MANNUR',
                'code' => 'G091005',
            ],
            [
                'district' => 'Palakkad',
                'name' => 'ALANALLUR',
                'code' => 'G091201',
            ],
            [
                'district' => 'Palakkad',
                'name' => 'PALAKKAD DISTRICT FRANCHISE',
                'code' => null,
            ],

            // =================================================================
            // MALAPPURAM
            // =================================================================

            [
                'district' => 'Malappuram',
                'name' => 'KARUVARAKUNDU',
                'code' => 'G100205',
            ],
            [
                'district' => 'Malappuram',
                'name' => 'EDAPPAL',
                'code' => 'G100106',
            ],
            [
                'district' => 'Malappuram',
                'name' => 'MUTHUVALLUR',
                'code' => 'G100409',
            ],
            [
                'district' => 'Malappuram',
                'name' => 'MAKKARAPARAMBA',
                'code' => null,
            ],
            [
                'district' => 'Malappuram',
                'name' => 'KANNAMANGALAM',
                'code' => null,
            ],
            [
                'district' => 'Malappuram',
                'name' => 'THENNALA',
                'code' => null,
            ],
            [
                'district' => 'Malappuram',
                'name' => 'PULIKKAL',
                'code' => null,
            ],

            // =================================================================
            // KOZHIKODE
            // =================================================================

            [
                'district' => 'Kozhikode',
                'name' => 'PURAMERY',
                'code' => null,
            ],
            [
                'district' => 'Kozhikode',
                'name' => 'PERUMANNA',
                'code' => null,
            ],
            [
                'district' => 'Kozhikode',
                'name' => 'MUKKAM',
                'code' => null,
            ],
            [
                'district' => 'Kozhikode',
                'name' => 'KODIYATHUR',
                'code' => null,
            ],
            [
                'district' => 'Kozhikode',
                'name' => 'KODENCHERY',
                'code' => null,
            ],
            [
                'district' => 'Kozhikode',
                'name' => 'FRANCHISE FILES',
                'code' => null,
            ],
            [
                'district' => 'Kozhikode',
                'name' => 'VELOM',
                'code' => null,
            ],

            // =================================================================
            // KANNUR
            // =================================================================

            [
                'district' => 'Kannur',
                'name' => 'KUTTIYATTOOR',
                'code' => null,
            ],
            [
                'district' => 'Kannur',
                'name' => 'PANOOR',
                'code' => null,
            ],
            [
                'district' => 'Kannur',
                'name' => 'PAYAM',
                'code' => null,
            ],
            [
                'district' => 'Kannur',
                'name' => 'ALAKODE',
                'code' => null,
            ],
            [
                'district' => 'Kannur',
                'name' => 'ANTHOOR',
                'code' => null,
            ],
            [
                'district' => 'Kannur',
                'name' => 'UDAYAGIRI',
                'code' => null,
            ],
            [
                'district' => 'Kannur',
                'name' => 'PADIYUR',
                'code' => null,
            ],
            [
                'district' => 'Kannur',
                'name' => 'ERAMAMKUTTOOR',
                'code' => null,
            ],
            [
                'district' => 'Kannur',
                'name' => 'PERINGOM VAYALKARA',
                'code' => null,
            ],
            [
                'district' => 'Kannur',
                'name' => 'THALASSERY',
                'code' => null,
            ],
            [
                'district' => 'Kannur',
                'name' => 'CHELORA',
                'code' => null,
            ],
            [
                'district' => 'Kannur',
                'name' => 'KOLACHERI',
                'code' => null,
            ],
            [
                'district' => 'Kannur',
                'name' => 'MUNDERI',
                'code' => null,
            ],

            // =================================================================
            // WAYANAD
            // =================================================================

            [
                'district' => 'Wayanad',
                'name' => 'AMBALAVAYAL',
                'code' => null,
            ],

            // =================================================================
            // KASARAGOD
            // =================================================================

            [
                'district' => 'Kasaragod',
                'name' => 'PADNE',
                'code' => null,
            ],
            [
                'district' => 'Kasaragod',
                'name' => 'CHERUVATHUR',
                'code' => null,
            ],
            [
                'district' => 'Kasaragod',
                'name' => 'ENMAKAJE',
                'code' => null,
            ],
            [
                'district' => 'Kasaragod',
                'name' => 'AJANUR',
                'code' => null,
            ],
            [
                'district' => 'Kasaragod',
                'name' => 'KUMBADAJE',
                'code' => null,
            ],
            [
                'district' => 'Kasaragod',
                'name' => 'PALLIKKARE',
                'code' => null,
            ],
        ];

        $count = 0;

        foreach ($panchayaths as $item) {

            $district = District::whereRaw(
                'LOWER(TRIM(district_name)) = ?',
                [strtolower(trim($item['district']))]
            )->first();

            if (! $district) {

                $this->command->warn(
                    "District not found: {$item['district']} ".
                    "for Panchayath: {$item['name']}"
                );

                continue;
            }

            Panchayath::updateOrCreate(
                [
                    'district_id' => $district->id,
                    'panchayath_name' => $item['name'],
                ],
                [
                    'state_id' => $kerala->n_state_id,
                    'panchayath_code' => $item['code'],
                    'status' => 1,
                ]
            );

            $count++;
        }

        $this->command->info(
            "Panchayath seeding completed. {$count} records processed."
        );
    }
}
