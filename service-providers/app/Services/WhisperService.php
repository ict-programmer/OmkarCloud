<?php

namespace App\Services;

use App\Data\Request\Whisper\AudioTranscribeData;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class WhisperService
{
    /**
     * Transcribe audio files to text.
     *
     * @param AudioTranscribeData $data
     * @return array
     * @throws ConnectionException
     */
    public function audioTranscribe(AudioTranscribeData $data): array
    {
        return $this->callWhisperAPI([
            'file' => $data->link,
            'language' => $data->language,
            'prompt' => $data->prompt,
        ]);
    }

    /**
     * Translate audio files to text.
     *
     * @param AudioTranscribeData $data
     * @return array
     * @throws ConnectionException
     */
    public function audioTranslate(AudioTranscribeData $data): array
    {
        return $this->callWhisperAPI([
            'file' => $data->link,
            'language' => $data->language,
            'prompt' => $data->prompt,
            'translate' => true,
        ]);
    }

    /**
     * Call the Whisper API with the provided data.
     *
     * @param array $data
     * @return array|mixed
     * @throws ConnectionException
     */
    private function callWhisperAPI(array $data): mixed
    {
        $response = Http::withToken(config('whisper.api_key'))
            ->post(config('whisper.api_url'), $data);

        if ($response->failed()) {
            throw new ConnectionException('Failed to transcribe audio file.' . $response->body());
        }

        return $response->json();
    }

    /**
     * Transcribe audio files to text with timestamps.
     *
     * @param AudioTranscribeData $data
     * @return array
     * @throws ConnectionException
     *
     */
    public function audioTranscribeTimestamps(AudioTranscribeData $data): array
    {
        $vtt = $this->callWhisperAPI([
            'file' => $data->link,
            'language' => $data->language,
            'prompt' => $data->prompt,
            'response_format' => 'vtt'
        ]);

        $lines = explode("\n", $vtt);
        $result = [];
        $currentTime = null;

        foreach ($lines as $line) {
            $line = trim($line);
            if (preg_match('/(\d{2}:\d{2}:\d{2}\.\d{3}) --> (\d{2}:\d{2}:\d{2}\.\d{3})/', $line, $matches)) {
                $currentTime = $matches[1] . ' --> ' . $matches[2];
            } elseif (!empty($line) && $currentTime) {
                $result[] = [
                    'time' => $currentTime,
                    'text' => $line,
                ];
                $currentTime = null;
            }
        }

        return $result;
    }
}