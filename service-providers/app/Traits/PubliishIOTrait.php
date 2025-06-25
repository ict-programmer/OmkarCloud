<?php

namespace App\Traits;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

trait PubliishIOTrait
{
    protected function getPublishUrl(string $cid): string
    {
        return config('image.base_url') . '/ipfs/' . $cid;
    }

    protected function uploadImage(
        string $path,
        bool $isContent = false,
        ?string $filename = null,
        bool $isFullResponse = false,
        int $brandId = 2,
        int $authUserId = 6
    ): array|string|null {
        $token = $this->getBearerToken();

        $contents = $isContent ? $path : file_get_contents($path);

        $response = Http::baseUrl(config('image.base_url'))
            ->timeout(config('image.upload_time_out'))
            ->withToken($token)
            ->attach('upload_image', $contents, $filename)
            ->withQueryParameters([
                'brand_id' => $brandId,
                'auth_user_id' => $authUserId,
            ])
            ->post('api/files/file_add_update');

        if (!$response->successful()) {
            $response->throw();
        }

        $data = $response->json('data')[0] ?? null;

        if (!$data || ($response->json('success') !== 'Y')) {
            return null;
        }

        return $isFullResponse ? $data : $data['cid'] ?? null;
    }

    private function getBearerToken(): string
    {
        $response = Http::baseUrl(config('image.base_url'))
            ->timeout(config('image.upload_time_out'))
            ->asJson()
            ->post('/api/auth/signin', [
                'email' => config('image.default_email'),
                'password' => config('image.default_password'),
            ]);

        if (!$response->successful()) {
            $response->throw();
        }

        $token = $response->json('access_token');

        if (!$token) {
            throw new \Exception('Missing access token from response');
        }

        return $token;
    }
}
