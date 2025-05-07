<?php

namespace App\Http\Controllers;

use App\Data\Request\Placid\ImageGenerationData;
use App\Data\Request\Placid\RetrievePdfData;
use App\Data\Request\Placid\RetrieveTemplateData;
use App\Data\Request\Placid\RetrieveVideoData;
use App\Data\Request\Placid\VideoGenerationData;
use App\Http\Requests\Placid\ImageGenerationRequest;
use App\Http\Requests\Placid\RetrievePdfRequest;
use App\Http\Requests\Placid\RetrieveTemplateRequest;
use App\Http\Requests\Placid\RetrieveVideoRequest;
use App\Http\Requests\Placid\VideoGenerationRequest;
use App\Services\PlacidService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class PlacidController extends BaseController
{
    public function __construct(public PlacidService $service) {}

    #[OA\Post(
        path: '/api/placid/image-generation',
        operationId: 'imageGeneration',
        description: 'Image Generation',
        summary: 'Generate images using Placid API.',
        security: [['authentication' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                example: [
                    'template_uuid' => '9twcboittpg08',
                    'layers' => [
                        'general' => [
                            'hide' => false,
                            'opacity' => 100,
                            'rotation' => 0,
                            'position_x_absolute' => 50,
                            'position_y_absolute' => 50,
                            'position_x_relative' => 0.5,
                            'position_y_relative' => 0.5,
                            'width' => 200,
                            'height' => 100,
                            'link_target' => '',
                        ],
                        'text layer' => [
                            'text' => 'Hello World',
                            'text_color' => '#000000',
                            'font' => 'Arial',
                            'alt_text_color' => '#FFFFFF',
                            'alt_font' => 'Arial',
                        ],
                        'picture layer' => [
                            'image' => 'https://example.com/image.jpg',
                            'image_viewport' => '1280x1024',
                            'video' => 'https://example.com/video.mp4',
                        ],
                        'shape' => [
                            'shape' => 'rectangle',
                            'background_color' => '#FF0000',
                            'border_color' => '#000000',
                            'border_radius' => 10,
                            'border_width' => 2,
                            'svg' => '<svg>...</svg>',
                        ],
                        'browser frame' => [
                            'image' => 'https://example.com/image.jpg',
                            'image_viewport' => '1280x1024',
                            'url' => 'https://placid.app',
                        ],
                        'Barcode' => [
                            'value' => '123456789012',
                            'color' => '#000000',
                        ],
                    ],
                ],
            )
        ),
        tags: ['Placid'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(
                example: [
                    'message' => 'Image generation successful.',
                    'data' => [
                        'image_url' => 'https://api.placid.app/api/rest/images/9twcboittpg08',
                    ],
                ],
            )),
        ]
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
        path: '/api/placid/pdf-generation',
        operationId: 'pdfGeneration',
        description: 'PDF Generation',
        summary: 'Generate pdfs using Placid API.',
        security: [['authentication' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                example: [
                    'template_uuid' => '9twcboittpg08',
                    'layers' => [
                        'text layer' => [
                            'text' => 'Hello World',
                        ],
                    ],
                ],
            )
        ),
        tags: ['Placid'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(
                example: [
                    'message' => 'Image generation successful.',
                    'data' => [
                        'pdf_url' => 'https://api.placid.app/api/rest/pdfs/9twcboittpg08',
                    ],
                ],
            )),
        ]
    )]
    public function pdfGeneration(ImageGenerationRequest $request): JsonResponse
    {
        $data = ImageGenerationData::from($request->validated());

        $result = $this->service->pdfGeneration($data);

        return $this->logAndResponse([
            'message' => 'PDF generation successful.',
            'data' => $result,
        ]);
    }

    #[OA\Get(
        path: '/api/placid/retrieve-template',
        operationId: 'retrieveTemplate',
        description: 'Retrieve Template',
        summary: 'Retrieve a template using Placid API.',
        security: [['authentication' => []]],
        tags: ['Placid'],
        parameters: [
            new OA\Parameter(
                name: 'template_uuid',
                description: 'The UUID of the template to retrieve',
                in: 'query',
                required: true,
                schema: new OA\Schema(
                    type: 'string',
                    example: '9twcboittpg08'
                )
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(
                example: [

                ]
            )),
        ]
    )]
    public function retrieveTemplate(RetrieveTemplateRequest $request): JsonResponse
    {
        $data = RetrieveTemplateData::from($request->validated());

        $result = $this->service->retrieveTemplate($data);

        return $this->logAndResponse([
            'message' => 'Template retrieved successfully.',
            'data' => $result,
        ]);
    }

    #[OA\Get(
        path: '/api/placid/retrieve-pdf',
        operationId: 'retrievePdf',
        description: 'Retrieve Pdf',
        summary: 'Retrieve a pdf using Placid API.',
        security: [['authentication' => []]],
        tags: ['Placid'],
        parameters: [
            new OA\Parameter(
                name: 'pdf_id',
                description: 'The ID of the PDF to retrieve',
                in: 'query',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                    example: 880750
                )
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(
                example: [

                ]
            )),
        ]
    )]
    public function retrievePdf(RetrievePdfRequest $request): JsonResponse
    {
        $data = RetrievePdfData::from($request->validated());

        $result = $this->service->retrievePdf($data);

        return $this->logAndResponse([
            'message' => 'Pdf retrieved successfully.',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/placid/video-generation',
        operationId: 'videoGeneration',
        description: 'Video Generation',
        summary: 'Generate videos using Placid API.',
        security: [['authentication' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                example: [
                    'clips' => [
                        [
                            'template_uuid' => '9twcboittpg08',
                            'layers' => [
                                'text layer' => [
                                    'text' => 'Hello World',
                                    'text_color' => '#000000',
                                    'font' => 'Arial',
                                    'alt_text_color' => '#FFFFFF',
                                    'alt_font' => 'Arial',
                                ],
                            ],
                        ],
                    ],
                ],
            )
        ),
        tags: ['Placid'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(
                example: [
                    'message' => 'Video generation successful.',

                ],
            )),
        ]
    )]
    public function videoGeneration(VideoGenerationRequest $request): JsonResponse
    {
        $data = VideoGenerationData::from($request->validated());

        $result = $this->service->videoGeneration($data);

        return $this->logAndResponse([
            'message' => 'Video generation successful.',
            'data' => $result,
        ]);
    }


    #[OA\Get(
        path: '/api/placid/retrieve-video',
        operationId: 'retrieveVideo',
        description: 'Retrieve Video',
        summary: 'Retrieve a video using Placid API.',
        security: [['authentication' => []]],
        tags: ['Placid'],
        parameters: [
            new OA\Parameter(
                name: 'video_id',
                description: 'The ID of the Video to retrieve',
                in: 'query',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                    example: 505393
                )
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(
                example: [

                ]
            )),
        ]
    )]
    public function retrieveVideo(RetrieveVideoRequest $request): JsonResponse
    {
        $data = RetrieveVideoData::from($request->validated());

        $result = $this->service->retrieveVideo($data);

        return $this->logAndResponse([
            'message' => 'Video retrieved successfully.',
            'data' => $result,
        ]);
    }
}
