<?php

namespace App\Services;

use App\Data\Request\Gemini\CodeGenerationData;
use App\Data\Request\Gemini\DocumentSummarizationData;
use App\Data\Request\Gemini\ImageAnalysisData;
use App\Data\Request\Gemini\TextGenerationData;
use App\Enums\common\ServiceTypeEnum;
use App\Http\Exceptions\Forbidden;
use App\Http\Exceptions\NotFound;
use App\Http\Resources\Gemini\CodeGenerationResource;
use App\Models\ServiceProvider;
use App\Models\ServiceProviderType;
use App\Models\ServiceType;
use App\Traits\GeminiTrait;
use Exception;
use Gemini\Exceptions\ErrorException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
use stdClass;
use Throwable;

class GeminiService
{
    use GeminiTrait;

    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta';

    protected string $apiKey;

    protected int $maxTokens;

    /**
     * @throws NotFound
     * @throws Throwable
     */
    protected function initializeService(string $serviceTypeName): void
    {
        $provider = ServiceProvider::where('type', 'Gemini')->first();

        if (!$provider) {
            throw new NotFound('Gemini service provider not found.');
        }

        $serviceType = ServiceType::where('name', $serviceTypeName)->first();
        if (!$serviceType) {
            throw new NotFound('Gemini service type not found.');
        }

        $providerType = ServiceProviderType::where([
            ['service_provider_id', $provider->id],
            ['service_type_id', $serviceType->id],
        ])->first();

        if (!$providerType) {
            throw new NotFound('Gemini provider type configuration missing.');
        }

        $apiKey = config('services.gemini.api_key');
        throw_if(empty($apiKey), new NotFound('Gemini key not configured.'));

        $this->apiKey = $apiKey;
        $this->maxTokens = $provider->parameter['max_tokens'];
    }

    /**
     * Generate text using Gemini
     *
     * @param TextGenerationData $data
     * @return array
     *
     * @throws ConnectionException
     * @throws Forbidden
     * @throws NotFound|Throwable
     */
    public function textGeneration(TextGenerationData $data): array
    {
        $this->initializeService(ServiceTypeEnum::TEXT_GENERATION->value);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])
            ->timeout(60)
            ->connectTimeout(5)
            ->post("{$this->baseUrl}/models/{$data->model}:generateContent?key={$this->apiKey}", [
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
                    'maxOutputTokens' => $this->maxTokens,
                ],
            ]);

        $jsonResponse = $response->json();

        if ($response->failed()) {
            Log::error('Gemini request error: ' . json_encode($response->json()));
            if (array_key_exists('error', $jsonResponse)) {
                throw new Forbidden('Gemini request failed: ' . $jsonResponse['error']['message']);
            }

            throw new Forbidden('Gemini request failed');
        }

        return [
            'generated_text' => $jsonResponse['candidates'][0]['content']['parts'][0]['text']
        ];
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
                    if ($attachment instanceof UploadedFile) {
                        $messages[0]['parts'][] = $this->prepareAttachmentPart($attachment);
                    }
                }
            }

            
            $payload = [
                'system_instruction' => [
                    'parts' => [
                        'text' => config('gemini.system_prompts.code_generation')
                    ]
                ],
                'contents' => $messages,
                'generation_config' => [
                    'max_output_tokens' => $this->maxTokens,
                    'temperature' => $data->temperature,
                ],
            ];
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])
                ->timeout(60)
                ->connectTimeout(5)
                ->post("{$this->baseUrl}/models/{$data->model}:generateContent?key={$this->apiKey}", $payload);
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
     * @return array
     *
     * @throws ConnectionException
     * @throws Forbidden
     * @throws NotFound|Throwable
     */
    public function imageAnalysis(ImageAnalysisData $data): array
    {
        $this->initializeService(ServiceTypeEnum::IMAGE_ANALYSIS->value);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/models/gemini-1.5-pro:generateContent?key={$this->apiKey}", [
            'contents' => [
                [
                    'parts' => [
                        [
                            'inlineData' => [
                                'mimeType' => 'image/*',
                                'data' => base64_encode(file_get_contents($data->image_url)),
                            ],
                        ],
                        ['text' => $data->description_required],
                    ],
                ],
            ],
        ]);

        $jsonResponse = $response->json();

        if ($response->failed()) {
            Log::error('Gemini request error: ' . json_encode($response->json()));
            if (array_key_exists('error', $jsonResponse)) {
                throw new Forbidden('Gemini request failed: ' . $jsonResponse['error']['message']);
            }

            throw new Forbidden('Gemini request failed');
        }

        return [
            'image_analyzed' => $jsonResponse['candidates'][0]['content']['parts'][0]['text']
        ];
    }

    /**
     * Document summarization code using Gemini
     *
     * @param DocumentSummarizationData $data
     * @return array
     *
     * @throws ConnectionException
     * @throws Forbidden
     * @throws NotFound|Throwable
     */
    public function documentSummarization(DocumentSummarizationData $data): array
    {
        $this->initializeService(ServiceTypeEnum::DOCUMENT_SUMMARIZATION->value);

        $prompt = "Please summarize the following document:\n\n{$data->document_text}\n\nSummary length should be approximately {$data->summary_length} tokens.";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/models/{$data->model}:generateContent?key={$this->apiKey}", [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                    ],
                ],
            ],
            'generationConfig' => [
                'maxOutputTokens' => $data->summary_length + 100,
            ],
        ]);

        $jsonResponse = $response->json();

        if ($response->failed()) {
            Log::error('Gemini request error: ' . json_encode($response->json()));
            if (array_key_exists('error', $jsonResponse)) {
                throw new Forbidden('Gemini request failed: ' . $jsonResponse['error']['message']);
            }

            throw new Forbidden('Gemini request failed');
        }

        return [
            'document_summarized' => $jsonResponse['candidates'][0]['content']['parts'][0]['text']
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
            $result->error = $errorData['error']['message'] ?? $response->body() ?? 'Unknown Gemini API error';
            Log::error('Gemini API Error: ' . $response->body());
            return $result;
        }

        $responseData = $response->json();

        if (empty($responseData['candidates'])) {
            $result->error = 'No candidates returned by Gemini API';
            Log::error('Gemini Malformed Response: Missing candidates', $responseData);
            return $result;
        }

        $firstCandidate = $responseData['candidates'][0] ?? null;
        $parts = $firstCandidate['content']['parts'] ?? [];

        if (empty($parts) || empty($parts[0]['text'] ?? null)) {
            $result->error = 'Missing or empty response content from Gemini';
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
