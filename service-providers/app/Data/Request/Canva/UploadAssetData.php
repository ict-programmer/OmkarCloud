<?php

namespace App\Data\Request\Canva;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class UploadAssetData extends Data
{
    public function __construct(
        public UploadedFile $file
    ) {}
}
