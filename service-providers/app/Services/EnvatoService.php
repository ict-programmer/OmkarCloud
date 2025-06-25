<?php

namespace App\Services;

use App\Data\Request\Envato\ItemSearchData;
use App\Data\Request\Envato\ItemDetailsData;
use App\Data\Request\Envato\UserAccountDetailsData;
use Illuminate\Support\Facades\Http;

class EnvatoService
{
    public function itemSearch(ItemSearchData $data): array
    {
        return $this->callEnvatoAPI(
            endpoint: '/v1/discovery/search/search/item',
            data: [
                'site' => $data->site,
                'term' => $data->term,
            ]
        );
    }

    public function itemDetails(ItemDetailsData $data): array
    {
        return $this->callEnvatoAPI(
            endpoint: '/v1/market/catalog/item',
            data: [
                'id' => $data->item_id,
            ],
        );
    }

    public function userAccountDetails(UserAccountDetailsData $data): array
    {
        return $this->callEnvatoAPI(
            endpoint: '/v1/market/user:' . $data->username . '.json',
        );
    }

    private function callEnvatoAPI(
        string $endpoint,
        string $method = 'GET',
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
            'GET' => $httpClient->get($url, $data),
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