<?php

namespace App\Traits;

use App\Http\Exceptions\BadRequest;
use Exception;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser;

trait DeepSeekTrait
{
    use PubliishIOTrait;

    protected static int $maxDownloadFileSize = 10 * 1024 * 1024;
    protected static int $maxTextLength = 1 * 1024 * 1024;

    public function prepareAttachment(string $cid): array
    {
        $attachmentUrl = $this->getPublishUrl($cid);

        if (!filter_var($attachmentUrl, FILTER_VALIDATE_URL)) {
            throw new BadRequest("Invalid attachment URL: '{$attachmentUrl}'");
        }

        $headers = @get_headers($attachmentUrl, 1);
        if ($headers === false) {
            throw new BadRequest("Could not retrieve headers from URL: '{$attachmentUrl}'. Check if the URL is accessible.");
        }

        $fileSize = 0;
        if (isset($headers['Content-Length'])) {
            $fileSize = (int)$headers['Content-Length'];
        } elseif (isset($headers['content-length'])) {
            $fileSize = (int)$headers['content-length'];
        }

        if ($fileSize === 0 && !self::isContentLengthOptional($attachmentUrl)) {
             Log::warning("Content-Length header not found or is 0 for URL: {$attachmentUrl}. Proceeding without size validation for now.");
        } elseif ($fileSize > self::$maxDownloadFileSize) {
            throw new BadRequest("File from URL '{$attachmentUrl}' exceeds maximum download size limit of " .
                self::formatBytes(self::$maxDownloadFileSize));
        }

        $mimeType = null;
        if (isset($headers['Content-Type'])) {
            if (is_array($headers['Content-Type'])) {
                $mimeType = explode(';', $headers['Content-Type'][0])[0];
            } else {
                $mimeType = explode(';', $headers['Content-Type'])[0];
            }
        }

        $pathInfo = pathinfo($attachmentUrl);
        $extension = strtolower($pathInfo['extension'] ?? '');

        if ($mimeType === 'application/octet-stream' || $mimeType === 'text/plain' || !$mimeType) {
            $mimeType = self::getMimeTypeFromExtension($extension) ?? $mimeType ?? 'application/octet-stream';
        }

        $type = self::resolveContentType($mimeType, $extension);

        try {
            switch ($type) {
                case 'text':
                    return self::handleTextUrl($attachmentUrl, $mimeType, $extension);

                case 'document':
                    return self::handleDocumentUrl($attachmentUrl);

                case 'spreadsheet':
                    return self::handleSpreadsheetUrl($attachmentUrl, $extension);

                case 'image':
                    throw new BadRequest("Image files from URL '{$attachmentUrl}' are not directly supported by DeepSeek chat models. Consider using an OCR service to extract text from the image before sending it to the model.");

                default:
                    throw new BadRequest("Unsupported file type '{$mimeType}' for DeepSeek API from URL: '{$attachmentUrl}'");
            }
        } catch (Exception $e) {
            if ($e instanceof BadRequest) {
                throw $e;
            }

            Log::error("Error processing attachment URL: " . $e->getMessage(), [
                'url' => $attachmentUrl,
                'mime_type' => $mimeType,
                'exception' => $e
            ]);

            throw new BadRequest("Failed to process attachment from URL '{$attachmentUrl}': " . $e->getMessage());
        }
    }

    private static function isContentLengthOptional(string $url): bool
    {
        $parsedUrl = parse_url($url);
        $host = $parsedUrl['host'] ?? '';
        
        // Add domains that might not provide Content-Length for all file types
        $optionalContentLengthHosts = [
            'drive.google.com', // Google Drive often redirects or streams, making Content-Length unreliable.
            'docs.google.com',
            // Add other hosts if you encounter similar issues
        ];

        foreach ($optionalContentLengthHosts as $domain) {
            if (str_contains($host, $domain)) {
                return true;
            }
        }
        return false;
    }

    private static function fetchUrlContents(string $url): string
    {
        $context = stream_context_create([
            'http' => [
                'follow_location' => 1,
                'max_redirects' => 5,
                'timeout' => 30,
            ],
        ]);

        $content = @file_get_contents($url, false, $context);

        if ($content === false) {
            throw new Exception("Failed to download content from URL: '{$url}'");
        }

        if (strlen($content) > self::$maxDownloadFileSize) {
             throw new BadRequest("Downloaded content from '{$url}' exceeds maximum allowed size of " . self::formatBytes(self::$maxDownloadFileSize));
        }

        return $content;
    }

