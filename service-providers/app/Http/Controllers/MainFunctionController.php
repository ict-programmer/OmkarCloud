<?php

namespace App\Http\Controllers;

use App\Services\MainService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class MainFunctionController extends BaseController
{
    public function __construct(protected MainService $service) {}

    #[OA\Post(
        path: "/api/services/{service_provider_id}/{service_type_id}",
        operationId: "executeServiceProviderFunction",
        description: "Execute a specific function for a given service provider and service type",
        summary: "Execute service provider function",
        requestBody: new OA\RequestBody(
            description: "Request payload (varies by service type)",
            required: true,
            content: new OA\JsonContent(
                type: "object",
                example: [
                    "key" => "value",
                ]
            )
        ),
        tags: ["Services"],
        parameters: [
            new OA\Parameter(
                name: "service_provider_id",
                description: "ID of the service provider",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string")
            ),
            new OA\Parameter(
                name: "service_type_id",
                description: "ID of the service type",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful response",
                content: new OA\JsonContent()
            ),
        ]
    )]
    public function __invoke(string $serviceProviderId, string $serviceTypeId, Request $request): JsonResponse
    {
        $result = $this->service->executeMainFunction($serviceProviderId, $serviceTypeId, $request);

        return $this->logAndResponse($result->original ?? $result);
    }
}
