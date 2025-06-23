<?php

namespace App\Services;

use App\Data\Request\Freepik\AiImageClassifierData;
use App\Data\Request\Freepik\DownloadResourceFormatData;
use App\Data\Request\Freepik\IconGenerationData;
use App\Data\Request\Freepik\KlingImageToVideoData;
use App\Data\Request\Freepik\KlingTextToVideoData;
use App\Data\Request\Freepik\StockContentData;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

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

    public function iconGeneration(IconGenerationData $data): array
    {
        $response = $this->client
            ->post('ai/text-to-icon', [
                'prompt' => $data->prompt,
                'webhook_url' => config('services.freepik.webhook_url'),
            ])->throw();
        $responseJson = $response->json();

        // Set webhook result
        $this->setWebhookResult($responseJson['data']);

        return $responseJson;
    }

    public function klingImageToVideo(KlingImageToVideoData $data): array
    {
        $response = $this->client->post('ai/image-to-video/' . $data->model->value, array_filter(Arr::except($data->toArray(), 'model'), fn ($value) => $value !== null));

        return $response->json();
    }

    public function klingImageToVideoStatus(string $model, string $taskId): array
    {
        $response = $this->client
            ->get("ai/image-to-video/{$model}/{$taskId}");

        return $response->json();
    }

    public function klingTextToVideo(KlingTextToVideoData $data): array
    {
        $response = $this->client->post('ai/image-to-video/kling-v2-1-master', array_filter($data->toArray(), fn ($value) => $value !== null));

        return $response->json();
    }

    public function klingTextToVideoStatus(string $taskId): array
    {
        $response = $this->client
            ->get("ai/image-to-video/kling-v2-1-master/{$taskId}");

        return $response->json();
    }

    public function setWebhookResult(array $result): void
    {
        Cache::set('freepik_' . $result['task_id'], $result);
    }

    public function getWebhookResult(string $taskId): array
    {
        return Cache::get('freepik_' . $taskId) ?? ['status' => 'NOT_FOUND', 'message' => 'Result not available.'];
    }
}
