<?php

namespace App\Http\Controllers;

use App\Data\Request\Asset\CreateAssetsData;
use App\Data\Request\Asset\DeleteAssetsData;
use App\Data\Request\Asset\ListAssetsData;
use App\Http\Requests\Assets\CreateAssetsRequest;
use App\Http\Requests\Assets\DeleteAssetsRequest;
use App\Http\Requests\Assets\ListAssetsRequest;
use App\Http\Resources\Assets\CreateAssetsResource;
use App\Http\Resources\Assets\ListAssetsResource;
use App\Services\AssetService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class AssetController extends Controller
{
    public function __construct(protected AssetService $service) {}

    #[OA\Get(
        path: '/api/assets/list',
        operationId: 'listAssets',
        description: 'List of all assets',
        summary: 'List of all assets',
        security: [['authentication' => []]],
        tags: ['Assets']
    )]
    #[OA\QueryParameter(
        name: 'page_size',
        description: 'Page size',
        required: false,
        schema: new OA\Schema(
            type: 'integer',
            format: 'integer',
            example: 20
        )
    )]
    #[OA\QueryParameter(
        name: 'page_limit',
        description: 'Page number',
        required: false,
        schema: new OA\Schema(
            type: 'integer',
            format: 'integer',
            example: 1
        )
    )]
    #[OA\QueryParameter(
        name: 'sort_by',
        description: 'Sort by',
        required: false,
        schema: new OA\Schema(
            type: 'string',
            example: 'created_at'
        )
    )]
    #[OA\QueryParameter(
        name: 'sort_order',
        description: 'Sort',
        required: false,
        schema: new OA\Schema(
            type: 'string',
            enum: ['asc', 'desc'],
        )
    )]
    #[OA\QueryParameter(
        name: 'search',
        description: 'Search',
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(
            example: [
                'success' => true,
                'message' => 'Retrieved Assets successfully',
                'data' => [
                    [
                        'id' => 'id',
                        'username' => 'username',
                        'channel_id' => 'channel_id',
                        'password' => 'password',
                        'api_key' => 'api_key',
                        'user_id' => 'user_id',
                        'api_key_status' => 'api_key_status',
                        'asset_status' => 'asset_status',
                        'last_email_sent' => 'last_email_sent',
                        'activity_id' => 'activity_id',
                        'start_limit' => 'start_limit',
                        'end_limit' => 'end_limit',
                        'current_limit' => 'current_limit',
                        'current_size' => 'current_size',
                        'increment_1' => 'increment_1',
                        'increment_2' => 'increment_2',
                        'increment_1_start_limit' => 'increment_1_start_limit',
                        'increment_2_start_limit' => 'increment_2_start_limit',
                        'efficiency' => 'efficiency',
                        '24H_efficiency' => 'efficiency_24h',
                        '1W_efficiency' => 'efficiency_1w',
                        'reset_date' => 'reset_date',
                        'process_record' => 'process_record',
                        'brand_id' => 'brand_id',
                        'duplicate' => 'duplicate',
                        'primary_id' => 'primary_id',
                        'notes' => 'notes',
                        'allowed_campaigns' => 'allowed_campaigns',
                        'available_campaigns' => 'available_campaigns',
                        'next_campaigns_check' => 'next_campaigns_check',
                        'twilio_sid' => 'twilio_sid',
                        'twilio_number' => 'twilio_number',
                        'twilio_auth_token' => 'twilio_auth_token',
                        'mailchimp_action' => 'mailchimp_action',
                        'gsuite_password' => 'gsuite_password',
                        'twitter_api_secret' => 'twitter_api_secret',
                        'twitter_client_secret' => 'twitter_client_secret',
                        'twitter_client_id' => 'twitter_client_id',
                        'linkedin_social_Id' => 'linkedin_social_Id',
                        'facebook_page_id' => 'facebook_page_id',
                        'instagram_business_id' => 'instagram_business_id',
                        'buka_api_secret' => 'buka_api_secret',
                        'buka_app_id' => 'buka_app_id',
                        'wp_database' => 'wp_database',
                        'wp_host_url' => 'wp_host_url',
                        'active_frequency' => 'active_frequency',
                        'inactive_frequency' => 'inactive_frequency',
                        'inactive_definition' => 'inactive_definition',
                        'domain_authority' => 'domain_authority',
                        'spam_score' => 'spam_score',
                        'guest_post1' => 'guest_post1',
                        'guest_post2' => 'guest_post2',
                        'guest_post3' => 'guest_post3',
                        'guest_post4' => 'guest_post4',
                        'guest_post5' => 'guest_post5',
                        'semrush_score' => 'semrush_score',
                        'grammarly_score' => 'grammarly_score',
                        'zerogpt_score' => 'zerogpt_score',
                        'organic_traffics' => 'organic_traffics',
                        'authority_score' => 'authority_score',
                        'authority_status' => 'authority_status',
                        'top_anchor' => 'top_anchor',
                        'natural_profile' => 'natural_profile',
                        'name' => 'name',
                        'status' => 'status',
                    ],
                ],
                'current_page' => 1,
                'last_page' => 1,
                'items_per_page' => 20,
                'page_items' => 7,
                'total' => 7,
                'timestamp' => '2024-06-26, 02:59:15',
                'execution_time' => '91.686964035034 ms',
                'cached' => false,
            ]
        )
    )]
    public function listAssets(ListAssetsRequest $request): JsonResponse
    {
        $data = ListAssetsData::from($request->validated());

        $result = $this->service->listAssets($data);

        return $this->jsonResponseWithPagination(
            ListAssetsResource::collection($result),
            __('Retrieved Assets successfully'),
        );
    }

    #[OA\Post(
        path: '/api/assets/create',
        operationId: 'createAsset',
        description: 'Create a new asset',
        summary: 'Create a new asset',
        security: [['authentication' => []]],
        tags: ['Assets']
    )]
    #[OA\QueryParameter(
        name: 'name',
        description: 'Name of the asset',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            format: 'string',
            example: 'Asset name'
        )
    )]
    #[OA\QueryParameter(
        name: 'status',
        description: 'Status of the asset',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            enum: [1,0]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(
            example: [
                'success' => true,
                'message' => 'Asset created successfully',
                'data' => [
                    'id' => '6814fe7431a59862500da562',
                ],
                'timestamp' => '2024-06-26, 02:59:15',
                'execution_time' => '91.686964035034 ms',
                'cached' => false,
            ]
        )
    )]
    public function createAsset(CreateAssetsRequest $request): JsonResponse
    {
        $data = CreateAssetsData::from($request->validated());

        $result = $this->service->createAsset($data);

        return $this->successfulResponse(
            CreateAssetsResource::make($result),
            __('Retrieved Assets successfully'),
        );
    }

    #[OA\Delete(
        path: '/api/assets/delete',
        operationId: 'Delete asset',
        description: 'Delete a new asset',
        summary: 'Delete a new asset',
        security: [['authentication' => []]],
        tags: ['Assets']
    )]
    #[OA\QueryParameter(
        name: 'id',
        description: 'ID of the asset',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            example: '6814fe7431a59862500da562'
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(
            example: [
                'success' => true,
                'message' => 'Asset deleted successfully',
                'data' => [],
                'timestamp' => '2024-06-26, 02:59:15',
                'execution_time' => '91.686964035034 ms',
                'cached' => false,
            ]
        )
    )]
    public function deleteAsset(DeleteAssetsRequest $request): JsonResponse
    {
        $data = DeleteAssetsData::from($request->validated());

        $this->service->deleteAsset($data);

        return $this->successfulResponse(
            null,
            __('Asset deleted successfully'),
        );
    }
}
