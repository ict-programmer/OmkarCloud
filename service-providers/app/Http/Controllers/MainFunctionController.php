<?php

namespace App\Http\Controllers;

use App\Services\MainService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MainFunctionController extends BaseController
{
    public function __construct(protected MainService $service) {}

    public function __invoke(string $serviceProviderId, string $serviceTypeId, Request $request): JsonResponse
    {
        $result = $this->service->executeMainFunction($serviceProviderId, $serviceTypeId, $request);
        return $this->logAndResponse($result);
    }
}
