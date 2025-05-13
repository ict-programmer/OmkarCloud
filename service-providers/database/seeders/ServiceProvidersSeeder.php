<?php

namespace Database\Seeders;

use App\Models\ServiceProvider;
use Illuminate\Database\Seeder;

class ServiceProvidersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceProviders = [
            [
                'type' => 'Claude API',
                'parameter' => [
                    'api_key' => 'your_api_key',
                    'base_url' => 'https://api.anthropic.com',
                    'version' => 'v1',
                    'features' => [
                        'text_generation',
                        'text_summerization',
                        'question_aswering',
                        'text_classification',
                        'text_translation',
                        'code_generation',
                        'data_analysis_and_insight_service',
                        'personalization_service',
                    ],
                ],
            ],
            [
                'type' => 'RunwayML',
                'parameter' => [
                    'base_url' => 'https://api.dev.runwayml.com',
                    'version' => 'v1',
                ],
            ],
            [
                'type' => 'Canva',
                'parameter' => [
                    'base_url' => 'https://api.canva.com',
                    'version' => 'v1',
                ]
            ],
            [
                'type' => 'Qwen',
                'parameter' => [
                    'base_url' => 'https://openrouter.ai/api/v1',
                    'version' => 'v1',
                ],
            ],
            [
                "type" => "DeepSeek",
                "parameter" => [
                    "version" => "latest",
                    "supported_models" => [
                        "deepseek-chat",
                        "deepseek-code"
                    ]
                ]
            ],
            [
                "type" => "Gemini",
                "parameter" => [
                    "version" => "latest",
                    "max_tokens" => 4096,
                    "supported_models" => [
                        "gemini-pro",
                        "gemini-ultra"
                    ]
                ]
            ]
        ];

        foreach ($serviceProviders as $serviceProvider) {
            ServiceProvider::updateOrCreate(
                ['type' => $serviceProvider['type']],
                [
                    'type' => $serviceProvider['type'],
                    'parameter' => $serviceProvider['parameter'],
                ]
            );
        }
    }
}
