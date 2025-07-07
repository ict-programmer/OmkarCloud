<?php

namespace App\Services;

use App\Data\GettyImages\AffiliateImageSearchData;
use App\Data\GettyImages\AffiliateVideoSearchData;
use App\Data\GettyImages\DownloadImageAsyncData;
use App\Data\GettyImages\ExtendImageData;
use App\Data\GettyImages\GenerateBackgroundsData;
use App\Data\GettyImages\ImageGenerationData;
use App\Data\GettyImages\ImageSearchByImageUploadData;
use App\Data\GettyImages\ImageSearchCreativeByImageData;
use App\Data\GettyImages\ImageSearchCreativeData;
use App\Data\GettyImages\ImageSearchData;
use App\Data\GettyImages\ImageSearchEditorialData;
use App\Data\GettyImages\ImageVariationsData;
use App\Data\GettyImages\InfluenceColorByImageData;
use App\Data\GettyImages\InfluenceCompositionByImageData;
use App\Data\GettyImages\RefineImageData;
use App\Data\GettyImages\RemoveBackgroundData;
use App\Data\GettyImages\RemoveObjectFromImageData;
use App\Data\GettyImages\ReplaceBackgroundData;
use App\Data\GettyImages\VideoSearchCreativeByImageData;
use App\Data\GettyImages\VideoSearchCreativeData;
use App\Data\GettyImages\VideoSearchData;
use App\Data\GettyImages\VideoSearchEditorialData;
use App\Enums\common\ServiceProviderEnum;
use App\Http\Exceptions\Forbidden;
use App\Http\Exceptions\NotFound;
use App\Http\Resources\GettyImages\AffiliateImageSearchResource;
use App\Http\Resources\GettyImages\AffiliateVideoSearchResource;
use App\Http\Resources\GettyImages\DownloadImageResource;
use App\Http\Resources\GettyImages\DownloadVideoResource;
use App\Http\Resources\GettyImages\ImageMetadataResource;
use App\Http\Resources\GettyImages\VideoMetadataResource;
use App\Models\ServiceProvider;
use App\Traits\QwenTrait;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use stdClass;

class GettyimagesService
{
  use QwenTrait;

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
    $provider = ServiceProvider::where('type', ServiceProviderEnum::GETTY_IMAGES->value)->first();

    if (
      !$provider ||
      !isset($provider->parameters['base_url'], $provider->parameters['version'])
    ) {
      throw new NotFound('Getty Images API service provider not found.');
    }

    $apiKey = config('services.gettyimages.api_key');

    throw_if(empty($apiKey), new NotFound('Getty Images API key not configured.'));

    $this->apiKey = $apiKey;

    $this->apiUrl = "{$provider->parameters['base_url']}/{$provider->parameters['version']}";

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

  public function searchImagesCreative(ImageSearchCreativeData $dto): AffiliateImageSearchResource
  {
    $this->initializeService();
    try {
      $response = $this->client->get($this->apiUrl . '/search/images/creative', [
        'phrase' => $dto->phrase ?? null,
        'sort_order' => $dto->sort_order ?? null,
        'fields' => $dto->fields['fields'] ?? null,
        'Accept-Language' => $dto->fields['language'] ?? null,
        'GI-Country-Code' => $dto->fields['countryCode'] ?? null,
        'age_of_people' => $dto->fields['age_of_people'] ?? null,
        'artists' => $dto->fields['artists'] ?? null,
        'collection_codes' => $dto->fields['collection_codes'] ?? null,
        'collections_filter_type' => $dto->fields['collections_filter_type'] ?? null,
        'color' => $dto->fields['color'] ?? null,
        'compositions' => $dto->fields['compositions'] ?? null,
        'download_product' => $dto->fields['download_product'] ?? null,
        'embed_content_only' => $dto->fields['embed_content_only'] ?? null,
        'enhanced_search' => $dto->fields['enhanced_search'] ?? null,
        'ethnicity' => $dto->fields['ethnicity'] ?? null,
        'exclude_editorial_use_only' => $dto->fields['exclude_editorial_use_only'] ?? null,
        'exclude_keyword_ids' => $dto->fields['exclude_keyword_ids'] ?? null,
        'exclude_nudity' => $dto->fields['exclude_nudity'] ?? null,
        'facet_fields' => $dto->fields['facet_fields'] ?? null,
        'facet_max_count' => $dto->fields['facet_max_count'] ?? null,
        'fields.fields' => $dto->fields['fields.fields'] ?? null,
        'file_types' => $dto->fields['file_types'] ?? null,
        'graphical_styles' => $dto->fields['graphical_styles'] ?? null,
        'graphical_styles_filter_type' => $dto->fields['graphical_styles_filter_type'] ?? null,
        'include_facets' => $dto->fields['include_facets'] ?? null,
        'include_related_searches' => $dto->fields['include_related_searches'] ?? null,
        'keyword_ids' => $dto->fields['keyword_ids'] ?? null,
        'minimum_size' => $dto->fields['minimum_size'] ?? null,
        'moods' => $dto->fields['moods'] ?? null,
        'number_of_people' => $dto->fields['number_of_people'] ?? null,
        'orientations' => $dto->fields['orientations'] ?? null,
        'page' => $dto->fields['page'] ?? null,
        'page_size' => $dto->fields['page_size'] ?? null,
        'safe_search' => $dto->fields['safe_search'] ?? null,
      ]);
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }

    $result = $this->handleResponse($response);

    return AffiliateImageSearchResource::make($result->data);
  }

