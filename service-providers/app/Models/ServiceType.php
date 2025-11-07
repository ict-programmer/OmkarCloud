<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MongoDB\Laravel\Eloquent\Model;

class ServiceType extends Model
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
    protected $table = 'service_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'request_class_name',
        'function_name',
        'input_parameters',
        'response',
        'response_path',
        'service_provider_id'
    ];

    /**
     * Get the service provider that owns the service type.
     */
    public function serviceProvider(): BelongsTo
    {
        return $this->belongsTo(ServiceProvider::class);
    }

    /**
     * Get the service provider models for this service type.
     */
    public function serviceProviderModels(): HasMany
    {
        return $this->hasMany(ServiceProviderModel::class);
    }
}
