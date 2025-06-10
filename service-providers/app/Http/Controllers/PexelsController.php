<?php

namespace App\Http\Controllers;

use App\Data\Pexels\GetCollectionData;
use App\Data\Pexels\GetCuratedPhotosData;
use App\Data\Pexels\GetFeaturedCollectionsData;
use App\Data\Pexels\GetPhotoData;
use App\Data\Pexels\GetPopularVideosData;
use App\Data\Pexels\GetVideoData;
use App\Data\Pexels\SearchPhotosData;
use App\Data\Pexels\SearchVideosData;
use App\Http\Requests\Pexels\GetCollectionRequest;
use App\Http\Requests\Pexels\GetCuratedPhotosRequest;
use App\Http\Requests\Pexels\GetFeaturedCollectionsRequest;
use App\Http\Requests\Pexels\GetPhotoRequest;
use App\Http\Requests\Pexels\GetPopularVideosRequest;
use App\Http\Requests\Pexels\GetVideoRequest;
use App\Http\Requests\Pexels\SearchPhotosRequest;
use App\Http\Requests\Pexels\SearchVideosRequest;
use App\Services\PexelsService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class PexelsController extends BaseController
{
    public function __construct(protected PexelsService $service) {}

    #[OA\Get(
        path: '/api/pexels/photos/search',
        summary: 'Search photos',
        description: 'Search photos',
        tags: ["Pexels"],
        security: [['authentication' => []]],
    )]
    #[OA\Parameter(
        name: 'query',
        in: 'query',
        required: true,
        description: 'Search query',
        schema: new OA\Schema(type: 'string', example: 'dog')
    )]
    #[OA\Parameter(
        name: 'orientation',
        in: 'query',
        required: false,
        description: 'Orientation',
        schema: new OA\Schema(type: 'string', enum: ['landscape', 'portrait', 'square'], example: 'landscape')
    )]
    #[OA\Parameter(
        name: 'size',
        in: 'query',
        required: false,
        description: 'Size',
        schema: new OA\Schema(type: 'string', enum: ['small', 'medium', 'large'], example: 'medium')
    )]
    #[OA\Parameter(
        name: 'color',
        in: 'query',
        required: false,
        description: 'Color',
        schema: new OA\Schema(type: 'string', example: 'red')
    )]
    #[OA\Parameter(
        name: 'locale',
        in: 'query',
        required: false,
        description: 'Locale',
        schema: new OA\Schema(type: 'string', example: 'en-US')
    )]
    #[OA\Parameter(
        name: 'page',
        in: 'query',
        required: false,
        description: 'Page number',
        schema: new OA\Schema(type: 'integer', example: 1)
    )]
    #[OA\Parameter(
        name: 'per_page',
        in: 'query',
        required: false,
        description: 'Number of results per page',
        schema: new OA\Schema(type: 'integer', example: 15)
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'total_results' => 100,
                'per_page' => 15,
                'page' => 1,
                'prev_page' => 'https://www.pexels.com/api/v1/search?query=dog&orientation=landscape&size=medium&color=red&locale=en&page=1&per_page=10',
                'next_page' => 'https://www.pexels.com/api/v1/search?query=dog&orientation=landscape&size=medium&color=red&locale=en&page=2&per_page=10',
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
                            'original' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                            'large2x' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=1360&w=2000',
                            'large' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                            'medium' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=1.5&h=430&w=750',
                            'small' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=1&h=320&w=550',
                            'portrait' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=1&h=320&w=550',
                            'landscape' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=1&h=320&w=550',
                            'tiny' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=1&h=320&w=550',
                        ],
                        'liked' => false,
                        'alt' => 'A dog running on grass',
                    ]
                ],
            ]
        )
    )]
    public function searchPhotos(SearchPhotosRequest $request): JsonResponse
    {
        $data = SearchPhotosData::from($request->validated());

        $result = $this->service->searchPhotos($data);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/pexels/photos/curated',
        summary: 'Get curated photos',
        description: 'Get curated photos',
        tags: ["Pexels"],
        security: [['authentication' => []]],
    )]
    #[OA\Parameter(
        name: 'page',
        in: 'query',
        required: false,
        description: 'Page number',
        schema: new OA\Schema(type: 'integer', example: 1)
    )]
    #[OA\Parameter(
        name: 'per_page',
        in: 'query',
        required: false,
        description: 'Number of results per page',
        schema: new OA\Schema(type: 'integer', example: 15)
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'total_results' => 100,
                'per_page' => 15,
                'page' => 1,
                'prev_page' => 'https://www.pexels.com/api/v1/search?query=dog&orientation=landscape&size=medium&color=red&locale=en&page=1&per_page=10',
                'next_page' => 'https://www.pexels.com/api/v1/search?query=dog&orientation=landscape&size=medium&color=red&locale=en&page=2&per_page=10',
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
                            'original' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                            'large2x' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=1360&w=2000',
                            'large' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                            'medium' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=1.5&h=430&w=750',
                            'small' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=1&h=320&w=550',
                            'portrait' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=1&h=320&w=550',
                            'landscape' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=1&h=320&w=550',
                            'tiny' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=1&h=320&w=550',
                        ],
                        'liked' => false,
                        'alt' => 'A dog running on grass',
                    ]
                ]
            ]
        )
    )]
    public function getCuratedPhotos(GetCuratedPhotosRequest $request): JsonResponse
    {
        $data = GetCuratedPhotosData::from($request->validated());

        $result = $this->service->getCuratedPhotos($data);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/pexels/photos/{id}',
        summary: 'Get photo',
        description: 'Get photo',
        tags: ["Pexels"],
        security: [['authentication' => []]],
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'Photo ID',
        schema: new OA\Schema(type: 'string', example: '123456789')
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'id' => '123456789',
                'width' => 1080,
                'height' => 1080,
                'url' => 'https://www.pexels.com/photo/dog-running-on-grass-123456789/',
                'photographer' => 'John Doe',
                'photographer_url' => 'https://www.pexels.com/@johndoe',
                'photographer_id' => '123456789',
                'avg_color' => '#000000',
                'src' => [
                    'original' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                    'large2x' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=1360&w=2000',
                    'large' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                    'medium' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=1.5&h=430&w=750',
                    'small' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=1&h=320&w=550',
                    'portrait' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=1&h=320&w=550',
                    'landscape' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=1&h=320&w=550',
                    'tiny' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=1&h=320&w=550',
                ],
                'liked' => false,
                'alt' => 'A dog running on grass',
            ]
        )
    )]
    public function getPhoto(GetPhotoRequest $request): JsonResponse
    {
        $data = GetPhotoData::from($request->validated());

        $result = $this->service->getPhoto($data);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/pexels/videos/search',
        summary: 'Search videos',
        description: 'Search videos',
        tags: ["Pexels"],
        security: [['authentication' => []]],
    )]
    #[OA\Parameter(
        name: 'query',
        in: 'query',
        required: true,
        description: 'Search query',
        schema: new OA\Schema(type: 'string', example: 'dog')
    )]
    #[OA\Parameter(
        name: 'orientation',
        in: 'query',
        required: false,
        description: 'Orientation',
        schema: new OA\Schema(type: 'string', enum: ['landscape', 'portrait', 'square'], example: 'landscape')
    )]
    #[OA\Parameter(
        name: 'size',
        in: 'query',
        required: false,
        description: 'Size',
        schema: new OA\Schema(type: 'string', enum: ['small', 'medium', 'large'], example: 'medium')
    )]
    #[OA\Parameter(
        name: 'locale',
        in: 'query',
        required: false,
        description: 'Locale',
        schema: new OA\Schema(type: 'string', example: 'en-US')
    )]
    #[OA\Parameter(
        name: 'page',
        in: 'query',
        required: false,
        description: 'Page number',
        schema: new OA\Schema(type: 'integer', example: 1)
    )]
    #[OA\Parameter(
        name: 'per_page',
        in: 'query',
        required: false,
        description: 'Number of results per page',
        schema: new OA\Schema(type: 'integer', example: 15)
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'total_results' => 100,
                'per_page' => 15,
                'page' => 1,
                'prev_page' => 'https://www.pexels.com/api/v1/search?query=dog&orientation=landscape&size=medium&color=red&locale=en&page=1&per_page=10',
                'next_page' => 'https://www.pexels.com/api/v1/search?query=dog&orientation=landscape&size=medium&color=red&locale=en&page=2&per_page=10',
                'url' => 'https://www.pexels.com/videos/dog-running-on-grass-123456789/',
                'videos' => [
                    [
                        'id' => '123456789',
                        'width' => 1080,
                        'height' => 1080,
                        'url' => 'https://www.pexels.com/video/dog-running-on-grass-123456789/',
                        'image' => 'https://images.pexels.com/videos/123456789/dog-running-on-grass-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                        'duration' => 10,
                        'user' => [
                            'id' => '123456789',
                            'name' => 'John Doe',
                            'url' => 'https://www.pexels.com/@johndoe',
                        ],
                        'video_files' => [
                            [
                                'id' => '123456789',
                                'quality' => 'sd',
                                'file_type' => 'video/mp4',
                                'width' => 640,
                                'height' => 360,
                                'link' => 'https://videos.pexels.com/videos/123456789/dog-running-on-grass-123456789.mp4?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                            ],
                            [
                                'id' => '123456789',
                                'quality' => 'hd',
                                'file_type' => 'video/mp4',
                                'width' => 1280,
                                'height' => 720,
                                'link' => 'https://videos.pexels.com/videos/123456789/dog-running-on-grass-123456789.mp4?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                            ],
                        ],
                        'video_pictures' => [
                            [
                                'id' => '123456789',
                                'picture' => 'https://images.pexels.com/videos/123456789/dog-running-on-grass-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                                'nr' => 0,
                            ],
                            [
                                'id' => '123456789',
                                'picture' => 'https://images.pexels.com/videos/123456789/dog-running-on-grass-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                                'nr' => 1,
                            ],
                        ]
                    ]
                ]
            ]
        )
    )]
    public function searchVideos(SearchVideosRequest $request): JsonResponse
    {
        $data = SearchVideosData::from($request->validated());

        $result = $this->service->searchVideos($data);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/pexels/videos/popular',
        summary: 'Get popular videos',
        description: 'Get popular videos',
        tags: ["Pexels"],
        security: [['authentication' => []]],
    )]
    #[OA\Parameter(
        name: 'min_width',
        in: 'query',
        required: false,
        description: 'Minimum width',
        schema: new OA\Schema(type: 'integer', example: 1080)
    )]
    #[OA\Parameter(
        name: 'min_height',
        in: 'query',
        required: false,
        description: 'Minimum height',
        schema: new OA\Schema(type: 'integer', example: 1080)
    )]
    #[OA\Parameter(
        name: 'min_duration',
        in: 'query',
        required: false,
        description: 'Minimum duration',
        schema: new OA\Schema(type: 'integer', example: 10)
    )]
    #[OA\Parameter(
        name: 'max_duration',
        in: 'query',
        required: false,
        description: 'Maximum duration',
        schema: new OA\Schema(type: 'integer', example: 10)
    )]
    #[OA\Parameter(
        name: 'page',
        in: 'query',
        required: false,
        description: 'Page number',
        schema: new OA\Schema(type: 'integer', example: 1)
    )]
    #[OA\Parameter(
        name: 'per_page',
        in: 'query',
        required: false,
        description: 'Number of results per page',
        schema: new OA\Schema(type: 'integer', example: 15)
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'total_results' => 100,
                'per_page' => 15,
                'page' => 1,
                'prev_page' => 'https://www.pexels.com/api/v1/search?query=dog&orientation=landscape&size=medium&color=red&locale=en&page=1&per_page=10',
                'next_page' => 'https://www.pexels.com/api/v1/search?query=dog&orientation=landscape&size=medium&color=red&locale=en&page=2&per_page=10',
                'url' => 'https://www.pexels.com/videos/dog-running-on-grass-123456789/',
                'videos' => [
                    [
                        'id' => '123456789',
                        'width' => 1080,
                        'height' => 1080,
                        'url' => 'https://www.pexels.com/video/dog-running-on-grass-123456789/',
                        'image' => 'https://images.pexels.com/videos/123456789/dog-running-on-grass-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                        'duration' => 10,
                        'user' => [
                            'id' => '123456789',
                            'name' => 'John Doe',
                            'url' => 'https://www.pexels.com/@johndoe',
                        ],
                        'video_files' => [
                            [
                                'id' => '123456789',
                                'quality' => 'sd',
                                'file_type' => 'video/mp4',
                                'width' => 640,
                                'height' => 360,
                                'link' => 'https://videos.pexels.com/videos/123456789/dog-running-on-grass-123456789.mp4?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                            ],
                            [
                                'id' => '123456789',
                                'quality' => 'hd',
                                'file_type' => 'video/mp4',
                                'width' => 1280,
                                'height' => 720,
                                'link' => 'https://videos.pexels.com/videos/123456789/dog-running-on-grass-123456789.mp4?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                            ],
                        ],
                        'video_pictures' => [
                            [
                                'id' => '123456789',
                                'picture' => 'https://images.pexels.com/videos/123456789/dog-running-on-grass-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                                'nr' => 0,
                            ],
                            [
                                'id' => '123456789',
                                'picture' => 'https://images.pexels.com/videos/123456789/dog-running-on-grass-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                                'nr' => 1,
                            ],
                        ]
                    ]
                ],
            ]
        )
    )]
    public function getPopularVideos(GetPopularVideosRequest $request): JsonResponse
    {
        $data = GetPopularVideosData::from($request->validated());

        $result = $this->service->getPopularVideos($data);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/pexels/videos/{id}',
        summary: 'Get video',
        description: 'Get video',
        tags: ["Pexels"],
        security: [['authentication' => []]],
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'Video ID',
        schema: new OA\Schema(type: 'string', example: '123456789')
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'id' => '123456789',
                'width' => 1080,
                'height' => 1080,
                'url' => 'https://www.pexels.com/video/dog-running-on-grass-123456789/',
                'image' => 'https://images.pexels.com/videos/123456789/dog-running-on-grass-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                'duration' => 10,
                'user' => [
                    'id' => '123456789',
                    'name' => 'John Doe',
                    'url' => 'https://www.pexels.com/@johndoe',
                ],
                'video_files' => [
                    [
                        'id' => '123456789',
                        'quality' => 'sd',
                        'file_type' => 'video/mp4',
                        'width' => 640,
                        'height' => 360,
                        'link' => 'https://videos.pexels.com/videos/123456789/dog-running-on-grass-123456789.mp4?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                    ],
                    [
                        'id' => '123456789',
                        'quality' => 'hd',
                        'file_type' => 'video/mp4',
                        'width' => 1280,
                        'height' => 720,
                        'link' => 'https://videos.pexels.com/videos/123456789/dog-running-on-grass-123456789.mp4?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                    ],
                ],
                'video_pictures' => [
                    [
                        'id' => '123456789',
                        'picture' => 'https://images.pexels.com/videos/123456789/dog-running-on-grass-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                        'nr' => 0,
                    ],
                    [
                        'id' => '123456789',
                        'picture' => 'https://images.pexels.com/videos/123456789/dog-running-on-grass-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                        'nr' => 1,
                    ],
                ]
            ]
        )
    )]
    public function getVideo(GetVideoRequest $request): JsonResponse
    {
        $data = GetVideoData::from($request->validated());

        $result = $this->service->getVideo($data);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/pexels/collections/featured',
        summary: 'Get featured collections',
        description: 'Get featured collections',
        tags: ["Pexels"],
        security: [['authentication' => []]],
    )]
    #[OA\Parameter(
        name: 'page',
        in: 'query',
        required: false,
        description: 'Page number',
        schema: new OA\Schema(type: 'integer', example: 1)
    )]
    #[OA\Parameter(
        name: 'per_page',
        in: 'query',
        required: false,
        description: 'Number of results per page',
        schema: new OA\Schema(type: 'integer', example: 15)
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'total_results' => 100,
                'per_page' => 15,
                'page' => 1,
                'prev_page' => 'https://www.pexels.com/api/v1/search?query=dog&orientation=landscape&size=medium&color=red&locale=en&page=1&per_page=10',
                'next_page' => 'https://www.pexels.com/api/v1/search?query=dog&orientation=landscape&size=medium&color=red&locale=en&page=2&per_page=10',
                'collections' => [
                    [
                        'id' => '123456789',
                        'title' => 'Cool Dogs',
                        'description' => 'Dog Running on Grass',
                        'private' => false,
                        'media_count' => 10,
                        'photos_count' => 7,
                        'videos_count' => 3,
                    ]
                ],
            ]
        )
    )]
    public function getFeaturedCollections(GetFeaturedCollectionsRequest $request): JsonResponse
    {
        $data = GetFeaturedCollectionsData::from($request->validated());

        $result = $this->service->getFeaturedCollections($data);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/pexels/collection/{id}',
        summary: 'Get collections',
        description: 'Get collections',
        tags: ["Pexels"],
        security: [['authentication' => []]],
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'Collection ID',
        schema: new OA\Schema(type: 'string', example: '123456789')
    )]
    #[OA\Parameter(
        name: 'type',
        in: 'query',
        required: false,
        description: 'Collection type',
        schema: new OA\Schema(type: 'string', enum: ['photos', 'videos'], example: 'photos')
    )]
    #[OA\Parameter(
        name: 'sort',
        in: 'query',
        required: false,
        description: 'Sort',
        schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'], example: 'asc')
    )]
    #[OA\Parameter(
        name: 'page',
        in: 'query',
        required: false,
        description: 'Page number',
        schema: new OA\Schema(type: 'integer', example: 1)
    )]
    #[OA\Parameter(
        name: 'per_page',
        in: 'query',
        required: false,
        description: 'Number of results per page',
        schema: new OA\Schema(type: 'integer', example: 15)
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'total_results' => 100,
                'per_page' => 15,
                'page' => 1,
                'prev_page' => 'https://www.pexels.com/api/v1/search?query=dog&orientation=landscape&size=medium&color=red&locale=en&page=1&per_page=10',
                'next_page' => 'https://www.pexels.com/api/v1/search?query=dog&orientation=landscape&size=medium&color=red&locale=en&page=2&per_page=10',
                'id' => '123456789',
                'media' => [
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
                            'original' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                            'large2x' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=1360&w=2000',
                            'large' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                            'medium' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=1.5&h=430&w=750',
                            'small' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=1&h=320&w=550',
                            'portrait' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=1&h=320&w=550',
                            'landscape' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=1&h=320&w=550',
                            'tiny' => 'https://images.pexels.com/photos/123456789/pexels-photo-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=1&h=320&w=550',
                        ],
                        'liked' => false,
                        'alt' => 'A dog running on grass',
                    ],
                    [
                        'id' => '123456789',
                        'width' => 1080,
                        'height' => 1080,
                        'url' => 'https://www.pexels.com/video/dog-running-on-grass-123456789/',
                        'image' => 'https://images.pexels.com/videos/123456789/dog-running-on-grass-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                        'duration' => 10,
                        'user' => [
                            'id' => '123456789',
                            'name' => 'John Doe',
                            'url' => 'https://www.pexels.com/@johndoe',
                        ],
                        'video_files' => [
                            [
                                'id' => '123456789',
                                'quality' => 'sd',
                                'file_type' => 'video/mp4',
                                'width' => 640,
                                'height' => 360,
                                'link' => 'https://videos.pexels.com/videos/123456789/dog-running-on-grass-123456789.mp4?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                            ],
                            [
                                'id' => '123456789',
                                'quality' => 'hd',
                                'file_type' => 'video/mp4',
                                'width' => 1280,
                                'height' => 720,
                                'link' => 'https://videos.pexels.com/videos/123456789/dog-running-on-grass-123456789.mp4?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                            ],
                        ],
                        'video_pictures' => [
                            [
                                'id' => '123456789',
                                'picture' => 'https://images.pexels.com/videos/123456789/dog-running-on-grass-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                                'nr' => 0,
                            ],
                            [
                                'id' => '123456789',
                                'picture' => 'https://images.pexels.com/videos/123456789/dog-running-on-grass-123456789.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                                'nr' => 1,
                            ],
                        ]
                    ]
                ],
            ]
        )
    )]
    public function getCollection(GetCollectionRequest $request): JsonResponse
    {
        $data = GetCollectionData::from($request->validated());

        $result = $this->service->getCollection($data);

        return $this->logAndResponse($result);
    }
}