  public function searchImagesCreativeByImage(ImageSearchCreativeByImageData $dto): AffiliateImageSearchResource
  {
    $this->initializeService();
    try {
      $response = $this->client->get($this->apiUrl . '/search/images/creative/by-image', [
        'phrase' => $dto->phrase ?? null,
        'Accept-Language' => $dto->fields['language'] ?? null,
        'GI-Country-Code' => $dto->fields['countryCode'] ?? null,
        'asset_id' => $dto->fields['asset_id'] ?? null,
        'exclude_editorial_use_only' => $dto->fields['exclude_editorial_use_only'] ?? null,
        'facet_fields' => $dto->fields['facet_fields'] ?? null,
        'facet_max_count' => $dto->fields['facet_max_count'] ?? null,
        'fields' => $dto->fields['fields'] ?? null,
        'image_url' => $dto->fields['image_url'] ?? null,
        'include_facets' => $dto->fields['include_facets'] ?? null,
        'page' => $dto->fields['page'] ?? null,
        'page_size' => $dto->fields['page_size'] ?? null,
        'product_types' => $dto->fields['product_types'] ?? null,
      ]);
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }

    $result = $this->handleResponse($response);

    return AffiliateImageSearchResource::make($result->data);
  }

  public function searchImagesEditorial(ImageSearchEditorialData $dto): AffiliateImageSearchResource
  {
    $this->initializeService();
    try {
      $response = $this->client->get($this->apiUrl . '/search/images/editorial', [
        'phrase' => $dto->phrase,
        'sort_order' => $dto->sort_order ?? null,
        'fields' => $dto->fields['fields'] ?? null,
        'Accept-Language' => $dto->fields['language'] ?? null,
        'GI-Country-Code' => $dto->fields['countryCode'] ?? null,
        'age_of_people' => $dto->fields['age_of_people'] ?? null,
        'artists' => $dto->fields['artists'] ?? null,
        'collection_codes' => $dto->fields['collection_codes'] ?? null,
        'collections_filter_type' => $dto->fields['collections_filter_type'] ?? null,
        'compositions' => $dto->fields['compositions'] ?? null,
        'date_from' => $dto->fields['date_from'] ?? null,
        'date_to' => $dto->fields['date_to'] ?? null,
        'download_product' => $dto->fields['download_product'] ?? null,
        'editorial_segments' => $dto->fields['editorial_segments'] ?? null,
        'embed_content_only' => $dto->fields['embed_content_only'] ?? null,
        'ethnicity' => $dto->fields['ethnicity'] ?? null,
        'event_ids' => $dto->fields['event_ids'] ?? null,
        'exclude_keyword_ids' => $dto->fields['exclude_keyword_ids'] ?? null,
        'fields.fields' => $dto->fields['fields.fields'] ?? null,
        'file_types' => $dto->fields['file_types'] ?? null,
        'graphical_styles' => $dto->fields['graphical_styles'] ?? null,
        'graphical_styles_filter_type' => $dto->fields['graphical_styles_filter_type'] ?? null,
        'include_related_searches' => $dto->fields['include_related_searches'] ?? null,
        'keyword_ids' => $dto->fields['keyword_ids'] ?? null,
        'minimum_size' => $dto->fields['minimum_size'] ?? null,
        'number_of_people' => $dto->fields['number_of_people'] ?? null,
        'orientations' => $dto->fields['orientations'] ?? null,
        'page' => $dto->fields['page'] ?? null,
        'page_size' => $dto->fields['page_size'] ?? null,
        'specific_people' => $dto->fields['specific_people'] ?? null,
        'minimum_quality_rank' => $dto->fields['minimum_quality_rank'] ?? null,
        'facet_fields' => $dto->fields['facet_fields'] ?? null,
        'facet_max_count' => $dto->fields['facet_max_count'] ?? null,
      ]);
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }

    $result = $this->handleResponse($response);

    return AffiliateImageSearchResource::make($result->data);
  }

