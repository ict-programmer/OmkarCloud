<?php

namespace Database\Seeders;

use App\Models\ServiceProvider;
use App\Models\ServiceProviderType;
use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class OmkarCloudMapsServiceProviderSeeder extends Seeder
{
    public function run(): void
    {
        $provider = ServiceProvider::updateOrCreate(
            ['type' => 'OmkarCloudMapsScraper'],
            ['parameter' => [
                'api_url' => config('services.omkarcloud.base_url', env('OMKAR_MAPS_BASE_URL', 'https://api.omkar.cloud/maps')),
                'api_key' => env('OMKAR_MAPS_API_KEY', ''),
            ]]
        );

        $names = [
            'Business Search by Query',
            'Search by Links',
            'Scrape Reviews',
            'Output Result Status',
            'Detailed Result View',
            'Export to JSON/CSV/Excel',
            'Task Management',
            'Filtered Search',
            'Sort by Ads/Reviews/Website',
        ];

        $defs = [
            'Business Search by Query'     => ['feature' => 'business_search_by_query'],
            'Search by Links'              => ['feature' => 'search_by_links'],
            'Scrape Reviews'               => ['feature' => 'scrape_reviews'],
            'Output Result Status'         => ['feature' => 'output_result_status'],
            'Detailed Result View'         => ['feature' => 'detailed_result_view'],
            'Export to JSON/CSV/Excel'     => ['feature' => 'export_to_json/csv/excel'],
            'Task Management'              => ['feature' => 'task_management'],
            'Filtered Search'              => ['feature' => 'filtered_search'],
            'Sort by Ads/Reviews/Website'  => ['feature' => 'sort_by_ads/reviews/website'],
        ];

        $typeIds = [];
        foreach ($names as $n) {
            $typeIds[$n] = ServiceType::firstOrCreate(['name' => $n])->id;
        }

        foreach ($defs as $name => $parameter) {
            ServiceProviderType::updateOrCreate(
                [
                    'service_type_id' => $typeIds[$name],
                    'service_provider_id' => $provider->id,
                ],
                ['parameter' => $parameter]
            );
        }
    }
}
