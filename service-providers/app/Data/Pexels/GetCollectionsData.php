<?php

namespace App\Data\Pexels;

use Spatie\LaravelData\Data;

class GetCollectionsData extends Data
{
    public function __construct(
        public ?int $page,
        public ?int $per_page,
    ) {}
}
