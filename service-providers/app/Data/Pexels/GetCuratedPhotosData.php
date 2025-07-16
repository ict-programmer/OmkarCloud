<?php

namespace App\Data\Pexels;

use Spatie\LaravelData\Data;

class GetCuratedPhotosData extends Data
{
    public function __construct(
        public ?int $page,
        public ?int $per_page,
    ) {}
}
