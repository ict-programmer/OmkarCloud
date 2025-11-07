<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Element extends Model
{
    protected $connection = 'mongodb_clusters_projects';

    protected $fillable = [
        'element_fused_json', 'element_code',
    ];
}
