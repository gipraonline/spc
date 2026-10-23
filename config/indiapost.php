<?php

return [
    'base_url' => env('INDIAPOST_BASE_URL', 'https://test.cept.gov.in/beextcustomer'),
    'username' => env('INDIAPOST_USERNAME'),
    'password' => env('INDIAPOST_PASSWORD'),
    'timeout'  => (int) env('INDIAPOST_TIMEOUT', 30),
];
