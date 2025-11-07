<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

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

class OmkarCloudMapsService
{
    public function __construct(
        private ?string $base = null,
        private ?string $key  = null,
    ) {
        $this->base = rtrim($this->base ?? config('services.omkarcloud.base_url', env('OMKAR_MAPS_BASE_URL', 'https://api.omkar.cloud/maps')), '/');
        $this->key  = $this->key  ?? env('OMKAR_MAPS_API_KEY', '');
    }

    private function http(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::timeout(120)
            ->acceptJson()
            ->withHeaders(['Authorization' => "Bearer {$this->key}"]);
    }

    /* ---- function_name-s exactly as in m.txt/seeder ---- */

    public function businessSearchByQuery(BusinessSearchData $d): Response
    { return $this->http()->post("{$this->base}/search/query", $d->toArray()); }

    public function searchByLinks(SearchByLinksData $d): Response
    { return $this->http()->post("{$this->base}/search/links", $d->toArray()); }

    public function scrapeReviews(ScrapeReviewsData $d): Response
    { return $this->http()->post("{$this->base}/reviews/fetch", $d->toArray()); }

    public function outputResultStatus(OutputResultStatusData $d): Response
    { return $this->http()->get("{$this->base}/results/status", $d->toArray()); }

    public function detailedResultView(DetailedResultViewData $d): Response
    { return $this->http()->get("{$this->base}/results/output", $d->toArray()); }

    public function exportToJsonCsvExcel(ExportToJsonCsvExcelData $d): Response
    { return $this->http()->post("{$this->base}/export", $d->toArray()); }

    public function taskManagement(TaskManagementData $d): Response
    { return $this->http()->post("{$this->base}/tasks/manage", $d->toArray()); }

    public function filteredSearch(FilteredSearchData $d): Response
    { return $this->http()->post("{$this->base}/results/filter", $d->toArray()); }

    public function sortByAdsReviewsWebsite(SortByAdsReviewsWebsiteData $d): Response
    { return $this->http()->post("{$this->base}/results/sort", $d->toArray()); }
}
