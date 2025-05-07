<?php

namespace App\Data\Request\FFMpeg;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class ImageProcessingData extends Data
{
    public function __construct(
        public UploadedFile $input_file,
        public int $width,
        public int $height
    ) {}
}
