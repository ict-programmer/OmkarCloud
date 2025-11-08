<?php

namespace App\Data\Request\Envato;

use Spatie\LaravelData\Data;

class VerifyPurchaseCodeData extends Data
{
    public function __construct(
        public string $purchase_code,
    ) {}
} 