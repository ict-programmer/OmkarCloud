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
                    'endpoints' => [
                        'images_search' => '/v2/images/search',
                        'images_details' => '/v2/images',
                        'images_licenses' => '/v2/images/licenses',
                        'videos_search' => '/v2/videos/search',
                        'videos_details' => '/v2/videos',
                        'videos_licenses' => '/v2/videos/licenses',
                        'audio_search' => '/v2/audio/search',
                        'audio_details' => '/v2/audio',
                        'audio_licenses' => '/v2/audio/licenses',
                        'collections' => '/v2/images/collections',
                        'user_subscriptions' => '/v2/user/subscriptions',
                    ],
                    'media_types' => [
                        'images',
                        'videos',
                        'audio',
                        'editorial',
                    ],
                    'subscription_types' => [
                        'free',
                        'standard',
                        'enterprise',
                    ],
                    'features' => [
                        'search_media',
                        'license_media',
                        'download_media',
                        'collections_management',
                        'subscription_management',
                        'bulk_operations',
                        'computer_vision_search',
                        'ai_search',
                    ],
                ],
                'is_active' => true,
                'controller_name' => ShutterstockController::class,
            ]
        );

        $serviceTypes = [
            [
                'name' => 'Search Images',
                'input_parameters' => [
                    'query' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 200,
                        'description' => 'Search query for images',
                        'example' => 'hiking mountains',
                    ],
                    'orientation' => [
                        'type' => 'string',
                        'required' => false,
                        'options' => ['horizontal', 'vertical', 'square'],
                        'description' => 'Image orientation filter',
                        'example' => 'horizontal',
                    ],
                    'image_type' => [
                        'type' => 'string',
                        'required' => false,
                        'options' => ['photo', 'illustration', 'vector'],
                        'description' => 'Type of image',
                        'example' => 'photo',
                    ],
                    'category' => [
                        'type' => 'string',
                        'required' => false,
                        'description' => 'Image category',
                        'example' => 'nature',
                    ],
                    'people_number' => [
                        'type' => 'integer',
                        'required' => false,
                        'min' => 0,
                        'max' => 4,
                        'description' => 'Number of people in image (0-4)',
                        'example' => 2,
                    ],
                    'page' => [
                        'type' => 'integer',
                        'required' => false,
                        'default' => 1,
                        'min' => 1,
                        'max' => 1000,
                        'description' => 'Page number for pagination',
                    ],
                    'per_page' => [
                        'type' => 'integer',
                        'required' => false,
                        'default' => 20,
                        'min' => 1,
                        'max' => 500,
                        'description' => 'Number of results per page',
                    ],
                    'sort' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'popular',
                        'options' => ['popular', 'newest', 'relevance', 'random'],
                        'description' => 'Sort order for results',
                    ],
                ],
                'response' => [
                    'data' => [
                        [
                            'id' => '1234567890',
                            'aspect' => 1.5,
                            'assets' => [
                                'preview' => [
                                    'height' => 300,
                                    'url' => 'https://image.shutterstock.com/image-photo/hiking-mountains-260nw-1234567890.jpg',
                                    'width' => 450,
                                ],
                                'small_thumb' => [
                                    'height' => 67,
                                    'url' => 'https://thumb7.shutterstock.com/thumb_small/1234567890/1234567890.jpg',
                                    'width' => 100,
                                ],
                            ],
                            'contributor' => [
                                'id' => '12345678',
                            ],
                            'description' => 'Hikers on mountain trail with beautiful landscape view',
                            'image_type' => 'photo',
                            'has_model_release' => true,
                            'has_property_release' => false,
                            'keywords' => ['hiking', 'mountains', 'nature', 'outdoor', 'adventure'],
                            'media_type' => 'image',
                        ],
                    ],
                    'page' => 1,
                    'per_page' => 20,
                    'total_count' => 15420,
                    'search_id' => 'abc123def456',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => SearchImagesRequest::class,
                'function_name' => 'searchImages',
            ],
            [
                'name' => 'Get Image Details',
                'input_parameters' => [
                    'image_id' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'Image ID to get details for',
                        'example' => '1234567890',
                    ],
                ],
                'response' => [
                    'id' => '1234567890',
                    'added_date' => '2023-01-15',
                    'aspect' => 1.5,
                    'assets' => [
                        'preview' => [
                            'height' => 300,
                            'url' => 'https://image.shutterstock.com/image-photo/hiking-mountains-260nw-1234567890.jpg',
                            'width' => 450,
                        ],
                        'small_thumb' => [
                            'height' => 67,
                            'url' => 'https://thumb7.shutterstock.com/thumb_small/1234567890/1234567890.jpg',
                            'width' => 100,
                        ],
                        'large_thumb' => [
                            'height' => 150,
                            'url' => 'https://thumb7.shutterstock.com/thumb_large/1234567890/1234567890.jpg',
                            'width' => 225,
                        ],
                    ],
                    'categories' => [
                        ['id' => '1', 'name' => 'Nature'],
                        ['id' => '2', 'name' => 'Sports/Recreation'],
                    ],
                    'contributor' => [
                        'id' => '12345678',
                    ],
                    'description' => 'Hikers on mountain trail with beautiful landscape view',
                    'image_type' => 'photo',
                    'has_model_release' => true,
                    'has_property_release' => false,
                    'keywords' => ['hiking', 'mountains', 'nature', 'outdoor', 'adventure'],
                    'media_type' => 'image',
                    'models' => [
                        ['id' => 'model123'],
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
                        'type' => 'string',
                        'required' => true,
                        'description' => 'Image ID to license',
                        'example' => '1234567890',
                    ],
                    'size' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'huge',
                        'options' => ['small', 'medium', 'huge', 'vector'],
                        'description' => 'License size',
                    ],
                    'format' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'jpg',
                        'options' => ['jpg', 'eps', 'ai'],
                        'description' => 'File format',
                    ],
                    'subscription_id' => [
                        'type' => 'string',
                        'required' => false,
                        'description' => 'Subscription ID to use for licensing',
                    ],
                ],
                'response' => [
                    'data' => [
                        [
                            'image_id' => '1234567890',
                            'download' => [
                                'url' => 'https://download.shutterstock.com/gatekeeper/W3siZCI6MTIzNDU2Nzg5MH0.jpg',
                            ],
                            'allotment_charge' => 1,
                            'license' => 'standard',
                        ],
                    ],
                    'errors' => [],
                    'message' => 'Images licensed successfully',
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
                        'required' => true,
                        'description' => 'License ID from licensing response',
                        'example' => 'li_12345678',
                    ],
                    'size' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'huge',
                        'options' => ['small', 'medium', 'huge', 'vector'],
                        'description' => 'Download size',
                    ],
                ],
                'response' => [
                    'url' => 'https://download.shutterstock.com/gatekeeper/W3siZCI6MTIzNDU2Nzg5MH0.jpg',
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
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 200,
                        'description' => 'Search query for videos',
                        'example' => 'hot air balloon',
                    ],
                    'orientation' => [
                        'type' => 'string',
                        'required' => false,
                        'options' => ['horizontal', 'vertical', 'square'],
                        'description' => 'Video orientation filter',
                        'example' => 'horizontal',
                    ],
                    'category' => [
                        'type' => 'string',
                        'required' => false,
                        'description' => 'Video category',
                        'example' => 'nature',
                    ],
                    'duration' => [
                        'type' => 'string',
                        'required' => false,
                        'options' => ['short', 'medium', 'long'],
                        'description' => 'Video duration filter',
                    ],
                    'fps' => [
                        'type' => 'string',
                        'required' => false,
                        'options' => ['24', '25', '29.97', '30', '50', '59.94', '60'],
                        'description' => 'Frames per second',
                    ],
                    'page' => [
                        'type' => 'integer',
                        'required' => false,
                        'default' => 1,
                        'min' => 1,
                        'max' => 1000,
                        'description' => 'Page number for pagination',
                    ],
                    'per_page' => [
                        'type' => 'integer',
                        'required' => false,
                        'default' => 20,
                        'min' => 1,
                        'max' => 500,
                        'description' => 'Number of results per page',
                    ],
                ],
                'response' => [
                    'data' => [
                        [
                            'id' => '9876543210',
                            'aspect' => 1.78,
                            'assets' => [
                                'preview_mp4' => [
                                    'url' => 'https://ak.picdn.net/shutterstock/videos/9876543210/preview/stock-footage-hot-air-balloon.mp4',
                                ],
                                'preview_webm' => [
                                    'url' => 'https://ak.picdn.net/shutterstock/videos/9876543210/preview/stock-footage-hot-air-balloon.webm',
                                ],
                                'thumb_jpg' => [
                                    'url' => 'https://ak.picdn.net/shutterstock/videos/9876543210/thumb/1.jpg',
                                ],
                            ],
                            'contributor' => [
                                'id' => '87654321',
                            ],
                            'description' => 'Hot air balloon floating over scenic landscape',
                            'duration' => 15.5,
                            'fps' => 29.97,
                            'has_model_release' => true,
                            'has_property_release' => false,
                            'keywords' => ['hot air balloon', 'floating', 'sky', 'adventure', 'travel'],
                            'media_type' => 'video',
                        ],
                    ],
                    'page' => 1,
                    'per_page' => 20,
                    'total_count' => 8540,
                    'search_id' => 'xyz789abc123',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => SearchVideosRequest::class,
                'function_name' => 'searchVideos',
            ],
            [
                'name' => 'Get Video Details',
                'input_parameters' => [
                    'video_id' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'Video ID to get details for',
                        'example' => '9876543210',
                    ],
                ],
                'response' => [
                    'id' => '9876543210',
                    'added_date' => '2023-02-10',
                    'aspect' => 1.78,
                    'assets' => [
                        'preview_mp4' => [
                            'url' => 'https://ak.picdn.net/shutterstock/videos/9876543210/preview/stock-footage-hot-air-balloon.mp4',
                        ],
                        'preview_webm' => [
                            'url' => 'https://ak.picdn.net/shutterstock/videos/9876543210/preview/stock-footage-hot-air-balloon.webm',
                        ],
                        'thumb_jpg' => [
                            'url' => 'https://ak.picdn.net/shutterstock/videos/9876543210/thumb/1.jpg',
                        ],
                    ],
                    'categories' => [
                        ['id' => '1', 'name' => 'Nature'],
                        ['id' => '3', 'name' => 'Transportation'],
                    ],
                    'contributor' => [
                        'id' => '87654321',
                    ],
                    'description' => 'Hot air balloon floating over scenic landscape',
                    'duration' => 15.5,
                    'fps' => 29.97,
                    'has_model_release' => true,
                    'has_property_release' => false,
                    'keywords' => ['hot air balloon', 'floating', 'sky', 'adventure', 'travel'],
                    'media_type' => 'video',
                    'models' => [],
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
                        'required' => true,
                        'description' => 'Array of video licensing data',
                        'items' => [
                            'type' => 'object',
                            'properties' => [
                                'video_id' => ['type' => 'string', 'required' => true],
                                'size' => ['type' => 'string', 'options' => ['web', 'sd', 'hd', '4k']],
                            ],
                        ],
                        'example' => [
                            ['video_id' => '9876543210', 'size' => 'hd'],
                        ],
                    ],
                    'search_id' => [
                        'type' => 'string',
                        'required' => false,
                        'description' => 'Search ID from search results',
                    ],
                ],
                'response' => [
                    'data' => [
                        [
                            'video_id' => '9876543210',
                            'download' => [
                                'url' => 'https://download.shutterstock.com/gatekeeper/W3siZCI6OTg3NjU0MzIxMH0.mp4',
                            ],
                            'allotment_charge' => 10,
                            'license' => 'standard',
                        ],
                    ],
                    'errors' => [],
                    'message' => 'Videos licensed successfully',
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
                        'required' => true,
                        'description' => 'License ID from licensing response',
                        'example' => 'lv_87654321',
                    ],
                ],
                'response' => [
                    'url' => 'https://download.shutterstock.com/gatekeeper/W3siZCI6OTg3NjU0MzIxMH0.mp4',
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
                        'min_length' => 1,
                        'max_length' => 200,
                        'description' => 'Search query for audio tracks',
                        'example' => 'bluegrass',
                    ],
                    'duration' => [
                        'type' => 'string',
                        'required' => false,
                        'options' => ['short', 'medium', 'long'],
                        'description' => 'Audio duration filter',
                    ],
                    'genre' => [
                        'type' => 'string',
                        'required' => false,
                        'description' => 'Music genre',
                        'example' => 'country',
                    ],
                    'mood' => [
                        'type' => 'string',
                        'required' => false,
                        'description' => 'Music mood',
                        'example' => 'upbeat',
                    ],
                    'page' => [
                        'type' => 'integer',
                        'required' => false,
                        'default' => 1,
                        'min' => 1,
                        'max' => 1000,
                        'description' => 'Page number for pagination',
                    ],
                    'per_page' => [
                        'type' => 'integer',
                        'required' => false,
                        'default' => 20,
                        'min' => 1,
                        'max' => 500,
                        'description' => 'Number of results per page',
                    ],
                ],
                'response' => [
                    'data' => [
                        [
                            'id' => '1357924680',
                            'added_date' => '2023-03-05',
                            'affiliate_url' => 'https://www.shutterstock.com/music/track/1357924680',
                            'artist' => 'Mountain Music Co.',
                            'assets' => [
                                'preview_mp3' => [
                                    'url' => 'https://ak.picdn.net/shutterstock/audio/1357924680/preview/preview.mp3',
                                ],
                                'waveform' => [
                                    'url' => 'https://ak.picdn.net/shutterstock/audio/1357924680/waveform.png',
                                ],
                            ],
                            'bpm' => 120,
                            'contributor' => [
                                'id' => '11223344',
                            ],
                            'description' => 'Upbeat bluegrass track with banjo and fiddle',
                            'duration' => 180.5,
                            'genres' => ['Country', 'Folk'],
                            'instruments' => ['Banjo', 'Fiddle', 'Guitar'],
                            'keywords' => ['bluegrass', 'country', 'upbeat', 'folk', 'acoustic'],
                            'media_type' => 'audio',
                            'title' => 'Mountain Trail Bluegrass',
                        ],
                    ],
                    'page' => 1,
                    'per_page' => 20,
                    'total_count' => 2340,
                    'search_id' => 'audio123xyz789',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => SearchAudioRequest::class,
                'function_name' => 'searchAudio',
            ],
            [
                'name' => 'Get Audio Details',
                'input_parameters' => [
                    'audio_id' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'Audio track ID to get details for',
                        'example' => '1357924680',
                    ],
                ],
                'response' => [
                    'id' => '1357924680',
                    'added_date' => '2023-03-05',
                    'affiliate_url' => 'https://www.shutterstock.com/music/track/1357924680',
                    'artist' => 'Mountain Music Co.',
                    'assets' => [
                        'preview_mp3' => [
                            'url' => 'https://ak.picdn.net/shutterstock/audio/1357924680/preview/preview.mp3',
                        ],
                        'waveform' => [
                            'url' => 'https://ak.picdn.net/shutterstock/audio/1357924680/waveform.png',
                        ],
                    ],
                    'bpm' => 120,
                    'contributor' => [
                        'id' => '11223344',
                    ],
                    'description' => 'Upbeat bluegrass track with banjo and fiddle',
                    'duration' => 180.5,
                    'genres' => ['Country', 'Folk'],
                    'instruments' => ['Banjo', 'Fiddle', 'Guitar'],
                    'keywords' => ['bluegrass', 'country', 'upbeat', 'folk', 'acoustic'],
                    'media_type' => 'audio',
                    'title' => 'Mountain Trail Bluegrass',
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
                    'audio_id' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'Audio track ID to license',
                        'example' => '1357924680',
                    ],
                    'license_type' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'standard',
                        'options' => ['standard', 'enhanced', 'premier'],
                        'description' => 'License type',
                    ],
                ],
                'response' => [
                    'data' => [
                        [
                            'audio_id' => '1357924680',
                            'download' => [
                                'url' => 'https://download.shutterstock.com/gatekeeper/W3siZCI6MTM1NzkyNDY4MH0.mp3',
                            ],
                            'allotment_charge' => 10,
                            'license' => 'standard',
                        ],
                    ],
                    'errors' => [],
                    'message' => 'Audio licensed successfully',
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
                        'required' => true,
                        'description' => 'License ID from licensing response',
                        'example' => 'la_13579246',
                    ],
                ],
                'response' => [
                    'url' => 'https://download.shutterstock.com/gatekeeper/W3siZCI6MTM1NzkyNDY4MH0.mp3',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => DownloadAudioRequest::class,
                'function_name' => 'downloadAudio',
            ],
            [
                'name' => 'Create Collection',
                'input_parameters' => [
                    'name' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 100,
                        'description' => 'Collection name',
                        'example' => 'My Nature Photos',
                    ],
                ],
                'response' => [
                    'id' => 'col_123456789',
                    'name' => 'My Nature Photos',
                    'total_item_count' => 0,
                    'items_updated_time' => '2024-01-15T10:30:00Z',
                    'cover_item' => null,
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => CreateCollectionRequest::class,
                'function_name' => 'createCollection',
            ],
            [
                'name' => 'Add to Collection',
                'input_parameters' => [
                    'collection_id' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'Collection ID to add items to',
                        'example' => 'col_123456789',
                    ],
                    'items' => [
                        'type' => 'array',
                        'required' => true,
                        'description' => 'Array of items to add to collection',
                        'items' => [
                            'type' => 'object',
                            'properties' => [
                                'id' => ['type' => 'string', 'required' => true],
                                'media_type' => ['type' => 'string', 'options' => ['image', 'video']],
                            ],
                        ],
                        'example' => [
                            ['id' => '1234567890', 'media_type' => 'image'],
                        ],
                    ],
                ],
                'response' => [
                    'message' => 'Items added to collection successfully',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => AddToCollectionRequest::class,
                'function_name' => 'addToCollection',
            ],
            [
                'name' => 'List User Subscriptions',
                'input_parameters' => [],
                'response' => [
                    'data' => [
                        [
                            'id' => 'sub_123456789',
                            'product_name' => 'Standard Images',
                            'allotment' => [
                                'downloads_left' => 450,
                                'downloads_limit' => 750,
                                'reset_time' => '2024-02-01T00:00:00Z',
                            ],
                            'license' => 'standard',
                            'sizes' => [
                                'small',
                                'medium',
                                'huge',
                            ],
                            'formats' => [
                                'jpg',
                            ],
                            'description' => 'Standard subscription for images',
                        ],
                        [
                            'id' => 'sub_987654321',
                            'product_name' => 'HD Video',
                            'allotment' => [
                                'downloads_left' => 8,
                                'downloads_limit' => 10,
                                'reset_time' => '2024-02-01T00:00:00Z',
                            ],
                            'license' => 'standard',
                            'sizes' => [
                                'web',
                                'sd',
                                'hd',
                            ],
                            'formats' => [
                                'mov',
                                'mp4',
                            ],
                            'description' => 'HD video subscription',
                        ],
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