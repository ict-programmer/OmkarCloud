<?php

namespace Database\Seeders;

use App\Enums\common\ServiceTypeEnum;
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
use App\Traits\ServiceProviderSeederTrait;
use Illuminate\Database\Seeder;

class ClaudeAPIServiceProviderSeeder extends Seeder
{
    use ServiceProviderSeederTrait;

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
            ['type' => 'Claude'],
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
                'name' => ServiceTypeEnum::TEXT_GENERATION_SERVICE->value,
                'input_parameters' => [
                    'model' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'claude-3-5-haiku-20241022',
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
                                'claude-3-5-haiku-20241022',
                                'claude-3-5-sonnet-20241022',
                                'claude-3-5-opus-20241022',
                            ],
                        ],
                        'description' => 'Claude model to use for text generation',
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
                ],
                'response' => [
                    'status' => true,
                    'data' => [
                        'response' => 'Once upon a time, in a faraway land, there lived a brave knight named Sir Lancelot. He was known throughout the kingdom for his courage and strength. One day, a terrible dragon appeared and began terrorizing the nearby village. The villagers were frightened and begged Sir Lancelot to help them. Without hesitation, he donned his armor and set off to confront the dragon. After a fierce battle, Sir Lancelot emerged victorious, saving the village and earning the gratitude of its people.',
                        'model' => 'claude-3-5-haiku-20241022',
                        'tokens_used' => 150,
                        'error' => null,
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.data.response',
                    'model_used' => '$.data.model',
                    'tokens_used' => '$.data.tokens_used',
                    'error_message' => '$.data.error',
                ],
                'request_class_name' => TextGenerationRequest::class,
                'function_name' => 'textGeneration',
            ],
            [
                'name' => ServiceTypeEnum::TEXT_SUMMARIZATION_SERVICE->value,
                'input_parameters' => [
                    'model' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'claude-3-5-haiku-20241022',
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
                                'claude-3-5-haiku-20241022',
                                'claude-3-5-sonnet-20241022',
                            ],
                        ],
                        'description' => 'Claude model to use for text summarization',
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
                    'summary_length' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'short',
                        'options' => ['short', 'medium', 'long'],
                        'description' => 'Desired length of the summary',
                        'example' => 'short',
                        'validation' => 'required|string|in:short,medium,long',
                    ],
                ],
                'response' => [
                    'status' => true,
                    'data' => [
                        'text' => 'Once upon a time, in a faraway land, there lived a brave knight named Sir Lancelot. He was known throughout the kingdom for his courage and strength. One day, a terrible dragon appeared and began terrorizing the nearby village. The villagers were frightened and begged Sir Lancelot to help them. Without hesitation, he donned his armor and set off to confront the dragon. After a fierce battle, Sir Lancelot emerged victorious, saving the village and earning the gratitude of its people.',
                        'summary_length' => 'short',
                        'summary' => 'A brave knight named Sir Lancelot saves a village from a dragon, earning the villagers\' gratitude.',
                        'model' => 'claude-3-5-haiku-20241022',
                        'error' => null,
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.data.summary',
                    'original_text' => '$.data.text',
                    'summary_length' => '$.data.summary_length',
                    'model_used' => '$.data.model',
                    'error_message' => '$.data.error',
                ],
                'request_class_name' => TextSummarizeRequest::class,
                'function_name' => 'textSummarize',
            ],
            [
                'name' => ServiceTypeEnum::QUESTION_ANSWERING_SERVICE->value,
                'input_parameters' => [
                    'model' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'claude-3-5-sonnet-20241022',
                        'options' => [
                            'source' => 'collection',
                            'collection_name' => 'service_provider_model',
                            'value_field' => 'model_name',
                            'label_field' => 'display_name',
                            'filters' => [
                                'service_provider_id' => $serviceProvider->id,
                                'status' => 'active',
                                'supports_question_answering' => true,
                            ],
                            'fallback_options' => [
                                'claude-3-5-sonnet-20241022',
                                'claude-3-5-opus-20241022',
                            ],
                        ],
                        'description' => 'Claude model to use for question answering',
                    ],
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
                'response' => [
                    'status' => true,
                    'data' => [
                        'answer' => 'The moral of the story is that courage and selflessness can overcome even the greatest challenges. Sir Lancelot\'s bravery in facing the dragon and his willingness to help the villagers demonstrate the importance of standing up for others in times of need.',
                        'question' => 'What is the moral of this story?',
                        'context' => 'Once upon a time, in a faraway land, there lived a brave knight named Sir Lancelot...',
                        'model' => 'claude-3-5-sonnet-20241022',
                        'error' => null,
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.data.answer',
                    'question' => '$.data.question',
                    'context' => '$.data.context',
                    'model_used' => '$.data.model',
                    'error_message' => '$.data.error',
                ],
                'request_class_name' => QuestionAnswerRequest::class,
                'function_name' => 'questionAnswer',
            ],
            [
                'name' => ServiceTypeEnum::TEXT_CLASSIFICATION_SERVICE->value,
                'input_parameters' => [
                    'model' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'claude-3-5-haiku-20241022',
                        'options' => [
                            'source' => 'collection',
                            'collection_name' => 'service_provider_model',
                            'value_field' => 'model_name',
                            'label_field' => 'display_name',
                            'filters' => [
                                'service_provider_id' => $serviceProvider->id,
                                'status' => 'active',
                                'supports_classification' => true,
                            ],
                            'fallback_options' => [
                                'claude-3-5-haiku-20241022',
                                'claude-3-5-sonnet-20241022',
                            ],
                        ],
                        'description' => 'Claude model to use for text classification',
                    ],
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
                'response' => [
                    'status' => true,
                    'data' => [
                        'sentiment' => 'positive',
                        'category' => 'Adventure',
                        'text' => 'This is a story about a brave knight who saved a village from a dragon.',
                        'categories' => 'Adventure, Fantasy, Drama',
                        'model' => 'claude-3-5-haiku-20241022',
                        'error' => null,
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.data.category',
                    'sentiment' => '$.data.sentiment',
                    'text' => '$.data.text',
                    'categories' => '$.data.categories',
                    'model_used' => '$.data.model',
                    'error_message' => '$.data.error',
                ],
                'request_class_name' => TextClassifyRequest::class,
                'function_name' => 'textClassify',
            ],
            [
                'name' => ServiceTypeEnum::TEXT_TRANSLATION_SERVICE->value,
                'input_parameters' => [
                    'model' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'claude-3-5-haiku-20241022',
                        'options' => [
                            'source' => 'collection',
                            'collection_name' => 'service_provider_model',
                            'value_field' => 'model_name',
                            'label_field' => 'display_name',
                            'filters' => [
                                'service_provider_id' => $serviceProvider->id,
                                'status' => 'active',
                                'supports_translation' => true,
                            ],
                            'fallback_options' => [
                                'claude-3-5-haiku-20241022',
                                'claude-3-5-sonnet-20241022',
                            ],
                        ],
                        'description' => 'Claude model to use for text translation',
                    ],
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
                'response' => [
                    'status' => true,
                    'data' => [
                        'translated_text' => 'Hola, ¿cómo estás?',
                        'text' => 'Hello, how are you?',
                        'source_language' => 'en',
                        'target_language' => 'es',
                        'model' => 'claude-3-5-haiku-20241022',
                        'error' => null,
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.data.translated_text',
                    'original_text' => '$.data.text',
                    'source_language' => '$.data.source_language',
                    'target_language' => '$.data.target_language',
                    'model_used' => '$.data.model',
                    'error_message' => '$.data.error',
                ],
                'request_class_name' => TextTranslateRequest::class,
                'function_name' => 'textTranslate',
            ],
            [
                'name' => ServiceTypeEnum::CODE_GENERATION_SERVICE->value,
                'input_parameters' => [
                    'model' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'claude-3-5-sonnet-20241022',
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
                                'claude-3-5-sonnet-20241022',
                                'claude-3-5-opus-20241022',
                            ],
                        ],
                        'description' => 'Claude model to use for code generation',
                    ],
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
                'response' => [
                    'status' => true,
                    'data' => [
                        'code' => 'def factorial(n):\n    if n == 0:\n        return 1\n    else:\n        return n * factorial(n-1)',
                        'description' => 'Write a Python function to calculate the factorial of a number.',
                        'language' => 'python',
                        'model' => 'claude-3-5-sonnet-20241022',
                        'error' => null,
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.data.code',
                    'description' => '$.data.description',
                    'language' => '$.data.language',
                    'model_used' => '$.data.model',
                    'error_message' => '$.data.error',
                ],
                'request_class_name' => CodegenRequest::class,
                'function_name' => 'codegen',
            ],
            [
                'name' => ServiceTypeEnum::DATA_ANALYSIS_AND_INSIGHT_SERVICE->value,
                'input_parameters' => [
                    'model' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'claude-3-5-sonnet-20241022',
                        'options' => [
                            'source' => 'collection',
                            'collection_name' => 'service_provider_model',
                            'value_field' => 'model_name',
                            'label_field' => 'display_name',
                            'filters' => [
                                'service_provider_id' => $serviceProvider->id,
                                'status' => 'active',
                                'supports_data_analysis' => true,
                            ],
                            'fallback_options' => [
                                'claude-3-5-sonnet-20241022',
                                'claude-3-5-opus-20241022',
                            ],
                        ],
                        'description' => 'Claude model to use for data analysis',
                    ],
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
                'response' => [
                    'status' => true,
                    'data' => [
                        'average_score' => 87.5,
                        'data' => [
                            ['name' => 'Alice', 'age' => 30, 'score' => 85],
                            ['name' => 'Bob', 'age' => 25, 'score' => 90],
                        ],
                        'task' => 'average_score',
                        'insights' => 'The average score is 87.5, indicating good performance across the dataset.',
                        'model' => 'claude-3-5-sonnet-20241022',
                        'error' => null,
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.data.insights',
                    'average_score' => '$.data.average_score',
                    'data' => '$.data.data',
                    'task' => '$.data.task',
                    'model_used' => '$.data.model',
                    'error_message' => '$.data.error',
                ],
                'request_class_name' => DataAnalysisInsightRequest::class,
                'function_name' => 'dataAnalysisAndInsight',
            ],
            [
                'name' => ServiceTypeEnum::PERSONALIZATION_SERVICE->value,
                'input_parameters' => [
                    'model' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'claude-3-5-haiku-20241022',
                        'options' => [
                            'source' => 'collection',
                            'collection_name' => 'service_provider_model',
                            'value_field' => 'model_name',
                            'label_field' => 'display_name',
                            'filters' => [
                                'service_provider_id' => $serviceProvider->id,
                                'status' => 'active',
                                'supports_personalization' => true,
                            ],
                            'fallback_options' => [
                                'claude-3-5-haiku-20241022',
                                'claude-3-5-sonnet-20241022',
                            ],
                        ],
                        'description' => 'Claude model to use for personalization',
                    ],
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
                'response' => [
                    'status' => true,
                    'data' => [
                        'personalized_content' => 'I am a software engineer with a strong interest in technology and science. I enjoy working on projects that involve complex algorithms and data analysis.',
                        'user_id' => '12345',
                        'preferences' => ['technology', 'science'],
                        'model' => 'claude-3-5-haiku-20241022',
                        'error' => null,
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.data.personalized_content',
                    'user_id' => '$.data.user_id',
                    'preferences' => '$.data.preferences',
                    'model_used' => '$.data.model',
                    'error_message' => '$.data.error',
                ],
                'request_class_name' => PersonalizationRequest::class,
                'function_name' => 'personalize',
            ],
        ];

        $keptServiceTypeIds = $this->processServiceTypes($serviceProvider, $serviceTypes, 'Claude');

        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);
        
        $this->command->info("Cleanup completed:");
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info("- Kept " . count($keptServiceTypeIds) . " service types for Claude API");
    }
} 