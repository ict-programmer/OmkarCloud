<?php

namespace App\Data\Request\GoogleSheetsAPI;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class BatchUpdateData extends Data
{
    public function __construct(
        public string $spreadSheetId,
        /** @var DataCollection<BatchUpdateSheetData> */
        public DataCollection $data,
        public string $valueInputOption,
    ) {}
}