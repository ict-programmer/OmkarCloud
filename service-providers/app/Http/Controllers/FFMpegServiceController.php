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
use OpenApi\Attributes as OA;

class FFMpegServiceController extends Controller
{
    public function __construct(public FFMpegService $service) {}

    #[OA\Post(
        path: '/api/ffmpeg/video_processing',
        operationId: 'videoProcessing',
        description: 'Process a video file with resolution, bitrate and frame rate.',
        summary: 'Video Processing',
        security: [['authentication' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['input_file', 'resolution', 'bitrate', 'frame_rate'],
                    properties: [
                        new OA\Property(
                            property: 'input_file',
                            type: 'string',
                            format: 'binary'
                        ),
                        new OA\Property(
                            property: 'resolution',
                            type: 'string',
                            example: '1280x720'
                        ),
                        new OA\Property(
                            property: 'bitrate',
                            type: 'string',
                            example: '1000k'
                        ),
                        new OA\Property(
                            property: 'frame_rate',
                            type: 'integer',
                            example: 30
                        ),
                    ],
                    type: 'object'
                )
            )
        ),
        tags: ['FFMpeg'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(
                example: [
                    'message' => 'Video processed successfully',
                    'output_file_link' => 'https://example.com/path/to/processed/video.mp4'
                ]
            )),
        ]
    )]
    public function videoProcessing(VideoProcessingRequest $request): JsonResponse
    {
        $data = VideoProcessingData::from($request->validated());

        $path = $this->service->processVideo($data);

        return response()->json([
            'message' => 'Video processed successfully',
            'output_file_link' => $path,
        ]);
    }

    #[OA\Post(
        path: '/api/ffmpeg/audio_processing',
        operationId: 'audioProcessing',
        description: 'Extract and encode audio from a video file.',
        summary: 'Audio Processing',
        security: [['authentication' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['input_file', 'channels', 'bitrate', 'sample_rate'],
                    properties: [
                        new OA\Property(
                            property: 'input_file',
                            type: 'string',
                            format: 'binary'
                        ),
                        new OA\Property(
                            property: 'bitrate',
                            type: 'string',
                            example: '1000k'
                        ),
                        new OA\Property(
                            property: 'channels',
                            type: 'integer',
                            example: 2
                        ),
                        new OA\Property(
                            property: 'sample_rate',
                            type: 'integer',
                            example: 44100
                        ),
                    ],
                    type: 'object'
                )
            )
        ),
        tags: ['FFMpeg'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(
                example: [
                    'message' => 'Audio processed successfully',
                    'output_file_link' => 'https://example.com/path/to/processed/audio.mp3'
                ]
            )),
        ]
    )]
    public function audioProcessing(AudioProcessingRequest $request): JsonResponse
    {
        $data = AudioProcessingData::from($request->validated());

        $path = $this->service->processAudio($data);

        return response()->json([
            'message' => 'Audio processed successfully',
            'output_file_link' => $path,
        ]);
    }

    #[OA\Post(
        path: '/api/ffmpeg/image_processing',
        operationId: 'imageProcessing',
        description: 'Resize an image using FFmpeg.',
        summary: 'Image Processing',
        security: [['authentication' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['input_file', 'width', 'height'],
                    properties: [
                        new OA\Property(
                            property: 'input_file',
                            type: 'string',
                            format: 'binary'
                        ),
                        new OA\Property(
                            property: 'width',
                            type: 'integer',
                            example: 1280
                        ),
                        new OA\Property(
                            property: 'height',
                            type: 'integer',
                            example: 720
                        ),
                    ],
                    type: 'object'
                )
            )
        ),
        tags: ['FFMpeg'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(
                example: [
                    'message' => 'Image processed successfully',
                    'output_file_link' => 'https://example.com/path/to/processed/image.jpg'
                ]
            )),
        ]
    )]
    public function imageProcessing(ImageProcessingRequest $request): JsonResponse
    {
        $data = ImageProcessingData::from($request->validated());

        $path = $this->service->processImage($data);

        return response()->json([
            'message' => 'Image processed successfully',
            'output_file_link' => $path,
        ]);
    }

    #[OA\Post(
        path: '/api/ffmpeg/video_trimming',
        operationId: 'videoTrimming',
        description: 'Trim a portion of a video file.',
        summary: 'Video Trimming',
        security: [['authentication' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    required: ["input_file", "start_time", "end_time"],
                    properties: [
                        new OA\Property(
                            property: "input_file",
                            type: "string",
                            format: "binary"
                        ),
                        new OA\Property(
                            property: "start_time",
                            type: "string",
                            example: "00:00:02"
                        ),
                        new OA\Property(
                            property: "end_time",
                            type: "string",
                            example: "00:00:05"
                        )
                    ],
                    type: "object"
                )
            )
        ),
        tags: ['FFMpeg'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(
                example: [
                    'message' => 'Video trimmed successfully',
                    'output_file_link' => 'https://example.com/path/to/trimmed/video.mp4'
                ]
            )),
        ]
    )]
    public function videoTrimming(VideoTrimmingRequest $request): JsonResponse
    {
        $data = VideoTrimmingData::from($request->validated());

        $path = $this->service->trimVideo($data);

        return response()->json([
            'message' => 'Video trimmed successfully',
            'output_file_link' => $path,
        ]);
    }
}
