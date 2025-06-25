<?php

namespace App\Http\Controllers;

use App\Models\ServiceProvider;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class MainFunctionController extends Controller
{
    public function __invoke($serviceProviderId, $serviceTypeId, Request $request)
    {
        $serviceProvider = ServiceProvider::query()->find($serviceProviderId);
        $serviceType = ServiceType::query()->find($serviceTypeId);

        if (!$serviceProvider || !$serviceType)
            return response()->json(['error' => 'Service provider or service type not found'], 404);

        if (is_null($serviceProvider->controller_name) || is_null($serviceType->function_name))
            return response()->json(['error' => 'Service provider or service type configuration is incomplete'], 404);

        if (!method_exists($serviceProvider->controller_name, $serviceType->function_name))
            return response()->json(['error' => 'Function not found in controller'], 404);

        $controller = app($serviceProvider->controller_name);

        if (! is_null($serviceType->request_class_name)){

            $formRequest = app($serviceType->request_class_name);

            $formRequest->replace($request->all());
            $formRequest->files = $request->files;
            $formRequest->headers = $request->headers;

            $formRequest->validateResolved();
        }
        
        return app()->call([$controller, $serviceType->function_name], [
            'request' => $formRequest ?? $request
        ]);
    }
}
