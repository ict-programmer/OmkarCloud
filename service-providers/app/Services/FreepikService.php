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
            $client = $this->client;

            if ($isMultipart) {
                $client->asMultipart();
            }

            return $client
                ->post($endpoint, array_filter($payload, fn($value) => $value !== null))
                ->throw()
                ->json();
        } catch (RequestException $e) {
            $message = $e->response->json('message') ?? 'API request failed';
            $status = $e->response->status();
            $responseData = $e->response->json();
            if (is_array($responseData)) {
                throw new ApiException($message, $status, Arr::except($responseData, 'message'));
            }
            throw new ApiException($message, $status, $responseData);
        }
    }

    private function get(string $endpoint, array $payload = []): array
    {
        try {
            return $this->client
                ->get($endpoint, array_filter($payload, fn($value) => $value !== null))
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
        return $this->get('/resources', $data->toArray());
    }

    public function resourceDetail(string $resource_id): array
    {
        return $this->get('/resources/' . $resource_id);
    }

    public function downloadResource(string $resource_id): array
    {
        return $this->get('/resources/' . $resource_id . '/download');
    }

    public function downloadResourceFormat(DownloadResourceFormatData $data): array
    {
        return $this->get("/resources/{$data->resource_id}/download/{$data->format}");
    }

    public function aiImageClassifier(AiImageClassifierData $data): array
    {
        return $this->post('/ai/classifier/image', [
            'image' => $data->image_url,
        ]);
    }

    public function iconGeneration(IconGenerationData $data): array
    {
        $responseJson = $this->post('ai/text-to-icon', [
            'prompt' => $data->prompt,
            'webhook_url' => config('services.freepik.webhook_url'),
        ]);

        if (isset($responseJson['data'])) {
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

        return $this->get("ai/image-to-video/{$model->getStatusModel()}/{$taskId}");
    }

    public function klingTextToVideo(KlingTextToVideoData $data): array
    {
        return $this->post('ai/image-to-video/kling-v2-1-master', $data->toArray());
    }

    public function klingTextToVideoStatus(string $taskId): array
    {
        return $this->get("ai/image-to-video/kling-v2-1-master/{$taskId}");
    }

    public function klingElementsVideo(KlingElementsVideoData $data): array
    {
        return $this->post('ai/image-to-video/' . $data->model->value, Arr::except($data->toArray(), 'model'));
    }

    public function klingElementsVideoStatus(string $taskId): array
    {
        return $this->get("ai/image-to-video/kling-elements/{$taskId}");
    }

    public function generateMysticImage(MysticGenerateData $data): array
    {
        if (!empty($data->structure_reference)) {
            $data->structure_reference = ImageToBase64Converter::imageUrlToBase64($data->structure_reference, false);
        }

        if (!empty($data->style_reference)) {
            $data->style_reference = ImageToBase64Converter::imageUrlToBase64($data->style_reference, false);
        }

        return $this->post('ai/mystic', $data->toArray());
    }

    public function getMysticTaskStatus(string $taskId): array
    {
        return $this->get("ai/mystic/{$taskId}");
    }

    public function getLoras(): array
    {
        return $this->get('ai/loras');
    }

    public function trainLoraStyle(LoraStyleTrainData $data): array
    {
        return $this->post('ai/loras/styles', $data->toArray());
    }

    public function trainLoraCharacter(LoraCharacterTrainData $data): array
    {
        return $this->post('ai/loras/characters', $data->toArray());
    }

    public function generateClassicFastImage(ClassicFastGenerateData $data): array
    {
        return $this->post('ai/text-to-image', $data->toArray());
    }

    public function generateImagen3Image(Imagen3GenerateData $data): array
    {
        return $this->post('ai/text-to-image/imagen3', $data->toArray());
    }

    public function getImagen3TaskStatus(string $taskId): array
    {
        return $this->get("ai/text-to-image/imagen3/{$taskId}");
    }

    public function generateFluxDevImage(FluxDevGenerateData $data): array
    {
        return $this->post('ai/text-to-image/flux-dev', array_filter($data->toArray(), fn($value) => $value !== null));
    }

    public function getFluxDevTaskStatus(string $taskId): array
    {
        return $this->get("ai/text-to-image/flux-dev/{$taskId}");
    }

    public function reimagineFluxImage(ReimagineFluxData $data): array
    {
        if (!empty($data->image)) {
            $data->image = ImageToBase64Converter::imageUrlToBase64($data->image, false);
        }

        return $this->post('ai/beta/text-to-image/reimagine-flux', $data->toArray());
    }

    public function upscaleImage(UpscaleImageData $data): array
    {
        if (!empty($data->image)) {
            $data->image = ImageToBase64Converter::imageUrlToBase64($data->image, false);
        }

        return $this->post('ai/image-upscaler', $data->toArray());
    }

    public function getUpscalerTaskStatus(string $taskId): array
    {
        return $this->get("ai/image-upscaler/{$taskId}");
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

        return $this->post('ai/image-relight', $data->toArray());
    }

    public function getRelightTaskStatus(string $taskId): array
    {
        return $this->get("ai/image-relight/{$taskId}");
    }

    public function styleTransfer(StyleTransferData $data): array
    {
        if (!empty($data->image)) {
            $data->image = ImageToBase64Converter::imageUrlToBase64($data->image, false);
        }
        if (!empty($data->reference_image)) {
            $data->reference_image = ImageToBase64Converter::imageUrlToBase64($data->reference_image, false);
        }

        return $this->post('ai/image-style-transfer', $data->toArray());
    }

    public function getStyleTransferTaskStatus(string $taskId): array
    {
        return $this->get("ai/image-style-transfer/{$taskId}");
    }

    public function removeBackground(RemoveBackgroundData $data): array
    {
        return $this->post('ai/beta/remove-background', $data->toArray(), true);
    }

    public function imageExpandFluxPro(ImageExpandFluxProData $data): array
    {
        if (!empty($data->image)) {
            $data->image = ImageToBase64Converter::imageUrlToBase64($data->image, false);
        }

        return $this->post('ai/image-expand/flux-pro', $data->toArray());
    }

    public function getImageExpandFluxProTaskStatus(string $taskId): array
    {
        return $this->get("ai/image-expand/flux-pro/{$taskId}");
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
