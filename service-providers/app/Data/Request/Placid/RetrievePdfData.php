<?php

namespace App\Data\Request\Placid;

use Spatie\LaravelData\Data;

class RetrievePdfData extends Data
{
    public function __construct(
        public int $pdf_id,
    ) {}
}
