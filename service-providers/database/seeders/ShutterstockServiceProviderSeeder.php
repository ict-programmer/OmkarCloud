<?php

namespace Database\Seeders;

use App\Http\Controllers\ShutterstockController;
use App\Http\Requests\Shutterstock\AddToCollectionRequest;
use App\Http\Requests\Shutterstock\CreateCollectionRequest;
use App\Http\Requests\Shutterstock\DownloadAudioRequest;
use App\Http\Requests\Shutterstock\DownloadImageRequest;
use App\Http\Requests\Shutterstock\DownloadVideoRequest;
use App\Http\Requests\Shutterstock\GetAudioRequest;
use App\Http\Requests\Shutterstock\GetImageRequest;
use App\Http\Requests\Shutterstock\GetVideoRequest;
use App\Http\Requests\Shutterstock\LicenseAudioRequest;
use App\Http\Requests\Shutterstock\LicenseImageRequest;
use App\Http\Requests\Shutterstock\LicenseVideoRequest;
use App\Http\Requests\Shutterstock\SearchAudioRequest;
use App\Http\Requests\Shutterstock\SearchImagesRequest;
use App\Http\Requests\Shutterstock\SearchVideosRequest;
use App\Models\ServiceProvider;
use App\Traits\ServiceProviderSeederTrait;
use Illuminate\Database\Seeder;

