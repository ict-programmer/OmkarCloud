<?php

namespace App\Repositories;

use App\Models\ServiceProvider;
use Illuminate\Database\Eloquent\Collection;

class ServiceProviderRepository
{
    /**
     * Get all service providers.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(): Collection
    {
        return ServiceProvider::all();
    }

    /**
     * Find a service provider by ID.
     *
     * @param  string  $id
     * @return \App\Models\ServiceProvider|null
     */
    public function find(string $id): ?ServiceProvider
    {
        return ServiceProvider::find($id);
    }

    /**
     * Find a service provider by type.
     *
     * @param  string  $type
     * @return \App\Models\ServiceProvider|null
     */
    public function findByType(array $attributes): ?ServiceProvider
    {
        return ServiceProvider::where($attributes)->first();
    }

    /**
     * Get service provider parameters in Google format.
     *
     * @param  string  $type
     * @return array|null
     */
    public function getGoogleParameters(array $attributes): ?array
    {
        $provider = $this->findByType($attributes);

        if (!$provider) {
            return null;
        }

        return $provider->getGoogleParameters();
    }

    /**
     * Create a new service provider.
     *
     * @param  array  $data
     * @return \App\Models\ServiceProvider
     */
    public function create(array $data): ServiceProvider
    {
        return ServiceProvider::create($data);
    }

    /**
     * Update a service provider.
     *
     * @param  string  $id
     * @param  array  $data
     * @return \App\Models\ServiceProvider|null
     */
    public function update(string $id, array $data): ?ServiceProvider
    {
        $serviceProvider = $this->find($id);

        if (!$serviceProvider) {
            return null;
        }

        $serviceProvider->update($data);

        return $serviceProvider->fresh();
    }

    /**
     * Delete a service provider.
     *
     * @param  string  $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        $serviceProvider = $this->find($id);

        if (!$serviceProvider) {
            return false;
        }

        return $serviceProvider->delete();
    }
}
