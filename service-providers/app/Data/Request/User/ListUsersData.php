<?php

namespace App\Data\Request\User;

use Spatie\LaravelData\Data;

class ListUsersData extends Data
{
    public function __construct(
        public ?string $search,
        public ?int $page_size = 1,
        public ?int $page_limit = 20,
        public ?string $sort_by = 'created_at',
        public ?string $sort_order = 'desc',
    ) {}
}