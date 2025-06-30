<?php

namespace App\Http\Controllers;

use App\Data\Request\Captions\AiAdsPollData;
use App\Data\Request\Captions\AiAdsSubmitData;
use App\Data\Request\Captions\AiCreatorPollData;
use App\Data\Request\Captions\AiCreatorSubmitData;
use App\Data\Request\Captions\AiTranslatePollData;
use App\Data\Request\Captions\AiTranslateSubmitData;
use App\Http\Requests\Captions\AiAdsPollRequest;
use App\Http\Requests\Captions\AiAdsSubmitRequest;
use App\Http\Requests\Captions\AiCreatorPollRequest;
use App\Http\Requests\Captions\AiCreatorSubmitRequest;
use App\Http\Requests\Captions\AiTranslatePollRequest;
use App\Http\Requests\Captions\AiTranslateSubmitRequest;
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

    #[OA\Post(
        path: '/api/captions/translate/supported-languages',
        operationId: 'getSupportedLanguages',
        summary: 'List supported languages for translation',
        description: 'Retrieves a list of languages available for AI translation.',
        tags: ['Captions']
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response containing available languages.',
        content: new OA\JsonContent(
            example: [
                'supportedLanguages' => [
                    'Arabic',
                    'Baby',
                ],
            ]
        )
    )]
    public function getSupportedLanguages(): JsonResponse
    {
        $response = $this->service->getSupportedLanguages();

        return $this->logAndResponse($response);
    }

    #[OA\Post(
        path: '/api/captions/translate/submit',
        operationId: 'submitVideoTranslation',
        summary: 'Submit a video translation request',
        description: 'Begins the AI video translation process.',
        tags: ['Captions']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['videoUrl', 'sourceLanguage', 'targetLanguage'],
            properties: [
                new OA\Property(
                    property: 'videoUrl',
                    type: 'string',
                    format: 'url',
                    description: 'Public direct link to the video.',
                    example: 'https://publiish.io/ipfs/QmXc3tZ8bKpwnetxSeXadVkYKtPdVQqByHxaMD1Cs2Cika'
                ),
                new OA\Property(
                    property: 'sourceLanguage',
                    type: 'string',
                    description: 'Language spoken in the original video.',
                    example: 'English'
                ),
                new OA\Property(
                    property: 'targetLanguage',
                    type: 'string',
                    description: 'Desired translation language.',
                    example: 'Japanese'
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Video translation process started.',
        content: new OA\JsonContent(
            example: [
                'operationId' => 'GHOk0bF4wCZ2V5ntWLpG',
            ]
        )
    )]
    public function submitVideoTranslation(AiTranslateSubmitRequest $request): JsonResponse
    {
        $data = AiTranslateSubmitData::from($request->validated());

        $response = $this->service->submitVideoTranslation($data);

        return $this->logAndResponse($response);
    }

    #[OA\Post(
        path: '/api/captions/translate/poll',
        operationId: 'pollTranslationStatus',
        summary: 'Poll translation status',
        description: 'Polls the status of an AI Translate video generation process.',
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
                    description: 'The operation ID returned when the translation was submitted.',
                    example: 'GHOk0bF4wCZ2V5ntWLpG'
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Job complete or in progress.',
        content: new OA\JsonContent(
            example: [
                'url' => 'https://storage.googleapis.com/captions-autolipdub/SKBSas0SgowfnH8EGH2y/ac3b781d-036b-4d40-a851-701aa7473589_stitched_video.mp4?X-Goog-Algorithm=GOOG4-RSA-SHA256&X-Goog-Credential=captions-cluster-account%40captions-f6de9.iam.gserviceaccount.com%2F20250630%2Fauto%2Fstorage%2Fgoog4_request&X-Goog-Date=20250630T094739Z&X-Goog-Expires=604800&X-Goog-SignedHeaders=host&X-Goog-Signature=b55697b54cd5a7ed219b9a2b14368d35489a086580117b82e5f0641fcda85d3de0ffff6d0729a714a4b649928562896de58ea0278724910e016a11927772123e5adf7c6b8124ae859a76c207b5d73bd4ba9ad7c003f57a37e919e50c106007630c5f2bf7d0f0531869dfe0286c96d083a8b27bffac0201e6b1ba906c3053ba996137ba6df734be22760501307e39df40eebc956f008289cdda1cfecac465307971455099ee2f7ab1e7e8f0e7b26ac6d6549c1ad95996919cae1dfea60095e832e0895e0d91e49192f70588dfc258703a68848710bb8ddb81cb53cdb31bcb0bb34e9a19f2fcde30574ae0acc8be3cf35c37a993f21b61d4a4dee08d9db34b4a3f',
                'state' => 'COMPLETE',
            ]
        )
    )]
    public function pollTranslationStatus(AiTranslatePollRequest $request): JsonResponse
    {
        $data = AiTranslatePollData::from($request->validated());

        $response = $this->service->pollTranslationStatus($data);

        return $this->logAndResponse($response);
    }

    #[OA\Post(
        path: '/api/captions/ads/list-creators',
        operationId: 'listAdsCreators',
        summary: 'List available AI Ad Creators',
        description: 'Retrieves a list of AI creators that can be used for ad video generation.',
        tags: ['Captions']
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response containing available AI ad creators.',
        content: new OA\JsonContent(
            example: [
                'supportedCreators' => [
                    'Jason',
                    'Grace-1',
                    'Grace-2',
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
    public function listAdsCreators(): JsonResponse
    {
        $response = $this->service->getAdsCreatorsList();

        return $this->logAndResponse($response);
    }

    #[OA\Post(
        path: '/api/captions/ads/submit',
        operationId: 'submitAdVideo',
        summary: 'Submit an AI Ad video generation request',
        description: 'Begins the AI Ads video generation process with a specified creator.',
        tags: ['Captions']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['script', 'creatorName', 'mediaUrls'],
            properties: [
                new OA\Property(
                    property: 'script',
                    type: 'string',
                    maxLength: 800,
                    description: 'Script for the AI Ad (max 800 characters)',
                    example: 'Introducing our latest product!'
                ),
                new OA\Property(
                    property: 'creatorName',
                    type: 'string',
                    description: 'Name of the AI Creator.',
                    example: 'Jason'
                ),
                new OA\Property(
                    property: 'mediaUrls',
                    type: 'array',
                    description: 'URLs to media files (JPEG, PNG, MOV, MP4). Minimum 1, maximum 10.',
                    minItems: 1,
                    maxItems: 10,
                    items: new OA\Items(type: 'string', format: 'url', example: [
                        'https://publiish.io/ipfs/QmVeajukWWf2Yy6oQD61YGJywqfZrm5kbRR31iKQQQahwJ',
                        'https://publiish.io/ipfs/QmYdLz3cJr49qPh2vyZn55W6NwsAmLQpzrUZyHFjE3dmaY',
                        'https://publiish.io/ipfs/QmaHawrk9Lp1PzF3mh9HdpyaSiTUepmAgaj5Ai7DDTyuAK',
                    ])
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
        description: 'AI Ad video generation process started.',
        content: new OA\JsonContent(
            example: [
                'operationId' => 'R61piXz8jjxcBuQgK7UM',
            ]
        )
    )]
    public function submitAdVideo(AiAdsSubmitRequest $request): JsonResponse
    {
        $data = AiAdsSubmitData::from($request->validated());

        $response = $this->service->submitAdVideoGeneration($data);

        return $this->logAndResponse($response);
    }

    #[OA\Post(
        path: '/api/captions/ads/poll',
        operationId: 'pollAdVideoStatus',
        summary: 'Check the status of an AI Ads video generation request',
        description: 'Polls the status of an AI Ads video generation process using an operationId.',
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
                    description: 'The unique operation ID of the submitted ad generation task.',
                    example: 'R61piXz8jjxcBuQgK7UM'
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Job complete or in progress.',
        content: new OA\JsonContent(
            example: [
                'url' => 'https://storage.googleapis.com/captions-avatar-orc/orc/studio/writer__ugc_variant_result/LHElp6sX7OlrIh6DyoNA/52f97ad4-6b0c-4a9e-b35d-4e664e3a6a57/hd_result.mp4?X-Goog-Algorithm=GOOG4-RSA-SHA256&X-Goog-Credential=cloud-run-captions-server%40captions-f6de9.iam.gserviceaccount.com%2F20250630%2Fauto%2Fstorage%2Fgoog4_request&X-Goog-Date=20250630T100848Z&X-Goog-Expires=604800&X-Goog-SignedHeaders=host&X-Goog-Signature=9c52cc4dd08fac1766491707ec9a3481becbfe99fc5561661a05fac20a55ffda9fd877a5ad6b540052961f3f4a41bb167ded64173fdd1182b198d628a24b467b5cac70eb03a4dc95905d149fea2180f305f9fda8a8de47cd1af3afe3d5a35d418a94cdc465f572038e31bf8a0a453a33085269abe2a318afa1004a272e04000399b31cb5c2d913c04ea353575fd91a579e8bcb2ab0649c4adce3356cd22efe28d5a51f6f1f6c05896eae9f4900a59feb6a371be529d322d77244d1fe1265ba658fa18efd7ad5008d6f9800cc385dd429457f220562dd18bb66d6cec8483208d1ca3e22ecba7c053aa61384e5554f0a6302feb2b9623073d239c2170d20d699b2',
                'state' => 'COMPLETE',
            ]
        )
    )]
    public function pollAdVideoStatus(AiAdsPollRequest $request): JsonResponse
    {
        $data = AiAdsPollData::from($request->validated());

        $response = $this->service->pollAdVideoStatus($data);

        return $this->logAndResponse($response);
    }
}
