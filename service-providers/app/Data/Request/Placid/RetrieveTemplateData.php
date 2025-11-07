<?php

namespace App\Data\Request\Placid;

use Spatie\LaravelData\Data;

class RetrieveTemplateData extends Data
{
    public function __construct(
        public string $template_uuid,
    ) {}
}
