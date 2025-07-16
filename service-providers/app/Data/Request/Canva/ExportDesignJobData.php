<?php

namespace App\Data\Request\Canva;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class ExportDesignJobData extends Data
{
    public function __construct(
        public string $design_id,
        public array $format,
    ) {}
}
