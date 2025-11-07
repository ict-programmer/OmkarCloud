<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
    ],

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
    ],

    'anthropic' => [
        'api_key' => env('ANTHROPIC_API_KEY'),
    ],

    'deep_seek' => [
        'api_key' => env('DEEP_SEEK_API_KEY'),
    ],

    'ffmpeg' => [
        'path' => env('FFMPEG_PATH', '/usr/bin/ffmpeg'),
    ],

    'ffprobe' => [
        'path' => env('FFPROBE_PATH', '/usr/bin/ffprobe'),
    ],

    'runwayml' => [
        'api_key' => env('RUNWAYML_API_KEY'),
    ],

    'canva' => [
        'api_key' => env('CANVA_API_KEY'),
        'api_secret' => env('CANVA_API_SECRET'),
        'scopes' => [
            'asset:read',
            'asset:write',
            'brandtemplate:content:read',
            'brandtemplate:meta:read',
            'design:content:read',
            'design:content:write',
            'design:meta:read',
            'profile:read',
            'folder:write',
            'folder:read',
        ],
    ],
    'perplexity' => [
        'api_key' => env('PERPLEXITY_API_KEY'),
        'search' => [
            'news' => [
                'search_domain_filter' => [
                    'bbc.com',
                    'reuters.com',
                    'apnews.com',
                    'theguardian.com',
                    'nytimes.com',
                    'washingtonpost.com',
                    'wsj.com',
                    'aljazeera.com',
                    'ft.com',
                    'economist.com',
                ],
                'web_search_options' => [
                    'search_context_size' => 'medium',
                ],
            ],
            'web' => [
                'web_search_options' => [
                    'search_context_size' => 'medium',
                ],
            ],
        ],
        'academic' => [
            'search_domain_filter' => [
                'pubmed.ncbi.nlm.nih.gov',
                'pmc.ncbi.nlm.nih.gov',
                'jstor.org',
                'sciencedirect.com',
                'ieeexplore.ieee.org',
                'eric.ed.gov',
                'science.gov',
                'semanticscholar.org',
                'base-search.net',
                'dblp.org',
                'network.bepress.com',
                'nature.com',
                'science.org',
                'plos.org',
                'link.springer.com',
                'doaj.org',
                'arxiv.org',
                'biorxiv.org',
                'medrxiv.org',
                'ssrn.com',
            ],
            'web_search_options' => [
                'search_context_size' => 'high',
            ],
        ],
    ],

    'qwen' => [
        'api_key' => env('QWEN_API_KEY'),
        'base_url' => env('QWEN_BASE_URL', 'https://openrouter.ai/api/v1'),
    ],

    'premierpro' => [
        'api_key' => env('PREMIERPRO_API_KEY'),
        'api_secret' => env('PREMIERPRO_API_SECRET'),
    ],

    'descriptai' => [
        'api_key' => env('DESCRIPTAI_API_KEY'),
    ],

    'omkarcloud' => [
        'base_url' => env('OMKAR_MAPS_BASE_URL', 'https://api.omkar.cloud/maps'),
        // do NOT store api key here if you rotate often; keep in ENV
    ],
    'pexels' => [
        'api_key' => env('PEXELS_API_KEY'),
    ],

    'freepik' => [
        'api_key' => env('FREEPIK_API_KEY'),
        'webhook_secret' => env('FREEPIK_WEBHOOK_SECRET'),
        'webhook_url' => env('FREEPIK_WEBHOOK_URL'),
    ],

    'gettyimages' => [
        'api_key' => env('GETTYIMAGES_API_KEY'),
    ],

    'captions' => [
        'api_key' => env('CAPTIONS_API_KEY'),
    ],

    'google' => [
        'custom_search' => [
            'api_key' => env('GOOGLE_CUSTOM_SEARCH_API_KEY'),
            'search_engine_id' => env('GOOGLE_CUSTOM_SEARCH_ENGINE_ID'),
        ],
    ],
     'billionmail' => [
        'api_url' => env('BILLIONMAIL_API_URL', 'https://10.253.10.66:8888//api/batch_mail/api'),
        'api_key' => env('BILLIONMAIL_API_KEY', 'b98020dae4c501b9e61be56d7312d6bb99595e9b190079e88308d732a6519ec3'),
    ],

    'shotstack' => [
        'api_key' => env('SHOTSTACK_API_KEY'),
    ],
];
