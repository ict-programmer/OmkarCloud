<?php

namespace App\Data\Request\Shutterstock;

use Spatie\LaravelData\Data;

class CreateVideoCollectionData extends Data
{
    public function __construct(
        public string $name,
    ) {}
} 