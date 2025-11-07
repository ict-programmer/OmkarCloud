<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

/**
 * Trait ImageTraits
 *
 * Provides methods for uploading files to Publiish and generating URLs.
 */
trait PubliishIOTrait
{
    protected function uploadFile(string $filePath, int $brandId = 1, int $authUserId = 1): string|array|null
    {
        $file = file_get_contents($filePath);

        $token = $this->getPublishToken();

        try {
            $uploadResponse = Http::withToken($token)->retry(3)
                ->timeout(config('image.upload_time_out'))
                ->attach('upload_file', $file, str($filePath)->afterLast('/'))
                ->withQueryParameters([
                    'brand_id' => $brandId,
                    'auth_user_id' => $authUserId,
                ])
                ->post(config('image.base_url') . '/api/files/file_add_update');

            $uploadResponse->throw();

            $data = $uploadResponse->json();
            if (($data['success'] ?? 'N') === 'Y' && isset($data['data'][0]['cid'])) {
                return $data['data'][0]['cid'];
            }
        } catch (RequestException $e) {
            throw new Exception('File upload failed: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Generates the URL for accessing a file on Publiish.
     *
     * @param  string  $cid  The CID of the file.
     * @return string The URL to access the file.
     */
    protected function getPublishUrl(string $cid): string
    {
        return config('image.base_url') . '/ipfs/' . $cid;
    }

    protected function getPublishToken(): string
    {
        $response = Http::baseUrl(config('image.base_url'))
            ->post('api/auth/signin', [
                'email' => config('image.auth.email'),
                'password' => config('image.auth.password'),
            ]);

        if ($response->successful()) {
            return $response->json('access_token');
        }
        $response->throw();

        return '';
    }
}
