<?php

namespace App\Services;

use App\Data\Request\Shutterstock\AddToCollectionData;
use App\Data\Request\Shutterstock\CreateCollectionData;
use App\Data\Request\Shutterstock\DownloadImageData;
use App\Data\Request\Shutterstock\DownloadVideoData;
use App\Data\Request\Shutterstock\GetImageData;
use App\Data\Request\Shutterstock\GetVideoData;
use App\Data\Request\Shutterstock\LicenseImageData;
use App\Data\Request\Shutterstock\LicenseVideoData;
use App\Data\Request\Shutterstock\SearchImagesData;
use App\Data\Request\Shutterstock\SearchVideosData;
use App\Data\Request\Shutterstock\SearchAudioData;
use App\Data\Request\Shutterstock\GetAudioData;
use App\Data\Request\Shutterstock\LicenseAudioData;
use App\Data\Request\Shutterstock\DownloadAudioData;
use Illuminate\Support\Facades\Http;

class ShutterstockService
{
    public function searchImages(SearchImagesData $data): array
    {
        return $this->callShutterstockAPI(
            endpoint: config('shutterstock.search_images_endpoint'),
            params: [
                'query' => $data->query,
                'orientation' => $data->orientation,
                'sort' => 'popular',
            ]
        );
    }

    public function getImage(GetImageData $data): array
    {
        $endpoint = config('shutterstock.get_image_endpoint') . '/' . $data->image_id;

        return $this->callShutterstockAPI(
            endpoint: $endpoint,
        );
    }

    public function licenseImage(LicenseImageData $data): array
    {
        $requestBody = [
            'images' => [
                [
                    'image_id' => $data->image_id,
                    'size' => 'huge',
                    'format' => 'jpg',
                ]
            ]
        ];

        return $this->callShutterstockAPI(
            endpoint: config('shutterstock.license_image_endpoint'),
            method: 'POST',
            data: $requestBody
        );
    }

    public function downloadImage(DownloadImageData $data): array
    {
        $endpoint = config('shutterstock.download_image_endpoint') . '/' . $data->license_id . '/downloads';

        $body = [
            'size' => 'huge',
        ];

        return $this->callShutterstockAPI(
            endpoint: $endpoint,
            method: 'POST',
            data: $body,
        );
    }

    public function createCollection(CreateCollectionData $data): array
    {
        $requestBody = [
            'name' => $data->name
        ];

        return $this->callShutterstockAPI(
            endpoint: config('shutterstock.create_collection_endpoint'),
            method: 'POST',
            data: $requestBody
        );
    }

    public function addToCollection(AddToCollectionData $data): void
    {
        $endpoint = config('shutterstock.add_to_collection_endpoint') . '/' . $data->collection_id . '/items';
        
        $requestBody = [
            'items' => $data->items
        ];
        
        $this->callShutterstockAPI(
            endpoint: $endpoint,
            method: 'POST',
            data: $requestBody
        );
    }

    public function searchVideos(SearchVideosData $data): array
    {
        return $this->callShutterstockAPI(
            endpoint: config('shutterstock.search_videos_endpoint'),
            params: [
                'query' => $data->query,
                'orientation' => $data->orientation,
                'sort' => 'popular',
            ]
        );
    }

    public function getVideo(GetVideoData $data): array
    {
        $endpoint = config('shutterstock.get_video_endpoint') . '/' . $data->video_id;

        return $this->callShutterstockAPI(
            endpoint: $endpoint,
        );
    }

    public function licenseVideo(LicenseVideoData $data): array
    {
        $requestBody = [
            'videos' => $data->videos
        ];

        if ($data->search_id) {
            $requestBody['search_id'] = $data->search_id;
        }
        
        return $this->callShutterstockAPI(
            endpoint: config('shutterstock.license_video_endpoint'),
            method: 'POST',
            data: $requestBody
        );
    }

    public function downloadVideo(DownloadVideoData $data): array
    {
        $endpoint = config('shutterstock.download_video_endpoint') . '/' . $data->license_id . '/downloads';

        return $this->callShutterstockAPI(
            endpoint: $endpoint,
            method: 'POST',
        );
    }

    // Audio methods
    public function searchAudio(SearchAudioData $data): array
    {
        $params = [
            'query' => $data->query,
        ];

        // Add sort parameter if provided
        if ($data->sort) {
            $params['sort'] = $data->sort;
        }

        return $this->callShutterstockAPI(
            endpoint: config('shutterstock.search_audio_endpoint'),
            params: $params
        );
    }

    public function getAudio(GetAudioData $data): array
    {
        $endpoint = config('shutterstock.get_audio_endpoint') . '/' . $data->audio_id;

        return $this->callShutterstockAPI(
            endpoint: $endpoint,
        );
    }

    public function licenseAudio(LicenseAudioData $data): array
    {
        $requestBody = [
            'audio' => $data->audio_tracks
        ];
        
        // Add search_id if provided
        if ($data->search_id) {
            $requestBody['search_id'] = $data->search_id;
        }
        
        return $this->callShutterstockAPI(
            endpoint: config('shutterstock.license_audio_endpoint'),
            method: 'POST',
            data: $requestBody
        );
    }

    public function downloadAudio(DownloadAudioData $data): array
    {
        $endpoint = config('shutterstock.download_audio_endpoint') . '/' . $data->license_id . '/downloads';

        return $this->callShutterstockAPI(
            endpoint: $endpoint,
            method: 'POST',
            data: []
        );
    }

    public function listUserSubscriptions(): array
    {
        return $this->callShutterstockAPI(
            endpoint: config('shutterstock.list_user_subscriptions_endpoint'),
        );
    }

    private function callShutterstockAPI(
        string $endpoint,
        string $method = 'GET',
        array $params = [],
        array $data = []
    ): array|null {
        $url = config('shutterstock.base_url') . $endpoint;
        
        $headers = [
            'Authorization' => 'Bearer ' . config('shutterstock.api_token'),
            'Accept' => 'application/json',
        ];

        // Add Content-Type header for POST requests
        if ($method === 'POST') {
            $headers['Content-Type'] = 'application/json';
        }

        $httpClient = Http::withHeaders($headers)->timeout(30);

        $response = match ($method) {
            'GET' => $httpClient->get($url, $params),
            'POST' => $httpClient->post($url, $data),
        };
        
        if ($response->failed()) {
            $statusCode = $response->status();
            $errorMessage = $response->json('message') ?? 'Request failed';
            
            // Check for API-level errors (Shutterstock specific)
            if ($response->json('errors')) {
                abort(response()->json([
                    'error' => 'Shutterstock API Error',
                    'details' => $response->json('errors')
                ], $statusCode));
            }
            
            // HTTP-level errors
            abort(response()->json([
                'error' => "HTTP {$statusCode}: {$errorMessage}"
            ], $statusCode));
        }

        return $response->json();
    }
} 