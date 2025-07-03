<?php

namespace App\Services;

use App\Data\Request\Envato\ItemSearchData;
use App\Data\Request\Envato\ItemDetailsData;
use App\Data\Request\Envato\UserAccountDetailsData;
use App\Data\Request\Envato\DownloadPurchasedItemData;
use App\Data\Request\Envato\VerifyPurchaseCodeData;
use App\Data\Request\Envato\PopularItemsData;
use App\Data\Request\Envato\CategoriesBySiteData;
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

    public function userPurchases(): array
    {
        return $this->callEnvatoAPI(
            endpoint: '/v1/market/private/user/account.json',
        );
    }

    public function downloadPurchasedItem(DownloadPurchasedItemData $data): array
    {
        return $this->callEnvatoAPI(
            endpoint: '/v1/market/private/user/download-purchase:' . $data->item_id . '.json',
        );
    }

    public function verifyPurchaseCode(VerifyPurchaseCodeData $data): array
    {
        return $this->callEnvatoAPI(
            endpoint: '/v1/market/private/user/verify-purchase:' . $data->purchase_code . '.json',
        );
    }

    public function userIdentity(): array
    {
        return $this->callEnvatoAPI(
            endpoint: '/v1/market/private/user/username.json',
        );
    }

    public function popularItems(PopularItemsData $data): array
    {
        return $this->callEnvatoAPI(
            endpoint: '/v1/market/popular:' . $data->site . '.json',
        );
    }

    public function categoriesBySite(CategoriesBySiteData $data): array
    {
        return $this->callEnvatoAPI(
            endpoint: '/v1/market/categories:' . $data->site . '.json',
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