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
                        'default' => '550e8400-e29b-41d4-a716-446655440000',
                        'description' => 'UUID of the template to use for image generation',
                    ],
                    'layers' => [
                        'type' => 'array',
                        'required' => true,
                        'default' => [
                            [
                                'name' => 'title',
                                'text' => 'Sample Title',
                            ],
                            [
                                'name' => 'subtitle',
                                'text' => 'Sample Subtitle',
                            ],
                        ],
                        'description' => 'Array of layers with content to be applied to the template',
                    ],
                ],
                'response' => [
                    'image_url' => 'https://placid.app/u/abc123/generated-image.png',
                    'status' => 'success',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => ImageGenerationRequest::class,
                'function_name' => 'imageGeneration',
            ],
            [
                'name' => 'Retrieve Template',
                'input_parameters' => [
                    'template_uuid' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => '550e8400-e29b-41d4-a716-446655440000',
                        'description' => 'UUID of the template to retrieve',
                    ],
                ],
                'response' => [
                    'template_uuid' => '550e8400-e29b-41d4-a716-446655440000',
                    'name' => 'Sample Template',
                    'description' => 'A sample template for demonstrations',
                    'width' => 1920,
                    'height' => 1080,
                    'layers' => [
                        [
                            'name' => 'title',
                            'type' => 'text',
                            'x' => 100,
                            'y' => 100,
                            'width' => 800,
                            'height' => 100,
                        ],
                        [
                            'name' => 'subtitle',
                            'type' => 'text',
                            'x' => 100,
                            'y' => 220,
                            'width' => 800,
                            'height' => 50,
                        ],
                    ],
                    'created_at' => '2024-01-01T12:00:00Z',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => RetrieveTemplateRequest::class,
                'function_name' => 'retrieveTemplate',
            ],
            [
                'name' => 'Video Generation',
                'input_parameters' => [
                    'clips' => [
                        'type' => 'array',
                        'required' => true,
                        'default' => [
                            [
                                'template_uuid' => '550e8400-e29b-41d4-a716-446655440000',
                                'layers' => [
                                    [
                                        'name' => 'title',
                                        'text' => 'Video Clip Title',
                                    ],
                                ],
                                'duration' => 5,
                            ],
                        ],
                        'description' => 'Array of video clips with templates and content',
                    ],
                ],
                'response' => [
                    'video_id' => 67890,
                    'status' => 'processing',
                    'created_at' => '2024-01-15T10:30:00Z',
                    'estimated_completion' => '2024-01-15T10:35:00Z',
                ],
                'response_path' => [
                    'final_result' => '$',
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
                        'default' => 67890,
                        'min' => 1,
                        'description' => 'ID of the video to retrieve',
                    ],
                ],
                'response' => [
                    'video_id' => 67890,
                    'status' => 'completed',
                    'video_url' => 'https://placid.app/u/abc123/generated-video.mp4',
                    'thumbnail_url' => 'https://placid.app/u/abc123/generated-video-thumb.jpg',
                    'created_at' => '2024-01-15T10:30:00Z',
                    'completed_at' => '2024-01-15T10:35:00Z',
                    'duration' => 30,
                    'file_size' => 5120000,
                    'resolution' => '1920x1080',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => RetrieveVideoRequest::class,
                'function_name' => 'retrieveVideo',
            ],
            [
                'name' => 'PDF Generation',
                'input_parameters' => [
                    'template_uuid' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => '550e8400-e29b-41d4-a716-446655440000',
                        'description' => 'UUID of the template to use for PDF generation',
                    ],
                    'layers' => [
                        'type' => 'array',
                        'required' => true,
                        'default' => [
                            [
                                'name' => 'title',
                                'text' => 'Sample PDF Title',
                            ],
                            [
                                'name' => 'content',
                                'text' => 'Sample PDF Content',
                            ],
                        ],
                        'description' => 'Array of layers with content to be applied to the template',
                    ],
                ],
                'response' => [
                    'pdf_id' => 12345,
                    'status' => 'processing',
                    'created_at' => '2024-01-15T10:30:00Z',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => ImageGenerationRequest::class,
                'function_name' => 'pdfGeneration',
            ],
            [
                'name' => 'Retrieve PDF',
                'input_parameters' => [
                    'pdf_id' => [
                        'type' => 'integer',
                        'required' => true,
                        'default' => 12345,
                        'min' => 1,
                        'description' => 'ID of the PDF to retrieve',
                    ],
                ],
                'response' => [
                    'pdf_id' => 12345,
                    'status' => 'completed',
                    'pdf_url' => 'https://placid.app/u/abc123/generated-document.pdf',
                    'created_at' => '2024-01-15T10:30:00Z',
                    'completed_at' => '2024-01-15T10:32:00Z',
                    'file_size' => 1024000,
                    'pages' => 1,
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => RetrievePdfRequest::class,
                'function_name' => 'retrievePdf',
            ],
        ];

        $keptServiceTypeIds = $this->processServiceTypes($serviceProvider, $serviceTypes, 'Placid');

        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);

        $this->command->info('Cleanup completed:');
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info('- Kept ' . count($keptServiceTypeIds) . ' service types for Placid');
    }
}
