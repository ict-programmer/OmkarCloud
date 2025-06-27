<?php

namespace Database\Seeders;

use App\Http\Controllers\ChatGPTController;
use App\Http\Requests\ChatGPT\ChatCompletionRequest;
use App\Http\Requests\ChatGPT\ImageGenerationRequest;
use App\Http\Requests\ChatGPT\TextEmbeddingRequest;
use App\Models\ServiceProvider;
use App\Traits\ServiceProviderSeederTrait;
use Illuminate\Database\Seeder;

class ChatGPTServiceProviderSeeder extends Seeder
{
    use ServiceProviderSeederTrait;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => 'ChatGPT'],
            [
                'parameters' => [
                    'api_key' => 'YOUR_OPENAI_API_KEY',
                    'base_url' => 'https://api.openai.com',
                    'version' => 'v1',
                    'models_supported' => [
                        'gpt-4o',
                        'gpt-4o-mini',
                        'gpt-4-turbo',
                        'gpt-3.5-turbo',
                        'dall-e-3',
                        'dall-e-2',
                        'text-embedding-3-large',
                        'text-embedding-3-small',
                        'text-embedding-ada-002',
                    ],
                    'features' => [
                        'chat_completion',
                        'image_generation',
                        'text_embedding',
                        'function_calling',
                        'vision',
                        'json_mode',
                    ],
                ],
                'is_active' => true,
                'controller_name' => ChatGPTController::class,
            ]
        );

        $serviceTypes = [
            [
                'name' => 'Chat Completion',
                'input_parameters' => [
                    'model' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'gpt-4o-mini',
                        'options' => [
                            'source' => 'collection',
                            'collection_name' => 'service_provider_model',
                            'value_field' => 'model_name',
                            'label_field' => 'display_name',
                            'filters' => [
                                'service_provider_id' => $serviceProvider->id,
                                'status' => 'active',
                                'supports_chat' => true,
                            ],
                            'fallback_options' => [
                                'gpt-4o',
                                'gpt-4o-mini',
                                'gpt-4-turbo',
                                'gpt-3.5-turbo',
                            ],
                        ],
                        'description' => 'ChatGPT model to use for completion',
                    ],
                    'messages' => [
                        'type' => 'array',
                        'required' => true,
                        'min_items' => 1,
                        'max_items' => 100,
                        'description' => 'Array of message objects for the conversation',
                        'items' => [
                            'type' => 'object',
                            'properties' => [
                                'role' => [
                                    'type' => 'string',
                                    'required' => true,
                                    'options' => ['system', 'user', 'assistant'],
                                ],
                                'content' => [
                                    'type' => 'string',
                                    'required' => true,
                                    'max_length' => 32768,
                                ],
                            ],
                        ],
                    ],
                    'temperature' => [
                        'type' => 'float',
                        'required' => false,
                        'default' => 1.0,
                        'min' => 0.0,
                        'max' => 2.0,
                        'description' => 'Sampling temperature between 0 and 2',
                    ],
                    'max_tokens' => [
                        'type' => 'integer',
                        'required' => false,
                        'default' => 1000,
                        'min' => 1,
                        'max' => 4096,
                        'description' => 'Maximum number of tokens to generate',
                    ],
                    'top_p' => [
                        'type' => 'float',
                        'required' => false,
                        'default' => 1.0,
                        'min' => 0.0,
                        'max' => 1.0,
                        'description' => 'Nucleus sampling parameter',
                    ],
                    'frequency_penalty' => [
                        'type' => 'float',
                        'required' => false,
                        'default' => 0.0,
                        'min' => -2.0,
                        'max' => 2.0,
                        'description' => 'Frequency penalty for token repetition',
                    ],
                    'presence_penalty' => [
                        'type' => 'float',
                        'required' => false,
                        'default' => 0.0,
                        'min' => -2.0,
                        'max' => 2.0,
                        'description' => 'Presence penalty for new topics',
                    ],
                    'response_format' => [
                        'type' => 'object',
                        'required' => false,
                        'properties' => [
                            'type' => [
                                'type' => 'string',
                                'options' => ['text', 'json_object'],
                                'default' => 'text',
                            ],
                        ],
                        'description' => 'Response format specification',
                    ],
                ],
                'response' => [
                    'id' => 'chatcmpl-9WLgAh6XgAVrCzznQ5KQf8lGlh4Qa',
                    'object' => 'chat.completion',
                    'created' => 1717502492,
                    'model' => 'gpt-4o-2024-05-13',
                    'choices' => [
                        [
                            'index' => 0,
                            'message' => [
                                'role' => 'assistant',
                                'content' => 'Artificial Intelligence (AI) refers to the simulation of human intelligence in machines that are programmed to think and learn like humans. AI systems can perform tasks that typically require human intelligence, such as visual perception, speech recognition, decision-making, and language translation.\n\nKey aspects of AI include:\n\n1. **Machine Learning**: Algorithms that improve automatically through experience\n2. **Natural Language Processing**: Understanding and generating human language\n3. **Computer Vision**: Interpreting and understanding visual information\n4. **Neural Networks**: Computing systems inspired by biological neural networks\n5. **Deep Learning**: Machine learning using deep neural networks\n\nAI applications are widespread today, from virtual assistants like Siri and Alexa to recommendation systems on Netflix and Amazon, autonomous vehicles, medical diagnosis systems, and financial trading algorithms.',
                            ],
                            'logprobs' => null,
                            'finish_reason' => 'stop',
                        ],
                    ],
                    'usage' => [
                        'prompt_tokens' => 12,
                        'completion_tokens' => 157,
                        'total_tokens' => 169,
                    ],
                    'system_fingerprint' => 'fp_319be4768e',
                ],
                'response_path' => [
                    'final_result' => '$.choices[0].message.content',
                ],
                'request_class_name' => ChatCompletionRequest::class,
                'function_name' => 'chatCompletion',
            ],
            [
                'name' => 'Image Generation',
                'input_parameters' => [
                    'prompt' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 4000,
                        'description' => 'Text description of the desired image',
                    ],
                    'model' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'dall-e-3',
                        'options' => [
                            'source' => 'collection',
                            'collection_name' => 'service_provider_model',
                            'value_field' => 'model_name',
                            'label_field' => 'display_name',
                            'filters' => [
                                'service_provider_id' => $serviceProvider->id,
                                'status' => 'active',
                                'supports_image_generation' => true,
                            ],
                            'fallback_options' => [
                                'dall-e-3',
                                'dall-e-2',
                            ],
                        ],
                        'description' => 'DALL-E model to use for image generation',
                    ],
                    'size' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => '1024x1024',
                        'options' => [
                            '256x256',
                            '512x512',
                            '1024x1024',
                            '1792x1024',
                            '1024x1792',
                        ],
                        'description' => 'Size of the generated image',
                    ],
                    'quality' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'standard',
                        'options' => [
                            'standard',
                            'hd',
                        ],
                        'description' => 'Quality of the generated image (DALL-E 3 only)',
                    ],
                    'style' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'vivid',
                        'options' => [
                            'vivid',
                            'natural',
                        ],
                        'description' => 'Style of the generated image (DALL-E 3 only)',
                    ],
                    'n' => [
                        'type' => 'integer',
                        'required' => false,
                        'default' => 1,
                        'min' => 1,
                        'max' => 4,
                        'description' => 'Number of images to generate',
                    ],
                    'response_format' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'url',
                        'options' => [
                            'url',
                            'b64_json',
                        ],
                        'description' => 'Format of the generated image response',
                    ],
                ],
                'response' => [
                    'created' => 1717502834,
                    'data' => [
                        [
                            'revised_prompt' => 'A futuristic cityscape at sunset with towering glass skyscrapers reflecting golden light, flying cars navigating between buildings, and holographic advertisements illuminating the streets below. The scene has a cyberpunk aesthetic with neon lights in blues and purples contrasting against the warm sunset colors.',
                            'url' => 'https://oaidalleapiprodscus.blob.core.windows.net/private/org-abc123/user-def456/img-ghi789.png?st=2024-06-04T14%3A47%3A14Z&se=2024-06-04T16%3A47%3A14Z&sp=r&sv=2021-08-06&sr=b&rscd=inline&rsct=image/png&skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&sktid=a48ccc56-e6da-484e-a814-9c849652bcb3&skt=2024-06-03T23%3A12%3A36Z&ske=2024-06-04T23%3A12%3A36Z&sks=b&skv=2021-08-06&sig=abc123def456',
                        ],
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.data[0]',
                ],
                'request_class_name' => ImageGenerationRequest::class,
                'function_name' => 'imageGeneration',
            ],
            [
                'name' => 'Text Embedding',
                'input_parameters' => [
                    'input' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 8191,
                        'description' => 'Text to generate embeddings for',
                    ],
                    'model' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'text-embedding-3-small',
                        'options' => [
                            'source' => 'collection',
                            'collection_name' => 'service_provider_model',
                            'value_field' => 'model_name',
                            'label_field' => 'display_name',
                            'filters' => [
                                'service_provider_id' => $serviceProvider->id,
                                'status' => 'active',
                                'supports_embedding' => true,
                            ],
                            'fallback_options' => [
                                'text-embedding-3-large',
                                'text-embedding-3-small',
                                'text-embedding-ada-002',
                            ],
                        ],
                        'description' => 'Embedding model to use',
                    ],
                    'encoding_format' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'float',
                        'options' => [
                            'float',
                            'base64',
                        ],
                        'description' => 'Format of the returned embeddings',
                    ],
                    'dimensions' => [
                        'type' => 'integer',
                        'required' => false,
                        'min' => 1,
                        'max' => 3072,
                        'description' => 'Number of dimensions for the embedding (text-embedding-3 models only)',
                    ],
                ],
                'response' => [
                    'object' => 'list',
                    'data' => [
                        [
                            'object' => 'embedding',
                            'index' => 0,
                            'embedding' => [
                                -0.006929283495992422,
                                -0.005336422007530928,
                                -0.00004547132642613747,
                                -0.024047505110502243,
                                0.01733201928436756,
                                -0.02397253736853599,
                                -0.01886593625322056,
                                0.007359594758600071,
                                -0.018711734563112258,
                                0.00023001800582185388,
                                // ... (1536 dimensions total for text-embedding-3-small)
                            ],
                        ],
                    ],
                    'model' => 'text-embedding-3-small',
                    'usage' => [
                        'prompt_tokens' => 15,
                        'total_tokens' => 15,
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.data[0].embedding',
                ],
                'request_class_name' => TextEmbeddingRequest::class,
                'function_name' => 'textEmbedding',
            ],
        ];

        $keptServiceTypeIds = $this->processServiceTypes($serviceProvider, $serviceTypes, 'ChatGPT');

        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);

        $this->command->info('Cleanup completed:');
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info('- Kept ' . count($keptServiceTypeIds) . ' service types for ChatGPT');
    }
} 