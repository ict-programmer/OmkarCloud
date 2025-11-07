<?php

namespace App\Traits;

use App\Models\ServiceProvider;
use App\Models\ServiceType;
use App\Models\ServiceProviderModel;
use App\Traits\MongoObjectIdTrait;

trait ServiceProviderSeederTrait
{
    use MongoObjectIdTrait;
    
    /**
     * Process service types for a service provider
     */
    protected function processServiceTypes(ServiceProvider $serviceProvider, array $serviceTypes, string $providerName): array
    {
        $keptServiceTypeIds = [];

        foreach ($serviceTypes as $serviceTypeData) {
            $serviceTypeName = $serviceTypeData['name'];

            $existingServiceType = ServiceType::where('service_provider_id', $this->toObjectId($serviceProvider->id))
                ->where('name', $serviceTypeName)
                ->first();

            $currentServiceType = null;

            if ($existingServiceType) {
                $existingServiceType->update([
                    'input_parameters' => $serviceTypeData['input_parameters'],
                    'request_class_name' => $serviceTypeData['request_class_name'],
                    'function_name' => $serviceTypeData['function_name'],
                    'response' => $serviceTypeData['response'],
                    'response_path' => $serviceTypeData['response_path'],
                ]);

                $currentServiceType = $existingServiceType;
                $keptServiceTypeIds[] = $existingServiceType->id;
                $this->command->info("Updated existing service type '{$serviceTypeName}' in {$providerName}");
            } else {
                $serviceType = ServiceType::create([
                    'name' => $serviceTypeName,
                    'service_provider_id' => $this->toObjectId($serviceProvider->id),
                    'input_parameters' => $serviceTypeData['input_parameters'],
                    'request_class_name' => $serviceTypeData['request_class_name'],
                    'function_name' => $serviceTypeData['function_name'],
                    'response' => $serviceTypeData['response'],
                    'response_path' => $serviceTypeData['response_path'],
                ]);

                $currentServiceType = $serviceType;
                $keptServiceTypeIds[] = $serviceType->id;
                $this->command->info("Created new service type '{$serviceTypeName}' in {$providerName}");
            }

            if (isset($serviceTypeData['input_parameters']['model']['options']['fallback_options'])) {
                foreach ($serviceTypeData['input_parameters']['model']['options']['fallback_options'] as $model) {
                    ServiceProviderModel::updateOrCreate(
                        [
                            'name' => $model,
                            'service_provider_id' => $this->toObjectId($serviceProvider->id),
                            'service_type_id' => $currentServiceType->id,
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
     * Clean up obsolete service types.
     */
    protected function cleanupObsoleteServiceTypes(ServiceProvider $serviceProvider, array $keptServiceTypeIds): int
    {
        $allServiceTypes = ServiceType::where('service_provider_id', $this->toObjectId($serviceProvider->id))->get();

        $serviceTypesToDelete = $allServiceTypes->filter(function ($serviceType) use ($keptServiceTypeIds) {
            return !in_array($serviceType->id, $keptServiceTypeIds);
        });

        $deletedServiceTypeCount = 0;
        foreach ($serviceTypesToDelete as $serviceTypeToDelete) {
            $serviceTypeToDelete->delete();
            $deletedServiceTypeCount++;
        }

        return $deletedServiceTypeCount;
    }
}
