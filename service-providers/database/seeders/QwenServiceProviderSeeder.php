<?php

namespace Database\Seeders;

use App\Enums\common\ServiceProviderEnum;
use App\Http\Controllers\QwenController;
use App\Http\Requests\Qwen\QwenChatbotRequest;
use App\Http\Requests\Qwen\QwenCodeGenerationRequest;
use App\Http\Requests\Qwen\QwenNLPRequest;
use App\Http\Requests\Qwen\QwenTextSummarizationRequest;
use App\Models\ServiceProvider;
use App\Traits\ServiceProviderSeederTrait;
use Illuminate\Database\Seeder;

class QwenServiceProviderSeeder extends Seeder
{
    use ServiceProviderSeederTrait;

    /**
     * Run the database seeds.
     * 
     * This seeder creates a comprehensive Qwen service provider configuration
     * with all available service types, including detailed parameter specifications,
     * data types, validation rules, and examples for each service.
     */
    public function run(): void
    {
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => ServiceProviderEnum::QWEN->value],
            [
                'parameters' => [
                    'api_key' => 'YOUR_API_KEY',
                    'base_url' => 'https://openrouter.ai/api/v1',
                    'version' => 'v1',
                    'models_supported' => [
                        'qwen/qwq-32b:free',
                        'qwen/qwq-72b:free',
                        'qwen/qwq-110b:free',
                        'qwen/qwq-32b:paid',
                        'qwen/qwq-72b:paid',
                        'qwen/qwq-110b:paid',
                    ],
                    'features' => [
                        'nlp',
                        'code_generation',
                        'text_summarization',
                        'chatbot',
                    ],
                    'documentation' => [
                        'description' => 'Qwen API provides advanced AI capabilities for natural language processing, code generation, and text summarization',
                        'api_documentation' => 'https://help.aliyun.com/zh/dashscope/developer-reference/api-details',
                        'rate_limits' => 'Varies by model and plan',
                        'authentication' => 'API Key required in headers',
                    ],
                ],
                'is_active' => true,
                'controller_name' => QwenController::class,
            ]
        );

        $serviceTypes = [
            [
                'name' => 'NLP',
                'description' => 'Natural Language Processing for text generation and analysis',
                'input_parameters' => [
                    'model' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'qwen/qwq-32b:free',
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
                                'qwen/qwq-32b:free',
                                'qwen/qwq-72b:free',
                                'qwen/qwq-110b:free',
                            ],
                        ],
                        'description' => 'Qwen model to use for code generation',
                    ],
                    'prompt' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 10000,
                        'description' => 'The text prompt to process',
                        'example' => 'What is the capital of France?',
                        'validation' => 'required|string|min:1|max:10000',
                    ],
                    'max_tokens' => [
                        'type' => 'integer',
                        'required' => false,
                        'min' => 1,
                        'max' => 2000,
                        'description' => 'Maximum number of tokens to generate in the response',
                        'example' => 2000,
                        'validation' => 'nullable|integer|min:1|max:2000',
                    ],
                    'temperature' => [
                        'type' => 'float',
                        'required' => false,
                        'min' => 0,
                        'max' => 1,
                        'description' => 'Controls randomness in the response generation',
                        'example' => 0.7,
                        'validation' => 'nullable|numeric|min:0|max:1',
                    ],
                    'endpoint_interface' => [
                        'type' => 'string',
                        'required' => false,
                        'description' => 'The endpoint interface to use',
                        'example' => 'generate',
                        'validation' => 'nullable|string',
                    ],
                ],
                'response' => [
                    'id' => 'qwen_response_id',
                    'object' => 'qwen_response_object',
                    'created' => 1234567890,
                    'model' => 'qwen/qwq-32b:free',
                    'choices' => [
                        [
                            'index' => 0,
                            'message' => [
                                'role' => 'assistant',
                                'content' => 'The capital of France is Paris.',
                            ],
                            'finish_reason' => 'stop',
                        ],
                    ],
                    'usage' => [
                        'prompt_tokens' => 10,
                        'completion_tokens' => 20,
                        'total_tokens' => 30,
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.choices[0].message.content',
                    'model_used' => '$.model',
                    'tokens_used' => '$.usage.total_tokens',
                    'finish_reason' => '$.choices[0].finish_reason',
                ],
                'request_class_name' => QwenNLPRequest::class,
                'function_name' => 'nlp',
            ],
            [
                'name' => 'Code Generation',
                'input_parameters' => [
                    'model' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'qwen/qwq-32b:free',
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
                                'qwen/qwq-32b:free',
                                'qwen/qwq-72b:free',
                                'qwen/qwq-110b:free',
                            ],
                        ],
                        'description' => 'Qwen model to use for code generation',
                    ],
                    'prompt' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 10000,
                        'description' => 'Natural language description of the code to generate',
                        'example' => 'Write a Python function to calculate the factorial of a number.',
                        'validation' => 'required|string|min:1|max:10000',
                    ],
                    'max_tokens' => [
                        'type' => 'integer',
                        'required' => false,
                        'min' => 1,
                        'max' => 2000,
                        'description' => 'Maximum number of tokens to generate in the response',
                        'example' => 2000,
                        'validation' => 'nullable|integer|min:1|max:2000',
                    ],
                    'temperature' => [
                        'type' => 'float',
                        'required' => false,
                        'min' => 0,
                        'max' => 1,
                        'description' => 'Controls randomness in the response generation',
                        'example' => 0.7,
                        'validation' => 'nullable|numeric|min:0|max:1',
                    ],
                    'endpoint_interface' => [
                        'type' => 'string',
                        'required' => false,
                        'description' => 'The endpoint interface to use',
                        'example' => 'generate',
                        'validation' => 'nullable|string',
                    ],
                    'attachments' => [
                        'type' => 'array',
                        'required' => false,
                        'description' => 'Array of file attachments for context',
                        'example' => [],
                        'validation' => 'nullable|array'
                    ],
                ],
                'response' => [
                    'id' => 'qwen_response_id',
                    'object' => 'qwen_response_object',
                    'created' => 1234567890,
                    'model' => 'qwen/qwq-32b:free',
                    'choices' => [
                        [
                            'index' => 0,
                            'message' => [
                                'role' => 'assistant',
                                'content' => 'def factorial(n):\n    if n == 0:\n        return 1\n    else:\n        return n * factorial(n-1)',
                            ],
                            'finish_reason' => 'stop',
                        ],
                    ],
                    'usage' => [
                        'prompt_tokens' => 10,
                        'completion_tokens' => 20,
                        'total_tokens' => 30,
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.choices[0].message.content',
                    'model_used' => '$.model',
                    'tokens_used' => '$.usage.total_tokens',
                    'finish_reason' => '$.choices[0].finish_reason',
                ],
                'request_class_name' => QwenCodeGenerationRequest::class,
                'function_name' => 'codeGeneration',
            ],
            [
                'name' => 'Text Summarization',
                'input_parameters' => [
                    'model' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'qwen/qwq-32b:free',
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
                                'qwen/qwq-32b:free',
                                'qwen/qwq-72b:free',
                                'qwen/qwq-110b:free',
                            ],
                        ],
                        'description' => 'Qwen model to use for text summarization',
                    ],
                    'text' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 10,
                        'max_length' => 10000,
                        'description' => 'The text content to be summarized',
                        'example' => 'Once upon a time, in a faraway land, there lived a brave knight named Sir Lancelot. He was known throughout the kingdom for his courage and strength. One day, a terrible dragon appeared and began terrorizing the nearby village. The villagers were frightened and begged Sir Lancelot to help them. Without hesitation, he donned his armor and set off to confront the dragon. After a fierce battle, Sir Lancelot emerged victorious, saving the village and earning the gratitude of its people.',
                        'validation' => 'required|string|min:10|max:10000',
                    ],
                    'text_length' => [
                        'type' => 'integer',
                        'required' => false,
                        'min' => 1,
                        'max' => 1000,
                        'description' => 'Desired length of the summary in words',
                        'example' => 200,
                        'validation' => 'nullable|integer|min:1|max:1000',
                    ],
                    'max_tokens' => [
                        'type' => 'integer',
                        'required' => false,
                        'min' => 1,
                        'max' => 2000,
                        'description' => 'Maximum number of tokens to generate in the response',
                        'example' => 2000,
                        'validation' => 'nullable|integer|min:1|max:2000',
                    ],
                    'temperature' => [
                        'type' => 'float',
                        'required' => false,
                        'min' => 0,
                        'max' => 1,
                        'description' => 'Controls randomness in the response generation',
                        'example' => 0.7,
                        'validation' => 'nullable|numeric|min:0|max:1',
                    ],
                    'endpoint_interface' => [
                        'type' => 'string',
                        'required' => false,
                        'description' => 'The endpoint interface to use',
                        'example' => 'generate',
                        'validation' => 'nullable|string',
                    ],
                ],
                'response' => [
                    'id' => 'qwen_response_id',
                    'object' => 'qwen_response_object',
                    'created' => 1234567890,
                    'model' => 'qwen/qwq-32b:free',
                    'choices' => [
                        [
                            'index' => 0,
                            'message' => [
                                'role' => 'assistant',
                                'content' => 'A brave knight named Sir Lancelot saves a village from a dragon, earning the villagers\' gratitude.',
                            ],
                            'finish_reason' => 'stop',
                        ],
                    ],
                    'usage' => [
                        'prompt_tokens' => 10,
                        'completion_tokens' => 20,
                        'total_tokens' => 30,
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.choices[0].message.content',
                    'model_used' => '$.model',
                    'tokens_used' => '$.usage.total_tokens',
                    'finish_reason' => '$.choices[0].finish_reason',
                ],
                'request_class_name' => QwenTextSummarizationRequest::class,
                'function_name' => 'textSummarization',
            ],
            [
                'name' => 'Chatbot',
                'input_parameters' => [
                    'model' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'qwen/qwq-32b:free',
                        'options' => [
                            'source' => 'collection',
                            'collection_name' => 'service_provider_model',
                            'value_field' => 'model_name',
                            'label_field' => 'display_name',
                            'filters' => [
                                'service_provider_id' => $serviceProvider->id,
                                'status' => 'active',
                                'supports_chatbot' => true,
                            ],
                            'fallback_options' => [
                                'qwen/qwq-32b:free',
                                'qwen/qwq-72b:free',
                                'qwen/qwq-110b:free',
                            ],
                        ],
                        'description' => 'Qwen model to use for chatbot interactions',
                    ],
                    'conversation_history' => [
                        'type' => 'array',
                        'required' => true,
                        'min_items' => 1,
                        'max_items' => 50,
                        'description' => 'Array of conversation messages with role and content',
                        'example' => [
                            ['role' => 'user', 'content' => 'Hello, how are you?'],
                            ['role' => 'assistant', 'content' => 'I am doing well, thank you!'],
                        ],
                        'validation' => 'required|array|min:1|max:50',
                        'structure' => [
                            'role' => 'string (user, assistant, system)',
                            'content' => 'string (message content)',
                        ],
                    ],
                    'temperature' => [
                        'type' => 'float',
                        'required' => false,
                        'min' => 0,
                        'max' => 1,
                        'description' => 'Controls randomness in the response generation',
                        'example' => 0.7,
                        'validation' => 'nullable|numeric|min:0|max:1',
                    ],
                    'max_tokens' => [
                        'type' => 'integer',
                        'required' => false,
                        'min' => 1,
                        'max' => 4000,
                        'description' => 'Maximum number of tokens to generate in the response',
                        'example' => 4000,
                        'validation' => 'nullable|integer|min:1|max:4000',
                    ],
                    'endpoint_interface' => [
                        'type' => 'string',
                        'required' => false,
                        'description' => 'The endpoint interface to use',
                        'example' => 'generate',
                        'validation' => 'nullable|string',
                    ],
                ],
                'response' => [
                    'id' => 'qwen_response_id',
                    'object' => 'qwen_response_object',
                    'created' => 1234567890,
                    'model' => 'qwen/qwq-32b:free',
                    'choices' => [
                        [
                            'index' => 0,
                            'message' => [
                                'role' => 'assistant',
                                'content' => 'Hello! I am doing well, thank you!',
                            ],
                            'finish_reason' => 'stop',
                        ],
                    ],
                    'usage' => [
                        'prompt_tokens' => 10,
                        'completion_tokens' => 20,
                        'total_tokens' => 30,
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.choices[0].message.content',
                    'model_used' => '$.model',
                    'tokens_used' => '$.usage.total_tokens',
                    'finish_reason' => '$.choices[0].finish_reason',
                ],
                'request_class_name' => QwenChatbotRequest::class,
                'function_name' => 'chatbot',
            ],
        ];

        $keptServiceTypeIds = $this->processServiceTypes($serviceProvider, $serviceTypes, ServiceProviderEnum::QWEN->value);

        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);
        
        $this->command->info("Cleanup completed:");
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info("- Kept " . count($keptServiceTypeIds) . " service types for Qwen API");
    }
}
