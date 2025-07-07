<?php

namespace App\Services;

use App\Data\Request\Claude\CodegenData;
use App\Data\Request\Claude\DataAnalysisInsightData;
use App\Data\Request\Claude\PersonalizationData;
use App\Data\Request\Claude\TextTranslateData;
use App\Data\Request\Claude\QuestionAnswerData;
use App\Data\Request\Claude\TextClassifyData;
use App\Data\Request\Claude\TextGenerationData;
use App\Data\Request\Claude\TextSummarizeData;
use App\Enums\common\ServiceProviderEnum;
use App\Enums\common\ServiceTypeEnum;
use App\Http\Exceptions\BadRequest;
use App\Http\Exceptions\Forbidden;
use App\Http\Exceptions\NotFound;
use App\Http\Resources\ClaudeAPI\CodegenResource;
use App\Http\Resources\ClaudeAPI\DataAnalysisInsightResource;
use App\Http\Resources\ClaudeAPI\PersonalizationResource;
use App\Http\Resources\ClaudeAPI\QuestionAnswerResource;
use App\Http\Resources\ClaudeAPI\TextClassifyResource;
use App\Http\Resources\ClaudeAPI\TextGenerationResource;
use App\Http\Resources\ClaudeAPI\TextSummarizeResource;
use App\Http\Resources\ClaudeAPI\TextTranslateResource;
use App\Models\ServiceProvider;
use App\Models\ServiceProviderModel;
use App\Models\ServiceType;
use App\Traits\ClaudeAITrait;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use stdClass;

class ClaudeAPIService
{
    use ClaudeAITrait;

    /**
     * Claude API model
     */
    protected string $CLAUDE_API_MODEL = 'claude-3-5-haiku-20241022';

    protected string $CLAUDE_API_VERSION_HEADER = '2023-06-01';

    protected string $apiUrl;

    protected string $apiKey;

    protected PendingRequest $client;

    /**
     * This file contains the ClaudeAPIService class, which is responsible for
     * interacting with the external Claude API service. The service provides
     * methods to handle API requests and responses for integration purposes.
     */
    protected function initializeService(ServiceTypeEnum $serviceTypeName, ?string $model = null): void
    {
        $provider = ServiceProvider::where('type', ServiceProviderEnum::CLAUDE->value)->first();

        if (
            !$provider ||
            !isset($provider->parameters['base_url'], $provider->parameters['version'])
        ) {
            throw new NotFound('Claude API service provider not found.');
        }

        $apiKey = config('services.anthropic.api_key');

        throw_if(empty($apiKey), new NotFound('Claude API key not configured.'));

        $this->apiKey = $apiKey;

        $serviceType = ServiceType::where('service_provider_id', $provider->id)
            ->where('name', $serviceTypeName->value)
            ->first();

        if (!$serviceType) {
            throw new NotFound('Claude API service type not found.');
        }

        if (!is_null($model)) {

            $modelExists = ServiceProviderModel::where([
                ['service_provider_id', $provider->id],
                ['name', $model],
            ])->exists();

            if (!$modelExists) {
                throw new NotFound('Claude API model not found.');
            }

            $this->CLAUDE_API_MODEL = $model;
        }

        $this->apiUrl = "{$provider->parameters['base_url']}/{$provider->parameters['version']}/messages";

        $this->client = Http::withHeaders([
            'x-api-key' => $this->apiKey,
            'anthropic-version' => $this->CLAUDE_API_VERSION_HEADER,
        ])->timeout(0)
            ->connectTimeout(15);
    }

