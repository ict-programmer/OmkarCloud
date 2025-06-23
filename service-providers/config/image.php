<?php

return [
    'base_url' => env('IMAGE_BASE_URL', 'https://publiish.io'),
    'max_file_size' => 500 * 1024, // 500MB
    'upload_time_out' => 0,
    'default_email' => env("PUBLIISH_IO_EMAIL"),
    'default_password' => env("PUBLIISH_IO_PASSWORD"),
];