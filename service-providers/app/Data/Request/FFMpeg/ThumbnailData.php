<?php

namespace App\Data\Request\FFMpeg;

use Spatie\LaravelData\Data;

class ThumbnailData extends Data
{
    public function __construct(
        public string $input,
        public string $timestamp
    ) {}
}
