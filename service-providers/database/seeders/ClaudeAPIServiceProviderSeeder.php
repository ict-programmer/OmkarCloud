<?php

namespace Database\Seeders;

use App\Http\Controllers\ClaudeAPIController;
use App\Http\Requests\ClaudeAPI\CodegenRequest;
use App\Http\Requests\ClaudeAPI\DataAnalysisInsightRequest;
use App\Http\Requests\ClaudeAPI\PersonalizationRequest;
use App\Http\Requests\ClaudeAPI\QuestionAnswerRequest;
use App\Http\Requests\ClaudeAPI\TextClassifyRequest;
use App\Http\Requests\ClaudeAPI\TextGenerationRequest;
use App\Http\Requests\ClaudeAPI\TextSummarizeRequest;
use App\Http\Requests\ClaudeAPI\TextTranslateRequest;
use App\Models\ServiceProvider;
use App\Models\ServiceType;
use App\Models\ServiceProviderType;
use Illuminate\Database\Seeder;

class ClaudeAPIServiceProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder creates a comprehensive Claude API service provider configuration
     * with all available service types, including detailed parameter specifications,
     * data types, validation rules, and examples for each service.
     */
    public function run(): void
    {
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => 'Claude API'],
            [
                'parameters' => [
                    'api_key' => 'YOUR_API_KEY',
                    'base_url' => 'https://api.anthropic.com',
                    'version' => 'v1',
                    'models_supported' => [
                        'claude-3-5-haiku-20241022',
                        'claude-3-5-sonnet-20241022',
                        'claude-3-5-opus-20241022',
                        'claude-3-haiku-20240307',
                        'claude-3-sonnet-20240229',
                        'claude-3-opus-20240229',
                    ],
                    'features' => [
                        'text_generation',
                        'text_summarization',
                        'question_answering',
                        'text_classification',
                        'text_translation',
                        'code_generation',
                        'data_analysis_insights',
                        'personalization',
                    ],
                    'documentation' => [
                        'description' => 'Claude API provides advanced AI capabilities for text generation, analysis, and processing',
                        'api_documentation' => 'https://docs.anthropic.com/claude/reference',
                        'rate_limits' => 'Varies by model and plan',
                        'authentication' => 'API Key required in headers',
                    ],
                ],
                'is_active' => true,
                'controller_name' => ClaudeAPIController::class,
            ]
        );

        $serviceTypes = [
            [
                'name' => 'Text Generation',
                'description' => 'Generate creative and informative text based on prompts',
                'parameter' => [
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
                ],
                'request_class_name' => TextGenerationRequest::class,
                'function_name' => 'textGeneration',
            ],
            [
                'name' => 'Text Summarization',
                'description' => 'Summarize long text content into concise versions',
                'parameter' => [
                    'text' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 10,
                        'max_length' => 10000,
                        'description' => 'The text content to be summarized',
                        'example' => 'Once upon a time, in a faraway land, there lived a brave knight named Sir Lancelot. He was known throughout the kingdom for his courage and strength. One day, a terrible dragon appeared and began terrorizing the nearby village. The villagers were frightened and begged Sir Lancelot to help them. Without hesitation, he donned his armor and set off to confront the dragon. After a fierce battle, Sir Lancelot emerged victorious, saving the village and earning the gratitude of its people.',
                        'validation' => 'required|string|min:10|max:10000',
                    ],
                    'summary_length' => [
                        'type' => 'string',
                        'required' => true,
                        'enum' => ['short', 'medium', 'long'],
                        'description' => 'Desired length of the summary',
                        'example' => 'short',
                        'validation' => 'required|string|in:short,medium,long',
                    ],
                ],
                'request_class_name' => TextSummarizeRequest::class,
                'function_name' => 'textSummarize',
            ],
            [
                'name' => 'Question Answering',
                'description' => 'Answer questions based on provided context information',
                'parameter' => [
                    'question' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 3,
                        'max_length' => 1000,
                        'description' => 'The question to be answered',
                        'example' => 'What are the main symptoms of COVID-19?',
                        'validation' => 'required|string|min:3|max:1000',
                    ],
                    'context' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 10,
                        'max_length' => 10000,
                        'description' => 'Contextual information to help answer the question',
                        'example' => 'COVID-19 is a respiratory disease caused by the SARS-CoV-2 virus. Common symptoms include fever, dry cough, and fatigue. Less common symptoms may include loss of taste or smell, aches and pains, headache, sore throat, nasal congestion, red eyes, diarrhea, or a skin rash. Severe symptoms include difficulty breathing or shortness of breath, chest pain or pressure, and loss of speech or movement. People of all ages who experience fever and/or cough associated with difficulty breathing or shortness of breath should seek medical attention immediately.',
                        'validation' => 'required|string|min:10|max:10000',
                    ],
                ],
                'request_class_name' => QuestionAnswerRequest::class,
                'function_name' => 'questionAnswer',
            ],
            [
                'name' => 'Text Classification',
                'description' => 'Classify text into predefined categories or genres',
                'parameter' => [
                    'text' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 5,
                        'max_length' => 5000,
                        'description' => 'The text content to be classified',
                        'example' => 'This is a story about a brave knight who saved a village from a dragon.',
                        'validation' => 'required|string|min:5|max:5000',
                    ],
                    'categories' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 3,
                        'max_length' => 500,
                        'description' => 'Comma-separated list of categories to classify the text into',
                        'example' => 'Adventure, Fantasy, Drama',
                        'validation' => 'required|string|min:3|max:500',
                    ],
                ],
                'request_class_name' => TextClassifyRequest::class,
                'function_name' => 'textClassify',
            ],
            [
                'name' => 'Text Translation',
                'description' => 'Translate text between different languages',
                'parameter' => [
                    'text' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 5000,
                        'description' => 'The text to be translated',
                        'example' => 'Hello, how are you?',
                        'validation' => 'required|string|min:1|max:5000',
                    ],
                    'source_language' => [
                        'type' => 'string',
                        'required' => true,
                        'size' => 2,
                        'pattern' => '/^[a-z]{2}$/',
                        'description' => 'Source language code (ISO 639-1 format)',
                        'example' => 'en',
                        'validation' => 'required|string|size:2|regex:/^[a-z]{2}$/',
                        'supported_languages' => ['en', 'es', 'fr', 'de', 'it', 'pt', 'ja', 'ko', 'zh', 'ar', 'hi', 'ru'],
                    ],
                    'target_language' => [
                        'type' => 'string',
                        'required' => true,
                        'size' => 2,
                        'pattern' => '/^[a-z]{2}$/',
                        'description' => 'Target language code (ISO 639-1 format)',
                        'example' => 'es',
                        'validation' => 'required|string|size:2|regex:/^[a-z]{2}$/',
                        'supported_languages' => ['en', 'es', 'fr', 'de', 'it', 'pt', 'ja', 'ko', 'zh', 'ar', 'hi', 'ru'],
                    ],
                ],
                'request_class_name' => TextTranslateRequest::class,
                'function_name' => 'textTranslate',
            ],
            [
                'name' => 'Code Generation',
                'description' => 'Generate code based on natural language descriptions',
                'parameter' => [
                    'description' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 5,
                        'max_length' => 2000,
                        'description' => 'Natural language description of the code to generate',
                        'example' => 'Write a Python function to calculate the factorial of a number.',
                        'validation' => 'required|string|min:5|max:2000',
                    ],
                    'attachments' => [
                        'type' => 'array',
                        'required' => false,
                        'description' => 'Array of file attachments (optional)',
                        'example' => [],
                        'validation' => 'nullable|array',
                        'file_constraints' => [
                            'max_size' => '30MB per file',
                            'allowed_types' => 'Any file type',
                        ],
                    ],
                ],
                'request_class_name' => CodegenRequest::class,
                'function_name' => 'codegen',
            ],
            [
                'name' => 'Data Analysis and Insights',
                'description' => 'Analyze data and provide insights based on specified tasks',
                'parameter' => [
                    'data' => [
                        'type' => 'array',
                        'required' => true,
                        'min_items' => 1,
                        'max_items' => 1000,
                        'description' => 'Array of data objects to analyze',
                        'example' => [
                            ['name' => 'Alice', 'age' => 30, 'score' => 85],
                            ['name' => 'Bob', 'age' => 25, 'score' => 90],
                        ],
                        'validation' => 'required|array|min:1|max:1000',
                        'structure' => 'Array of objects with consistent keys',
                    ],
                    'task' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 3,
                        'max_length' => 100,
                        'description' => 'The analysis task to perform on the data',
                        'example' => 'average_score',
                        'validation' => 'required|string|min:3|max:100',
                        'common_tasks' => [
                            'average_score',
                            'find_correlation',
                            'identify_trends',
                            'statistical_summary',
                            'data_visualization_suggestions',
                        ],
                    ],
                ],
                'request_class_name' => DataAnalysisInsightRequest::class,
                'function_name' => 'dataAnalysisAndInsight',
            ],
            [
                'name' => 'Personalization',
                'description' => 'Personalize content based on user preferences and characteristics',
                'parameter' => [
                    'user_id' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 100,
                        'description' => 'Unique identifier for the user',
                        'example' => '12345',
                        'validation' => 'required|string|min:1|max:100',
                    ],
                    'preferences' => [
                        'type' => 'array',
                        'required' => true,
                        'min_items' => 1,
                        'max_items' => 20,
                        'description' => 'Array of user preferences or interests',
                        'example' => ['technology', 'science'],
                        'validation' => 'required|array|min:1|max:20',
                        'array_type' => 'string',
                        'common_preferences' => [
                            'technology', 'science', 'sports', 'music', 'art',
                            'literature', 'travel', 'food', 'fitness', 'business',
                        ],
                    ],
                ],
                'request_class_name' => PersonalizationRequest::class,
                'function_name' => 'personalize',
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
                $uniqueName = $serviceTypeName . ' (Claude API)';
                $counter = 1;
                while (ServiceType::where('name', $uniqueName)->exists()) {
                    $uniqueName = $serviceTypeName . ' (Claude API ' . $counter . ')';
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
                $this->command->info("Updated existing service type '{$serviceTypeName}' for Claude API");
                
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
                $this->command->info("Created new service type '{$serviceTypeName}' for Claude API");
            }
        }

        $serviceTypeIdsToKeep = collect($keptServiceTypeIds)->unique()->toArray();
        
        $allClaudeServiceProviderTypes = ServiceProviderType::where('service_provider_id', $serviceProvider->id)->get();
        
        $serviceProviderTypesToDelete = $allClaudeServiceProviderTypes->filter(function ($providerType) use ($serviceTypeIdsToKeep) {
            return !in_array($providerType->service_type_id, $serviceTypeIdsToKeep);
        });
        
        $deletedProviderTypeCount = 0;
        foreach ($serviceProviderTypesToDelete as $providerTypeToDelete) {
            $providerTypeToDelete->delete();
            $deletedProviderTypeCount++;
        }
        
        $this->command->info("Cleanup completed:");
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info("- Kept " . count($keptServiceTypeIds) . " service types for Claude API");
    }
} 