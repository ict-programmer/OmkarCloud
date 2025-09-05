<?php

namespace Database\Seeders;

use App\Models\ServiceProvider;
use App\Models\ServiceProviderType;
use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class OmkarCloudMapsServiceProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => 'OmkarCloudMapsScraper'],
            [
                'parameters' => [
                    'api_key'  => env('OMKAR_MAPS_API_KEY', 'YOUR_API_KEY'),
                    'base_url' => env('OMKAR_MAPS_BASE_URL', 'https://api.omkar.cloud/maps'),
                    'version'  => null,

                    'models_supported' => [
                        'maps-business-search',
                        'maps-links-search',
                        'maps-reviews',
                        'maps-results',
                        'maps-export',
                    ],

                    'features' => [
                        'Business Search by Query',
                        'Search by Links',
                        'Scrape Reviews',
                        'Output Result Status',
                        'Detailed Result View',
                        'Export to JSON/CSV/Excel',
                        'Task Management',
                        'Filtered Search',
                        'Sort by Ads/Reviews/Website',
                    ],
                ],
                'is_active' => true,
            ]
        );

        $typeDefinitions = [
            'Business Search by Query' => [
                'endpoint'    => 'search/query',
                'query'       => 'search term',
                'filters'     => ['city' => null, 'country' => null, 'rating' => null],
                'format'      => 'json',
                'max_results' => 50,
            ],
            'Search by Links' => [
                'endpoint' => 'search/links',
                'links'    => ['https://maps.google.com/?cid=PLACE_ID'],
                'filters'  => ['city' => null, 'country' => null],
                'format'   => 'json',
            ],
            'Scrape Reviews' => [
                'endpoint'   => 'reviews/fetch',
                'identifier' => 'place_id_or_maps_url',
                'limit'      => 100,
                'format'     => 'json',
            ],
            'Output Result Status' => [
                'endpoint' => 'results/status',
                'task_id'  => 'TASK_ID_HERE',
            ],
            'Detailed Result View' => [
                'endpoint' => 'results/output',
                'task_id'  => 'TASK_ID_HERE',
                'format'   => 'json',
            ],
            'Export to JSON/CSV/Excel' => [
                'endpoint' => 'export',
                'task_id'  => 'TASK_ID_HERE',
                'format'   => 'csv', // json|csv|excel
            ],
            'Task Management' => [
                'endpoint' => 'tasks/manage',
                'action'   => 'start', // start|abort|delete
                'task_id'  => null,
            ],
            'Filtered Search' => [
                'endpoint' => 'results/filter',
                'task_id'  => 'TASK_ID_HERE',
                'filters'  => ['city' => null, 'country' => null, 'rating' => null],
                'format'   => 'json',
            ],
            'Sort by Ads/Reviews/Website' => [
                'endpoint' => 'results/sort',
                'task_id'  => 'TASK_ID_HERE',
                'mode'     => 'best_customer',
                'format'   => 'json',
            ],
        ];

        $serviceTypeIds = [];
        foreach (array_keys($typeDefinitions) as $name) {
            $serviceTypeIds[$name] = ServiceType::firstOrCreate(['name' => $name])->id;
        }

        foreach ($typeDefinitions as $typeName => $parameters) {
            ServiceProviderType::updateOrCreate(
                [
                    'service_type_id'     => $serviceTypeIds[$typeName],
                    'service_provider_id' => $serviceProvider->id,
                ],
                ['parameter' => $parameters]
            );
        }
    }
}
