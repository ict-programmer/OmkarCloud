<?php

namespace App\Data\Request\FFMpeg;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class VideoProcessingData extends Data
{
    public function __construct(
        public UploadedFile $input_file,
        public string $resolution,
        public string $bitrate,
        public int $frame_rate
    ) {}
}
