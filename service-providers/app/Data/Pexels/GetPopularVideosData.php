<?php

namespace App\Data\Pexels;

use Spatie\LaravelData\Data;

class GetPopularVideosData extends Data
{
    public function __construct(
        public ?int $min_width,
        public ?int $min_height,
        public ?int $min_duration,
        public ?int $max_duration,
        public ?int $page,
        public ?int $per_page,
    ) {}
}
