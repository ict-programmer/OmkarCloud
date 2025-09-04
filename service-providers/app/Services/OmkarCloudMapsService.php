<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

use App\Data\Request\OmkarCloud\{
    BusinessSearchData, SearchLinksData, FetchReviewsData, TaskIdData,
    ExportData, ManageTasksData, FilterResultsData, SortLogicData
};
use Illuminate\Http\Client\Response;

class OmkarCloudMapsService
{
    public function __construct(
        private ?string $base = null,
        private ?string $key = null,
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

    public function searchQuery(BusinessSearchData $d): Response
    {
        return $this->http()->post("{$this->base}/search/query", $d->toArray());
    }

    public function searchLinks(SearchLinksData $d): Response
    {
        return $this->http()->post("{$this->base}/search/links", $d->toArray());
    }

    public function fetchReviews(FetchReviewsData $d): Response
    {
        return $this->http()->post("{$this->base}/reviews/fetch", $d->toArray());
    }

    public function resultsStatus(TaskIdData $d): Response
    {
        return $this->http()->get("{$this->base}/results/status", $d->toArray());
    }

    public function outputData(TaskIdData $d): Response
    {
        return $this->http()->get("{$this->base}/results/output", $d->toArray());
    }

    public function exportData(ExportData $d): Response
    {
        return $this->http()->post("{$this->base}/export", $d->toArray());
    }

    public function manageTasks(ManageTasksData $d): Response
    {
        return $this->http()->post("{$this->base}/tasks/manage", $d->toArray());
    }

    public function filterResults(FilterResultsData $d): Response
    {
        return $this->http()->post("{$this->base}/results/filter", $d->toArray());
    }

    public function sortLogic(SortLogicData $d): Response
    {
        return $this->http()->post("{$this->base}/results/sort", $d->toArray());
    }
}
