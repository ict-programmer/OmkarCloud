<?php

namespace App\Http\Controllers;

use App\Data\Request\Shutterstock\AddToCollectionData;
use App\Data\Request\Shutterstock\AddToVideoCollectionData;
use App\Data\Request\Shutterstock\CreateCollectionData;
use App\Data\Request\Shutterstock\CreateVideoCollectionData;
use App\Data\Request\Shutterstock\DownloadImageData;
use App\Data\Request\Shutterstock\DownloadVideoData;
use App\Data\Request\Shutterstock\GetImageData;
use App\Data\Request\Shutterstock\GetVideoData;
use App\Data\Request\Shutterstock\LicenseImageData;
use App\Data\Request\Shutterstock\LicenseVideoData;
use App\Data\Request\Shutterstock\SearchImagesData;
use App\Data\Request\Shutterstock\SearchVideosData;
use App\Data\Request\Shutterstock\SearchAudioData;
use App\Data\Request\Shutterstock\GetAudioData;
use App\Data\Request\Shutterstock\LicenseAudioData;
use App\Data\Request\Shutterstock\DownloadAudioData;
use App\Http\Requests\Shutterstock\AddToCollectionRequest;
use App\Http\Requests\Shutterstock\AddToVideoCollectionRequest;
use App\Http\Requests\Shutterstock\CreateCollectionRequest;
use App\Http\Requests\Shutterstock\CreateVideoCollectionRequest;
use App\Http\Requests\Shutterstock\DownloadImageRequest;
use App\Http\Requests\Shutterstock\DownloadVideoRequest;
use App\Http\Requests\Shutterstock\GetImageRequest;
use App\Http\Requests\Shutterstock\GetVideoRequest;
use App\Http\Requests\Shutterstock\LicenseImageRequest;
use App\Http\Requests\Shutterstock\LicenseVideoRequest;
use App\Http\Requests\Shutterstock\SearchImagesRequest;
use App\Http\Requests\Shutterstock\SearchVideosRequest;
use App\Http\Requests\Shutterstock\SearchAudioRequest;
use App\Http\Requests\Shutterstock\GetAudioRequest;
use App\Http\Requests\Shutterstock\LicenseAudioRequest;
use App\Http\Requests\Shutterstock\DownloadAudioRequest;
use App\Http\Requests\Shutterstock\ListUserSubscriptionsRequest;
use App\Services\ShutterstockService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ShutterstockController extends BaseController
{
    public function __construct(protected ShutterstockService $service) {}

    #[OA\Post(
        path: '/api/shutterstock/create_collection',
        operationId: 'shutterstock_create_collection',
        description: 'Create an image collection (lightbox) using Shutterstock API',
        summary: 'Shutterstock Create Collection',
        security: [['authentication' => []]],
        tags: ['Shutterstock'],
    )]
    #[OA\RequestBody(
        description: 'Create collection request with name parameter',
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                required: ['name'],
                properties: [
                    new OA\Property(
                        property: 'name',
                        description: 'The name of the collection to create',
                        type: 'string',
                        example: 'My collection'
                    ),
                ],
                type: 'object'
            )
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Successfully created image collection',
        content: new OA\JsonContent(
            example: [
                'message' => 'Collection created successfully.',
                'data' => [
                    'id' => '48433105'
                ]
            ]
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'validation_error',
                'message' => [
                    'name' => 'The name field is required.',
                ],
            ]
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized - Invalid API token',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Unauthorized access'
            ]
        )
    )]
    #[OA\Response(
        response: 403,
        description: 'Forbidden - Insufficient permissions',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Insufficient permissions to create collections'
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Failed to create collection'
            ]
        )
    )]
    public function createCollection(CreateCollectionRequest $request): JsonResponse
    {
        $data = CreateCollectionData::from($request->validated());

        $result = $this->service->createCollection($data);

        return $this->logAndResponse([
            'message' => 'Collection created successfully.',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/shutterstock/add_to_collection',
        operationId: 'shutterstock_add_to_collection',
        description: 'Add images to a collection using Shutterstock API',
        summary: 'Shutterstock Add Images to Collection',
        security: [['authentication' => []]],
        tags: ['Shutterstock'],
    )]
    #[OA\RequestBody(
        description: 'Add images to collection request with collection_id and items array',
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                required: ['collection_id', 'items'],
                properties: [
                    new OA\Property(
                        property: 'collection_id',
                        description: 'The ID of the collection to add images to',
                        type: 'string',
                        example: '326120296'
                    ),
                    new OA\Property(
                        property: 'items',
                        description: 'Array of items to add to the collection',
                        type: 'array',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'id', type: 'string', example: '49572945'),
                                new OA\Property(property: 'media_type', type: 'string', example: 'image')
                            ],
                            type: 'object'
                        ),
                        example: [
                            [
                                'id' => '49572945',
                                'media_type' => 'image'
                            ]
                        ]
                    ),
                ],
                type: 'object'
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successfully added images to collection',
        content: new OA\JsonContent(
            example: [
                'message' => 'Images added to collection successfully.',
                'data' => []
            ]
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'validation_error',
                'message' => [
                    'collection_id' => 'The collection_id field is required.',
                    'items' => 'The items field is required.',
                ],
            ]
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized - Invalid API token',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Unauthorized access'
            ]
        )
    )]
    #[OA\Response(
        response: 403,
        description: 'Forbidden - Insufficient permissions',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Insufficient permissions to modify collections'
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Collection not found',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Collection not found'
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Failed to add images to collection'
            ]
        )
    )]
    public function addToCollection(AddToCollectionRequest $request): JsonResponse
    {
        $data = AddToCollectionData::from($request->validated());

        $this->service->addToCollection($data);

        return $this->logAndResponse([
            'message' => 'Item added to collection successfully.',
        ]);
    }

    #[OA\Post(
        path: '/api/shutterstock/search_images',
        operationId: 'shutterstock_search_images',
        description: 'Search for images using Shutterstock API',
        summary: 'Shutterstock Image Search',
        security: [['authentication' => []]],
        tags: ['Shutterstock'],
    )]
    #[OA\RequestBody(
        description: 'Search images request with query and orientation parameters',
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                required: ['query', 'orientation'],
                properties: [
                    new OA\Property(
                        property: 'query',
                        description: 'Search query for images',
                        type: 'string',
                        example: 'Vienna'
                    ),
                    new OA\Property(
                        property: 'orientation',
                        description: 'Image orientation',
                        type: 'string',
                        enum: ['horizontal', 'vertical', 'square'],
                        example: 'horizontal'
                    ),
                ],
                type: 'object'
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'message' => 'Images search successful.',
                'data' => [
                    'data' => [
                        [
                            'id' => '1572478477',
                            'aspect' => 1.5,
                            'description' => 'cropped image of woman gardening',
                            'image_type' => 'photo',
                            'assets' => [
                                'preview' => [
                                    'height' => 300,
                                    'url' => 'https://image.shutterstock.com/display_pic_with_logo/250738318/1572478477/stock-photo-cropped-image-of-woman-gardening-1572478477.jpg',
                                    'width' => 450
                                ]
                            ]
                        ]
                    ],
                    'page' => 1,
                    'per_page' => 5,
                    'total_count' => 45
                ]
            ]
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'validation_error',
                'message' => [
                    'query' => 'The query field is required.',
                    'orientation' => 'The orientation field is required.',
                ],
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Failed to search images'
            ]
        )
    )]
    public function searchImages(SearchImagesRequest $request): JsonResponse
    {
        $data = SearchImagesData::from($request->validated());

        $result = $this->service->searchImages($data);

        return $this->logAndResponse([
            'message' => 'Images search successful.',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/shutterstock/get_image',
        operationId: 'shutterstock_get_image',
        description: 'Get details about a specific image using Shutterstock API',
        summary: 'Shutterstock Get Image Details',
        security: [['authentication' => []]],
        tags: ['Shutterstock'],
    )]
    #[OA\RequestBody(
        description: 'Get image request with image_id parameter',
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                required: ['image_id'],
                properties: [
                    new OA\Property(
                        property: 'image_id',
                        description: 'Shutterstock image ID',
                        type: 'string',
                        example: '465011609'
                    ),
                ],
                type: 'object'
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'message' => 'Image details retrieved successfully.',
                'data' => [
                    'id' => '465011609',
                    'aspect' => 1.5,
                    'description' => 'Beautiful landscape with mountains',
                    'image_type' => 'photo',
                    'has_model_release' => true,
                    'media_type' => 'image',
                    'assets' => [
                        'preview' => [
                            'height' => 300,
                            'url' => 'https://image.shutterstock.com/display_pic_with_logo/465011609.jpg',
                            'width' => 450
                        ],
                        'small_thumb' => [
                            'height' => 67,
                            'url' => 'https://thumb7.shutterstock.com/thumb_small/465011609.jpg',
                            'width' => 100
                        ]
                    ],
                    'contributor' => [
                        'id' => '250738318'
                    ]
                ]
            ]
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'validation_error',
                'message' => [
                    'image_id' => 'The image_id field is required.',
                ],
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Image not found',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Image not found'
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Failed to get image details'
            ]
        )
    )]
    public function getImage(GetImageRequest $request): JsonResponse
    {
        $data = GetImageData::from($request->validated());

        $result = $this->service->getImage($data);

        return $this->logAndResponse([
            'message' => 'Image details retrieved successfully.',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/shutterstock/license_image',
        operationId: 'shutterstock_license_image',
        description: 'License an image using Shutterstock API',
        summary: 'Shutterstock License Image',
        security: [['authentication' => []]],
        tags: ['Shutterstock'],
    )]
    #[OA\RequestBody(
        description: 'License image request with image_id parameter',
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                required: ['image_id'],
                properties: [
                    new OA\Property(
                        property: 'image_id',
                        description: 'Shutterstock image ID to license',
                        type: 'string',
                        example: '59656357'
                    ),
                ],
                type: 'object'
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'message' => 'Image licensed successfully.',
                'data' => [
                    'data' => [
                        [
                            'image_id' => '59656357',
                            'download' => [
                                'url' => 'https://download.shutterstock.com/gatekeeper/[random-characters]/shutterstock_59656357.jpg'
                            ],
                            'allotment_charge' => 1
                        ]
                    ]
                ]
            ]
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'validation_error',
                'message' => [
                    'image_id' => 'The image_id field is required.',
                ],
            ]
        )
    )]
    #[OA\Response(
        response: 403,
        description: 'Insufficient subscription or credits',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Insufficient subscription or credits to license this image'
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Failed to license image'
            ]
        )
    )]
    public function licenseImage(LicenseImageRequest $request): JsonResponse
    {
        $data = LicenseImageData::from($request->validated());

        $result = $this->service->licenseImage($data);

        return $this->logAndResponse([
            'message' => 'Image licensed successfully.',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/shutterstock/download_image',
        operationId: 'shutterstock_download_image',
        description: 'Get a redownload link for a previously licensed image using Shutterstock API',
        summary: 'Shutterstock Download Image',
        security: [['authentication' => []]],
        tags: ['Shutterstock'],
    )]
    #[OA\RequestBody(
        description: 'Download image request with license_id parameter',
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                required: ['license_id'],
                properties: [
                    new OA\Property(
                        property: 'license_id',
                        description: 'License ID from a previously licensed image',
                        type: 'string',
                        example: 'i4117504971'
                    ),
                ],
                type: 'object'
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'message' => 'Download link generated successfully.',
                'data' => [
                    'url' => 'https://download.shutterstock.com/gatekeeper/[random-characters]/shutterstock_1079756147.jpg'
                ]
            ]
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'validation_error',
                'message' => [
                    'license_id' => 'The license_id field is required.',
                ],
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'License not found',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'License not found'
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Failed to generate download link'
            ]
        )
    )]
    public function downloadImage(DownloadImageRequest $request): JsonResponse
    {
        $data = DownloadImageData::from($request->validated());

        $result = $this->service->downloadImage($data);

        return $this->logAndResponse([
            'message' => 'Download link generated successfully.',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/shutterstock/videos/search',
        operationId: 'shutterstock_search_videos',
        description: 'Search for videos using Shutterstock API',
        summary: 'Shutterstock Video Search',
        security: [['authentication' => []]],
        tags: ['Shutterstock'],
    )]
    #[OA\RequestBody(
        description: 'Search videos request with query and orientation parameters',
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                required: ['query', 'orientation'],
                properties: [
                    new OA\Property(
                        property: 'query',
                        description: 'Search query for videos',
                        type: 'string',
                        example: 'nature'
                    ),
                    new OA\Property(
                        property: 'orientation',
                        description: 'Video orientation',
                        type: 'string',
                        enum: ['horizontal', 'vertical', 'square'],
                        example: 'horizontal'
                    ),
                ],
                type: 'object'
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'message' => 'Videos search successful.',
                'data' => [
                    'data' => [
                        [
                            'id' => '1012345678',
                            'aspect' => 1.78,
                            'description' => 'Beautiful nature video',
                            'media_type' => 'video',
                            'assets' => [
                                'preview_mp4' => [
                                    'url' => 'https://ak.picdn.net/shutterstock/videos/1012345678/preview/stock-footage-beautiful-nature.mp4'
                                ]
                            ]
                        ]
                    ],
                    'page' => 1,
                    'per_page' => 5,
                    'total_count' => 45
                ]
            ]
        )
    )]
    public function searchVideos(SearchVideosRequest $request): JsonResponse
    {
        $data = SearchVideosData::from($request->validated());
        $result = $this->service->searchVideos($data);

        return $this->logAndResponse([
            'message' => 'Videos search successful.',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/shutterstock/videos/get',
        operationId: 'shutterstock_get_video',
        description: 'Get details about a specific video using Shutterstock API',
        summary: 'Shutterstock Get Video Details',
        security: [['authentication' => []]],
        tags: ['Shutterstock'],
    )]
    #[OA\RequestBody(
        description: 'Get video request with video_id parameter',
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                required: ['video_id'],
                properties: [
                    new OA\Property(
                        property: 'video_id',
                        description: 'Shutterstock video ID',
                        type: 'string',
                        example: '1012345678'
                    ),
                ],
                type: 'object'
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'message' => 'Video details retrieved successfully.',
                'data' => [
                    'id' => '1012345678',
                    'aspect' => 1.78,
                    'description' => 'Beautiful nature video',
                    'media_type' => 'video',
                    'duration' => 15.5,
                    'fps' => 29.97
                ]
            ]
        )
    )]
    public function getVideo(GetVideoRequest $request): JsonResponse
    {
        $data = GetVideoData::from($request->validated());
        $result = $this->service->getVideo($data);

        return $this->logAndResponse([
            'message' => 'Video details retrieved successfully.',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/shutterstock/videos/license',
        operationId: 'shutterstock_license_video',
        description: 'License one or more videos using Shutterstock API',
        summary: 'Shutterstock License Videos',
        security: [['authentication' => []]],
        tags: ['Shutterstock'],
    )]
    #[OA\RequestBody(
        description: 'License videos request with videos array containing video_id, subscription_id, and size',
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                required: ['videos'],
                properties: [
                    new OA\Property(
                        property: 'videos',
                        description: 'Array of videos to license',
                        type: 'array',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'video_id', type: 'string', example: '2140697'),
                                new OA\Property(property: 'subscription_id', type: 'string', example: 's12345678'),
                                new OA\Property(property: 'size', type: 'string', enum: ['web', 'sd', 'hd', '4k'], example: 'hd')
                            ],
                            type: 'object'
                        ),
                        example: [
                            [
                                'video_id' => '2140697',
                                'subscription_id' => 's12345678',
                                'size' => 'hd'
                            ]
                        ]
                    ),
                    new OA\Property(
                        property: 'search_id',
                        description: 'The Search ID that led to this licensing event',
                        type: 'string',
                        example: '749090bb-2967-4a20-b22e-c800dc845e10'
                    ),
                ],
                type: 'object'
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'message' => 'Videos licensed successfully.',
                'data' => [
                    'data' => [
                        [
                            'video_id' => '2140697',
                            'download' => [
                                'url' => 'https://download.shutterstock.com/gatekeeper/[random-characters]/shutterstock_2140697.mp4'
                            ],
                            'allotment_charge' => 1,
                            'price' => [
                                'local_amount' => 12.34,
                                'local_currency' => 'EUR'
                            ]
                        ]
                    ],
                    'page' => 1,
                    'per_page' => 5,
                    'total_count' => 1
                ]
            ]
        )
    )]
    public function licenseVideo(LicenseVideoRequest $request): JsonResponse
    {
        $data = LicenseVideoData::from($request->validated());
        $result = $this->service->licenseVideo($data);

        return $this->logAndResponse([
            'message' => 'Videos licensed successfully.',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/shutterstock/videos/download',
        operationId: 'shutterstock_download_video',
        description: 'Get a redownload link for a previously licensed video using Shutterstock API',
        summary: 'Shutterstock Download Video',
        security: [['authentication' => []]],
        tags: ['Shutterstock'],
    )]
    #[OA\RequestBody(
        description: 'Download video request with license_id parameter',
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                required: ['license_id'],
                properties: [
                    new OA\Property(
                        property: 'license_id',
                        description: 'License ID from a previously licensed video',
                        type: 'string',
                        example: 'v4117504971'
                    ),
                ],
                type: 'object'
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'message' => 'Download link generated successfully.',
                'data' => [
                    'url' => 'https://download.shutterstock.com/gatekeeper/[random-characters]/shutterstock_1012345678.mov'
                ]
            ]
        )
    )]
    public function downloadVideo(DownloadVideoRequest $request): JsonResponse
    {
        $data = DownloadVideoData::from($request->validated());
        $result = $this->service->downloadVideo($data);

        return $this->logAndResponse([
            'message' => 'Download link generated successfully.',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/shutterstock/audio/search',
        operationId: 'shutterstock_search_audio',
        description: 'Search for audio tracks using Shutterstock API. This endpoint allows you to find audio content based on various criteria including query terms, artists, genre, mood, and duration.',
        summary: 'Shutterstock Audio Search',
        security: [['authentication' => []]],
        tags: ['Shutterstock'],
    )]
    #[OA\RequestBody(
        description: 'Search audio tracks request with query and optional sort parameter',
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                required: ['query'],
                properties: [
                    new OA\Property(
                        property: 'query',
                        description: 'Search query for audio tracks',
                        type: 'string',
                        example: 'upbeat jazz'
                    ),
                    new OA\Property(
                        property: 'sort',
                        description: 'Sort order for search results',
                        type: 'string',
                        enum: ['score', 'ranking_all', 'artist', 'title', 'bpm', 'freshness', 'duration'],
                        example: 'score'
                    ),
                ],
                type: 'object'
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'message' => 'Audio search successful.',
                'data' => [
                    'data' => [
                        [
                            'id' => '1234567890',
                            'title' => 'Upbeat Jazz Track',
                            'description' => 'A lively jazz composition perfect for commercial use',
                            'duration' => 180.5,
                            'assets' => [
                                'preview_mp3' => [
                                    'url' => 'https://ak.picdn.net/shutterstock/audio/1234567890/preview/preview.mp3'
                                ]
                            ],
                            'contributor' => [
                                'id' => '12345678'
                            ]
                        ]
                    ],
                    'page' => 1,
                    'per_page' => 20,
                    'total_count' => 150
                ]
            ]
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'validation_error',
                'message' => [
                    'query' => 'The query field is required.',
                ],
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Failed to search audio tracks'
            ]
        )
    )]
    public function searchAudio(SearchAudioRequest $request): JsonResponse
    {
        $data = SearchAudioData::from($request->validated());
        $result = $this->service->searchAudio($data);

        return $this->logAndResponse([
            'message' => 'Audio search successful.',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/shutterstock/audio/get',
        operationId: 'shutterstock_get_audio',
        description: 'Get details about a specific audio track using Shutterstock API. This endpoint retrieves comprehensive information about an audio track including metadata, assets, and licensing details.',
        summary: 'Shutterstock Get Audio Track Details',
        security: [['authentication' => []]],
        tags: ['Shutterstock'],
    )]
    #[OA\RequestBody(
        description: 'Get audio track request with audio_id parameter',
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                required: ['audio_id'],
                properties: [
                    new OA\Property(
                        property: 'audio_id',
                        description: 'Shutterstock audio track ID',
                        type: 'string',
                        example: '1234567890'
                    ),
                ],
                type: 'object'
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'message' => 'Audio track details retrieved successfully.',
                'data' => [
                    'id' => '1234567890',
                    'title' => 'Upbeat Jazz Track',
                    'description' => 'A lively jazz composition perfect for commercial use',
                    'duration' => 180.5,
                    'bpm' => 120,
                    'instruments' => ['Piano', 'Saxophone', 'Drums'],
                    'genres' => ['Jazz', 'Upbeat'],
                    'moods' => ['Happy', 'Energetic'],
                    'media_type' => 'audio',
                    'assets' => [
                        'preview_mp3' => [
                            'url' => 'https://ak.picdn.net/shutterstock/audio/1234567890/preview/preview.mp3'
                        ],
                        'waveform_png' => [
                            'url' => 'https://ak.picdn.net/shutterstock/audio/1234567890/waveform/waveform.png'
                        ]
                    ],
                    'contributor' => [
                        'id' => '12345678'
                    ]
                ]
            ]
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'validation_error',
                'message' => [
                    'audio_id' => 'The audio_id field is required.',
                ],
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Audio track not found',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Audio track not found'
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Failed to get audio track details'
            ]
        )
    )]
    public function getAudio(GetAudioRequest $request): JsonResponse
    {
        $data = GetAudioData::from($request->validated());
        $result = $this->service->getAudio($data);

        return $this->logAndResponse([
            'message' => 'Audio track details retrieved successfully.',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/shutterstock/audio/license',
        operationId: 'shutterstock_license_audio',
        description: 'License one or more audio tracks using Shutterstock API. This endpoint allows you to license audio tracks for commercial use.',
        summary: 'Shutterstock License Audio Tracks',
        security: [['authentication' => []]],
        tags: ['Shutterstock'],
    )]
    #[OA\RequestBody(
        description: 'License audio tracks request with audio_tracks array containing audio_id and license type',
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                required: ['audio_tracks'],
                properties: [
                    new OA\Property(
                        property: 'audio_tracks',
                        description: 'Array of audio tracks to license',
                        type: 'array',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'audio_id', type: 'string', example: '1234567890'),
                                new OA\Property(property: 'license', type: 'string', enum: ['audio_platform', 'premier_music_basic', 'premier_music_extended', 'premier_music_pro', 'premier_music_comp', 'audio_standard', 'audio_enhanced'], example: 'audio_platform')
                            ],
                            type: 'object'
                        ),
                        example: [
                            [
                                'audio_id' => '1234567890',
                                'license' => 'audio_platform'
                            ]
                        ]
                    ),
                    new OA\Property(
                        property: 'search_id',
                        description: 'The Search ID that led to this licensing event',
                        type: 'string',
                        example: '749090bb-2967-4a20-b22e-c800dc845e10'
                    ),
                ],
                type: 'object'
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'message' => 'Audio tracks licensed successfully.',
                'data' => [
                    'data' => [
                        [
                            'audio_id' => '1234567890',
                            'download' => [
                                'url' => 'https://download.shutterstock.com/gatekeeper/[random-characters]/shutterstock_1234567890.mp3'
                            ],
                            'allotment_charge' => 1,
                            'price' => [
                                'local_amount' => 9.99,
                                'local_currency' => 'USD'
                            ]
                        ]
                    ]
                ]
            ]
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'validation_error',
                'message' => [
                    'audio_tracks' => 'The audio_tracks field is required.',
                ],
            ]
        )
    )]
    #[OA\Response(
        response: 403,
        description: 'Insufficient subscription or credits',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Insufficient subscription or credits to license this audio track'
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Failed to license audio tracks'
            ]
        )
    )]
    public function licenseAudio(LicenseAudioRequest $request): JsonResponse
    {
        $data = LicenseAudioData::from($request->validated());
        $result = $this->service->licenseAudio($data);

        return $this->logAndResponse([
            'message' => 'Audio tracks licensed successfully.',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/shutterstock/audio/download',
        operationId: 'shutterstock_download_audio',
        description: 'Get a redownload link for a previously licensed audio track using Shutterstock API',
        summary: 'Shutterstock Download Audio Track',
        security: [['authentication' => []]],
        tags: ['Shutterstock'],
    )]
    #[OA\RequestBody(
        description: 'Download audio track request with license_id parameter',
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                required: ['license_id'],
                properties: [
                    new OA\Property(
                        property: 'license_id',
                        description: 'License ID from a previously licensed audio track',
                        type: 'string',
                        example: 'a4117504971'
                    ),
                ],
                type: 'object'
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'message' => 'Download link generated successfully.',
                'data' => [
                    'url' => 'https://download.shutterstock.com/gatekeeper/[random-characters]/shutterstock_1234567890.wav'
                ]
            ]
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'validation_error',
                'message' => [
                    'license_id' => 'The license_id field is required.',
                ],
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'License not found',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'License not found'
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Failed to generate download link'
            ]
        )
    )]
    public function downloadAudio(DownloadAudioRequest $request): JsonResponse
    {
        $data = DownloadAudioData::from($request->validated());
        $result = $this->service->downloadAudio($data);

        return $this->logAndResponse([
            'message' => 'Download link generated successfully.',
            'data' => $result,
        ]);
    }

    #[OA\Get(
        path: '/api/shutterstock/user/subscriptions',
        operationId: 'shutterstock_list_user_subscriptions',
        description: 'List user subscriptions using Shutterstock API. This endpoint retrieves information about the current user\'s subscriptions including license type, allotments, and expiration dates.',
        summary: 'Shutterstock List User Subscriptions',
        security: [['authentication' => []]],
        tags: ['Shutterstock'],
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'message' => 'User subscriptions retrieved successfully.',
                'data' => [
                    'data' => [
                        [
                            'id' => 's12345678',
                            'license' => 'standard',
                            'description' => 'Monthly subscription with 10 images',
                            'allotment' => [
                                'downloads_left' => 8,
                                'downloads_limit' => 10,
                                'resets_at' => '2024-02-01T00:00:00Z'
                            ],
                            'expiration_time' => '2024-02-01T00:00:00Z',
                            'formats' => [
                                [
                                    'format' => 'jpg',
                                    'media_type' => 'image',
                                    'min_resolution' => 4000,
                                    'size' => 'huge'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized - Invalid API token',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Unauthorized access'
            ]
        )
    )]
    #[OA\Response(
        response: 403,
        description: 'Forbidden - Insufficient permissions',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Insufficient permissions to view subscriptions'
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Failed to retrieve user subscriptions'
            ]
        )
    )]
    public function listUserSubscriptions(): JsonResponse
    {
        $result = $this->service->listUserSubscriptions();

        return $this->logAndResponse([
            'message' => 'User subscriptions retrieved successfully.',
            'data' => $result,
        ]);
    }
} 