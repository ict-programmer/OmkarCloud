<?php

namespace App\Data\Request\Claude;

use Spatie\LaravelData\Data;

class PersonalizationData extends Data
{
    public function __construct(
        public string $user_id,
        public string $preferences,
        public int $max_tokens,
        public ?string $model = null
    ) {}
}
