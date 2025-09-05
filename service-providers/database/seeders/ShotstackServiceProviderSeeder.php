<?php

namespace Database\Seeders;

use App\Enums\common\ServiceProviderEnum;
use App\Enums\common\ServiceTypeEnum;
use App\Models\ServiceProvider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShotstackServiceProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => ServiceProviderEnum::SHOTSTACK->value],
            [
                'parameters' => [
                    'api_key' => 'YOUR_API_KEY',
                    'base_url' => 'https://api.shotstack.io',
                    'version' => 'v1',
                    'features' => [
                        'create_asset',
                        'check_render_status',
                        'get_video_metadata',
                    ],
                    'documentation' => [
                        'description' => 'Shotstack is a video asset management platform that allows you to easily create, manage, and deliver high-quality video content to your audience.',
                        'api_documentation' => 'https://shotstack.io/docs/api/#shotstack',
                        'rate_limits' => 'Shotstack API has a rate limit of 100 requests per minute per IP address.',
                        'authentication' => 'API Key required in headers',
                    ],
                ],
                'is_active' => true,
                'controller_name' => ShotstackAPIController::class,
            ]
        );

        $serviceTypes = [
            [
                'name' => ServiceTypeEnum::CREATE_ASSET->value,
                'input_parameters' => [
                    'clips' => [
                        'type' => 'array',
                        'required' => true,
                        'userinput_rqd' => false,
                        'description' => 'Array of timeline tracks and clips',
                        'validation' => 'required|array',
                        'example' => [
                            [
                                "asset" => [
                                    "type" => "text",
                                    "text" => "Hello, world!",
                                    "font" => [
                                        "family" => "Arial",
                                        "size" => 24,
                                        "color" => "#000000",
                                    ],
                                    "alignment" => [
                                        "horizontal" => "left",
                                    ],
                                    "width" => 400,
                                    "height" => 100,
                                ],
                                "start" => 0,
                                "length" => "end",
                                "transition" => [
                                    "in" => "fade",
                                    "out" => "fade",
                                ],
                                "offset" => [
                                    "x" => -0.15,
                                    "y" => 0,
                                ],
                                "effect" => "zoomIn"
                            ],
                            [
                                "asset" => [
                                    "type" => "video",
                                    "src" => "https://example.com/video.mp4",
                                    "trim" => 5,
                                    "volume" => 1,
                                ],
                                "start" => 0,
                                "length" => "auto",
                                "transition" => [
                                    "in" => "fade",
                                    "out" => "fade",
                                ],
                            ],
                            [
                                "asset" => [
                                    "type" => "audio",
                                    "src" => "https://example.com/audio.mp3",
                                    "effect" => "fadeOut",
                                    "volume" => 1,
                                ],
                                "start" => 0,
                                "length" => "auto",
                            ]
                        ]
                    ],
                    "output" => [
                        "type" => "array",
                        "required" => true,
                        "userinput_rqd" => false,
                        "description" => "Object of output parameters",
                        "validation" => "required|array",
                        "example" => [
                            "format" => "mp4",
                            "width" => 400,
                            "height" => 300,
                        ]
                    ],
                ],
                'response' => [
                    'status' => true,
                    'data' => [
                        'id' => 'abf61ec5-0632-43d7-94b3-d0c9c4124acc',
                        'status' => 'queued',
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.data.id',
                    'status' => '$.data.status',
                ],
                'request_class_name' => CreateAssetRequest::class,
                'function_name' => 'createAsset',
            ],
            [
                'name' => ServiceTypeEnum::CHECK_RENDER_STATUS->value,
                'input_parameters' => [
                    'id' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'min_length' => 1,
                        'max_length' => 100,
                        'description' => 'Unique identifier for the asset',
                        'example' => 'abf61ec5-0632-43d7-94b3-d0c9c4124acc',
                        'validation' => 'required|string|uuid',
                    ],
                ],
                'response' => [
                    'status' => true,
                    'data' => [
                        'id' => 'abf61ec5-0632-43d7-94b3-d0c9c4124acc',
                        'status' => 'ready',
                        'url' => 'https://example.com/video.mp4',
                        'metadata' => [],
                        'created_at' => '2023-03-01T12:00:00.000Z',
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.data',
                    'status' => '$.data.status',
                ],
                'request_class_name' => CheckRenderStatusRequest::class,
                'function_name' => 'checkRenderStatus',
            ],
            [
                'name' => ServiceTypeEnum::GET_VIDEO_METADATA->value,
                'input_parameters' => [
                    'id' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'min_length' => 1,
                        'max_length' => 100,
                        'description' => 'Unique identifier for the asset',
                        'example' => 'abf61ec5-0632-43d7-94b3-d0c9c4124acc',
                        'validation' => 'required|string|uuid',
                    ],
                ],
                'response' => [
                    'status' => true,
                    'data' => [
                        'id' => 'abf61ec5-0632-43d7-94b3-d0c9c4124acc',
                        'name' => 'My Asset',
                        'description' => 'This is my asset',
                        'type' => 'image',
                        'url' => 'https://example.com/my-asset.png',
                        'created_at' => '2023-03-14T12:00:00.000000Z',
                        'updated_at' => '2023-03-14T12:00:00.000000Z',
                        'error' => null,
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.data',
                    'name' => '$.data.name',
                    'description' => '$.data.description',
                    'type' => '$.data.type',
                    'url' => '$.data.url',
                    'created_at' => '$.data.created_at',
                    'updated_at' => '$.data.updated_at',
                    'error_message' => '$.data.error',
                ],
                'request_class_name' => GetVideoMetadataRequest::class,
                'function_name' => 'getVideoMetadata',
            ],
        ];
    }
}
