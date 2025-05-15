<?php

namespace App\Http\Controllers;

use App\Data\Request\Canva\CreateDesignData;
use App\Data\Request\Canva\CreateFolderData;
use App\Data\Request\Canva\ExportDesignJobData;
use App\Data\Request\Canva\GetDesignData;
use App\Data\Request\Canva\GetFolderData;
use App\Data\Request\Canva\GetFolderItemsData;
use App\Data\Request\Canva\GetUploadJobData;
use App\Data\Request\Canva\ListDesignsData;
use App\Data\Request\Canva\MoveFolderItemData;
use App\Data\Request\Canva\OAuthCallbackData;
use App\Data\Request\Canva\UpdateFolderData;
use App\Data\Request\Canva\UploadAssetData;
use App\Http\Requests\Canva\CreateDesignRequest;
use App\Http\Requests\Canva\CreateFolderRequest;
use App\Http\Requests\Canva\ExportDesignJobRequest;
use App\Http\Requests\Canva\GetDesignRequest;
use App\Http\Requests\Canva\GetFolderItemsRequest;
use App\Http\Requests\Canva\GetFolderRequest;
use App\Http\Requests\Canva\GetUploadJobRequest;
use App\Http\Requests\Canva\ListDesignsRequest;
use App\Http\Requests\Canva\MoveFolderItemRequest;
use App\Http\Requests\Canva\OAuthCallbackRequest;
use App\Http\Requests\Canva\UpdateFolderRequest;
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
    #[OA\RequestBody(
        description: 'Create a new design',
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                type: 'object',
                required: ['design_type', 'asset_id', 'title', 'endpoint_interface'],
                properties: [
                    new OA\Property(
                        property: 'design_type',
                        type: 'object',
                        required: ['type', 'name'],
                        properties: [
                            new OA\Property(
                                property: 'type',
                                type: 'string',
                                description: 'Design type',
                                example: 'preset'
                            ),
                            new OA\Property(
                                property: 'name',
                                type: 'string',
                                description: 'Design type name',
                                example: 'presentation'
                            ),
                            new OA\Property(
                                property: 'width',
                                type: 'integer',
                                description: 'Design width',
                                example: 800
                            ),
                            new OA\Property(
                                property: 'height',
                                type: 'integer',
                                description: 'Design height',
                                example: 600
                            ),
                        ]
                    ),
                    new OA\Property(
                        property: 'asset_id',
                        type: 'string',
                        description: 'Asset ID',
                        example: 'MAGnWRFNXUA'
                    ),
                    new OA\Property(
                        property: 'title',
                        type: 'string',
                        description: 'Title of the design',
                        example: 'My Holiday Presentation'
                    ),
                    new OA\Property(
                        property: 'endpoint_interface',
                        type: 'string',
                        description: 'Endpoint interface',
                        example: 'generate'
                    ),
                ]
            )
        )
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
        path: '/api/canva/create_export_design',
        summary: 'Create export design job',
        description: 'Create export design job.',
        tags: ["Canva"],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["design_id", "format"],
            properties: [
                new OA\Property(property: 'design_id', type: 'string', example: 'design_12345'),
                new OA\Property(
                    property: 'format',
                    properties: [
                        new OA\Property(
                            property: 'type',
                            type: 'string',
                            enum: ['pdf', 'jpg', 'png', 'pptx', 'gif', 'mp4'],
                            example: 'png'
                        ),
                        new OA\Property(
                            property: 'quality',
                            properties: [
                                new OA\Property(
                                    property: 'orientation',
                                    type: 'string',
                                    enum: ['horizontal', 'vertical'],
                                    example: 'horizontal'
                                ),
                                new OA\Property(
                                    property: 'resolution',
                                    type: 'string',
                                    enum: ['480p', '720p', '1080p', '4k'],
                                    example: '1080p'
                                ),
                            ],
                            type: 'object'
                        ),
                        new OA\Property(
                            property: 'page',
                            type: 'array',
                            items: new OA\Items(type: 'integer', example: 1)
                        ),
                        new OA\Property(
                            property: 'export_quality',
                            type: 'string',
                            enum: ['regular', 'pro'],
                            example: 'pro'
                        ),
                        new OA\Property(
                            property: 'size',
                            type: 'string',
                            enum: ['a4', 'a3', 'letter', 'legal'],
                            example: 'a4'
                        ),
                        new OA\Property(property: 'height', type: 'integer', example: 1080),
                        new OA\Property(property: 'width', type: 'integer', example: 1920),
                        new OA\Property(property: 'lossless', type: 'boolean', example: true),
                        new OA\Property(property: 'transparent_background', type: 'boolean', example: true),
                        new OA\Property(property: 'as_single_image', type: 'boolean', example: true),
                    ],
                    type: 'object'
                ),
                new OA\Property(
                    property: 'file',
                    type: 'string',
                    format: 'binary',
                    description: 'Optional file upload'
                ),
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 202,
        description: 'In progress job',
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
        response: 200,
        description: 'Successfully completed job',
        content: new OA\JsonContent(
            example: [
                'job' => [
                    'id' => 'e08861ae-3b29-45db-8dc1-1fe0bf7f1cc8',
                    'status' => 'success',
                    "urls" => [
                        "https://export-download.canva.com/..."
                    ]
                ],
            ]
        )
    )]
    #[OA\Response(
        response: 204,
        description: 'Failed job',
        content: new OA\JsonContent(
            example: [
                'job' => [
                    'id' => 'e08861ae-3b29-45db-8dc1-1fe0bf7f1cc8',
                    'status' => 'failed',
                    "error" => [
                        "code" => "license_required",
                        "message" => "User doesn't have the required license to export in PRO quality."
                    ]
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
                'message' => [],
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
    public function createDesignExportJob(ExportDesignJobRequest $request)
    {
        $data = ExportDesignJobData::from($request->validated());

        $result = $this->service->exportDesignJob($data);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/canva/get_export_design/{exportID}',
        summary: 'Create export design job',
        description: 'Create export design job.',
        tags: ["Canva"],
    )]
    #[OA\Parameter(
        name: 'exportID',
        in: 'path',
        required: true,
        description: 'ID of the export',
        schema: new OA\Schema(
            type: 'string',
            example: '17f20503-6c24-4c16-946b-35dbbce2af2f',
        ),
    )]
    #[OA\Response(
        response: 202,
        description: 'In progress job',
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
        response: 200,
        description: 'Successfully completed job',
        content: new OA\JsonContent(
            example: [
                'job' => [
                    'id' => 'e08861ae-3b29-45db-8dc1-1fe0bf7f1cc8',
                    'status' => 'success',
                    "urls" => [
                        "https://export-download.canva.com/..."
                    ]
                ],
            ]
        )
    )]
    #[OA\Response(
        response: 204,
        description: 'Failed job',
        content: new OA\JsonContent(
            example: [
                'job' => [
                    'id' => 'e08861ae-3b29-45db-8dc1-1fe0bf7f1cc8',
                    'status' => 'failed',
                    "error" => [
                        "code" => "license_required",
                        "message" => "User doesn't have the required license to export in PRO quality."
                    ]
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
                'message' => [],
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
    public function getDesignExportJob(string $exportID)
    {
        $result = $this->service->getExportDesignJob($exportID);

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

    #[OA\Post(
        path: '/api/canva/create_folder',
        summary: 'Create a new folder',
        description: 'Create a new folder with the specified parameters',
        tags: ["Canva"],
    )]
    #[OA\Parameter(
        name: 'name',
        in: 'query',
        required: true,
        description: 'The name of the folder',
        schema: new OA\Schema(type: 'string', example: 'My awesome holiday', minLength: 1, maxLength: 255)
    )]
    #[OA\Parameter(
        name: 'parent_folder_id',
        in: 'query',
        required: true,
        description: 'The folder ID of the parent folder. To create a new folder at the top level, use the ID root',
        schema: new OA\Schema(type: 'string', example: 'root', minLength: 1, maxLength: 50)
    )]
    #[OA\Parameter(
        name: 'endpoint_interface',
        in: 'query',
        required: true,
        description: 'Endpoint interface',
        schema: new OA\Schema(type: 'string', example: 'generate', enum: ['generate'])
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'folder' => [
                    'id' => 'FAF2lZtloor',
                    'name' => 'My awesome holiday',
                    'created_at' => 1377396000,
                    'updated_at' => 1692928800,
                    'thumbnail' => [
                        'width' => 595,
                        'height' => 335,
                        'url' => 'https://document-export.canva.com/Vczz9/zF9vzVtdADc/2/thumbnail/0001.png?<query-string>',
                    ],
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
    public function createFolder(CreateFolderRequest $request)
    {
        $data = CreateFolderData::from($request->validated());

        $result = $this->service->createFolder($data);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/canva/get_folder_details',
        summary: 'Get Canva folder details',
        description: 'Retrieve details of a specific Canva folder.',
        tags: ["Canva"],
    )]
    #[OA\Parameter(
        name: 'folder_id',
        in: 'query',
        required: true,
        description: 'ID of the folder to retrieve details for.',
        schema: new OA\Schema(type: 'string', example: 'folder_id')
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
                'folder' => [
                    'id' => 'FAF2lZtloor',
                    'name' => 'My awesome holiday',
                    'created_at' => 1377396000,
                    'updated_at' => 1692928800,
                    'thumbnail' => [
                        'width' => 595,
                        'height' => 335,
                        'url' => 'https://document-export.canva.com/Vczz9/zF9vzVtdADc/2/thumbnail/0001.png?<query-string>',
                    ],
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
                'message' => 'Invalid parameters provided. Ensure "folder_id" and "endpoint_interface" are specified and valid.',
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
    public function getFolder(GetFolderRequest $request)
    {
        $data = GetFolderData::from($request->validated());

        $result = $this->service->getFolder($data);

        return $this->logAndResponse($result);
    }

    #[OA\Put(
        path: '/api/canva/update_folder',
        summary: 'Update a new folder',
        description: 'Update a folder with the specified parameters',
        tags: ["Canva"],
    )]
    #[OA\Parameter(
        name: 'name',
        in: 'query',
        required: true,
        description: 'The name of the folder',
        schema: new OA\Schema(type: 'string', example: 'My awesome holiday', minLength: 1, maxLength: 255)
    )]
    #[OA\Parameter(
        name: 'folder_id',
        in: 'query',
        required: true,
        description: 'The folder ID of the parent folder. To Update a new folder at the top level, use the ID root',
        schema: new OA\Schema(type: 'string', example: 'root', minLength: 1, maxLength: 50)
    )]
    #[OA\Parameter(
        name: 'endpoint_interface',
        in: 'query',
        required: true,
        description: 'Endpoint interface',
        schema: new OA\Schema(type: 'string', example: 'generate', enum: ['generate'])
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'folder' => [
                    'id' => 'FAF2lZtloor',
                    'name' => 'My awesome holiday',
                    'created_at' => 1377396000,
                    'updated_at' => 1692928800,
                    'thumbnail' => [
                        'width' => 595,
                        'height' => 335,
                        'url' => 'https://document-export.canva.com/Vczz9/zF9vzVtdADc/2/thumbnail/0001.png?<query-string>',
                    ],
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
    public function updateFolder(UpdateFolderRequest $request)
    {
        $data = UpdateFolderData::from($request->validated());

        $result = $this->service->updateFolder($data);

        return $this->logAndResponse($result);
    }

    #[OA\Delete(
        path: '/api/canva/delete_folder/{folderID}',
        summary: 'Delete a new folder',
        description: 'Delete a folder with the specified parameters',
        tags: ["Canva"],
    )]
    #[OA\Parameter(
        name: 'folderID',
        in: 'path',
        required: true,
        description: 'The folder ID to be deleted',
        schema: new OA\Schema(type: 'string', example: 'root', minLength: 1, maxLength: 50)
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'status' => 'success'
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
    public function deleteFolder(string $folderID)
    {
        $result = $this->service->deleteFolder($folderID);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/canva/get_folder_items',
        summary: 'Get folder items',
        description: 'Get folder items with the specified parameters',
        tags: ["Canva"],
    )]
    #[OA\Parameter(
        name: 'folder_id',
        in: 'query',
        required: true,
        description: 'The ID of the folder to update',
        schema: new OA\Schema(type: 'string', example: 'FAF2lZtloor')
    )]
    #[OA\Parameter(
        name: 'continuation',
        in: 'query',
        required: false,
        description: 'Continuation token (optional)',
        schema: new OA\Schema(type: 'string', example: 'continue-here')
    )]
    #[OA\Parameter(
        name: 'item_types',
        in: 'query',
        required: false,
        description: 'Filter by item types (optional)',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(
                type: 'string',
                enum: ['design', 'folder', 'image']
            ),
            example: ['design', 'image']
        ),
        style: 'form',
        explode: true
    )]
    #[OA\Parameter(
        name: 'sort_by',
        in: 'query',
        required: false,
        description: 'Sort items by criteria',
        schema: new OA\Schema(
            type: 'string',
            enum: [
                'created_ascending',
                'created_descending',
                'modified_ascending',
                'modified_descending',
                'title_ascending',
                'title_descending'
            ],
            example: 'created_descending'
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response with items',
        content: new OA\JsonContent(
            type: 'object',
            required: ['items'],
            properties: [
                new OA\Property(
                    property: 'items',
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        required: ['type', 'folder'],
                        properties: [
                            new OA\Property(
                                property: 'type',
                                type: 'string',
                                example: 'folder'
                            ),
                            new OA\Property(
                                property: 'folder',
                                type: 'object',
                                required: ['id', 'name', 'created_at', 'updated_at'],
                                properties: [
                                    new OA\Property(
                                        property: 'id',
                                        type: 'string',
                                        example: 'FAFniUzF2XY'
                                    ),
                                    new OA\Property(
                                        property: 'name',
                                        type: 'string',
                                        example: 'item 1'
                                    ),
                                    new OA\Property(
                                        property: 'created_at',
                                        type: 'integer',
                                        example: 1747330758
                                    ),
                                    new OA\Property(
                                        property: 'updated_at',
                                        type: 'integer',
                                        example: 1747330758
                                    ),
                                ]
                            )
                        ]
                    )
                )
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad request',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Invalid parameters provided. Ensure "folder_id" and "endpoint_interface" are specified and valid.',
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
    public function getFolderItems(GetFolderItemsRequest $request)
    {
        $data = GetFolderItemsData::from($request->validated());

        $result = $this->service->getFolderItems($data);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/canva/move_folder_item',
        summary: 'Move folder item',
        description: 'Move folder item with the specified parameters',
        tags: ["Canva"],
    )]
    #[OA\Parameter(
        name: 'to_folder_id',
        in: 'query',
        required: true,
        description: 'The ID of the to folder',
        schema: new OA\Schema(type: 'string', example: 'FAF2lZtloor')
    )]
    #[OA\Parameter(
        name: 'item_id',
        in: 'query',
        required: true,
        description: 'The ID of the item to move',
        schema: new OA\Schema(type: 'string', example: 'FAF2lZtloor')
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'status' => 'success'
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad request',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Invalid parameters provided. Ensure "item_id" are specified and valid.',
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
    public function moveFolderItem(MoveFolderItemRequest $request)
    {
        $data = MoveFolderItemData::from($request->validated());

        $result = $this->service->moveFolderItem($data);

        return $this->logAndResponse($result);
    }
}
