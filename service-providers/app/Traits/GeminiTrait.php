<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Log;
use App\Helpers\ImageToBase64Converter;
use App\Http\Exceptions\BadRequest;

trait GeminiTrait
{
    use PubliishIOTrait;

    protected static int $maxInlineDataFileSize = 20 * 1024 * 1024;
    protected static int $maxTextLength = 1 * 1024 * 1024;
    protected static int $maxImageDownloadSize = 20 * 1024 * 1024;

    public function prepareAttachmentPart(string $cid, bool $forceInlineImage = false): array
    {
        $attachmentUrl = $this->getPublishUrl($cid);

        if (!filter_var($attachmentUrl, FILTER_VALIDATE_URL)) {
            throw new BadRequest("Invalid attachment URL: '{$attachmentUrl}'");
        }

        $pathInfo = pathinfo($attachmentUrl);
        $extension = strtolower($pathInfo['extension'] ?? '');
        $isImageUrl = in_array($extension, ['png', 'jpg', 'jpeg', 'gif', 'webp', 'heic', 'heif']);

        if ($forceInlineImage && $isImageUrl) {
            return self::handleImageUrlAsInlineData($attachmentUrl);
        } else {
            return self::prepareUrlAttachmentPart($attachmentUrl);
        }
    }

    protected static function handleImageUrlAsInlineData(string $imageUrl): array
    {
        $converter = new ImageToBase64Converter(maxFileSize: self::$maxImageDownloadSize);

        try {
            $base64Data = $converter->convertSingle($imageUrl, includeDataUri: false, checkSizeFirst: true);

            if (is_null($base64Data)) {
                throw new BadRequest("Failed to convert image URL '{$imageUrl}' to Base64.");
            }

            $info = $converter->getImageInfo($imageUrl);
            $mimeType = $info['content_type'] ?? self::getMimeTypeFromExtension(pathinfo($imageUrl, PATHINFO_EXTENSION));

            if (!$mimeType || $mimeType === 'application/octet-stream' || !str_starts_with($mimeType, 'image/')) {
                 $detectedMime = $converter->detectMimeType(base64_decode($base64Data));
                 $mimeType = $detectedMime ?: 'image/jpeg';
            }

            if (!isset(self::getSupportedGeminiMimeTypes()[$mimeType])) {
                 Log::warning("Mime type '{$mimeType}' fetched for URL '{$imageUrl}' is not officially listed as supported for Gemini inline image. Attempting anyway.");
            }

            return [
                'inline_data' => [
                    'mime_type' => $mimeType,
                    'data' => $base64Data,
                ],
            ];
        } catch (Exception $e) {
            Log::error("Error processing image URL for inline Gemini content: " . $e->getMessage(), [
                'url' => $imageUrl,
                'exception' => $e
            ]);
            throw new BadRequest("Failed to prepare image from URL '{$imageUrl}' for Gemini API: " . $e->getMessage());
        }
    }

    protected static function prepareUrlAttachmentPart(string $url): array
    {
        $pathInfo = pathinfo($url);
        $extension = strtolower($pathInfo['extension'] ?? '');
        $mimeType = self::getMimeTypeFromExtension($extension) ?? 'application/octet-stream';

        if (!isset(self::getSupportedGeminiMimeTypes()[$mimeType]) && !str_starts_with($mimeType, 'image/')) {
            Log::warning("MIME type '{$mimeType}' inferred for URL '{$url}' may not be directly supported by Gemini for file_data. Proceeding with URL.");
        }

        return [
            'file_data' => [
                'mime_type' => $mimeType,
                'file_uri' => $url,
            ],
        ];
    }

    private static function resolveContentType(string $mimeType, string $extension = ''): string
    {
        $extension = strtolower($extension);

        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }
        if ($mimeType === 'application/pdf') {
             return 'document';
        }

         if ($mimeType === 'text/csv' || $extension === 'csv') {
             return 'spreadsheet';
         }

         if (in_array($mimeType, [
             'application/vnd.ms-excel',
             'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
             'application/vnd.oasis.opendocument.spreadsheet'
             ]) || in_array($extension, ['xls', 'xlsx', 'ods'])) {
             return 'spreadsheet';
         }

        $textMimePrefixes = ['text/', 'application/json', 'application/xml', 'application/javascript', 'application/typescript', 'application/x-yaml'];
        foreach ($textMimePrefixes as $prefix) {
            if (str_starts_with($mimeType, $prefix)) {
                return 'text';
            }
        }

