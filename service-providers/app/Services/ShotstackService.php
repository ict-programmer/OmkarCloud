<?php

namespace App\Services;

use App\Data\Request\Shotstack\CheckRenderStatusData;
use App\Data\Request\Shotstack\CreateAssetData;
use App\Data\Request\Shotstack\GetVideoMetadataData;
use App\Enums\common\ServiceProviderEnum;
use App\Exceptions\ApiException;
use App\Http\Exceptions\Forbidden;
use App\Http\Exceptions\NotFound;
use App\Http\Resources\Shotstack\CheckRenderStatusResource;
use App\Http\Resources\Shotstack\CreateAssetResource;
use App\Http\Resources\Shotstack\GetVideoMetadataResource;
use App\Models\ServiceProvider;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShotstackService
{
  private string $baseUrl;
  private string $apiKey;
  private string $version;
  private PendingRequest $client;

  public function __construct()
  {
    $provider = ServiceProvider::where('type', ServiceProviderEnum::SHOTSTACK->value)->first();

    if (
      !$provider ||
      !isset($provider->parameters['base_url'], $provider->parameters['version'])
    ) {
      throw new NotFound('Shotstack API service provider not found.');
    }

    throw_if(empty(config('services.shotstack.api_key')), new NotFound('Shotstack API key not found.'));
    $this->apiKey = config('services.shotstack.api_key');

    $this->baseUrl = $provider->parameters['base_url'];
    $this->version = $provider->parameters['version'];

    $this->client = Http::baseUrl($this->baseUrl)
      ->withHeader('x-api-key', $this->apiKey);
  }

  public function createAsset(CreateAssetData $data): CreateAssetResource
  {
    try {
      $timeline = $this->buildTimeline($data->clips);
      $response = $this->client
        ->post("edit/{$this->version}/render", [
          'timeline' => $timeline,
          'output' => $data->output,
        ]);

      $parsedResponse = $response->json();

      if ($response->failed()) {
        throw new ApiException('Failed to create asset', $response->getStatusCode(), $parsedResponse);
      }

      return CreateAssetResource::make($parsedResponse);
    } catch (ConnectionException | Exception $e) {
      Log::error('Shotstack request error: ' . $e->getMessage());
      throw $this->handleException($e);
    }
  }

  public function checkRenderStatus(CheckRenderStatusData $data): CheckRenderStatusResource
  {
    try {
      $response = $this->client->get("edit/{$this->version}/render/{$data->id}");

      $parsedResponse = $response->json();

      if ($response->failed()) {
        throw new ApiException('Failed to check render status', $response->getStatusCode(), $parsedResponse);
      }

      return CheckRenderStatusResource::make($response->json());
    } catch (ConnectionException | Exception $e) {
      Log::error('Shotstack request error: ' . $e->getMessage());
      throw $this->handleException($e);
    }
  }

  public function getVideoMetadata(GetVideoMetadataData $data): GetVideoMetadataResource
  {
    try {
      $response = $this->client->get("serve/{$this->version}/assets/render/{$data->id}");

      $parsedResponse = $response->json();

      if ($response->failed()) {
        throw new ApiException('Failed to get video metadata', $response->getStatusCode(), $parsedResponse);
      }

      return GetVideoMetadataResource::make($response->json());
    } catch (ConnectionException | Exception $e) {
      Log::error('Shotstack request error: ' . $e->getMessage());
      throw $this->handleException($e);
    }
  }

  private function buildTimeline(array $clips): array
  {
    $tracks = [];

    foreach ($clips as $clip) {
      $tracks[] = [
        "clips" => [$clip],
      ];
    }

    return  [
      "tracks" => $tracks,
    ];
  }

  private function handleException(Exception $e): Exception
  {
    if ($e instanceof ApiException) {
      $response = $e->details;
      if ($e->getStatusCode() == 400 && $response && array_key_exists('response', $response) && array_key_exists('errors', $response['response'])) {
        $message = $response['response']['message'];
        $errors = [];
        foreach ($response['response']['errors'] as $error) {
          $errors[] = $error['message'];
        }
        return new ApiException($message, $e->getStatusCode(), $errors);
      }

      if ($e->getStatusCode() == 404) {
        return new NotFound('Shotstack asset not found');
      }

      if ($e->getStatusCode() == 401 && $response && array_key_exists('errors', $response)) {
        $message = $response['errors'][0]['detail'];
        return new ApiException($message, $e->getStatusCode());
      }

      return $e;
    }

    return new Forbidden('Shotstack request failed: ' . $e->getMessage());
  }
}
