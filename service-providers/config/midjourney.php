<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midjourney API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Midjourney API integration through PiAPI
    |
    */

    'base_url' => env('MIDJOURNEY_BASE_URL', 'https://api.piapi.ai/api/v1'),
    'api_key' => env('MIDJOURNEY_API_KEY'),
    
    /*
    |--------------------------------------------------------------------------
    | Default Settings
    |--------------------------------------------------------------------------
    */
    
    'defaults' => [
        'process_mode' => 'fast', // relax, fast, turbo
        'aspect_ratio' => '1:1',
        'skip_prompt_check' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Polling Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for polling task status until completion
    |
    */
    
    'polling' => [
        'max_attempts' => env('MIDJOURNEY_POLLING_MAX_ATTEMPTS', 60), // Maximum polling attempts
        'interval_seconds' => env('MIDJOURNEY_POLLING_INTERVAL', 5), // Seconds between polls
    ],
]; 