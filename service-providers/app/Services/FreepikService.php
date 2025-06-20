<?php

namespace App\Services;

use App\Data\Request\Freepik\AiImageClassifierData;
use App\Data\Request\Freepik\DownloadResourceFormatData;
use App\Data\Request\Freepik\StockContentData;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use OpenAI;

class FreepikService
{
    protected string $baseUrl = 'https://api.freepik.com/v1';

    private PendingRequest $client;

    public function __construct()
    {
        $this->client = Http::baseUrl($this->baseUrl)
            ->withHeader('x-freepik-api-key', config(('services.freepik.api_key')))
            ->timeout(30 * 60);
    }

    public function stockContent(StockContentData $data): array
    {
        $response = $this->client->get('/resources', $data->toArray());

        return $response->json();
    }

    public function resourceDetail(string $resource_id): array
    {
        $response = $this->client->get('/resources/' . $resource_id);
        return $response->json();
    }

    public function downloadResource(string $resource_id): array
    {
        $response = $this->client->get('/resources/' . $resource_id . '/download');
        return $response->json();
    }

    public function downloadResourceFormat(DownloadResourceFormatData $data): array
    {
        $response = $this->client->get("/resources/{$data->resource_id}/download/{$data->format}");
        return $response->json();
    }

    public function aiImageClassifier(AiImageClassifierData $data): array
    {
        $response = $this->client->post('/ai/classifier/image', [
            'image' => $data->image_url,
        ]);

        return $response->json();
    }
}
