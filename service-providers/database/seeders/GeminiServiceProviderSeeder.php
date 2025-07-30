<?php

namespace Database\Seeders;

use App\Enums\common\ServiceProviderEnum;
use App\Http\Controllers\GeminiController;
use App\Http\Requests\Gemini\CodeGenerationRequest;
use App\Http\Requests\Gemini\DocumentSummarizationRequest;
use App\Http\Requests\Gemini\ImageAnalysisRequest;
use App\Http\Requests\Gemini\TextGenerationRequest;
use App\Models\ServiceProvider;
use App\Traits\ServiceProviderSeederTrait;
use Illuminate\Database\Seeder;

class GeminiServiceProviderSeeder extends Seeder
{
    use ServiceProviderSeederTrait;

    /**
     * Run the database seeds.
     * 
     * This seeder creates a comprehensive Gemini service provider configuration
     * with all available service types, including detailed parameter specifications,
     * data types, validation rules, and examples for each service.
     */
    public function run(): void
    {
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => ServiceProviderEnum::GEMINI->value],
            [
                'parameters' => [
                    'api_key' => 'YOUR_API_KEY',
                    'base_url' => 'https://generativelanguage.googleapis.com/v1beta',
                    'version' => 'v1beta',
                    'models_supported' => [
                        'gemini-2.5-flash',
                        'gemini-2.5-pro',
                        'gemini-2.0-flash',
                        'gemini-1.5-flash',
                        'gemini-1.5-pro',
                    ],
                    'features' => [
                        'text_generation',
                        'code_generation',
                        'image_analysis',
                        'document_summarization',
                    ],
                    'documentation' => [
                        'description' => 'Gemini API provides advanced AI capabilities for text generation, code generation, image analysis, and document summarization',
                        'api_documentation' => 'https://ai.google.dev/api/gemini_api',
                        'rate_limits' => 'Varies by model and plan',
                        'authentication' => 'API Key required in query parameters',
                    ],
                ],
                'is_active' => true,
                'controller_name' => GeminiController::class,
            ]
        );

        $serviceTypes = [
            [
                'name' => 'Text Generation',
                'description' => 'Generate creative and informative text based on prompts',
                'input_parameters' => [
                    'model' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'gemini-2.5-flash',
                        'options' => [
                            'source' => 'collection',
                            'collection_name' => 'service_provider_model',
                            'value_field' => 'model_name',
                            'label_field' => 'display_name',
                            'filters' => [
                                'service_provider_id' => $serviceProvider->id,
                                'status' => 'active',
                                'supports_text_generation' => true,
                            ],
                            'fallback_options' => [
                                'gemini-2.5-flash',
                                'gemini-2.5-pro',
                                'gemini-2.0-flash',
                                'gemini-1.5-flash',
                                'gemini-1.5-pro',
                            ],
                        ],
                        'description' => 'The Gemini model to use for text generation',
                    ],
                    'prompt' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 1000,
                        'description' => 'The text prompt to generate content from',
                        'example' => 'Write a short story about a brave knight saving a village from a dragon.',
                        'validation' => 'required|string|min:1|max:1000',
                    ],
                    'max_tokens' => [
                        'type' => 'integer',
                        'required' => true,
                        'min' => 1,
                        'max' => 5000,
                        'description' => 'Maximum number of tokens to generate in the response',
                        'example' => 200,
                        'validation' => 'required|integer|min:1|max:5000',
                    ],
                    'temperature' => [
                        'type' => 'float',
                        'required' => true,
                        'min' => 0,
                        'max' => 1,
                        'description' => 'Controls randomness in the response generation',
                        'example' => 0.7,
                        'validation' => 'required|numeric|between:0,1',
                    ],
                ],
                'response' => [
                    'status' => 'success',
                    'data' => [
                        'text' => 'Once upon a time, in a faraway land...',
                    ],
                    'timestamp' => '2025-05-01T12:45:30+00:00',
                ],
                'response_path' => [
                    'success_indicator' => '$.status',
                    'main_data' => '$.data',
                    'final_result' => '$.data.text',
                    'timestamp' => '$.timestamp',
                ],
                'request_class_name' => TextGenerationRequest::class,
                'function_name' => 'textGeneration',
            ],
            [
                'name' => 'Code Generation',
                'description' => 'Generate code based on natural language descriptions with file attachments support',
                'input_parameters' => [
                    'model' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'gemini-1.5-pro',
                        'options' => [
                            'source' => 'collection',
                            'collection_name' => 'service_provider_model',
                            'value_field' => 'model_name',
                            'label_field' => 'display_name',
                            'filters' => [
                                'service_provider_id' => $serviceProvider->id,
                                'status' => 'active',
                                'supports_code_generation' => true,
                            ],
                            'fallback_options' => [
                                'gemini-2.5-flash',
                                'gemini-2.5-pro',
                                'gemini-2.0-flash',
                                'gemini-1.5-flash',
                                'gemini-1.5-pro',
                            ],
                        ],
                        'description' => 'The Gemini model to use for code generation',
                    ],
                    'prompt' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 1000,
                        'description' => 'Natural language description of the code to generate',
                        'example' => 'Give me simple html code',
                        'validation' => 'required|string|min:1|max:1000',
                    ],
                    'max_tokens' => [
                        'type' => 'integer',
                        'required' => true,
                        'min' => 1,
                        'max' => 5000,
                        'description' => 'Maximum number of tokens to generate in the response',
                        'example' => 200,
                        'validation' => 'required|integer|min:1|max:5000',
                    ],
                    'temperature' => [
                        'type' => 'float',
                        'required' => true,
                        'min' => 0,
                        'max' => 1,
                        'description' => 'Controls randomness in the response generation',
                        'example' => 0.7,
                        'validation' => 'required|numeric|between:0,1',
                    ],
                    'attachments' => [
                        'type' => 'array',
                        'required' => false,
                        'description' => 'Array of file attachments for context',
                        'example' => [],
                        'validation' => 'nullable|array',
                    ],
                ],
                'response' => [
                    'status' => 'success',
                    'data' => [
                        'code' => 'def factorial(n):\n if n == 0:\n return 1\n else:\n return n * factorial(n-1)',
                    ],
                    'timestamp' => '2025-05-01T12:45:30+00:00',
                ],
                'response_path' => [
                    'success_indicator' => '$.status',
                    'main_data' => '$.data',
                    'final_result' => '$.data.code',
                    'timestamp' => '$.timestamp',
                ],
                'request_class_name' => CodeGenerationRequest::class,
                'function_name' => 'codeGeneration',
            ],
            [
                'name' => 'Image Analysis',
                'description' => 'Analyze images and provide detailed descriptions or insights',
                'input_parameters' => [
                    'image_cid' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'Image string to analyze',
                        'example' => 'QmWhs8gKKZYBaQdSzFUXaAzubVz7B12HfomyBjpvKTPQ2N',
                        'validation' => 'required|string',
                    ],
                    'description_required' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 1000,
                        'description' => 'Specific description or analysis request for the image',
                        'example' => 'give me the content of the image',
                        'validation' => 'required|string|min:1|max:1000',
                    ],
                    'max_tokens' => [
                        'type' => 'integer',
                        'required' => true,
                        'min' => 1,
                        'max' => 5000,
                        'description' => 'Maximum number of tokens to generate in the response',
                        'example' => 200,
                        'validation' => 'required|integer|min:1|max:5000',
                    ],
                ],
                'response' => [
                    'status' => 'success',
                    'data' => [
                        'analysis' => 'The image is a painting of a landscape with a mountain in the background and a river flowing through it. The colors are warm and vibrant, and the painting is very detailed and realistic. The image is a beautiful representation of nature.',
                    ],
                    'timestamp' => '2025-05-01T12:45:30+00:00',
                ],
                'response_path' => [
                    'success_indicator' => '$.status',
                    'main_data' => '$.data',
                    'final_result' => '$.data.analysis',
                    'timestamp' => '$.timestamp',
                ],
                'request_class_name' => ImageAnalysisRequest::class,
                'function_name' => 'imageAnalysis',
            ],
            [
                'name' => 'Document Summarization',
                'description' => 'Summarize long document text into concise versions',
                'input_parameters' => [
                    'document_text' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 10,
                        'max_length' => 100000,
                        'description' => 'The document text content to be summarized',
                        'example' => 'Once upon a time, in a faraway land, there lived a brave knight named Sir Lancelot. He was known throughout the kingdom for his courage and strength. One day, a terrible dragon appeared and began terrorizing the nearby village. The villagers were frightened and begged Sir Lancelot to help them. Without hesitation, he donned his armor and set off to confront the dragon. After a fierce battle, Sir Lancelot emerged victorious, saving the village and earning the gratitude of its people.',
                        'validation' => 'required|string|min:10|max:100000',
                    ],
                    'model' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'gemini-1.5-pro',
                        'options' => [
                            'source' => 'collection',
                            'collection_name' => 'service_provider_model',
                            'value_field' => 'model_name',
                            'label_field' => 'display_name',
                            'filters' => [
                                'service_provider_id' => $serviceProvider->id,
                                'status' => 'active',
                                'supports_summarization' => true,
                            ],
                            'fallback_options' => [
                                'gemini-2.5-flash',
                                'gemini-2.5-pro',
                                'gemini-2.0-flash',
                                'gemini-1.5-flash',
                                'gemini-1.5-pro',
                            ],
                        ],
                        'description' => 'The Gemini model to use for document summarization',
                    ],
                    'summary_length' => [
                        'type' => 'integer',
                        'required' => true,
                        'min' => 1,
                        'max' => 1000,
                        'description' => 'Desired length of the summary in sentences',
                        'example' => 20,
                        'validation' => 'required|integer|min:1|max:1000',
                    ],
                    'max_tokens' => [
                        'type' => 'integer',
                        'required' => true,
                        'min' => 1,
                        'max' => 5000,
                        'description' => 'Maximum number of tokens to generate in the response',
                        'example' => 200,
                        'validation' => 'required|integer|min:1|max:5000',
                    ],
                ],
                'response' => [
                    'status' => 'success',
                    'data' => [
                        'summary' => 'The document is a short story about a young boy who discovers a magical portal to another world. The story is written in a simple and engaging style, and the characters are well-developed and relatable. The summary is a concise summary of the main points and key takeaways from the document.',
                    ],
                    'timestamp' => '2025-05-01T12:45:30+00:00',
                ],
                'response_path' => [
                    'success_indicator' => '$.status',
                    'main_data' => '$.data',
                    'final_result' => '$.data.summary',
                    'timestamp' => '$.timestamp',
                ],
                'request_class_name' => DocumentSummarizationRequest::class,
                'function_name' => 'documentSummarization',
            ],
        ];

        $keptServiceTypeIds = $this->processServiceTypes($serviceProvider, $serviceTypes, ServiceProviderEnum::GEMINI->value);

        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);

        $this->command->info("Cleanup completed:");
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info("- Kept " . count($keptServiceTypeIds) . " service types for Gemini API");
    }
}
