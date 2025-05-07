<?php

namespace App\Data\Request\FFMpeg;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class AudioProcessingData extends Data
{
    public function __construct(
        public UploadedFile $input_file,
        public string $bitrate,
        public int $channels,
        public int $sample_rate
    ) {}
}
