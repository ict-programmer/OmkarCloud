<?php

namespace App\Services;

use App\Data\Request\Google\SearchImageWithOperatorsData;
use App\Data\Request\Google\SearchWebWithOperatorsData;
use App\Exceptions\ApiException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class GoogleService
{
    protected string $baseUrl = 'https://customsearch.googleapis.com/customsearch/v1';

    private PendingRequest $client;

    private string $apiKey;

    private string $googleCx;

    public function __construct()
    {
        $this->apiKey = config('services.google.custom_search.api_key');
        $this->googleCx = config('services.google.custom_search.search_engine_id');
        $this->client = Http::baseUrl($this->baseUrl)
            ->timeout(30 * 60);
    }

    private function get(string $endpoint, array $payload = []): array
    {
        try {
            return $this->client
                ->get($endpoint, array_filter($payload, fn ($value) => $value !== null) + ['key' => $this->apiKey, 'cx' => $this->googleCx])
                ->throw()
                ->json();
        } catch (RequestException $e) {
            $message = $e->response->json('error.message') ?? 'API request failed';
            $status = $e->response->status();

            throw new ApiException($message, $status, $e->response->json());
        }
    }

    public function searchWeb(SearchWebWithOperatorsData $data): array
    {
        return $this->get($this->baseUrl, $data->toArray());
    }

    public function searchImage(SearchImageWithOperatorsData $data): array
    {
        return $this->get($this->baseUrl, $data->toArray() + ['searchType' => 'image']);
    }
}
