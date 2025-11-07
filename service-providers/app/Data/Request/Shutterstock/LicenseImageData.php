<?php

namespace App\Data\Request\Shutterstock;

use Spatie\LaravelData\Data;

class LicenseImageData extends Data
{
    public function __construct(
        public string $image_id,
    ) {}
} 