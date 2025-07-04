<?php

namespace Database\Seeders;

use App\Http\Controllers\PlacidController;
use App\Http\Requests\Placid\ImageGenerationRequest;
use App\Http\Requests\Placid\RetrievePdfRequest;
use App\Http\Requests\Placid\RetrieveTemplateRequest;
use App\Http\Requests\Placid\RetrieveVideoRequest;
use App\Http\Requests\Placid\VideoGenerationRequest;
use App\Models\ServiceProvider;
use App\Traits\ServiceProviderSeederTrait;
use Illuminate\Database\Seeder;

class PlacidServiceProviderSeeder extends Seeder
{
    use ServiceProviderSeederTrait;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => 'Placid'],
            [
                'parameters' => [
                    'api_key' => 'YOUR_PLACID_API_KEY',
                    'base_url' => 'https://api.placid.app',
                    'version' => '2.0',
                    'features' => [
                        'image_generation',
                        'retrieve_template',
                        'video_generation',
                        'retrieve_video',
                        'pdf_generation',
                        'retrieve_pdf',
                    ],
                ],
                'is_active' => true,
                'controller_name' => PlacidController::class,
            ]
        );

        $serviceTypes = [

        ];

        $keptServiceTypeIds = $this->processServiceTypes($serviceProvider, $serviceTypes, 'Placid');

        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);

        $this->command->info('Cleanup completed:');
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info('- Kept ' . count($keptServiceTypeIds) . ' service types for Placid');
    }
}
