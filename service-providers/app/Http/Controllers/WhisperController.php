<?php

namespace App\Http\Controllers;

use App\Data\Request\Whisper\AudioTranscribeData;
use App\Http\Requests\Whisper\AudioTranscribeRequest;
use App\Services\WhisperService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class WhisperController extends BaseController
{
    public function __construct(public WhisperService $service) {}

    #[OA\Post(
        path: '/api/whisper/audio-transcribe',
        operationId: 'audioTranscribe',
        description: 'Audio Transcription',
        summary: 'Transcribe audio files to text.',
        security: [['authentication' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['language', 'prompt'],
                    properties: [
                        new OA\Property(
                            property: 'file',
                            type: 'string',
                            format: 'binary',
                        ),
                        new OA\Property(
                            property: 'link',
                            type: 'string',
                            example: 'https://output.lemonfox.ai/wikipedia_ai.mp3'
                        ),
                        new OA\Property(
                            property: 'language',
                            type: 'string',
                            example: 'en'
                        ),
                        new OA\Property(
                            property: 'prompt',
                            type: 'string',
                            example: 'Transcribe the audio file'
                        ),
                    ],
                    type: 'object'
                )
            )
        ),
        tags: ['Whisper'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(
                example: [
                    'text' => 'This is a sample transcription of the audio file.',
                ]
            )),
        ]
    )]
    public function audioTranscribe(AudioTranscribeRequest $request): JsonResponse
    {
        $data = AudioTranscribeData::from($request->validated());

        $result = $this->service->audioTranscribe($data);

        return $this->logAndResponse([
            'message' => 'Audio transcription successful.',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/whisper/audio-transcribe-timestamps',
        operationId: 'audioTranscribeTimestamps',
        description: 'Audio Transcription with Timestamps',
        summary: 'Transcribe audio files to text with timestamps.',
        security: [['authentication' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['language', 'prompt'],
                    properties: [
                        new OA\Property(
                            property: 'file',
                            type: 'string',
                            format: 'binary',
                        ),
                        new OA\Property(
                            property: 'link',
                            type: 'string',
                            example: 'https://output.lemonfox.ai/wikipedia_ai.mp3'
                        ),
                        new OA\Property(
                            property: 'language',
                            type: 'string',
                            example: 'en'
                        ),
                        new OA\Property(
                            property: 'prompt',
                            type: 'string',
                            example: 'Transcribe the audio file'
                        ),
                    ],
                    type: 'object'
                )
            )
        ),
        tags: ['Whisper'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(
                example: [

                ]
            )),
        ]
    )]
    public function audioTranscribeTimestamps(AudioTranscribeRequest $request): JsonResponse
    {
        $data = AudioTranscribeData::from($request->validated());

        $result = $this->service->audioTranscribeTimestamps($data);

        return $this->logAndResponse([
            'message' => 'Audio transcription with timestamps successfully completed.',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/whisper/audio-translate',
        operationId: 'audioTranslate',
        description: 'Audio Translation',
        summary: 'Translate audio files to text.',
        security: [['authentication' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['language', 'prompt'],
                    properties: [
                        new OA\Property(
                            property: 'file',
                            type: 'string',
                            format: 'binary',
                        ),
                        new OA\Property(
                            property: 'link',
                            type: 'string',
                            example: 'https://output.lemonfox.ai/wikipedia_ai.mp3'
                        ),
                        new OA\Property(
                            property: 'language',
                            type: 'string',
                            example: 'en'
                        ),
                        new OA\Property(
                            property: 'prompt',
                            type: 'string',
                            example: 'Transcribe the audio file'
                        ),
                    ],
                    type: 'object'
                )
            )
        ),
        tags: ['Whisper'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(
                example: [

                ]
            )),
        ]
    )]
    public function audioTranslate(AudioTranscribeRequest $request): JsonResponse
    {
        $data = AudioTranscribeData::from($request->validated());

        $result = $this->service->audioTranslate($data);

        return $this->logAndResponse([
            'message' => 'Audio translation successfully completed.',
            'data' => $result,
        ]);
    }
}
