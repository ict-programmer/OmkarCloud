<?php

namespace App\Helpers;

use Exception;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImageToBase64Converter
{
    private $timeout;

    private $maxFileSize;

    private $userAgent;

    private $maxConcurrent;

    public function __construct(
        int $timeout = 15 * 60, // 15 minutes
        int $maxFileSize = 100 * 1024 * 1024, // 100MB
        string $userAgent = 'Mozilla/5.0 (compatible; ImageConverter/2.0)',
        int $maxConcurrent = 10
    ) {
        $this->timeout = $timeout;
        $this->maxFileSize = $maxFileSize;
        $this->userAgent = $userAgent;
        $this->maxConcurrent = $maxConcurrent;
    }

    /**
     * Convert single image URL to base64
     */
    public function convertSingle(string $url, bool $includeDataUri = true, bool $checkSizeFirst = true): ?string
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        try {
            // First check image info if requested
            if ($checkSizeFirst) {
                $info = $this->getImageInfo($url);

                if (!$info) {
                    throw new Exception('Failed to get image info.');
                }

                // Validate content type
                if (!str_starts_with($info['content_type'] ?? '', 'image/')) {
                    throw new Exception('Invalid content type.');
                }

                // Check file size (if available)
                if ($info['content_length'] > 0 && $info['content_length'] > $this->maxFileSize) {
                    throw new Exception('File size exceeds the maximum limit of ' . $this->maxFileSize . ' bytes.');
                }
            }

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'User-Agent' => $this->userAgent,
                    'Accept' => 'image/*',
                ])
                ->withOptions([
                    'allow_redirects' => ['max' => 3],
                    'stream' => true, // Memory efficient for large files
                ])
                ->get($url);

            return $this->processResponse($response, $includeDataUri);
        } catch (\Exception $e) {
            throw new Exception('Image conversion failed: ' . $e->getMessage());
        }
    }

    /**
     * Convert multiple image URLs to base64 concurrently
     */
    public function convertMultiple(array $urls, bool $includeDataUri = true): array
    {
        $urls = array_filter($urls, fn ($url) => filter_var($url, FILTER_VALIDATE_URL));

        if (empty($urls)) {
            return [];
        }

        $results = [];
        $chunks = array_chunk($urls, $this->maxConcurrent);

        foreach ($chunks as $chunk) {
            $chunkResults = $this->processChunk($chunk, $includeDataUri);
            $results = array_merge($results, $chunkResults);
        }

        return $results;
    }

    /**
     * Process a chunk of URLs concurrently
     */
    private function processChunk(array $urls, bool $includeDataUri): array
    {
        $responses = Http::pool(function (Pool $pool) use ($urls) {
            $requests = [];
            foreach ($urls as $url) {
                $requests[$url] = $pool
                    ->timeout($this->timeout)
                    ->withHeaders([
                        'User-Agent' => $this->userAgent,
                        'Accept' => 'image/*',
                    ])
                    ->withOptions([
                        'allow_redirects' => ['max' => 3],
                        'stream' => true,
                    ])
                    ->get($url);
            }

            return $requests;
        });

        $results = [];
        foreach ($responses as $url => $response) {
            $results[$url] = $this->processResponse($response, $includeDataUri);
        }

        return $results;
    }

    /**
     * Process HTTP response and convert to base64
     */
    private function processResponse($response, bool $includeDataUri): ?string
    {
        try {
            if (!$response->successful()) {
                return null;
            }

            $body = $response->body();

            // Check file size
            if (strlen($body) > $this->maxFileSize) {
                return null;
            }

            // Validate content type
            $contentType = $response->header('Content-Type');
            if ($includeDataUri && $contentType && !str_starts_with($contentType, 'image/')) {
                return null;
            }

            // Additional image validation by checking magic bytes
            if (!$this->isValidImageData($body)) {
                return null;
            }

            $base64 = base64_encode($body);

            return $includeDataUri
                ? 'data:' . ($contentType ?: $this->detectMimeType($body)) . ';base64,' . $base64
                : $base64;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Validate image data by checking magic bytes
     */
    private function isValidImageData(string $data): bool
    {
        $signatures = [
            "\xFF\xD8\xFF", // JPEG
            "\x89PNG\r\n\x1A\n", // PNG
            'GIF87a', // GIF87a
            'GIF89a', // GIF89a
            'RIFF', // WebP (starts with RIFF)
            "\x00\x00\x01\x00", // ICO
            "\x00\x00\x02\x00", // CUR
            'BM', // BMP
        ];

        foreach ($signatures as $signature) {
            if (str_starts_with($data, $signature)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Detect MIME type from binary data
     */
    private function detectMimeType(string $data): string
    {
        $signatures = [
            "\xFF\xD8\xFF" => 'image/jpeg',
            "\x89PNG\r\n\x1A\n" => 'image/png',
            'GIF87a' => 'image/gif',
            'GIF89a' => 'image/gif',
            'RIFF' => 'image/webp',
            "\x00\x00\x01\x00" => 'image/x-icon',
            "\x00\x00\x02\x00" => 'image/x-icon',
            'BM' => 'image/bmp',
        ];

        foreach ($signatures as $signature => $mimeType) {
            if (str_starts_with($data, $signature)) {
                return $mimeType;
            }
        }

        return 'image/jpeg'; // Default fallback
    }

    /**
     * Get image info without downloading full content (HEAD request)
     */
    public function getImageInfo(string $url): ?array
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        try {
            $response = Http::timeout(5)
                ->withHeaders(['User-Agent' => $this->userAgent])
                ->head($url);

            if (!$response->successful()) {
                return null;
            }

            return [
                'url' => $url,
                'content_type' => $response->header('Content-Type'),
                'content_length' => (int) $response->header('Content-Length'),
                'last_modified' => $response->header('Last-Modified'),
                'etag' => $response->header('ETag'),
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get info for multiple images concurrently
     */
    public function getMultipleImageInfo(array $urls): array
    {
        $urls = array_filter($urls, fn ($url) => filter_var($url, FILTER_VALIDATE_URL));

        if (empty($urls)) {
            return [];
        }

        $results = [];
        $chunks = array_chunk($urls, $this->maxConcurrent);

        foreach ($chunks as $chunk) {
            $responses = Http::pool(function (Pool $pool) use ($chunk) {
                $requests = [];
                foreach ($chunk as $url) {
                    $requests[$url] = $pool
                        ->timeout(5)
                        ->withHeaders(['User-Agent' => $this->userAgent])
                        ->head($url);
                    Log::info($url, ['url' => $url]);
                }

                return $requests;
            });

            foreach ($responses as $url => $response) {
                if ($response->successful()) {
                    $results[$url] = [
                        'url' => $url,
                        'content_type' => $response->header('Content-Type'),
                        'content_length' => (int) $response->header('Content-Length'),
                        'last_modified' => $response->header('Last-Modified'),
                        'etag' => $response->header('ETag'),
                    ];
                    Log::info($url, $results[$url]);
                } else {
                    $results[$url] = null;
                }
            }
        }

        return $results;
    }

    /**
     * Smart batch conversion - check info first, then convert only valid images
     */
    public function smartConvertMultiple(array $urls, bool $includeDataUri = true, ?int $maxSizeBytes = null): array
    {
        $maxSizeBytes = $maxSizeBytes ?: $this->maxFileSize;

        // First, get info for all images
        $infoResults = $this->getMultipleImageInfo($urls);

        // Filter URLs based on criteria
        $validUrls = [];
        foreach ($infoResults as $url => $info) {
            if (
                $info &&
                str_starts_with($info['content_type'] ?? '', 'image/') &&
                ($info['content_length'] === 0 || $info['content_length'] <= $maxSizeBytes)
            ) {
                $validUrls[] = $url;
            } else {
                if (!empty($info['content_length']) && $info['content_length'] > $maxSizeBytes) {
                    throw new Exception('File size exceeds the maximum limit of ' . $maxSizeBytes . ' bytes.');
                } else {
                    throw new Exception('Invalid image url.');
                }
            }
        }

        // Convert only valid images
        $convertResults = $this->convertMultiple($validUrls, $includeDataUri);

        // Merge results
        $finalResults = [];
        foreach ($urls as $url) {
            $finalResults[$url] = $convertResults[$url] ?? null;
        }

        return $finalResults;
    }

    /**
     * Convert with retry mechanism
     */
    public function convertWithRetry(string $url, bool $includeDataUri = true, int $maxRetries = 3): ?string
    {
        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            $result = $this->convertSingle($url, $includeDataUri);

            if ($result !== null) {
                return $result;
            }

            if ($attempt < $maxRetries) {
                // Exponential backoff
                sleep(pow(2, $attempt - 1));
            }
        }

        return null;
    }

    public static function imageUrlToBase64(string $url, bool $includeDataUri = true): ?string
    {
        static $converter = null;
        if ($converter === null) {
            $converter = new ImageToBase64Converter();
        }

        return $converter->convertSingle($url, $includeDataUri);
    }

    public static function batchImageUrlToBase64(array $urls, bool $includeDataUri = true): array
    {
        static $converter = null;
        if ($converter === null) {
            $converter = new ImageToBase64Converter();
        }

        return $converter->smartConvertMultiple($urls, $includeDataUri);
    }
}
