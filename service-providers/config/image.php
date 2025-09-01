<?php

return [
    'base_url' => env('IMAGE_BASE_URL', 'https://publiish.io'),
    'max_file_size' => 500 * 1024, // 500MB
    'upload_time_out' => 5, // 5 minutes
    'auth' => [
        'email' => env('PUBLIISH_AUTH_EMAIL'),
        'password' => env('PUBLIISH_AUTH_PASSWORD'),
    ],
];