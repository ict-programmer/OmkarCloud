<?php

namespace App\Data\Request\Shutterstock;

use Spatie\LaravelData\Data;

class LicenseVideoData extends Data
{
    public function __construct(
        public string $video_id,
    ) {}
} 