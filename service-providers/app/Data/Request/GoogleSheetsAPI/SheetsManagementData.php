<?php

namespace App\Data\Request\GoogleSheetsAPI;

use Spatie\LaravelData\Data;

class SheetsManagementData extends Data
{
    public function __construct(
        public string $spreadSheetId,
        public string $type, // 'addSheet', 'deleteSheet', 'copySheet'
        public ?string $title = null, // For addSheet
        public ?int $sheetId = null, // For deleteSheet, copySheet
        public ?string $destinationSpreadsheetId = null, // For copySheet
    ) {}
}