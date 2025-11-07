<?php

namespace App\Data\Request\Shutterstock;

use Spatie\LaravelData\Data;

class GetVideoData extends Data
{
    public function __construct(
        public string $video_id,
    ) {}
} 