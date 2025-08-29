<?php

namespace App\Services;

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

    public function sendEmail(array $data): array
    {
        return $this->callApi('send', [
            'recipient' => $data['recipient'],
            'addresser' => $data['addresser'],
            'attribs'   => $data['attribs'],
        ]);
    }

    public function sendBatchEmail(array $data): array
    {
        return $this->callApi('batch_send', [
            'recipients' => $data['recipients'],
            'addresser'  => $data['addresser'],
            'attribs'    => $data['attribs'],
        ]);
    }

    protected function callApi(string $endpoint, array $payload): array
    {
        try {
            $http = Http::withHeaders(['X-API-Key' => $this->apiKey]);

            if (config('app.env') === 'local') {
                $http = $http->withoutVerifying();
            }

            $response = $http->post("{$this->apiUrl}/{$endpoint}", $payload);

            return [
                'success' => $response->successful(),
                'status'  => $response->status(),
                'data'    => $response->json(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'status'  => 500,
                'message' => $e->getMessage(),
            ];
        }
    }
}
