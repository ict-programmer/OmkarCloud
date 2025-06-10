<?php

namespace App\Data\Pexels;

use Spatie\LaravelData\Data;

class SearchPhotosData extends Data
{
    public function __construct(
        public string $query,
        public ?string $orientation,
        public ?string $size,
        public ?string $color,
        public ?string $locale,
        public ?int $page,
        public ?int $per_page,
    ) {}
}
