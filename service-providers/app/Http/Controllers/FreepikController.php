<?php

namespace App\Http\Controllers;

use App\Data\Request\Freepik\AiImageClassifierData;
use App\Data\Request\Freepik\DownloadResourceFormatData;
use App\Data\Request\Freepik\StockContentData;
use App\Http\Requests\Freepik\AiImageClassifierRequest;
use App\Http\Requests\Freepik\DownloadResourceFormatRequest;
use App\Http\Requests\Freepik\StockContentRequest;
use App\Services\FreepikService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class FreepikController extends BaseController
{
    public function __construct(protected FreepikService $service) {}

    #[OA\Get(
        path: '/api/freepik/stock_content',
        operationId: 'stockContent',
        description: 'Get stock resources from Freepik with full filtering support',
        summary: 'Freepik stock content endpoint',
        tags: ['Freepik'],
    )]
    #[OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'limit', in: 'query', required: true, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100, example: 20))]
    #[OA\Parameter(name: 'order', in: 'query', required: true, schema: new OA\Schema(type: 'string', enum: ['relevance', 'recent']))]
    #[OA\Parameter(name: 'term', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'slug', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]

    #[OA\Parameter(name: 'filters[orientation][landscape]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]
    #[OA\Parameter(name: 'filters[orientation][portrait]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]
    #[OA\Parameter(name: 'filters[orientation][square]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]
    #[OA\Parameter(name: 'filters[orientation][panoramic]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]

    #[OA\Parameter(name: 'filters[content_type][photo]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]
    #[OA\Parameter(name: 'filters[content_type][psd]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]
    #[OA\Parameter(name: 'filters[content_type][vector]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]

    #[OA\Parameter(name: 'filters[license][freemium]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]
    #[OA\Parameter(name: 'filters[license][premium]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]

    #[OA\Parameter(name: 'filters[people][include]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]
    #[OA\Parameter(name: 'filters[people][exclude]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]
    #[OA\Parameter(name: 'filters[people][number]', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['1', '2', '3', 'more_than_three']))]
    #[OA\Parameter(name: 'filters[people][age]', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['infant', 'child', 'teen', 'young-adult', 'adult', 'senior', 'elder']))]
    #[OA\Parameter(name: 'filters[people][gender]', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['male', 'female']))]
    #[OA\Parameter(name: 'filters[people][ethnicity]', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['south-asian', 'middle-eastern', 'east-asian', 'black', 'hispanic', 'indian', 'white', 'multiracial', 'southeast-asian']))]

    #[OA\Parameter(name: 'filters[period]', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['last-month', 'last-quarter', 'last-semester', 'last-year']))]
    #[OA\Parameter(name: 'filters[color]', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['black', 'blue', 'gray', 'green', 'orange', 'red', 'white', 'yellow', 'purple', 'cyan', 'pink']))]
    #[OA\Parameter(name: 'filters[author]', in: 'query', required: false, schema: new OA\Schema(type: 'integer'))]

    #[OA\Parameter(name: 'filters[ai-generated][excluded]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]
    #[OA\Parameter(name: 'filters[ai-generated][only]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]

    #[OA\Parameter(name: 'filters[vector][type]', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['jpg', 'ai', 'eps', 'svg']))]
    #[OA\Parameter(name: 'filters[vector][style]', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['watercolor', 'flat', 'cartoon', 'geometric', 'gradient', 'isometric', '3d', 'hand-drawn']))]

    #[OA\Parameter(name: 'filters[psd][type]', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['jpg', 'psd']))]
    #[OA\Parameter(name: 'filters[ids]', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]

    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                "data" => [
                    [
                        "id" => 7663349,
                        "title" => "Father's day event hand drawn style",
                        "url" => "https://www.freepik.com/free-vector/father-s-day-event-hand-drawn-style_7663349.htm",
                        "filename" => "fathers-day-event-hand-drawn-style.zip",
                        "licenses" => [
                            [
                                "type" => "freemium",
                                "url" => "https://www.freepik.com/profile/license/pdf/7663349?lang=en"
                            ]
                        ],
                        "products" => [
                            [
                                "type" => "essential",
                                "url" => "https://www.freepik.com/profile/license/pdf/7663349?lang=en"
                            ]
                        ],
                        "meta" => [
                            "published_at" => "2020-04-15 17:50:35",
                            "is_new" => false,
                            "available_formats" => [
                                "ai" => [
                                    "total" => 1,
                                    "items" => [
                                        [
                                            "size" => 340222,
                                            "id" => 567457
                                        ]
                                    ]
                                ],
                                "eps" => [
                                    "total" => 1,
                                    "items" => [
                                        [
                                            "size" => 1323126,
                                            "id" => 567458
                                        ]
                                    ]
                                ],
                                "jpg" => [
                                    "total" => 1,
                                    "items" => [
                                        [
                                            "size" => 1131441,
                                            "id" => 567459
                                        ]
                                    ]
                                ],
                                "fonts" => [
                                    "total" => 1,
                                    "items" => [
                                        [
                                            "size" => 203,
                                            "id" => 567460
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        "image" => [
                            "type" => "vector",
                            "orientation" => "square",
                            "source" => [
                                "key" => "large",
                                "url" => "https://img.b2bpic.net/free-vector/father-s-day-event-hand-drawn-style_23-2148507324.jpg",
                                "size" => "626x626"
                            ]
                        ],
                        "related" => [
                            "serie" => [],
                            "others" => [],
                            "keywords" => []
                        ],
                        "stats" => [
                            "downloads" => 7198,
                            "likes" => 91
                        ],
                        "author" => [
                            "id" => 23,
                            "name" => "freepik",
                            "avatar" => "https://avatar.cdnpk.net/23.jpg",
                            "assets" => 6403390,
                            "slug" => "freepik"
                        ],
                        "active" => true
                    ]
                ],
                "meta" => [
                    "current_page" => 1,
                    "per_page" => 1,
                    "last_page" => 181651088,
                    "total" => 181651088,
                    "clean_search" => false
                ]
            ]
        )
    )]
    public function stockContent(StockContentRequest $request)
    {
        $data = StockContentData::from($request->validated());
        $result = $this->service->stockContent($data);
        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/freepik/resource_detail/{resource_id}',
        operationId: 'resourceDetail',
        description: 'Get detailed info about a specific Freepik resource',
        summary: 'Freepik resource detail',
        tags: ['Freepik'],
    )]
    #[OA\Parameter(
        name: 'resource_id',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'string', example: '7663349')
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                "preview" => [
                    "width" => 300,
                    "url" => "https://www.freepik.com/free-ai-image/surreal-landscape_41357833.htm",
                    "height" => 500
                ]
            ]
        )
    )]
    public function resourceDetail(string $resource_id): JsonResponse
    {
        $result = $this->service->resourceDetail($resource_id);
        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/freepik/download_resource/{resource_id}',
        operationId: 'downloadResource',
        description: 'Get download link for a Freepik resource',
        summary: 'Freepik download resource',
        tags: ['Freepik'],
    )]
    #[OA\Parameter(
        name: 'resource_id',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'string', example: '7663349')
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response with download link',
        content: new OA\JsonContent(
            example: [
                "data" => [
                    "filename" => "Mother-and-daughter.zip",
                    "url" => "https://downloadscdn5.freepik.com/d/999999/23/99999/8888888/mother-and-daughter.zip?token=exp=1689689298~hmac=1234567890abcde"
                ]
            ]
        )
    )]
    public function downloadResource(string $resource_id): JsonResponse
    {
        $result = $this->service->downloadResource($resource_id);
        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/freepik/download_resource_format',
        operationId: 'downloadResourceFormat',
        description: 'Download a Freepik resource in the specified format',
        summary: 'Download Freepik resource by query (resource_id & format)',
        tags: ['Freepik'],
    )]
    #[OA\Parameter(
        name: 'resource_id',
        in: 'query',
        required: true,
        schema: new OA\Schema(type: 'string', example: '150898146')
    )]
    #[OA\Parameter(
        name: 'format',
        in: 'query',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            enum: ['psd', 'ai', 'eps', 'atn', 'fonts', 'resources', 'png', 'jpg', '3d-render', 'svg', 'mockup'],
            example: 'psd'
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                "data" => [
                    [
                        "signed_url" => "https://img.freepik.com/premium-photo/close-up-cat-resting_1048944-9269194.jpg?t=st=1725276607~exp=1725280207~hmac=1538f1b294fc3a19a19e9f02ceeb6594a9a1e36a900de85d47bbd386e27dddbe",
                        "filename" => "blackboard-template.zip",
                        "url" => "https://downloadscdn5.freepik.com/d/1137445/blackboard-template.zip"
                    ],
                    [
                        "signed_url" => "https://img.freepik.com/premium-photo/close-up-cat-resting_1048944-9269194.jpg?t=st=1725276607~exp=1725280207~hmac=1538f1b294fc3a19a19e9f02ceeb6594a9a1e36a900de85d47bbd386e27dddbe",
                        "filename" => "blackboard-template.zip",
                        "url" => "https://downloadscdn5.freepik.com/d/1137445/blackboard-template.zip"
                    ]
                ]
            ]
        )
    )]
    public function downloadResourceFormat(DownloadResourceFormatRequest $request): JsonResponse
    {
        $data = DownloadResourceFormatData::from($request->validated());
        $result = $this->service->downloadResourceFormat($data);
        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/freepik/ai_image_classifier',
        operationId: 'aiImageClassifier',
        summary: 'AI Image Classifier',
        description: 'Classify an image via URL to determine if it was AI-generated.',
        tags: ['Freepik']
    )]
    #[OA\Parameter(
        name: 'image_url',
        in: 'query',
        required: true,
        schema: new OA\Schema(type: 'string', format: 'uri', example: 'https://publiish.io/ipfs/QmTkm5aAqNPgc3rXKTjYJ1VVB86xWJGofZX5wiRXHeew7f')
    )]
    #[OA\Response(
        response: 200,
        description: 'Classification result',
        content: new OA\JsonContent(
            example: [
                "data" => [
                    [
                        "class_name" => "not_ai",
                        "probability" => 0.94891726970673
                    ],
                    [
                        "class_name" => "ai",
                        "probability" => 0.051082730293274
                    ]
                ]
            ]
        )
    )]
    public function aiImageClassifier(AiImageClassifierRequest $request): JsonResponse
    {
        $data = AiImageClassifierData::from($request->validated());
        $result = $this->service->aiImageClassifier($data);
        return $this->logAndResponse($result);
    }
}
