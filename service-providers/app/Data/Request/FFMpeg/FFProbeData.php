<?php

namespace App\Data\Request\FFMpeg;

use Spatie\LaravelData\Data;

class FFProbeData extends Data
{
    public function __construct(
        public string $file_link,
        public string $output_format = 'json',
        public bool $show_format = true,
        public bool $show_streams = true,
        public bool $show_chapters = false,
        public bool $show_programs = false,
        public ?string $select_streams = ''
    ) {}
}
