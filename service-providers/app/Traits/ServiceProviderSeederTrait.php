<?php

namespace App\Traits;

use App\Models\ServiceProvider;
use App\Models\ServiceType;
use App\Models\ServiceProviderType;
use App\Models\ServiceProviderModel;

trait ServiceProviderSeederTrait
{
    /**
     * Process service types for a service provider
     */
    protected function processServiceTypes(ServiceProvider $serviceProvider, array $serviceTypes, string $providerName): array
    {
        $keptServiceTypeIds = [];

        foreach ($serviceTypes as $serviceTypeData) {
            $serviceTypeName = $serviceTypeData['name'];
            
            $existingServiceProviderType = ServiceProviderType::where('service_provider_id', $serviceProvider->id)
                ->whereHas('serviceType', function($query) use ($serviceTypeName) {
                    $query->where('name', $serviceTypeName);
                })
                ->first();
            
            if ($existingServiceProviderType) {
                $serviceType = $existingServiceProviderType->serviceType;
                
                $existingServiceProviderType->update([
                    'input_parameters' => $serviceTypeData['input_parameters'],
                    'request_class_name' => $serviceTypeData['request_class_name'],
                    'function_name' => $serviceTypeData['function_name'],
                    'response' => $serviceTypeData['response'],
                    'response_path' => $serviceTypeData['response_path'],
                ]);
                
                $keptServiceTypeIds[] = $serviceType->id;
                $this->command->info("Updated existing service provider type relationship for '{$serviceTypeName}' in {$providerName}");
                
            } else {
                $serviceType = ServiceType::create([
                    'name' => $serviceTypeName,
                    
                ]);
                
                ServiceProviderType::create([
                    'service_provider_id' => $serviceProvider->id,
                    'service_type_id' => $serviceType->id,
                    'input_parameters' => $serviceTypeData['input_parameters'],
                    'request_class_name' => $serviceTypeData['request_class_name'],
                    'function_name' => $serviceTypeData['function_name'],
                    'response' => $serviceTypeData['response'],
                    'response_path' => $serviceTypeData['response_path'],
                ]);
                
                $keptServiceTypeIds[] = $serviceType->id;
                $this->command->info("Created new service provider type relationship for '{$serviceTypeName}' in {$providerName}");
            }

            if (isset($serviceTypeData['input_parameters']['model']['options']['fallback_options'])) {
                foreach ($serviceTypeData['input_parameters']['model']['options']['fallback_options'] as $model) {
                    ServiceProviderModel::updateOrCreate(
                        [
                            'name' => $model,
                            'service_provider_id' => $serviceProvider->id,
                            'service_type_id' => $serviceType->id,
                        ],
                        [
                            'status' => 'active',
                        ]
                    );
                }
            }
        }

        return $keptServiceTypeIds;
    }

    /**
     * Clean up obsolete service provider types.
     */
    protected function cleanupObsoleteServiceTypes(ServiceProvider $serviceProvider, array $keptServiceTypeIds): int
    {
        $allServiceProviderTypes = ServiceProviderType::where('service_provider_id', $serviceProvider->id)->get();
        
        $serviceProviderTypesToDelete = $allServiceProviderTypes->filter(function ($providerType) use ($keptServiceTypeIds) {
            return !in_array($providerType->service_type_id, $keptServiceTypeIds);
        });
        
        $deletedProviderTypeCount = 0;
        foreach ($serviceProviderTypesToDelete as $providerTypeToDelete) {
            $providerTypeToDelete->delete();
            $deletedProviderTypeCount++;
        }
        
        return $deletedProviderTypeCount;
    }
}
