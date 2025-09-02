<?php

namespace App\Services;

use App\Data\Request\BillionMail\SendEmailData;
use App\Data\Request\BillionMail\SendBatchEmailData;
use Illuminate\Support\Facades\Http;

class BillionMailService
{
    protected string $apiUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.billionmail.api_url');
        $this->apiKey = config('services.billionmail.api_key');
    }

    public function sendEmail(SendEmailData $data): array
    {
        $http = Http::withHeaders(['X-API-Key' => $this->apiKey]);

        if (config('app.env') === 'local') {
            $http = $http->withoutVerifying();
        }

        $response = $http->post("{$this->apiUrl}/send", $data->toArray());

        return $response->json();
    }

    public function sendBatchEmail(SendBatchEmailData $data): array
    {
        $http = Http::withHeaders(['X-API-Key' => $this->apiKey]);

        if (config('app.env') === 'local') {
            $http = $http->withoutVerifying();
        }

        $response = $http->post("{$this->apiUrl}/batch_send", $data->toArray());

        return $response->json();
    }
}
