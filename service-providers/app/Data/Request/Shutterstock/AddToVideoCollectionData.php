<?php

namespace App\Data\Request\Shutterstock;

use Spatie\LaravelData\Data;

class AddToVideoCollectionData extends Data
{
    public function __construct(
        public string $collection_id,
        public array $items,
    ) {}
} 