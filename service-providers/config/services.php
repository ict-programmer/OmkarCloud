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
            'folder:read'
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

    'pexels' => [
        'api_key' => env('PEXELS_API_KEY'),
    ],

    'gettyimages' => [
        'api_key' => env('GETTYIMAGES_API_KEY'),
    ],
];
