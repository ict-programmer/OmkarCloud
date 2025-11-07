<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait PremierProTrait
{
    protected function getApiKey(): string
    {
        return config('services.premierpro.api_key');
    }

    protected function getAccessToken(): string
    {
        return Cache::get('premierpro.access_token');
    }

    protected function setAccessToken(string $accessToken, int $expiresIn): void
    {
        Cache::put('premierpro.access_token', $accessToken, $expiresIn);
    }
}