        $textExtensions = [
             'txt', 'json', 'php', 'html', 'htm', 'md', 'py', 'js', 'ts', 'css',
             'xml', 'yaml', 'yml', 'java', 'cpp', 'c', 'h', 'cs', 'rb', 'go',
             'rs', 'swift', 'kt', 'sql', 'sh', 'bat', 'ps1', 'config', 'ini',
             'conf', 'log', 'jsx', 'tsx', 'vue', 'dart'
        ];
         if (in_array($extension, $textExtensions)) {
             return 'text';
         }

        return 'unsupported';
    }

    private static function getSupportedGeminiMimeTypes(): array
    {
        return [
            'text/plain' => true,
            'text/html' => true,
            'text/css' => true,
            'text/javascript' => true,
            'application/json' => true,
            'application/xml' => true,
            'text/markdown' => true,
            'text/csv' => true,
             'text/x-python' => true,
             'text/x-java' => true,
             'text/x-php' => true,

            'image/png' => true,
            'image/jpeg' => true,
            'image/webp' => true,
            'image/heic' => true,
            'image/heif' => true,
            'image/gif' => true,

            'application/pdf' => true,
        ];
    }

    private static function getMimeTypeFromExtension(?string $extension): ?string
    {
        if (empty($extension)) {
            return null;
        }

        $extension = strtolower($extension);
        return match ($extension) {
            'pdf' => 'application/pdf',
            'txt' => 'text/plain',
            'json' => 'application/json',
            'php' => 'text/x-php',
            'html', 'htm' => 'text/html',
            'md', 'markdown' => 'text/markdown',
            'py' => 'text/x-python',
            'js' => 'application/javascript',
            'ts' => 'application/typescript',
            'css' => 'text/css',
            'xml' => 'application/xml',
            'yaml', 'yml' => 'application/x-yaml',
            'java' => 'text/x-java',
            'cpp', 'c', 'h' => 'text/x-c++',
            'cs' => 'text/x-csharp',
            'rb' => 'text/x-ruby',
            'go' => 'text/x-go',
            'rs' => 'text/x-rust',
            'csv' => 'text/csv',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'heic' => 'image/heic',
            'heif' => 'image/heif',
            'svg' => 'image/svg+xml',
            default => null,
        };
    }

    private static function guessCodeBlockLanguage(string $mimeType, string $extension = ''): string
    {
        $byMimeType = match ($mimeType) {
            'application/json' => 'json',
            'text/x-php' => 'php',
            'text/html' => 'html',
            'text/markdown' => 'markdown',
            'text/x-python' => 'python',
            'text/javascript', 'application/javascript' => 'javascript',
            'application/typescript' => 'typescript',
            'text/css' => 'css',
            'application/xml', 'text/xml' => 'xml',
            'application/x-yaml', 'text/yaml' => 'yaml',
            'text/x-java' => 'java',
            'text/x-c++' => 'cpp',
            'text/x-csharp' => 'csharp',
            'text/x-ruby' => 'ruby',
            'text/x-go' => 'go',
            'text/x-rust' => 'rust',
            'text/csv' => 'csv',
            'text/plain' => '',
            default => null,
        };

        if ($byMimeType !== null) {
            return $byMimeType;
        }

        $extension = strtolower($extension);
        return match ($extension) {
            'json' => 'json',
            'php' => 'php',
            'html', 'htm' => 'html',
            'md', 'markdown' => 'markdown',
            'py' => 'python',
            'js' => 'javascript',
            'ts' => 'typescript',
            'jsx' => 'jsx',
            'tsx' => 'tsx',
            'css' => 'css',
            'xml' => 'xml',
            'yaml', 'yml' => 'yaml',
            'java' => 'java',
            'cpp', 'c', 'h' => 'cpp',
            'cs' => 'csharp',
            'rb' => 'ruby',
            'go' => 'go',
            'rs' => 'rust',
            'swift' => 'swift',
            'kt' => 'kotlin',
            'sql' => 'sql',
            'sh' => 'bash',
            'bat' => 'batch',
            'ps1' => 'powershell',
            'vue' => 'vue',
            'dart' => 'dart',
            'csv' => 'csv',
            'txt' => '',
            'log' => '',
            default => '',
        };
    }

    private static function isBinaryString(string $str): bool
    {
        return str_contains($str, "\0");
    }

    private static function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= ($pow > 0) ? (1 << (10 * $pow)) : 1;

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}