<?php

namespace Database\Seeders;

use App\Http\Controllers\RunwaymlAPIController;
use App\Http\Requests\Runwayml\VideoProcessingRequest;
use App\Models\ServiceProvider;
use App\Traits\ServiceProviderSeederTrait;
use Illuminate\Database\Seeder;

class RunwaymlServiceProviderSeeder extends Seeder
{
    use ServiceProviderSeederTrait;

    /**
     * Run the database seeds.
     * 
     * This seeder creates a RunwayML service provider configuration
     * with service types that demonstrate both POST parameters and path parameters.
     */
    public function run(): void
    {
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => 'RunwayML'],
            [
                'parameters' => [
                    'api_key' => 'YOUR_API_KEY',
                    'base_url' => 'https://api.runwayml.com',
                    'version' => 'v1',
                    'models_supported' => [
                        'gen4_turbo',
                        'gen3a_turbo',
                    ],
                    'features' => [
                        'video_processing',
                        'task_management',
                    ],
                    'documentation' => [
                        'description' => 'RunwayML API provides advanced video generation and processing capabilities',
                        'api_documentation' => 'https://docs.runwayml.com',
                        'rate_limits' => 'Varies by plan',
                        'authentication' => 'API Key required in headers',
                    ],
                ],
                'is_active' => true,
                'controller_name' => RunwaymlAPIController::class,
            ]
        );

        $serviceTypes = [
            [
                'name' => 'Video Processing',
                'description' => 'Generate videos using RunwayML AI models',
                'parameter' => [
                    'model' => [
                        'type' => 'string',
                        'required' => true,
                        'enum' => ['gen4_turbo', 'gen3a_turbo'],
                        'description' => 'The model to use for video generation',
                        'example' => 'gen4_turbo',
                        'validation' => 'required|string|in:gen4_turbo,gen3a_turbo',
                    ],
                    'prompt_image' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'The URL of the prompt image',
                        'example' => 'https://example.com/image.png',
                        'validation' => 'required|string|url',
                    ],
                    'prompt_text' => [
                        'type' => 'string',
                        'required' => true,
                        'max_length' => 1000,
                        'description' => 'The text prompt for video generation',
                        'example' => 'A beautiful sunset over the mountains',
                        'validation' => 'required|string|max:1000',
                    ],
                    'seed' => [
                        'type' => 'integer',
                        'required' => true,
                        'description' => 'The seed value for randomization',
                        'example' => 12345,
                        'validation' => 'required|integer',
                    ],
                    'duration' => [
                        'type' => 'integer',
                        'required' => true,
                        'enum' => [5, 10],
                        'description' => 'The duration of the generated video in seconds',
                        'example' => 5,
                        'validation' => 'required|integer|in:5,10',
                    ],
                    'width' => [
                        'type' => 'integer',
                        'required' => true,
                        'enum' => [1280, 720, 1104, 832, 960, 1584, 768],
                        'description' => 'The width of the generated video',
                        'example' => 1280,
                        'validation' => 'required|integer|in:1280,720,1104,832,960,1584,768',
                    ],
                    'height' => [
                        'type' => 'integer',
                        'required' => true,
                        'enum' => [720, 1280, 832, 1104, 960, 672, 768],
                        'description' => 'The height of the generated video',
                        'example' => 720,
                        'validation' => 'required|integer|in:720,1280,832,1104,960,672,768',
                    ],
                ],
                'path_parameters' => [], // No path parameters for this endpoint
                'request_class_name' => VideoProcessingRequest::class,
                'function_name' => 'videoProcessing',
            ],
            [
                'name' => 'Task Management',
                'description' => 'Check the status of a video generation task',
                'parameter' => [], // No POST parameters for this endpoint
                'path_parameters' => [
                    'id' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'The ID of the task to check',
                        'example' => '17f20503-6c24-4c16-946b-35dbbce2af2f',
                        'validation' => 'required|string|uuid',
                        'pattern' => '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i',
                    ]
                ],
                'request_class_name' => null, // No form request needed for path-only parameters
                'function_name' => 'taskManagement',
            ],
        ];

        $keptServiceTypeIds = $this->processServiceTypes($serviceProvider, $serviceTypes, 'RunwayML');

        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);
        
        $this->command->info("Cleanup completed:");
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info("- Kept " . count($keptServiceTypeIds) . " service types for RunwayML API");
    }
} 