<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use OpenApi\Attributes as OA;

#[OA\Info(
    title: "Services API",
    version: "1.0.0",
    description: <<<DESC
API for interacting with service providers and executing service-related functions.

### Features
- Retrieve dynamic schema based on service type
- Execute service provider operations
- Designed for modular and dynamic service integration

### Tags
- **Services**: Includes all endpoints for handling service provider schemas and executions.

DESC,
    contact: new OA\Contact(
        name: "Orderific Dev Team",
        email: "info@orderific.com",
    )
)]
abstract class Controller
{
    protected function successfulResponse($data = null, ?string $message = null, string $version = '2.0.0', bool $cached = false): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message ?? __('Success'),
            'data' => $data ?? [],
            'cached' => $cached,
            'execution_time' => (microtime(true) - START_EXECUTION_TIME) * 1000 . ' ms',
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ])->withHeaders([
            'X-Api-Version' => $version,
        ]);
    }

    protected function errorResponse(string $message, array $errors = [], string $version = '2.0.0', int $status = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return response()->json(['success' => false, 'message' => $message, 'errors' => $errors, 'timestamp' => now()->format('Y-m-d H:i:s')], $status)->withHeaders([
            'X-Api-Version' => $version,
        ]);
    }

    protected function failedValidationResponse(string $message, array $errors, string $version = '2.0.0', bool $cached = false): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => now()->format('Y-m-d, H:i:s'),
            'execution_time' => (microtime(true) - START_EXECUTION_TIME) * 1000 . ' ms',
            'cached' => $cached,
        ], Response::HTTP_UNPROCESSABLE_ENTITY)->withHeaders([
            'X-Api-Version' => $version,
        ]);
    }

    public function jsonResponseWithPagination(LengthAwarePaginator|AnonymousResourceCollection $lengthAwarePaginator, ?string $message = null, string $version = '2.0.0', bool $cached = false): JsonResponse
    {
        $template = [
            'success' => true,
            'message' => $message ?? __('Success'),
            'data' => $lengthAwarePaginator->getCollection(),
            'cached' => $cached,
            'execution_time' => (microtime(true) - START_EXECUTION_TIME) * 1000 . ' ms',
            'current_page' => $lengthAwarePaginator->currentPage(),
            'last_page' => $lengthAwarePaginator->lastPage(),
            'items_per_page' => $lengthAwarePaginator->perPage(),
            'page_items' => $lengthAwarePaginator->count(),
            'total' => $lengthAwarePaginator->total(),
            'timestamp' => now()->format('Y-m-d, H:i:s'),
        ];

        return \response()->json($template)->withHeaders([
            'X-Api-Version' => $version,
        ]);
    }

    public function jsonResponseWithDetailedPagination(LengthAwarePaginator $lengthAwarePaginator, ?string $message = null, string $version = '2.0.0', bool $cached = false): JsonResponse
    {
        $template = [
            'success' => true,
            'message' => $message ?? __('Success'),
            'current_page' => $lengthAwarePaginator->currentPage(),
            'total' => $lengthAwarePaginator->total(),
            'data' => $lengthAwarePaginator->getCollection(),
            'items_per_page' => $lengthAwarePaginator->perPage(),
            'next_page_url' => $lengthAwarePaginator->nextPageUrl(),
            'prev_page_url' => $lengthAwarePaginator->previousPageUrl(),
            'links' => $lengthAwarePaginator->links(),
            'last_page' => $lengthAwarePaginator->lastPage(),
            'timestamp' => now()->format('Y-m-d, H:i:s'),
            'execution_time' => (microtime(true) - START_EXECUTION_TIME) * 1000 . ' ms',
            'cached' => $cached,
        ];

        return \response()->json($template)->withHeaders([
            'X-Api-Version' => $version,
        ]);
    }
}
