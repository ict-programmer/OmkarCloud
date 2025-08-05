<?php

namespace Database\Seeders;

use App\Enums\common\ServiceProviderEnum;
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
     * This seeder creates a comprehensive RunwayML service provider configuration
     * with all available service types, including detailed parameter specifications,
     * data types, validation rules, and examples for each service.
     */
    public function run(): void
    {
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => ServiceProviderEnum::RUNWAY_ML->value],
            [
                'parameters' => [
                    'api_key' => 'YOUR_API_KEY',
                    'base_url' => 'https://api.dev.runwayml.com',
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
                        'description' => 'RunwayML API provides advanced video generation and processing capabilities using AI models',
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
                'description' => 'Generate videos using RunwayML AI models with image and text prompts',
                'input_parameters' => [
                    'model' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'The model to use for video generation',
                        'example' => 'gen4_turbo',
                        'validation' => 'required|string|in:gen4_turbo,gen3a_turbo',
                        'options' => [
                            'source' => 'collection',
                            'collection_name' => 'service_provider_model',
                            'value_field' => 'model_name',
                            'label_field' => 'display_name',
                            'filters' => [
                                'service_provider_id' => $serviceProvider->id,
                                'status' => 'active',
                                'supports_video_generation' => true,
                            ],
                            'fallback_options' => [
                                'gen4_turbo',
                                'gen3a_turbo',
                            ],
                        ],
                    ],
                    'prompt_image' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'The URL of the prompt image for video generation',
                        'example' => 'https://fastly.picsum.photos/id/0/5000/3333.jpg?hmac=_j6ghY5fCfSD6tvtcV74zXivkJSPIfR9B8w34XeQmvU',
                        'validation' => 'required|string|url',
                    ],
                    'prompt_text' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 1000,
                        'description' => 'The text prompt for video generation',
                        'example' => 'A beautiful sunset over the mountains',
                        'validation' => 'required|string|max:1000',
                    ],
                    'seed' => [
                        'type' => 'integer',
                        'required' => true,
                        'description' => 'The seed value for randomization to ensure consistent results',
                        'example' => 12345,
                        'validation' => 'required|integer',
                    ],
                    'duration' => [
                        'type' => 'integer',
                        'required' => true,
                        'default' => 5,
                        'options' => [5, 10],
                        'description' => 'The duration of the generated video in seconds',
                        'example' => 5,
                        'validation' => 'required|integer|in:5,10',
                    ],
                    'width' => [
                        'type' => 'integer',
                        'required' => true,
                        'default' => 1280,
                        'options' => [1280, 720, 1104, 832, 960, 1584, 768],
                        'description' => 'The width of the generated video in pixels',
                        'example' => 1280,
                        'validation' => 'required|integer|in:1280,720,1104,832,960,1584,768',
                    ],
                    'height' => [
                        'type' => 'integer',
                        'required' => true,
                        'default' => 720,
                        'options' => [720, 1280, 832, 1104, 960, 672, 768],
                        'description' => 'The height of the generated video in pixels',
                        'example' => 720,
                        'validation' => 'required|integer|in:720,1280,832,1104,960,672,768',
                    ],
                ],
                'response' => [
                    'status' => true,
                    'message' => 'Video processing task created successfully',
                    'data' => [
                        'id' => '17f20503-6c24-4c16-946b-35dbbce2af2f',
                        'status' => 'PENDING',
                        'createdAt' => '2024-06-27T19:49:32.334Z',
                        'model' => 'gen4_turbo',
                        'prompt_text' => 'A beautiful sunset over the mountains',
                        'duration' => 5,
                        'width' => 1280,
                        'height' => 720,
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.data.id',
                ],
                'request_class_name' => VideoProcessingRequest::class,
                'function_name' => 'videoProcessing',
            ],
            [
                'name' => 'Task Management',
                'description' => 'Check the status and details of a video generation task',
                'input_parameters' => [
                    'task_id' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'The ID of the task to check status and retrieve details',
                        'example' => '17f20503-6c24-4c16-946b-35dbbce2af2f',
                        'validation' => 'required|string|uuid',
                        'pattern' => '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i',
                    ],
                ],
                'response' => [
                    'id' => '17f20503-6c24-4c16-946b-35dbbce2af2f',
                    'status' => 'COMPLETED',
                    'createdAt' => '2024-06-27T19:49:32.334Z',
                    'updatedAt' => '2024-06-27T19:52:15.123Z',
                    'result' => [
                        'video_url' => 'https://api.runwayml.com/videos/17f20503-6c24-4c16-946b-35dbbce2af2f.mp4',
                        'thumbnail_url' => 'https://api.runwayml.com/thumbnails/17f20503-6c24-4c16-946b-35dbbce2af2f.jpg',
                        'duration' => 5.0,
                        'width' => 1280,
                        'height' => 720,
                        'file_size' => 2048576,
                    ],
                    'error' => null,
                ],
                'response_path' => [
                    'final_result' => '$.result',
                    'task_id' => '$.id',
                    'task_status' => '$.status',
                    'created_at' => '$.createdAt',
                    'updated_at' => '$.updatedAt',
                    'video_url' => '$.result.video_url',
                    'thumbnail_url' => '$.result.thumbnail_url',
                    'duration' => '$.result.duration',
                    'width' => '$.result.width',
                    'height' => '$.result.height',
                    'file_size' => '$.result.file_size',
                    'error_message' => '$.error',
                ],
                'request_class_name' => null, // No form request needed for path-only parameters
                'function_name' => 'taskManagement',
            ],
        ];

        $keptServiceTypeIds = $this->processServiceTypes($serviceProvider, $serviceTypes, ServiceProviderEnum::RUNWAY_ML->value);

        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);

        $this->command->info("Cleanup completed:");
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info("- Kept " . count($keptServiceTypeIds) . " service types for RunwayML API");
    }
}
