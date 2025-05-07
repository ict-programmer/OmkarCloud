<?php

namespace Database\Seeders;

use App\Models\ServiceProvider;
use App\Models\ServiceProviderType;
use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceTypesWithProviders = [
            'Claude API' => [
                [
                    'name' => 'Text Generation Service',
                    'parameter' => [
                        'prompt' => 'Write a poem about spring.',
                        'max_tokens' => 100,
                    ],
                ],
                [
                    'name' => 'Text Summarization Service',
                    'parameter' => [
                        'text' => 'Claude AI is a conversational model developed by Anthropic. It is used for a variety of natural language processing tasks, including text generation, summarization, and question answering. It excels in understanding complex language and providing relevant outputs.',
                        'summary_length' => 'short',
                        'max_tokens' => 100,
                    ],
                ],
                [
                    'name' => 'Question Answering Service',
                    'parameter' => [
                        'question' => 'What is Claude AI?',
                        'context' => 'Claude AI is a conversational model developed by Anthropic. It is used for a variety of NLP tasks, including text generation and summarization.',
                        'max_tokens' => 100,
                    ],
                ],
                [
                    'name' => 'Text Classification Service',
                    'parameter' => [
                        'text' => 'I love this product! It works amazingly well.',
                        'categories' => ['sentiment', 'product_review'],
                        'max_tokens' => 100,
                    ],
                ],
                [
                    'name' => 'Text Translation Service',
                    'parameter' => [
                        'text' => 'Hello, how are you?',
                        'source_language' => 'en',
                        'target_language' => 'es',
                        'max_tokens' => 100,
                    ],
                ],
                [
                    'name' => 'Code Generation Service',
                    'parameter' => [
                        'description' => 'Write a Python function to calculate the factorial of a number.',
                        'max_tokens' => 100,
                    ],
                ],
                [
                    'name' => 'Data Analysis and Insights Service',
                    'parameter' => [
                        'data' => [
                            ['name' => 'Alice', 'age' => 30, 'score' => 85],
                            ['name' => 'Bob', 'age' => 25, 'score' => 90],
                        ],
                        'task' => 'average_score',
                        'max_tokens' => 100,
                    ],
                ],
                [
                    'name' => 'Personalization Service',
                    'parameter' => [
                        'user_id' => '12345',
                        'preferences' => ['technology', 'science'],
                        'max_tokens' => 100,
                    ],
                ],
            ],
            'RunwayML' => [
                [
                    'name' => 'Image Generation Service',
                    'slug' => 'image-generation',
                    'parameter' => [
                        'model' => 'biggan',
                        'input_data' => 'base64_encoded_or_noise_vector',
                        'num_samples' => 1,
                        'output_format' => 'jpeg',
                        'height' => 512,
                        'width' => 512,
                        'seed' => 12345,

                    ],
                    [
                        'name' => 'Text Generation Service',
                        'slug' => 'text-generation',
                        'parameter' => [
                            'model' => 'gpt3',
                            'prompt' => 'Once upon a time',
                            'max_tokens' => 100,
                            'temperature' => 0.7,
                            'top_p' => 0.9,
                            'stop' => ["\n"],
                        ],
                    ],
                    [
                        'name' => 'Video Processing Service (Inpainting, Motion Tracking, Frame Interpolation)',
                        'slug' => 'video-processing',
                        'parameter' => [
                            'model' => 'inpainting',
                            'video_url' => 'https://example.com/video.mp4',
                            'frames' => [1, 2, 3],
                            'mask' => 'base64_encoded_image',
                            'output_format' => 'mp4',
                            'resolution' => '1080p',
                        ],

                    ],
                    [
                        'name' => 'Image Processing Service (Segmentation, Background Removal)',
                        'slug' => 'image-processing',
                        'parameter' => [
                            'model' => 'rembg',
                            'image' => 'base64_encoded_image',
                            'operations' => ['remove_background'],
                            'output_format' => 'png',
                        ],
                    ],
                    [
                        'name' => 'Style Transfer Service',
                        'slug' => 'style-transfer',
                        'parameter' => [
                            'style_image' => 'base64_encoded_image',
                            'content_image' => 'base64_encoded_image',
                            'output_format' => 'png',
                            'style_weight' => 0.5,
                            'content_weight' => 0.5,
                        ],
                    ],
                ],
            ],
            'DeepSeek' => [
                [
                    'name' => 'Chat Completion',
                    'parameter' => [
                        "model" => "deepseek-chat",
                        "messages" =>
                            [
                                [
                                    "role" => "user",
                                    "content" => "Your input text"
                                ]
                            ],
                        "max_tokens" => 500,
                        "temperature" => 0.7
                    ]
                ],
                [
                    'name' => 'Code Completion',
                    'parameter' => [
                        "model" => "deepseek-code",
                        "prompt" => "Write a Python function",
                        "max_tokens" => 800,
                        "temperature" => 0.6
                    ]
                ],
                [
                    'name' => 'Document Q&A',
                    'parameter' => [
                        "model" => "deepseek-chat",
                        "document_text" => "Full document content here",
                        "question" => "Summarize this document"
                    ]
                ],
                [
                    'name' => 'Mathematical Reasoning',
                    'parameter' => [
                        "model" => "deepseek-chat",
                        "problem_statement" => "Solve x^2 - 4x + 4 = 0",
                        "max_steps" => 5
                    ]
                ]
            ],
            'Gemini' => [
                [
                    'name' => 'Text Generation',
                    'parameter' => [
                        "model" => "gemini-pro",
                        "prompt" => "Your input text",
                        "max_tokens" => 500,
                        "temperature" => 0.7
                    ]
                ],
                [
                    'name' => 'Code Generation',
                    'parameter' => [
                        "model" => "gemini-pro",
                        "prompt" => "Generate Python function",
                        "max_tokens" => 800,
                        "temperature" => 0.6
                    ]
                ],
                [
                    'name' => 'Image Analysis',
                    'parameter' => [
                        "model" => "gemini-ultra",
                        "image_url" => "https://example.com/image.jpg",
                        "description_required" => true
                    ]
                ],
                [
                    'name' => 'Document Summarization',
                    'parameter' => [
                        "model" => "gemini-pro",
                        "document_text" => "Full document content here",
                        "summary_length" => 200
                    ]
                ]
            ]
        ];

        foreach ($serviceTypesWithProviders as $serviceProvider => $serviceTypes) {
            $serviceProvider = ServiceProvider::where('type', $serviceProvider)->first();
            foreach ($serviceTypes as $serviceType) {
                $saved = ServiceType::updateOrCreate(
                    ['name' => $serviceType['name']],
                    ['name' => $serviceType['name']]
                );

                $serviceProviderType = [
                    'service_provider_id' => $serviceProvider->id,
                    'service_type_id' => $saved->id,
                ];

                if (array_key_exists('parameter', $serviceType)) {
                    $serviceProviderType['parameter'] = $serviceType['parameter'];
                }

                ServiceProviderType::updateOrCreate(
                    ['service_provider_id' => $serviceProvider->id, 'service_type_id' => $saved->id],
                    $serviceProviderType
                );
            }
        }
    }
}
