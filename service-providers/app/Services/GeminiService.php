<?php

namespace App\Services;

use App\Data\Request\Gemini\CodeGenerationData;
use App\Data\Request\Gemini\DocumentSummarizationData;
use App\Data\Request\Gemini\ImageAnalysisData;
use App\Data\Request\Gemini\TextGenerationData;
use App\Enums\common\ServiceProviderEnum;
use App\Enums\common\ServiceTypeEnum;
use App\Http\Exceptions\Forbidden;
use App\Http\Exceptions\NotFound;
use App\Http\Resources\Gemini\CodeGenerationResource;
use App\Http\Resources\Gemini\DocumentSummarizationResource;
use App\Http\Resources\Gemini\ImageAnalysisResource;
use App\Http\Resources\Gemini\TextGenerationResource;
use App\Models\ServiceProvider;
use App\Models\ServiceType;
use App\Traits\GeminiTrait;
use App\Traits\MongoObjectIdTrait;
use Exception;
use Gemini\Exceptions\ErrorException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
use stdClass;
use Throwable;

class GeminiService
{
    use GeminiTrait;
    use MongoObjectIdTrait;

    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta';

    protected string $apiKey;

    protected PendingRequest $client;

    /**
     * @throws NotFound
     * @throws Throwable
     */
    protected function initializeService(string $serviceTypeName): void
    {
        $provider = ServiceProvider::where('type', ServiceProviderEnum::GEMINI->value)->first();

        if (!$provider) {
            throw new NotFound('Gemini service provider not found.');
        }

        $serviceType = ServiceType::where('service_provider_id', $this->toObjectId($provider->id))
            ->where('name', $serviceTypeName)
            ->first();

        if (!$serviceType) {
            throw new NotFound('Gemini service type not found.');
        }

        $apiKey = config('services.gemini.api_key');
        throw_if(empty($apiKey), new NotFound('Gemini key not configured.'));

        $this->apiKey = $apiKey;

        $this->client = Http::withHeaders(['Content-Type' => 'application/json'])
            ->timeout(0)
            ->connectTimeout(5);
    }

