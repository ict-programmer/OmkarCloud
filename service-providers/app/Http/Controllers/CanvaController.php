<?php

namespace App\Http\Controllers;

use App\Data\Request\Canva\CreateDesignData;
use App\Data\Request\Canva\GetDesignData;
use App\Data\Request\Canva\GetUploadJobData;
use App\Data\Request\Canva\ListDesignsData;
use App\Data\Request\Canva\OAuthCallbackData;
use App\Data\Request\Canva\UploadAssetData;
use App\Http\Requests\Canva\CreateDesignRequest;
use App\Http\Requests\Canva\GetDesignRequest;
use App\Http\Requests\Canva\GetUploadJobRequest;
use App\Http\Requests\Canva\ListDesignsRequest;
use App\Http\Requests\Canva\OAuthCallbackRequest;
use App\Http\Requests\Canva\UploadAssetRequest;
use App\Services\CanvaService;
use OpenApi\Attributes as OA;

class CanvaController extends BaseController
{
    public function __construct(protected CanvaService $service) {}

    #[OA\Post(
        path: '/api/canva/oauth/authorize',
        summary: 'Get the authorization url',
        description: 'Get the authorization url',
        tags: ["Canva"],
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'url' => 'https://www.canva.com/api/oauth/authorize?response_type=code&code_challenge=code_challenge&state=state',
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while processing your request',
            ],
        )
    )]
    public function initiateOAuth()
    {
        $result = $this->service->oauthInit();

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/canva/oauth/callback',
        summary: 'Handle OAuth callback',
        description: 'Handle OAuth callback',
        tags: ["Canva"],
    )]
    #[OA\QueryParameter(
        name: 'code',
        description: 'Code',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            example: 'code'
        )
    )]
    #[OA\QueryParameter(
        name: 'state',
        description: 'State',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            example: 'state'
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'access_token' => 'access_token',
                'refresh_token' => 'refresh_token',
                'expires_in' => 3600,
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
                    'code' => 'The code field is required.',
                    'state' => 'The state field is required.',
                ],
            ],
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad request',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'The code or state is invalid',
            ],
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while processing your request',
            ],
        )
    )]
    public function callback(OAuthCallbackRequest $request)
    {
        $data = OAuthCallbackData::from($request->validated());

        $result = $this->service->oauthCallback($data);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/canva/oauth/refresh_token',
        summary: 'Refresh OAuth token',
        description: 'Refresh OAuth token',
        tags: ["Canva"],
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'access_token' => 'access_token',
                'refresh_token' => 'refresh_token',
                'expires_in' => 3600,
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while processing your request',
            ],
        )
    )]
    public function refreshToken()
    {
        $result = $this->service->refreshToken();

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/canva/create_design',
        summary: 'Create a new design',
        description: 'Create a new design with the specified parameters',
        tags: ["Canva"],
    )]
    #[OA\Parameter(
        name: 'design_type[type]',
        in: 'query',
        required: true,
        description: 'Design type',
        schema: new OA\Schema(type: 'string', example: 'presentation')
    )]
    #[OA\Parameter(
        name: 'design_type[name]',
        in: 'query',
        required: true,
        description: 'Design type name',
        schema: new OA\Schema(type: 'string', example: 'My Presentation')
    )]
    #[OA\Parameter(
        name: 'asset_id',
        in: 'query',
        required: true,
        description: 'Asset ID',
        schema: new OA\Schema(type: 'string', example: 'asset_12345')
    )]
    #[OA\Parameter(
        name: 'title',
        in: 'query',
        required: true,
        description: 'Title of the design',
        schema: new OA\Schema(type: 'string', example: 'My Holiday Presentation')
    )]
    #[OA\Parameter(
        name: 'endpoint_interface',
        in: 'query',
        required: true,
        description: 'Endpoint interface',
        schema: new OA\Schema(type: 'string', example: 'generate')
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'design' => [
                    'id' => 'design_id',
                    'owner' => [
                        'user_id' => 'user_id',
                        'team_id' => 'team_id',
                    ],
                    'urls' => [
                        'edit_url' => 'https://www.canva.com/design/...',
                        'view_url' => 'https://www.canva.com/design/...',
                    ],
                    'created_at' => 1616161616,
                    'updated_at' => 1616161616,
                    'title' => 'My Holiday Presentation',
                    'thumbnail' => [
                        'width' => 800,
                        'height' => 600,
                        'url' => 'https://www.canva.com/design/...',
                    ],
                    'page_count' => 10,
                ],
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad request',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Invalid parameters provided. Ensure "endpoint_interface" is specified and valid.',
            ],
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while processing your request',
            ],
        )
    )]
    public function createDesign(CreateDesignRequest $request)
    {
        $data = CreateDesignData::from($request->validated());

        $result = $this->service->createDesign($data);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/canva/list_design',
        summary: 'List Canva designs',
        description: 'Retrieve a list of Canva designs with pagination support.',
        tags: ["Canva"],
    )]
    #[OA\Parameter(
        name: 'continuation',
        in: 'query',
        required: false,
        description: 'Token for fetching the next page of results. Leave empty for the first page.',
        schema: new OA\Schema(type: 'string', example: 'next_page_token')
    )]
    #[OA\Parameter(
        name: 'endpoint_interface',
        in: 'query',
        required: true,
        description: 'Endpoint interface to specify the operation.',
        schema: new OA\Schema(type: 'string', example: 'generate')
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'items' => [
                    [
                        'id' => 'design_id',
                        'owner' => [
                            'user_id' => 'user_id',
                            'team_id' => 'team_id',
                        ],
                        'urls' => [
                            'edit_url' => 'https://www.canva.com/design/...',
                            'view_url' => 'https://www.canva.com/design/...',
                        ],
                        'created_at' => 1616161616,
                        'updated_at' => 1616161616,
                        'title' => 'Design Title',
                        'thumbnail' => [
                            'width' => 800,
                            'height' => 600,
                            'url' => 'https://www.canva.com/design/...',
                        ],
                        'page_count' => 10,
                    ],
                ],
                'continuation' => 'next_page_token',
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad request',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Invalid parameters provided. Ensure "endpoint_interface" is specified and valid.',
            ],
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while processing your request',
            ]
        )
    )]
    public function listDesigns(ListDesignsRequest $request)
    {
        $data = ListDesignsData::from($request->validated());

        $result = $this->service->listDesigns($data);

        return $this->logAndResponse($result);
    }


    #[OA\Get(
        path: '/api/canva/get_design_details',
        summary: 'Get Canva design details',
        description: 'Retrieve details of a specific Canva design.',
        tags: ["Canva"],
    )]
    #[OA\Parameter(
        name: 'design_id',
        in: 'query',
        required: true,
        description: 'ID of the design to retrieve details for.',
        schema: new OA\Schema(type: 'string', example: 'design_id')
    )]
    #[OA\Parameter(
        name: 'endpoint_interface',
        in: 'query',
        required: true,
        description: 'Endpoint interface to specify the operation.',
        schema: new OA\Schema(type: 'string', example: 'generate')
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'design' => [
                    'id' => 'design_id',
                    'owner' => [
                        'user_id' => 'user_id',
                        'team_id' => 'team_id',
                    ],
                    'urls' => [
                        'edit_url' => 'https://www.canva.com/design/...',
                        'view_url' => 'https://www.canva.com/design/...',
                    ],
                    'created_at' => 1616161616,
                    'updated_at' => 1616161616,
                    'title' => 'Design Title',
                    'thumbnail' => [
                        'width' => 800,
                        'height' => 600,
                        'url' => 'https://www.canva.com/design/...',
                    ],
                    'page_count' => 10,
                ],
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad request',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Invalid parameters provided. Ensure "design_id" and "endpoint_interface" are specified and valid.',
            ],
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while processing your request',
            ]
        )
    )]
    public function getDesign(GetDesignRequest $request)
    {
        $data = GetDesignData::from($request->validated());

        $result = $this->service->getDesign($data);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/canva/asset_upload',
        summary: 'Upload asset',
        description: 'Upload asset',
        tags: ["Canva"],
    )]
    #[OA\RequestBody(
        description: 'Upload asset to Canva',
        required: true,
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(
                type: 'object',
                required: ['file'],
                properties: [
                    new OA\Property(
                        property: 'file',
                        type: 'string',
                        format: 'binary',
                        description: 'File to upload'
                    )
                ]
            )
        )
    )]

    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'job' => [
                    'id' => 'e08861ae-3b29-45db-8dc1-1fe0bf7f1cc8',
                    'status' => 'in_progress',
                ],
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
                    'files' => 'The files field is required.',
                    'files.*' => 'The file is required.',
                    'files.*.file' => 'The upload must be a valid file.',
                    'files.*.mimes' => 'The file must be a valid image or video. Allowed file types: jpeg, png, heic, tiff, webp, gif, m4v, mkv, mp4, mpeg, webm, quicktime.',
                ],
            ],
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while processing your request',
            ]
        )
    )]
    public function uploadAsset(UploadAssetRequest $request)
    {
        $data = UploadAssetData::from($request->validated());

        $result = $this->service->uploadAsset($data);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/canva/asset_upload_job',
        summary: 'Get upload job',
        description: 'Get upload job',
        tags: ["Canva"],
    )]
    #[OA\Parameter(
        name: 'job_id',
        in: 'query',
        required: true,
        description: 'ID of the upload job to retrieve details for.',
        schema: new OA\Schema(type: 'string', example: 'e08861ae-3b29-45db-8dc1-1fe0bf7f1cc8')
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'job' => [
                    'id' => 'e08861ae-3b29-45db-8dc1-1fe0bf7f1cc8',
                    'status' => 'success',
                    'asset' => [
                        'id' => 'asset_12345',
                        'name' => 'My Asset',
                        'tags' => ['tag1', 'tag2'],
                        'created_at' => '2023-04-23T20:15:07.000000Z',
                        'updated_at' => '2023-04-23T20:15:07.000000Z',
                        'thumbnail' => [
                            'width' => 800,
                            'height' => 600,
                            'url' => 'https://www.canva.com/design/...',
                        ],
                    ],
                ],
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
                    'job_id' => 'The job_id field is required.',
                ],
            ],
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while processing your request',
            ]
        )
    )]
    public function getUploadJob(GetUploadJobRequest $request)
    {
        $data = GetUploadJobData::from($request->validated());

        $result = $this->service->getUploadJob($data);

        return $this->logAndResponse($result);
    }
}
