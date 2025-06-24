<?php

namespace App\Http\Controllers;

use App\Data\Request\Shutterstock\AddToCollectionData;
use App\Data\Request\Shutterstock\CreateCollectionData;
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
use App\Http\Requests\Shutterstock\CreateCollectionRequest;
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

    public function searchImages(SearchImagesRequest $request): JsonResponse
    {
        $data = SearchImagesData::from($request->validated());

        $result = $this->service->searchImages($data);

        return $this->logAndResponse([
            'message' => 'Images search successful.',
            'data' => $result,
        ]);
    }

    public function getImage(GetImageRequest $request): JsonResponse
    {
        $data = GetImageData::from($request->validated());

        $result = $this->service->getImage($data);

        return $this->logAndResponse([
            'message' => 'Image details retrieved successfully.',
            'data' => $result,
        ]);
    }

    public function licenseImage(LicenseImageRequest $request): JsonResponse
    {
        $data = LicenseImageData::from($request->validated());

        $result = $this->service->licenseImage($data);

        return $this->logAndResponse([
            'message' => 'Image licensed successfully.',
            'data' => $result,
        ]);
    }

    public function downloadImage(DownloadImageRequest $request): JsonResponse
    {
        $data = DownloadImageData::from($request->validated());

        $result = $this->service->downloadImage($data);

        return $this->logAndResponse([
            'message' => 'Download link generated successfully.',
            'data' => $result,
        ]);
    }

    public function searchVideos(SearchVideosRequest $request): JsonResponse
    {
        $data = SearchVideosData::from($request->validated());
        $result = $this->service->searchVideos($data);

        return $this->logAndResponse([
            'message' => 'Videos search successful.',
            'data' => $result,
        ]);
    }

    public function getVideo(GetVideoRequest $request): JsonResponse
    {
        $data = GetVideoData::from($request->validated());
        $result = $this->service->getVideo($data);

        return $this->logAndResponse([
            'message' => 'Video details retrieved successfully.',
            'data' => $result,
        ]);
    }

    public function licenseVideo(LicenseVideoRequest $request): JsonResponse
    {
        $data = LicenseVideoData::from($request->validated());
        $result = $this->service->licenseVideo($data);

        return $this->logAndResponse([
            'message' => 'Videos licensed successfully.',
            'data' => $result,
        ]);
    }

    public function downloadVideo(DownloadVideoRequest $request): JsonResponse
    {
        $data = DownloadVideoData::from($request->validated());
        $result = $this->service->downloadVideo($data);

        return $this->logAndResponse([
            'message' => 'Download link generated successfully.',
            'data' => $result,
        ]);
    }

    public function searchAudio(SearchAudioRequest $request): JsonResponse
    {
        $data = SearchAudioData::from($request->validated());
        $result = $this->service->searchAudio($data);

        return $this->logAndResponse([
            'message' => 'Audio search successful.',
            'data' => $result,
        ]);
    }

    public function getAudio(GetAudioRequest $request): JsonResponse
    {
        $data = GetAudioData::from($request->validated());
        $result = $this->service->getAudio($data);

        return $this->logAndResponse([
            'message' => 'Audio track details retrieved successfully.',
            'data' => $result,
        ]);
    }

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