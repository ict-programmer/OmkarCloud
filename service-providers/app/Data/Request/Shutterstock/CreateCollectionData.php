<?php

namespace App\Data\Request\Shutterstock;

use Spatie\LaravelData\Data;

class CreateCollectionData extends Data
{
    public function __construct(
        public string $name,
    ) {}
} 