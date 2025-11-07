<?php

namespace App\Data\Request\ReactJs;

use Spatie\LaravelData\Data;

class ReactJsMergeJsonData extends Data
{
    public function __construct(
        public mixed $project_structure,
    ) {}
}
