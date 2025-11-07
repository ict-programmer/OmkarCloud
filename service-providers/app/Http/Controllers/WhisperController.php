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

    public function audioTranscribe(AudioTranscribeRequest $request): JsonResponse
    {
        $data = AudioTranscribeData::from($request->validated());

        $result = $this->service->audioTranscribe($data);

        return $this->logAndResponse([
            'message' => 'Audio transcription successful.',
            'data' => $result,
        ]);
    }

    public function audioTranscribeTimestamps(AudioTranscribeRequest $request): JsonResponse
    {
        $data = AudioTranscribeData::from($request->validated());

        $result = $this->service->audioTranscribeTimestamps($data);

        return $this->logAndResponse([
            'message' => 'Audio transcription with timestamps successfully completed.',
            'data' => $result,
        ]);
    }

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
