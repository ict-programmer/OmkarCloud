<?php

namespace App\Services;

use App\Data\Request\PremierPro\ImageGenData;
use App\Data\Request\PremierPro\ReframeData;
use App\Http\Exceptions\BadRequest;
use App\Http\Exceptions\NotFound;
use App\Traits\PremierProTrait;
use App\Traits\PubliishIOTrait;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class PremierProService
{
  use PremierProTrait, PubliishIOTrait;

  private PendingRequest $client;

  public function __construct()
  {
    $baseUri = config('premierpro.api_url');
    $accessToken = $this->getAccessToken();
    $apiKey = $this->getApiKey();

    if (empty($accessToken) || empty($apiKey)) {
      throw new NotFound('PremierPro service provider not found.');
    }

    $this->client = Http::withHeaders([
      'Content-Type' => 'application/json',
      'Accept' => 'application/json',
      'Authorization' => 'Bearer ' . $accessToken,
      'x-api-key' => $apiKey,
    ])
      ->timeout(0)
      ->connectTimeout(15)
      ->baseUrl($baseUri);
  }
  /**
   * 
   * @param ReframeData $data
   * @return array
   * @throws BadRequest
   */
  public function reframe(ReframeData $data): array
  {
    $data->video_cid = $this->getPublishUrl($data->video_cid);

    $response = $this->client->post('/reframe', [
      'json' => [
        'video_url' => $data->video_cid,
        'scene_detection' => $data->scene_detection,
        'output_config' => $data->output_config,
      ],
    ]);

    if ($response->failed()) {
      throw new BadRequest($response->json('message'));
    }

    return $response->json('data');
  }

  public function imageGeneration(ImageGenData $data): array
  {
    $baseUri = config('premierpro.firefly_base_url');
    $response = $this->client
      ->baseUrl($baseUri)
      ->post('/images/generate', [
        'json' => [
          'prompt' => $data->prompt,
        ],
      ]);

    if ($response->failed()) {
      throw new BadRequest($response->json('message'));
    }

    return $response->json('data');
  }

  public function getStatus(string $jobId): array
  {
    $response = $this->client->get("/status/{$jobId}");

    if ($response->failed()) {
      throw new BadRequest($response->json('message'));
    }

    return $response->json('data');
  }
}
