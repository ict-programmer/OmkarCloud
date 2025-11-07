<?php

namespace App\Traits;

use Exception;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use OpenAI\Client;
use Smalot\PdfParser\Parser;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mime\MimeTypes;

trait OpenAIChatTrait
{
    /**
     * Maximum file size in bytes (20MB)
     */
    protected static int $maxFileSize = 20 * 1024 * 1024;

    /**
     * Maximum text length to process (4MB of text)
     */
    protected static int $maxTextLength = 4 * 1024 * 1024;

    /**
     * Maximum image size in bytes (20MB)
     */
    protected static int $maxImageSize = 20 * 1024 * 1024;

    /**
     * Prepare an attachment for OpenAI API
     * 
     * @param UploadedFile $attachment The uploaded file
     * @param Client $client OpenAI client instance (required for file uploads)
     * @return array Properly formatted content item for message
     * @throws BadRequestHttpException
     */
    public static function prepareAttachment(UploadedFile $attachment, ?Client $client = null): array
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
                    return self::handleImageFile($attachment, $mimeType);

                case 'file':
                    return self::handleGenericFile($attachment, $client);

                default:
                    if (self::isLikelyTextFile($attachment)) {
                        return self::handleTextFile($attachment, $fileName, 'text/plain');
                    }

                    if ($client) {
                        return self::handleGenericFile($attachment, $client);
                    }

