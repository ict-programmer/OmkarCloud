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
                    'endpoints' => [
                        'images' => '/api/rest/images',
                        'pdfs' => '/api/rest/pdfs',
                        'videos' => '/api/rest/videos',
                        'templates' => '/api/rest/templates',
                    ],
                    'features' => [
                        'image_generation',
                        'pdf_generation',
                        'video_generation',
                        'template_management',
                        'webhook_support',
                        'async_processing',
                        's3_transfer',
                        'custom_modifications',
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
                        'min_length' => 8,
                        'max_length' => 50,
                        'description' => 'Template UUID to be used for image creation',
                    ],
                    'layers' => [
                        'type' => 'object',
                        'required' => true,
                        'description' => 'Layer data to customize the template',
                        'properties' => [
                            'text_layers' => [
                                'type' => 'object',
                                'description' => 'Text content for text layers',
                            ],
                            'image_layers' => [
                                'type' => 'object',
                                'description' => 'Image URLs for image layers',
                            ],
                        ],
                    ],
                    'create_now' => [
                        'type' => 'boolean',
                        'required' => false,
                        'default' => false,
                        'description' => 'Process the image instantly instead of queueing it',
                    ],
                    'webhook_success' => [
                        'type' => 'string',
                        'required' => false,
                        'format' => 'url',
                        'description' => 'Webhook URL to be called after successful generation',
                    ],
                    'passthrough' => [
                        'type' => 'string',
                        'required' => false,
                        'max_length' => 1024,
                        'description' => 'Custom data to be passed through webhooks',
                    ],
                    'modifications' => [
                        'type' => 'object',
                        'required' => false,
                        'properties' => [
                            'width' => [
                                'type' => 'integer',
                                'min' => 1,
                                'max' => 4000,
                            ],
                            'height' => [
                                'type' => 'integer',
                                'min' => 1,
                                'max' => 4000,
                            ],
                            'filename' => [
                                'type' => 'string',
                                'max_length' => 255,
                            ],
                        ],
                        'description' => 'Image size and filename modifications',
                    ],
                    'transfer' => [
                        'type' => 'object',
                        'required' => false,
                        'properties' => [
                            'to' => [
                                'type' => 'string',
                                'options' => ['s3', 'gcs', 'ftp'],
                            ],
                            'bucket' => ['type' => 'string'],
                            'key' => ['type' => 'string'],
                            'secret' => ['type' => 'string'],
                            'region' => ['type' => 'string'],
                            'path' => ['type' => 'string'],
                        ],
                        'description' => 'Transfer settings for external storage',
                    ],
                ],
                'response' => [
                    'id' => 12345,
                    'status' => 'finished',
                    'image_url' => 'https://placid.app/storage/images/generated_image_12345.png',
                    'errors' => [],
                    'template_uuid' => 'ospo24ysn',
                    'layers' => [
                        'img' => [
                            'image' => 'https://faywoodwildlife.com/images/lion-singh.jpg',
                        ],
                        'subline' => [
                            'text' => 'Employee of the month',
                        ],
                        'title' => [
                            'text' => 'Meet Singh',
                        ],
                    ],
                    'modifications' => [
                        'width' => 1200,
                        'height' => 630,
                        'filename' => 'custom-image.png',
                    ],
                    'created_at' => '2024-01-15T10:30:00Z',
                    'finished_at' => '2024-01-15T10:30:05Z',
                ],
                'response_path' => [
                    'final_result' => '$',
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
                        'min_length' => 8,
                        'max_length' => 50,
                        'description' => 'Template UUID to be used for PDF creation',
                    ],
                    'layers' => [
                        'type' => 'object',
                        'required' => true,
                        'description' => 'Layer data to customize the PDF template',
                        'properties' => [
                            'text_layers' => [
                                'type' => 'object',
                                'description' => 'Text content for text layers',
                            ],
                            'image_layers' => [
                                'type' => 'object',
                                'description' => 'Image URLs for image layers',
                            ],
                        ],
                    ],
                    'create_now' => [
                        'type' => 'boolean',
                        'required' => false,
                        'default' => false,
                        'description' => 'Process the PDF instantly instead of queueing it',
                    ],
                    'webhook_success' => [
                        'type' => 'string',
                        'required' => false,
                        'format' => 'url',
                        'description' => 'Webhook URL to be called after successful generation',
                    ],
                    'modifications' => [
                        'type' => 'object',
                        'required' => false,
                        'properties' => [
                            'filename' => [
                                'type' => 'string',
                                'max_length' => 255,
                            ],
                            'format' => [
                                'type' => 'string',
                                'options' => ['A4', 'A3', 'Letter', 'Legal'],
                                'default' => 'A4',
                            ],
                        ],
                        'description' => 'PDF format and filename modifications',
                    ],
                ],
                'response' => [
                    'id' => 12346,
                    'status' => 'finished',
                    'pdf_url' => 'https://placid.app/storage/pdfs/generated_pdf_12346.pdf',
                    'errors' => [],
                    'template_uuid' => 'pdf123abc',
                    'layers' => [
                        'title' => [
                            'text' => 'Monthly Report',
                        ],
                        'subtitle' => [
                            'text' => 'January 2024',
                        ],
                        'company_logo' => [
                            'image' => 'https://company.com/logo.png',
                        ],
                    ],
                    'modifications' => [
                        'filename' => 'monthly-report-jan-2024.pdf',
                        'format' => 'A4',
                    ],
                    'created_at' => '2024-01-15T10:35:00Z',
                    'finished_at' => '2024-01-15T10:35:08Z',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => ImageGenerationRequest::class,
                'function_name' => 'pdfGeneration',
            ],
            [
                'name' => 'Video Generation',
                'input_parameters' => [
                    'clips' => [
                        'type' => 'array',
                        'required' => true,
                        'min_items' => 1,
                        'max_items' => 20,
                        'description' => 'Array of video clips to generate',
                        'items' => [
                            'type' => 'object',
                            'properties' => [
                                'template_uuid' => [
                                    'type' => 'string',
                                    'required' => true,
                                ],
                                'layers' => [
                                    'type' => 'object',
                                    'required' => true,
                                ],
                                'duration' => [
                                    'type' => 'integer',
                                    'min' => 1,
                                    'max' => 300,
                                ],
                            ],
                        ],
                    ],
                    'create_now' => [
                        'type' => 'boolean',
                        'required' => false,
                        'default' => false,
                        'description' => 'Process the video instantly instead of queueing it',
                    ],
                    'webhook_success' => [
                        'type' => 'string',
                        'required' => false,
                        'format' => 'url',
                        'description' => 'Webhook URL to be called after successful generation',
                    ],
                    'modifications' => [
                        'type' => 'object',
                        'required' => false,
                        'properties' => [
                            'width' => [
                                'type' => 'integer',
                                'min' => 1,
                                'max' => 4000,
                            ],
                            'height' => [
                                'type' => 'integer',
                                'min' => 1,
                                'max' => 4000,
                            ],
                            'format' => [
                                'type' => 'string',
                                'options' => ['mp4', 'mov', 'avi'],
                                'default' => 'mp4',
                            ],
                            'fps' => [
                                'type' => 'integer',
                                'options' => [24, 25, 30, 60],
                                'default' => 30,
                            ],
                        ],
                        'description' => 'Video format and quality modifications',
                    ],
                ],
                'response' => [
                    'id' => 12347,
                    'status' => 'finished',
                    'video_url' => 'https://placid.app/storage/videos/generated_video_12347.mp4',
                    'errors' => [],
                    'clips' => [
                        [
                            'template_uuid' => 'illcmemnt',
                            'layers' => [
                                'video' => [
                                    'video' => 'https://socialmediacollection.com/assets/video-tiktok-1.mp4',
                                ],
                                'logo' => [
                                    'image' => 'https://socialmediacollection.com/assets/logo.png',
                                ],
                                'username' => [
                                    'text' => '@username',
                                ],
                            ],
                            'duration' => 10,
                        ],
                    ],
                    'modifications' => [
                        'width' => 1080,
                        'height' => 1920,
                        'format' => 'mp4',
                        'fps' => 30,
                    ],
                    'total_duration' => 10,
                    'created_at' => '2024-01-15T10:40:00Z',
                    'finished_at' => '2024-01-15T10:40:45Z',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => VideoGenerationRequest::class,
                'function_name' => 'videoGeneration',
            ],
            [
                'name' => 'Retrieve Template',
                'input_parameters' => [
                    'template_uuid' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 8,
                        'max_length' => 50,
                        'description' => 'Template UUID to retrieve information for',
                    ],
                ],
                'response' => [
                    'uuid' => 'ospo24ysn',
                    'name' => 'Social Media Post Template',
                    'description' => 'Template for creating social media posts with image and text',
                    'type' => 'image',
                    'width' => 1200,
                    'height' => 630,
                    'layers' => [
                        [
                            'name' => 'img',
                            'type' => 'image',
                            'required' => true,
                            'description' => 'Main background image',
                        ],
                        [
                            'name' => 'title',
                            'type' => 'text',
                            'required' => true,
                            'max_length' => 100,
                            'description' => 'Main title text',
                        ],
                        [
                            'name' => 'subline',
                            'type' => 'text',
                            'required' => false,
                            'max_length' => 200,
                            'description' => 'Subtitle or description text',
                        ],
                    ],
                    'created_at' => '2024-01-10T08:00:00Z',
                    'updated_at' => '2024-01-12T10:30:00Z',
                ],
                'response_path' => [
                    'final_result' => '$',
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
                        'min' => 1,
                        'description' => 'PDF ID to retrieve',
                    ],
                ],
                'response' => [
                    'id' => 12346,
                    'status' => 'finished',
                    'pdf_url' => 'https://placid.app/storage/pdfs/generated_pdf_12346.pdf',
                    'template_uuid' => 'pdf123abc',
                    'filename' => 'monthly-report-jan-2024.pdf',
                    'file_size' => 2048576,
                    'pages' => 5,
                    'layers' => [
                        'title' => [
                            'text' => 'Monthly Report',
                        ],
                        'subtitle' => [
                            'text' => 'January 2024',
                        ],
                    ],
                    'created_at' => '2024-01-15T10:35:00Z',
                    'finished_at' => '2024-01-15T10:35:08Z',
                    'expires_at' => '2024-02-15T10:35:08Z',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => RetrievePdfRequest::class,
                'function_name' => 'retrievePdf',
            ],
            [
                'name' => 'Retrieve Video',
                'input_parameters' => [
                    'video_id' => [
                        'type' => 'integer',
                        'required' => true,
                        'min' => 1,
                        'description' => 'Video ID to retrieve',
                    ],
                ],
                'response' => [
                    'id' => 12347,
                    'status' => 'finished',
                    'video_url' => 'https://placid.app/storage/videos/generated_video_12347.mp4',
                    'thumbnail_url' => 'https://placid.app/storage/thumbnails/video_12347_thumb.jpg',
                    'filename' => 'social-media-video.mp4',
                    'file_size' => 15728640,
                    'duration' => 10,
                    'width' => 1080,
                    'height' => 1920,
                    'fps' => 30,
                    'format' => 'mp4',
                    'clips_count' => 1,
                    'created_at' => '2024-01-15T10:40:00Z',
                    'finished_at' => '2024-01-15T10:40:45Z',
                    'expires_at' => '2024-02-15T10:40:45Z',
                ],
                'response_path' => [
                    'final_result' => '$',
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
