<?php

namespace App\Http\Controllers;

use App\Data\Request\Placid\ImageGenerationData;
use App\Data\Request\Placid\RetrievePdfData;
use App\Data\Request\Placid\RetrieveTemplateData;
use App\Data\Request\Placid\RetrieveVideoData;
use App\Data\Request\Placid\VideoGenerationData;
use App\Http\Requests\Placid\ImageGenerationRequest;
use App\Http\Requests\Placid\RetrievePdfRequest;
use App\Http\Requests\Placid\RetrieveTemplateRequest;
use App\Http\Requests\Placid\RetrieveVideoRequest;
use App\Http\Requests\Placid\VideoGenerationRequest;
use App\Services\PlacidService;
use Illuminate\Http\JsonResponse;

class PlacidController extends BaseController
{
    public function __construct(public PlacidService $service) {}

    public function imageGeneration(ImageGenerationRequest $request): JsonResponse
    {
        $data = ImageGenerationData::from($request->validated());

        $result = $this->service->imageGeneration($data);

        return $this->logAndResponse([
            'message' => 'Image generation successful.',
            'data' => $result,
        ]);
    }

    public function pdfGeneration(ImageGenerationRequest $request): JsonResponse
    {
        $data = ImageGenerationData::from($request->validated());

        $result = $this->service->pdfGeneration($data);

        return $this->logAndResponse([
            'message' => 'PDF generation successful.',
            'data' => $result,
        ]);
    }

    public function retrieveTemplate(RetrieveTemplateRequest $request): JsonResponse
    {
        $data = RetrieveTemplateData::from($request->validated());

        $result = $this->service->retrieveTemplate($data);

        return $this->logAndResponse([
            'message' => 'Template retrieved successfully.',
            'data' => $result,
        ]);
    }

    public function retrievePdf(RetrievePdfRequest $request): JsonResponse
    {
        $data = RetrievePdfData::from($request->validated());

        $result = $this->service->retrievePdf($data);

        return $this->logAndResponse([
            'message' => 'Pdf retrieved successfully.',
            'data' => $result,
        ]);
    }

    public function videoGeneration(VideoGenerationRequest $request): JsonResponse
    {
        $data = VideoGenerationData::from($request->validated());

        $result = $this->service->videoGeneration($data);

        return $this->logAndResponse([
            'message' => 'Video generation successful.',
            'data' => $result,
        ]);
    }

    public function retrieveVideo(RetrieveVideoRequest $request): JsonResponse
    {
        $data = RetrieveVideoData::from($request->validated());

        $result = $this->service->retrieveVideo($data);

        return $this->logAndResponse([
            'message' => 'Video retrieved successfully.',
            'data' => $result,
        ]);
    }
}
