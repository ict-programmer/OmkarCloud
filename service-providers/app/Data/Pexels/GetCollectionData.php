<?php

namespace App\Data\Pexels;

use Spatie\LaravelData\Data;

class GetCollectionData extends Data
{
    public function __construct(
        public string $id,
        public ?string $type,
        public ?string $sort,
        public ?int $page,
        public ?int $per_page,
    ) {}
}
