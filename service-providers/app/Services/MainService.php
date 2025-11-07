<?php

namespace App\Services;

use App\Models\ServiceProvider;
use App\Models\ServiceProviderModel;
use App\Models\ServiceType;
use App\Traits\MongoObjectIdTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MainService
{
    use MongoObjectIdTrait;
    
    /**
     * Execute the main function based on service provider and service type
     *
     * @param  string  $serviceProviderId
     * @param  string  $serviceTypeId
     * @param  Request  $request
     * @return mixed
     */
    public function executeMainFunction(string $serviceProviderId, string $serviceTypeId, Request $request): mixed
    {
        $serviceProvider = ServiceProvider::query()->find($this->toObjectId($serviceProviderId));

        if (is_null($serviceProvider)) {
            return $this->errorResponse('Service provider not found');
        }

        $serviceType = ServiceType::query()->where('service_provider_id', $this->toObjectId($serviceProviderId))
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
                ->where('service_provider_id', $this->toObjectId($serviceProviderId))
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
            'request' => $formRequest ?? $request,
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

    public function getRequestBody(string $serviceProviderId, string $serviceTypeId)
    {
        $serviceProvider = ServiceProvider::query()->find($this->toObjectId($serviceProviderId));

        if (is_null($serviceProvider)) {
            return $this->errorResponse('Service provider not found');
        }

        $serviceType = ServiceType::query()->where('service_provider_id', $this->toObjectId($serviceProviderId))
            ->where('id', $this->toObjectId($serviceTypeId))
            ->first();

        if (is_null($serviceType)) {
            return $this->errorResponse('Service type not found');
        }

        // Extract input parameters schema
        $inputParameters = $serviceType->input_parameters ?? [];

        // Convert to actual parameters
        $parameters = $this->extractParameters($inputParameters);

        return response()->json($parameters);
    }

    /**
     * Extract parameters with default values from schema (supports nested objects and arrays)
     */
    private function extractParameters(array $schema): array
    {
        $parameters = [];

        foreach ($schema as $key => $definition) {
            $parameters[$key] = $this->getParameterValue($definition);
        }

        return $parameters;
    }

    /**
     * Get parameter value based on definition (builds complete structure)
     */
    private function getParameterValue(array $definition): mixed
    {
        // Check for explicit default first
        if (array_key_exists('default', $definition)) {
            return $definition['default'];
        }

        // Handle nested objects - build complete structure
        if (isset($definition['type']) && $definition['type'] === 'object' && isset($definition['properties'])) {
            return $this->buildObjectStructure($definition['properties']);
        }

        // Handle arrays - build structure with one sample item
        if (isset($definition['type']) && $definition['type'] === 'array' && isset($definition['items'])) {
            return $this->buildArrayStructure($definition['items']);
        }

        // Handle examples
        if (isset($definition['example'])) {
            return $definition['example'];
        }

        // Return null for primitive types without defaults
        return null;
    }

    /**
     * Build complete object structure with all properties
     */
    private function buildObjectStructure(array $properties): array
    {
        $object = [];

        foreach ($properties as $key => $property) {
            $object[$key] = $this->getParameterValue($property);
        }

        return $object;
    }

    /**
     * Build array structure with sample nested items
     */
    private function buildArrayStructure(array $items): array
    {
        // If array items are objects, create one sample object to show structure
        if (isset($items['type']) && $items['type'] === 'object' && isset($items['properties'])) {
            $sampleObject = $this->buildObjectStructure($items['properties']);

            return [$sampleObject];
        }

        // If array items are arrays, create nested structure
        if (isset($items['type']) && $items['type'] === 'array' && isset($items['items'])) {
            $sampleArray = $this->buildArrayStructure($items['items']);

            return [$sampleArray];
        }

        // For primitive array items, return array with one null item
        return [null];
    }
}
