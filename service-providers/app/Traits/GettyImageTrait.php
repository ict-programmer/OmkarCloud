<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mime\MimeTypes;

trait GettyImageTrait
{
    /**
     * Handle image files
     */
    private static function handleImageFile(UploadedFile $file, string $fileName, string $mimeType): array
    {
        // Qwen supports image inputs
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

}