    private static function handleTextUrl(string $url, string $mimeType, string $extension): array
    {
        $fileContent = self::fetchUrlContents($url);

        if (self::isBinaryString($fileContent)) {
            throw new BadRequest("Content from URL '{$url}' appears to be binary, not text.");
        }

        if (strlen($fileContent) > self::$maxTextLength) {
            $fileContent = substr($fileContent, 0, self::$maxTextLength) . "\n\n[Content truncated due to size limitations]";
        }

        $language = self::guessCodeBlockLanguage($mimeType, $extension);
        $codeBlock = !empty($language) ? "```{$language}\n{$fileContent}\n```" : $fileContent;

        return [
            'type' => 'text',
            'text' => "Attached file from `{$url}`:\n{$codeBlock}"
        ];
    }

    private static function handleDocumentUrl(string $url): array
    {
        $tempPdfPath = tempnam(sys_get_temp_dir(), 'pdf_');
        if ($tempPdfPath === false) {
            throw new Exception("Failed to create temporary file for PDF download.");
        }

        try {
            $pdfContent = self::fetchUrlContents($url);
            file_put_contents($tempPdfPath, $pdfContent);

            $parser = new Parser();
            $pdf = $parser->parseFile($tempPdfPath);
            $text = $pdf->getText();

            if (empty(trim($text))) {
                throw new Exception("No text could be extracted from the PDF at '{$url}'. It might be an image-only PDF.");
            }

            if (strlen($text) > self::$maxTextLength) {
                $text = substr($text, 0, self::$maxTextLength) . "\n\n[Content truncated due to size limitations]";
            }

            return [
                'type' => 'text',
                'text' => "Extracted text from PDF at `{$url}`:\n\n{$text}"
            ];
        } catch (Exception $e) {
            Log::error("PDF processing error from URL", [
                'url' => $url,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new BadRequest(
                "Unable to process PDF from URL '{$url}'. " .
                "This PDF may be corrupted, image-based, or in an unsupported format. " .
                "DeepSeek's API primarily processes text, and direct PDF upload for advanced analysis is not supported."
            );
        } finally {
            if (file_exists($tempPdfPath)) {
                unlink($tempPdfPath);
            }
        }
    }

    private static function handleSpreadsheetUrl(string $url, string $extension): array
    {
        if ($extension === 'csv') {
            $content = self::fetchUrlContents($url);

            if (strlen($content) > self::$maxTextLength) {
                $content = substr($content, 0, self::$maxTextLength) . "\n\n[Content truncated due to size limitations]";
            }

            return [
                'type' => 'text',
                'text' => "CSV data from URL `{$url}`:\n```csv\n{$content}\n```"
            ];
        }

        throw new BadRequest("Excel files from URL '{$url}' are not supported. Please convert to CSV or a text format.");
    }

    private static function resolveContentType(string $mimeType, string $extension = ''): string
    {
        $byMimeType = match ($mimeType) {
            'application/pdf' => 'document',
            'text/plain',
            'application/json',
            'text/x-php',
            'text/html',
            'text/markdown',
            'text/x-python',
            'text/javascript',
            'application/javascript',
            'application/typescript',
            'text/css',
            'application/xml',
            'text/xml',
            'application/x-yaml',
            'text/yaml',
            'text/x-java',
            'text/x-c++',
            'text/x-csharp',
            'text/x-ruby',
            'text/x-go',
            'text/x-rust' => 'text',
            'text/csv',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'spreadsheet',
            'image/png',
            'image/jpeg',
            'image/gif',
            'image/webp',
            'image/svg+xml' => 'image',
            default => null,
        };

        if ($byMimeType !== null) {
            return $byMimeType;
        }

        $extension = strtolower($extension);
        return match ($extension) {
            'pdf' => 'document',
            'txt', 'json', 'php', 'html', 'htm', 'md', 'py', 'js', 'ts', 'css',
            'xml', 'yaml', 'yml', 'java', 'cpp', 'c', 'h', 'cs', 'rb', 'go',
            'rs', 'swift', 'kt', 'sql', 'sh', 'bat', 'ps1', 'config', 'ini',
            'conf', 'log', 'jsx', 'tsx', 'vue', 'dart' => 'text',
            'csv', 'xls', 'xlsx', 'ods' => 'spreadsheet',
            'png', 'jpg', 'jpeg', 'gif', 'webp', 'svg', 'bmp', 'tiff', 'tif' => 'image',
            default => 'unsupported',
        };
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
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
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
        $nonPrintable = 0;
        $length = strlen($str);

        if ($length === 0) {
            return false;
        }

        $sampleSize = min(1000, $length);
        $sample = substr($str, 0, $sampleSize);

        for ($i = 0; $i < strlen($sample); $i++) {
            $char = ord($sample[$i]);
            if ($char === 0) {
                return true;
            }

            if (($char < 32 && !in_array($char, [9, 10, 13])) || $char >= 127) {
                $nonPrintable++;
            }
        }

        return ($nonPrintable / $sampleSize) > 0.3;
    }

    private static function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}