class ShutterstockServiceProviderSeeder extends Seeder
{
    use ServiceProviderSeederTrait;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => 'Shutterstock'],
            [
                'parameters' => [
                    'api_key' => 'YOUR_SHUTTERSTOCK_API_TOKEN',
                    'base_url' => 'https://api.shutterstock.com',
                    'version' => 'v2',
                    'features' => [
                        'create_collection',
                        'add_to_collection',
                        'search_images',
                        'get_image',
                        'license_image',
                        'download_image',
                        'search_videos',
                        'get_video',
                        'license_video',
                        'download_video',
                        'search_audio',
                        'get_audio',
                        'license_audio',
                        'download_audio',
                        'list_user_subscriptions',
                    ],
                ],
                'is_active' => true,
                'controller_name' => ShutterstockController::class,
            ]
        );

        $serviceTypes = [
            [
                'name' => 'Create Collection',
                'input_parameters' => [
                    'name' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => 'My New Collection',
                        'description' => 'Name of the collection to create',
                    ],
                ],
                'response' => [
                    'message' => 'Collection created successfully.',
                    'data' => [
                        'id' => '12345678',
                        'name' => 'My New Collection',
                        'total_item_count' => 0,
                        'items_updated_time' => '2024-01-15T10:30:00.000Z',
                        'cover_item' => null,
                        'created_time' => '2024-01-15T10:30:00.000Z',
                        'updated_time' => '2024-01-15T10:30:00.000Z',
                    ],
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => CreateCollectionRequest::class,
                'function_name' => 'createCollection',
            ],
            [
                'name' => 'Add To Collection',
                'input_parameters' => [
                    'collection_id' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => '12345678',
                        'description' => 'ID of the collection to add items to',
                    ],
                    'items' => [
                        'type' => 'array',
                        'userinput_rqd' => true,
                        'required' => true,
                        'default' => [
                            [
                                'id' => '1234567890',
                                'media_type' => 'image',
                            ],
                        ],
                        'description' => 'Array of items to add to the collection',
                    ],
                ],
                'response' => [
                    'message' => 'Item added to collection successfully.',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => AddToCollectionRequest::class,
                'function_name' => 'addToCollection',
            ],
            [
                'name' => 'Search Images',
                'input_parameters' => [
                    'query' => [
                        'userinput_rqd' => true,
                        'type' => 'string',
                        'required' => true,
                        'default' => 'business team',
                        'description' => 'Search query for images',
                    ],
                    'orientation' => [
                        'userinput_rqd' => true,
                        'type' => 'string',
                        'required' => true,
                        'default' => 'horizontal',
                        'options' => [
                            'source' => 'static',
                            'static_options' => [
                                'horizontal',
                                'vertical',
                                'square',
                            ],
                        ],
                        'description' => 'Image orientation filter',
                    ],
                ],
                'response' => [
                    'message' => 'Images search successful.',
                    'data' => [
                        'data' => [
                            [
                                'id' => '1234567890',
                                'aspect' => 1.78,
                                'assets' => [
                                    'preview' => [
                                        'height' => 300,
                                        'url' => 'https://image.shutterstock.com/z/stock-photo-business-team-1234567890.jpg',
                                        'width' => 450,
                                    ],
                                    'small_thumb' => [
                                        'height' => 67,
                                        'url' => 'https://thumb1.shutterstock.com/thumb_small/1234567890/1234567890.jpg',
                                        'width' => 100,
                                    ],
                                    'large_thumb' => [
                                        'height' => 150,
                                        'url' => 'https://thumb1.shutterstock.com/thumb_large/1234567890/1234567890.jpg',
                                        'width' => 150,
                                    ],
                                    'huge_thumb' => [
                                        'height' => 260,
                                        'url' => 'https://image.shutterstock.com/image-photo/business-team-working-together-modern-260nw-1234567890.jpg',
                                        'width' => 390,
                                    ],
                                ],
                                'contributor' => [
                                    'id' => '12345',
                                ],
                                'description' => 'Business team working together in modern office',
                                'image_type' => 'photo',
                                'has_model_release' => true,
                                'has_property_release' => true,
                                'keywords' => [
                                    'business',
                                    'team',
                                    'office',
                                    'corporate',
                                ],
                                'categories' => [
                                    [
                                        'id' => '1',
                                        'name' => 'Business/Finance',
                                    ],
                                ],
                                'media_type' => 'image',
                            ],
                        ],
                        'page' => 1,
                        'per_page' => 20,
                        'total_count' => 1250000,
                        'search_id' => 'search_123456789',
                    ],
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => SearchImagesRequest::class,
                'function_name' => 'searchImages',
            ],
            [
                'name' => 'Get Image',
                'input_parameters' => [
                    'image_id' => [
                        'type' => 'string',
                        'userinput_rqd' => true,
                        'required' => true,
                        'default' => '1234567890',
                        'description' => 'ID of the image to retrieve',
                    ],
                ],
                'response' => [
                    'message' => 'Image details retrieved successfully.',
                    'data' => [
                        'id' => '1234567890',
                        'aspect' => 1.78,
                        'assets' => [
                            'preview' => [
                                'height' => 300,
                                'url' => 'https://image.shutterstock.com/z/stock-photo-business-team-1234567890.jpg',
                                'width' => 450,
                            ],
                            'small_thumb' => [
                                'height' => 67,
                                'url' => 'https://thumb1.shutterstock.com/thumb_small/1234567890/1234567890.jpg',
                                'width' => 100,
                            ],
                            'large_thumb' => [
                                'height' => 150,
                                'url' => 'https://thumb1.shutterstock.com/thumb_large/1234567890/1234567890.jpg',
                                'width' => 150,
                            ],
                            'huge_thumb' => [
                                'height' => 260,
                                'url' => 'https://image.shutterstock.com/image-photo/business-team-working-together-modern-260nw-1234567890.jpg',
                                'width' => 390,
                            ],
                        ],
                        'contributor' => [
                            'id' => '12345',
                        ],
                        'description' => 'Business team working together in modern office',
                        'image_type' => 'photo',
                        'has_model_release' => true,
                        'has_property_release' => true,
                        'keywords' => [
                            'business',
                            'team',
                            'office',
                            'corporate',
                        ],
                        'categories' => [
                            [
                                'id' => '1',
                                'name' => 'Business/Finance',
                            ],
                        ],
                        'media_type' => 'image',
                        'added_date' => '2024-01-01',
                        'affiliate_url' => 'https://www.shutterstock.com/image-photo/business-team-working-together-modern-office-1234567890',
                    ],
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => GetImageRequest::class,
                'function_name' => 'getImage',
            ],
            [
                'name' => 'License Image',
                'input_parameters' => [
                    'image_id' => [
                        'userinput_rqd' => true,
                        'type' => 'string',
                        'required' => true,
                        'default' => '1234567890',
                        'description' => 'ID of the image to license',
                    ],
                ],
                'response' => [
                    'message' => 'Image licensed successfully.',
                    'data' => [
                        'data' => [
                            [
                                'image_id' => '1234567890',
                                'download' => [
                                    'url' => 'https://download.shutterstock.com/gatekeeper/W3siZSI6MTYwNzUyMDAwMCwiayI6InBob3RvLXNlcnZpY2UvcHJvZHVjdGlvbi...',
                                ],
                                'license_id' => 'lic_12345678901234567890',
                                'allotment_charge' => 1,
                                'price' => [
                                    'local_amount' => 0,
                                    'local_currency' => 'USD',
                                ],
                            ],
                        ],
                        'errors' => [],
                    ],
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => LicenseImageRequest::class,
                'function_name' => 'licenseImage',
            ],
            [
                'name' => 'Download Image',
                'input_parameters' => [
                    'license_id' => [
                        'type' => 'string',
                        'userinput_rqd' => true,
                        'required' => true,
                        'default' => 'lic_12345678901234567890',
                        'description' => 'License ID from the licensing step',
                    ],
                ],
                'response' => [
                    'message' => 'Download link generated successfully.',
                    'data' => [
                        'url' => 'https://download.shutterstock.com/gatekeeper/W3siZSI6MTYwNzUyMDAwMCwiayI6InBob3RvLXNlcnZpY2UvcHJvZHVjdGlvbi...',
                    ],
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => DownloadImageRequest::class,
                'function_name' => 'downloadImage',
            ],
            [
                'name' => 'Search Videos',
                'input_parameters' => [
                    'query' => [
                        'type' => 'string',
                        'userinput_rqd' => true,
                        'required' => true,
                        'default' => 'business meeting',
                        'description' => 'Search query for videos',
                    ],
                    'orientation' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => 'horizontal',
                        'options' => [
                            'source' => 'static',
                            'static_options' => [
                                'horizontal',
                                'vertical',
                                'square',
                            ],
                        ],
                        'description' => 'Video orientation filter',
                    ],
                ],
                'response' => [
                    'message' => 'Videos search successful.',
                    'data' => [
                        'data' => [
                            [
                                'id' => '1234567890',
                                'aspect' => 1.78,
                                'assets' => [
                                    'preview_mp4' => [
                                        'url' => 'https://ak.picdn.net/shutterstock/videos/1234567890/preview/stock-footage-business-meeting.mp4',
                                    ],
                                    'preview_webm' => [
                                        'url' => 'https://ak.picdn.net/shutterstock/videos/1234567890/preview/stock-footage-business-meeting.webm',
                                    ],
                                    'thumb_webm' => [
                                        'url' => 'https://ak.picdn.net/shutterstock/videos/1234567890/thumb/stock-footage-business-meeting.webm',
                                    ],
                                    'thumb_mp4' => [
                                        'url' => 'https://ak.picdn.net/shutterstock/videos/1234567890/thumb/stock-footage-business-meeting.mp4',
                                    ],
                                    'thumb_jpg' => [
                                        'url' => 'https://ak.picdn.net/shutterstock/videos/1234567890/thumb/stock-footage-business-meeting.jpg',
                                    ],
                                ],
                                'contributor' => [
                                    'id' => '12345',
                                ],
                                'description' => 'Business people having a meeting in conference room',
                                'duration' => 15.5,
                                'fps' => 30,
                                'has_model_release' => true,
                                'has_property_release' => true,
                                'categories' => [
                                    [
                                        'id' => '1',
                                        'name' => 'Business/Finance',
                                    ],
                                ],
                                'media_type' => 'video',
                            ],
                        ],
                        'page' => 1,
                        'per_page' => 20,
                        'total_count' => 850000,
                        'search_id' => 'search_123456789',
                    ],
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => SearchVideosRequest::class,
                'function_name' => 'searchVideos',
            ],
            [
                'name' => 'Get Video',
                'input_parameters' => [
                    'video_id' => [
                        'type' => 'string',
                        'userinput_rqd' => true,
                        'required' => true,
                        'default' => '1234567890',
                        'description' => 'ID of the video to retrieve',
                    ],
                ],
                'response' => [
                    'message' => 'Video details retrieved successfully.',
                    'data' => [
                        'id' => '1234567890',
                        'aspect' => 1.78,
                        'assets' => [
                            'preview_mp4' => [
                                'url' => 'https://ak.picdn.net/shutterstock/videos/1234567890/preview/stock-footage-business-meeting.mp4',
                            ],
                            'preview_webm' => [
                                'url' => 'https://ak.picdn.net/shutterstock/videos/1234567890/preview/stock-footage-business-meeting.webm',
                            ],
                            'thumb_webm' => [
                                'url' => 'https://ak.picdn.net/shutterstock/videos/1234567890/thumb/stock-footage-business-meeting.webm',
                            ],
                            'thumb_mp4' => [
                                'url' => 'https://ak.picdn.net/shutterstock/videos/1234567890/thumb/stock-footage-business-meeting.mp4',
                            ],
                            'thumb_jpg' => [
                                'url' => 'https://ak.picdn.net/shutterstock/videos/1234567890/thumb/stock-footage-business-meeting.jpg',
                            ],
                        ],
                        'contributor' => [
                            'id' => '12345',
                        ],
                        'description' => 'Business people having a meeting in conference room',
                        'duration' => 15.5,
                        'fps' => 30,
                        'has_model_release' => true,
                        'has_property_release' => true,
                        'categories' => [
                            [
                                'id' => '1',
                                'name' => 'Business/Finance',
                            ],
                        ],
                        'media_type' => 'video',
                        'added_date' => '2024-01-01',
                        'affiliate_url' => 'https://www.shutterstock.com/video/clip-1234567890-business-people-having-meeting-conference-room',
                    ],
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => GetVideoRequest::class,
                'function_name' => 'getVideo',
            ],
            [
                'name' => 'License Video',
                'input_parameters' => [
                    'videos' => [
                        'type' => 'array',
                        'userinput_rqd' => true,
                        'required' => true,
                        'default' => [
                            [
                                'video_id' => '1234567890',
                                'size' => 'hd',
                            ],
                        ],
                        'description' => 'Array of videos to license',
                    ],
                    'search_id' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => '',
                        'userinput_rqd' => true,
                        'description' => 'Search ID from the search that found this video',
                    ],
                ],
                'response' => [
                    'message' => 'Videos licensed successfully.',
                    'data' => [
                        'data' => [
                            [
                                'video_id' => '1234567890',
                                'download' => [
                                    'url' => 'https://download.shutterstock.com/gatekeeper/W3siZSI6MTYwNzUyMDAwMCwiayI6InZpZGVvLXNlcnZpY2UvcHJvZHVjdGlvbi...',
                                ],
                                'license_id' => 'lic_12345678901234567890',
                                'allotment_charge' => 10,
                                'price' => [
                                    'local_amount' => 0,
                                    'local_currency' => 'USD',
                                ],
                            ],
                        ],
                        'errors' => [],
                    ],
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => LicenseVideoRequest::class,
                'function_name' => 'licenseVideo',
            ],
            [
                'name' => 'Download Video',
                'input_parameters' => [
                    'license_id' => [
                        'type' => 'string',
                        'userinput_rqd' => true,
                        'required' => true,
                        'default' => 'lic_12345678901234567890',
                        'description' => 'License ID from the licensing step',
                    ],
                ],
                'response' => [
                    'message' => 'Download link generated successfully.',
                    'data' => [
                        'url' => 'https://download.shutterstock.com/gatekeeper/W3siZSI6MTYwNzUyMDAwMCwiayI6InZpZGVvLXNlcnZpY2UvcHJvZHVjdGlvbi...',
                    ],
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => DownloadVideoRequest::class,
                'function_name' => 'downloadVideo',
            ],
            [
                'name' => 'Search Audio',
                'input_parameters' => [
                    'query' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => 'upbeat corporate',
                        'description' => 'Search query for audio tracks',
                    ],
                    'sort' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'popular',
                        'options' => [
                            'source' => 'static',
                            'static_options' => [
                                'popular',
                                'newest',
                                'oldest',
                                'duration',
                                'duration_desc',
                            ],
                        ],
                        'description' => 'Sort order for search results',
                    ],
                ],
                'response' => [
                    'message' => 'Audio search successful.',
                    'data' => [
                        'data' => [
                            [
                                'id' => '1234567890',
                                'title' => 'Upbeat Corporate Background Music',
                                'artist' => 'AudioCreator',
                                'duration' => 120.5,
                                'genres' => ['corporate', 'background'],
                                'instruments' => ['piano', 'guitar', 'drums'],
                                'moods' => ['upbeat', 'positive', 'energetic'],
                                'assets' => [
                                    'preview_mp3' => [
                                        'url' => 'https://ak.picdn.net/shutterstock/audio/1234567890/preview/preview.mp3',
                                    ],
                                    'waveform' => [
                                        'url' => 'https://ak.picdn.net/shutterstock/audio/1234567890/waveform/waveform.png',
                                    ],
                                ],
                                'contributor' => [
                                    'id' => '12345',
                                ],
                                'description' => 'Upbeat corporate background music perfect for presentations',
                                'bpm' => 120,
                                'has_vocal' => false,
                                'media_type' => 'audio',
                                'added_date' => '2024-01-01',
                                'affiliate_url' => 'https://www.shutterstock.com/music/track/1234567890',
                            ],
                        ],
                        'page' => 1,
                        'per_page' => 20,
                        'total_count' => 450000,
                        'search_id' => 'search_123456789',
                    ],
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => SearchAudioRequest::class,
                'function_name' => 'searchAudio',
            ],
            [
                'name' => 'Get Audio',
                'input_parameters' => [
                    'audio_id' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => '1234567890',
                        'description' => 'ID of the audio track to retrieve',
                    ],
                ],
                'response' => [
                    'message' => 'Audio track details retrieved successfully.',
                    'data' => [
                        'id' => '1234567890',
                        'title' => 'Upbeat Corporate Background Music',
                        'artist' => 'AudioCreator',
                        'duration' => 120.5,
                        'genres' => ['corporate', 'background'],
                        'instruments' => ['piano', 'guitar', 'drums'],
                        'moods' => ['upbeat', 'positive', 'energetic'],
                        'assets' => [
                            'preview_mp3' => [
                                'url' => 'https://ak.picdn.net/shutterstock/audio/1234567890/preview/preview.mp3',
                            ],
                            'waveform' => [
                                'url' => 'https://ak.picdn.net/shutterstock/audio/1234567890/waveform/waveform.png',
                            ],
                        ],
                        'contributor' => [
                            'id' => '12345',
                        ],
                        'description' => 'Upbeat corporate background music perfect for presentations',
                        'bpm' => 120,
                        'has_vocal' => false,
                        'media_type' => 'audio',
                        'added_date' => '2024-01-01',
                        'affiliate_url' => 'https://www.shutterstock.com/music/track/1234567890',
                        'album' => [
                            'id' => 'album_123',
                            'title' => 'Corporate Collection Vol. 1',
                        ],
                    ],
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => GetAudioRequest::class,
                'function_name' => 'getAudio',
            ],
            [
                'name' => 'License Audio',
                'input_parameters' => [
                    'audio_tracks' => [
                        'type' => 'array',
                        'userinput_rqd' => true,
                        'required' => true,
                        'default' => [
                            [
                                'audio_id' => '1234567890',
                                'size' => 'mp3',
                            ],
                        ],
                        'description' => 'Array of audio tracks to license',
                    ],
                    'search_id' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => '',
                        'description' => 'Search ID from the search that found this audio',
                    ],
                ],
                'response' => [
                    'message' => 'Audio tracks licensed successfully.',
                    'data' => [
                        'data' => [
                            [
                                'audio_id' => '1234567890',
                                'download' => [
                                    'url' => 'https://download.shutterstock.com/gatekeeper/W3siZSI6MTYwNzUyMDAwMCwiayI6ImF1ZGlvLXNlcnZpY2UvcHJvZHVjdGlvbi...',
                                ],
                                'license_id' => 'lic_12345678901234567890',
                                'allotment_charge' => 5,
                                'price' => [
                                    'local_amount' => 0,
                                    'local_currency' => 'USD',
                                ],
                            ],
                        ],
                        'errors' => [],
                    ],
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => LicenseAudioRequest::class,
                'function_name' => 'licenseAudio',
            ],
            [
                'name' => 'Download Audio',
                'input_parameters' => [
                    'license_id' => [
                        'type' => 'string',
                        'userinput_rqd' => true,
                        'required' => true,
                        'default' => 'lic_12345678901234567890',
                        'description' => 'License ID from the licensing step',
                    ],
                ],
                'response' => [
                    'message' => 'Download link generated successfully.',
                    'data' => [
                        'url' => 'https://download.shutterstock.com/gatekeeper/W3siZSI6MTYwNzUyMDAwMCwiayI6ImF1ZGlvLXNlcnZpY2UvcHJvZHVjdGlvbi...',
                    ],
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => DownloadAudioRequest::class,
                'function_name' => 'downloadAudio',
            ],
            [
                'name' => 'List User Subscriptions',
                'input_parameters' => [],
                'response' => [
                    'message' => 'User subscriptions retrieved successfully.',
                    'data' => [
                        'data' => [
                            [
                                'id' => 'sub_12345678901234567890',
                                'description' => 'Premium Annual Subscription',
                                'expires_time' => '2025-01-15T23:59:59.000Z',
                                'license' => 'standard',
                                'metadata' => [
                                    'purchase_order' => 'PO-123456',
                                ],
                                'formats' => [
                                    [
                                        'format' => 'jpg',
                                        'min_resolution' => 500,
                                        'size' => 'huge',
                                    ],
                                ],
                                'allotment' => [
                                    'downloads_limit' => 750,
                                    'downloads_used' => 245,
                                ],
                            ],
                        ],
                        'page' => 1,
                        'per_page' => 20,
                        'total_count' => 1,
                    ],
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => null,
                'function_name' => 'listUserSubscriptions',
            ],
        ];

        $keptServiceTypeIds = $this->processServiceTypes($serviceProvider, $serviceTypes, 'Shutterstock');

        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);

        $this->command->info('Cleanup completed:');
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info('- Kept ' . count($keptServiceTypeIds) . ' service types for Shutterstock');
    }
} 