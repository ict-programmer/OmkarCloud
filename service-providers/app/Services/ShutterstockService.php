<?php

namespace App\Services;

use App\Data\Request\Shutterstock\SearchImagesData;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ShutterstockService
{
    public function searchImages(SearchImagesData $data): array
    {
        $response = $this->callShutterstockAPI([
            'query' => $data->query,
            'orientation' => $data->orientation,
            'sort' => 'popular',
        ]);

        return $response->json();
    }

    private function callShutterstockAPI(array $params = []): Response
    {
        $url = config('shutterstock.base_url') . config('shutterstock.search_images_endpoint');
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('shutterstock.api_token'),
            'Accept' => 'application/json',
        ])
            ->timeout(30)
            ->get($url, $params);

        if ($response->failed()) {
            throw new ConnectionException('Failed to search images: ' . $response->body());
        }

        return $response;
    }
} 