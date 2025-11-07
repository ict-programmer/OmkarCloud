<?php

namespace App\Http\Controllers;

use App\Data\Request\Shotstack\CheckRenderStatusData;
use App\Data\Request\Shotstack\CreateAssetData;
use App\Data\Request\Shotstack\GetVideoMetadataData;
use App\Http\Requests\Shotstack\CheckRenderStatusRequest;
use App\Http\Requests\Shotstack\CreateAssetRequest;
use App\Http\Requests\Shotstack\GetVideoMetadataRequest;
use App\Services\ShotstackService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShotstackAPIController extends BaseController
{
    public function __construct(protected ShotstackService $service) {}

    public function createAsset(CreateAssetRequest $request): JsonResponse {
        $data = CreateAssetData::from($request->validated());

        $result = $this->service->createAsset($data);

        return $this->logAndResponse($result);
    }

    public function checkRenderStatus(CheckRenderStatusRequest $request): JsonResponse {
        $data = CheckRenderStatusData::from($request->validated());

        $result = $this->service->checkRenderStatus($data);

        return $this->logAndResponse($result);
    }

    public function getVideoMetadata(GetVideoMetadataRequest $request): JsonResponse {
        $data = GetVideoMetadataData::from($request->validated());

        $result = $this->service->getVideoMetadata($data);

        return $this->logAndResponse($result);
    }
}
