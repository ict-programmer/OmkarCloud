<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Exceptions\Forbidden;


trait CanvaTrait
{
    const TOKEN_EXPIRATION = 3600;
    const CACHE_KEY_ACCESS_TOKEN = 'canva_access_token';
    const CACHE_KEY_REFRESH_TOKEN = 'canva_refresh_token';
    const CACHE_KEY_TOKEN_EXPIRES_IN = 'canva_token_expires_in';
    const CACHE_KEY_STATE = 'canva_state_';
    const AUTH_REDIRECT_URI = 'api/canva/oauth/callback';

    public function getAccessToken(): string
    {
        $accessToken = Cache::get(self::CACHE_KEY_ACCESS_TOKEN);
        if (!$accessToken) {
            throw new Forbidden('Access token not found');
        }
        return $accessToken;
    }

    public function getRefreshToken(): string
    {
        $refreshToken = Cache::get(self::CACHE_KEY_REFRESH_TOKEN);
        if (!$refreshToken) {
            throw new Forbidden('Refresh token not found');
        }
        return $refreshToken;
    }

    public function getTokenExpiresIn(): int
    {
        $tokenExpiresIn = Cache::get(self::CACHE_KEY_TOKEN_EXPIRES_IN);
        if (!$tokenExpiresIn) {
            throw new Forbidden('Token expires in not found');
        }
        return $tokenExpiresIn;
    }

    public function setAccessToken(string $accessToken): void
    {
        Cache::put(self::CACHE_KEY_ACCESS_TOKEN, $accessToken, self::TOKEN_EXPIRATION);
    }

    public function setRefreshToken(string $refreshToken): void
    {
        Cache::put(self::CACHE_KEY_REFRESH_TOKEN, $refreshToken, self::TOKEN_EXPIRATION);
    }

    public function setTokenExpiresIn(int $tokenExpiresIn): void
    {
        Cache::put(self::CACHE_KEY_TOKEN_EXPIRES_IN, $tokenExpiresIn, self::TOKEN_EXPIRATION);
    }

    public function setState(string $state, string $codeVerifier): void
    {
        Cache::put(self::CACHE_KEY_STATE . $state,[
            'code_verifier' => $codeVerifier,
        ], self::TOKEN_EXPIRATION);
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY_ACCESS_TOKEN);
        Cache::forget(self::CACHE_KEY_REFRESH_TOKEN);
        Cache::forget(self::CACHE_KEY_TOKEN_EXPIRES_IN);
        Cache::forget(self::CACHE_KEY_STATE);
    }

    public function getAuthorizationUrl(string $state): string
    {
        $clientId = config('services.canva.api_key');
        $scopes = implode(' ', config('services.canva.scopes', []));
        $baseUrl = 'https://www.canva.com/api/oauth/authorize?';

        $url = $baseUrl . http_build_query([
            'response_type' => 'code',
            'code_challenge_method' => 'S256',
            'code_challenge' => $this->getCodeChallenge($state),
            'scope' => $scopes,
            'state' => $state,
            'client_id' => $clientId,
            'redirect_uri' => url(self::AUTH_REDIRECT_URI),
        ]);

        return $url;
    }

    public function getCodeChallenge(string $state): string
    {
        $codeVerifier = rtrim(strtr(base64_encode(random_bytes(96)), '+/', '-_'), '=');
        $hash = hash('sha256', $codeVerifier, true);
        $codeChallenge = rtrim(strtr(base64_encode($hash), '+/', '-_'), '=');
        $this->setState($state, $codeVerifier);

        return $codeChallenge;
    }

    public function getCodeVerifier(string $state): string
    {
        $cachedState = Cache::get('canva_state_' . $state);
        if (!$cachedState || !isset($cachedState['code_verifier'])) {
            throw new Forbidden('Code verifier not found: ' . $state);
        }

        return $cachedState['code_verifier'];
    }

    public function handleOAuthCallback(string $code, string $state): void
    {
        $codeVerifier = $this->getCodeVerifier($state);
        $clientId = config('services.canva.api_key');
        $clientSecret = config('services.canva.api_secret');
        $redirectUri = url(self::AUTH_REDIRECT_URI);
        $credentials = base64_encode($clientId . ':' . $clientSecret);

        $headers = [
            'Authorization' => 'Basic ' . $credentials,
        ];

        $res = Http::asForm()
            ->withHeaders($headers)
            ->post('https://api.canva.com/rest/v1/oauth/token', [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'code_verifier' => $codeVerifier,
                'redirect_uri' => $redirectUri,
            ]);

        if ($res->failed()) {
            Log::error('Canva request error: ' . json_encode($res->json()));
            throw new Forbidden('Canva request failed: ' . $res->json()['error']);
        }

        $json = $res->json();

        if (empty($json['access_token']) || empty($json['refresh_token'])) {
            throw new Forbidden('Canva request failed: No access token returned');
        }

        $accessToken = $json['access_token'];
        $refreshToken = $json['refresh_token'];
        $expiresIn = $json['expires_in'];

        $this->setAccessToken($accessToken);
        $this->setRefreshToken($refreshToken);
        $this->setTokenExpiresIn($expiresIn);
    }

    public function refreshAccessToken(): bool
    {
        $refreshToken = $this->getRefreshToken();
        $clientId = config('services.canva.api_key');
        $clientSecret = config('services.canva.api_secret');

        $credentials = base64_encode($clientId . ':' . $clientSecret);

        $headers = [
            'Authorization' => 'Basic ' . $credentials,
        ];

        $res = Http::asForm()
            ->withHeaders($headers)
            ->post('https://api.canva.com/rest/v1/oauth/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
            ]);

        if ($res->failed()) {
            Log::error('Canva request error: ' . json_encode($res->json()));
            throw new Forbidden('Canva request failed: ' . $res->json()['error']);
        }

        $json = $res->json();

        if (empty($json['access_token']) || empty($json['refresh_token'])) {
            throw new Forbidden('Canva request failed: No access token returned');
        }

        $accessToken = $json['access_token'];
        $refreshToken = $json['refresh_token'];
        $expiresIn = $json['expires_in'];

        $this->setAccessToken($accessToken);
        $this->setRefreshToken($refreshToken);
        $this->setTokenExpiresIn($expiresIn);

        return true;
    }
}
