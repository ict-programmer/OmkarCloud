<?php

namespace App\Services;

use App\Models\ServiceProvider;
use App\Models\ServiceProviderModel;
use App\Models\ServiceType;
use App\Models\ServiceProviderType;
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
            return $this->response('Service provider not found', null, 404);
        }

        $serviceType = ServiceType::query()->find($serviceTypeId);

        if (is_null($serviceType)) {
            return $this->response('Service type not found', null, 404);
        }

        $serviceProviderType = ServiceProviderType::where('service_provider_id', $serviceProviderId)
            ->where('service_type_id', $serviceTypeId)
            ->first();

        if (is_null($serviceProviderType)) {
            return $this->response('Service provider type configuration not found', null, 404);
        }

        if (is_null($serviceProvider->controller_name) || is_null($serviceProviderType->function_name)) {
            return $this->response('Service provider or service type configuration is incomplete', null, 404);
        }

        if (!method_exists($serviceProvider->controller_name, $serviceProviderType->function_name)) {
            return $this->response('Function not found in controller', null, 404);
        }

        $controller = app($serviceProvider->controller_name);

        $model = $request->input('model');

        if (!is_null($model)) {
            $modelExists = ServiceProviderModel::query()->where('service_provider_id', $serviceProviderId)->where('name', $model)->exists();

            if (!$modelExists) {
                return $this->response('Model not configured for this service provider', null, 404);
            }
        }

        $formRequest = null;
        if (!is_null($serviceProviderType->request_class_name)) {
            $formRequest = app($serviceProviderType->request_class_name);
            $formRequest->replace($request->all());
            $formRequest->files = $request->files;
            $formRequest->headers = $request->headers;
            $formRequest->validateResolved();
        }

        return app()->call([$controller, $serviceProviderType->function_name]);
    }

    private function response(string $message, mixed $data, int $status = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}
