<?php

namespace App\Http\Controllers;

use App\Data\Request\Midjourney\ImageGenerationData;
use App\Data\Request\Midjourney\ImageVariationData;
use App\Data\Request\Midjourney\GetTaskData;
use App\Http\Requests\Midjourney\ImageGenerationRequest;
use App\Http\Requests\Midjourney\ImageVariationRequest;
use App\Http\Requests\Midjourney\GetTaskRequest;
use App\Services\MidjourneyService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class MidjourneyController extends BaseController
{
    public function __construct(protected MidjourneyService $service) {}

    public function imageGeneration(ImageGenerationRequest $request): JsonResponse
    {
        $data = ImageGenerationData::from($request->validated());

        $result = $this->service->imageGeneration($data);

        return $this->logAndResponse([
            'message' => 'Image generation successful.',
            'data' => $result,
        ]);
    }

    public function imageVariation(ImageVariationRequest $request): JsonResponse
    {
        $data = ImageVariationData::from($request->validated());

        $result = $this->service->imageVariation($data);

        return $this->logAndResponse([
            'message' => 'Image variation successful.',
            'data' => $result,
        ]);
    }

    public function getTask(GetTaskRequest $request): JsonResponse
    {
        $data = GetTaskData::from($request->validated());

        $result = $this->service->getTask($data);

        return $this->logAndResponse([
            'message' => 'Task status retrieved successfully.',
            'data' => $result,
        ]);
    }
} 