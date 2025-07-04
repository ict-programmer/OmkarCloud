<?php

namespace Database\Seeders;

use App\Http\Controllers\MidjourneyController;
use App\Http\Requests\Midjourney\ImageGenerationRequest;
use App\Http\Requests\Midjourney\ImageVariationRequest;
use App\Http\Requests\Midjourney\GetTaskRequest;
use App\Models\ServiceProvider;
use App\Traits\ServiceProviderSeederTrait;
use Illuminate\Database\Seeder;

class MidjourneyServiceProviderSeeder extends Seeder
{
    use ServiceProviderSeederTrait;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => 'Midjourney'],
            [
                'parameters' => [
                    'api_key' => 'YOUR_PIAPI_KEY',
                    'base_url' => 'https://api.piapi.ai/api/v1',
                    'version' => 'v1',
                    'features' => [
                        'image_generation',
                        'image_variation',
                    ],
                ],
                'is_active' => true,
                'controller_name' => MidjourneyController::class,
            ]
        );

        $serviceTypes = [
            
        ];

        $keptServiceTypeIds = $this->processServiceTypes($serviceProvider, $serviceTypes, 'Midjourney');

        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);

        $this->command->info('Cleanup completed:');
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info('- Kept ' . count($keptServiceTypeIds) . ' service types for Midjourney');
    }
} 