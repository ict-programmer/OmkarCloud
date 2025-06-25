<?php

namespace App\Services;

use App\Data\Request\Envato\ItemSearchData;
use Illuminate\Support\Facades\Http;

class EnvatoService
{
    public function itemSearch(ItemSearchData $data): array
    {
        return $this->callEnvatoAPI(
            endpoint: '/v1/discovery/search/search/item',
            params: [
                'site' => $data->site,
                'term' => $data->term,
            ]
        );
    }

    private function callEnvatoAPI(
        string $endpoint,
        string $method = 'GET',
        array $params = [],
        array $data = []
    ): array|null {
        $url = config('envato.base_url') . $endpoint;

        $headers = [
            'Accept' => 'application/json',
        ];

        if ($method === 'POST') {
            $headers['Content-Type'] = 'application/json';
        }

        $httpClient = Http::withHeaders($headers)->withToken(config('envato.api_token'))->timeout(30);

        $response = match ($method) {
            'GET' => $httpClient->get($url, $params),
            'POST' => $httpClient->post($url, $data),
        };

        if ($response->failed()) {
            $statusCode = $response->status();
            
            if ($response->json('errors')) {
                abort(response()->json([
                    'error' => 'Envato API Error',
                    'details' => $response->json('errors')
                ], $statusCode));
            }

            $errorMessage = $response->json() ?? 'Request failed';
            abort(response()->json([
                'code' => "HTTP {$statusCode}",
                'errors' => $errorMessage
            ], $statusCode));
        }

        return $response->json();
    }
} 