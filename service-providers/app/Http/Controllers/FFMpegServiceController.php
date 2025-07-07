<?php

namespace App\Http\Controllers;

use App\Data\Request\FFMpeg\AudioProcessingData;
use App\Data\Request\FFMpeg\ImageProcessingData;
use App\Data\Request\FFMpeg\VideoProcessingData;
use App\Data\Request\FFMpeg\VideoTrimmingData;
use App\Http\Requests\FFMpeg\AudioProcessingRequest;
use App\Http\Requests\FFMpeg\ImageProcessingRequest;
use App\Http\Requests\FFMpeg\VideoProcessingRequest;
use App\Http\Requests\FFMpeg\VideoTrimmingRequest;
use App\Services\FFMpegService;
use Illuminate\Http\JsonResponse;

class FFMpegServiceController extends BaseController
{
    public function __construct(public FFMpegService $service) {}

    public function videoProcessing(VideoProcessingRequest $request): JsonResponse
    {
        $data = VideoProcessingData::from($request->validated());

        $path = $this->service->processVideo($data);

        return $this->logAndResponse([
            'message' => 'Video processed successfully',
            'output_file_link' => $path,
        ]);
    }

    public function audioProcessing(AudioProcessingRequest $request): JsonResponse
    {
        $data = AudioProcessingData::from($request->validated());

        $path = $this->service->processAudio($data);

        return $this->logAndResponse([
            'message' => 'Audio processed successfully',
            'output_file_link' => $path,
        ]);
    }

    public function imageProcessing(ImageProcessingRequest $request): JsonResponse
    {
        $data = ImageProcessingData::from($request->validated());

        $path = $this->service->processImage($data);

        return $this->logAndResponse([
            'message' => 'Image processed successfully',
            'output_file_link' => $path,
        ]);
    }

    public function videoTrimming(VideoTrimmingRequest $request): JsonResponse
    {
        $data = VideoTrimmingData::from($request->validated());

        $path = $this->service->trimVideo($data);

        return $this->logAndResponse([
            'message' => 'Video trimmed successfully',
            'output_file_link' => $path,
        ]);
    }
}
