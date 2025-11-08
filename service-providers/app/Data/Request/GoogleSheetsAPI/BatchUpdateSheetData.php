<?php

namespace App\Data\Request\GoogleSheetsAPI;

use Spatie\LaravelData\Data;

class BatchUpdateSheetData extends Data
{
    public function __construct(
        public string $range,
        public array $values, // This will be a 2D array of strings
    ) {}
}