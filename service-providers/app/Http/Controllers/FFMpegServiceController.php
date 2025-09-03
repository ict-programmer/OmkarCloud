<?php

namespace App\Http\Controllers;

use App\Data\Request\FFMpeg\AudioFadesData;
use App\Data\Request\FFMpeg\AudioOverlayData;
use App\Data\Request\FFMpeg\AudioProcessingData;
use App\Data\Request\FFMpeg\AudioResampleData;
use App\Data\Request\FFMpeg\AudioVolumeData;
use App\Data\Request\FFMpeg\BatchProcessData;
use App\Data\Request\FFMpeg\BitrateControlData;
use App\Data\Request\FFMpeg\ConcatenateData;
use App\Data\Request\FFMpeg\FileInspectionData;
use App\Data\Request\FFMpeg\FrameExtractionData;
use App\Data\Request\FFMpeg\ImageProcessingData;
use App\Data\Request\FFMpeg\LoudnessNormalizationData;
use App\Data\Request\FFMpeg\ScaleData;
use App\Data\Request\FFMpeg\StreamCopyData;
use App\Data\Request\FFMpeg\ThumbnailData;
use App\Data\Request\FFMpeg\TranscodingData;
use App\Data\Request\FFMpeg\VideoEncodeData;
use App\Data\Request\FFMpeg\VideoProcessingData;
use App\Data\Request\FFMpeg\VideoTrimmingData;
use App\Http\Requests\FFMpeg\AudioFadesRequest;
use App\Http\Requests\FFMpeg\AudioOverlayRequest;
use App\Http\Requests\FFMpeg\AudioProcessingRequest;
use App\Http\Requests\FFMpeg\AudioResampleRequest;
use App\Http\Requests\FFMpeg\AudioVolumeRequest;
use App\Http\Requests\FFMpeg\BatchProcessRequest;
use App\Http\Requests\FFMpeg\BitrateControlRequest;
use App\Http\Requests\FFMpeg\ConcatenateRequest;
use App\Http\Requests\FFMpeg\FileInspectionRequest;
use App\Http\Requests\FFMpeg\FrameExtractionRequest;
use App\Http\Requests\FFMpeg\ImageProcessingRequest;
use App\Http\Requests\FFMpeg\LoudnessNormalizationRequest;
use App\Http\Requests\FFMpeg\ScaleRequest;
use App\Http\Requests\FFMpeg\StreamCopyRequest;
use App\Http\Requests\FFMpeg\ThumbnailRequest;
use App\Http\Requests\FFMpeg\TranscodingRequest;
use App\Http\Requests\FFMpeg\VideoEncodeRequest;
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

    public function loudnessNormalization(LoudnessNormalizationRequest $request): JsonResponse
    {
        $data = LoudnessNormalizationData::from($request->validated());

        $path = $this->service->normalizeLoudness($data);

        return $this->logAndResponse([
            'message' => 'Loudness normalized successfully',
            'output_file_link' => $path,
        ]);
    }

    public function transcoding(TranscodingRequest $request): JsonResponse
    {
        $data = TranscodingData::from($request->validated());

        $path = $this->service->transcodeMedia($data);

        return $this->logAndResponse([
            'message' => 'Media transcoded successfully',
            'output_file_link' => $path,
        ]);
    }

    public function audioOverlay(AudioOverlayRequest $request): JsonResponse
    {
        $data = AudioOverlayData::from($request->validated());

        $path = $this->service->overlayAudio($data);

        return $this->logAndResponse([
            'message' => 'Audio overlay completed successfully',
            'output_file_link' => $path,
        ]);
    }

    public function frameExtraction(FrameExtractionRequest $request): JsonResponse
    {
        $data = FrameExtractionData::from($request->validated());

        $frameUrls = $this->service->extractFrames($data);

        return $this->logAndResponse([
            'message' => 'Frame extraction completed successfully',
            'total_frames' => count($frameUrls),
            'frame_urls' => $frameUrls,
        ]);
    }

    public function audioVolume(AudioVolumeRequest $request): JsonResponse
    {
        $data = AudioVolumeData::from($request->validated());

        $path = $this->service->adjustAudioVolume($data);

        return $this->logAndResponse([
            'message' => 'Audio volume adjusted successfully',
            'output_file_link' => $path,
        ]);
    }

    public function audioFades(AudioFadesRequest $request): JsonResponse
    {
        $data = AudioFadesData::from($request->validated());

        $path = $this->service->applyAudioFades($data);

        return $this->logAndResponse([
            'message' => 'Audio fades applied successfully',
            'output_file_link' => $path,
        ]);
    }

    public function audioResample(AudioResampleRequest $request): JsonResponse
    {
        $data = AudioResampleData::from($request->validated());

        $path = $this->service->audioResample($data);

        return $this->logAndResponse([
            'message' => 'Audio resampled and normalized successfully',
            'output_file_link' => $path,
        ]);
    }

    public function scale(ScaleRequest $request): JsonResponse
    {
        $data = ScaleData::from($request->validated());

        $path = $this->service->scaleVideo($data);

        return $this->logAndResponse([
            'message' => 'Video scaled successfully',
            'output_file_link' => $path,
        ]);
    }

    public function concatenate(ConcatenateRequest $request): JsonResponse
    {
        $data = ConcatenateData::from($request->validated());

        $path = $this->service->concatenateVideos($data);

        return $this->logAndResponse([
            'message' => 'Videos concatenated successfully',
            'output_file_link' => $path,
        ]);
    }

    public function fileInspection(FileInspectionRequest $request): JsonResponse
    {
        $data = FileInspectionData::from($request->validated());

        $metadata = $this->service->inspectFile($data);

        return $this->logAndResponse([
            'message' => 'File inspection completed successfully',
            'metadata' => $metadata,
        ]);
    }

    public function thumbnail(ThumbnailRequest $request): JsonResponse
    {
        $data = ThumbnailData::from($request->validated());

        $thumbnailUrl = $this->service->generateThumbnail($data);

        return $this->logAndResponse([
            'message' => 'Thumbnail generated successfully',
            'thumbnail_url' => $thumbnailUrl,
        ]);
    }

    public function bitrateControl(BitrateControlRequest $request): JsonResponse
    {
        $data = BitrateControlData::from($request->validated());

        $path = $this->service->controlBitrate($data);

        return $this->logAndResponse([
            'message' => 'Bitrate control applied successfully',
            'output_file_link' => $path,
        ]);
    }

    public function streamCopy(StreamCopyRequest $request): JsonResponse
    {
        $data = StreamCopyData::from($request->validated());

        $path = $this->service->copyStreams($data);

        return $this->logAndResponse([
            'message' => 'Stream copy completed successfully',
            'output_file_link' => $path,
        ]);
    }

    public function videoEncode(VideoEncodeRequest $request): JsonResponse
    {
        $data = VideoEncodeData::from($request->validated());

        $path = $this->service->encodeVideo($data);

        return $this->logAndResponse([
            'message' => 'Video encoded successfully',
            'output_file_link' => $path,
        ]);
    }

    public function batchProcess(BatchProcessRequest $request): JsonResponse
    {
        $data = BatchProcessData::from([
            'services' => $request->services,
            'ffmpegProvider' => $request->ffmpegProvider,
        ]);
        $results = $this->service->processBatch($data);

        return $this->logAndResponse([
            'message' => 'Batch processing completed',
            'total_jobs' => count($data->services),
            'successful_jobs' => count(array_filter($results, fn($result) => $result['status'] === 'success')),
            'failed_jobs' => count(array_filter($results, fn($result) => $result['status'] === 'error')),
            'results' => $results,
        ]);
    }
}
