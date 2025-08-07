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
            [
                'name' => 'Image Generation',
                'input_parameters' => [
                    'prompt' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'min_length' => 1,
                        'max_length' => 4000,
                        'description' => 'A detailed text prompt for image generation',
                        'default' => 'A beautiful landscape with mountains and a lake at sunset, photorealistic, 4K, detailed',
                    ],
                    'aspect_ratio' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => '1:1',
                        'options' => [
                            '1:1',
                            '16:9',
                            '9:16',
                            '4:3',
                            '3:4',
                            '3:2',
                            '2:3',
                        ],
                        'description' => 'Aspect ratio for the generated image',
                    ],
                ],
                'response' => [
                    'message' => 'Image generation successful.',
                    'data' => [
                        'task_id' => '550e8400-e29b-41d4-a716-446655440000',
                        'status' => 'completed',
                        'output' => [
                            'images' => [
                                [
                                    'url' => 'https://example.com/generated-image-1.jpg',
                                    'width' => 1024,
                                    'height' => 1024,
                                ],
                                [
                                    'url' => 'https://example.com/generated-image-2.jpg',
                                    'width' => 1024,
                                    'height' => 1024,
                                ],
                            ],
                        ],
                        'meta' => [
                            'created_at' => '2024-01-15T10:30:00Z',
                            'processing_time' => 45.2,
                        ],
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.data',
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
                        'userinput_rqd' => true,
                        'description' => 'UUID of the parent task to create variations from',
                        'default' => '550e8400-e29b-41d4-a716-446655440000',
                    ],
                    'index' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => '1',
                        'options' => [
                            '1',
                            '2',
                            '3',
                            '4',
                            'high_variation',
                            'low_variation',
                        ],
                        'description' => 'Index of the image to vary or variation type',
                    ],
                    'prompt' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'min_length' => 1,
                        'max_length' => 4000,
                        'description' => 'Prompt for the variation operation',
                        'default' => 'Make it more colorful and vibrant',
                    ],
                ],
                'response' => [
                    'message' => 'Image variation successful.',
                    'data' => [
                        'task_id' => '660e8400-e29b-41d4-a716-446655440001',
                        'status' => 'completed',
                        'output' => [
                            'images' => [
                                [
                                    'url' => 'https://example.com/variation-image-1.jpg',
                                    'width' => 1024,
                                    'height' => 1024,
                                ],
                                [
                                    'url' => 'https://example.com/variation-image-2.jpg',
                                    'width' => 1024,
                                    'height' => 1024,
                                ],
                            ],
                        ],
                        'meta' => [
                            'parent_task_id' => '550e8400-e29b-41d4-a716-446655440000',
                            'variation_index' => '1',
                            'created_at' => '2024-01-15T10:35:00Z',
                            'processing_time' => 42.8,
                        ],
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.data',
                ],
                'request_class_name' => ImageVariationRequest::class,
                'function_name' => 'imageVariation',
            ],
        ];

        $keptServiceTypeIds = $this->processServiceTypes($serviceProvider, $serviceTypes, 'Midjourney');

        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);

        $this->command->info('Cleanup completed:');
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info('- Kept ' . count($keptServiceTypeIds) . ' service types for Midjourney');
    }
} 