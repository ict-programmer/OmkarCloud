<?php

namespace Database\Seeders;

use App\Models\ServiceProvider;
use App\Models\ServiceProviderType;
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
            ]
        );

        $typeDefinitions = [
            'AI Search' => [
                'model' => 'sonar',
                'query' => 'search query',
                'search_type' => 'web',
                'max_results' => 5,
                'temperature' => 0.7,
            ],
            'Academic Research' => [
                'model' => 'sonar-deep-research',
                'query' => 'research query',
                'search_type' => 'academic',
                'max_results' => 10,
            ],
            'Code Assistant' => [
                'model' => 'sonar-reasoning',
                'query' => 'coding question',
                'programming_language' => 'python',
                'code_length' => 'medium',
            ],
        ];

        // Create or retrieve service type IDs
        $serviceTypeIds = [];
        foreach (array_keys($typeDefinitions) as $name) {
            $serviceType = ServiceType::firstOrCreate(['name' => $name]);
            $serviceTypeIds[$name] = $serviceType->id;
        }

        // Create or update service_provider_types entries
        foreach ($typeDefinitions as $typeName => $parameters) {
            ServiceProviderType::updateOrCreate(
                [
                    'service_type_id' => $serviceTypeIds[$typeName],
                    'service_provider_id' => $serviceProvider->id,
                ],
                ['parameter' => $parameters]
            );
        }
    }
}
