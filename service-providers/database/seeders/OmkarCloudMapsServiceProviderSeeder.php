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
        // 1) Provider record (same pattern as Perplexity seeder)
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => 'OmkarCloudMapsScraper'],
            ['parameter' => [
                'api_url' => env('OMKAR_MAPS_BASE_URL', 'https://api.omkar.cloud/maps'),
                'api_key' => env('OMKAR_MAPS_API_KEY', ''),
            ]]
        );

        // 2) The 9 service types (names must match SRS)
        $typeNames = [
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

        // 3) Parameters per type (follows Perplexity pattern: name => parameter array)
        $typeDefinitions = [
            'Business Search by Query'        => ['feature' => 'business_search_by_query',   'sample' => true],
            'Search by Links'                 => ['feature' => 'search_by_links',            'sample' => true],
            'Scrape Reviews'                  => ['feature' => 'scrape_reviews',             'sample' => true],
            'Output Result Status'            => ['feature' => 'output_result_status',       'sample' => true],
            'Detailed Result View'            => ['feature' => 'detailed_result_view',       'sample' => true],
            'Export to JSON/CSV/Excel'        => ['feature' => 'export_to_json/csv/excel',   'sample' => true],
            'Task Management'                 => ['feature' => 'task_management',            'sample' => true],
            'Filtered Search'                 => ['feature' => 'filtered_search',            'sample' => true],
            'Sort by Ads/Reviews/Website'     => ['feature' => 'sort_by_ads/reviews/website','sample' => true],
        ];

        // 4) Create or get ServiceType IDs (same approach as Perplexity)
        $serviceTypeIds = [];
        foreach ($typeNames as $typeName) {
            $serviceTypeIds[$typeName] = ServiceType::firstOrCreate(['name' => $typeName])->id;
        }

        // 5) Link Provider <-> Types in service_provider_types using updateOrCreate
        foreach ($typeDefinitions as $typeName => $parameters) {
            ServiceProviderType::updateOrCreate(
                [
                    'service_type_id'     => $serviceTypeIds[$typeName],
                    'service_provider_id' => $serviceProvider->id,
                ],
                [
                    // IMPORTANT: follow Perplexity style => singular 'parameter'
                    'parameter' => $parameters,
                ]
            );
        }
    }
}
