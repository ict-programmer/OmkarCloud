<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser; 
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

trait GeminiTrait
{
    protected static int $maxFileSize = 20 * 1024 * 1024; 
    protected static int $maxTextLength = 1 * 1024 * 1024;

    public static function prepareAttachmentPart(UploadedFile $attachment): array
    {
        if ($attachment->getSize() > self::$maxFileSize) {
            throw new BadRequestHttpException("File '{$attachment->getClientOriginalName()}' exceeds maximum size limit of " .
                self::formatBytes(self::$maxFileSize));
        }

        if ($attachment->getSize() === 0) {
            throw new BadRequestHttpException("File '{$attachment->getClientOriginalName()}' is empty");
        }

        $mimeType = $attachment->getMimeType(); 
        if (!$mimeType) {
            $mimeType = $attachment->getClientMimeType() ?? 'application/octet-stream';
        }

        $fileName = $attachment->getClientOriginalName();
        $extension = $attachment->getClientOriginalExtension() ?? '';

        if ($mimeType === 'application/octet-stream' || $mimeType === 'text/plain') {
            $guessedMime = self::getMimeTypeFromExtension($extension);
             if ($guessedMime) {
                 $mimeType = $guessedMime;
             }
        }

        $type = self::resolveContentType($mimeType, $extension);

        $supportedMimeTypes = self::getSupportedGeminiMimeTypes();
        if (!isset($supportedMimeTypes[$mimeType])) {
             $fallbackMime = match ($type) {
                 'text' => 'text/plain',
                 'document' => 'application/pdf', 
                 'image' => null, 
                 'spreadsheet' => 'text/csv', 
                 default => null
             };
             if ($fallbackMime && isset($supportedMimeTypes[$fallbackMime])) {
                 Log::debug("Mime type '{$mimeType}' not directly supported by Gemini, using fallback '{$fallbackMime}' for file '{$fileName}' based on resolved type '{$type}'.");
                 $mimeType = $fallbackMime;
                 $type = self::resolveContentType($mimeType, $extension); 
             } else {
                 throw new BadRequestHttpException("Unsupported MIME type '{$mimeType}' for Gemini API processing: {$fileName}");
             }
        }

        try {
            switch ($type) {
                case 'text':
                    return self::handleTextFile($attachment, $fileName, $mimeType);

                case 'document': 
                     if ($mimeType === 'application/pdf') {
                         return self::handleBinaryFile($attachment, $fileName, $mimeType);
                     } else {
                          throw new BadRequestHttpException("Unsupported document type '{$mimeType}' for direct processing: {$fileName}. Only PDF is directly supported.");
                     }
                    
                case 'spreadsheet': 
                    if (strtolower($extension) === 'csv' || $mimeType === 'text/csv') {
                        return self::handleCsvFile($attachment, $fileName);
                    } else {
                        throw new BadRequestHttpException("Spreadsheet format '{$mimeType}' ({$fileName}) not supported. Please convert to CSV.");
                    }

                case 'image':
                    return self::handleBinaryFile($attachment, $fileName, $mimeType);

                default:
                    throw new BadRequestHttpException("Unsupported file type category '{$type}' derived from '{$mimeType}' for Gemini API: {$fileName}");
            }
        } catch (Exception $e) {
            if ($e instanceof BadRequestHttpException) {
                throw $e;
            }

            Log::error("Error processing attachment for Gemini: " . $e->getMessage(), [
                'file' => $fileName,
                'mime_type' => $mimeType,
                'exception' => $e
            ]);

            throw new BadRequestHttpException("Failed to process file '{$fileName}' for Gemini API: " . $e->getMessage());
        }
    }

    private static function handleTextFile(UploadedFile $file, string $fileName, string $mimeType): array
    {
        $fileContent = file_get_contents($file->path());

        if ($fileContent === false) {
            throw new BadRequestHttpException("Failed to read file '{$fileName}'");
        }

        if ($mimeType == 'text/plain' && self::isBinaryString($fileContent)) {
             Log::warning("File '{$fileName}' has MIME type text/plain but appears to contain binary data.");
        }

        $originalLength = strlen($fileContent);
        $truncated = false;
        if ($originalLength > self::$maxTextLength) {
            $fileContent = substr($fileContent, 0, self::$maxTextLength);
            $truncated = true;
            Log::warning("Text content truncated for file '{$fileName}'", [
                'original_size' => $originalLength,
                'truncated_size' => self::$maxTextLength
            ]);
        }
        
        $language = self::guessCodeBlockLanguage($mimeType, $file->getClientOriginalExtension() ?? '');
        $formattedContent = !empty($language) ? "```{$language}\n{$fileContent}\n```" : $fileContent;
        
        $prefix = "Content from file `{$fileName}` ({$mimeType}):\n";
        $suffix = $truncated ? "\n\n[Content truncated due to size limitations]" : "";

        return [
            'text' => $prefix . $formattedContent . $suffix
        ];
    }

    private static function handleCsvFile(UploadedFile $file, string $fileName): array
    {
        $content = file_get_contents($file->path());
        if ($content === false) {
            throw new BadRequestHttpException("Failed to read CSV file '{$fileName}'");
        }
        
        $originalLength = strlen($content);
        $truncated = false;
        if ($originalLength > self::$maxTextLength) {
            $content = substr($content, 0, self::$maxTextLength);
            $truncated = true;
             Log::warning("CSV content truncated for file '{$fileName}'", [
                'original_size' => $originalLength,
                'truncated_size' => self::$maxTextLength
            ]);
        }

        $prefix = "CSV data from file `{$fileName}`:\n```csv\n";
        $suffix = "\n```" . ($truncated ? "\n\n[Content truncated due to size limitations]" : "");

        return [
            'text' => $prefix . $content . $suffix
        ];
    }

    private static function handleBinaryFile(UploadedFile $file, string $fileName, string $mimeType): array
    {
        $fileContent = file_get_contents($file->path());
        if ($fileContent === false) {
            throw new BadRequestHttpException("Failed to read binary file '{$fileName}'");
        }

        $base64Data = base64_encode($fileContent);
        unset($fileContent); 

        if ($base64Data === false) {
            throw new BadRequestHttpException("Failed to base64 encode file '{$fileName}'");
        }

        return [
            'inline_data' => [
                'mime_type' => $mimeType,
                'data' => $base64Data
            ]
        ];
    }

    private static function resolveContentType(string $mimeType, string $extension = ''): string
    {
        $extension = strtolower($extension);

        if (str_starts_with($mimeType, 'image/')) {
            if (isset(self::getSupportedGeminiMimeTypes()[$mimeType])) {
                 return 'image';
            }
        }
        if ($mimeType === 'application/pdf') {
             if (isset(self::getSupportedGeminiMimeTypes()[$mimeType])) {
                 return 'document'; 
            }
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