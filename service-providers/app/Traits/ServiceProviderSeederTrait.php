<?php

namespace App\Traits;

use App\Models\ServiceProvider;
use App\Models\ServiceProviderType;
use App\Models\ServiceType;
use Illuminate\Support\Str;

/**
 * Shared helpers for “service provider” seeders.
 * Stores a structured definition for each provider->type row in the `parameter` JSON.
 */
trait ServiceProviderSeederTrait
{
    /**
     * Create/Update all service types for a provider and return the kept type IDs.
     *
     * @param  ServiceProvider $provider
     * @param  array<int,array<string,mixed>> $serviceTypes
     * @param  string $providerKey  // e.g. 'OmkarCloudMapsScraper' (kept for metadata/debugging)
     * @return array<int,int>       // service_type IDs kept/created
     */
    protected function processServiceTypes(ServiceProvider $provider, array $serviceTypes, string $providerKey): array
    {
        $keptServiceTypeIds = [];

        foreach ($serviceTypes as $def) {
            // 1) ServiceType row
            $name = (string) ($def['name'] ?? 'Undefined');
            $serviceType = ServiceType::firstOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name)] // if your table doesn’t have slug, this will be ignored by Eloquent
            );

            $keptServiceTypeIds[] = $serviceType->id;

            // 2) provider<->type pivot row with full structured parameter payload
            $parameter = [
                'name'               => $name,
                'input_parameters'   => $def['input_parameters']   ?? [],
                'response'           => $def['response']           ?? null,
                'response_path'      => $def['response_path']      ?? null,
                'request_class_name' => $def['request_class_name'] ?? null,
                'function_name'      => $def['function_name']      ?? null,
                'provider_key'       => $providerKey,
            ];

            ServiceProviderType::updateOrCreate(
                [
                    'service_provider_id' => $provider->id,
                    'service_type_id'     => $serviceType->id,
                ],
                [
                    // Keep ONLY “parameter” here to match your Perplexity flow.
                    'parameter' => $parameter,
                ]
            );
        }

        return $keptServiceTypeIds;
    }

    /**
     * Delete provider-type rows for types that were not in the new definition.
     *
     * @return int number of deleted rows
     */
    protected function cleanupObsoleteServiceTypes(ServiceProvider $provider, array $keptServiceTypeIds): int
    {
        return ServiceProviderType::where('service_provider_id', $provider->id)
            ->whereNotIn('service_type_id', $keptServiceTypeIds)
            ->delete();
    }
}
