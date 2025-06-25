<?php

namespace App\Data\Request\Shutterstock;

use Spatie\LaravelData\Data;

class LicenseVideoData extends Data
{
    public function __construct(
        public array $videos,
        public ?string $search_id = null,
    ) {}
} 