<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ServiceProviderType extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * The collection associated with the model.
     *
     * @var string
     */
    protected $collection = 'service_provider_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
      'service_provider_id','service_type_id','parameters',
      'create_by','updated_by','deleted_by',
      'created_at','updated_at','deleted_at','seed'
    ];

    protected $casts = ['parameters'=>'array','created_at'=>'datetime','updated_at'=>'datetime','deleted_at'=>'datetime'];

}
