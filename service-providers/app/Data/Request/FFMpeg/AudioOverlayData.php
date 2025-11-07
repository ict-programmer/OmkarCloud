<?php

namespace App\Data\Request\FFMpeg;

use Spatie\LaravelData\Data;

class AudioOverlayData extends Data
{
    public function __construct(
        public string $background_track,
        public string $overlay_track,
        public string $output_format
    ) {}
}
