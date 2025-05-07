<?php

namespace App\Traits;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

trait PubliishIOTrait
{
    /**
     * Uploads a file to the Publiish API and returns the file CID.
     *
     * @param string $path The path to the file to be uploaded.
     * @param bool $isContent
     * @param string|null $filename
     * @param bool $isFullResponse
     * @param int $brandId The ID of the brand. Defaults to 1.
     * @param int $authUserId The ID of the authenticated user. Defaults to 1.
     * @return string|array|null The CID of the uploaded file or the full response data.
     *
     * @throws ConnectionException If there is a connection issue with the Publiish API.
     * @throws \Illuminate\Http\Client\RequestException
     */
    protected function uploadImage(string $path, bool $isContent = false, ?string $filename = null, bool $isFullResponse = false, int $brandId = 1, int $authUserId = 1): array|string|null
    {
        $contents = $isContent ? $path : file_get_contents($path);

        $publishRes = Http::baseUrl(config('image.base_url'))
            ->timeout(config('image.upload_time_out'))
            ->attach('upload_image', $contents, $filename)
            ->withQueryParameters([
                'brand_id' => $brandId,
                'auth_user_id' => $authUserId,
            ])
            ->post('api/files/file_add_update');

        if ($publishRes->successful()) {
            $publishResCollect = $publishRes->collect();
            if ($publishResCollect['success'] === 'Y' && isset($publishResCollect['data'][0]['cid'])) {
                if ($isFullResponse) {
                    return $publishResCollect['data'][0];
                }

                return $publishResCollect['data'][0]['cid'];
            }
        }

        // Throw an exception if the response was not successful
        $publishRes->throw();
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
}