    /**
     * Generate text using Claude API
     *
     * @throws Forbidden
     */
    public function textGeneration(TextGenerationData $data): TextGenerationResource
    {
        $this->initializeService(ServiceTypeEnum::TEXT_GENERATION_SERVICE, $data->model);

        try {
            $response = $this->client->post($this->apiUrl, [
                'model' => $this->CLAUDE_API_MODEL,
                'max_tokens' => $data->max_tokens,
                'system' => config('claudeAPI.system_prompts.text_generation'),
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => json_encode([
                            'prompt' => $data->prompt,
                            'max_tokens' => $data->max_tokens,
                        ]),
                    ],
                ],
            ]);
        } catch (ConnectionException | Exception $e) {
            Log::error('Claude API request error: ' . json_encode($e->getMessage()));
            throw new Forbidden('Claude API request failed');
        }

        $result = $this->handleResponse($response);

        return TextGenerationResource::make($result);
    }

    /**
     * Generate text using Claude API
     *
     * @throws Forbidden
     */
    public function textSummarize(TextSummarizeData $data): TextSummarizeResource
    {
        $this->initializeService(ServiceTypeEnum::TEXT_SUMMARIZATION_SERVICE, $data->model);

        try {
            $response = $this->client->post($this->apiUrl, [
                'model' => $this->CLAUDE_API_MODEL,
                'max_tokens' => $data->max_tokens,
                'system' => config('claudeAPI.system_prompts.text_summarize'),
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => json_encode([
                            'text' => $data->text,
                            'summary_length' => $data->summary_length,
                        ]),
                    ],
                ],
            ]);
        } catch (ConnectionException | Exception $e) {
            Log::error('Claude API request error: ' . json_encode($e->getMessage()));
            throw new Forbidden('Claude API request failed');
        }

        $result = $this->handleResponse($response);
        $result->summary_length = $data->summary_length;

        return TextSummarizeResource::make($result);
    }

    /**
     * Generate text using Claude API
     *
     * @throws Forbidden
     */
    public function questionAnswer(QuestionAnswerData $data): QuestionAnswerResource
    {
        $this->initializeService(ServiceTypeEnum::QUESTION_ANSWERING_SERVICE, $data->model);

        try {
            $response = $this->client->post($this->apiUrl, [
                'model' => $this->CLAUDE_API_MODEL,
                'max_tokens' => $data->max_tokens,
                'system' => config('claudeAPI.system_prompts.question_answer'),
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => json_encode([
                            'question' => $data->question,
                            'context' => $data->context,
                        ]),
                    ],
                ],
            ]);
        } catch (ConnectionException | Exception $e) {
            Log::error('Claude API request error: ' . json_encode($e->getMessage()));
            throw new Forbidden('Claude API request failed');
        }

        $result = $this->handleResponse($response);

        return QuestionAnswerResource::make($result);
    }

    /**
     * Generate text using Claude API
     *
     * @throws Forbidden
     */
    public function textClassify(TextClassifyData $data): TextClassifyResource
    {
        $this->initializeService(ServiceTypeEnum::TEXT_CLASSIFICATION_SERVICE, $data->model);

        try {
            $response = $this->client->post($this->apiUrl, [
                'model' => $this->CLAUDE_API_MODEL,
                'max_tokens' => $data->max_tokens,
                'system' => config('claudeAPI.system_prompts.text_classify'),
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => json_encode([
                            'text' => $data->text,
                            'categories' => $data->categories,
                        ]),
                    ],
                ],
            ]);
        } catch (ConnectionException | Exception $e) {
            Log::error('Claude API request error: ' . json_encode($e->getMessage()));
            throw new Forbidden('Claude API request failed');
        }

        $result = $this->handleResponse($response);

        $content = $result->message;

        $content = preg_replace('/```json\s*(.*?)\s*```/s', '$1', $content);
        $content = preg_replace('/```\s*(.*?)\s*```/s', '$1', $content);
        $content = trim($content);

        $classification = json_decode($content, true);

        $result->sentiment = $classification['sentiment'] ?? null;
        $result->category = $classification['category'] ?? null;

        return TextClassifyResource::make($result);
    }

    /**
     * Translate text using Claude API
     *
     * @throws Forbidden
     */
    public function textTranslate(TextTranslateData $data): TextTranslateResource
    {
        $this->initializeService(ServiceTypeEnum::TEXT_TRANSLATION_SERVICE, $data->model);

        try {
            $response = $this->client->post($this->apiUrl, [
                'model' => $this->CLAUDE_API_MODEL,
                'max_tokens' => $data->max_tokens,
                'system' => config('claudeAPI.system_prompts.text_translation'),
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => json_encode([
                            'text' => $data->text,
                            'source_language' => $data->source_language,
                            'target_language' => $data->target_language,
                        ]),
                    ],
                ],
            ]);
        } catch (ConnectionException | Exception $e) {
            Log::error('Claude API request error: ' . json_encode($e->getMessage()));
            throw new Forbidden('Claude API request failed');
        }

        $result = $this->handleResponse($response);

        return TextTranslateResource::make($result);
    }

    /**
     * Generate code using Claude API
     *
     * @throws Forbidden
     */
    public function codegen(CodegenData $data): CodegenResource
    {
        $this->initializeService(ServiceTypeEnum::CODE_GENERATION_SERVICE, $data->model);

        try {
            $messages = [
                [
                    'role' => 'user',
                    'content' => []
                ]
            ];

            $messages[0]['content'][] = [
                'type' => 'text',
                'text' => $data->description
            ];

            if (!empty($data->attachments)) {
                foreach ($data->attachments as $attachment) {
                    $messages[0]['content'][] = $this->prepareAttachment($attachment);
                }
            }

            $response = $this->client->post($this->apiUrl, [
                'model' => $this->CLAUDE_API_MODEL,
                'max_tokens' => $data->max_tokens,
                'system' => config('claudeAPI.system_prompts.code_generation'),
                'messages' => $messages
            ]);
        } catch (ConnectionException | Exception $e) {
            Log::error('Claude API request error: ' . json_encode($e->getMessage()));
            throw new Forbidden('Claude API request error: ' . $e->getMessage());
        }

        $result = $this->handleResponse($response);

        return CodegenResource::make($result);
    }

    /**
     * Perform data analysis and insights using Claude API
     *
     * @throws Forbidden
     */
    public function dataAnalysisAndInsight(DataAnalysisInsightData $data): DataAnalysisInsightResource
    {
        $this->initializeService(ServiceTypeEnum::DATA_ANALYSIS_AND_INSIGHT_SERVICE, $data->model ?? null);

        try {
            $response = $this->client->post($this->apiUrl, [
                'model' => $this->CLAUDE_API_MODEL,
                'max_tokens' => $data->max_tokens,
                'system' => config('claudeAPI.system_prompts.data_analysis_and_insight'),
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => json_encode([
                            'text' => $data->data,
                            'task' => $data->task,
                        ]),
                    ],
                ],
            ]);
        } catch (ConnectionException | Exception $e) {
            Log::error('Claude API request error: ' . json_encode($e->getMessage()));
            throw new Forbidden('Claude API request failed');
        }

        $result = $this->handleResponse($response);

        return DataAnalysisInsightResource::make($result);
    }

    /**
     * Personalize content using Claude API
     *
     * @throws Forbidden
     */
    public function personalize(PersonalizationData $data): PersonalizationResource
    {
        $this->initializeService(ServiceTypeEnum::PERSONALIZATION_SERVICE, $data->model ?? null);

        try {
            $response = $this->client->post($this->apiUrl, [
                'model' => $this->CLAUDE_API_MODEL,
                'max_tokens' => $data->max_tokens,
                'system' => config('claudeAPI.system_prompts.personalization'),
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => json_encode([
                            'text' => $data->user_id,
                            'preferences' => $data->preferences,
                        ]),
                    ],
                ],
            ]);
        } catch (ConnectionException | Exception $e) {
            Log::error('Claude API request error: ' . json_encode($e->getMessage()));
            throw new Forbidden('Claude API request failed');
        }

        $result = $this->handleResponse($response);

        return PersonalizationResource::make($result);
    }

    /**
     * Handle and process Claude API response
     * 
     * @param Response $response The response from Claude API
     * @return stdClass Object containing status, message, and potentially error
     * @throws HttpException When API request fails
     */
    protected function handleResponse(Response $response)
    {
        $result = new stdClass;
        $result->status = false;
        $result->message = '';

        // Handle HTTP error cases
        if ($response->failed()) {
            // Extract error information from response
            $errorMessage = 'Claude API request failed';
            $errorData = null;

            // Try to extract error from JSON
            try {
                $responseBody = $response->json();
                if (is_array($responseBody) && array_key_exists('error', $responseBody)) {
                    $errorData = $responseBody['error'];
                    if (is_array($errorData) && isset($errorData['message'])) {
                        $errorMessage = 'Claude API request failed: ' . $errorData['message'];
                    } elseif (is_string($errorData)) {
                        $errorMessage = 'Claude API request failed: ' . $errorData;
                    }
                }
            } catch (\Exception $e) {
                // Fallback if response cannot be parsed as JSON
                $responseBody = $response->body();
                if (!empty($responseBody) && is_string($responseBody)) {
                    // Check if body contains error information
                    if (str_contains(strtolower($responseBody), 'error')) {
                        $errorMessage = 'Claude API request failed: ' . substr($responseBody, 0, 200);
                    }
                }
            }

            // Log the error with all available information
            Log::error('Claude API error', [
                'status_code' => $response->status(),
                'response_body' => $response->body(),
                'error_message' => $errorMessage
            ]);

            $result->error = $errorMessage;
            return $result;
        }

        // Handle successful HTTP response but potentially invalid content
        try {
            // First check if response can be parsed as JSON
            $responseData = $response->json();
            if ($responseData === null) {
                Log::warning('Claude API returned non-JSON response', [
                    'response_body' => $response->body()
                ]);
                $result->error = $response->body() ?? 'Invalid response format from Claude API';
                return $result;
            }

            // Extract stop reason
            $stopReason = $response->json('stop_reason');

            // Check for content in the response
            $content = $response->json('content');
            if (is_array($content) && !empty($content)) {
                $result->status = true;

                // Extract text from content objects
                $textParts = [];
                foreach ($content as $contentItem) {
                    if (isset($contentItem['text'])) {
                        $textParts[] = $contentItem['text'];
                    }
                }

                $result->message = implode('', $textParts);

                // If message is empty despite having content, that's an issue
                if (empty($result->message) && $result->status) {
                    Log::warning('Claude API returned content with no text', [
                        'content' => $content
                    ]);
                }
            } else {
                Log::warning('Claude API response missing content array', [
                    'response' => $responseData
                ]);
                $result->error = 'No content in Claude API response';
            }

            // Handle stop reason cases
            if ($stopReason === 'max_tokens') {
                $result->status = false;
                $result->error = 'Claude API request failed: max tokens reached';
                Log::warning('Claude API hit max tokens limit', [
                    'message_length' => strlen($result->message ?? '')
                ]);
            } elseif ($stopReason === 'end_turn' || $stopReason === 'stop_sequence') {
                $result->status = true;
            } elseif (!empty($stopReason)) {
                // Log other stop reasons but don't treat as errors
                Log::info('Claude API stopped with reason: ' . $stopReason);
            } elseif (empty($stopReason) && $result->status) {
                // No stop reason but we have content - log but continue
                Log::info('Claude API response missing stop_reason but has content');
            }

            return $result;
        } catch (\Exception $e) {
            // Handle unexpected exceptions during response processing
            Log::error('Error processing Claude API response', [
                'exception' => $e->getMessage(),
                'response_body' => $response->body()
            ]);

            $result->error = 'Error processing Claude API response: ' . $e->getMessage();
            return $result;
        }
    }
}
