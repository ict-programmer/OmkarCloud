<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Asset extends Model
{
    protected $connection = 'mongodb_clusters_content';

    protected $fillable = [
        'name', 'status',
    ];
}
