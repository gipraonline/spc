<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Task assignment settings
    |--------------------------------------------------------------------------
    |
    | notify_levels : when an employee changes the status of a task, the
    |                 person who assigned it is always notified, plus this many
    |                 levels of reporting managers above the employee
    |                 (employee_masters.reporting_to). Use null for the whole
    |                 chain up to the top.
    |
    | default_priority : pre-selected priority on the "Assign task" form.
    |
    */

    'notify_levels' => 3,

    'default_priority' => 'medium',
];
