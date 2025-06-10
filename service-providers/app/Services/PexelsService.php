<?php

namespace App\Services;

use App\Data\Pexels\GetCollectionData;
use App\Data\Pexels\GetCuratedPhotosData;
use App\Data\Pexels\GetFeaturedCollectionsData;
use App\Data\Pexels\GetPhotoData;
use App\Data\Pexels\GetPopularVideosData;
use App\Data\Pexels\GetVideoData;
use App\Data\Pexels\SearchPhotosData;
use App\Data\Pexels\SearchVideosData;
use App\Http\Exceptions\BadRequest;
use App\Http\Exceptions\NotFound;
use Devscast\Pexels\Client;
use Devscast\Pexels\Data\CollectionMedia;
use Devscast\Pexels\Data\Collections;
use Devscast\Pexels\Data\Photo;
use Devscast\Pexels\Data\Photos;
use Devscast\Pexels\Data\Video;
use Devscast\Pexels\Data\Videos;
use Devscast\Pexels\Exception\NetworkException;
use Devscast\Pexels\Parameter\CollectionParameters;
use Devscast\Pexels\Parameter\PaginationParameters;
use Devscast\Pexels\Parameter\PopularVideosParameters;
use Devscast\Pexels\Parameter\SearchParameters;

class PexelsService
{
  protected $client;

  public function __construct()
  {
    $apiKey = env('PEXELS_API_KEY');

    if (empty($apiKey)) {
      throw new NotFound('PEXELS_API_KEY is not set');
    }

    $this->client = new Client($apiKey);
  }

  public function searchPhotos(SearchPhotosData $data): Photos
  {
    $searchParams = new SearchParameters(
      orientation: $data->orientation ?? null,
      size: $data->size ?? null,
      page: $data->page ?? 1,
      per_page: $data->per_page ?? 10,
      color: $data->color ?? null,
      locale: $data->locale ?? null,
    );

    try {
      $response = $this->client->searchPhotos($data->query, $searchParams);
    } catch (NetworkException $e) {
      throw new BadRequest($e->getMessage());
    }

    return $response;
  }

  public function getCuratedPhotos(GetCuratedPhotosData $data): Photos
  {
    $searchParams = new PaginationParameters(
      page: $data->page ?? 1,
      per_page: $data->per_page ?? 10,
    );

    try {
      $response = $this->client->curatedPhotos($searchParams);
    } catch (NetworkException $e) {
      throw new BadRequest($e->getMessage());
    }

    return $response;
  }

  public function getPhoto(GetPhotoData $data): Photo
  {
    $response = $this->client->photo($data->id);

    return $response;
  }

  public function searchVideos(SearchVideosData $data): Videos
  {
    $searchParams = new SearchParameters(
      orientation: $data->orientation ?? null,
      size: $data->size ?? null,
      page: $data->page ?? 1,
      per_page: $data->per_page ?? 10,
      color: $data->color ?? null,
      locale: $data->locale ?? null,
    );

    try {
      $response = $this->client->searchVideos($data->query, $searchParams);
    } catch (NetworkException $e) {
      throw new BadRequest($e->getMessage());
    }

    return $response;
  }

  public function getPopularVideos(GetPopularVideosData $data): Videos
  {
    $searchParams = new PopularVideosParameters(
      min_width: $data->min_width ?? null,
      min_height: $data->min_height ?? null,
      min_duration: $data->min_duration ?? null,
      max_duration: $data->max_duration ?? null,
      page: $data->page ?? 1,
      per_page: $data->per_page ?? 10,
    );

    try {
      $response = $this->client->popularVideos($searchParams);
    } catch (NetworkException $e) {
      throw new BadRequest($e->getMessage());
    }

    return $response;
  }

  public function getVideo(GetVideoData $data): Video
  {
    try {
      $response = $this->client->video($data->id);
    } catch (NetworkException $e) {
      throw new BadRequest($e->getMessage());
    }

    return $response;
  }

  public function getFeaturedCollections(GetFeaturedCollectionsData $data): Collections
  {
    $searchParams = new PaginationParameters(
      page: $data->page ?? 1,
      per_page: $data->per_page ?? 10,
    );

    try {
      $response = $this->client->featuredCollections($searchParams);
    } catch (NetworkException $e) {
      throw new BadRequest($e->getMessage());
    }

    return $response;
  }

  public function getCollection(GetCollectionData $data): CollectionMedia
  {
    $searchParams = new CollectionParameters(
      page: $data->page ?? 1,
      per_page: $data->per_page ?? 10,
      type: $data->type ?? null,
      sort: $data->sort ?? null,
    );

    try {
      $response = $this->client->collection($data->id, $searchParams);
    } catch (NetworkException $e) {
      throw new BadRequest($e->getMessage());
    }

    return $response;
  }
}
