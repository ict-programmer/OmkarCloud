<?php

namespace App\Data\Request\Canva;

use Spatie\LaravelData\Data;

class OAuthCallbackData extends Data
{
    public function __construct(
        public string $code,
        public string $state
    ) {}
}
