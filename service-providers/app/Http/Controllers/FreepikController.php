<?php

namespace App\Http\Controllers;

use App\Data\Request\Freepik\AiImageClassifierData;
use App\Data\Request\Freepik\DownloadResourceFormatData;
use App\Data\Request\Freepik\IconGenerationData;
use App\Data\Request\Freepik\KlingImageToVideoData;
use App\Data\Request\Freepik\KlingTextToVideoData;
use App\Data\Request\Freepik\StockContentData;
use App\Http\Requests\Freepik\AiImageClassifierRequest;
use App\Http\Requests\Freepik\DownloadResourceFormatRequest;
use App\Http\Requests\Freepik\IconGenerationRequest;
use App\Http\Requests\Freepik\KlingImageToVideoRequest;
use App\Http\Requests\Freepik\KlingImageToVideoStatusRequest;
use App\Http\Requests\Freepik\KlingTextToVideoRequest;
use App\Http\Requests\Freepik\StockContentRequest;
use App\Services\FreepikService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
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
                'data' => [
                    [
                        'id' => 7663349,
                        'title' => "Father's day event hand drawn style",
                        'url' => 'https://www.freepik.com/free-vector/father-s-day-event-hand-drawn-style_7663349.htm',
                        'filename' => 'fathers-day-event-hand-drawn-style.zip',
                        'licenses' => [
                            [
                                'type' => 'freemium',
                                'url' => 'https://www.freepik.com/profile/license/pdf/7663349?lang=en',
                            ],
                        ],
                        'products' => [
                            [
                                'type' => 'essential',
                                'url' => 'https://www.freepik.com/profile/license/pdf/7663349?lang=en',
                            ],
                        ],
                        'meta' => [
                            'published_at' => '2020-04-15 17:50:35',
                            'is_new' => false,
                            'available_formats' => [
                                'ai' => [
                                    'total' => 1,
                                    'items' => [
                                        [
                                            'size' => 340222,
                                            'id' => 567457,
                                        ],
                                    ],
                                ],
                                'eps' => [
                                    'total' => 1,
                                    'items' => [
                                        [
                                            'size' => 1323126,
                                            'id' => 567458,
                                        ],
                                    ],
                                ],
                                'jpg' => [
                                    'total' => 1,
                                    'items' => [
                                        [
                                            'size' => 1131441,
                                            'id' => 567459,
                                        ],
                                    ],
                                ],
                                'fonts' => [
                                    'total' => 1,
                                    'items' => [
                                        [
                                            'size' => 203,
                                            'id' => 567460,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'image' => [
                            'type' => 'vector',
                            'orientation' => 'square',
                            'source' => [
                                'key' => 'large',
                                'url' => 'https://img.b2bpic.net/free-vector/father-s-day-event-hand-drawn-style_23-2148507324.jpg',
                                'size' => '626x626',
                            ],
                        ],
                        'related' => [
                            'serie' => [],
                            'others' => [],
                            'keywords' => [],
                        ],
                        'stats' => [
                            'downloads' => 7198,
                            'likes' => 91,
                        ],
                        'author' => [
                            'id' => 23,
                            'name' => 'freepik',
                            'avatar' => 'https://avatar.cdnpk.net/23.jpg',
                            'assets' => 6403390,
                            'slug' => 'freepik',
                        ],
                        'active' => true,
                    ],
                ],
                'meta' => [
                    'current_page' => 1,
                    'per_page' => 1,
                    'last_page' => 181651088,
                    'total' => 181651088,
                    'clean_search' => false,
                ],
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
                'preview' => [
                    'width' => 300,
                    'url' => 'https://www.freepik.com/free-ai-image/surreal-landscape_41357833.htm',
                    'height' => 500,
                ],
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
                'data' => [
                    'filename' => 'Mother-and-daughter.zip',
                    'url' => 'https://downloadscdn5.freepik.com/d/999999/23/99999/8888888/mother-and-daughter.zip?token=exp=1689689298~hmac=1234567890abcde',
                ],
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
                'data' => [
                    [
                        'signed_url' => 'https://img.freepik.com/premium-photo/close-up-cat-resting_1048944-9269194.jpg?t=st=1725276607~exp=1725280207~hmac=1538f1b294fc3a19a19e9f02ceeb6594a9a1e36a900de85d47bbd386e27dddbe',
                        'filename' => 'blackboard-template.zip',
                        'url' => 'https://downloadscdn5.freepik.com/d/1137445/blackboard-template.zip',
                    ],
                    [
                        'signed_url' => 'https://img.freepik.com/premium-photo/close-up-cat-resting_1048944-9269194.jpg?t=st=1725276607~exp=1725280207~hmac=1538f1b294fc3a19a19e9f02ceeb6594a9a1e36a900de85d47bbd386e27dddbe',
                        'filename' => 'blackboard-template.zip',
                        'url' => 'https://downloadscdn5.freepik.com/d/1137445/blackboard-template.zip',
                    ],
                ],
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
                'data' => [
                    [
                        'class_name' => 'not_ai',
                        'probability' => 0.94891726970673,
                    ],
                    [
                        'class_name' => 'ai',
                        'probability' => 0.051082730293274,
                    ],
                ],
            ]
        )
    )]
    public function aiImageClassifier(AiImageClassifierRequest $request): JsonResponse
    {
        $data = AiImageClassifierData::from($request->validated());
        $result = $this->service->aiImageClassifier($data);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/freepik/icon_generation',
        operationId: 'iconGeneration',
        summary: 'Generate icon preview using Freepik AI',
        description: 'Submit a text prompt to generate AI icon previews. Result is sent via webhook.',
        tags: ['Freepik']
    )]
    #[OA\Parameter(
        name: 'prompt',
        in: 'query',
        required: true,
        description: 'Text prompt describing the icon you want to generate',
        schema: new OA\Schema(
            type: 'string',
            example: 'Cute robot with camera in flat vector style'
        )
    )]
    #[OA\Parameter(
        name: 'wait_for_result',
        in: 'query',
        required: false,
        description: 'Set to 1 to wait for result, or 0 to get task_id only.',
        schema: new OA\Schema(
            type: 'integer',
            enum: [0, 1],
            example: 1
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Task accepted',
        content: new OA\JsonContent(
            example: [
                'generated' => [],
                'task_id' => '046b6c7f-0b8a-43b9-b35d-6489e6daee91',
                'task_status' => 'IN_PROGRESS',
            ]
        )
    )]
    public function iconGeneration(IconGenerationRequest $request): JsonResponse
    {
        $data = IconGenerationData::from($request->validated());
        $result = $this->service->iconGeneration($data);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/freepik/icon_generation/result/{task_id}',
        operationId: 'getIconGenerationResult',
        summary: 'Get result of icon generation by task_id',
        description: 'Retrieve the result of a previously submitted icon generation task using task_id.',
        tags: ['Freepik']
    )]
    #[OA\Parameter(
        name: 'task_id',
        in: 'path',
        required: true,
        description: 'The task_id received from the iconGeneration endpoint.',
        schema: new OA\Schema(type: 'string', example: '796dd3c1-c50b-42bc-a9e2-a892eef53438')
    )]
    #[OA\Response(
        response: 200,
        description: 'Result of icon generation task',
        content: new OA\JsonContent(
            example: [
                'status' => 'COMPLETED',
                'request_id' => '796dd3c1-c50b-42bc-a9e2-a892eef53438',
                'task_id' => '796dd3c1-c50b-42bc-a9e2-a892eef53438',
                'generated' => [
                    'https://cdn-magnific.freepik.com/796dd3c1-c50b-42bc-a9e2-a892eef53438.png?token=exp=1750667013~hmac=262c8162c696d586a84155664318b75313d40cbf7dc67c70f8c8709dfa522cfd',
                ],
            ]
        )
    )]
    public function getIconGenerationResult(string $taskId): JsonResponse
    {
        $result = $this->service->getWebhookResult($taskId);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/freepik/kling_video_generation/image_to_video',
        operationId: 'kling_video_generation_image_to_video',
        summary: 'Generate video using Kling v2.1 Master model',
        description: 'Generate a video from an image prompt using Freepik’s Kling v2.1 Master model. Supports Image to Video modes with detailed input parameters.',
        tags: ['Freepik']
    )]
    #[OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    title: 'Image to Video',
                    type: 'object',
                    required: ['duration'],
                    properties: [
                        new OA\Property(
                            property: 'model',
                            type: 'string',
                            enum: ['kling-v2-1-master', 'kling-v2-1-pro', 'kling-v2-1-std', 'kling-v2', 'kling-pro', 'kling-std'],
                            description: 'Model of the generated video in seconds. Available options: kling-v2-1-master,kling-v2-1-pro,kling-v2-1-std,kling-v2,kling-pro,kling-std.',
                            example: 'kling-v2-1-master'
                        ),
                        new OA\Property(
                            property: 'duration',
                            type: 'string',
                            enum: ['5', '10'],
                            description: 'Duration of the generated video in seconds. Available options: 5, 10.',
                            example: '10'
                        ),
                        new OA\Property(
                            property: 'image',
                            type: 'string',
                            description: 'Reference image. Supports Base64 encoding or URL. Max 10MB, min 300x300px, aspect ratio 1:2.5 to 2.5:1.',
                            example: 'https://cdn.example.com/image.jpg'
                        ),
                        new OA\Property(
                            property: 'image_tail',
                            type: 'string',
                            description: "Reference Image - End frame control. Supports Base64 encoding or URL. For URL, must be publicly accessible. Must follow the same format requirements as the 'image' field. (Optional) Not compatible with standard mode.",
                            example: 'https://cdn.example.com/image.jpg'
                        ),
                        new OA\Property(
                            property: 'prompt',
                            type: 'string',
                            description: 'Text prompt describing the desired motion. Required if image is not provided.',
                            example: 'A mountain range expanding into mist'
                        ),
                        new OA\Property(
                            property: 'negative_prompt',
                            type: 'string',
                            description: 'Describe what to avoid in the generated video.',
                            example: 'blurry, distorted'
                        ),
                        new OA\Property(
                            property: 'cfg_scale',
                            type: 'number',
                            format: 'float',
                            description: 'Higher = stronger relevance to prompt (0-1). Default is 0.5.',
                            example: 0.5,
                            minimum: 0,
                            maximum: 1
                        ),
                        new OA\Property(
                            property: 'static_mask',
                            type: 'string',
                            description: 'Static mask image (Base64 or URL). Must match resolution and aspect ratio of input image.',
                            example: 'https://cdn.example.com/static_mask.png'
                        ),
                        new OA\Property(
                            property: 'dynamic_masks',
                            type: 'array',
                            description: 'Array of dynamic masks with motion trajectories.',
                            items: new OA\Items(
                                type: 'object',
                                required: ['mask', 'trajectories'],
                                properties: [
                                    new OA\Property(
                                        property: 'mask',
                                        type: 'string',
                                        description: 'Dynamic mask image (Base64 or URL)',
                                        example: 'https://cdn.example.com/dynamic_mask.jpg'
                                    ),
                                    new OA\Property(
                                        property: 'trajectories',
                                        type: 'array',
                                        items: new OA\Items(
                                            type: 'object',
                                            required: ['x', 'y'],
                                            properties: [
                                                new OA\Property(property: 'x', type: 'integer', example: 100),
                                                new OA\Property(property: 'y', type: 'integer', example: 150),
                                            ]
                                        )
                                    ),
                                ]
                            )
                        ),
                    ]
                )
            ),
        ]
    )]
    #[OA\Response(
        response: 200,
        description: 'Video generation started',
        content: new OA\JsonContent(
            type: 'object',
            example: [
                'task_id' => '123e4567-e89b-12d3-a456-426614174000',
                'status' => 'IN_PROGRESS',
            ]
        )
    )]
    public function klingVideoGenerationImageToVideo(KlingImageToVideoRequest $request): JsonResponse
    {
        $data = KlingImageToVideoData::from($request->validated());

        $result = $this->service->klingImageToVideo($data);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/freepik/kling_video_generation/image_to_video/status/{task_id}',
        operationId: 'klingVideoGenerationImageToVideoStatus',
        summary: 'Get status of Kling v2.1 video generation task',
        description: 'Check the current status of a Kling v2.1 Master image-to-video generation task by task ID.',
        tags: ['Freepik'],
    )]
    #[OA\Parameter(
        name: 'task_id',
        in: 'path',
        required: true,
        description: 'ID of the video generation task',
        schema: new OA\Schema(type: 'string'),
        example: '046b6c7f-0b8a-43b9-b35d-6489e6daee91'
    )]
    #[OA\QueryParameter(
        name: 'model',
        required: true,
        schema: new OA\Schema(type: 'string', enum: ['kling-v2-1-master', 'kling-v2-1-pro', 'kling-v2-1-std', 'kling-v2', 'kling-pro', 'kling-std']),
        description: 'Model of the generated video in seconds. Available options: kling-v2-1-master,kling-v2-1-pro,kling-v2-1-std,kling-v2,kling-pro,kling-std.',
        example: 'kling-v2-1-master'
    )]
    #[OA\Response(
        response: 200,
        description: 'Task status response',
        content: new OA\JsonContent(
            type: 'object',
            example: [
                'task_id' => '046b6c7f-0b8a-43b9-b35d-6489e6daee91',
                'status' => 'IN_PROGRESS',
                'generated' => [
                    'https://cdn.example.com/video1.mp4',
                    'https://cdn.example.com/video2.mp4',
                ],
            ]
        )
    )]
    public function klingVideoGenerationImageToVideoStatus(KlingImageToVideoStatusRequest $request, string $task_id): JsonResponse
    {
        $result = $this->service->klingImageToVideoStatus($request->validated()['model'], $task_id);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/freepik/kling_video_generation/text_to_video',
        operationId: 'kling_video_generation_text_to_video',
        summary: 'Generate video using Kling v2.1 Master model',
        description: 'Generate a video from an text prompt using Freepik’s Kling v2.1 Master model. Supports Text to Video modes with detailed input parameters.',
        tags: ['Freepik']
    )]
    #[OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    title: 'Text to Video',
                    type: 'object',
                    required: ['duration'],
                    properties: [
                        new OA\Property(
                            property: 'duration',
                            type: 'string',
                            enum: ['5', '10'],
                            description: 'Duration of the generated video in seconds. Available options: 5, 10.',
                            example: '5'
                        ),
                        new OA\Property(
                            property: 'prompt',
                            type: 'string',
                            description: 'Text prompt describing the desired motion. Max 2500 characters.',
                            example: 'A sunset over the ocean with crashing waves'
                        ),
                        new OA\Property(
                            property: 'negative_prompt',
                            type: 'string',
                            description: 'Describe what to avoid in the generated video.',
                            example: 'low resolution, night scene'
                        ),
                        new OA\Property(
                            property: 'aspect_ratio',
                            type: 'string',
                            enum: ['widescreen_16_9', 'social_story_9_16', 'square_1_1'],
                            description: 'Aspect ratio for generated video (only used when image is not provided).',
                            example: 'widescreen_16_9'
                        ),
                        new OA\Property(
                            property: 'cfg_scale',
                            type: 'number',
                            format: 'float',
                            description: 'Higher = stronger relevance to prompt (0-1). Default is 0.5.',
                            example: 0.5,
                            minimum: 0,
                            maximum: 1
                        ),
                    ]
                )
            ),
        ]
    )]
    #[OA\Response(
        response: 200,
        description: 'Video generation started',
        content: new OA\JsonContent(
            type: 'object',
            example: [
                'task_id' => '123e4567-e89b-12d3-a456-426614174000',
                'status' => 'IN_PROGRESS',
            ]
        )
    )]
    public function klingVideoGenerationTextToVideo(KlingTextToVideoRequest $request): JsonResponse
    {
        $data = KlingTextToVideoData::from($request->validated());

        $result = $this->service->klingTextToVideo($data);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/freepik/kling_video_generation/text_to_video/status/{task_id}',
        operationId: 'klingVideoGenerationStatus',
        summary: 'Get status of Kling v2.1 video generation task',
        description: 'Check the current status of a Kling v2.1 Master text-to-video generation task by task ID.',
        tags: ['Freepik'],
    )]
    #[OA\Parameter(
        name: 'task_id',
        in: 'path',
        required: true,
        description: 'ID of the video generation task',
        schema: new OA\Schema(type: 'string'),
        example: '046b6c7f-0b8a-43b9-b35d-6489e6daee91'
    )]
    #[OA\Response(
        response: 200,
        description: 'Task status response',
        content: new OA\JsonContent(
            type: 'object',
            example: [
                'task_id' => '046b6c7f-0b8a-43b9-b35d-6489e6daee91',
                'status' => 'IN_PROGRESS',
                'generated' => [
                    'https://cdn.example.com/video1.mp4',
                    'https://cdn.example.com/video2.mp4',
                ],
            ]
        )
    )]
    public function klingVideoGenerationTextToVideoStatus(string $task_id): JsonResponse
    {
        $result = $this->service->klingTextToVideoStatus($task_id);

        return $this->logAndResponse($result);
    }

    public function handleWebhook(Request $request)
    {
        $webhookId = $request->header('webhook-id');
        $timestamp = $request->header('webhook-timestamp');
        $signatureHeader = $request->header('webhook-signature');
        $rawBody = $request->getContent();

        if (!$webhookId || !$timestamp || !$signatureHeader) {
            Log::warning('Missing Freepik webhook headers');

            return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $content = "{$webhookId}.{$timestamp}.{$rawBody}";
        $secret = config('services.freepik.webhook_secret');
        $computedSignature = base64_encode(hash_hmac('sha256', $content, $secret, true));

        $valid = collect(explode(' ', $signatureHeader))
            ->map(fn ($pair) => explode(',', $pair)[1] ?? null)
            ->contains(fn ($sig) => hash_equals($sig, $computedSignature));

        if (!$valid) {
            Log::error('Invalid Freepik webhook signature', ['computed' => $computedSignature]);

            return response()->json(['message' => 'Invalid signature'], Response::HTTP_UNAUTHORIZED);
        }

        $payload = $request->all();
        Log::info('Verified Freepik webhook received', $payload);

        // 🔁 Log the webhook to cache
        $this->service->setWebhookResult($payload);

        return response()->json(['message' => 'Webhook verified and logged'], Response::HTTP_OK);
    }
}
