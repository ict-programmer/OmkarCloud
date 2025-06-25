<?php

namespace App\Traits;

use App\Models\ServiceProvider;
use App\Models\ServiceType;
use App\Models\ServiceProviderType;

trait ServiceProviderSeederTrait
{
    /**
     * Process service types for a service provider
     */
    protected function processServiceTypes(ServiceProvider $serviceProvider, array $serviceTypes, string $providerName): array
    {
        $existingServiceTypes = ServiceType::whereIn('name', collect($serviceTypes)->pluck('name'))->get();
        
        $existingServiceProviderTypes = ServiceProviderType::where('service_provider_id', $serviceProvider->id)
            ->with('serviceType')
            ->get()
            ->keyBy('service_type_id');

        $keptServiceTypeIds = [];

        foreach ($serviceTypes as $serviceTypeData) {
            $serviceTypeName = $serviceTypeData['name'];
            
            // First, check if we already have a service type for this provider with the exact name
            $existingProviderType = $existingServiceProviderTypes->first(function ($providerType) use ($serviceTypeName) {
                return $providerType->serviceType->name === $serviceTypeName;
            });
            
            if ($existingProviderType) {
                // Update existing service type and provider type
                $serviceType = $existingProviderType->serviceType;
                $serviceType->update([
                    'description' => $serviceTypeData['description'],
                    'request_class_name' => $serviceTypeData['request_class_name'],
                    'function_name' => $serviceTypeData['function_name'],
                ]);
                
                $existingProviderType->update([
                    'parameter' => $serviceTypeData['parameter'],
                    'path_parameters' => $serviceTypeData['path_parameters'] ?? [],
                ]);
                
                $keptServiceTypeIds[] = $serviceType->id;
                $this->command->info("Updated existing service type '{$serviceTypeName}' for {$providerName}");
                
            } else {
                // Check if there's a service type with the same name but not associated with this provider
                $existingServiceType = $existingServiceTypes->where('name', $serviceTypeName)->first();
                
                if ($existingServiceType) {
                    // Create a unique name for this provider
                    $uniqueName = $serviceTypeName . " ({$providerName})";
                    $counter = 1;
                    while (ServiceType::where('name', $uniqueName)->exists()) {
                        $uniqueName = $serviceTypeName . " ({$providerName} " . $counter . ')';
                        $counter++;
                    }
                    
                    $newServiceType = ServiceType::create([
                        'name' => $uniqueName,
                        'description' => $serviceTypeData['description'],
                        'request_class_name' => $serviceTypeData['request_class_name'],
                        'function_name' => $serviceTypeData['function_name'],
                    ]);
                    
                    ServiceProviderType::create([
                        'service_provider_id' => $serviceProvider->id,
                        'service_type_id' => $newServiceType->id,
                        'parameter' => $serviceTypeData['parameter'],
                        'path_parameters' => $serviceTypeData['path_parameters'] ?? [],
                    ]);
                    
                    $keptServiceTypeIds[] = $newServiceType->id;
                    $this->command->info("Created new service type '{$uniqueName}' to avoid conflict with existing '{$serviceTypeName}'");
                    
                } else {
                    // Create new service type with original name
                    $newServiceType = ServiceType::create([
                        'name' => $serviceTypeName,
                        'description' => $serviceTypeData['description'],
                        'request_class_name' => $serviceTypeData['request_class_name'],
                        'function_name' => $serviceTypeData['function_name'],
                    ]);
                    
                    ServiceProviderType::create([
                        'service_provider_id' => $serviceProvider->id,
                        'service_type_id' => $newServiceType->id,
                        'parameter' => $serviceTypeData['parameter'],
                        'path_parameters' => $serviceTypeData['path_parameters'] ?? [],
                    ]);
                    
                    $keptServiceTypeIds[] = $newServiceType->id;
                    $this->command->info("Created new service type '{$serviceTypeName}' for {$providerName}");
                }
            }
        }

        return $keptServiceTypeIds;
    }

    /**
     * Clean up obsolete service provider types
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