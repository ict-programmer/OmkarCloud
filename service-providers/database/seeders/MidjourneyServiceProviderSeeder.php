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
                    'endpoints' => [
                        'task' => '/task',
                        'task_status' => '/task/{task_id}',
                    ],
                    'task_types' => [
                        'imagine',
                        'variation',
                    ],
                    'process_modes' => [
                        'relax',
                        'fast',
                        'turbo',
                    ],
                    'features' => [
                        'image_generation',
                        'image_variation',
                    ],
                    'supported_models' => [
                        'midjourney',
                    ],
                    'versions' => [
                        'v4',
                        'v5',
                        'v5.2',
                        'v6',
                        'v7',
                    ],
                ],
                'is_active' => true,
                'controller_name' => MidjourneyController::class,
            ]
        );

        $serviceTypes = [
            [
                'name' => 'Image Generation',
                'input_parameters' => [
                    'prompt' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 4000,
                        'description' => 'Detailed text prompt for image generation',
                        'example' => 'A majestic mountain landscape with snow-capped peaks at sunset',
                    ],
                    'aspect_ratio' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => '1:1',
                        'options' => ['1:1', '16:9', '9:16', '4:3', '3:4', '3:2', '2:3'],
                        'description' => 'Aspect ratio of the generated image',
                        'example' => '1:1',
                    ],
                    'quality' => [
                        'type' => 'string',
                        'required' => false,
                        'options' => ['high', 'medium', 'low'],
                        'description' => 'Quality of the generated image',
                        'example' => 'high',
                    ],
                    'style' => [
                        'type' => 'string',
                        'required' => false,
                        'options' => ['realistic', 'artistic', 'cartoon', 'anime'],
                        'description' => 'Style of the generated image',
                        'example' => 'realistic',
                    ],
                    'seed' => [
                        'type' => 'integer',
                        'required' => false,
                        'min' => 1,
                        'max' => 4294967295,
                        'description' => 'Seed for reproducible results',
                        'example' => 12345,
                    ],
                ],
                'response' => [
                    'task_id' => 'f5c0810d-a413-413b-8340-bc6b73ba899f',
                    'status' => 'completed',
                    'output' => [
                        'image_url' => 'https://img.theapi.app/mj/f5c0810d-a413-413b-8340-bc6b73ba899f.png',
                        'image_urls' => [
                            'https://cdn.midjourney.com/01da5cd8-61ee-4a33-afa7-015a18ca165e/0_0.png',
                            'https://cdn.midjourney.com/01da5cd8-61ee-4a33-afa7-015a18ca165e/0_1.png',
                            'https://cdn.midjourney.com/01da5cd8-61ee-4a33-afa7-015a18ca165e/0_2.png',
                            'https://cdn.midjourney.com/01da5cd8-61ee-4a33-afa7-015a18ca165e/0_3.png',
                        ],
                        'temporary_image_urls' => [
                            'https://img.theapi.app/cdn-cgi/image/trim=0;1024;1024;0/mj/f5c0810d-a413-413b-8340-bc6b73ba899f.png',
                            'https://img.theapi.app/cdn-cgi/image/trim=0;0;1024;1024/mj/f5c0810d-a413-413b-8340-bc6b73ba899f.png',
                            'https://img.theapi.app/cdn-cgi/image/trim=1024;1024;0;0/mj/f5c0810d-a413-413b-8340-bc6b73ba899f.png',
                            'https://img.theapi.app/cdn-cgi/image/trim=1024;0;0;1024/mj/f5c0810d-a413-413b-8340-bc6b73ba899f.png',
                        ],
                        'discord_image_url' => '',
                        'actions' => [
                            'reroll',
                            'upscale1',
                            'upscale2',
                            'upscale3',
                            'upscale4',
                            'variation1',
                            'variation2',
                            'variation3',
                            'variation4',
                        ],
                        'progress' => 100,
                        'intermediate_image_urls' => null,
                    ],
                    'meta' => [
                        'created_at' => '2025-06-28T12:01:21Z',
                        'started_at' => '2025-06-28T12:01:25Z',
                        'ended_at' => '2025-06-28T12:01:56Z',
                        'usage' => [
                            'type' => 'point',
                            'frozen' => 700000,
                            'consume' => 700000,
                        ],
                        'is_using_private_pool' => false,
                        'model_version' => 'unknown',
                        'process_mode' => 'fast',
                        'failover_triggered' => false,
                    ],
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => ImageGenerationRequest::class,
                'function_name' => 'imageGeneration',
            ],
            [
                'name' => 'Image Variation',
                'input_parameters' => [
                    'origin_task_id' => [
                        'type' => 'string',
                        'required' => true,
                        'format' => 'uuid',
                        'description' => 'The task ID of the parent task to create variations from',
                        'example' => '8409f94e-dd6a-4e5d-874d-3a074e72dcd0',
                    ],
                    'index' => [
                        'type' => 'string',
                        'required' => true,
                        'options' => ['1', '2', '3', '4', 'high_variation', 'low_variation'],
                        'description' => 'Image index to vary (1-4 for individual images, high_variation/low_variation for upscaled images)',
                        'example' => '1',
                    ],
                    'prompt' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 4000,
                        'description' => 'The prompt for the variation operation',
                        'example' => 'angry cat wearing a hat',
                    ],
                ],
                'response' => [
                    'task_id' => 'variation-8409f94e-dd6a-4e5d-874d-3a074e72dcd0',
                    'status' => 'completed',
                    'output' => [
                        'image_url' => 'https://img.theapi.app/mj/variation-8409f94e-dd6a-4e5d-874d-3a074e72dcd0.png',
                        'image_urls' => [
                            'https://cdn.midjourney.com/variation1/0_0.png',
                            'https://cdn.midjourney.com/variation1/0_1.png',
                            'https://cdn.midjourney.com/variation1/0_2.png',
                            'https://cdn.midjourney.com/variation1/0_3.png',
                        ],
                        'temporary_image_urls' => [
                            'https://img.theapi.app/cdn-cgi/image/trim=0;1024;1024;0/mj/variation-8409f94e-dd6a-4e5d-874d-3a074e72dcd0.png',
                            'https://img.theapi.app/cdn-cgi/image/trim=0;0;1024;1024/mj/variation-8409f94e-dd6a-4e5d-874d-3a074e72dcd0.png',
                            'https://img.theapi.app/cdn-cgi/image/trim=1024;1024;0;0/mj/variation-8409f94e-dd6a-4e5d-874d-3a074e72dcd0.png',
                            'https://img.theapi.app/cdn-cgi/image/trim=1024;0;0;1024/mj/variation-8409f94e-dd6a-4e5d-874d-3a074e72dcd0.png',
                        ],
                        'discord_image_url' => '',
                        'actions' => [
                            'reroll',
                            'upscale1',
                            'upscale2',
                            'upscale3',
                            'upscale4',
                            'variation1',
                            'variation2',
                            'variation3',
                            'variation4',
                        ],
                        'progress' => 100,
                        'intermediate_image_urls' => null,
                    ],
                    'meta' => [
                        'created_at' => '2025-06-28T12:05:21Z',
                        'started_at' => '2025-06-28T12:05:25Z',
                        'ended_at' => '2025-06-28T12:05:50Z',
                        'usage' => [
                            'type' => 'point',
                            'frozen' => 700000,
                            'consume' => 700000,
                        ],
                        'is_using_private_pool' => false,
                        'model_version' => 'unknown',
                        'process_mode' => 'fast',
                        'failover_triggered' => false,
                    ],
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => ImageVariationRequest::class,
                'function_name' => 'imageVariation',
            ]
        ];

        $keptServiceTypeIds = $this->processServiceTypes($serviceProvider, $serviceTypes, 'Midjourney');

        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);

        $this->command->info('Cleanup completed:');
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info('- Kept ' . count($keptServiceTypeIds) . ' service types for Midjourney');
    }
} 