<?php

namespace Database\Seeders;

use App\Enums\common\ServiceProviderEnum;
use App\Http\Controllers\DeepSeekController;
use App\Http\Requests\DeepSeek\ChatCompletionRequest;
use App\Http\Requests\DeepSeek\CodeCompletionRequest;
use App\Http\Requests\DeepSeek\DocumentQaRequest;
use App\Http\Requests\DeepSeek\MathematicalReasoningRequest;
use App\Models\ServiceProvider;
use App\Traits\ServiceProviderSeederTrait;
use Illuminate\Database\Seeder;

class DeepSeekServiceProviderSeeder extends Seeder
{
    use ServiceProviderSeederTrait;

    /**
     * Run the database seeds.
     * 
     * This seeder creates a comprehensive DeepSeek service provider configuration
     * with all available service types, including detailed parameter specifications,
     * data types, validation rules, and examples for each service.
     */
    public function run(): void
    {
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => ServiceProviderEnum::DEEPSEEK->value],
            [
                'parameters' => [
                    'api_key' => 'YOUR_API_KEY',
                    'base_url' => 'https://api.deepseek.com/v1',
                    'version' => 'v1',
                    'models_supported' => [
                        'deepseek-chat',
                        'deepseek-coder',
                        'deepseek-math',
                    ],
                    'features' => [
                        'chat_completion',
                        'code_completion',
                        'document_qa',
                        'mathematical_reasoning',
                    ],
                    'documentation' => [
                        'description' => 'DeepSeek API provides advanced AI capabilities for chat completion, code generation, document Q&A, and mathematical reasoning',
                        'api_documentation' => 'https://platform.deepseek.com/api-docs',
                        'rate_limits' => 'Varies by model and plan',
                        'authentication' => 'API Key required in headers',
                    ],
                ],
                'is_active' => true,
                'controller_name' => DeepSeekController::class,
            ]
        );

        $serviceTypes = [
            [
                'name' => 'Chat Completion',
                'description' => 'Generate chat completions with conversation history support',
                'input_parameters' => [
                    'model' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'deepseek-chat',
                        'options' => [
                            'source' => 'collection',
                            'collection_name' => 'service_provider_model',
                            'value_field' => 'model_name',
                            'label_field' => 'display_name',
                            'filters' => [
                                'service_provider_id' => $serviceProvider->id,
                                'status' => 'active',
                                'supports_chat_completion' => true,
                            ],
                            'fallback_options' => [
                                'deepseek-chat',
                            ],
                        ],
                        'description' => 'The DeepSeek model to use for chat completion',
                        'example' => 'deepseek-chat',
                        'validation' => 'required|string|in:deepseek-chat',
                    ],
                    'messages' => [
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
                            'role' => 'string (system, user, assistant)',
                            'content' => 'string (message content)',
                        ],
                    ],
                    'max_tokens' => [
                        'type' => 'integer',
                        'required' => true,
                        'min' => 1,
                        'max' => 4000,
                        'description' => 'Maximum number of tokens to generate in the response',
                        'example' => 4000,
                        'validation' => 'required|integer|min:1|max:4000',
                    ],
                    'temperature' => [
                        'type' => 'float',
                        'required' => true,
                        'min' => 0,
                        'max' => 1,
                        'description' => 'Controls randomness in the response generation',
                        'example' => 0.7,
                        'validation' => 'required|numeric|min:0|max:1',
                    ],
                ],
                'response' => [
                    'status' => 'success',
                    'data' => [
                        'completion' => 'Once upon a time, in a faraway land...',
                    ],
                    'timestamp' => '2025-05-01T12:45:30+00:00',
                ],
                'response_path' => [
                    'success_indicator' => '$.status',
                    'main_data' => '$.data',
                    'final_result' => '$.data.completion',
                    'timestamp' => '$.timestamp',
                ],
                'request_class_name' => ChatCompletionRequest::class,
                'function_name' => 'chatCompletion',
            ],
            [
                'name' => 'Code Completion',
                'description' => 'Generate code based on natural language descriptions with file attachments support',
                'input_parameters' => [
                    'model' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'deepseek-chat',
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
                                'deepseek-chat',
                            ],
                        ],
                        'description' => 'The DeepSeek model to use for code completion',
                        'example' => 'deepseek-chat',
                        'validation' => 'required|string|in:deepseek-chat',
                    ],
                    'prompt' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 1000,
                        'description' => 'Natural language description of the code to generate',
                        'example' => 'Write a Python function to calculate the factorial of a number.',
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
                        'validation' => 'required|numeric|min:0|max:1',
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
                'request_class_name' => CodeCompletionRequest::class,
                'function_name' => 'codeCompletion',
            ],
            [
                'name' => 'Document Q&A',
                'description' => 'Answer questions based on provided document text',
                'input_parameters' => [
                    'document_text' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 10,
                        'max_length' => 100000,
                        'description' => 'The document text content to analyze',
                        'example' => 'COVID-19 is a respiratory disease caused by the SARS-CoV-2 virus. Common symptoms include fever, dry cough, and fatigue. Less common symptoms may include loss of taste or smell, aches and pains, headache, sore throat, nasal congestion, red eyes, diarrhea, or a skin rash. Severe symptoms include difficulty breathing or shortness of breath, chest pain or pressure, and loss of speech or movement. People of all ages who experience fever and/or cough associated with difficulty breathing or shortness of breath should seek medical attention immediately.',
                        'validation' => 'required|string|min:10|max:100000',
                    ],
                    'question' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 100000,
                        'description' => 'The question to be answered based on the document',
                        'example' => 'What are the main symptoms of COVID-19?',
                        'validation' => 'required|string|min:1|max:100000',
                    ],
                ],
                'response' => [
                    'status' => 'success',
                    'data' => [
                        'question' => 'What are the main symptoms of COVID-19?',
                        'answer' => 'The main symptoms of COVID-19 include fever, dry cough, and fatigue. Less common symptoms may include loss of taste or smell, aches and pains, headache, sore throat, nasal congestion, red eyes, diarrhea, or a skin rash.',
                    ],
                    'timestamp' => '2025-05-01T12:45:30+00:00',
                ],
                'response_path' => [
                    'success_indicator' => '$.status',
                    'main_data' => '$.data',
                    'final_result' => '$.data.answer',
                    'question' => '$.data.question',
                    'timestamp' => '$.timestamp',
                ],
                'request_class_name' => DocumentQaRequest::class,
                'function_name' => 'documentQa',
            ],
            [
                'name' => 'Mathematical Reasoning',
                'description' => 'Solve mathematical problems and provide step-by-step reasoning',
                'input_parameters' => [
                    'problem_statement' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 100000,
                        'description' => 'The mathematical problem to be solved',
                        'example' => 'If a car travels 60 miles in 1.5 hours, what is its average speed?',
                        'validation' => 'required|string|min:1|max:100000',
                        'supported_topics' => [
                            'algebra',
                            'calculus',
                            'geometry',
                            'statistics',
                            'arithmetic',
                            'word_problems',
                        ],
                    ],
                    'model' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'deepseek-chat',
                        'options' => [
                            'source' => 'collection',
                            'collection_name' => 'service_provider_model',
                            'value_field' => 'model_name',
                            'label_field' => 'display_name',
                            'filters' => [
                                'service_provider_id' => $serviceProvider->id,
                                'status' => 'active',
                                'supports_mathematical_reasoning' => true,
                            ],
                            'fallback_options' => [
                                'deepseek-chat',
                            ],
                        ],
                        'description' => 'The DeepSeek model to use for mathematical reasoning',
                        'example' => 'deepseek-chat',
                        'validation' => 'required|string|in:deepseek-chat',
                    ],
                    'max_tokens' => [
                        'type' => 'integer',
                        'required' => true,
                        'min' => 1,
                        'max' => 5000,
                        'description' => 'Maximum number of tokens to generate in the response',
                        'example' => 1024,
                        'validation' => 'required|integer|min:1|max:5000',
                    ],
                ],
                'response' => [
                    'status' => 'success',
                    'data' => [
                        'reasoning' => 'To find the average speed, I need to divide the total distance by the total time. Distance = 60 miles, Time = 1.5 hours. Average speed = 60 miles ÷ 1.5 hours = 40 miles per hour.',
                        'answer' => '40 miles per hour',
                    ],
                    'timestamp' => '2025-05-01T12:45:30+00:00',
                ],
                'response_path' => [
                    'success_indicator' => '$.status',
                    'main_data' => '$.data',
                    'final_result' => '$.data.reasoning',
                    'answer' => '$.data.answer',
                    'timestamp' => '$.timestamp',
                ],
                'request_class_name' => MathematicalReasoningRequest::class,
                'function_name' => 'mathematicalReasoning',
            ],
        ];

        $keptServiceTypeIds = $this->processServiceTypes($serviceProvider, $serviceTypes, ServiceProviderEnum::DEEPSEEK->value);

        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);
        
        $this->command->info("Cleanup completed:");
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info("- Kept " . count($keptServiceTypeIds) . " service types for DeepSeek API");
    }
} 