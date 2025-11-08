<?php

namespace App\Traits;

use App\Http\Exceptions\Forbidden;
use App\Models\TempAuthToken;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait CanvaTrait
{
    const TOKEN_EXPIRATION = 3600;
    const CACHE_KEY_STATE = 'canva_state_';
    const AUTH_REDIRECT_URI = 'api/canva/oauth/callback';
    const ACCESS_TOKEN_NAME = 'canva_access_token';
    const REFRESH_TOKEN_NAME = 'canva_refresh_token';

    /**
     * Retrieves a token value from the database.
     *
     * @param string $name The name of the token (e.g., access or refresh).
     * @param string $column The column to retrieve ('token' or 'expires_at').
     * @return mixed
     * @throws Forbidden If the token is not found.
     */
    private function getTokenValue(string $name, string $column): mixed
    {
        $tempAuthToken = TempAuthToken::where('name', $name)->first();
        if (!$tempAuthToken || !isset($tempAuthToken->$column)) {
            throw new Forbidden(ucfirst($name) . ' not found');
        }

        return $tempAuthToken->$column;
    }

    /**
     * Updates or creates an authentication token in the database.
     *
     * @param string $name The name of the token.
     * @param string $token The token value.
     * @param int $expiresIn The expiration time.
     * @return void
     */
    private function updateAuthToken(string $name, string $token, int $expiresIn): void
    {
        TempAuthToken::updateOrCreate(
            ['name' => $name],
            ['token' => $token, 'expires_at' => $expiresIn]
        );
        $t = TempAuthToken::where('name', $name)->first();
        Log::info('Updated token: ' . $t->token);
    }

    /**
     * Handles the HTTP request to the Canva token endpoint.
     *
     * @param array $payload The request body payload.
     * @return array The JSON response from the API.
     * @throws Forbidden
     */
    private function handleTokenRequest(array $payload): array
    {
        $clientId = config('services.canva.api_key');
        $clientSecret = config('services.canva.api_secret');
        $credentials = base64_encode($clientId . ':' . $clientSecret);

        $headers = [
            'Authorization' => 'Basic ' . $credentials,
        ];

        $res = Http::asForm()
            ->withHeaders($headers)
            ->post('https://api.canva.com/rest/v1/oauth/token', $payload);

        if ($res->failed()) {
            Log::error('Canva request error: ' . json_encode($res->json()));
            throw new Forbidden('Canva request failed: ' . ($res->json()['error'] ?? 'Unknown error'));
        }

        $json = $res->json();

        if (empty($json['access_token']) || empty($json['refresh_token'])) {
            throw new Forbidden('Canva request failed: No access or refresh token returned');
        }

        // Save the tokens using the new helper method
        $this->updateAuthToken(self::ACCESS_TOKEN_NAME, $json['access_token'], $json['expires_in']);
        $this->updateAuthToken(self::REFRESH_TOKEN_NAME, $json['refresh_token'], $json['expires_in']);

        return $json;
    }

    public function getAccessToken(): string
    {
        return $this->getTokenValue(self::ACCESS_TOKEN_NAME, 'token');
    }

    public function getRefreshToken(): string
    {
        return $this->getTokenValue(self::REFRESH_TOKEN_NAME, 'token');
    }

    public function getTokenExpiresIn(): int
    {
        return $this->getTokenValue(self::ACCESS_TOKEN_NAME, 'expires_at');
    }

    public function clearCache(): void
    {
        TempAuthToken::where('name', self::ACCESS_TOKEN_NAME)->delete();
        TempAuthToken::where('name', self::REFRESH_TOKEN_NAME)->delete();
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

    public function setState(string $state, string $codeVerifier): void
    {
        Cache::put(self::CACHE_KEY_STATE . $state, [
            'code_verifier' => $codeVerifier,
        ], self::TOKEN_EXPIRATION);
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
        $redirectUri = url(self::AUTH_REDIRECT_URI);

        $payload = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'code_verifier' => $codeVerifier,
            'redirect_uri' => $redirectUri,
        ];

        $this->handleTokenRequest($payload);
    }

    public function refreshAccessToken(): bool
    {
        $refreshToken = $this->getRefreshToken();

        $payload = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ];

        $res = $this->handleTokenRequest($payload);

        if ($res['access_token'] && $res['refresh_token']) {
            return true;
        }

        return false;
    }
}