  public function searchImagesByImageUpload(ImageSearchByImageUploadData $data): AffiliateImageSearchResource
  {
    $this->initializeService();

    if (isset($data->file) && !filter_var($data->file, FILTER_VALIDATE_URL)) {
      throw new Forbidden('The provided file name is not a valid URL.');
    }

    try {
      $response = $this->client->attach(
        name: 'file',
        contents: file_get_contents($data->file, false),
        filename: $data->file_name
      )->put($this->apiUrl . "/search/by-image/uploads/{$data->file_name}");
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }

    $result = $this->handleResponse($response);

    return AffiliateImageSearchResource::make($result->data);
  }

  public function searchVideosCreative(VideoSearchCreativeData $dto): AffiliateVideoSearchResource
  {
    $this->initializeService();
    try {
      $response = $this->client->get($this->apiUrl . '/search/videos/creative', [
        'phrase' => $dto->phrase,
        'sort_order' => $dto->sort_order ?? null,
        'fields' => $dto->fields['fields'] ?? null,
        'Accept-Language' => $dto->fields['language'] ?? null,
        'GI-Country-Code' => $dto->fields['countryCode'] ?? null,
        'age_of_people' => $dto->fields['age_of_people'] ?? null,
        'artists' => $dto->fields['artists'] ?? null,
        'aspect_ratios' => $dto->fields['aspect_ratios'] ?? null,
        'collection_codes' => $dto->fields['collection_codes'] ?? null,
        'collections_filter_type' => $dto->fields['collections_filter_type'] ?? null,
        'color' => $dto->fields['color'] ?? null,
        'compositions' => $dto->fields['compositions'] ?? null,
        'download_product' => $dto->fields['download_product'] ?? null,
        'enhanced_search' => $dto->fields['enhanced_search'] ?? null,
        'ethnicity' => $dto->fields['ethnicity'] ?? null,
        'exclude_editorial_use_only' => $dto->fields['exclude_editorial_use_only'] ?? null,
        'exclude_keyword_ids' => $dto->fields['exclude_keyword_ids'] ?? null,
        'exclude_nudity' => $dto->fields['exclude_nudity'] ?? null,
        'facet_fields' => $dto->fields['facet_fields'] ?? null,
        'facet_max_count' => $dto->fields['facet_max_count'] ?? null,
        'fields.fields' => $dto->fields['fields.fields'] ?? null,
        'format_available' => $dto->fields['format_available'] ?? null,
        'frame_rates' => $dto->fields['frame_rates'] ?? null,
        'image_techniques' => $dto->fields['image_techniques'] ?? null,
        'include_facets' => $dto->fields['include_facets'] ?? null,
        'keyword_ids' => $dto->fields['keyword_ids'] ?? null,
        'license_models' => $dto->fields['license_models'] ?? null,
        'min_clip_length' => $dto->fields['min_clip_length'] ?? null,
        'max_clip_length' => $dto->fields['max_clip_length'] ?? null,
        'number_of_people' => $dto->fields['number_of_people'] ?? null,
        'orientations' => $dto->fields['orientations'] ?? null,
        'page' => $dto->fields['page'] ?? null,
        'page_size' => $dto->fields['page_size'] ?? null,
        'safe_search' => $dto->fields['safe_search'] ?? null,
        'release_status' => $dto->fields['release_status'] ?? null,
        'viewpoints' => $dto->fields['viewpoints'] ?? null,
      ]);
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }

    $result = $this->handleResponse($response);

    return AffiliateVideoSearchResource::make($result->data);
  }

