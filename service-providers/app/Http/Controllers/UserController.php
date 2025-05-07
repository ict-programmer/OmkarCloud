<?php

namespace App\Http\Controllers;

use App\Data\Request\User\CreateUsersData;
use App\Data\Request\User\DeleteUsersData;
use App\Data\Request\User\ListUsersData;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\DeleteUserRequest;
use App\Http\Requests\User\ListUserRequest;
use App\Http\Resources\User\CreateUserResource;
use App\Http\Resources\User\ListUserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    public function __construct(protected UserService $service) {}

    #[OA\Get(
        path: '/api/users/list',
        operationId: 'listUsers',
        description: 'List of all users',
        summary: 'List of all users',
        security: [['authentication' => []]],
        tags: ['Users']
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
                'message' => 'Retrieved Users successfully',
                'data' => [
                    [
                        'id' => '6814fe7431a59862500da562',
                        'name' => 'User name',
                        'restaurant_name' => 'Restaurant name',
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
    public function listUsers(ListUserRequest $request): JsonResponse
    {
        $data = ListUsersData::from($request->validated());

        $result = $this->service->listUsers($data);

        return $this->jsonResponseWithPagination(
            ListUserResource::collection($result),
            __('Retrieved Users successfully'),
        );
    }

    #[OA\Post(
        path: '/api/users/create',
        operationId: 'createUser',
        description: 'Create a new user',
        summary: 'Create a new user',
        security: [['authentication' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                example: [
                    'name' => 'John Doe',
                    'restaurant_name' => 'Doe’s Diner',
                    'email' => 'john@example.com',
                    'email_verified_at' => '2025-05-01 10:00:00',
                    'is_approved' => 1,
                    'password' => '$2y$10$exampleHashedPassword1234567890',
                    'two_factor_secret' => 'base64:secretkey123456',
                    'two_factor_recovery_codes' => '["recovery1","recovery2"]',
                    'two_factor_confirmed_at' => '2025-05-01 11:00:00',
                    'country_code' => '+1',
                    'mobile' => '1234567890',
                    'whatsapp' => '1234567890',
                    'telegram_group_id' => 'tg_group_001',
                    'send_daily_report' => 1,
                    'send_weekly_report' => 0,
                    'send_monthly_report' => 1,
                    'user_type' => 2,
                    'parent_id' => 1,
                    'approved' => 1,
                    'referral_code' => 'REF12345',
                    'company_name' => 'Doe Inc.',
                    'company_title' => 'CEO',
                    'orderific_client' => 'ORD001',
                    'refer_leads' => 'Referral from A',
                    'business_tenure' => '3 years',
                    'remember_token' => 'abc123xyz',
                    'profile' => 'profile.jpg',
                    'product_service' => 'POS System',
                    'zoom_info' => 'zoom_meeting_id_123',
                    'company_website' => 'https://doecompany.com',
                    'industry' => 'Technology',
                    'location' => 'New York, USA',
                    'about' => 'Entrepreneur with a focus on tech',
                    'expertskill' => 'JavaScript, PHP',
                    'expert_level' => 'Senior',
                    'copy' => 'Sample marketing copy text',
                    'graphics' => 'graphics.png',
                    'video' => 'video.mp4',
                    'audio' => 'audio.mp3',
                    'email_otp' => '456789',
                    'email_otp_expired_at' => '2025-05-01 12:00:00',
                    'forgot_otp' => '987654',
                    'forgot_otp_expired_at' => '2025-05-01 13:00:00',
                    'otp_attempt' => 2,
                    'forgot_otp_attempt' => 1,
                    'creator_api_key' => 'api_key_123',
                    'creator_secure_key' => 'secure_key_456',
                    'brand_key' => 'brand_key_789',
                    'referred_by' => 5,
                    'currency_code' => 'USD',
                    'sub_domain_brand_id' => 3,
                    'profile_cid' => 'CID123456',
                    'profile_image_name' => 'avatar.jpg',
                    'user_code' => 'USR123',
                    'is_testing' => 0,
                    'user_auth_token' => 'token_abc_123',
                    'driver_currency_id' => 2,
                    'first_name' => 'John',
                    'middle_name' => 'M',
                    'last_name' => 'Doe',
                    'login_pin' => 1234,
                    'bio' => '2025-05-01 14:00:00',
                    'last_login_at' => '2025-05-04 18:30:00',
                    'status' => 1,
                    'share_mode' => 1,
                    'delete_reason' => 'Requested account closure',
                    'default_address' => '123 Main St, NY, USA',
                ]
            )
        ),
        tags: ['Users']
    )]
    #[OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(
            example: [
                'success' => true,
                'message' => 'User created successfully',
                'data' => [
                    'id' => '6814fe7431a59862500da562',
                ],
                'timestamp' => '2024-06-26, 02:59:15',
                'execution_time' => '91.686964035034 ms',
                'cached' => false,
            ]
        )
    )]
    public function createUser(CreateUserRequest $request): JsonResponse
    {
        $data = CreateUsersData::from($request->validated());

        $result = $this->service->createUser($data);

        return $this->successfulResponse(
            CreateUserResource::make($result),
            __('Retrieved Users successfully'),
        );
    }

    #[OA\Delete(
        path: '/api/users/delete',
        operationId: 'deleteUser',
        description: 'Delete a new user',
        summary: 'Delete a new user',
        security: [['authentication' => []]],
        tags: ['Users']
    )]
    #[OA\QueryParameter(
        name: 'id',
        description: 'ID of the user',
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
                'message' => 'User deleted successfully',
                'data' => [],
                'timestamp' => '2024-06-26, 02:59:15',
                'execution_time' => '91.686964035034 ms',
                'cached' => false,
            ]
        )
    )]
    public function deleteUser(DeleteUserRequest $request): JsonResponse
    {
        $data = DeleteUsersData::from($request->validated());

        $this->service->deleteUser($data);

        return $this->successfulResponse(
            null,
            __('User deleted successfully'),
        );
    }
}
