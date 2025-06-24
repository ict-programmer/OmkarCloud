<?php

namespace App\Http\Controllers;

use App\Models\ServiceProvider;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class MainFunctionController extends Controller
{
    #[OA\Post(
        path: '/api/67cae130b9787c543c0d5a40/67f40bb42878e1a2da0f75a2',
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
    public function __invoke($serviceProviderId, $serviceTypeId, Request $request)
    {
        $serviceProvider = ServiceProvider::query()->find($serviceProviderId);
        $serviceType = ServiceType::query()->find($serviceTypeId);

        if (!$serviceProvider || !$serviceType)
            return response()->json(['error' => 'Service provider or service type not found'], 404);

        if (is_null($serviceProvider->controller_name) || is_null($serviceType->request_class_name) || is_null($serviceType->function_name))
            return response()->json(['error' => 'Service provider or service type configuration is incomplete'], 404);

        $controller = app($serviceProvider->controller_name);

        $formRequest = app($serviceType->request_class_name);
        
        $formRequest->replace($request->all());
        $formRequest->files = $request->files;
        $formRequest->headers = $request->headers;
        
        $formRequest->validateResolved();
        
        return app()->call([$controller, $serviceType->function_name], ['request' => $formRequest]);
    }
}
