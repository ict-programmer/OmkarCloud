<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ProjectStructure extends Model
{
    protected $connection = 'mongodb_clusters_projects';
}
