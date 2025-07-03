<?php

namespace Database\Seeders;

use App\Http\Controllers\MidjourneyController;
use App\Http\Requests\Midjourney\ImageGenerationRequest;
use App\Http\Requests\Midjourney\ImageVariationRequest;
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
                        'default' => 'A futuristic cityscape at sunset with flying cars and neon lights, photorealistic, 8k quality',
                        'min_length' => 1,
                        'max_length' => 2000,
                        'description' => 'Text description of the image to generate',
                    ],
                    'aspect_ratio' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => '1:1',
                        'description' => 'Aspect ratio of the generated image',
                    ],
                ],
                'response' => [
                    'task_id' => 'task_abc123def456',
                    'status' => 'completed',
                    'output' => [
                        'images' => [
                            'https://cdn.piapi.ai/output/image1.jpg',
                            'https://cdn.piapi.ai/output/image2.jpg',
                            'https://cdn.piapi.ai/output/image3.jpg',
                            'https://cdn.piapi.ai/output/image4.jpg',
                        ],
                    ],
                    'meta' => [
                        'prompt' => 'A futuristic cityscape at sunset with flying cars and neon lights, photorealistic, 8k quality',
                        'aspect_ratio' => '1:1',
                        'created_at' => '2024-01-15T10:30:00Z',
                        'completed_at' => '2024-01-15T10:32:00Z',
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
                        'default' => 'task_abc123def456',
                        'description' => 'Task ID of the original image generation task',
                    ],
                    'index' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => '1',
                        'description' => 'Index of the image to create variations from (1-4)',
                    ],
                    'prompt' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'Create variations of this futuristic cityscape with different lighting and colors',
                        'min_length' => 1,
                        'max_length' => 2000,
                        'description' => 'Additional prompt for the variation',
                    ],
                ],
                'response' => [
                    'task_id' => 'task_variation_xyz789',
                    'status' => 'completed',
                    'output' => [
                        'images' => [
                            'https://cdn.piapi.ai/output/variation1.jpg',
                            'https://cdn.piapi.ai/output/variation2.jpg',
                            'https://cdn.piapi.ai/output/variation3.jpg',
                            'https://cdn.piapi.ai/output/variation4.jpg',
                        ],
                    ],
                    'meta' => [
                        'origin_task_id' => 'task_abc123def456',
                        'selected_index' => '1',
                        'variation_prompt' => 'Create variations of this futuristic cityscape with different lighting and colors',
                        'created_at' => '2024-01-15T10:35:00Z',
                        'completed_at' => '2024-01-15T10:37:00Z',
                    ],
                ],
                'response_path' => [
                    'final_result' => '$',
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