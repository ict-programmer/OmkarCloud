<?php

namespace App\Services;

use App\Data\Request\Captions\AiAdsPollData;
use App\Data\Request\Captions\AiAdsSubmitData;
use App\Data\Request\Captions\AiCreatorPollData;
use App\Data\Request\Captions\AiCreatorSubmitData;
use App\Data\Request\Captions\AiTranslatePollData;
use App\Data\Request\Captions\AiTranslateSubmitData;
use App\Data\Request\Captions\AiTwinCreateData;
use App\Data\Request\Captions\AiTwinDeleteData;
use App\Data\Request\Captions\AiTwinScriptData;
use App\Data\Request\Captions\AiTwinStatusData;
use App\Exceptions\ApiException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class CaptionsService
{
    protected string $baseUrl = 'https://api.captions.ai/api';

    private PendingRequest $client;

    public function __construct()
    {
        $this->client = Http::baseUrl($this->baseUrl)
            ->withHeader('x-api-key', config(('services.captions.api_key')))
            ->timeout(30 * 60);
    }

    private function post(string $endpoint, array $payload = []): array
    {
        try {
            return $this->client
                ->post($endpoint, array_filter($payload, fn ($value) => $value !== null))
                ->throw()
                ->json();
        } catch (RequestException $e) {
            $message = $e->response->json('detail') ?? 'API request failed';
            $status = $e->response->status();

            throw new ApiException($message, $status);
        }
    }

    public function getCreatorsList(): array
    {
        return $this->post('creator/list');
    }

    public function submitVideoGeneration(AiCreatorSubmitData $data): array
    {
        return $this->post('creator/submit', $data->toArray());
    }

    public function pollVideoGenerationStatus(AiCreatorPollData $data): array
    {
        return $this->post('creator/poll', $data->toArray());
    }

    public function getSupportedLanguages(): array
    {
        return $this->post('translate/supported-languages');
    }

    public function submitVideoTranslation(AiTranslateSubmitData $data): array
    {
        return $this->post('translate/submit', $data->toArray());
    }

    public function pollTranslationStatus(AiTranslatePollData $data): array
    {
        return $this->post('translate/poll', $data->toArray());
    }

    public function getAdsCreatorsList(): array
    {
        return $this->post('ads/list-creators');
    }

    public function submitAdVideoGeneration(AiAdsSubmitData $data): array
    {
        return $this->post('ads/submit', $data->toArray());
    }

    public function pollAdVideoStatus(AiAdsPollData $data): array
    {
        return $this->post('ads/poll', $data->toArray());
    }

    public function getTwinSupportedLanguages(): array
    {
        return $this->post('twin/supported-languages');
    }

    public function getAiTwins(): array
    {
        return $this->post('twin/list');
    }

    public function createAiTwin(AiTwinCreateData $data): array
    {
        return $this->post('twin/create', $data->toArray());
    }

    public function checkAiTwinStatus(AiTwinStatusData $data): array
    {
        return $this->post('twin/status', $data->toArray());
    }

    public function getAiTwinScript(AiTwinScriptData $data): array
    {
        return $this->post('twin/script', $data->toArray());
    }

    public function deleteAiTwin(AiTwinDeleteData $data): array
    {
        return $this->post('twin/delete', $data->toArray());
    }
}
