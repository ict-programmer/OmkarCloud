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
            [
                'name' => 'Image Generation',
                'input_parameters' => [
                    'template_uuid' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'UUID of the template to use for image generation',
                        'default' => 'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
                    ],
                    'layers' => [
                        'type' => 'array',
                        'required' => true,
                        'description' => 'Array of layer configurations for the image',
                        'default' => [
                            [
                                'name' => 'text_layer',
                                'text' => 'Hello World',
                                'color' => '#000000',
                            ],
                        ],
                    ],
                ],
                'response' => [
                    'message' => 'Image generation successful.',
                    'data' => 'https://placid.app/generated-image-url.jpg',
                ],
                'response_path' => [
                    'final_result' => '$.data',
                ],
                'request_class_name' => ImageGenerationRequest::class,
                'function_name' => 'imageGeneration',
            ],
            [
                'name' => 'PDF Generation',
                'input_parameters' => [
                    'template_uuid' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'UUID of the template to use for PDF generation',
                        'default' => 'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
                    ],
                    'layers' => [
                        'type' => 'array',
                        'required' => true,
                        'description' => 'Array of layer configurations for the PDF pages',
                        'default' => [
                            [
                                'name' => 'text_layer',
                                'text' => 'Sample PDF Content',
                                'color' => '#000000',
                            ],
                        ],
                    ],
                ],
                'response' => [
                    'message' => 'PDF generation successful.',
                    'data' => 12345,
                ],
                'response_path' => [
                    'final_result' => '$.data',
                ],
                'request_class_name' => ImageGenerationRequest::class,
                'function_name' => 'pdfGeneration',
            ],
            [
                'name' => 'Retrieve Template',
                'input_parameters' => [
                    'template_uuid' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'UUID of the template to retrieve',
                        'default' => 'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
                    ],
                ],
                'response' => [
                    'message' => 'Template retrieved successfully.',
                    'data' => [
                        'uuid' => 'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
                        'name' => 'Sample Template',
                        'width' => 800,
                        'height' => 600,
                        'layers' => [
                            [
                                'name' => 'background',
                                'type' => 'image',
                            ],
                            [
                                'name' => 'text_layer',
                                'type' => 'text',
                            ],
                        ],
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.data',
                ],
                'request_class_name' => RetrieveTemplateRequest::class,
                'function_name' => 'retrieveTemplate',
            ],
            [
                'name' => 'Retrieve PDF',
                'input_parameters' => [
                    'pdf_id' => [
                        'type' => 'integer',
                        'required' => true,
                        'description' => 'ID of the PDF to retrieve',
                        'default' => 12345,
                    ],
                ],
                'response' => [
                    'message' => 'Pdf retrieved successfully.',
                    'data' => [
                        'id' => 12345,
                        'status' => 'completed',
                        'url' => 'https://placid.app/generated-pdf-url.pdf',
                        'pages' => 1,
                        'created_at' => '2024-01-15T10:30:00Z',
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.data',
                ],
                'request_class_name' => RetrievePdfRequest::class,
                'function_name' => 'retrievePdf',
            ],
            [
                'name' => 'Video Generation',
                'input_parameters' => [
                    'clips' => [
                        'type' => 'array',
                        'required' => true,
                        'description' => 'Array of video clips configuration',
                        'default' => [
                            [
                                'template_uuid' => 'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
                                'duration' => 5,
                                'layers' => [
                                    [
                                        'name' => 'text_layer',
                                        'text' => 'Video Content',
                                        'color' => '#FFFFFF',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'response' => [
                    'message' => 'Video generation successful.',
                    'data' => [
                        'id' => 67890,
                        'status' => 'processing',
                        'estimated_completion' => '2024-01-15T10:35:00Z',
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.data',
                ],
                'request_class_name' => VideoGenerationRequest::class,
                'function_name' => 'videoGeneration',
            ],
            [
                'name' => 'Retrieve Video',
                'input_parameters' => [
                    'video_id' => [
                        'type' => 'integer',
                        'required' => true,
                        'description' => 'ID of the video to retrieve',
                        'default' => 67890,
                    ],
                ],
                'response' => [
                    'message' => 'Video retrieved successfully.',
                    'data' => [
                        'id' => 67890,
                        'status' => 'completed',
                        'url' => 'https://placid.app/generated-video-url.mp4',
                        'duration' => 5,
                        'format' => 'mp4',
                        'created_at' => '2024-01-15T10:30:00Z',
                        'completed_at' => '2024-01-15T10:35:00Z',
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.data',
                ],
                'request_class_name' => RetrieveVideoRequest::class,
                'function_name' => 'retrieveVideo',
            ],
        ];

        $keptServiceTypeIds = $this->processServiceTypes($serviceProvider, $serviceTypes, 'Placid');

        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);

        $this->command->info('Cleanup completed:');
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info('- Kept ' . count($keptServiceTypeIds) . ' service types for Placid');
    }
}
