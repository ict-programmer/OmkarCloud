<?php

namespace App\Services;

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
            method: 'GET',
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
            method: 'GET'
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

        // Add Content-Type header for POST requests
        if ($method === 'POST') {
            $headers['Content-Type'] = 'application/json';
        }

        $httpClient = Http::withHeaders($headers)->timeout(30);

        $response = match ($method) {
            'GET' => $httpClient->get($url, $params),
            'POST' => $httpClient->post($url, $data),
        };
        
        if ($response->failed() || ($response->json('errors') && count($response->json('errors')) > 0)) {
            $statusCode = 403;
            abort(response()->json([
                'errors' => $response->json('errors'),
                'data' => $response->json('data'),
            ], $statusCode));
        }

        return $response;
    }
} 