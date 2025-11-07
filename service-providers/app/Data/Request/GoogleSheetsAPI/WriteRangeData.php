<?php

namespace App\Data\Request\GoogleSheetsAPI;

use Spatie\LaravelData\Data;

class WriteRangeData extends Data
{
    public function __construct(
        public string $spreadSheetId,
        public string $range,
        public ?string $valueInputOption,
        public array $values,
    ) {}
}