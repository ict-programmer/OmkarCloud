<?php

namespace App\Http\Controllers;

use App\Data\Request\Midjourney\ImageGenerationData;
use App\Data\Request\Midjourney\ImageVariationData;
use App\Data\Request\Midjourney\GetTaskData;
use App\Http\Requests\Midjourney\ImageGenerationRequest;
use App\Http\Requests\Midjourney\ImageVariationRequest;
use App\Http\Requests\Midjourney\GetTaskRequest;
use App\Services\MidjourneyService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class MidjourneyController extends BaseController
{
    public function __construct(protected MidjourneyService $service) {}

    #[OA\Post(
        path: '/api/midjourney/image_generation',
        operationId: 'midjourney_image_generation',
        description: 'Generate images using Midjourney API',
        summary: 'Midjourney Image Generation',
        security: [['authentication' => []]],
        tags: ['Midjourney'],
    )]
    #[OA\RequestBody(
        description: 'Generate image using Midjourney',
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                required: ['prompt'],
                properties: [
                    new OA\Property(
                        property: 'prompt',
                        type: 'string',
                        description: 'Detailed text prompt for image generation',
                        example: 'A majestic mountain landscape with snow-capped peaks at sunset',
                    ),
                    new OA\Property(
                        property: 'aspect_ratio',
                        type: 'string',
                        description: 'Aspect ratio of the generated image',
                        example: '1:1',
                        enum: ['1:1', '16:9', '9:16', '4:3', '3:4', '3:2', '2:3'],
                    ),
                    new OA\Property(
                        property: 'quality',
                        type: 'string',
                        description: 'Quality of the generated image',
                        example: 'high',
                        enum: ['high', 'medium', 'low'],
                    ),
                    new OA\Property(
                        property: 'style',
                        type: 'string',
                        description: 'Style of the generated image',
                        example: 'realistic',
                        enum: ['realistic', 'artistic', 'cartoon', 'anime'],
                    ),
                    new OA\Property(
                        property: 'seed',
                        type: 'integer',
                        description: 'Seed for reproducible results',
                        example: 12345,
                    ),
                ],
                type: 'object'
            ),
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful image generation with completed result',
        content: new OA\JsonContent(
            example: [
                'message' => 'Image generation successful.',
                'data' => [
                    'task_id' => '8409f94e-dd6a-4e5d-874d-3a074e72dcd0',
                    'status' => 'completed',
                    'output' => [
                        'image_url' => 'https://img.theapi.app/mj/task_id.png',
                        'temporary_image_urls' => [
                            'https://img.theapi.app/cdn-cgi/image/trim=0;1024;1024;0/mj/task_id.png',
                            'https://img.theapi.app/cdn-cgi/image/trim=0;0;1024;1024/mj/task_id.png',
                            'https://img.theapi.app/cdn-cgi/image/trim=1024;1024;0;0/mj/task_id.png',
                            'https://img.theapi.app/cdn-cgi/image/trim=1024;0;0;1024/mj/task_id.png'
                        ],
                        'actions' => ['reroll', 'upscale1', 'upscale2', 'upscale3', 'upscale4', 'variation1', 'variation2', 'variation3', 'variation4'],
                        'progress' => 100
                    ],
                ],
            ],
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'validation_error',
                'message' => [
                    'prompt' => 'The prompt field is required.',
                ],
            ],
        )
    )]
    public function imageGeneration(ImageGenerationRequest $request): JsonResponse
    {
        $data = ImageGenerationData::from($request->validated());

        $result = $this->service->imageGeneration($data);

        return $this->logAndResponse([
            'message' => 'Image generation successful.',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/midjourney/image_generation_async',
        operationId: 'midjourney_image_generation_async',
        description: 'Generate images using Midjourney API (returns immediately with task_id)',
        summary: 'Midjourney Image Generation (Async)',
        security: [['authentication' => []]],
        tags: ['Midjourney'],
    )]
    #[OA\RequestBody(
        description: 'Generate image using Midjourney (async)',
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                required: ['prompt'],
                properties: [
                    new OA\Property(
                        property: 'prompt',
                        type: 'string',
                        description: 'Detailed text prompt for image generation',
                        example: 'A majestic mountain landscape with snow-capped peaks at sunset',
                    ),
                    new OA\Property(
                        property: 'aspect_ratio',
                        type: 'string',
                        description: 'Aspect ratio of the generated image',
                        example: '1:1',
                        enum: ['1:1', '16:9', '9:16', '4:3', '3:4', '3:2', '2:3'],
                    ),
                    new OA\Property(
                        property: 'quality',
                        type: 'string',
                        description: 'Quality of the generated image',
                        example: 'high',
                        enum: ['high', 'medium', 'low'],
                    ),
                    new OA\Property(
                        property: 'style',
                        type: 'string',
                        description: 'Style of the generated image',
                        example: 'realistic',
                        enum: ['realistic', 'artistic', 'cartoon', 'anime'],
                    ),
                    new OA\Property(
                        property: 'seed',
                        type: 'integer',
                        description: 'Seed for reproducible results',
                        example: 12345,
                    ),
                ],
                type: 'object'
            ),
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Task created successfully (immediate response)',
        content: new OA\JsonContent(
            example: [
                'message' => 'Image generation task created successfully.',
                'data' => [
                    'task_id' => '8409f94e-dd6a-4e5d-874d-3a074e72dcd0',
                    'status' => 'pending',
                    'message' => 'Use the task_id to check status with /api/midjourney/task endpoint',
                ],
            ],
        )
    )]
    public function imageGenerationAsync(ImageGenerationRequest $request): JsonResponse
    {
        $data = ImageGenerationData::from($request->validated());

        $result = $this->service->imageGenerationAsync($data);

        return $this->logAndResponse([
            'message' => 'Image generation task created successfully.',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/midjourney/image_variation',
        operationId: 'midjourney_image_variation',
        description: 'Generate image variations using Midjourney API',
        summary: 'Midjourney Image Variation',
        security: [['authentication' => []]],
        tags: ['Midjourney'],
    )]
    #[OA\RequestBody(
        description: 'Generate image variations using Midjourney',
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                required: ['input_image', 'variation_strength', 'count'],
                properties: [
                    new OA\Property(
                        property: 'input_image',
                        type: 'string',
                        description: 'Path or URL to source image',
                        example: 'https://example.com/image.jpg',
                    ),
                    new OA\Property(
                        property: 'variation_strength',
                        type: 'number',
                        format: 'float',
                        description: 'Strength of variation (0.0 to 1.0)',
                        example: 0.7,
                        minimum: 0.0,
                        maximum: 1.0,
                    ),
                    new OA\Property(
                        property: 'count',
                        type: 'integer',
                        description: 'Number of variations to generate',
                        example: 4,
                        minimum: 1,
                        maximum: 4,
                    ),
                    new OA\Property(
                        property: 'guidance_scale',
                        type: 'number',
                        format: 'float',
                        description: 'Guidance scale for generation',
                        example: 7.5,
                        minimum: 1.0,
                        maximum: 20.0,
                    ),
                ],
                type: 'object'
            ),
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful image variation generation',
        content: new OA\JsonContent(
            example: [
                'message' => 'Image variation successful.',
                'data' => [
                    'task_id' => '8409f94e-dd6a-4e5d-874d-3a074e72dcd0',
                    'status' => 'processing',
                    'variations' => [],
                    'progress' => 0,
                ],
            ],
        )
    )]
    public function imageVariation(ImageVariationRequest $request): JsonResponse
    {
        $data = ImageVariationData::from($request->validated());

        $result = $this->service->imageVariation($data);

        return $this->logAndResponse([
            'message' => 'Image variation successful.',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/midjourney/task',
        operationId: 'midjourney_get_task',
        description: 'Get the status and result of a Midjourney task',
        summary: 'Get Midjourney Task Status',
        security: [['authentication' => []]],
        tags: ['Midjourney'],
    )]
    #[OA\RequestBody(
        description: 'Get task status by task ID',
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                required: ['task_id'],
                properties: [
                    new OA\Property(
                        property: 'task_id',
                        type: 'string',
                        format: 'uuid',
                        description: 'The task ID to check status for',
                        example: '9c20af76-5751-47a4-812c-c0a54f6858b7',
                    ),
                ],
                type: 'object'
            ),
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Task status retrieved successfully',
        content: new OA\JsonContent(
            example: [
                'message' => 'Task status retrieved successfully.',
                'data' => [
                    'task_id' => '9c20af76-5751-47a4-812c-c0a54f6858b7',
                    'model' => 'midjourney',
                    'task_type' => 'imagine',
                    'status' => 'Completed',
                    'output' => [
                        'discord_image_url' => 'https://cdn.midjourney.com/...',
                        'image_url' => 'https://temporary-url.com/...',
                        'temporary_image_urls' => ['url1', 'url2', 'url3', 'url4'],
                        'task_progress' => 100,
                    ],
                ],
            ],
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Task not found',
        content: new OA\JsonContent(
            example: [
                'error' => 'Task not found or invalid task ID',
            ],
        )
    )]
    public function getTask(GetTaskRequest $request): JsonResponse
    {
        $data = GetTaskData::from($request->validated());

        $result = $this->service->getTask($data);

        return $this->logAndResponse([
            'message' => 'Task status retrieved successfully.',
            'data' => $result,
        ]);
    }
} 