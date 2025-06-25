<?php

namespace App\Data\Request\Shutterstock;

use Spatie\LaravelData\Data;

class DownloadVideoData extends Data
{
    public function __construct(
        public string $license_id,
    ) {}
} 