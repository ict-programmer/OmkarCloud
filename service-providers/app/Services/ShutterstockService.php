<?php

namespace App\Services;

use App\Data\Request\Shutterstock\AddToCollectionData;
use App\Data\Request\Shutterstock\AddToVideoCollectionData;
use App\Data\Request\Shutterstock\CreateCollectionData;
use App\Data\Request\Shutterstock\CreateVideoCollectionData;
use App\Data\Request\Shutterstock\DownloadImageData;
use App\Data\Request\Shutterstock\DownloadVideoData;
use App\Data\Request\Shutterstock\GetImageData;
use App\Data\Request\Shutterstock\GetVideoData;
use App\Data\Request\Shutterstock\LicenseImageData;
use App\Data\Request\Shutterstock\LicenseVideoData;
use App\Data\Request\Shutterstock\SearchImagesData;
use App\Data\Request\Shutterstock\SearchVideosData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ShutterstockService
{
    // Image methods
    public function searchImages(SearchImagesData $data): array
    {
        $response = $this->callShutterstockAPI(
            endpoint: config('shutterstock.search_images_endpoint'),
            method: 'GET',
            params: [
                'query' => $data->query,
                'orientation' => $data->orientation,
                'sort' => 'popular',
            ]
        );

        return $response;
    }

    public function getImage(GetImageData $data): array
    {
        $endpoint = config('shutterstock.get_image_endpoint') . '/' . $data->image_id;
        
        $response = $this->callShutterstockAPI(
            endpoint: $endpoint,
            method: 'GET'
        );

        return $response;
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
        
        $response = $this->callShutterstockAPI(
            endpoint: config('shutterstock.license_image_endpoint'),
            method: 'POST',
            data: $requestBody
        );

        return $response;
    }

    public function downloadImage(DownloadImageData $data): array
    {
        $endpoint = config('shutterstock.download_image_endpoint') . '/' . $data->license_id . '/downloads';
        
        $response = $this->callShutterstockAPI(
            endpoint: $endpoint,
            method: 'POST',
            data: []
        );

        return $response;
    }

    public function createCollection(CreateCollectionData $data): array
    {
        $requestBody = [
            'name' => $data->name
        ];
        
        $response = $this->callShutterstockAPI(
            endpoint: config('shutterstock.create_collection_endpoint'),
            method: 'POST',
            data: $requestBody
        );

        return $response;
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

    // Video methods
    public function searchVideos(SearchVideosData $data): array
    {
        $response = $this->callShutterstockAPI(
            endpoint: config('shutterstock.search_videos_endpoint'),
            method: 'GET',
            params: [
                'query' => $data->query,
                'orientation' => $data->orientation,
                'sort' => 'popular',
            ]
        );

        return $response;
    }

    public function getVideo(GetVideoData $data): array
    {
        $endpoint = config('shutterstock.get_video_endpoint') . '/' . $data->video_id;
        
        $response = $this->callShutterstockAPI(
            endpoint: $endpoint,
            method: 'GET'
        );

        return $response;
    }

    public function licenseVideo(LicenseVideoData $data): array
    {
        $response = $this->callShutterstockAPI(
            endpoint: config('shutterstock.license_video_endpoint'),
            method: 'POST',
            data: $data->videos
        );

        return $response;
    }

    public function downloadVideo(DownloadVideoData $data): array
    {
        $endpoint = config('shutterstock.download_video_endpoint') . '/' . $data->license_id . '/downloads';
        
        $response = $this->callShutterstockAPI(
            endpoint: $endpoint,
            method: 'POST',
            data: []
        );

        return $response;
    }

    public function createVideoCollection(CreateVideoCollectionData $data): array
    {
        $requestBody = [
            'name' => $data->name
        ];
        
        $response = $this->callShutterstockAPI(
            endpoint: config('shutterstock.create_video_collection_endpoint'),
            method: 'POST',
            data: $requestBody
        );

        return $response;
    }

    public function addToVideoCollection(AddToVideoCollectionData $data): void
    {
        $endpoint = config('shutterstock.add_to_video_collection_endpoint') . '/' . $data->collection_id . '/items';
        
        $requestBody = [
            'items' => $data->items
        ];
        
        $this->callShutterstockAPI(
            endpoint: $endpoint,
            method: 'POST',
            data: $requestBody
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