  public function searchVideosCreativeByImage(VideoSearchCreativeByImageData $dto): AffiliateVideoSearchResource
  {
    $this->initializeService();
    try {
      $response = $this->client->get($this->apiUrl . '/search/videos/creative/by-image', [
        'phrase' => $dto->phrase ?? null,
        'Accept-Language' => $dto->fields['language'] ?? null,
        'GI-Country-Code' => $dto->fields['countryCode'] ?? null,
        'asset_id' => $dto->fields['asset_id'] ?? null,
        'exclude_editorial_use_only' => $dto->fields['exclude_editorial_use_only'] ?? null,
        'facet_fields' => $dto->fields['facet_fields'] ?? null,
        'facet_max_count' => $dto->fields['facet_max_count'] ?? null,
        'fields' => $dto->fields['fields'] ?? null,
        'image_url' => $dto->fields['image_url'] ?? null,
        'include_facets' => $dto->fields['include_facets'] ?? null,
        'page' => $dto->fields['page'] ?? null,
        'page_size' => $dto->fields['page_size'] ?? null,
        'product_types' => $dto->fields['product_types'] ?? null,
      ]);
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }

    $result = $this->handleResponse($response);

    return AffiliateVideoSearchResource::make($result->data);
  }

  public function searchVideosEditorial(VideoSearchEditorialData $dto): AffiliateVideoSearchResource
  {
    $this->initializeService();
    try {
      $response = $this->client->get($this->apiUrl . '/search/videos/editorial', [
        'phrase' => $dto->phrase,
        'sort_order' => $dto->sort_order ?? null,
        'Accept-Language' => $dto->fields['language'] ?? null,
        'GI-Country-Code' => $dto->fields['countryCode'] ?? null,
        'age_of_people' => $dto->fields['age_of_people'] ?? null,
        'artists' => $dto->fields['artists'] ?? null,
        'aspect_ratios' => $dto->fields['aspect_ratios'] ?? null,
        'collection_codes' => $dto->fields['collection_codes'] ?? null,
        'collections_filter_type' => $dto->fields['collections_filter_type'] ?? null,
        'color' => $dto->fields['color'] ?? null,
        'compositions' => $dto->fields['compositions'] ?? null,
        'date_from' => $dto->fields['date_from'] ?? null,
        'date_to' => $dto->fields['date_to'] ?? null,
        'download_product' => $dto->fields['download_product'] ?? null,
        'editorial_video_types' => $dto->fields['editorial_video_types'] ?? null,
        'event_ids' => $dto->fields['event_ids'] ?? null,
        'format_available' => $dto->fields['format_available'] ?? null,
        'frame_rates' => $dto->fields['frame_rates'] ?? null,
        'image_techniques' => $dto->fields['image_techniques'] ?? null,
        'include_related_searches' => $dto->fields['include_related_searches'] ?? null,
        'keyword_ids' => $dto->fields['keyword_ids'] ?? null,
        'min_clip_length' => $dto->fields['min_clip_length'] ?? null,
        'max_clip_length' => $dto->fields['max_clip_length'] ?? null,
        'orientations' => $dto->fields['orientations'] ?? null,
        'page' => $dto->fields['page'] ?? null,
        'page_size' => $dto->fields['page_size'] ?? null,
        'specific_people' => $dto->fields['specific_people'] ?? null,
        'release_status' => $dto->fields['release_status'] ?? null,
        'facet_fields' => $dto->fields['facet_fields'] ?? null,
        'include_facets' => $dto->fields['include_facets'] ?? null,
        'facet_max_count' => $dto->fields['facet_max_count'] ?? null,
        'viewpoints' => $dto->fields['viewpoints'] ?? null,
        'fields' => $dto->fields['fields'] ?? null,
      ]);
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }

    $result = $this->handleResponse($response);

    return AffiliateVideoSearchResource::make($result->data);
  }