                    throw new BadRequestHttpException("Unsupported file type '{$mimeType}' for OpenAI API: {$fileName}");
            }
        } catch (Exception $e) {
            if ($e instanceof BadRequestHttpException) {
                throw $e;
            }

            Log::error("Error processing attachment: " . $e->getMessage(), [
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
            $fileContent = substr($fileContent, 0, self::$maxTextLength) . "\n\n[Content truncated due to size limitations]";
        }

        $language = self::guessCodeBlockLanguage($mimeType, $file->getClientOriginalExtension());
        $formattedContent = !empty($language)
            ? "```{$language}\n{$fileContent}\n```"
            : $fileContent;

        return [
            'type' => 'text',
            'text' => "Attached file `{$fileName}`:\n\n{$formattedContent}"
        ];
    }

    /**
     * Handle PDF documents
     */
    /**
     * Handle PDF documents with fallback options for ChatGPT
     */
    private static function handleDocumentFile(UploadedFile $file, string $fileName): array
    {
        try {
            $parser = new Parser();
            $pdf = $parser->parseFile($file->path());
            $text = $pdf->getText();

            if (empty(trim($text))) {
                return self::handlePdfAsBase64ForChatGPT($file, $fileName);
            }

            if (strlen($text) > self::$maxTextLength) {
                $text = substr($text, 0, self::$maxTextLength) . "\n\n[Content truncated due to size limitations]";
            }

            return [
                'type' => 'text',
                'text' => "Extracted text from PDF `{$fileName}`:\n\n{$text}"
            ];
        } catch (Exception $e) {
            // Log the error
            Log::error("PDF parsing error in ChatGPT trait", [
                'file' => $fileName,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            try {
                return self::handlePdfAsBase64ForChatGPT($file, $fileName);
            } catch (Exception $fallbackError) {
                Log::error("ChatGPT PDF fallback error", [
                    'file' => $fileName,
                    'error' => $fallbackError->getMessage()
                ]);

                throw new BadRequestHttpException(
                    "Unable to process PDF file '{$fileName}'. " .
                        "The PDF may be corrupted, password-protected, or in an unsupported format. " .
                        "Please try converting it to text or providing the content in another format."
                );
            }
        }
    }

    /**
     * Special handling for PDFs with ChatGPT - using base64 approach
     */
    private static function handlePdfAsBase64ForChatGPT(UploadedFile $file, string $fileName): array
    {
        $fileContent = file_get_contents($file->path());

        if ($fileContent === false) {
            throw new Exception("Failed to read file contents");
        }

        return [
            'type' => 'file',
            'file' => [
                'file_id' => self::uploadFileToChatGpt($fileContent, $fileName, 'application/pdf')
            ]
        ];
    }

    /**
     * Upload a file to ChatGPT API and return the file_id
     */
    private static function uploadFileToChatGpt(string $fileContent, string $fileName, string $mimeType): string
    {
        // Example implementation (pseudo-code):
        try {
            $client = new GuzzleHttpClient(); // Guzzle or whatever HTTP client you're using

            // Create a temporary file
            $tempFile = tempnam(sys_get_temp_dir(), 'chatgpt_');
            file_put_contents($tempFile, $fileContent);

            // Prepare the multipart request
            $response = $client->post('https://api.openai.com/v1/files', [
                'headers' => [
                    'Authorization' => 'Bearer ' . config('services.openai.api_key'),
                ],
                'multipart' => [
                    [
                        'name' => 'purpose',
                        'contents' => 'assistants'
                    ],
                    [
                        'name' => 'file',
                        'contents' => fopen($tempFile, 'r'),
                        'filename' => $fileName,
                        'headers' => [
                            'Content-Type' => $mimeType
                        ]
                    ]
                ]
            ]);

            // Clean up the temp file
            unlink($tempFile);

            // Parse the response to get the file ID
            $responseData = json_decode($response->getBody()->getContents(), true);

            if (isset($responseData['id'])) {
                return $responseData['id'];
            } else {
                throw new Exception("File upload response doesn't contain file ID");
            }
        } catch (Exception $e) {
            Log::error("Failed to upload file to ChatGPT", [
                'error' => $e->getMessage(),
                'file' => $fileName
            ]);
            throw $e;
        }
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
                $content = substr($content, 0, self::$maxTextLength) . "\n\n[Content truncated due to size limitations]";
            }

            return [
                'type' => 'text',
                'text' => "CSV data from file `{$fileName}`:\n```csv\n{$content}\n```"
            ];
        }

        // For Excel files, we'll upload via the Assistants API as a file
        return [
            'type' => 'text',
            'text' => "Note: Excel file '{$fileName}' has been attached, but content cannot be directly embedded. The model will process it as a file attachment."
        ];
    }

    /**
     * Handle image files
     */
    private static function handleImageFile(UploadedFile $file, string $mimeType): array
    {
        // Check if image size is within limits
        if ($file->getSize() > self::$maxImageSize) {
            throw new BadRequestHttpException("Image '{$file->getClientOriginalName()}' exceeds maximum size limit of " .
                self::formatBytes(self::$maxImageSize));
        }

        $imageData = file_get_contents($file->path());

        if ($imageData === false) {
            throw new BadRequestHttpException("Failed to read image file '{$file->getClientOriginalName()}'");
        }

        return [
            'type' => 'image_url',
            'image_url' => [
                'url' => 'data:' . $mimeType . ';base64,' . base64_encode($imageData)
            ]
        ];
    }

    /**
     * Handle unsupported files using the Assistants API via file upload
     */
    private static function handleGenericFile(UploadedFile $file, Client $client): array
    {
        $fileContent = file_get_contents($file->path());

        if ($fileContent === false) {
            throw new BadRequestHttpException("Failed to read file '{$file->getClientOriginalName()}'");
        }

        return [
            'type' => 'file',
            'file' => [
                'file_id' => self::uploadFileToChatGpt(
                    $fileContent,
                    $file->getClientOriginalName(),
                    $file->getMimeType() ?? 'application/octet-stream'
                )
            ]
        ];
    }

    /**
     * Resolve content type from MIME and extension
     */
    private static function resolveContentType(string $mimeType, string $extension): string
    {
        $mimeType = strtolower($mimeType);
        $extension = strtolower($extension);

        if (str_starts_with($mimeType, 'text/') || in_array($extension, ['txt', 'md', 'log', 'json', 'xml'])) {
            return 'text';
        }

        if ($mimeType === 'application/pdf' || $extension === 'pdf') {
            return 'document';
        }

        if (in_array($extension, ['csv', 'xls', 'xlsx'])) {
            return 'spreadsheet';
        }

        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }

        return 'file';
    }

    /**
     * Guess language for code blocks
     */
    private static function guessCodeBlockLanguage(string $mimeType, string $extension): ?string
    {
        $mapping = [
            'json' => 'json',
            'xml' => 'xml',
            'js' => 'javascript',
            'ts' => 'typescript',
            'py' => 'python',
            'php' => 'php',
            'rb' => 'ruby',
            'java' => 'java',
            'go' => 'go',
            'c' => 'c',
            'cpp' => 'cpp',
            'cs' => 'csharp',
            'sh' => 'bash',
            'html' => 'html',
            'css' => 'css',
            'sql' => 'sql',
        ];

        return $mapping[strtolower($extension)] ?? null;
    }

    /**
     * Guess MIME type from file extension
     */
    private static function getMimeTypeFromExtension(string $extension): ?string
    {
        return (new MimeTypes())->getMimeTypes($extension)[0] ?? null;
    }

    /**
     * Format bytes into a human-readable string
     */
    private static function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' bytes';
    }

    /**
     * Determine if file content is likely binary
     */
    private static function isBinaryString(string $str): bool
    {
        return preg_match('~[^\x09\x0A\x0D\x20-\x7E]~', $str) > 0;
    }

    /**
     * Determine if file is likely a text file based on content
     */
    private static function isLikelyTextFile(UploadedFile $file): bool
    {
        $content = file_get_contents($file->path());
        return $content !== false && !self::isBinaryString($content);
    }
}
