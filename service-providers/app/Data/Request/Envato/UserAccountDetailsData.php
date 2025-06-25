<?php

namespace App\Data\Request\Envato;

use Spatie\LaravelData\Data;

class UserAccountDetailsData extends Data
{
    public function __construct(
        public string $username,
    ) {}
} 