  public function removeBackground(RemoveBackgroundData $dto): AffiliateImageSearchResource
  {
    $this->initializeService();

    try {
      $response = $this->client->post($this->apiUrl . '/ai/image-generations/background-removal', [
        'reference_asset_id' => $dto->reference_asset_id,
        'reference_generation' => $dto->reference_generation
          ? [
            'generation_request_id' => $dto->reference_generation->generation_request_id,
            'index' => $dto->reference_generation->index,
          ]
          : null,
        'product_id' => $dto->product_id,
        'project_code' => $dto->project_code,
        'notes' => $dto->notes,
      ]);
    } catch (ConnectionException | \Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }

    $result = $this->handleResponse($response);

    return AffiliateImageSearchResource::make($result->data);
  }

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

  public function imageGeneration(ImageGenerationData $data)
  {
    $this->initializeService();
    try {
      $response = $this->client->post($this->apiUrl . '/ai/image-generations', $data);
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }
    return $this->handleResponse($response);
  }

  public function getImageGeneration(string $generationRequestId)
  {
    $this->initializeService();
    try {
      $response = $this->client->get($this->apiUrl . "/ai/image-generations/{$generationRequestId}");
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }
    return $this->handleResponse($response);
  }

  public function getImageVariations(string $generationRequestId, int $index, ImageVariationsData $data)
  {
    $this->initializeService();
    try {
      $response = $this->client->post($this->apiUrl . "/ai/image-generations/{$generationRequestId}/variations/{$index}", $data);
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }
    return $this->handleResponse($response);
  }

  public function refineImage(RefineImageData $data)
  {
    $this->initializeService();
    try {
      $response = $this->client->post($this->apiUrl . '/ai/image-generations/refine', $data);
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }
    return $this->handleResponse($response);
  }

  public function extendImage(ExtendImageData $data)
  {
    $this->initializeService();
    try {
      $response = $this->client->post($this->apiUrl . '/ai/image-generations/extend', $data);
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }
    return $this->handleResponse($response);
  }

  public function removeObjectFromImage(RemoveObjectFromImageData $data)
  {
    $this->initializeService();
    try {
      $response = $this->client->post($this->apiUrl . '/ai/image-generations/remove-object', $data);
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }
    return $this->handleResponse($response);
  }

  public function replaceBackground(ReplaceBackgroundData $data)
  {
    $this->initializeService();
    try {
      $response = $this->client->post($this->apiUrl . '/ai/image-generations/replace-background', $data);
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }
    return $this->handleResponse($response);
  }

  public function influenceColorByImage(InfluenceColorByImageData $payload)
  {
    $this->initializeService();
    try {
      $response = $this->client->post($this->apiUrl . '/ai/image-generations/influence-color-by-image', $payload);
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }
    return $this->handleResponse($response);
  }

  public function influenceCompositionByImage(InfluenceCompositionByImageData $payload)
  {
    $this->initializeService();
    try {
      $response = $this->client->post($this->apiUrl . '/ai/image-generations/influence-composition-by-image', $payload);
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }
    return $this->handleResponse($response);
  }

  public function generateBackgrounds(GenerateBackgroundsData $payload)
  {
    $this->initializeService();
    try {
      $response = $this->client->post($this->apiUrl . '/ai/image-generations/backgrounds', $payload);
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }
    return $this->handleResponse($response);
  }

  public function getDownloadSizes(string $generationRequestId, int $index)
  {
    $this->initializeService();
    try {
      $response = $this->client->get($this->apiUrl . "/ai/image-generations/{$generationRequestId}/downloads/{$index}/sizes");
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }
    return $this->handleResponse($response);
  }

  public function downloadImageAsync(string $generationRequestId, int $index, DownloadImageAsyncData $data)
  {
    $this->initializeService();
    try {
      $response = $this->client->put($this->apiUrl . "/ai/image-generations/{$generationRequestId}/downloads/{$index}/async", $data);
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }
    return $this->handleResponse($response);
  }

  public function downloadImageByIndex(string $generationRequestId, int $index)
  {
    $this->initializeService();
    try {
      $response = $this->client->get($this->apiUrl . "/ai/image-generations/{$generationRequestId}/downloads/{$index}");
    } catch (ConnectionException | Exception $e) {
      Log::error('Getty Images request error: ' . json_encode($e->getMessage()));
      throw new Forbidden('Getty Images request failed: ' . $e->getMessage());
    }
    return $this->handleResponse($response);
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
