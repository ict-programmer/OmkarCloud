<?php

namespace App\Services;

use App\Data\Request\Shutterstock\AddToCollectionData;
use App\Data\Request\Shutterstock\CreateCollectionData;
use App\Data\Request\Shutterstock\DownloadImageData;
use App\Data\Request\Shutterstock\GetImageData;
use App\Data\Request\Shutterstock\LicenseImageData;
use App\Data\Request\Shutterstock\SearchImagesData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ShutterstockService
{
    public function searchImages(SearchImagesData $data): array
    {
        $response = $this->callShutterstockAPI(
            endpoint: config('shutterstock.search_images_endpoint'),
            params: [
                'query' => $data->query,
                'orientation' => $data->orientation,
                'sort' => 'popular',
            ]
        );

        return $response->json();
    }

    public function getImage(GetImageData $data): array
    {
        $endpoint = config('shutterstock.get_image_endpoint') . '/' . $data->image_id;
        
        $response = $this->callShutterstockAPI(
            endpoint: $endpoint,
        );

        return $response->json();
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

        return $response->json();
    }

    public function downloadImage(DownloadImageData $data): array
    {
        $endpoint = config('shutterstock.download_image_endpoint') . '/' . $data->license_id . '/downloads';
        
        $response = $this->callShutterstockAPI(
            endpoint: $endpoint,
            method: 'POST',
        );

        return $response->json();
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

        return $response->json();
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

    private function callShutterstockAPI(
        string $endpoint,
        string $method = 'GET',
        array $params = [],
        array $data = []
    ): Response {
        $url = config('shutterstock.base_url') . $endpoint;

        $headers = [
            'Authorization' => 'Bearer ' . config('shutterstock.api_token'),
            'Accept' => 'application/json',
        ];

        if ($method === 'POST') {
            $headers['Content-Type'] = 'application/json';
        }

        $httpClient = Http::withHeaders($headers)->timeout(30);

        $response = match ($method) {
            'GET' => $httpClient->get($url, $params),
            'POST' => $httpClient->post($url, $data),
        };


        $errors = $response->json('errors');
        if ($response->failed() || ($errors && count($errors) > 0)) {
            $statusCode = 403;
            abort(response()->json([
                'message' => $response->json('message') ?? 'An error occurred',
                'errors' => $response->json('errors') ?? ($response->json('error') ?? 'An error occurred'),
                'data' => $response->json('data'),
            ], $statusCode));
        }

        return $response;
    }
} 