<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mime\MimeTypes;

trait ClaudeAITrait
{
    /**
     * Maximum file size in bytes (10MB)
     */
    protected static int $maxFileSize = 10 * 1024 * 1024;

    /**
     * Maximum text length to process (100KB of text)
     */
    protected static int $maxTextLength = 100 * 1024;

    /**
     * Prepare an attachment for Claude AI
     * 
     * @param UploadedFile $attachment
     * @return array
     * @throws BadRequestHttpException
     */
    public static function prepareAttachment(UploadedFile $attachment): array
    {
        // Check file size
        if ($attachment->getSize() > self::$maxFileSize) {
            throw new BadRequestHttpException("File '{$attachment->getClientOriginalName()}' exceeds maximum size limit of " .
                self::formatBytes(self::$maxFileSize));
        }

        // Check for empty file
        if ($attachment->getSize() === 0) {
            throw new BadRequestHttpException("File '{$attachment->getClientOriginalName()}' is empty");
        }

        $mimeType = $attachment->getMimeType() ?? 'application/octet-stream';
        $fileName = $attachment->getClientOriginalName();
        $extension = $attachment->getClientOriginalExtension();

        // If MIME type is generic, try guessing more accurately
        if ($mimeType === 'application/octet-stream' || $mimeType === 'text/plain') {
            $mimeTypes = new MimeTypes();
            $guessedType = $mimeTypes->guessMimeType($attachment->path());

            if ($guessedType !== null) {
                $mimeType = $guessedType;
            } else {
                $mimeType = self::getMimeTypeFromExtension($extension) ?? $mimeType;
            }
        }

        $type = self::resolveContentType($mimeType, $extension);

        try {
            switch ($type) {
                case 'text':
                    return self::handleTextFile($attachment, $fileName, $mimeType);

                case 'document':
                    return self::handleDocumentFile($attachment, $fileName);

                case 'spreadsheet':
                    return self::handleSpreadsheetFile($attachment, $fileName);

                case 'image':
                    return self::handleImageFile($attachment, $fileName, $mimeType);

                default:
                    // For unsupported formats, try to handle as text if it looks like text
                    if (self::isLikelyTextFile($attachment)) {
                        return self::handleTextFile($attachment, $fileName, 'text/plain');
                    }

                    throw new BadRequestHttpException("Unsupported file type '{$mimeType}' for Claude AI: {$fileName}");
            }
        } catch (Exception $e) {
            if ($e instanceof BadRequestHttpException) {
                throw $e;
            }

            Log::error("Error processing attachment for Claude AI: " . $e->getMessage(), [
                'file' => $fileName,
                'mime_type' => $mimeType,
                'exception' => $e
            ]);

            throw new BadRequestHttpException("Failed to process file '{$fileName}': " . $e->getMessage());
        }
    }

    /**
     * Handle text-based files
     */
    private static function handleTextFile(UploadedFile $file, string $fileName, string $mimeType): array
    {
        $fileContent = file_get_contents($file->path());

        if ($fileContent === false) {
            throw new BadRequestHttpException("Failed to read file '{$fileName}'");
        }

        // Check for binary content in text files
        if (self::isBinaryString($fileContent)) {
            throw new BadRequestHttpException("File '{$fileName}' appears to be binary, not text");
        }

        // Trim the content if it's too large
        if (strlen($fileContent) > self::$maxTextLength) {
            $fileContent = substr($fileContent, 0, self::$maxTextLength) . "\n\n[Content truncated due to Claude's size limitations]";
        }

        $language = self::guessCodeBlockLanguage($mimeType, $file->getClientOriginalExtension());
        $codeBlock = !empty($language) ? "```{$language}\n{$fileContent}\n```" : $fileContent;

        // Return in the format Claude expects
        return [
            'type' => 'text',
            'text' => "Attached file `{$fileName}`:\n\n{$codeBlock}"
        ];
    }

