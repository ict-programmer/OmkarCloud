<?php

namespace Database\Seeders;

use App\Http\Controllers\QwenController;
use App\Http\Requests\Qwen\QwenChatbotRequest;
use App\Http\Requests\Qwen\QwenCodeGenerationRequest;
use App\Http\Requests\Qwen\QwenNLPRequest;
use App\Http\Requests\Qwen\QwenTextSummarizationRequest;
use App\Models\ServiceProvider;
use App\Models\ServiceType;
use App\Models\ServiceProviderType;
use Illuminate\Database\Seeder;

class QwenServiceProviderSeeder extends Seeder
{
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
            ['type' => 'Qwen'],
            [
                'parameters' => [
                    'api_key' => 'YOUR_API_KEY',
                    'base_url' => 'https://dashscope.aliyuncs.com',
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
                'parameter' => [
                    'model' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'The Qwen model to use for processing',
                        'example' => 'qwen/qwq-32b:free',
                        'validation' => 'required|string',
                        'supported_models' => [
                            'qwen/qwq-32b:free',
                            'qwen/qwq-72b:free',
                            'qwen/qwq-110b:free',
                            'qwen/qwq-32b:paid',
                            'qwen/qwq-72b:paid',
                            'qwen/qwq-110b:paid',
                        ],
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
                        'type' => 'number',
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
                'request_class_name' => QwenNLPRequest::class,
                'function_name' => 'nlp',
            ],
            [
                'name' => 'Code Generation',
                'description' => 'Generate code based on natural language descriptions with file attachments support',
                'parameter' => [
                    'model' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'The Qwen model to use for code generation',
                        'example' => 'qwen/qwq-32b:free',
                        'validation' => 'required|string',
                        'supported_models' => [
                            'qwen/qwq-32b:free',
                            'qwen/qwq-72b:free',
                            'qwen/qwq-110b:free',
                            'qwen/qwq-32b:paid',
                            'qwen/qwq-72b:paid',
                            'qwen/qwq-110b:paid',
                        ],
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
                        'type' => 'number',
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
                        'validation' => 'nullable|array',
                        'file_constraints' => [
                            'max_size' => '30MB per file',
                            'allowed_types' => 'Text, PDF, CSV, Images',
                        ],
                    ],
                ],
                'request_class_name' => QwenCodeGenerationRequest::class,
                'function_name' => 'codeGeneration',
            ],
            [
                'name' => 'Text Summarization',
                'description' => 'Summarize long text content into concise versions',
                'parameter' => [
                    'model' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'The Qwen model to use for summarization',
                        'example' => 'qwen/qwq-32b:free',
                        'validation' => 'required|string',
                        'supported_models' => [
                            'qwen/qwq-32b:free',
                            'qwen/qwq-72b:free',
                            'qwen/qwq-110b:free',
                            'qwen/qwq-32b:paid',
                            'qwen/qwq-72b:paid',
                            'qwen/qwq-110b:paid',
                        ],
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
                        'type' => 'number',
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
                'request_class_name' => QwenTextSummarizationRequest::class,
                'function_name' => 'textSummarization',
            ],
            [
                'name' => 'Chatbot',
                'description' => 'Interactive chatbot with conversation history support',
                'parameter' => [
                    'model' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'The Qwen model to use for chatbot interactions',
                        'example' => 'qwen/qwq-32b:free',
                        'validation' => 'required|string',
                        'supported_models' => [
                            'qwen/qwq-32b:free',
                            'qwen/qwq-72b:free',
                            'qwen/qwq-110b:free',
                            'qwen/qwq-32b:paid',
                            'qwen/qwq-72b:paid',
                            'qwen/qwq-110b:paid',
                        ],
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
                        'type' => 'number',
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
                'request_class_name' => QwenChatbotRequest::class,
                'function_name' => 'chatbot',
            ],
        ];

        $existingServiceTypes = ServiceType::whereIn('name', collect($serviceTypes)->pluck('name'))->get();
        
        $existingServiceProviderTypes = ServiceProviderType::where('service_provider_id', $serviceProvider->id)
            ->with('serviceType')
            ->get()
            ->keyBy('service_type_id');

        $keptServiceTypeIds = [];

        foreach ($serviceTypes as $serviceTypeData) {
            $serviceTypeName = $serviceTypeData['name'];
            
            $existingServiceType = $existingServiceTypes->where('name', $serviceTypeName)->first();
            $existingProviderType = $existingServiceProviderTypes->where('serviceType.name', $serviceTypeName)->first();
            
            if ($existingServiceType && !$existingProviderType) {
                $uniqueName = $serviceTypeName . ' (Qwen)';
                $counter = 1;
                while (ServiceType::where('name', $uniqueName)->exists()) {
                    $uniqueName = $serviceTypeName . ' (Qwen ' . $counter . ')';
                    $counter++;
                }
                
                $newServiceType = ServiceType::create([
                    'name' => $uniqueName,
                    'description' => $serviceTypeData['description'],
                    'request_class_name' => $serviceTypeData['request_class_name'],
                    'function_name' => $serviceTypeData['function_name'],
                ]);
                
                ServiceProviderType::create([
                    'service_provider_id' => $serviceProvider->id,
                    'service_type_id' => $newServiceType->id,
                    'parameter' => $serviceTypeData['parameter'],
                ]);
                
                $keptServiceTypeIds[] = $newServiceType->id;
                $this->command->info("Created new service type '{$uniqueName}' to avoid conflict with existing '{$serviceTypeName}'");
                
            } elseif ($existingProviderType) {
                $serviceType = $existingProviderType->serviceType;
                $serviceType->update([
                    'description' => $serviceTypeData['description'],
                    'request_class_name' => $serviceTypeData['request_class_name'],
                    'function_name' => $serviceTypeData['function_name'],
                ]);
                
                $existingProviderType->update([
                    'parameter' => $serviceTypeData['parameter'],
                ]);
                
                $keptServiceTypeIds[] = $serviceType->id;
                $this->command->info("Updated existing service type '{$serviceTypeName}' for Qwen");
                
            } else {
                $newServiceType = ServiceType::create([
                    'name' => $serviceTypeName,
                    'description' => $serviceTypeData['description'],
                    'request_class_name' => $serviceTypeData['request_class_name'],
                    'function_name' => $serviceTypeData['function_name'],
                ]);
                
                ServiceProviderType::create([
                    'service_provider_id' => $serviceProvider->id,
                    'service_type_id' => $newServiceType->id,
                    'parameter' => $serviceTypeData['parameter'],
                ]);
                
                $keptServiceTypeIds[] = $newServiceType->id;
                $this->command->info("Created new service type '{$serviceTypeName}' for Qwen");
            }
        }

        $serviceTypeIdsToKeep = collect($keptServiceTypeIds)->unique()->toArray();
        
        $allQwenServiceProviderTypes = ServiceProviderType::where('service_provider_id', $serviceProvider->id)->get();
        
        $serviceProviderTypesToDelete = $allQwenServiceProviderTypes->filter(function ($providerType) use ($serviceTypeIdsToKeep) {
            return !in_array($providerType->service_type_id, $serviceTypeIdsToKeep);
        });
        
        $deletedProviderTypeCount = 0;
        foreach ($serviceProviderTypesToDelete as $providerTypeToDelete) {
            $providerTypeToDelete->delete();
            $deletedProviderTypeCount++;
        }
        
        $this->command->info("Cleanup completed:");
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info("- Kept " . count($keptServiceTypeIds) . " service types for Qwen");
    }
} 