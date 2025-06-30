<?php

namespace App\Services;

use App\Data\Request\Captions\AiCreatorPollData;
use App\Data\Request\Captions\AiCreatorSubmitData;
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
}