    /**
     * Handle PDF documents
     */
    private static function handleDocumentFile(UploadedFile $file, string $fileName): array
    {
        try {
            $parser = new Parser();
            $pdf = $parser->parseFile($file->path());
            $text = $pdf->getText();

            if (empty(trim($text))) {
                return self::handlePdfAsBase64($file, $fileName);
            }

            // Trim the content if it's too large
            if (strlen($text) > self::$maxTextLength) {
                $text = substr($text, 0, self::$maxTextLength) . "\n\n[Content truncated due to Claude's size limitations]";
            }

            return [
                'type' => 'text',
                'text' => "Extracted text from PDF `{$fileName}`:\n\n{$text}"
            ];
        } catch (Exception $e) {
            Log::error("PDF parsing error", [
                'file' => $fileName,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            try {
                return self::handlePdfAsBase64($file, $fileName);
            } catch (Exception $imageError) {
                // If both approaches fail, throw a more detailed exception
                throw new BadRequestHttpException("Failed to process PDF file '{$fileName}'. The PDF may be corrupted or in an unsupported format.");
            }
        }
    }

    /**
     * Handle PDF as base64 encoded text
     */
    private static function handlePdfAsBase64(UploadedFile $file, string $fileName): array
    {

        $fileContent = base64_encode(file_get_contents($file->path()));

        return [
            'type' => 'document',
            'source' => [
                'type' => 'base64',
                'media_type' => 'application/pdf',
                'data' => $fileContent
            ]
        ];
    }

    /**
     * Handle spreadsheet files (CSV, Excel)
     */
    private static function handleSpreadsheetFile(UploadedFile $file, string $fileName): array
    {
        $extension = strtolower($file->getClientOriginalExtension());

        // Handle CSV
        if ($extension === 'csv') {
            $content = file_get_contents($file->path());
            if ($content === false) {
                throw new BadRequestHttpException("Failed to read CSV file '{$fileName}'");
            }

            // Trim if needed
            if (strlen($content) > self::$maxTextLength) {
                $content = substr($content, 0, self::$maxTextLength) . "\n\n[Content truncated due to Claude's size limitations]";
            }

            return [
                'type' => 'text',
                'text' => "CSV data from file `{$fileName}`:\n```csv\n{$content}\n```"
            ];
        }

        // For Excel files, currently not supported directly
        throw new BadRequestHttpException("Excel files like '{$fileName}' are not directly supported by Claude AI. Consider converting to CSV format.");
    }

    /**
     * Handle image files
     */
    private static function handleImageFile(UploadedFile $file, string $fileName, string $mimeType): array
    {
        // Claude supports image inputs
        $fileContent = base64_encode(file_get_contents($file->path()));

        return [
            'type' => 'image',
            'source' => [
                'type' => 'base64',
                'media_type' => $mimeType,
                'data' => $fileContent
            ]
        ];
    }

    /**
     * Resolve content type from MIME type and extension
     */
    private static function resolveContentType(string $mimeType, string $extension = ''): string
    {
        // Check by MIME type first
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
            'image/jpg',
            'image/gif',
            'image/webp',
            'image/svg+xml' => 'image',
            default => null,
        };

        if ($byMimeType !== null) {
            return $byMimeType;
        }

        // Fall back to extension check if MIME type didn't match
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

    /**
     * Get MIME type from file extension
     */
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

    /**
     * Guess code block language based on MIME type and file extension
     */
    private static function guessCodeBlockLanguage(string $mimeType, string $extension = ''): string
    {
        // Try by MIME type first
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

        // Fall back to extension
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
            default => '',
        };
    }

    /**
     * Check if a string appears to be binary rather than text
     */
    private static function isBinaryString(string $str): bool
    {
        // Check for null bytes and a high percentage of non-printable characters
        $nonPrintable = 0;
        $length = strlen($str);

        if ($length === 0) {
            return false;
        }

        // Sample the string (up to 1000 characters)
        $sampleSize = min(1000, $length);
        $sample = substr($str, 0, $sampleSize);

        for ($i = 0; $i < strlen($sample); $i++) {
            $char = ord($sample[$i]);
            // Check for null byte (definite binary indicator)
            if ($char === 0) {
                return true;
            }

            // Count non-printable characters (except common whitespace)
            if (($char < 32 && !in_array($char, [9, 10, 13])) || $char >= 127) {
                $nonPrintable++;
            }
        }

        // If more than 30% non-printable characters, consider it binary
        return ($nonPrintable / $sampleSize) > 0.3;
    }

    /**
     * Check if a file is likely to be a text file
     */
    private static function isLikelyTextFile(UploadedFile $file): bool
    {
        // Check a sample of the file
        $f = fopen($file->path(), 'r');
        if (!$f) {
            return false;
        }

        // Read first 1000 bytes
        $sample = fread($f, 1000);
        fclose($f);

        if ($sample === false) {
            return false;
        }

        // Use binary string detection
        return !self::isBinaryString($sample);
    }

    /**
     * Format bytes to a human-readable string
     */
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
