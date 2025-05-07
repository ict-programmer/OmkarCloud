<?php

namespace App\Data\Request\FFMpeg;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class VideoTrimmingData extends Data
{
    public function __construct(
        public UploadedFile $input_file,
        public string $start_time,
        public string $end_time
    ) {}
}
