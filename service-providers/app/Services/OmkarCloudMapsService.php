<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

use App\Data\OmkarCloud\Requests\{
    SearchQuery, SearchLinks, FetchReviews, TaskId, ExportData,
    ManageTasks, FilterResults, SortLogic
};
use App\Data\OmkarCloud\Responses\GenericResponse;

final class OmkarCloudMapsService
{
    private string $base;
    private string $key;

    public function __construct(?string $baseUrl = null, ?string $apiKey = null)
    {
        $this->base = rtrim($baseUrl ?? config('services.omkarcloud.base_url', env('OMKAR_MAPS_BASE_URL', 'https://api.omkar.cloud/maps')), '/');
        $this->key  = $apiKey  ?? env('OMKAR_MAPS_API_KEY', '');
    }

    private function request(string $method, string $path, array $payload = []): GenericResponse
    {
        $http = Http::timeout(120)
            ->acceptJson()
            ->withHeaders(['Authorization' => "Bearer {$this->key}"]);

        $resp = $method === 'get'
            ? $http->get("{$this->base}/{$path}", $payload)   // query string
            : $http->{$method}("{$this->base}/{$path}", $payload); // JSON body

        if ($resp->serverError()) {
            throw RequestException::create($resp);
        }

        return GenericResponse::fromHttp($resp->status(), $resp->json() ?? $resp->body());
    }

    public function searchByQuery(SearchQuery $req): GenericResponse   { return $this->request('post','search/query',   $req->toArray()); }
    public function searchByLinks(SearchLinks $req): GenericResponse   { return $this->request('post','search/links',   $req->toArray()); }
    public function fetchReviews(FetchReviews $req): GenericResponse   { return $this->request('post','reviews/fetch',  $req->toArray()); }
    public function resultsStatus(TaskId $req): GenericResponse        { return $this->request('get', 'results/status', $req->toArray()); }
    public function outputData(TaskId $req): GenericResponse           { return $this->request('get', 'results/output', $req->toArray()); }
    public function exportData(ExportData $req): GenericResponse       { return $this->request('post','export',         $req->toArray()); }
    public function manageTasks(ManageTasks $req): GenericResponse     { return $this->request('post','tasks/manage',   $req->toArray()); }
    public function filterResults(FilterResults $req): GenericResponse { return $this->request('post','results/filter', $req->toArray()); }
    public function sortLogic(SortLogic $req): GenericResponse         { return $this->request('post','results/sort',   $req->toArray()); }
}
