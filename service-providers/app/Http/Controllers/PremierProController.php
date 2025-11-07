<?php

namespace App\Http\Controllers;

use App\Data\Request\PremierPro\ImageGenData;
use App\Data\Request\PremierPro\ReframeData;
use App\Http\Requests\PremierPro\ImageGenRequest;
use App\Http\Requests\PremierPro\ReframeRequest;
use App\Services\PremierProService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class PremierProController extends BaseController
{
    public function __construct(protected PremierProService $service){}

    // #[OA\Post(
    //     path: '/api/premierpro/reframe',
    //     operationId: 'reframe',
    //     description: 'Reframe',
    //     summary: 'Reframe a video using PremierPro API.',
    //     security: [['authentication' => []]],
    //     requestBody: new OA\RequestBody(
    //         required: true,
    //         content: new OA\JsonContent(
    //             example: [
    //                 'video_url' => 'https://example.com/video.mp4',
    //                 'scene_detection' => true,
    //                 'output_config' => [
    //                     'aspect_ratios' => [
    //                         '16:9',
    //                         '4:3',
    //                     ],
    //                 ],
    //             ],
    //         )
    //     ),
    //     tags: ['PremierPro'],
    //     responses: [
    //         new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(
    //             example: [
    //                 'message' => 'Reframe successful.',
    //                 'data' => [
    //                     'jobId' => '9twcboittpg08',
    //                     'statusUrl' => 'https://<base_url>/v1/status/9twcboittpg08',
    //                 ],
    //             ],
    //         )),
    //     ]
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
    public function reframe(ReframeRequest $request): JsonResponse
    {
        $data = ReframeData::from($request->validated());

        $result = $this->service->reframe($data);

        return $this->logAndResponse([
            'message' => _('Reframe successful.'),
            'data' => $result,
        ]);
    }

    // #[OA\Post(
    //     path: '/api/premierpro/image-generation',
    //     operationId: '/api/premierpro/image-generation',
    //     description: 'Generate image using PremierPro API',
    //     summary: 'Generate image using PremierPro API',
    //     security: [['authentication' => []]],
    //     requestBody: new OA\RequestBody(
    //         required: true,
    //         content: new OA\JsonContent(
    //             example: [
    //                 'prompt' => 'A photo of a cat',
    //             ],
    //         )
    //     ),
    //     tags: ['PremierPro'],
    //     responses: [
    //         new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(
    //             example: [
    //                 'message' => 'Reframe successful.',
    //                 'data' => [
    //                     'jobId' => '9twcboittpg08',
    //                     'statusUrl' => 'https://<base_url>/v1/status/9twcboittpg08',
    //                 ],
    //             ],
    //         )),
    //     ]
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
    public function imageGeneration(ImageGenRequest $request): JsonResponse
    {
        $data = ImageGenData::from($request->validated());
        $result = $this->service->imageGeneration($data);
        return $this->logAndResponse([
            'message' => _('Reframe successful.'),
            'data' => $result,
        ]);
    }


    // #[OA\Post(
    //     path: '/api/premierpro/status/{id}',
    //     operationId: '/api/premierpro/status',
    //     description: 'Managing job that have been submitted.',
    //     summary: 'Managing job that have been submitted.',
    //     security: [['authentication' => []]],
    //     tags: ['PremierPro'],
    // )]
    // #[OA\Parameter(
    //     name: 'id',
    //     in: 'path',
    //     required: true,
    //     description: 'The ID of the job generated.',
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
    //                     property: 'status',
    //                     type: 'string',
    //                     example: 'not_started',
    //                 ),
    //                 new OA\Property(
    //                     property: 'jobId',
    //                     type: 'string',
    //                     example: '123e4567-e89b-12d3-a456-426614174002',
    //                 ),
    //             ]
    //         ),
    //     ),
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
    public function status (string $id): JsonResponse
    {
        $result = $this->service->getStatus($id);

        return $this->logAndResponse($result,);
    }
}
