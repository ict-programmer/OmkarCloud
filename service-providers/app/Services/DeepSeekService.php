<?php

namespace App\Services;

use App\Data\Request\DeepSeek\ChatCompletionData;
use App\Data\Request\DeepSeek\CodeCompletionData;
use App\Data\Request\DeepSeek\DocumentQaData;
use App\Data\Request\DeepSeek\mathematicalReasoningData;
use App\Http\Exceptions\Forbidden;
use App\Http\Exceptions\NotFound;
use App\Http\Resources\Deepseek\ChatCompletionResource;
use App\Http\Resources\DeepSeek\CodeCompletionResource;
use App\Http\Resources\Deepseek\DocumentQAResource;
use App\Http\Resources\Deepseek\MathReasoningResource;
use App\Traits\DeepSeekTrait;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use stdClass;

class DeepSeekService
{
    use DeepSeekTrait;

    protected mixed $apiKey;
    protected string $baseUrl = 'https://api.deepseek.com/v1';
    protected PendingRequest $client;

    public function __construct()
    {
        $this->apiKey = config('services.deep_seek.api_key');

        if (empty($this->apiKey)) {
            throw new NotFound('Deep Seek API key not configured.');
        }

        $this->client = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])
            ->timeout(0)
            ->connectTimeout(5);
    }

    /**
     * Generate chat completion using Deep Seek
     *
     * @param ChatCompletionData $data
     * @return ChatCompletionResource
     *
     * @throws ConnectionException
     * @throws Forbidden
     */
    public function chatCompletion(ChatCompletionData $data): ChatCompletionResource
    {
        try {
            $response = $this->client->post(
                $this->baseUrl . '/chat/completions',
                [
                    'messages' => $data->messages,
                    'model' => $data->model,
                    'max_tokens' => $data->max_tokens,
                    'temperature' => $data->temperature,
                ]
            );
        } catch (ConnectionException | Exception $e) {
            Log::error('Deepseek API request error: ' . $e->getMessage());
            throw new Forbidden('Deepseek API request failed: ' . $e->getMessage());
        }

        $result = $this->handleResponse($response);

        return ChatCompletionResource::make($result);
    }

    /**
     * Generate code completion using Deep Seek
     *
     * @param  CodeCompletionData  $data
     * @return CodeCompletionResource
     *
     * @throws Forbidden
     */
    public function codeCompletion(CodeCompletionData $data): CodeCompletionResource
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
                    $messages[1]['content'][] = $this->prepareAttachment($attachment);
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
     * @return DocumentQAResource
     *
     * @throws ConnectionException
     * @throws Forbidden
     */
    public function documentQa(DocumentQaData $data): DocumentQAResource
    {
        try {
            $response = $this->client->post(
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
        } catch (ConnectionException | Exception $e) {
            Log::error('Deepseek API request error: ' . $e->getMessage());
            throw new Forbidden('Deepseek API request failed: ' . $e->getMessage());
        }

        $result = $this->handleResponse($response);

        return DocumentQAResource::make($result);
    }

    /**
     * Generate mathematical reasoning using Deep Seek
     *
     * @param  mathematicalReasoningData  $data
     * @return MathReasoningResource
     *
     * @throws ConnectionException
     * @throws Forbidden
     */
    public function mathematicalReasoning(mathematicalReasoningData $data): MathReasoningResource
    {
        try {
            $response = $this->client->post(
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
        } catch (ConnectionException | Exception $e) {
            Log::error('Deepseek API request error: ' . $e->getMessage());
            throw new Forbidden('Deepseek API request failed: ' . $e->getMessage());
        }

        $result = $this->handleResponse($response);

        return MathReasoningResource::make($result);
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
