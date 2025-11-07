<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class TempAuthToken extends Model
{
    protected $connection = 'mongodb_clusters_marketing';

    protected $table = 'temp_auth_tokens';

    protected $fillable = [
        'name',
        'token',
        'expires_at',
    ];
}
