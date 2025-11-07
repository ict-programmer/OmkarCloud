<?php

namespace App\Data\Pexels;

use Spatie\LaravelData\Data;

class GetFeaturedCollectionsData extends Data
{
    public function __construct(
        public ?int $page,
        public ?int $per_page,
    ) {}
}
