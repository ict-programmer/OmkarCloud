<?php

namespace App\Services;

use App\Data\Request\DeepSeek\ChatCompletionData;
use App\Data\Request\DeepSeek\CodeCompletionData;
use App\Data\Request\DeepSeek\DocumentQaData;
use App\Data\Request\DeepSeek\mathematicalReasoningData;
use App\Http\Exceptions\Forbidden;
use App\Http\Resources\DeepSeek\CodeCompletionResource;
use App\Traits\DeepSeekTrait;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use stdClass;

class DeepSeekService
{
    use DeepSeekTrait;

    protected mixed $apiKey;
    protected string $baseUrl = 'https://api.deepseek.com/v1';

    public function __construct()
    {
        $this->apiKey = config('services.deep_seek.api_key');
    }

    /**
     * Generate chat completion using Deep Seek
     *
     * @param ChatCompletionData $data
     * @return array
     *
     * @throws ConnectionException
     * @throws Forbidden
     */
    public function chatCompletion(ChatCompletionData $data): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post(
            $this->baseUrl . '/chat/completions',
            [
                'messages' => $data->messages,
                'model' => $data->model,
                'max_tokens' => $data->max_tokens,
                'temperature' => $data->temperature,
            ]
        );

        $jsonResponse = $response->json();

        if ($response->failed()) {
            Log::error('Deep Seek request error: ' . json_encode($response->json()));
            if (is_array($jsonResponse) && array_key_exists('error', $jsonResponse)) {
                throw new Forbidden('Deep Seek request failed: ' . $jsonResponse['error']['message']);
            }

            throw new Forbidden('Deep Seek request failed');
        }

        return [
            'chat_completion' => $jsonResponse['choices'][0]['message']['content']
        ];
    }

    /**
     * Generate code completion using Deep Seek
     *
     * @param  CodeCompletionData  $data
     * @return JsonResource
     *
     * @throws Forbidden
     */
    public function codeCompletion(CodeCompletionData $data): JsonResource
    {

        try {

            $messages = [
                [
                    'role' => 'system',
                    'content' => config('deepseek.system_prompts.code_generation')
                ],
                [
                    'role' => 'user',
                    'content' => []
                ]
            ];

            $messages[1]['content'][] = [
                'type' => 'text',
                'text' => $data->prompt
            ];

            if (!empty($data->attachments)) {
                foreach ($data->attachments as $attachment) {
                    if ($attachment instanceof UploadedFile) {
                        $messages[1]['content'][] = $this->prepareAttachment($attachment);
                    }
                }
            }

            $payload = [
                'model' => $data->model,
                'messages' =>  $messages,
                'max_tokens' => $data->max_tokens,
                'temperature' => $data->temperature,
                'stream' => false,
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
                ->timeout(0)
                ->connectTimeout(15)
                ->post(
                    $this->baseUrl . '/chat/completions',
                    $payload
                );
        } catch (ConnectionException | Exception $e) {
            Log::error('Deepseek API request error: ' . $e->getMessage());
            throw new Forbidden('Deepseek API request failed: ' . $e->getMessage());
        }

        $result = $this->handleResponse($response);
        return CodeCompletionResource::make($result);
    }

    /**
     * Generate code completion using Deep Seek
     *
     * @param  DocumentQaData  $data
     * @return array
     *
     * @throws ConnectionException
     * @throws Forbidden
     */
    public function documentQa(DocumentQaData $data): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post(
            $this->baseUrl . '/chat/completions',
            [
                'model' => 'deepseek-chat',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => json_encode([
                            'question' => $data->question,
                            'context' => $data->document_text,
                        ]),
                    ]
                ]
            ]
        );

        $jsonResponse = $response->json();

        if ($response->failed()) {
            Log::error('Deep Seek request error: ' . json_encode($response->json()));
            if (array_key_exists('error', $jsonResponse)) {
                throw new Forbidden('Deep Seek request failed: ' . $jsonResponse['error']['message']);
            }

            throw new Forbidden('Deep Seek request failed');
        }

        return [
            'document_qa' => $jsonResponse['choices'][0]['message']['content']
        ];
    }

    /**
     * Generate mathematical reasoning using Deep Seek
     *
     * @param  mathematicalReasoningData  $data
     * @return array
     *
     * @throws ConnectionException
     * @throws Forbidden
     */
    public function mathematicalReasoning(mathematicalReasoningData $data): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post(
            $this->baseUrl . '/chat/completions',
            [
                'max_tokens' => $data->max_tokens,
                'model' => $data->model,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $data->problem_statement
                    ]
                ],
            ]
        );

        $jsonResponse = $response->json();

        if ($response->failed()) {
            Log::error('Deep Seek request error: ' . json_encode($response->json()));
            if (array_key_exists('error', $jsonResponse)) {
                throw new Forbidden('Deep Seek request failed: ' . $jsonResponse['error']['message']);
            }

            throw new Forbidden('Deep Seek request failed');
        }

        return [
            'mathematical_reason' => $jsonResponse['choices'][0]['message']['content']
        ];
    }

    protected function handleResponse(Response $response): stdClass
    {
        $result = new stdClass;
        $result->status = false;
        $result->message = '';
        $result->error = null;
        $result->usage = [];

        if ($response->failed()) {
            $errorData = $response->json();
            $result->error = $errorData['error']['message'] ?? $response->body() ?? 'Unknown API error';
            Log::error('Deepseek API Error: ' . $response->body());
            return $result;
        }

        $responseData = $response->json();

        if (!isset($responseData['choices'])) {
            $result->error = 'Invalid API response format';
            Log::error('Deepseek Malformed Response: Missing choices', $responseData);
            return $result;
        }

        if (empty($responseData['choices'])) {
            $result->error = 'API returned no completion choices';
            return $result;
        }

        $firstChoice = $responseData['choices'][0];

        if (isset($firstChoice['finish_reason']) && $firstChoice['finish_reason'] === 'error') {
            $result->error = $firstChoice['message'] ?? 'API processing error';
            return $result;
        }

        if (!isset($firstChoice['message']['content'])) {
            $result->error = 'Missing content in API response';
            return $result;
        }

        if (empty(trim($firstChoice['message']['content']))) {
            $result->error = 'Empty response from API';
            return $result;
        }

        $result->status = true;
        $result->message = $firstChoice['message']['content'];

        if (preg_match('/\b(error|sorry|unable)\b/i', $result->message)) {
            $result->status = false;
            $result->error = $result->message;
            $result->message = '';
            Log::warning('AI-generated error detected: ' . $result->error);
        }

        return $result;
    }
}
