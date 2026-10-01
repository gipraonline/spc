<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Field log GPS
    |--------------------------------------------------------------------------
    | When true, an employee cannot check in / out unless the browser shares
    | a location. Keep false while rolling out (desktop users often have no GPS);
    | the location is still saved whenever it is available.
    */
    'field_log_require_gps' => env('SPC_FIELD_LOG_REQUIRE_GPS', false),

    // GPS readings less accurate than this (metres) are flagged "low accuracy".
    'field_log_max_accuracy_m' => (int) env('SPC_FIELD_LOG_MAX_ACCURACY_M', 200),

    /*
    |--------------------------------------------------------------------------
    | Franchise assignment / coverage map
    |--------------------------------------------------------------------------
    */
    // Franchises farther than this from the order location are not suggested.
    'nearest_franchise_max_km' => (float) env('SPC_NEAREST_FRANCHISE_MAX_KM', 50),

    // Orders farther than this from every active franchise show as "under-served".
    'underserved_km' => (float) env('SPC_UNDERSERVED_KM', 25),

    /*
    |--------------------------------------------------------------------------
    | Sales orders that count towards incentives
    |--------------------------------------------------------------------------
    */
    'incentive' => [
        // Order approval status that makes a sale eligible.
        'order_status' => 'Approved',
        // Only count orders whose payment_status is one of these
        // (sales_orders.payment_status is stored lower-case).
        'payment_status' => ['paid'],
    ],
];
