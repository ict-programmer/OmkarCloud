<?php

namespace App\Data\Request\Shutterstock;

use Spatie\LaravelData\Data;

class DownloadImageData extends Data
{
    public function __construct(
        public string $license_id,
    ) {}
} 