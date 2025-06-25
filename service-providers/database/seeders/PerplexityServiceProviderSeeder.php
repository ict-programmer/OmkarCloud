<?php

namespace Database\Seeders;

use App\Http\Controllers\PerplexityController;
use App\Http\Requests\Perplexity\AcademicResearchRequest;
use App\Http\Requests\Perplexity\AiSearchRequest;
use App\Http\Requests\Perplexity\CodeAssistantRequest;
use App\Models\ServiceProvider;
use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class PerplexityServiceProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => 'Perplexity'],
            [
                'parameters' => [
                    'api_key' => 'YOUR_API_KEY',
                    'base_url' => 'https://api.perplexity.ai',
                    'version' => null,
                    'models_supported' => [
                        'sonar',
                        'sonar-pro',
                        'sonar-deep-research',
                        'sonar-reasoning',
                        'sonar-reasoning-pro',
                    ],
                    'features' => [
                        'ai_search',
                        'academic_research',
                        'code_assistant',
                    ],
                ],
                'is_active' => true,
                'controller_name' => PerplexityController::class
            ]
        );

        $serviceTypes = [
            [
                'name' => 'AI Search',
                'parameter' => [
                    "model" => "sonar",
                    "query" => "What is AI?",
                    "search_type" => "web",
                    "max_results" => 0,
                    "temperature" => 0.2
                ],
                'request_class_name' => AiSearchRequest::class,
                'function_name' => 'aiSearch',
            ],
            [
                'name' => 'Academic Research',
                'parameter' => [
                    "model" => "sonar-deep-research",
                    "query" => "Impact of artificial intelligence on healthcare",
                    "search_type" => "academic",
                    "max_results" => 0
                ],
                'request_class_name' => AcademicResearchRequest::class,
                'function_name' => 'academicResearch',
            ],
            [
                'name' => 'Code Assistant',
                'parameter' => [
                    "model" => "sonar-reasoning",
                    "query" => "How to reverse a string in Python?",
                    "programming_language" => "python",
                    "code_length" => "medium"
                ],
                'request_class_name' => CodeAssistantRequest::class,
                'function_name' => 'codeAssistant',
            ]
        ];


        // Create or update service_provider_types entries
        foreach ($serviceTypes as $serviceType) {
            ServiceType::updateOrCreate(
                [
                    'name' => $serviceType['name'],
                    'service_provider_id' => $serviceProvider->id,
                ],
                [
                    'parameter' => $serviceType['parameter'],
                    'request_class_name' => $serviceType['request_class_name'],
                    'function_name' => $serviceType['function_name'],
                ]
            );
        }
    }
}
