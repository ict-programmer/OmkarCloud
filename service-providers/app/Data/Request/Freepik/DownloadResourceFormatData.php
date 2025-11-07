<?php

namespace App\Data\Request\Freepik;

use Spatie\LaravelData\Data;

class DownloadResourceFormatData extends Data
{
    public function __construct(
        public string $resource_id,
        public string $format
    ) {}
}
