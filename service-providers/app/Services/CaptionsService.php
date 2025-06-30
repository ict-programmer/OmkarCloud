<?php

namespace App\Services;

use App\Data\Request\Captions\AiAdsPollData;
use App\Data\Request\Captions\AiAdsSubmitData;
use App\Data\Request\Captions\AiCreatorPollData;
use App\Data\Request\Captions\AiCreatorSubmitData;
use App\Data\Request\Captions\AiTranslatePollData;
use App\Data\Request\Captions\AiTranslateSubmitData;
use Illuminate\Http\Client\PendingRequest;
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

    public function getCreatorsList(): array
    {
        $response = $this->client->post('creator/list');

        return $response->json();
    }

    public function submitVideoGeneration(AiCreatorSubmitData $data): array
    {
        $response = $this->client->post('creator/submit', array_filter($data->toArray(), fn ($value) => $value !== null));

        return $response->json();
    }

    public function pollVideoGenerationStatus(AiCreatorPollData $data): array
    {
        $response = $this->client->post('creator/poll', array_filter($data->toArray(), fn ($value) => $value !== null));

        return $response->json();
    }

    public function getSupportedLanguages(): array
    {
        $response = $this->client->post('translate/supported-languages');

        return $response->json();
    }

    public function submitVideoTranslation(AiTranslateSubmitData $data): array
    {
        $response = $this->client->post('translate/submit', array_filter($data->toArray(), fn ($value) => $value !== null));

        return $response->json();
    }

    public function pollTranslationStatus(AiTranslatePollData $data): array
    {
        $response = $this->client->post('translate/poll', array_filter($data->toArray(), fn ($value) => $value !== null));

        return $response->json();
    }

    public function getAdsCreatorsList(): array
    {
        $response = $this->client->post('ads/list-creators');

        return $response->json();
    }

    public function submitAdVideoGeneration(AiAdsSubmitData $data): array
    {
        $payload = array_filter($data->toArray(), fn ($value) => $value !== null);

        $response = $this->client->post('ads/submit', $payload);

        return $response->json();
    }

    public function pollAdVideoStatus(AiAdsPollData $data): array
    {
        $response = $this->client->post('ads/poll', array_filter($data->toArray(), fn ($value) => $value !== null));

        return $response->json();
    }
}
