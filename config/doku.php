<?php

return [
    'client_id' => env('DOKU_CLIENT_ID', null),
    'secret_key' => env('DOKU_SECRET_KEY', null),
    'expired_time' => env('DOKU_EXPIRED_TIME', 1440),
    'is_production' => env('DOKU_PRODUCTION', false)
];
