<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MongoDB\Laravel\Eloquent\Model;

class ServiceProvider extends Model
{
    use HasFactory;

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
    protected $table = 'service_providers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'parameters',
        'is_active',
        'type',
        'client_id',
        'project_id',
        'auth_uri',
        'token_uri',
        'auth_provider_x509_cert_url',
        'client_secret',
        'redirect_uris',
        'interface',
        'controller_name',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'redirect_uris' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the service types for this service provider.
     */
    public function serviceTypes(): HasMany
    {
        return $this->hasMany(ServiceType::class);
    }

    /**
     * Get the service provider's parameters formatted for Google API.
     *
     * @return array
     */
    public function getGoogleParameters(): array
    {
        return [
            'installed' => [
                'client_id' => $this->client_id,
                'project_id' => $this->project_id,
                'auth_uri' => $this->auth_uri,
                'token_uri' => $this->token_uri,
                'auth_provider_x509_cert_url' => $this->auth_provider_x509_cert_url,
                'client_secret' => $this->client_secret,
                'redirect_uris' => $this->redirect_uris,
                'interface' => $this->interface,
            ],
        ];
    }
}
