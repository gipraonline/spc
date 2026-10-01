<?php

/*
|--------------------------------------------------------------------------
| Payroll statutory tables (India)
|--------------------------------------------------------------------------
| Income-tax new regime, FY 2026-27 (Budget 2026 left the slabs unchanged).
| Surcharge is not modelled (applies only above Rs 50 lakh taxable income).
| Rates that HR may need to tweak without a deploy (PF / ESI / professional
| tax slabs, PF ceiling, weekly off) live in system_settings instead and are
| edited on HR > Settings.
*/

return [

    'tds' => [
        // [upper bound of slab, rate]; last slab is open-ended
        'new_regime_slabs' => [
            [400000, 0.00],
            [800000, 0.05],
            [1200000, 0.10],
            [1600000, 0.15],
            [2000000, 0.20],
            [2400000, 0.25],
            [null, 0.30],
        ],
        'rebate_limit' => 1200000,   // Sec 87A: no tax up to this taxable income
        'rebate_max'   => 60000,
        'cess'         => 0.04,
    ],

];
