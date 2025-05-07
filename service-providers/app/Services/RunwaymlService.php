<?php

namespace App\Services;

use App\Data\Runwayml\TaskManagementData;
use App\Data\Runwayml\VideoProcessingData;
use App\Http\Exceptions\Forbidden;
use App\Http\Exceptions\NotFound;
use App\Http\Resources\Runwayml\TaskManagementResource;
use App\Http\Resources\Runwayml\VideoProcessingResource;
use App\Models\ServiceProvider;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use stdClass;

class RunwaymlService
{
    /**
     * RunwayML API model
     */
    protected string $RUNWWAYML_API_VERSION_HEADER = '2024-11-06';

    protected string $apiUrl;

    protected PendingRequest $client;

    /**
     * This file contains the RunwayMLService class, which is responsible for
     * interacting with the external RunwayML API service. The service provides
     * methods to handle API requests and responses for integration purposes.
     */
    protected function initializeService(): void
    {
        $provider = ServiceProvider::where('type', 'RunwayML')->first();

        if (
            !$provider ||
            !isset($provider->parameter['base_url'], $provider->parameter['version'])
        ) {
            throw new NotFound('RunwayML API service provider not found.');
        }

        $apiKey = config('services.runwayml.api_key');

        throw_if(empty($apiKey), new NotFound('RunwayML API key not configured.'));

        $this->apiUrl = "{$provider->parameter['base_url']}/{$provider->parameter['version']}";

        $this->client = Http::withToken($apiKey)
            ->withHeader('X-Runway-Version', $this->RUNWWAYML_API_VERSION_HEADER)
            ->timeout(0)
            ->connectTimeout(15);
    }

    public function videoProcessing(VideoProcessingData $data): VideoProcessingResource
    {
        $this->initializeService();

        try {
            $response = $this->client
                ->post($this->apiUrl . '/image_to_video', [
                    'model' => $data->model,
                    'promptImage' => $data->prompt_image,
                    'promptText' => $data->prompt_text,
                    'seed' => $data->seed,
                    'duration' => $data->duration,
                    'ratio' => $data->width . ':' . $data->height,
                ]);
        } catch (ConnectionException | Exception $e) {
            Log::error('RunwayML API request error: ' . json_encode($e->getMessage()));
            throw new Forbidden('RunwayML API request failed');
        }

        $result = $this->handleResponse($response);

        return VideoProcessingResource::make($result);
    }

    public function taskManagement(string $id): TaskManagementResource
    {
        $this->initializeService();

        try {
            $response = $this->client->get($this->apiUrl . "/tasks/{$id}");
        } catch (ConnectionException | Exception $e) {
            Log::error('RunwayML API request error: ' . json_encode($e->getMessage()));
            throw new Forbidden('RunwayML API request failed');
        }

        $result = $this->handleResponse($response);

        return TaskManagementResource::make($result);
    }

    protected function handleResponse(Response $response): stdClass
    {

        if ($response->failed()) {
            Log::error('RunwayML API request error: ' . json_encode($response->json()));
            if ($response->json() && array_key_exists('error', $response->json())) {
                throw new Forbidden('RunwayML API request failed: ' . $response->json()['error']);
            }

            throw new Forbidden('RunwayML API request failed');
        }

        $result = new stdClass;
        $result->data = $response->json();

        return $result;
    }
}
