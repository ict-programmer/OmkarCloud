<?php

namespace App\Http\Controllers;

use App\Data\Request\Captions\AiCreatorPollData;
use App\Data\Request\Captions\AiCreatorSubmitData;
use App\Http\Requests\Captions\AiCreatorPollRequest;
use App\Http\Requests\Captions\AiCreatorSubmitRequest;
use App\Services\CaptionsService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class CaptionsController extends BaseController
{
    public function __construct(protected CaptionsService $service) {}

    #[OA\Post(
        path: '/api/captions/creator/list',
        operationId: 'listCreators',
        summary: 'List available AI Creators',
        description: 'Retrieves a list of AI creators that can be used in the API.',
        tags: ['Captions']
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response containing available AI Creators.',
        content: new OA\JsonContent(
            example: [
                'supportedCreators' => [
                    'Jason',
                    'Grace-1',
                ],
                'thumbnails' => [
                    'Jason' => [
                        'imageUrl' => 'https://captions-cdn.xyz/ai-avatars/ugc/v2/oo0RAIESofBfKGEes2BL/thumb.jpg',
                        'videoUrl' => 'https://captions-cdn.xyz/ai-avatars/ugc/v2/oo0RAIESofBfKGEes2BL/preview.mp4',
                    ],
                    'Grace-1' => [
                        'imageUrl' => 'https://captions-cdn.xyz/ai-avatars/ugc/v2/hKUDZndVfPfMT3YUTYSd/thumb.jpg',
                        'videoUrl' => 'https://captions-cdn.xyz/ai-avatars/ugc/v2/hKUDZndVfPfMT3YUTYSd/preview.mp4',
                    ],
                    'Grace-2' => [
                        'imageUrl' => 'https://captions-cdn.xyz/ai-avatars/ugc/v2/Nmxmr9QhaNeJB1CqNuxO/thumb.jpg',
                        'videoUrl' => 'https://captions-cdn.xyz/ai-avatars/ugc/v2/Nmxmr9QhaNeJB1CqNuxO/preview.mp4',
                    ],
                ],
            ]
        )
    )]
    public function listCreators(): JsonResponse
    {
        $response = $this->service->getCreatorsList();

        return $this->logAndResponse($response);
    }

    #[OA\Post(
        path: '/api/captions/creator/submit',
        operationId: 'submitCreatorVideo',
        summary: 'Submit AI Creator video generation request',
        description: 'Begins the video generation process using a specified AI Creator.',
        tags: ['Captions']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['script'],
            properties: [
                new OA\Property(
                    property: 'script',
                    type: 'string',
                    maxLength: 800,
                    description: 'Script for the AI Creator (max 800 characters)',
                    example: 'Hello, welcome to our brand new tutorial!'
                ),
                new OA\Property(
                    property: 'creatorName',
                    type: 'string',
                    description: 'Name of the AI Creator. Default is "Kate".',
                    example: 'Kate'
                ),
                new OA\Property(
                    property: 'resolution',
                    type: 'string',
                    enum: ['fhd', '4k'],
                    description: 'Desired output resolution (default is 4k).',
                    example: 'fhd'
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Video generation process started',
        content: new OA\JsonContent(
            example: [
                'operationId' => 'tg7FaiVgmGqTQfuDhyGA',
            ]
        )
    )]
    public function submitCreatorVideo(AiCreatorSubmitRequest $request): JsonResponse
    {
        $data = AiCreatorSubmitData::from($request->validated());

        $response = $this->service->submitVideoGeneration($data);

        return $this->logAndResponse($response);
    }

    #[OA\Post(
        path: '/api/captions/creator/poll',
        operationId: 'pollCreatorStatus',
        summary: 'Poll AI Creator generation status',
        description: 'Checks the status of a video generation request by operationId.',
        tags: ['Captions']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['operationId'],
            properties: [
                new OA\Property(
                    property: 'operationId',
                    type: 'string',
                    description: 'The unique operation ID of the submitted video generation task.',
                    example: 'tg7FaiVgmGqTQfuDhyGA'
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Job complete or in progress.',
        content: new OA\JsonContent(
            example: [
                'url' => 'https://storage.googleapis.com/captions-avatar-orc/orc/studio/video_clip__crop_video/aiA8TwJfHR6TbiTlQZgC/69f3642a-fdb4-47e1-9552-5e22911482de/cropped_result.mp4?X-Goog-Algorithm=GOOG4-RSA-SHA256&X-Goog-Credential=cloud-run-captions-server%40captions-f6de9.iam.gserviceaccount.com%2F20250630%2Fauto%2Fstorage%2Fgoog4_request&X-Goog-Date=20250630T092829Z&X-Goog-Expires=604800&X-Goog-SignedHeaders=host&X-Goog-Signature=0ebaa117d06052f31c74d88a30363134618af2aab80d9481a95c0a6d64928434a1b9a0df4258a3fe5bce7665c3847d987a1468356367f7d4152d4d7cbab2c4281a67ba5334ea598e827d98202b405e65db05cf725adec1f0a3c0d04cc4e9b25df07c801692edc8d44830c2c276cd962e7676c3f7d16c7e5bbadc17cf69e8798991d8f32507c6e2e9bb325a917753e68f6ae21a7be916c873f9ae81e46266829c75a1776a662442abf093624553599a7e7f2cac3174031a5b8b27278fdd810a3be42e210affd9d880798ae7f234421a3c0305fb3f8e9539cec7d52cbb87e21502194996e92d590dd64ba9caac56c501893c490f71d076b4d8856f7d517a127888',
                'state' => 'COMPLETE',
            ]
        )
    )]
    public function pollCreatorStatus(AiCreatorPollRequest $request): JsonResponse
    {
        $data = AiCreatorPollData::from($request->validated());

        $response = $this->service->pollVideoGenerationStatus($data);

        return $this->logAndResponse($response);
    }
}
