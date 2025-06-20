<?php

namespace App\Services;

use App\Data\GettyImages\AffiliateImageSearchData;
use App\Data\GettyImages\AffiliateVideoSearchData;
use App\Data\GettyImages\ImageSearchData;
use App\Data\GettyImages\VideoSearchData;
use App\Http\Exceptions\Forbidden;
use App\Http\Exceptions\NotFound;
use App\Http\Resources\GettyImages\AffiliateImageSearchResource;
use App\Http\Resources\GettyImages\AffiliateVideoSearchResource;
use App\Http\Resources\GettyImages\DownloadImageResource;
use App\Http\Resources\GettyImages\DownloadVideoResource;
use App\Http\Resources\GettyImages\ImageMetadataResource;
use App\Http\Resources\GettyImages\VideoMetadataResource;
use App\Models\ServiceProvider;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;
use stdClass;

class GettyimagesService
{

  protected string $apiUrl;

  protected string $apiKey;

  protected PendingRequest $client;

  /**
   * This file contains the GettyimagesService class, which is responsible for
   * interacting with the external Getty Images API service. The service provides
   * methods to handle API requests and responses for integration purposes.
   */
  protected function initializeService(): void
  {
    $provider = ServiceProvider::where('type', 'Getty Images')->first();

    if (
      !$provider ||
      !isset($provider->parameter['base_url'], $provider->parameter['version'])
    ) {
      throw new NotFound('Getty Images API service provider not found.');
    }

    $apiKey = config('services.gettyimages.api_key');

    throw_if(empty($apiKey), new NotFound('Getty Images API key not configured.'));

    $this->apiKey = $apiKey;

    $this->apiUrl = "{$provider->parameter['base_url']}/{$provider->parameter['version']}";

    $this->client = Http::withHeaders([
      'Accept' => 'application/json',
      'Authorization' => 'Bearer ' . $this->apiKey,
    ])->timeout(0)
      ->connectTimeout(15);
  }

  public function searchImages(ImageSearchData $dto): AffiliateImageSearchResource
  {
    $this->initializeService();
    try {
      $response = $this->client->get($this->apiUrl . '/search/images', [
        'phrase' => $dto->phrase,
        'Accept-Language' => $dto->fields['language'] ?? null,
        'GI-Country-Code' => $dto->fields['countryCode'] ?? null,
        'sort_order' => $dto->sort_order ?? null,
        'age_of_people' => $dto->fields['age_of_people'] ?? null,
        'artists' => $dto->fields['artists'] ?? null,
        'collection_codes' => $dto->fields['collection_codes'] ?? null,
        'collections_filter_type' => $dto->fields['collections_filter_type'] ?? null,
        'color' => $dto->fields['color'] ?? null,
        'compositions' => $dto->fields['compositions'] ?? null,
        'download_product' => $dto->fields['download_product'] ?? null,
        'embed_content_only' => $dto->fields['embed_content_only'] ?? null,
        'event_ids' => $dto->fields['event_ids'] ?? null,
        'ethnicity' => $dto->fields['ethnicity'] ?? null,
        'exclude_nudity' => $dto->fields['exclude_nudity'] ?? null,
        'fields' => $dto->fields['fields'] ?? null,
        'file_types' => $dto->fields['file_types'] ?? null,
        'graphical_styles' => $dto->fields['graphical_styles'] ?? null,
        'graphical_style_filter_type' => $dto->fields['graphical_style_filter_type'] ?? null,
        'include_related_searches' => $dto->fields['include_related_searches'] ?? null,
        'keyword_ids' => $dto->fields['keyword_ids'] ?? null,
        'minimum_size' => $dto->fields['minimum_size'] ?? null,
        'number_of_people' => $dto->fields['number_of_people'] ?? null,
        'orientations' => $dto->fields['orientations'] ?? null,
        'page' => $dto->fields['page'] ?? null,
        'page_size' => $dto->fields['page_size'] ?? null,
        'specific_people' => $dto->fields['specific_people'] ?? null,
      ]);
      
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }

    $result = $this->handleResponse($response);

    return AffiliateImageSearchResource::make($result->data);
  }

