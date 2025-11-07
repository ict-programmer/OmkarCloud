<?php

namespace App\Data\Request\User;

use Spatie\LaravelData\Data;

class DeleteUsersData extends Data
{
    public function __construct(
        public string $id,
    ) {}
}