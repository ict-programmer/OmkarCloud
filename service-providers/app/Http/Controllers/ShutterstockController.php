<?php

namespace App\Http\Controllers;

use App\Data\Request\Shutterstock\SearchImagesData;
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
} 