  // public function searchVideos(VideoSearchData $dto): AffiliateImageSearchResource
  // {
  //   $this->initializeService();
  //   try {
  //     $response = $this->client->get($this->apiUrl . '/affiliates/search/images', [
  //       'phrase' => $dto->phrase,
  //       'Accept-Language' => $dto->fields['language'] ?? null,
  //       'GI-Country-Code' => $dto->fields['countryCode'] ?? null,
  //       'style' => $dto->fields['style'] ?? null,
  //     ]);
  //   } catch (ConnectionException | Exception $e) {
  //     Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
  //     throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
  //   }

  //   $result = $this->handleResponse($response);

  //   return AffiliateImageSearchResource::make($result->data);
  // }

  public function searchAffiliateImages(AffiliateImageSearchData $dto): AffiliateImageSearchResource
  {
    $this->initializeService();
    try {
      $response = $this->client->get($this->apiUrl . '/affiliates/search/images', [
        'phrase' => $dto->phrase,
        'Accept-Language' => $dto->fields['language'] ?? null,
        'GI-Country-Code' => $dto->fields['countryCode'] ?? null,
        'style' => $dto->fields['style'] ?? null,
      ]);
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }

    $result = $this->handleResponse($response);

    return AffiliateImageSearchResource::make($result->data);
  }

  public function searchAffiliateVideos(AffiliateVideoSearchData $dto): AffiliateVideoSearchResource
  {
    $this->initializeService();
    try {
      $response = $this->client->get($this->apiUrl . '/affiliates/search/videos', [
        'phrase' => $dto->phrase,
        'Accept-Language' => $dto->fields['language'] ?? null,
        'GI-Country-Code' => $dto->fields['countryCode'] ?? null,
      ]);
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }

    $result = $this->handleResponse($response);

    return AffiliateVideoSearchResource::make($result->data);
  }

  public function downloadImage(string $id): DownloadImageResource
  {
    $this->initializeService();
    try {
      $response = $this->client->post($this->apiUrl . '/downloads/images', [
        'id' => $id,
      ]);
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }

    $result = $this->handleResponse($response);

    return DownloadImageResource::make($result->data);
  }

  public function downloadVideo(string $id): DownloadVideoResource
  {
    $this->initializeService();
    try {
      $response = $this->client->post($this->apiUrl . '/downloads/videos', [
         'id' => $id,
      ]);
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }

    $result = $this->handleResponse($response);

    return DownloadVideoResource::make($result->data);
  }

  public function getImageMetadata(string $id): ImageMetadataResource
  {
    $this->initializeService();
    try {
      $response = $this->client->get($this->apiUrl . '/images', [
        'ids' => [
          $id
        ],
      ]);
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }

    $result = $this->handleResponse($response);

    return ImageMetadataResource::make($result->data);
  }

  public function getVideoMetadata(string $id): VideoMetadataResource
  {
    $this->initializeService();
    try {
      $response = $this->client->get($this->apiUrl . '/videos', [
        'ids' => [
          $id
        ],
      ]);
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }

    $result = $this->handleResponse($response);

    return VideoMetadataResource::make($result->data);
  }

  protected function handleResponse(Response $response): stdClass
  {
    if ($response->failed()) {
      Log::error('Getty Images request error: ' . json_encode($response->json()));
      if ($response->json()) {
        if (array_key_exists('message', $response->json())) {
          throw new Forbidden('Getty Images request failed: ' . $response->json()['message']);
        }
        if (array_key_exists('error', $response->json())) {
          throw new Forbidden('Getty Images request failed: ' . $response->json()['error']);
        }
      }

      throw new Forbidden('Getty Images request failed');
    }

    $result = new stdClass;
    $result->data = $response->json();

    return $result;
  }
}