    /**
     * Generate text using Gemini
     *
     * @param TextGenerationData $data
     * @return TextGenerationResource
     *
     * @throws ConnectionException
     * @throws Forbidden
     * @throws NotFound|Throwable
     */
    public function textGeneration(TextGenerationData $data): TextGenerationResource
    {
        $this->initializeService(ServiceTypeEnum::TEXT_GENERATION->value);

        $systemPrompt = config('gemini.system_prompts.text_generation');

        try {
            $response = $this->client->post(
                "{$this->baseUrl}/models/{$data->model}:generateContent?key={$this->apiKey}",
                [
                    'system_instruction' => [
                        'parts' => [
                            'text' => $systemPrompt
                        ]
                    ],
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => $data->prompt,
                                ],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => $data->max_tokens,
                    ],
                ]
            );
        } catch (ConnectionException | Exception | ErrorException $e) {
            Log::error('Gemini request error: ' . json_encode($e->getMessage()));
            throw new Forbidden('Gemini request error: ' . $e->getMessage());
        }

        $result = $this->handleResponse($response);

        return TextGenerationResource::make($result);
    }

    /**
     * Generate code using Gemini
     *
     * @param  CodeGenerationData  $data
     * @return CodeGenerationResource
     *
     * @throws Forbidden
     * @throws NotFound|Throwable
     */
    public function codeGeneration(CodeGenerationData $data): CodeGenerationResource
    {
        $this->initializeService(ServiceTypeEnum::TEXT_GENERATION->value);

        $systemPrompt = config('gemini.system_prompts.code_generation');

        try {
            $messages = [
                [
                    'parts' => [],
                ],
            ];

            $messages[0]['parts'][] = [
                'text' => $data->prompt,
            ];

            if (!empty($data->attachments)) {
                foreach ($data->attachments as $attachment) {
                    $messages[0]['parts'][] = $this->prepareAttachmentPart($attachment, true);
                }
            }


            $payload = [
                'system_instruction' => [
                    'parts' => [
                        'text' => $systemPrompt
                    ]
                ],
                'contents' => $messages,
                'generation_config' => [
                    'max_output_tokens' => $data->max_tokens,
                    'temperature' => $data->temperature,
                ],
            ];

            $response = $this->client->post(
                "{$this->baseUrl}/models/{$data->model}:generateContent?key={$this->apiKey}",
                $payload
            );
        } catch (ConnectionException | Exception | ErrorException $e) {
            Log::error('Gemini request error: ' . json_encode($e->getMessage()));
            throw new Forbidden('Gemini request error: ' . $e->getMessage());
        }

        $result = $this->handleResponse($response);

        return CodeGenerationResource::make($result);
    }

    /**
     * Image analysis code using Gemini
     *
     * @param ImageAnalysisData $data
     * @return ImageAnalysisResource
     *
     * @throws ConnectionException
     * @throws Forbidden
     * @throws NotFound|Throwable
     */
    public function imageAnalysis(ImageAnalysisData $data): ImageAnalysisResource
    {
        $this->initializeService(ServiceTypeEnum::IMAGE_ANALYSIS->value);

        $systemPrompt = config('gemini.system_prompts.image_analysis');

        $data->image_cid = $this->getPublishUrl($data->image_cid);

        try {
            $file = file_get_contents($data->image_cid);
        } catch (Throwable $e) {
            throw new Forbidden('Failed to open provided file. Please try a different file.');
        }

        try {
            $response = $this->client->post(
                "{$this->baseUrl}/models/{$data->model}:generateContent?key={$this->apiKey}",
                [
                    'system_instruction' => [
                        'parts' => [
                            'text' => $systemPrompt
                        ]
                    ],
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'inlineData' => [
                                        'mimeType' => 'image/*',
                                        'data' => base64_encode($file),
                                    ],
                                ],
                                ['text' => $data->description_required],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => $data->max_tokens,
                    ],
                ]
            );
        } catch (ConnectionException | Exception | ErrorException $e) {
            Log::error('Gemini request error: ' . json_encode($e->getMessage()));
            throw new Forbidden('Gemini request error: ' . $e->getMessage());
        }

        $result = $this->handleResponse($response);

        return ImageAnalysisResource::make($result);
    }

    /**
     * Document summarization code using Gemini
     *
     * @param DocumentSummarizationData $data
     * @return DocumentSummarizationResource
     *
     * @throws ConnectionException
     * @throws Forbidden
     * @throws NotFound|Throwable
     */
    public function documentSummarization(DocumentSummarizationData $data): DocumentSummarizationResource
    {
        $this->initializeService(ServiceTypeEnum::DOCUMENT_SUMMARIZATION->value);

        $systemPrompt = config('gemini.system_prompts.document_summarization') . "\nSummary length should be approximately {$data->summary_length} tokens.";

        try {
            $response = $this->client->post(
                "{$this->baseUrl}/models/{$data->model}:generateContent?key={$this->apiKey}",
                [
                    'system_instruction' => [
                        'parts' => [
                            'text' => $systemPrompt
                        ]
                    ],
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => $data->document_text
                                ],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => $data->max_tokens,
                    ],
                ]
            );
        } catch (ConnectionException | Exception | ErrorException $e) {
            Log::error('Gemini request error: ' . json_encode($e->getMessage()));
            throw new Forbidden('Gemini request error: ' . $e->getMessage());
        }

        $result = $this->handleResponse($response);

        return DocumentSummarizationResource::make($result);
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
            $result->error = $errorData['error']['message'] ?? $response->body() ?? 'Unknown Gemini API error';
            Log::error('Gemini API Error: ' . $response->body());
            return $result;
        }

        $responseData = $response->json();

        $firstCandidate = $responseData['candidates'][0] ?? null;
        $parts = $firstCandidate['content']['parts'] ?? [];

        if (empty($parts) || empty($parts[0]['text'] ?? null)) {
            // Check if the response was stopped due to not enough tokens
            $finishReason = $firstCandidate['finishReason'] ?? null;

            if ($finishReason === 'MAX_TOKENS') {
                $result->error = 'Gemini API reached maximum token limit, try reducing the prompt length or increasing the max_tokens.';
                Log::error('Gemini API reached maximum token limit: ' . $response->body());
                return $result;
            }

            $result->error = 'Missing or empty response content from Gemini' . json_encode($responseData);
            return $result;
        }

        $result->status = true;
        $result->message = $parts[0]['text'];

        if (preg_match('/\\b(error|sorry|unable)\\b/i', $result->message)) {
            $result->status = false;
            $result->error = $result->message;
            $result->message = '';
            Log::warning('Gemini-generated error detected: ' . $result->error);
        }

        $result->usage = $responseData['usageMetadata'] ?? [];

        return $result;
    }
}
