<?php

namespace App\Http\Controllers;

use App\Data\Request\Shutterstock\DownloadImageData;
use App\Data\Request\Shutterstock\GetImageData;
use App\Data\Request\Shutterstock\LicenseImageData;
use App\Data\Request\Shutterstock\SearchImagesData;
use App\Http\Requests\Shutterstock\DownloadImageRequest;
use App\Http\Requests\Shutterstock\GetImageRequest;
use App\Http\Requests\Shutterstock\LicenseImageRequest;
use App\Http\Requests\Shutterstock\SearchImagesRequest;
use App\Services\ShutterstockService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ShutterstockController extends BaseController
{
    public function __construct(protected ShutterstockService $service) {}

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
} 