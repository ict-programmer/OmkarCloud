<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Services\OmkarCloudMapsService;
use App\Data\Request\OmkarCloud\{
    BusinessSearchData,
    SearchByLinksData,
    ScrapeReviewsData,
    OutputResultStatusData,
    DetailedResultViewData,
    ExportToJsonCsvExcelData,
    TaskManagementData,
    FilteredSearchData,
    SortByAdsReviewsWebsiteData
};
use App\Http\Requests\OmkarCloud\{
    BusinessSearchRequest,
    SearchByLinksRequest,
    ScrapeReviewsRequest,
    OutputResultStatusRequest,
    DetailedResultViewRequest,
    ExportToJsonCsvExcelRequest,
    TaskManagementRequest,
    FilteredSearchRequest,
    SortByAdsReviewsWebsiteRequest
};
use OpenApi\Attributes as OA;

class OmkarCloudMapsController extends BaseController
{
    public function __construct(protected OmkarCloudMapsService $service) {}

    #[OA\Post(
        path: '/api/maps/search_query',
        operationId: 'maps_business_search_by_query',
        description: 'Search Google Maps businesses by free-text query.',
        summary: 'Business Search by Query',
        tags: ['OmkarCloudMaps'],
    )]
    #[OA\Parameter(
        name: 'query',
        in: 'query',
        description: 'Business search query (e.g., "coffee shop in Tokyo")',
        required: true,
        schema: new OA\Schema(type: 'string', example: 'coffee shop in Tokyo')
    )]
    #[OA\Parameter(
        name: 'location',
        in: 'query',
        description: 'Optional location bias (e.g., "Tokyo, JP")',
        required: false,
        schema: new OA\Schema(type: 'string', nullable: true, example: 'Tokyo, JP')
    )]
    #[OA\Parameter(
        name: 'radius_km',
        in: 'query',
        description: 'Search radius in kilometers',
        required: false,
        schema: new OA\Schema(type: 'integer', minimum: 0, nullable: true, example: 5)
    )]
    #[OA\Parameter(
        name: 'max_results',
        in: 'query',
        description: 'Maximum number of results',
        required: false,
        schema: new OA\Schema(type: 'integer', minimum: 1, nullable: true, example: 20)
    )]
    #[OA\Parameter(
        name: 'format',
        in: 'query',
        description: 'Output format',
        required: false,
        schema: new OA\Schema(type: 'string', enum: ['json','csv','excel'], nullable: true, example: 'json')
    )]
    #[OA\Parameter(
        name: 'language',
        in: 'query',
        description: 'ISO language code',
        required: false,
        schema: new OA\Schema(type: 'string', nullable: true, example: 'en')
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'status'  => 'success',
                'results' => [
                    ['name' => 'Example Cafe', 'rating' => 4.6, 'website' => 'https://example.com'],
                ],
            ],
        )
    )]
    public function businessSearchByQuery(BusinessSearchRequest $req): JsonResponse
    {
        return $this->logAndResponse(
            $this->service->businessSearchByQuery(BusinessSearchData::from($req->validated()))
        );
    }

    #[OA\Post(
        path: '/api/maps/search_links',
        operationId: 'maps_search_by_links',
        description: 'Search businesses by Google Maps URLs or CIDs.',
        summary: 'Search by Links',
        tags: ['OmkarCloudMaps'],
    )]
    #[OA\Parameter(
        name: 'urls',
        in: 'query',
        description: 'Array of Google Maps URLs or CIDs',
        required: true,
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(type: 'string'),
            example: ['https://maps.google.com/?cid=XXXXXXXXXXXXXXX']
        )
    )]
    #[OA\Parameter(
        name: 'format',
        in: 'query',
        description: 'Output format',
        required: false,
        schema: new OA\Schema(type: 'string', enum: ['json','csv','excel'], nullable: true, example: 'json')
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'status'  => 'success',
                'results' => [
                    ['place_id' => 'xxxxxxxx', 'name' => 'Linked Place'],
                ],
            ],
        )
    )]
    public function searchByLinks(SearchByLinksRequest $req): JsonResponse
    {
        return $this->logAndResponse(
            $this->service->searchByLinks(SearchByLinksData::from($req->validated()))
        );
    }

    #[OA\Post(
        path: '/api/maps/fetch_reviews',
        operationId: 'maps_scrape_reviews',
        description: 'Scrape reviews for a business by Place ID or canonical Maps URL.',
        summary: 'Scrape Reviews',
        tags: ['OmkarCloudMaps'],
    )]
    #[OA\Parameter(
        name: 'business_id',
        in: 'query',
        description: 'Place ID or canonical Google Maps URL',
        required: true,
        schema: new OA\Schema(type: 'string', example: 'ChIJN1t_tDeuEmsRUsoyG83frY4')
    )]
    #[OA\Parameter(
        name: 'max_results',
        in: 'query',
        description: 'Maximum number of reviews to fetch',
        required: false,
        schema: new OA\Schema(type: 'integer', minimum: 1, nullable: true, example: 100)
    )]
    #[OA\Parameter(
        name: 'language',
        in: 'query',
        description: 'ISO language code',
        required: false,
        schema: new OA\Schema(type: 'string', nullable: true, example: 'en')
    )]
    #[OA\Parameter(
        name: 'format',
        in: 'query',
        description: 'Output format',
        required: false,
        schema: new OA\Schema(type: 'string', enum: ['json','csv','excel'], nullable: true, example: 'json')
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'status'  => 'success',
                'reviews' => [
                    ['author' => 'John', 'rating' => 5, 'text' => 'Great place!'],
                ],
            ],
        )
    )]
    public function scrapeReviews(ScrapeReviewsRequest $req): JsonResponse
    {
        return $this->logAndResponse(
            $this->service->scrapeReviews(ScrapeReviewsData::from($req->validated()))
        );
    }

    #[OA\Get(
        path: '/api/maps/results_status',
        operationId: 'maps_output_result_status',
        description: 'Get the current status of a scraping task.',
        summary: 'Output Result Status',
        tags: ['OmkarCloudMaps'],
    )]
    #[OA\Parameter(
        name: 'task_id',
        in: 'query',
        description: 'Task identifier to check status',
        required: true,
        schema: new OA\Schema(type: 'string', example: 'task_abc_123')
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'status' => 'queued',
                'task'   => ['id' => 'task_abc_123', 'progress' => 65],
            ],
        )
    )]
    public function outputResultStatus(OutputResultStatusRequest $req): JsonResponse
    {
        return $this->logAndResponse(
            $this->service->outputResultStatus(OutputResultStatusData::from($req->validated()))
        );
    }

    #[OA\Get(
        path: '/api/maps/output_data',
        operationId: 'maps_detailed_result_view',
        description: 'Fetch final output data for a completed task.',
        summary: 'Detailed Result View',
        tags: ['OmkarCloudMaps'],
    )]
    #[OA\Parameter(
        name: 'task_id',
        in: 'query',
        description: 'Task identifier to fetch final results',
        required: true,
        schema: new OA\Schema(type: 'string', example: 'task_abc_123')
    )]
    #[OA\Parameter(
        name: 'include_raw',
        in: 'query',
        description: 'Whether to include raw payload from the scraper',
        required: false,
        schema: new OA\Schema(type: 'boolean', nullable: true, example: false)
    )]
    #[OA\Parameter(
        name: 'format',
        in: 'query',
        description: 'Output format',
        required: false,
        schema: new OA\Schema(type: 'string', enum: ['json','csv','excel'], nullable: true, example: 'json')
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'status'  => 'success',
                'results' => [
                    ['name' => 'Example Cafe', 'site' => 'https://example.com', 'ads' => false],
                ],
            ],
        )
    )]
    public function detailedResultView(DetailedResultViewRequest $req): JsonResponse
    {
        return $this->logAndResponse(
            $this->service->detailedResultView(DetailedResultViewData::from($req->validated()))
        );
    }

    #[OA\Post(
        path: '/api/maps/export_csv',
        operationId: 'maps_export_to_json_csv_excel',
        description: 'Export task results to JSON/CSV/Excel.',
        summary: 'Export to JSON/CSV/Excel',
        tags: ['OmkarCloudMaps'],
    )]
    #[OA\Parameter(
        name: 'task_id',
        in: 'query',
        description: 'Task identifier whose results will be exported',
        required: true,
        schema: new OA\Schema(type: 'string', example: 'task_abc_123')
    )]
    #[OA\Parameter(
        name: 'format',
        in: 'query',
        description: 'Export format',
        required: true,
        schema: new OA\Schema(type: 'string', enum: ['json','csv','excel'], example: 'csv')
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'status' => 'success',
                'file'   => ['url' => 'https://example.com/export.csv'],
            ],
        )
    )]
    public function exportToJsonCsvExcel(ExportToJsonCsvExcelRequest $req): JsonResponse
    {
        return $this->logAndResponse(
            $this->service->exportToJsonCsvExcel(ExportToJsonCsvExcelData::from($req->validated()))
        );
    }

    #[OA\Post(
        path: '/api/maps/manage_tasks',
        operationId: 'maps_task_management',
        description: 'Start/abort/delete scraping tasks.',
        summary: 'Task Management',
        tags: ['OmkarCloudMaps'],
    )]
    #[OA\Parameter(
        name: 'action',
        in: 'query',
        description: 'Action to perform',
        required: true,
        schema: new OA\Schema(type: 'string', enum: ['start','abort','delete'], example: 'start')
    )]
    #[OA\Parameter(
        name: 'task_id',
        in: 'query',
        description: 'Required when action is abort or delete',
        required: false,
        schema: new OA\Schema(type: 'string', nullable: true, example: 'task_abc_123')
    )]
    #[OA\Parameter(
        name: 'query',
        in: 'query',
        description: 'Provide when starting a task by query',
        required: false,
        schema: new OA\Schema(type: 'string', nullable: true, example: 'coffee shop in Tokyo')
    )]
    #[OA\Parameter(
        name: 'urls',
        in: 'query',
        description: 'Provide when starting a task by links',
        required: false,
        schema: new OA\Schema(
            type: 'array',
            nullable: true,
            items: new OA\Items(type: 'string'),
            example: ['https://maps.google.com/?cid=XXXXXXXXXXXXXXX']
        )
    )]
    #[OA\Parameter(
        name: 'format',
        in: 'query',
        description: 'Output format',
        required: false,
        schema: new OA\Schema(type: 'string', enum: ['json','csv','excel'], nullable: true, example: 'json')
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'status' => 'success',
                'task'   => ['id' => 'task_abc_123', 'state' => 'queued'],
            ],
        )
    )]
    public function taskManagement(TaskManagementRequest $req): JsonResponse
    {
        return $this->logAndResponse(
            $this->service->taskManagement(TaskManagementData::from($req->validated()))
        );
    }

    #[OA\Post(
        path: '/api/maps/filter_results',
        operationId: 'maps_filtered_search',
        description: 'Filter a task’s results by conditions (city, country, rating, has_web…).',
        summary: 'Filtered Search',
        tags: ['OmkarCloudMaps'],
    )]
    #[OA\Parameter(
        name: 'task_id',
        in: 'query',
        description: 'Task whose results to filter',
        required: true,
        schema: new OA\Schema(type: 'string', example: 'task_abc_123')
    )]
    #[OA\Parameter(
        name: 'filters',
        in: 'query',
        description: 'Key-value filters (JSON map)',
        required: true,
        schema: new OA\Schema(type: 'string', example: '{"city":"Tokyo","has_web":true}')
    )]
    #[OA\Parameter(
        name: 'format',
        in: 'query',
        description: 'Output format',
        required: false,
        schema: new OA\Schema(type: 'string', enum: ['json','csv','excel'], nullable: true, example: 'json')
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'status'  => 'success',
                'results' => [],
            ],
        )
    )]
    public function filteredSearch(FilteredSearchRequest $req): JsonResponse
    {
        return $this->logAndResponse(
            $this->service->filteredSearch(FilteredSearchData::from($req->validated()))
        );
    }

    #[OA\Post(
        path: '/api/maps/sort_logic',
        operationId: 'maps_sort_by_ads_reviews_website',
        description: 'Sort task results using heuristic (e.g., best_customer).',
        summary: 'Sort by Ads/Reviews/Website',
        tags: ['OmkarCloudMaps'],
    )]
    #[OA\Parameter(
        name: 'task_id',
        in: 'query',
        description: 'Task whose results to sort',
        required: true,
        schema: new OA\Schema(type: 'string', example: 'task_abc_123')
    )]
    #[OA\Parameter(
        name: 'mode',
        in: 'query',
        description: 'Sorting heuristic',
        required: false,
        schema: new OA\Schema(type: 'string', enum: ['best_customer'], nullable: true, example: 'best_customer')
    )]
    #[OA\Parameter(
        name: 'format',
        in: 'query',
        description: 'Output format',
        required: false,
        schema: new OA\Schema(type: 'string', enum: ['json','csv','excel'], nullable: true, example: 'json')
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'status'  => 'success',
                'results' => [],
            ],
        )
    )]
    public function sortByAdsReviewsWebsite(SortByAdsReviewsWebsiteRequest $req): JsonResponse
    {
        return $this->logAndResponse(
            $this->service->sortByAdsReviewsWebsite(SortByAdsReviewsWebsiteData::from($req->validated()))
        );
    }
}
