<?php

namespace App\Services;

use App\Data\Request\Freepik\AiImageClassifierData;
use App\Data\Request\Freepik\ClassicFastGenerateData;
use App\Data\Request\Freepik\DownloadResourceFormatData;
use App\Data\Request\Freepik\FluxDevGenerateData;
use App\Data\Request\Freepik\IconGenerationData;
use App\Data\Request\Freepik\ImageExpandFluxProData;
use App\Data\Request\Freepik\Imagen3GenerateData;
use App\Data\Request\Freepik\KlingElementsVideoData;
use App\Data\Request\Freepik\KlingImageToVideoData;
use App\Data\Request\Freepik\KlingTextToVideoData;
use App\Data\Request\Freepik\LoraCharacterTrainData;
use App\Data\Request\Freepik\LoraStyleTrainData;
use App\Data\Request\Freepik\MysticGenerateData;
use App\Data\Request\Freepik\ReimagineFluxData;
use App\Data\Request\Freepik\RelightImageData;
use App\Data\Request\Freepik\RemoveBackgroundData;
use App\Data\Request\Freepik\StockContentData;
use App\Data\Request\Freepik\StyleTransferData;
use App\Data\Request\Freepik\UpscaleImageData;
use App\Enums\Freepik\KlingModelEnum;
use App\Exceptions\ApiException;
use App\Helpers\ImageToBase64Converter;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
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

    private function post(string $endpoint, array $payload = [], bool $isMultipart = false): array
    {
        try {
            $client = $this->client
                ->post($endpoint, array_filter($payload, fn ($value) => $value !== null));

            if ($isMultipart) {
                $client->asMultipart();
            }

            return $client
                ->throw()
                ->json();
        } catch (RequestException $e) {
            $message = $e->response->json('message') ?? 'API request failed';
            $status = $e->response->status();
            throw new ApiException($message, $status, Arr::except($e->response->json(), 'message'));
        }
    }

    private function get(string $endpoint, array $payload = []): array
    {
        try {
            return $this->client
                ->get($endpoint, array_filter($payload, fn ($value) => $value !== null))
                ->throw()
                ->json();
        } catch (RequestException $e) {
            $message = $e->response->json('message') ?? 'API request failed';
            $status = $e->response->status();
            throw new ApiException($message, $status, Arr::except($e->response->json(), 'message'));
        }
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
        return $this->post('/ai/classifier/image', [
            'image' => $data->image_url,
        ]);
    }

    public function iconGeneration(IconGenerationData $data): array
    {
        $response = $this->client
            ->post('ai/text-to-icon', [
                'prompt' => $data->prompt,
                'webhook_url' => config('services.freepik.webhook_url'),
            ]);
        $responseJson = $response->json();

        if ($response->successful()) {
            // Set webhook result
            $this->setWebhookResult($responseJson['data']);
        }

        return $responseJson;
    }

    public function klingImageToVideo(KlingImageToVideoData $data): array
    {
        return $this->post('ai/image-to-video/' . $data->model->value, Arr::except($data->toArray(), 'model'));
    }

    public function klingImageToVideoStatus(string $model, string $taskId): array
    {
        $model = KlingModelEnum::from($model);

        $response = $this->client
            ->get("ai/image-to-video/{$model->getStatusModel()}/{$taskId}");

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

    public function klingElementsVideo(KlingElementsVideoData $data): array
    {
        return $this->post('ai/image-to-video/' . $data->model->value, Arr::except($data->toArray(), 'model'));
    }

    public function klingElementsVideoStatus(string $taskId): array
    {
        $response = $this->client
            ->get("ai/image-to-video/kling-elements/{$taskId}");

        return $response->json();
    }

    public function generateMysticImage(MysticGenerateData $data): array
    {
        if (!empty($data->structure_reference)) {
            $data->structure_reference = ImageToBase64Converter::imageUrlToBase64($data->structure_reference, false);
        }

        if (!empty($data->style_reference)) {
            $data->style_reference = ImageToBase64Converter::imageUrlToBase64($data->style_reference, false);
        }

        $response = $this->client->post('ai/mystic', array_filter($data->toArray(), fn ($value) => $value !== null));

        return $response->json();
    }

    public function getMysticTaskStatus(string $taskId): array
    {
        $response = $this->client->get("ai/mystic/{$taskId}");

        return $response->json();
    }

    public function getLoras(): array
    {
        $response = $this->client->get('ai/loras');

        return $response->json();
    }

    public function trainLoraStyle(LoraStyleTrainData $data): array
    {
        $response = $this->client->post('ai/loras/styles', array_filter($data->toArray(), fn ($value) => $value !== null));

        return $response->json();
    }

    public function trainLoraCharacter(LoraCharacterTrainData $data): array
    {
        $response = $this->client->post('ai/loras/characters', array_filter($data->toArray(), fn ($value) => $value !== null));

        return $response->json();
    }

    public function generateClassicFastImage(ClassicFastGenerateData $data): array
    {
        $response = $this->client->post('ai/text-to-image', array_filter($data->toArray(), fn ($value) => $value !== null));

        return $response->json();
    }

    public function generateImagen3Image(Imagen3GenerateData $data): array
    {
        $response = $this->client->post('ai/text-to-image/imagen3', array_filter($data->toArray(), fn ($value) => $value !== null));

        return $response->json();
    }

    public function getImagen3TaskStatus(string $taskId): array
    {
        $response = $this->client->get("ai/text-to-image/imagen3/{$taskId}");

        return $response->json();
    }

    public function generateFluxDevImage(FluxDevGenerateData $data): array
    {
        $response = $this->client->post('ai/text-to-image/flux-dev', array_filter($data->toArray(), fn ($value) => $value !== null));

        return $response->json();
    }

    public function getFluxDevTaskStatus(string $taskId): array
    {
        $response = $this->client->get("ai/text-to-image/flux-dev/{$taskId}");

        return $response->json();
    }

    public function reimagineFluxImage(ReimagineFluxData $data): array
    {
        if (!empty($data->image)) {
            $data->image = ImageToBase64Converter::imageUrlToBase64($data->image, false);
        }
        $response = $this->client->post('ai/beta/text-to-image/reimagine-flux', array_filter($data->toArray(), fn ($value) => $value !== null));

        return $response->json();
    }

    public function upscaleImage(UpscaleImageData $data): array
    {
        if (!empty($data->image)) {
            $data->image = ImageToBase64Converter::imageUrlToBase64($data->image, false);
        }

        $response = $this->client->post('ai/image-upscaler', array_filter($data->toArray(), fn ($value) => $value !== null));

        return $response->json();
    }

    public function getUpscalerTaskStatus(string $taskId): array
    {
        $response = $this->client->get("ai/image-upscaler/{$taskId}");

        return $response->json();
    }

    public function relightImage(RelightImageData $data): array
    {
        if (!empty($data->image)) {
            $data->image = ImageToBase64Converter::imageUrlToBase64($data->image, false);
        }
        if (!empty($data->transfer_light_from_reference_image)) {
            $data->transfer_light_from_reference_image = ImageToBase64Converter::imageUrlToBase64($data->transfer_light_from_reference_image, false);
        }
        if (!empty($data->transfer_light_from_lightmap)) {
            $data->transfer_light_from_lightmap = ImageToBase64Converter::imageUrlToBase64($data->transfer_light_from_lightmap, false);
        }

        $response = $this->client->post('ai/image-relight', array_filter($data->toArray(), fn ($value) => $value !== null));

        return $response->json();
    }

    public function getRelightTaskStatus(string $taskId): array
    {
        $response = $this->client->get("ai/image-relight/{$taskId}");

        return $response->json();
    }

    public function styleTransfer(StyleTransferData $data): array
    {
        if (!empty($data->image)) {
            $data->image = ImageToBase64Converter::imageUrlToBase64($data->image, false);
        }
        if (!empty($data->reference_image)) {
            $data->reference_image = ImageToBase64Converter::imageUrlToBase64($data->reference_image, false);
        }

        $response = $this->client->post('ai/image-style-transfer', array_filter($data->toArray(), fn ($value) => $value !== null));

        return $response->json();
    }

    public function getStyleTransferTaskStatus(string $taskId): array
    {
        $response = $this->client->get("ai/image-style-transfer/{$taskId}");

        return $response->json();
    }

    public function removeBackground(RemoveBackgroundData $data): array
    {
        $response = $this->client
            ->asMultipart()
            ->post('ai/beta/remove-background', array_filter($data->toArray(), fn ($value) => $value !== null));

        return $response->json();
    }

    public function imageExpandFluxPro(ImageExpandFluxProData $data): array
    {
        if (!empty($data->image)) {
            $data->image = ImageToBase64Converter::imageUrlToBase64($data->image, false);
        }

        $response = $this->client->post('ai/image-expand/flux-pro', array_filter($data->toArray(), fn ($value) => $value !== null));

        return $response->json();
    }

    public function getImageExpandFluxProTaskStatus(string $taskId): array
    {
        $response = $this->client->get("ai/image-expand/flux-pro/{$taskId}");

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
