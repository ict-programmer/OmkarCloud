<?php

namespace App\Data\Request\Shutterstock;

use Spatie\LaravelData\Data;

class DownloadAudioData extends Data
{
    public function __construct(
        public string $license_id,
    ) {}
} 