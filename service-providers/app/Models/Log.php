<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class Log extends Model
{
    use SoftDeletes;

    protected $connection = 'mongodb_clusters_logs';

    protected $fillable = [
        'email', 'type', 'activity', 'created_by', 'updated_by',
    ];
}
