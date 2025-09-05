<?php

namespace Database\Seeders;

use App\Http\Controllers\OmkarCloudMapsController;
use App\Models\ServiceProvider;
use App\Traits\ServiceProviderSeederTrait;
use Illuminate\Database\Seeder;

class OmkarCloudMapsServiceProviderSeeder extends Seeder
{
    use ServiceProviderSeederTrait;

    public function run(): void
    {
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => 'OmkarCloudMapsScraper'],
            [
                'parameters' => [
                    'api_key'  => env('OMKAR_MAPS_API_KEY', ''),
                    'base_url' => config('services.omkarcloud.base_url', env('OMKAR_MAPS_BASE_URL', 'https://api.omkar.cloud/maps')),
                    'features' => [
                        'business_search_by_query',
                        'search_by_links',
                        'scrape_reviews',
                        'output_result_status',
                        'detailed_result_view',
                        'export_to_json_csv_excel',
                        'task_management',
                        'filtered_search',
                        'sort_by_ads_reviews_website',
                    ],
                ],
                'is_active'       => true,
                'controller_name' => OmkarCloudMapsController::class,
            ]
        );

        $serviceTypes = [
            [
                'name' => 'Business Search by Query',
                'input_parameters' => [
                    'query'       => ['type'=>'string','required'=>true,'userinput_rqd'=>true,'description'=>'Business search query'],
                    'location'    => ['type'=>'string','required'=>false,'userinput_rqd'=>false,'description'=>'Optional location bias'],
                    'radius_km'   => ['type'=>'integer','required'=>false,'userinput_rqd'=>false,'description'=>'Radius in km'],
                    'max_results' => ['type'=>'integer','required'=>false,'userinput_rqd'=>false,'description'=>'Max results'],
                    'format'      => ['type'=>'string','required'=>false,'userinput_rqd'=>false,'description'=>'json|csv|excel'],
                    'language'    => ['type'=>'string','required'=>false,'userinput_rqd'=>false,'description'=>'ISO code'],
                ],
                'response'         => ['status'=>'success','results'=>[]],
                'response_path'    => ['final_result'=>'$.results'],
                // Make sure these classes exist with this namespace:
                'request_class_name' => \App\Http\Requests\OmkarCloud\BusinessSearchRequest::class,
                'function_name'      => 'businessSearchByQuery',
            ],
            [
                'name' => 'Search by Links',
                'input_parameters' => [
                    'urls'   => ['type'=>'array','required'=>true,'userinput_rqd'=>true,'description'=>'Array of Google Maps URLs/CIDs'],
                    'format' => ['type'=>'string','required'=>false,'userinput_rqd'=>false,'description'=>'json|csv|excel'],
                ],
                'response'         => ['status'=>'success','results'=>[]],
                'response_path'    => ['final_result'=>'$.results'],
                'request_class_name' => \App\Http\Requests\OmkarCloud\SearchByLinksRequest::class,
                'function_name'      => 'searchByLinks',
            ],
            [
                'name' => 'Scrape Reviews',
                'input_parameters' => [
                    'business_id' => ['type'=>'string','required'=>true,'userinput_rqd'=>true,'description'=>'Place ID or GMaps URL'],
                    'max_results' => ['type'=>'integer','required'=>false,'userinput_rqd'=>false],
                    'language'    => ['type'=>'string','required'=>false,'userinput_rqd'=>false],
                    'format'      => ['type'=>'string','required'=>false,'userinput_rqd'=>false],
                ],
                'response'         => ['status'=>'success','reviews'=>[]],
                'response_path'    => ['final_result'=>'$.reviews'],
                'request_class_name' => \App\Http\Requests\OmkarCloud\ScrapeReviewsRequest::class,
                'function_name'      => 'scrapeReviews',
            ],
            [
                'name' => 'Output Result Status',
                'input_parameters' => [
                    'task_id' => ['type'=>'string','required'=>true,'userinput_rqd'=>true],
                ],
                'response'         => ['status'=>'queued','task'=>['id'=>'','progress'=>0]],
                'response_path'    => ['final_result'=>'$.task'],
                'request_class_name' => \App\Http\Requests\OmkarCloud\OutputResultStatusRequest::class,
                'function_name'      => 'outputResultStatus',
            ],
            [
                'name' => 'Detailed Result View',
                'input_parameters' => [
                    'task_id'     => ['type'=>'string','required'=>true,'userinput_rqd'=>true],
                    'include_raw' => ['type'=>'boolean','required'=>false,'userinput_rqd'=>false],
                    'format'      => ['type'=>'string','required'=>false,'userinput_rqd'=>false],
                ],
                'response'         => ['status'=>'success','results'=>[]],
                'response_path'    => ['final_result'=>'$.results'],
                'request_class_name' => \App\Http\Requests\OmkarCloud\DetailedResultViewRequest::class,
                'function_name'      => 'detailedResultView',
            ],
            [
                'name' => 'Export to JSON/CSV/Excel',
                'input_parameters' => [
                    'task_id' => ['type'=>'string','required'=>true,'userinput_rqd'=>true],
                    'format'  => ['type'=>'string','required'=>true,'userinput_rqd'=>true],
                ],
                'response'         => ['status'=>'success','file'=>['url'=>'']],
                'response_path'    => ['final_result'=>'$.file'],
                'request_class_name' => \App\Http\Requests\OmkarCloud\ExportToJsonCsvExcelRequest::class,
                'function_name'      => 'exportToJsonCsvExcel',
            ],
            [
                'name' => 'Task Management',
                'input_parameters' => [
                    'action'  => ['type'=>'string','required'=>true,'userinput_rqd'=>true,'description'=>'start|abort|delete'],
                    'task_id' => ['type'=>'string','required'=>false,'userinput_rqd'=>false],
                    'query'   => ['type'=>'string','required'=>false,'userinput_rqd'=>false],
                    'urls'    => ['type'=>'array','required'=>false,'userinput_rqd'=>false],
                    'format'  => ['type'=>'string','required'=>false,'userinput_rqd'=>false],
                ],
                'response'         => ['status'=>'success','task'=>['id'=>'','state'=>'queued']],
                'response_path'    => ['final_result'=>'$.task'],
                'request_class_name' => \App\Http\Requests\OmkarCloud\TaskManagementRequest::class,
                'function_name'      => 'taskManagement',
            ],
            [
                'name' => 'Filtered Search',
                'input_parameters' => [
                    'task_id' => ['type'=>'string','required'=>true,'userinput_rqd'=>true],
                    'filters' => ['type'=>'array','required'=>true,'userinput_rqd'=>true],
                    'format'  => ['type'=>'string','required'=>false,'userinput_rqd'=>false],
                ],
                'response'         => ['status'=>'success','results'=>[]],
                'response_path'    => ['final_result'=>'$.results'],
                'request_class_name' => \App\Http\Requests\OmkarCloud\FilteredSearchRequest::class,
                'function_name'      => 'filteredSearch',
            ],
            [
                'name' => 'Sort by Ads/Reviews/Website',
                'input_parameters' => [
                    'task_id' => ['type'=>'string','required'=>true,'userinput_rqd'=>true],
                    'mode'    => ['type'=>'string','required'=>false,'userinput_rqd'=>false,'description'=>'best_customer'],
                    'format'  => ['type'=>'string','required'=>false,'userinput_rqd'=>false],
                ],
                'response'         => ['status'=>'success','results'=>[]],
                'response_path'    => ['final_result'=>'$.results'],
                'request_class_name' => \App\Http\Requests\OmkarCloud\SortByAdsReviewsWebsiteRequest::class,
                'function_name'      => 'sortByAdsReviewsWebsite',
            ],
        ];

        $keptServiceTypeIds      = $this->processServiceTypes($serviceProvider, $serviceTypes, 'OmkarCloudMapsScraper');
        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);

        if (property_exists($this, 'command') && $this->command) {
            $this->command->info('Cleanup completed:');
            $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
            $this->command->info('- Kept ' . count($keptServiceTypeIds) . ' service types for OmkarCloudMapsScraper');
        }
    }
}
