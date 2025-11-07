<?php

namespace App\Data\Request\Freepik;

use Spatie\LaravelData\Data;

class StockContentData extends Data
{
    public function __construct(
        public ?int $page,
        public int $limit,
        public string $order,
        public ?string $term,
        public ?string $slug,
        public ?array $filters,
    ) {}
}
