<?php

namespace App\Data\Request\Canva;

use Spatie\LaravelData\Data;

class GetUploadJobData extends Data
{
    public function __construct(
        public string $job_id
    ) {}
}
