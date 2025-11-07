<?php

namespace App\Data\Request\GoogleSheetsAPI;

use Spatie\LaravelData\Data;

class CreateSpreadsheetData extends Data
{
    public function __construct(
        public array $sheets = [],
        public array $properties = []
    ) {}
}
