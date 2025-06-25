<?php

namespace App\Services;

use App\Models\ServiceProvider;
use App\Models\ServiceType;
use App\Models\ServiceProviderType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        $serviceType = ServiceType::query()->find($serviceTypeId);

        if (is_null($serviceProvider) || is_null($serviceType)) {
            return $this->response('Service provider or service type not found', null, 404);
        }

        $serviceProviderType = ServiceProviderType::where('service_provider_id', $serviceProviderId)
            ->where('service_type_id', $serviceTypeId)
            ->first();

        if (is_null($serviceProviderType)) {
            return $this->response('Service provider type configuration not found', null, 404);
        }

        if (is_null($serviceProvider->controller_name) || is_null($serviceType->function_name)) {
            return $this->response('Service provider or service type configuration is incomplete', null, 404);
        }

        if (!method_exists($serviceProvider->controller_name, $serviceType->function_name)) {
            return $this->response('Function not found in controller', null, 404);
        }

        $controller = app($serviceProvider->controller_name);

        $pathParameters = $this->extractPathParameters($request);
        
        $pathValidationResult = $this->validatePathParameters($pathParameters, $serviceProviderType);
        if ($pathValidationResult !== true) {
            return $this->response('Path parameter validation failed', $pathValidationResult, 422);
        }
        
        $formRequest = null;
        if (!is_null($serviceType->request_class_name)) {
            $formRequest = app($serviceType->request_class_name);
            $formRequest->replace($request->all());
            $formRequest->files = $request->files;
            $formRequest->headers = $request->headers;
            $formRequest->validateResolved();
        }
        
        $methodParameters = $this->prepareMethodParameters($pathParameters, $formRequest ?? $request);
        
        return app()->call([$controller, $serviceType->function_name], $methodParameters);
    }

    /**
     * Extract path parameters from the request
     *
     * @param Request $request
     * @return array
     */
    private function extractPathParameters(Request $request): array
    {
        $pathParameters = [];
        
        $routeParameters = $request->route()->parameters();
        
        // Remove the service provider and service type IDs as they're handled separately
        unset($routeParameters['service_provider_id'], $routeParameters['service_type_id']);
        
        // Check if we have a catch-all path parameter
        if (isset($routeParameters['path']) && !empty($routeParameters['path'])) {
            $pathSegments = explode('/', trim($routeParameters['path'], '/'));
            
            // Get the service provider type to know what path parameters are expected
            $serviceProviderId = $request->route('service_provider_id');
            $serviceTypeId = $request->route('service_type_id');
            
            $serviceProviderType = ServiceProviderType::where('service_provider_id', $serviceProviderId)
                ->where('service_type_id', $serviceTypeId)
                ->first();
            
            if ($serviceProviderType && !empty($serviceProviderType->path_parameters)) {
                $expectedPathParams = array_keys($serviceProviderType->path_parameters);
                
                // Map path segments to expected parameter names in order
                foreach ($expectedPathParams as $index => $paramName) {
                    if (isset($pathSegments[$index])) {
                        $pathParameters[$paramName] = $pathSegments[$index];
                    } elseif (isset($serviceProviderType->path_parameters[$paramName]['required']) && 
                             !$serviceProviderType->path_parameters[$paramName]['required']) {
                        // Handle nullable parameters
                        $pathParameters[$paramName] = null;
                    }
                }
            } else {
                // Fallback: use generic names if no configuration found
                foreach ($pathSegments as $index => $segment) {
                    $pathParameters["param_{$index}"] = $segment;
                }
            }
        } else {
            // Handle regular route parameters (if not using catch-all)
            if (!empty($routeParameters)) {
                $pathParameters = $routeParameters;
            }
        }
        
        return $pathParameters;
    }

    /**
     * Validate path parameters based on service provider type configuration
     *
     * @param array $pathParameters
     * @param ServiceProviderType $serviceProviderType
     * @return bool|array
     */
    private function validatePathParameters(array $pathParameters, ServiceProviderType $serviceProviderType): bool|array
    {
        if (empty($serviceProviderType->path_parameters)) {
            return true;
        }

        $validationRules = [];
        $validationMessages = [];

        foreach ($serviceProviderType->path_parameters as $paramName => $paramConfig) {
            if (isset($paramConfig['validation'])) {
                $validationRules[$paramName] = $paramConfig['validation'];
            }
            
            if (isset($paramConfig['description'])) {
                $validationMessages[$paramName . '.required'] = "{$paramConfig['description']} is required.";
                $validationMessages[$paramName . '.string'] = "{$paramConfig['description']} must be a string.";
                $validationMessages[$paramName . '.integer'] = "{$paramConfig['description']} must be an integer.";
                $validationMessages[$paramName . '.uuid'] = "{$paramConfig['description']} must be a valid UUID.";
                $validationMessages[$paramName . '.regex'] = "{$paramConfig['description']} format is invalid.";
            }
        }

        if (empty($validationRules)) {
            return true;
        }

        $validator = Validator::make($pathParameters, $validationRules, $validationMessages);

        if ($validator->fails()) {
            return $validator->errors()->toArray();
        }

        return true;
    }

    /**
     * Prepare method parameters for the target controller method.
     *
     * @param array $pathParameters
     * @param mixed $request
     * @return array
     */
    private function prepareMethodParameters(array $pathParameters, mixed $request): array
    {
        $methodParameters = [];
        
        foreach ($pathParameters as $key => $value) {
            $methodParameters[$key] = $value;
        }
        
        $methodParameters['request'] = $request;
        
        return $methodParameters;
    }

    private function response(string $message, mixed $data, int $status = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $status);
    }
} 