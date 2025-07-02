<?php

namespace App\Services;

use App\Models\ServiceProvider;
use App\Models\ServiceProviderModel;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MainService
{
    /**
     * Execute the main function based on service provider and service type
     *
     * @param string $serviceProviderId
     * @param string $serviceTypeId
     * @param Request $request
     * @return mixed
     */
    public function executeMainFunction(string $serviceProviderId, string $serviceTypeId, Request $request): mixed
    {
        $serviceProvider = ServiceProvider::query()->find($serviceProviderId);
        
        if (is_null($serviceProvider)) {
            return $this->errorResponse('Service provider not found');
        }

        $serviceType = ServiceType::query()->where('service_provider_id', $serviceProviderId)
            ->where('_id', $serviceTypeId)
            ->first();

        if (is_null($serviceType)) {
            return $this->errorResponse('Service type not found');
        }

        if (is_null($serviceProvider->controller_name) || is_null($serviceType->function_name)) {
            return $this->errorResponse('Service provider or service type configuration is incomplete');
        }

        if (!method_exists($serviceProvider->controller_name, $serviceType->function_name)) {
            return $this->errorResponse('Function not found in controller');
        }

        $controller = app($serviceProvider->controller_name);

        $model = $request->input('model');

        if (!is_null($model)) {
            $modelExists = ServiceProviderModel::query()
                ->where('service_provider_id', $serviceProviderId)
                ->where('name', $model)
                ->exists();

            if (!$modelExists) {
                return $this->errorResponse('Model not configured for this service provider');
            }
        }

        $formRequest = null;
        if (!is_null($serviceType->request_class_name)) {
            $formRequest = app($serviceType->request_class_name);
            $formRequest->replace($request->all());
            $formRequest->files = $request->files;
            $formRequest->headers = $request->headers;
            $formRequest->validateResolved();
        }

        $call = app()->call([$controller, $serviceType->function_name], [
            'request' => $formRequest ?? $request
        ]);
        return $call->original ?? $call;
    }

    private function errorResponse(string $message): JsonResponse
    {
        return response()->json([
            'error' => true,
            'message' => $message,
        ], 404);
    }
}
