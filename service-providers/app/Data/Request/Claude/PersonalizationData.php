<?php

namespace App\Data\Request\Claude;

use Spatie\LaravelData\Data;

class PersonalizationData extends Data
{
    public function __construct(
        public string $user_id,
        public array $preferences
    ) {}
}
