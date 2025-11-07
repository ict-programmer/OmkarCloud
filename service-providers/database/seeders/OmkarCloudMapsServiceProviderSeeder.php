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
        // Provider row
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

        // Service Types (all userinput_rqd => true)
        $serviceTypes = [
            // 1) Business Search by Query
            [
                'name' => 'Business Search by Query',
                'input_parameters' => [
                    'query'       => ['type'=>'string','required'=>true, 'userinput_rqd'=>true, 'description'=>'Business search query (e.g., "coffee shop in Makati")'],
                    'location'    => ['type'=>'string','required'=>false,'userinput_rqd'=>true, 'description'=>'Location bias (e.g., "Makati, PH")'],
                    'radius_km'   => ['type'=>'integer','required'=>false,'userinput_rqd'=>true, 'description'=>'Search radius in kilometers'],
                    'max_results' => ['type'=>'integer','required'=>false,'userinput_rqd'=>true, 'description'=>'Maximum number of results'],
                    'format'      => ['type'=>'string','required'=>false,'userinput_rqd'=>true, 'description'=>'Output format: json|csv|excel'],
                    'language'    => ['type'=>'string','required'=>false,'userinput_rqd'=>true, 'description'=>'ISO language code (e.g., "en")'],
                ],
                'response' => [
                    'status'  => 'success',
                    'results' => [
                        [
                            'name'           => 'Example Cafe Makati',
                            'address'        => 'Ayala Ave, Makati, Metro Manila',
                            'place_id'       => 'ChIJPH_PLACE_ID_MKT',
                            'rating'         => 4.6,
                            'reviews_count'  => 312,
                            'phone'          => '+63 2 8123 4567',
                            'website'        => 'https://example-cafe.ph',
                            'categories'     => ['Cafe','Coffee Shop'],
                            'has_ads'        => false,
                            'latitude'       => 14.5546,
                            'longitude'      => 121.0151,
                        ],
                    ],
                ],
                'response_path'    => ['final_result'=>'$.results'],
                'request_class_name' => \App\Http\Requests\OmkarCloud\BusinessSearchRequest::class,
                'function_name'      => 'businessSearchByQuery',
            ],

            // 2) Search by Links
            [
                'name' => 'Search by Links',
                'input_parameters' => [
                    'urls'   => ['type'=>'array','required'=>true, 'userinput_rqd'=>true, 'description'=>'Array of Google Maps URLs or CIDs'],
                    'format' => ['type'=>'string','required'=>false,'userinput_rqd'=>true, 'description'=>'Output format: json|csv|excel'],
                ],
                'response' => [
                    'status'  => 'success',
                    'results' => [
                        [
                            'input'          => 'https://maps.google.com/?cid=XXXXXXXXXXXXXXX',
                            'resolved'       => ['place_id' => 'ChIJPH_PLACE_ID_QC'],
                            'name'           => 'Linked Place Quezon City',
                            'address'        => 'Commonwealth Ave, Quezon City, Metro Manila',
                            'rating'         => 4.2,
                            'website'        => 'https://linked-place.ph',
                        ],
                    ],
                ],
                'response_path'    => ['final_result'=>'$.results'],
                'request_class_name' => \App\Http\Requests\OmkarCloud\SearchByLinksRequest::class,
                'function_name'      => 'searchByLinks',
            ],

            // 3) Scrape Reviews
            [
                'name' => 'Scrape Reviews',
                'input_parameters' => [
                    'business_id' => ['type'=>'string','required'=>true, 'userinput_rqd'=>true, 'description'=>'Place ID or canonical Google Maps URL'],
                    'max_results' => ['type'=>'integer','required'=>false,'userinput_rqd'=>true, 'description'=>'Max number of reviews to fetch'],
                    'language'    => ['type'=>'string','required'=>false,'userinput_rqd'=>true, 'description'=>'ISO language code (e.g., "en")'],
                    'format'      => ['type'=>'string','required'=>false,'userinput_rqd'=>true, 'description'=>'Output format: json|csv|excel'],
                ],
                'response' => [
                    'status'  => 'success',
                    'reviews' => [
                        [
                            'author'        => 'Maria',
                            'rating'        => 5,
                            'text'          => 'Masarap ang kape at mabait ang staff!',
                            'published_at'  => '2024-11-12T09:22:00Z',
                            'helpful_count' => 7,
                        ],
                        [
                            'author'        => 'Jose',
                            'rating'        => 4,
                            'text'          => 'Maganda ang ambience, medyo mahal lang.',
                            'published_at'  => '2024-10-01T11:05:00Z',
                            'helpful_count' => 2,
                        ],
                    ],
                ],
                'response_path'    => ['final_result'=>'$.reviews'],
                'request_class_name' => \App\Http\Requests\OmkarCloud\ScrapeReviewsRequest::class,
                'function_name'      => 'scrapeReviews',
            ],

            // 4) Output Result Status
            [
                'name' => 'Output Result Status',
                'input_parameters' => [
                    'task_id' => ['type'=>'string','required'=>true, 'userinput_rqd'=>true, 'description'=>'Task identifier to check status'],
                ],
                'response' => [
                    'status' => 'running', // queued|running|done|error
                    'task'   => [
                        'id'         => 'task_ph_123',
                        'state'      => 'running',
                        'progress'   => 65,
                        'created_at' => '2025-09-03T01:00:00Z',
                        'updated_at' => '2025-09-03T01:10:00Z',
                    ],
                ],
                'response_path'    => ['final_result'=>'$.task'],
                'request_class_name' => \App\Http\Requests\OmkarCloud\OutputResultStatusRequest::class,
                'function_name'      => 'outputResultStatus',
            ],

            // 5) Detailed Result View
            [
                'name' => 'Detailed Result View',
                'input_parameters' => [
                    'task_id'     => ['type'=>'string','required'=>true, 'userinput_rqd'=>true, 'description'=>'Task identifier to fetch final results'],
                    'include_raw' => ['type'=>'boolean','required'=>false,'userinput_rqd'=>true, 'description'=>'Include raw payload from the scraper'],
                    'format'      => ['type'=>'string','required'=>false,'userinput_rqd'=>true, 'description'=>'Output format: json|csv|excel'],
                ],
                'response' => [
                    'status'  => 'success',
                    'results' => [
                        [
                            'name'           => 'Cafe BGC',
                            'place_id'       => 'ChIJPH_PLACE_ID_BGC',
                            'address'        => 'Bonifacio Global City, Taguig, Metro Manila',
                            'rating'         => 4.7,
                            'reviews_count'  => 158,
                            'website'        => 'https://cafebgc.ph',
                            'has_ads'        => false,
                            'enriched'       => ['open_now'=>true,'price_level'=>2],
                        ],
                    ],
                ],
                'response_path'    => ['final_result'=>'$.results'],
                'request_class_name' => \App\Http\Requests\OmkarCloud\DetailedResultViewRequest::class,
                'function_name'      => 'detailedResultView',
            ],

            // 6) Export to JSON/CSV/Excel
            [
                'name' => 'Export to JSON/CSV/Excel',
                'input_parameters' => [
                    'task_id' => ['type'=>'string','required'=>true, 'userinput_rqd'=>true, 'description'=>'Task identifier whose results will be exported'],
                    'format'  => ['type'=>'string','required'=>true, 'userinput_rqd'=>true, 'description'=>'Export format: json|csv|excel'],
                ],
                'response' => [
                    'status' => 'success',
                    'file'   => [
                        'url'        => 'https://files.omkar.cloud/exports/task_ph_123.csv',
                        'format'     => 'csv',
                        'size_bytes' => 225_347,
                        'expires_at' => '2025-09-04T01:00:00Z',
                    ],
                ],
                'response_path'    => ['final_result'=>'$.file'],
                'request_class_name' => \App\Http\Requests\OmkarCloud\ExportToJsonCsvExcelRequest::class,
                'function_name'      => 'exportToJsonCsvExcel',
            ],

            // 7) Task Management
            [
                'name' => 'Task Management',
                'input_parameters' => [
                    'action'  => ['type'=>'string','required'=>true, 'userinput_rqd'=>true, 'description'=>'start|abort|delete'],
                    'task_id' => ['type'=>'string','required'=>false,'userinput_rqd'=>true, 'description'=>'Required when action is abort or delete'],
                    'query'   => ['type'=>'string','required'=>false,'userinput_rqd'=>true, 'description'=>'When starting a search-by-query task'],
                    'urls'    => ['type'=>'array','required'=>false,'userinput_rqd'=>true, 'description'=>'When starting a search-by-links task'],
                    'format'  => ['type'=>'string','required'=>false,'userinput_rqd'=>true, 'description'=>'Output format: json|csv|excel'],
                ],
                'response' => [
                    'status' => 'success',
                    'task'   => [
                        'id'     => 'task_ph_123',
                        'action' => 'start',
                        'state'  => 'queued',
                    ],
                ],
                'response_path'    => ['final_result'=>'$.task'],
                'request_class_name' => \App\Http\Requests\OmkarCloud\TaskManagementRequest::class,
                'function_name'      => 'taskManagement',
            ],

            // 8) Filtered Search
            [
                'name' => 'Filtered Search',
                'input_parameters' => [
                    'task_id' => ['type'=>'string','required'=>true, 'userinput_rqd'=>true, 'description'=>'Task whose results to filter'],
                    'filters' => ['type'=>'array','required'=>true, 'userinput_rqd'=>true, 'description'=>'Filter map: city, country, rating, has_web, …'],
                    'format'  => ['type'=>'string','required'=>false,'userinput_rqd'=>true, 'description'=>'Output format: json|csv|excel'],
                ],
                'response' => [
                    'status'  => 'success',
                    'results' => [
                        [
                            'name'          => 'Example Cafe Makati',
                            'rating'        => 4.6,
                            'has_website'   => true,
                            'country'       => 'PH',
                            'city'          => 'Makati',
                        ],
                    ],
                ],
                'response_path'    => ['final_result'=>'$.results'],
                'request_class_name' => \App\Http\Requests\OmkarCloud\FilteredSearchRequest::class,
                'function_name'      => 'filteredSearch',
            ],

            // 9) Sort by Ads/Reviews/Website
            [
                'name' => 'Sort by Ads/Reviews/Website',
                'input_parameters' => [
                    'task_id' => ['type'=>'string','required'=>true, 'userinput_rqd'=>true, 'description'=>'Task whose results to sort'],
                    'mode'    => ['type'=>'string','required'=>false,'userinput_rqd'=>true, 'description'=>'Sorting heuristic (e.g., best_customer)'],
                    'format'  => ['type'=>'string','required'=>false,'userinput_rqd'=>true, 'description'=>'Output format: json|csv|excel'],
                ],
                'response' => [
                    'status'  => 'success',
                    'results' => [
                        [
                            'name'           => 'Cafe BGC',
                            'score'          => 0.91,
                            'has_ads'        => false,
                            'reviews_count'  => 158,
                            'has_website'    => true,
                        ],
                    ],
                    'criteria' => ['mode' => 'best_customer'],
                ],
                'response_path'    => ['final_result'=>'$.results'],
                'request_class_name' => \App\Http\Requests\OmkarCloud\SortByAdsReviewsWebsiteRequest::class,
                'function_name'      => 'sortByAdsReviewsWebsite',
            ],
        ];

        // Save + cleanup via your trait
        $keptServiceTypeIds       = $this->processServiceTypes($serviceProvider, $serviceTypes, 'OmkarCloudMapsScraper');
        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);

        if (property_exists($this, 'command') && $this->command) {
            $this->command->info('Cleanup completed:');
            $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
            $this->command->info('- Kept ' . count($keptServiceTypeIds) . ' service types for OmkarCloudMapsScraper');
        }
    }
}
