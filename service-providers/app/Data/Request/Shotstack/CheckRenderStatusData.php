<?php

namespace App\Data\Request\Shotstack;

use Spatie\LaravelData\Data;

class CheckRenderStatusData extends Data
{
    public function __construct(
        public string $id
    ) {}
}
