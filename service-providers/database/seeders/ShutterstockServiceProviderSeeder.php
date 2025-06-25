<?php

namespace Database\Seeders;

use App\Models\ServiceProvider;
use App\Models\ServiceType;
use App\Models\ServiceProviderType;
use Illuminate\Database\Seeder;

class ShutterstockServiceProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => 'artlist'],
            [
                'type' => 'artlist',
                'parameter' => [
                    'base_url' => 'https://api.shutterstock.com/v2',
                ]
            ]
        );

        $this->command->info('Created/Updated Artlist service provider');

        // 1.3 Insert service types
        $serviceTypes = [
            'Search Images',
            'Get Image Details',
            'License Image',
            'Download Image',
            'Search Videos',
            'Get Video Details',
            'License Video',
            'Download Video',
            'Search Audio',
            'Get Audio Details',
            'License Audio',
            'Download Audio'
        ];

        $serviceTypeRecords = [];
        foreach ($serviceTypes as $serviceTypeName) {
            $serviceType = ServiceType::updateOrCreate(
                ['name' => $serviceTypeName],
                ['name' => $serviceTypeName]
            );
            $serviceTypeRecords[$serviceTypeName] = $serviceType;
            $this->command->info("Created/Updated service type: {$serviceTypeName}");
        }

        // 1.4 Insert service provider types with parameters
        $serviceProviderTypes = [
            [
                'service_type_name' => 'Search Images',
                'parameter' => [
                    'query' => 'technology',
                    'orientation' => 'horizontal'
                ]
            ],
            [
                'service_type_name' => 'Get Image Details',
                'parameter' => [
                    'image_id' => 'abc123'
                ]
            ],
            [
                'service_type_name' => 'License Image',
                'parameter' => [
                    'image_id' => 'abc123'
                ]
            ],
            [
                'service_type_name' => 'Download Image',
                'parameter' => [
                    'license_id' => 'lic123'
                ]
            ],
            [
                'service_type_name' => 'Search Videos',
                'parameter' => [
                    'query' => 'nature'
                ]
            ],
            [
                'service_type_name' => 'Get Video Details',
                'parameter' => [
                    'video_id' => 'vid123'
                ]
            ],
            [
                'service_type_name' => 'License Video',
                'parameter' => [
                    'video_id' => 'vid123'
                ]
            ],
            [
                'service_type_name' => 'Download Video',
                'parameter' => [
                    'license_id' => 'lic456'
                ]
            ],
            [
                'service_type_name' => 'Search Audio',
                'parameter' => [
                    'query' => 'ambient'
                ]
            ],
            [
                'service_type_name' => 'Get Audio Details',
                'parameter' => [
                    'audio_id' => 'aud123'
                ]
            ],
            [
                'service_type_name' => 'License Audio',
                'parameter' => [
                    'audio_id' => 'aud123'
                ]
            ],
            [
                'service_type_name' => 'Download Audio',
                'parameter' => [
                    'license_id' => 'lic789'
                ]
            ]
        ];

        foreach ($serviceProviderTypes as $serviceProviderTypeData) {
            $serviceType = $serviceTypeRecords[$serviceProviderTypeData['service_type_name']];
            
            ServiceProviderType::updateOrCreate(
                [
                    'service_provider_id' => $serviceProvider->id,
                    'service_type_id' => $serviceType->id
                ],
                [
                    'service_provider_id' => $serviceProvider->id,
                    'service_type_id' => $serviceType->id,
                    'parameter' => $serviceProviderTypeData['parameter'],
                ]
            );

            $this->command->info("Created/Updated service provider type for: {$serviceProviderTypeData['service_type_name']}");
        }

        $this->command->info('Successfully seeded Shutterstock/Artlist service provider with all types and parameters');
    }
} 