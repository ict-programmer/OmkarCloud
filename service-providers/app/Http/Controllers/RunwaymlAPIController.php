<?php

namespace App\Http\Controllers;

use App\Data\Runwayml\VideoProcessingData;
use App\Http\Exceptions\BadRequest;
use App\Http\Requests\Runwayml\VideoProcessingRequest;
use App\Services\RunwaymlService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class RunwaymlAPIController extends BaseController
{
    public function __construct(protected RunwaymlService $service) {}

    // #[OA\Post(
    //     path: '/api/runwayml/video_processing',
    //     operationId: '/api/runwayml/video_processing',
    //     description: 'Generate Video using RunwayML API',
    //     summary: 'Generate Video using RunwayML API',
    //     security: [['authentication' => []]],
    //     tags: ['RunwayML'],
    // )]
    // #[OA\Parameter(
    //     name: 'model',
    //     in: 'query',
    //     required: true,
    //     description: 'The model to use for video generation.',
    //     schema: new OA\Schema(
    //         type: 'string',
    //         enum: ['gen4_turbo', 'gen3a_turbo'],
    //         example: 'gen4_turbo',
    //     ),
    // )]
    // #[OA\Parameter(
    //     name: 'prompt_image',
    //     in: 'query',
    //     required: true,
    //     description: 'The URL of the prompt image.',
    //     schema: new OA\Schema(
    //         type: 'string',
    //         example: 'https://example.com/image.png',
    //     ),
    // )]
    // #[OA\Parameter(
    //     name: 'prompt_text',
    //     in: 'query',
    //     required: true,
    //     description: 'The text prompt for video generation.',
    //     schema: new OA\Schema(
    //         type: 'string',
    //         maxLength: 1000,
    //         example: 'A beautiful sunset over the mountains.',
    //     ),
    // )]
    // #[OA\Parameter(
    //     name: 'seed',
    //     in: 'query',
    //     required: true,
    //     description: 'The seed value for randomization.',
    //     schema: new OA\Schema(
    //         type: 'integer',
    //         example: 12345,
    //     ),
    // )]
    // #[OA\Parameter(
    //     name: 'duration',
    //     in: 'query',
    //     required: true,
    //     description: 'The duration of the generated video in seconds.',
    //     schema: new OA\Schema(
    //         type: 'integer',
    //         enum: [5, 10],
    //         example: 5,
    //     ),
    // )]
    // #[OA\Parameter(
    //     name: 'width',
    //     in: 'query',
    //     required: true,
    //     description: 'The width of the generated video.',
    //     schema: new OA\Schema(
    //         type: 'integer',
    //         enum: [1280, 720, 1104, 832, 960, 1584, 768],
    //         example: 1280,
    //     ),
    // )]
    // #[OA\Parameter(
    //     name: 'height',
    //     in: 'query',
    //     required: true,
    //     description: 'The height of the generated video.',
    //     schema: new OA\Schema(
    //         type: 'integer',
    //         enum: [720, 1280, 832, 1104, 960, 672, 768],
    //         example: 720,
    //     ),
    // )]
    // #[OA\Response(
    //     response: 200,
    //     description: 'Generate Video using RunwayML API',
    //     content: new OA\MediaType(
    //         mediaType: 'application/json',
    //         schema: new OA\Schema(
    //             type: 'object',
    //             properties: [
    //                 new OA\Property(
    //                     property: 'status',
    //                     type: 'boolean',
    //                     example: true,
    //                 ),
    //                 new OA\Property(
    //                     property: 'id',
    //                     type: 'string',
    //                     description: 'The generated video id.',
    //                     example: '17f20503-6c24-4c16-946b-35dbbce2af2f',
    //                 ),
    //             ]
    //         ),
    //     ),
    // )]
    // #[OA\Response(
    //     response: 422,
    //     description: 'Validation error',
    //     content: new OA\JsonContent(
    //         example: [
    //             'status' => 'validation_error',
    //             'message' => [
    //                 'model' => 'The model field is required.',
    //                 'video_url' => 'The video url field is required.',
    //                 'frames' => 'The frames field is required.',
    //                 'mask' => 'The mask field is required.',
    //                 'output_format' => 'The output format field is required.',
    //                 'resolution' => 'The resolution field is required.',
    //             ],
    //         ],
    //     )
    // )]
    // #[OA\Response(
    //     response: 500,
    //     description: 'Server error',
    //     content: new OA\JsonContent(
    //         example: [
    //             'status' => 'error',
    //             'message' => 'An error occurred while processing your request',
    //         ],
    //     )
    // )]
    public function videoProcessing(VideoProcessingRequest $request): JsonResponse
    {
        $data = VideoProcessingData::from($request->validated());

        $result = $this->service->videoProcessing($data);

        return $this->logAndResponse($result);
    }

    // #[OA\Post(
    //     path: '/api/runwayml/task_management/{id}',
    //     operationId: '/api/runwayml/task_management',
    //     description: 'Managing task that have been submitted.',
    //     summary: 'Managing task that have been submitted.',
    //     security: [['authentication' => []]],
    //     tags: ['RunwayML'],
    // )]
    // #[OA\Parameter(
    //     name: 'id',
    //     in: 'path',
    //     required: true,
    //     description: 'The ID of the task generated.',
    //     schema: new OA\Schema(
    //         type: 'string',
    //         example: '17f20503-6c24-4c16-946b-35dbbce2af2f',
    //     ),
    // )]
    // #[OA\Response(
    //     response: 200,
    //     description: 'Managing task that have been submitted.',
    //     content: new OA\MediaType(
    //         mediaType: 'application/json',
    //         schema: new OA\Schema(
    //             type: 'object',
    //             properties: [
    //                 new OA\Property(
    //                     property: 'id',
    //                     type: 'string',
    //                     example: '17f20503-6c24-4c16-946b-35dbbce2af2f',
    //                 ),
    //                 new OA\Property(
    //                     property: 'status',
    //                     type: 'string',
    //                     example: 'PENDING',
    //                 ),
    //                 new OA\Property(
    //                     property: 'createdAt',
    //                     type: 'string',
    //                     format: 'date-time',
    //                     example: '2024-06-27T19:49:32.334Z',
    //                 ),
    //             ]
    //         ),
    //     ),
    // )]
    // #[OA\Response(
    //     response: 422,
    //     description: 'Validation error',
    //     content: new OA\JsonContent(
    //         example: [
    //             'status' => 'validation_error',
    //             'message' => [
    //                 'id' => 'The id field is required.',
    //             ],
    //         ],
    //     )
    // )]
    // #[OA\Response(
    //     response: 500,
    //     description: 'Server error',
    //     content: new OA\JsonContent(
    //         example: [
    //             'status' => 'error',
    //             'message' => 'An error occurred while processing your request',
    //         ],
    //     )
    // )]
    public function taskManagement(): JsonResponse
    {
        $taskId = (string) request()->input('task_id');

        throw_if(empty($taskId), new BadRequest(__('Task ID is required')));

        $result = $this->service->taskManagement($taskId);

        return $this->logAndResponse($result->data);
    }
}
