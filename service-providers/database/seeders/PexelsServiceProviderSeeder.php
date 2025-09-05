<?php

namespace Database\Seeders;

use App\Enums\common\ServiceProviderEnum;
use App\Enums\common\ServiceTypeEnum;
use App\Http\Controllers\PexelsController;
use App\Http\Requests\Pexels\GetCollectionRequest;
use App\Http\Requests\Pexels\GetCollectionsRequest;
use App\Http\Requests\Pexels\GetCuratedPhotosRequest;
use App\Http\Requests\Pexels\GetFeaturedCollectionsRequest;
use App\Http\Requests\Pexels\GetPhotoRequest;
use App\Http\Requests\Pexels\GetPopularVideosRequest;
use App\Http\Requests\Pexels\GetVideoRequest;
use App\Http\Requests\Pexels\SearchPhotosRequest;
use App\Http\Requests\Pexels\SearchVideosRequest;
use App\Models\ServiceProvider;
use App\Traits\ServiceProviderSeederTrait;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PexelsServiceProviderSeeder extends Seeder
{
    use ServiceProviderSeederTrait;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => ServiceProviderEnum::PEXELS->value],
            [
                'parameters' => [
                    'api_key' => 'YOUR_API_KEY',
                    'base_url' => 'https://api.pexels.com',
                    'version' => 'v1',
                    'features' => [
                        'photos_search',
                        'photos_curated',
                        'get_photo',
                        'videos_search',
                        'videos_popular',
                        'get_video',
                        'collections_featured',
                        'get_collections',
                        'get_collection',
                    ],
                    'documentation' => [
                        'description' => 'The Pexels API enables programmatic access to the full Pexels content library, including photos, videos.',
                        'api_documentation' => 'https://www.pexels.com/api/documentation',
                        'rate_limits' => 'Varies by model and plan',
                        'authentication' => 'API Key required in headers',
                    ],
                ],
                'is_active' => true,
                'controller_name' => PexelsController::class,
            ]
        );

        $serviceTypes = [
            [
                'name' => ServiceTypeEnum::SEARCH_PHOTOS->value,
                'input_parameters' => [
                    'query' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'Search query',
                        'example' => 'dog',
                        'validation' => 'required|string'
                    ],
                    'orientation' => [
                        'type' => 'string',
                        'required' => false,
                        'userinput_rqd' => false,
                        'description' => 'Orientation',
                        'example' => 'landscape',
                        'validation' => 'nullable|string|in:landscape,portrait,square',
                        'options' => ['landscape', 'portrait', 'square'],
                    ],
                    'size' => [
                        'type' => 'string',
                        'required' => false,
                        'userinput_rqd' => false,
                        'description' => 'Size',
                        'example' => 'medium',
                        'validation' => 'nullable|string|in:small,medium,large',
                        'options' => ['small', 'medium', 'large'],
                    ],
                    'color' => [
                        'type' => 'string',
                        'required' => false,
                        'userinput_rqd' => true,
                        'description' => 'Color',
                        'example' => 'red',
                        'validation' => 'nullable|string'
                    ],
                    'locale' => [
                        'type' => 'string',
                        'required' => false,
                        'userinput_rqd' => true,
                        'description' => 'Locale',
                        'example' => 'en-US',
                        'validation' => 'nullable|string'
                    ],
                    'page' => [
                        'type' => 'integer',
                        'required' => false,
                        'userinput_rqd' => true,
                        'description' => 'Page number',
                        'example' => 1,
                        'validation' => 'nullable|integer'
                    ],
                    'per_page' => [
                        'type' => 'integer',
                        'required' => false,
                        'userinput_rqd' => true,
                        'description' => 'Results per page',
                        'example' => 15,
                        'validation' => 'nullable|integer'
                    ]
                ],
                'response' => [
                    'total_results' => 100,
                    'per_page' => 15,
                    'page' => 1,
                    'prev_page' => 'https://www.pexels.com/api/v1/search?query=dog&page=1',
                    'next_page' => 'https://www.pexels.com/api/v1/search?query=dog&page=2',
                    'photos' => [
                        [
                            'id' => '123456789',
                            'width' => 1080,
                            'height' => 1080,
                            'url' => 'https://www.pexels.com/photo/dog-running-on-grass-123456789/',
                            'photographer' => 'John Doe',
                            'photographer_url' => 'https://www.pexels.com/@johndoe',
                            'photographer_id' => '123456789',
                            'avg_color' => '#000000',
                            'src' => [
                                'original' => 'https://www.pexels.com/data-placeholder',
                                'large2x' => 'https://www.pexels.com/data-placeholder',
                                'large' => 'https://www.pexels.com/data-placeholder',
                                'medium' => 'https://www.pexels.com/data-placeholder',
                                'small' => 'https://www.pexels.com/data-placeholder',
                                'portrait' => 'https://www.pexels.com/data-placeholder',
                                'landscape' => 'https://www.pexels.com/data-placeholder',
                                'tiny' => 'https://www.pexels.com/data-placeholder'
                            ],
                            'liked' => false,
                            'alt' => 'A dog running on grass'
                        ]
                    ]
                ],
                'response_path' => [
                    'total_results' => '$.total_results',
                    'per_page' => '$.per_page',
                    'page' => '$.page',
                    'prev_page' => '$.prev_page',
                    'next_page' => '$.next_page',
                    'final_result' => '$.photos'
                ],
                'request_class_name' => SearchPhotosRequest::class,
                'function_name' => 'searchPhotos'
            ],
            [
                'name' => ServiceTypeEnum::GET_CURATED_PHOTOS->value,
                'input_parameters' => [
                    'page' => [
                        'type' => 'integer',
                        'required' => false,
                        'userinput_rqd' => true,
                        'description' => 'Page number',
                        'example' => 1,
                        'validation' => 'nullable|integer'
                    ],
                    'per_page' => [
                        'type' => 'integer',
                        'required' => false,
                        'userinput_rqd' => true,
                        'description' => 'Results per page',
                        'example' => 15,
                        'validation' => 'nullable|integer'
                    ]
                ],
                'response' => [
                    'total_results' => 100,
                    'per_page' => 15,
                    'page' => 1,
                    'prev_page' => 'https://www.pexels.com/api/v1/search?query=dog&page=1',
                    'next_page' => 'https://www.pexels.com/api/v1/search?query=dog&page=2',
                    'photos' => [
                        [
                            'id' => '123456789',
                            'width' => 1080,
                            'height' => 1080,
                            'url' => 'https://www.pexels.com/photo/dog-running-on-grass-123456789/',
                            'photographer' => 'John Doe',
                            'photographer_url' => 'https://www.pexels.com/@johndoe',
                            'photographer_id' => '123456789',
                            'avg_color' => '#000000',
                            'src' => [
                                'original' => 'https://www.pexels.com/data-placeholder',
                                'large2x' => 'https://www.pexels.com/data-placeholder',
                                'large' => 'https://www.pexels.com/data-placeholder',
                                'medium' => 'https://www.pexels.com/data-placeholder',
                                'small' => 'https://www.pexels.com/data-placeholder',
                                'portrait' => 'https://www.pexels.com/data-placeholder',
                                'landscape' => 'https://www.pexels.com/data-placeholder',
                                'tiny' => 'https://www.pexels.com/data-placeholder'
                            ],
                            'liked' => false,
                            'alt' => 'A dog running on grass'
                        ]
                    ]
                ],
                'response_path' => [
                    'total_results' => '$.total_results',
                    'per_page' => '$.per_page',
                    'page' => '$.page',
                    'prev_page' => '$.prev_page',
                    'next_page' => '$.next_page',
                    'final_result' => '$.photos'
                ],
                'request_class_name' => GetCuratedPhotosRequest::class,
                'function_name' => 'getCuratedPhotos'
            ],
            [
                'name' => ServiceTypeEnum::GET_PHOTO->value,
                'input_parameters' => [
                    'id' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'Photo ID',
                        'example' => '123456789',
                        'validation' => 'required|string'
                    ]
                ],
                'response' => [
                    'id' => '123456789',
                    'width' => 1080,
                    'height' => 1080,
                    'url' => 'https://www.pexels.com/photo/dog-running-on-grass-123456789/',
                    'photographer' => 'John Doe',
                    'photographer_url' => 'https://www.pexels.com/@johndoe',
                    'photographer_id' => '123456789',
                    'avg_color' => '#000000',
                    'src' => [
                        'original' => 'https://www.pexels.com/data-placeholder',
                        'large2x' => 'https://www.pexels.com/data-placeholder',
                        'large' => 'https://www.pexels.com/data-placeholder',
                        'medium' => 'https://www.pexels.com/data-placeholder',
                        'small' => 'https://www.pexels.com/data-placeholder',
                        'portrait' => 'https://www.pexels.com/data-placeholder',
                        'landscape' => 'https://www.pexels.com/data-placeholder',
                        'tiny' => 'https://www.pexels.com/data-placeholder'
                    ],
                    'liked' => false,
                    'alt' => 'A dog running on grass'
                ],
                'response_path' => [
                    'final_result' => '$',
                    'id' => '$.id',
                    'width' => '$.width',
                    'height' => '$.height',
                    'url' => '$.url',
                    'photographer' => '$.photographer',
                    'photographer_url' => '$.photographer_url',
                    'photographer_id' => '$.photographer_id',
                    'avg_color' => '$.avg_color',
                    'src' => '$.src',
                    'liked' => '$.liked',
                    'alt' => '$.alt'
                ],
                'request_class_name' => GetPhotoRequest::class,
                'function_name' => 'getPhoto'
            ],
            [
                'name' => ServiceTypeEnum::SEARCH_VIDEOS->value,
                'input_parameters' => [
                    'query' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'Search query',
                        'example' => 'dog',
                        'validation' => 'required|string'
                    ],
                    'orientation' => [
                        'type' => 'string',
                        'required' => false,
                        'userinput_rqd' => false,
                        'description' => 'Orientation',
                        'example' => 'landscape',
                        'validation' => 'nullable|string|in:landscape,portrait,square',
                        'options' => ['landscape', 'portrait', 'square'],
                    ],
                    'size' => [
                        'type' => 'string',
                        'required' => false,
                        'userinput_rqd' => false,
                        'userinput_rqd' => true,
                        'description' => 'Size',
                        'example' => 'medium',
                        'validation' => 'nullable|string|in:small,medium,large',
                        'options' => ['small', 'medium', 'large'],
                    ],
                    'locale' => [
                        'type' => 'string',
                        'required' => false,
                        'userinput_rqd' => true,
                        'description' => 'Locale',
                        'example' => 'en-US',
                        'validation' => 'nullable|string'
                    ],
                    'page' => [
                        'type' => 'integer',
                        'required' => false,
                        'userinput_rqd' => true,
                        'description' => 'Page number',
                        'example' => 1,
                        'validation' => 'nullable|integer'
                    ],
                    'per_page' => [
                        'type' => 'integer',
                        'required' => false,
                        'userinput_rqd' => true,
                        'description' => 'Results per page',
                        'example' => 15,
                        'validation' => 'nullable|integer'
                    ]
                ],
                'response' => [
                    'total_results' => 100,
                    'per_page' => 15,
                    'page' => 1,
                    'prev_page' => 'https://www.pexels.com/api/v1/search?query=dog&page=1',
                    'next_page' => 'https://www.pexels.com/api/v1/search?query=dog&page=2',
                    'videos' => [
                        [
                            'id' => '123456789',
                            'width' => 1080,
                            'height' => 1080,
                            'url' => 'https://www.pexels.com/video/dog-running-on-grass-123456789/',
                            'image' => 'https://www.pexels.com/example-placeholder',
                            'duration' => 10,
                            'user' => [
                                'id' => '123456789',
                                'name' => 'John Doe',
                                'url' => 'https://www.pexels.com/@johndoe'
                            ],
                            'video_files' => [
                                [
                                    'id' => '123456789',
                                    'quality' => 'sd',
                                    'file_type' => 'video/mp4',
                                    'width' => 640,
                                    'height' => 360,
                                    'link' => 'https://www.pexels.com/example-placeholder'
                                ],
                                [
                                    'id' => '123456789',
                                    'quality' => 'hd',
                                    'file_type' => 'video/mp4',
                                    'width' => 1280,
                                    'height' => 720,
                                    'link' => 'https://www.pexels.com/example-placeholder'
                                ]
                            ],
                            'video_pictures' => [
                                [
                                    'id' => '123456789',
                                    'picture' => 'https://www.pexels.com/example-placeholder',
                                    'nr' => 0
                                ],
                                [
                                    'id' => '123456789',
                                    'picture' => 'https://www.pexels.com/example-placeholder',
                                    'nr' => 1
                                ]
                            ]
                        ]
                    ]
                ],
                'response_path' => [
                    'total_results' => '$.total_results',
                    'per_page' => '$.per_page',
                    'page' => '$.page',
                    'prev_page' => '$.prev_page',
                    'next_page' => '$.next_page',
                    'final_result' => '$.videos'
                ],
                'request_class_name' => SearchVideosRequest::class,
                'function_name' => 'searchVideos'
            ],
            [
                'name' => ServiceTypeEnum::GET_POPULAR_VIDEOS->value,
                'input_parameters' => [
                    'min_width' => [
                        'type' => 'integer',
                        'required' => false,
                        'userinput_rqd' => true,
                        'description' => 'Minimum width',
                        'example' => 1080,
                        'validation' => 'nullable|integer'
                    ],
                    'min_height' => [
                        'type' => 'integer',
                        'required' => false,
                        'userinput_rqd' => true,
                        'description' => 'Minimum height',
                        'example' => 1080,
                        'validation' => 'nullable|integer'
                    ],
                    'min_duration' => [
                        'type' => 'integer',
                        'required' => false,
                        'userinput_rqd' => true,
                        'description' => 'Minimum duration',
                        'example' => 10,
                        'validation' => 'nullable|integer'
                    ],
                    'max_duration' => [
                        'type' => 'integer',
                        'required' => false,
                        'userinput_rqd' => true,
                        'description' => 'Maximum duration',
                        'example' => 10,
                        'validation' => 'nullable|integer'
                    ],
                    'page' => [
                        'type' => 'integer',
                        'required' => false,
                        'userinput_rqd' => true,
                        'description' => 'Page number',
                        'example' => 1,
                        'validation' => 'nullable|integer'
                    ],
                    'per_page' => [
                        'type' => 'integer',
                        'required' => false,
                        'userinput_rqd' => true,
                        'description' => 'Results per page',
                        'example' => 15,
                        'validation' => 'nullable|integer'
                    ]
                ],
                'response' => [
                    'total_results' => 100,
                    'per_page' => 15,
                    'page' => 1,
                    'prev_page' => 'https://www.pexels.com/api/v1/search?query=dog&page=1',
                    'next_page' => 'https://www.pexels.com/api/v1/search?query=dog&page=2',
                    'videos' => [
                        [
                            'id' => '123456789',
                            'width' => 1080,
                            'height' => 1080,
                            'url' => 'https://www.pexels.com/video/dog-running-on-grass-123456789/',
                            'image' => 'https://www.pexels.com/example-placeholder',
                            'duration' => 10,
                            'user' => [
                                'id' => '123456789',
                                'name' => 'John Doe',
                                'url' => 'https://www.pexels.com/@johndoe'
                            ],
                            'video_files' => [
                                [
                                    'id' => '123456789',
                                    'quality' => 'sd',
                                    'file_type' => 'video/mp4',
                                    'width' => 640,
                                    'height' => 360,
                                    'link' => 'https://www.pexels.com/example-placeholder'
                                ],
                                [
                                    'id' => '123456789',
                                    'quality' => 'hd',
                                    'file_type' => 'video/mp4',
                                    'width' => 1280,
                                    'height' => 720,
                                    'link' => 'https://www.pexels.com/example-placeholder'
                                ]
                            ],
                            'video_pictures' => [
                                [
                                    'id' => '123456789',
                                    'picture' => 'https://www.pexels.com/example-placeholder',
                                    'nr' => 0
                                ],
                                [
                                    'id' => '123456789',
                                    'picture' => 'https://www.pexels.com/example-placeholder',
                                    'nr' => 1
                                ]
                            ]
                        ]
                    ]
                ],
                'response_path' => [
                    'total_results' => '$.total_results',
                    'per_page' => '$.per_page',
                    'page' => '$.page',
                    'prev_page' => '$.prev_page',
                    'next_page' => '$.next_page',
                    'final_result' => '$.videos'
                ],
                'request_class_name' => GetPopularVideosRequest::class,
                'function_name' => 'getPopularVideos'
            ],
            [
                'name' => ServiceTypeEnum::GET_VIDEO->value,
                'input_parameters' => [
                    'id' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'Video ID',
                        'example' => '123456789',
                        'validation' => 'required|string'
                    ]
                ],
                'response' => [
                    'id' => '123456789',
                    'width' => 1080,
                    'height' => 1080,
                    'url' => 'https://www.pexels.com/video/dog-running-on-grass-123456789/',
                    'image' => 'https://www.pexels.com/example-placeholder',
                    'duration' => 10,
                    'user' => [
                        'id' => '123456789',
                        'name' => 'John Doe',
                        'url' => 'https://www.pexels.com/@johndoe'
                    ],
                    'video_files' => [
                        [
                            'id' => '123456789',
                            'quality' => 'sd',
                            'file_type' => 'video/mp4',
                            'width' => 640,
                            'height' => 360,
                            'link' => 'https://www.pexels.com/example-placeholder'
                        ],
                        [
                            'id' => '123456789',
                            'quality' => 'hd',
                            'file_type' => 'video/mp4',
                            'width' => 1280,
                            'height' => 720,
                            'link' => 'https://www.pexels.com/example-placeholder'
                        ]
                    ],
                    'video_pictures' => [
                        [
                            'id' => '123456789',
                            'picture' => 'https://www.pexels.com/example-placeholder',
                            'nr' => 0
                        ],
                        [
                            'id' => '123456789',
                            'picture' => 'https://www.pexels.com/example-placeholder',
                            'nr' => 1
                        ]
                    ]
                ],
                'response_path' => [
                    'id' => '$.id',
                    'width' => '$.width',
                    'height' => '$.height',
                    'url' => '$.url',
                    'image' => '$.image',
                    'duration' => '$.duration',
                    'user' => '$.user',
                    'final_result' => '$.video_pictures'
                ],
                'request_class_name' => GetVideoRequest::class,
                'function_name' => 'getVideo'
            ],
            [
                'name' => ServiceTypeEnum::GET_FEATURED_COLLECTIONS->value,
                'input_parameters' => [
                    'page' => [
                        'type' => 'integer',
                        'required' => false,
                        'userinput_rqd' => true,
                        'description' => 'Page number',
                        'example' => 1,
                        'validation' => 'nullable|integer'
                    ],
                    'per_page' => [
                        'type' => 'integer',
                        'required' => false,
                        'userinput_rqd' => true,
                        'description' => 'Results per page',
                        'example' => 15,
                        'validation' => 'nullable|integer'
                    ]
                ],
                'response' => [
                    'total_results' => 100,
                    'per_page' => 15,
                    'page' => 1,
                    'prev_page' => 'https://www.pexels.com/data-placeholder',
                    'next_page' => 'https://www.pexels.com/data-placeholder',
                    'collections' => [
                        [
                            'id' => '123456789',
                            'title' => 'Cool Dogs',
                            'description' => 'Dog Running on Grass',
                            'private' => false,
                            'media_count' => 10,
                            'photos_count' => 7,
                            'videos_count' => 3
                        ]
                    ]
                ],
                'response_path' => [
                    'total_results' => '$.total_results',
                    'per_page' => '$.per_page',
                    'page' => '$.page',
                    'prev_page' => '$.prev_page',
                    'next_page' => '$.next_page',
                    'final_result' => '$.collections'
                ],
                'request_class_name' => GetFeaturedCollectionsRequest::class,
                'function_name' => 'getFeaturedCollections'
            ],
            [
                'name' => ServiceTypeEnum::GET_COLLECTIONS->value,
                'input_parameters' => [
                    'page' => [
                        'type' => 'integer',
                        'required' => false,
                        'userinput_rqd' => true,
                        'description' => 'Page number',
                        'example' => 1,
                        'validation' => 'nullable|integer'
                    ],
                    'per_page' => [
                        'type' => 'integer',
                        'required' => false,
                        'userinput_rqd' => true,
                        'description' => 'Results per page',
                        'example' => 15,
                        'validation' => 'nullable|integer'
                    ]
                ],
                'response' => [
                    'total_results' => 100,
                    'per_page' => 15,
                    'page' => 1,
                    'prev_page' => 'https://www.pexels.com/data-placeholder',
                    'next_page' => 'https://www.pexels.com/data-placeholder',
                    'collections' => [
                        [
                            'id' => '123456789',
                            'title' => 'Cool Dogs',
                            'description' => 'Dog Running on Grass',
                            'private' => false,
                            'media_count' => 10,
                            'photos_count' => 7,
                            'videos_count' => 3
                        ]
                    ]
                ],
                'response_path' => [
                    'total_results' => '$.total_results',
                    'per_page' => '$.per_page',
                    'page' => '$.page',
                    'prev_page' => '$.prev_page',
                    'next_page' => '$.next_page',
                    'final_result' => '$.collections'
                ],
                'request_class_name' => GetCollectionsRequest::class,
                'function_name' => 'getCollections'
            ],
            [
                'name' => ServiceTypeEnum::GET_COLLECTION->value,
                'input_parameters' => [
                    'id' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'Collection ID',
                        'example' => '123456789',
                        'validation' => 'required|string'
                    ],
                    'type' => [
                        'type' => 'string',
                        'required' => false,
                        'userinput_rqd' => false,
                        'description' => 'Collection type',
                        'example' => 'photos',
                        'validation' => 'nullable|string|in:photos,videos',
                        'options' => ['photos', 'videos'],
                    ],
                    'sort' => [
                        'type' => 'string',
                        'required' => false,
                        'userinput_rqd' => false,
                        'description' => 'Sort order',
                        'example' => 'asc',
                        'validation' => 'nullable|string|in:asc,desc',
                        'options' => ['asc', 'desc'],
                    ],
                    'page' => [
                        'type' => 'integer',
                        'required' => false,
                        'userinput_rqd' => true,
                        'description' => 'Page number',
                        'example' => 1,
                        'validation' => 'nullable|integer'
                    ],
                    'per_page' => [
                        'type' => 'integer',
                        'required' => false,
                        'userinput_rqd' => true,
                        'description' => 'Results per page',
                        'example' => 15,
                        'validation' => 'nullable|integer'
                    ]
                ],
                'response' => [
                    'total_results' => 100,
                    'per_page' => 15,
                    'page' => 1,
                    'prev_page' => 'https://www.pexels.com/data-placeholder',
                    'next_page' => 'https://www.pexels.com/data-placeholder',
                    'id' => '123456789',
                    'media' => [
                        [
                            'id' => '123456789',
                            'width' => 1080,
                            'height' => 1080,
                            'url' => 'https://www.pexels.com/example-placeholder',
                            'photographer' => 'John Doe',
                            'photographer_url' => 'https://www.pexels.com/example-placeholder',
                            'photographer_id' => '123456789',
                            'avg_color' => '#000000',
                            'src' => [
                                'original' => 'https://www.pexels.com/data-placeholder',
                                'tiny' => 'https://www.pexels.com/data-placeholder'
                            ],
                            'liked' => false,
                            'alt' => 'https://www.pexels.com/data-placeholder'
                        ],
                        [
                            'id' => '123456789',
                            'width' => 1080,
                            'height' => 1080,
                            'url' => 'https://www.pexels.com/example-placeholder',
                            'image' => 'https://www.pexels.com/example-placeholder',
                            'duration' => 10,
                            'user' => [
                                'id' => '123456789',
                                'name' => 'John Doe',
                                'url' => 'https://www.pexels.com/example-placeholder'
                            ],
                            'video_files' => [
                                [
                                    'id' => '123',
                                    'quality' => 'hd',
                                    'link' => 'https://www.pexels.com/example-placeholder'
                                ]
                            ],
                            'video_pictures' => [
                                [
                                    'id' => '123',
                                    'picture' => 'https://www.pexels.com/example-placeholder',
                                    'nr' => 0
                                ]
                            ]
                        ]
                    ]
                ],
                'response_path' => [
                    'total_results' => '$.total_results',
                    'per_page' => '$.per_page',
                    'page' => '$.page',
                    'prev_page' => '$.prev_page',
                    'next_page' => '$.next_page',
                    'id' => '$.id',
                    'final_result' => '$.media'
                ],
                'request_class_name' => GetCollectionRequest::class,
                'function_name' => 'getCollection'
            ]
        ];

        $keptServiceTypeIds = $this->processServiceTypes($serviceProvider, $serviceTypes, ServiceProviderEnum::PEXELS->value);

        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);

        $this->command->info("Cleanup completed:");
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info("- Kept " . count($keptServiceTypeIds) . " service types for Pexels API");
    }
}
