<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceProviderListRequest;
use App\Services\ServiceProviderService;
use Illuminate\Support\Facades\Log;


class ServiceProviderController extends Controller
{
    protected $serviceProviderService;

    public function __construct(ServiceProviderService $serviceProviderService)
    {
        $this->serviceProviderService = $serviceProviderService;
    }

    /**
     * Service Provider operations (create, update, list, delete)
     *
     * @OA\Post(
     *     path="/api/extanal",
     *     summary="External API operations",
     *     description="Perform operations on external API based on service_provider_id and operation type",
     *     operationId="externalApiOperations",
     *     tags={"External API"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *             required={"service_provider_id", "interface"},
     *
     *             @OA\Property(
     *                 property="service_provider_id",
     *                 type="string",
     *                 description="ID of the service provider",
     *                 example="SP12345"
     *             ),
     *             @OA\Property(
     *                 property="interface",
     *                 type="string",
     *                 description="Interface parameters with operation type as first element",
     *                 example="list"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="string",
     *                 example="list"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="error"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Validation failed"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="error"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="An error occurred while processing your request"
     *             )
     *         )
     *     )
     * )
     *
     * @param  \App\Http\Requests\ServiceProviderListRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function list(ServiceProviderListRequest $request)
    {
        try {
            // Get validated data (validation is already handled by the FormRequest)
            $validatedData = $request->validated();
            $serviceProviderId = $validatedData['service_provider_id'];
            $interface = $validatedData['interface'];
            $provider = $this->serviceProviderService->listProviders($serviceProviderId, $interface);

            if (!$provider) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Google spreadsheet service provider not found',
                ], 404);
            }

            // Return the provider data
            return response()->json([
                'status' => 'success',
                'data' => $provider,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Master Cluster List Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
