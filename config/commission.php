<?php

/*
|--------------------------------------------------------------------------
| Associate commission (Farm Care Advisers + Tele Callers)
|--------------------------------------------------------------------------
| The National Sales Head, COO or MD sets the commission percentage. HR calculates the
| month from eligible sales (same rules as incentives: config/spc.php
| 'incentive'), then it is approved by COO -> MD and paid by Finance.
|
| People are matched by the designation title on their HR employee record
| (case-insensitive). Add alternative spellings here if yours differ.
*/

return [
    // SPC designation identifier => label
    'designations' => [
        'FCA' => 'Farm Care Advisor',
        'TC' => 'Tele Caller',
    ],

    'roles' => [
        'national_sales_head' => ['National Sales Head'],
        'coo' => ['COO / CFO', 'COO', 'Chief Operating Officer'],
        'md' => ['Managing Director', 'MD'],
        'finance' => ['Finance', 'Finance Manager'],
    ],
];