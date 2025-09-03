<?php

namespace App\Services;

use App\Data\Request\Shotstack\CheckRenderStatusData;
use App\Data\Request\Shotstack\CreateAssetData;
use App\Data\Request\Shotstack\GetVideoMetadataData;
use App\Http\Exceptions\Forbidden;
use App\Http\Resources\Shotstack\CheckRenderStatusResource;
use App\Http\Resources\Shotstack\CreateAssetResource;
use App\Http\Resources\Shotstack\GetVideoMetadataResource;
use App\Models\ServiceProvider;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

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


    $this->apiKey = config('services.shotstack.api_key');
    throw_if(empty($this->apiKey), new NotFound('Shotstack API key not configured.'));

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
          'json' => [
            'timeline' => $timeline,
            'output' => $data->output,
          ],
        ]);

      if ($response->failed()) {
        throw new \Exception('Failed to create asset');
      }

      return CreateAssetResource::make($response->getData(true));
    } catch (ConnectionException | Exception $e) {
      Log::error('Shotstack request error: ' . $e->getMessage());
      throw new Forbidden('Shotstack request failed');
    }
  }

  public function checkRenderStatus(CheckRenderStatusData $data): CheckRenderStatusResource
  {
    try {
      $response = $this->client->get("edit/{$this->version}/render/{$data->id}");
      if ($response->failed()) {
        throw new \Exception('Failed to check render status');
      }

      return CheckRenderStatusResource::make($response->getData(true));
    } catch (ConnectionException | Exception $e) {
      Log::error('Shotstack request error: ' . $e->getMessage());
      throw new Forbidden('Shotstack request failed');
    }
  }

  public function getVideoMetadata(GetVideoMetadataData $data): GetVideoMetadataResource
  {
    try {
      $response = $this->client->get("serve/{$this->version}/assets/{$data->id}");
      if ($response->failed()) {
        throw new \Exception('Failed to get video metadata');
      }

      return GetVideoMetadataResource::make($response->getData(true));
    } catch (ConnectionException | Exception $e) {
      Log::error('Shotstack request error: ' . $e->getMessage());
      throw new Forbidden('Shotstack request failed');
    }
  }

  private function buildTimeline(array $clips): array
  {
    $tracks = [];

    foreach ($clips as $clip) {
      $tracks[] = [
        "clips" => $clip,
      ];
    }

    return ["timeline" => [
      "tracks" => $tracks,
    ]];
  }
}
