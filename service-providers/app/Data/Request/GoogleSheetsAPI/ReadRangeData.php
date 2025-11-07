<?php

namespace App\Data\Request\GoogleSheetsAPI;

use Spatie\LaravelData\Data;

class ReadRangeData extends Data
{
    public function __construct(
        public string $spreadSheetId,
        public string $range,
        public ?string $majorDimensions = null,
        public ?string $valueRenderOption = null,
        public ?string $dateTimeRenderOption = null
    ) {}
}