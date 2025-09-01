<?php

namespace App\Data\Request\GoogleSheetsAPI;

use Spatie\LaravelData\Data;

class ClearRangeData extends Data
{
    public function __construct(
        public string $spreadSheetId,
        public string $range,
    ) {}
}