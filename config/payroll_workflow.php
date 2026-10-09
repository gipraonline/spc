<?php

/*
|--------------------------------------------------------------------------
| Payroll approval workflow
|--------------------------------------------------------------------------
| HR runs payroll -> COO approves -> MD approves -> Finance marks paid.
| People are matched by the designation title on their employee record
| (case-insensitive), so no new login roles are needed. Add alternative
| spellings here if your designation titles differ.
*/

return [
    'coo' => ['COO / CFO', 'COO', 'Chief Operating Officer'],
    'md' => ['Managing Director', 'MD'],
    'finance' => ['Finance', 'Finance Manager'],
];
