<?php

namespace Database\Seeders;

use App\Enums\common\ServiceProviderEnum;
use App\Http\Controllers\CanvaController;
use App\Http\Requests\Canva\CreateDesignRequest;
use App\Http\Requests\Canva\CreateFolderRequest;
use App\Http\Requests\Canva\ExportDesignJobRequest;
use App\Http\Requests\Canva\GetDesignRequest;
use App\Http\Requests\Canva\GetFolderItemsRequest;
use App\Http\Requests\Canva\GetFolderRequest;
use App\Http\Requests\Canva\GetUploadJobRequest;
use App\Http\Requests\Canva\ListDesignsRequest;
use App\Http\Requests\Canva\MoveFolderItemRequest;
use App\Http\Requests\Canva\UpdateFolderRequest;
use App\Http\Requests\Canva\UploadAssetRequest;
use App\Models\ServiceProvider;
use App\Traits\ServiceProviderSeederTrait;
use Illuminate\Database\Seeder;

class CanvaServiceProviderSeeder extends Seeder
{
    use ServiceProviderSeederTrait;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => ServiceProviderEnum::CANVA->value],
            [
                'parameters' => [
                    'api_key' => 'YOUR_API_KEY',
                    'api_secret' => 'YOUR_API_SECRET',
                    'base_url' => 'https://api.canva.com',
                    'version' => 'v1',
                    'features' => [
                        'create_design',
                        'list_design',
                        'get_design_details',
                        'create_export_design',
                        'get_export_design',
                        'asset_upload',
                        'asset_upload_job',
                        'create_folder',
                        'get_folder_details',
                        'update_folder',
                        'delete_folder',
                        'get_folder_items',
                        'move_folder_item',
                    ],
                    'documentation' => [
                        'description' => 'Canva API provides advanced AI capabilities for text generation, analysis, and processing',
                        'api_documentation' => 'https://www.canva.dev/docs/connect',
                        'rate_limits' => 'Varies by plan',
                        'authentication' => 'API Key required in headers',
                    ],
                ],
                'is_active' => true,
                'controller_name' => CanvaController::class,
            ]
        );

        $serviceTypes = [
            [
                'name' => 'create_design',
                'input_parameters' => [
                    'design_type' => [
                        'type' => 'array',
                        'required' => true,
                        'description' => 'Design type',
                        'example' => [
                            'type' => 'preset',
                            'name' => 'presentation',
                            'width' => 800,
                            'height' => 600,
                        ],
                        'validation' => 'required|array',
                        'structure' => 'Array of objects with consistent keys',
                        'array_type' => 'object',
                        'common_fields' => [
                            'type' => 'string',
                            'name' => 'string',
                            'width' => 'integer',
                            'height' => 'integer',
                        ],
                    ],
                    'asset_id' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'Asset ID',
                        'example' => 'MAGnWRFNXUA',
                        'validation' => 'required|string',
                    ],
                    'title' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'Title of the design',
                        'example' => 'My Holiday Presentation',
                        'validation' => 'required|string',
                    ],
                    'endpoint_interface' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'Endpoint interface',
                        'example' => 'generate',
                        'validation' => 'required|string|in:generate',
                    ],
                ],
                'response' => [
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
                ],
                'response_path' => [
                    'final_result' => '$.design',
                    'design_id' => '$.design.id',
                    'user_id' => '$.design.owner.user_id',
                    'team_id' => '$.design.owner.team_id',
                    'edit_url' => '$.design.urls.edit_url',
                    'view_url' => '$.design.urls.view_url',
                    'created_at' => '$.design.created_at',
                    'updated_at' => '$.design.updated_at',
                    'title' => '$.design.title',
                    'thumbnail_width' => '$.design.thumbnail.width',
                    'thumbnail_height' => '$.design.thumbnail.height',
                    'thumbnail_url' => '$.design.thumbnail.url',
                    'page_count' => '$.design.page_count',
                ],
                'request_class_name' => CreateDesignRequest::class,
                'function_name' => 'createDesign',
            ],
            [
                'name' => 'list_design',
                'input_parameters' => [
                    'continuation' => [
                        'type' => 'string',
                        'required' => false,
                        'description' => 'Token for fetching the next page of results. Leave empty for the first page.',
                        'example' => 'next_page_token',
                        'validation' => 'nullable|string',
                    ],
                    'endpoint_interface' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'Endpoint interface to specify the operation.',
                        'example' => 'generate',
                        'validation' => 'required|string|in:generate',
                    ],
                ],
                'response' => [
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
                    ]
                ],
                'response_path' => [
                    'final_result' => '$.items',
                    'items' => '$.items',
                    'item_id' => '$.items[*].id',
                    'item_owner_user_id' => '$.items[*].owner.user_id',
                    'item_owner_team_id' => '$.items[*].owner.team_id',
                    'item_urls_edit_url' => '$.items[*].urls.edit_url',
                    'item_urls_view_url' => '$.items[*].urls.view_url',
                    'item_created_at' => '$.items[*].created_at',
                    'item_updated_at' => '$.items[*].updated_at',
                    'item_title' => '$.items[*].title',
                    'item_thumbnail_width' => '$.items[*].thumbnail.width',
                    'item_thumbnail_height' => '$.items[*].thumbnail.height',
                    'item_thumbnail_url' => '$.items[*].thumbnail.url',
                    'item_page_count' => '$.items[*].page_count',
                    'continuation' => '$.continuation',
                ],
                'request_class_name' => ListDesignsRequest::class,
                'function_name' => 'listDesigns',
            ],
            [
                'name' => 'get_design_details',
                'input_parameters' => [
                    'design_id' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'ID of the design to retrieve details for.',
                        'example' => 'design_id',
                        'validation' => 'required|string',
                    ],
                    'endpoint_interface' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'Endpoint interface to specify the operation.',
                        'example' => 'generate',
                        'validation' => 'required|string|in:generate',
                    ],
                ],
                'response' => [
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
                ],
                'response_path' => [
                    'final_result' => '$.design',
                    'design_id' => '$.design.id',
                    'user_id' => '$.design.owner.user_id',
                    'team_id' => '$.design.owner.team_id',
                    'edit_url' => '$.design.urls.edit_url',
                    'view_url' => '$.design.urls.view_url',
                    'created_at' => '$.design.created_at',
                    'updated_at' => '$.design.updated_at',
                    'title' => '$.design.title',
                    'thumbnail_width' => '$.design.thumbnail.width',
                    'thumbnail_height' => '$.design.thumbnail.height',
                    'thumbnail_url' => '$.design.thumbnail.url',
                    'page_count' => '$.design.page_count',
                ],
                'request_class_name' => GetDesignRequest::class,
                'function_name' => 'getDesign',
            ],
            [
                'name' => 'create_export_design',
                'input_parameters' => [
                    'design_id' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'ID of the design to export.',
                        'example' => 'design_id',
                        'validation' => 'required|string',
                    ],
                    'format' => [
                        'type' => 'array',
                        'required' => true,
                        'description' => 'Format of the export',
                        'example' => [
                            'type' => 'png',
                            'quality' => [
                                'orientation' => 'horizontal',
                                'resolution' => '480p',
                            ],
                            'page' => [1, 2],
                            'export_quality' => 'pro',
                            'size' => 'a4',
                            'height' => 1080,
                            'width' => 1920,
                            'lossless' => true,
                            'transparent_background' => true,
                            'as_single_image' => true,
                        ],
                        'validation' => 'required|array',
                        'structure' => 'Array of objects with consistent keys',
                        'array_type' => 'object',
                        'common_fields' => [
                            'type' => 'string',
                            'name' => 'string',
                            'width' => 'integer',
                            'height' => 'integer',
                            'orientation' => 'string',
                            'resolution' => 'string',
                            'page' => 'array',
                            'export_quality' => 'string',
                            'size' => 'string',
                            'lossless' => 'boolean',
                            'transparent_background' => 'boolean',
                            'as_single_image' => 'boolean',
                        ],
                        'structure' => 'Array of objects with consistent keys',
                    ],
                ],
                'response' => [
                    'job' => [
                        'id' => 'e08861ae-3b29-45db-8dc1-1fe0bf7f1cc8',
                        'status' => 'in_progress',
                    ]
                ],
                'response_path' => [
                    'final_result' => '$.job',
                    'job_id' => '$.job.id',
                    'job_status' => '$.job.status',
                ],
                'request_class_name' => ExportDesignJobRequest::class,
                'function_name' => 'createDesignExportJob',
            ],
            [
                'name' => 'get_export_design',
                'input_parameters' => [
                    'exportID' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'ID of the export',
                        'example' => '17f20503-6c24-4c16-946b-35dbbce2af2f',
                        'validation' => 'required|string|uuid',
                    ]
                ],
                'response' => [
                    'job' => [
                        'id' => 'e08861ae-3b29-45db-8dc1-1fe0bf7f1cc8',
                        'status' => 'success',
                        'urls' => [
                            "https://export-download.canva.com/..."
                        ]
                    ]
                ],
                'response_path' => [
                    'final_result' => '$.job',
                    'job_id' => '$.job.id',
                    'job_status' => '$.job.status',
                    'job_urls' => '$.job.urls',
                ],
                'request_class_name' => null,
                'function_name' => 'getDesignExportJob',
            ],
            [
                'name' => 'asset_upload',
                'input_parameters' => [
                    'file' => [
                        'type' => 'file',
                        'required' => true,
                        'description' => 'File to upload',
                        'example' => 'file',
                        'validation' => 'required|file|max:30720',
                    ],
                ],
                'response' => [
                    'job' => [
                        'id' => 'e08861ae-3b29-45db-8dc1-1fe0bf7f1cc8',
                        'status' => 'in_progress',
                    ],
                ],
                'response_path' => [
                    'job' => '$.job',
                    'id' => '$.job.id',
                    'status' => '$.job.status',
                ],
                'request_class_name' => UploadAssetRequest::class,
                'function_name' => 'uploadAsset',
            ],
            [
                'name' => 'asset_upload_job',
                'input_parameters' => [
                    'job_id' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'ID of the upload job to retrieve details for.',
                        'example' => 'e08861ae-3b29-45db-8dc1-1fe0bf7f1cc8',
                        'validation' => 'required|string',
                    ],
                ],
                'response' => [
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
                ],
                'response_path' => [
                    'final_result' => '$.job',
                    'id' => '$.job.id',
                    'status' => '$.job.status',
                    'asset' => '$.job.asset',
                ],
                'request_class_name' => GetUploadJobRequest::class,
                'function_name' => 'getUploadJob',
            ],
            [
                'name' => 'create_folder',
                'input_parameters' => [
                    'name' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'The name of the folder',
                        'example' => 'My awesome holiday',
                        'validation' => 'required|string|min:1|max:255',
                    ],
                    'parent_folder_id' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'The folder ID of the parent folder. To create a new folder at the top level, use the ID root',
                        'example' => 'root',
                        'validation' => 'required|string|min:1|max:50',
                    ],
                    'endpoint_interface' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'Endpoint interface',
                        'example' => 'generate',
                        'validation' => 'required|string|in:generate',
                    ],
                ],
                'response' => [
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
                ],
                'response_path' => [
                    'final_result' => '$.folder',
                    'id' => '$.folder.id',
                    'name' => '$.folder.name',
                    'created_at' => '$.folder.created_at',
                    'updated_at' => '$.folder.updated_at',
                    'thumbnail_width' => '$.folder.thumbnail.width',
                    'thumbnail_height' => '$.folder.thumbnail.height',
                    'thumbnail_url' => '$.folder.thumbnail.url',
                ],
                'request_class_name' => CreateFolderRequest::class,
                'function_name' => 'createFolder',
            ],
            [
                'name' => 'get_folder_details',
                'input_parameters' => [
                    'folder_id' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'ID of the folder to retrieve details for.',
                        'example' => 'folder_id',
                        'validation' => 'required|string',
                    ],
                    'endpoint_interface' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'Endpoint interface to specify the operation.',
                        'example' => 'generate',
                        'validation' => 'required|string|in:generate',
                    ],
                ],
                'response' => [
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
                ],
                'response_path' => [
                    'final_result' => '$.folder',
                    'id' => '$.folder.id',
                    'name' => '$.folder.name',
                    'created_at' => '$.folder.created_at',
                    'updated_at' => '$.folder.updated_at',
                    'thumbnail_width' => '$.folder.thumbnail.width',
                    'thumbnail_height' => '$.folder.thumbnail.height',
                    'thumbnail_url' => '$.folder.thumbnail.url',
                ],
                'request_class_name' => GetFolderRequest::class,
                'function_name' => 'getFolder',
            ],
            [
                'name' => 'update_folder',
                'input_parameters' => [
                    'name' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'The name of the folder',
                        'example' => 'My awesome holiday',
                        'validation' => 'required|string|min:1|max:255',
                    ],
                    'folder_id' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'The folder ID of the parent folder. To Update a new folder at the top level, use the ID root',
                        'example' => 'root',
                        'validation' => 'required|string|min:1|max:50',
                    ],
                    'endpoint_interface' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'Endpoint interface',
                        'example' => 'generate',
                        'validation' => 'required|string|in:generate',
                    ],
                ],
                'response' => [
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
                ],
                'response_path' => [
                    'final_result' => '$.folder',
                    'id' => '$.folder.id',
                    'name' => '$.folder.name',
                    'created_at' => '$.folder.created_at',
                    'updated_at' => '$.folder.updated_at',
                    'thumbnail_width' => '$.folder.thumbnail.width',
                    'thumbnail_height' => '$.folder.thumbnail.height',
                    'thumbnail_url' => '$.folder.thumbnail.url',
                ],
                'request_class_name' => UpdateFolderRequest::class,
                'function_name' => 'updateFolder',
            ],
            [
                'name' => 'delete_folder',
                'input_parameters' => [
                    'folderID' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'The folder ID to be deleted',
                        'example' => 'root',
                        'validation' => 'required|string|min:1|max:50',
                    ],
                ],
                'response' => [
                    'status' => 'success',
                ],
                'response_path' => [
                    'final_result' => '$.status',
                ],
                'request_class_name' => null,
                'function_name' => 'deleteFolder',
            ],
            [
                'name' => 'get_folder_items',
                'input_parameters' => [
                    'folder_id' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'The ID of the folder to update',
                        'example' => 'FAF2lZtloor',
                        'validation' => 'required|string|min:1|max:50',
                    ],
                    'continuation' => [
                        'type' => 'string',
                        'required' => false,
                        'description' => 'Continuation token (optional)',
                        'example' => 'continue-here',
                        'validation' => 'nullable|string',
                    ],
                    'item_types' => [
                        'type' => 'array',
                        'required' => false,
                        'description' => 'Filter by item types (optional)',
                        'example' => ['design', 'image'],
                        'validation' => 'nullable|array',
                        'array_type' => 'string',
                        'common_item_types' => [
                            'design',
                            'folder',
                            'image',
                        ],
                    ],
                    'sort_by' => [
                        'type' => 'string',
                        'required' => false,
                        'description' => 'Sort items by criteria',
                        'example' => 'created_ascending',
                        'validation' => 'nullable|string|in:created_ascending,created_descending,modified_ascending,modified_descending,title_ascending,title_descending',
                    ],
                ],
                'response' => [
                    'items' => [
                        [
                            'type' => 'folder',
                            'folder' => [
                                'id' => 'FAF2lZtloor',
                                'name' => 'My awesome holiday',
                                'created_at' => 1377396000,
                                'updated_at' => 1692928800,
                            ],
                        ],
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.items',
                    'items' => '$.items',
                    'folder' => '$.items[*].folder',
                    'id' => '$.items[*].folder.id',
                    'name' => '$.items[*].folder.name',
                    'created_at' => '$.items[*].folder.created_at',
                    'updated_at' => '$.items[*].folder.updated_at',
                ],
                'request_class_name' => GetFolderItemsRequest::class,
                'function_name' => 'getFolderItems',
            ],
            [
                'name' => 'move_folder_item',
                'input_parameters' => [
                    'to_folder_id' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'The ID of the to folder',
                        'example' => 'FAF2lZtloor',
                        'validation' => 'required|string|min:1|max:50',
                    ],
                    'item_id' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'The ID of the item to move',
                        'example' => 'FAF2lZtloor',
                        'validation' => 'required|string|min:1|max:50',
                    ],
                ],
                'response' => [
                    'status' => 'success',
                ],
                'response_path' => [
                    'final_result' => '$.status',
                ],
                'request_class_name' => MoveFolderItemRequest::class,
                'function_name' => 'moveFolderItem',
            ]
        ];

        $keptServiceTypeIds = $this->processServiceTypes($serviceProvider, $serviceTypes, 'Canva');

        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);

        $this->command->info("Cleanup completed:");
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info("- Kept " . count($keptServiceTypeIds) . " service types for Canva");
    }
}
