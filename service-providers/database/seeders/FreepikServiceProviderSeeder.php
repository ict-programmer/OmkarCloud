<?php

namespace Database\Seeders;

use App\Http\Controllers\FreepikController;
use App\Http\Requests\Freepik\AiImageClassifierRequest;
use App\Http\Requests\Freepik\ClassicFastGenerateRequest;
use App\Http\Requests\Freepik\DownloadResourceFormatRequest;
use App\Http\Requests\Freepik\FluxDevGenerateRequest;
use App\Http\Requests\Freepik\IconGenerationRequest;
use App\Http\Requests\Freepik\ImageExpandFluxProRequest;
use App\Http\Requests\Freepik\Imagen3GenerateRequest;
use App\Http\Requests\Freepik\KlingElementsVideoRequest;
use App\Http\Requests\Freepik\KlingElementsVideoStatusRequest;
use App\Http\Requests\Freepik\KlingImageToVideoRequest;
use App\Http\Requests\Freepik\KlingImageToVideoStatusRequest;
use App\Http\Requests\Freepik\KlingTextToVideoRequest;
use App\Http\Requests\Freepik\LoraCharacterTrainRequest;
use App\Http\Requests\Freepik\LoraStyleTrainRequest;
use App\Http\Requests\Freepik\MysticGenerateRequest;
use App\Http\Requests\Freepik\ReimagineFluxRequest;
use App\Http\Requests\Freepik\RelightImageRequest;
use App\Http\Requests\Freepik\RemoveBackgroundRequest;
use App\Http\Requests\Freepik\StockContentRequest;
use App\Http\Requests\Freepik\StyleTransferRequest;
use App\Http\Requests\Freepik\UpscaleImageRequest;
use App\Models\ServiceProvider;
use App\Models\ServiceProviderModel;
use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class FreepikServiceProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => 'Freepik'],
            [
                'parameters' => [
                    'api_key' => 'YOUR_API_KEY',
                    'base_url' => 'https://api.freepik.com/v1',
                    'version' => 'v1',
                    'models_supported' => [
                        'kling_video' => [
                            'kling-v2-1-master',
                            'kling-v2-1-pro',
                            'kling-v2-1-std',
                            'kling-v2',
                            'kling-pro',
                            'kling-std',
                        ],
                        'kling_elements' => [
                            'kling-elements-pro',
                            'kling-elements-std',
                        ],
                        'mystic_generation' => [
                            'realism',
                            'fluid',
                            'zen',
                        ],
                        'classic_fast_style' => [
                            'photo',
                            'digital-art',
                            '3d',
                            'painting',
                            'low-poly',
                            'pixel-art',
                            'anime',
                            'cyberpunk',
                            'comic',
                            'vintage',
                            'cartoon',
                            'vector',
                            'studio-shot',
                            'dark',
                            'sketch',
                            'mockup',
                            '2000s-pone',
                            '70s-vibe',
                            'watercolor',
                            'art-nouveau',
                            'origami',
                            'surreal',
                            'fantasy',
                            'traditional-japan',
                        ],
                        'imagen3_style' => [
                            'photo',
                            'digital-art',
                            '3d',
                            'painting',
                            'low-poly',
                            'pixel-art',
                            'anime',
                            'cyberpunk',
                            'comic',
                            'vintage',
                            'cartoon',
                            'vector',
                            'studio-shot',
                            'dark',
                            'sketch',
                            'mockup',
                            '2000s-pone',
                            '70s-vibe',
                            'watercolor',
                            'art-nouveau',
                            'origami',
                            'surreal',
                            'fantasy',
                            'traditional-japan',
                        ],
                        'upscaler_engine' => [
                            'automatic',
                            'magnific_illusio',
                            'magnific_sharpy',
                            'magnific_sparkle',
                        ],
                        'relight_engine' => [
                            'automatic',
                            'balanced',
                            'cool',
                            'real',
                            'illusio',
                            'fairy',
                            'colorful_anime',
                            'hard_transform',
                            'softy',
                        ],
                        'style_transfer_engine' => [
                            'balanced',
                            'definio',
                            'illusio',
                            '3d_cartoon',
                            'colorful_anime',
                            'caricature',
                            'real',
                            'super_real',
                            'softy',
                        ],
                    ],
                    'features' => [
                        'Stock Content Search',
                        'Resource Detail',
                        'Resource Download',
                        'AI Image Classifier',
                        'Icon Generation',
                        'Video Generation',
                        'Text-to-Image Generation',
                        'LoRA Model Training',
                        'Image Upscaling',
                        'Image Relighting',
                        'Image Style Transfer',
                        'Image Background Removal',
                        'Image Expansion',
                        'Image Reimagine',
                    ],
                ],
                'is_active' => true,
                'controller_name' => FreepikController::class,
            ]
        );

        $serviceTypes = [
            [
                'name' => 'Stock Content',
                'input_parameters' => [
                    'page' => [
                        'type' => 'integer',
                        'required' => false,
                        'description' => 'The page number for pagination.',
                        'example' => 1,
                    ],
                    'limit' => [
                        'type' => 'integer',
                        'required' => true,
                        'min' => 1,
                        'max' => 100,
                        'description' => 'The number of items to return per page.',
                        'example' => 20,
                    ],
                    'order' => [
                        'type' => 'string',
                        'required' => true,
                        'options' => ['relevance', 'recent'],
                        'description' => 'The order to sort the results by.',
                    ],
                    'term' => [
                        'type' => 'string',
                        'required' => false,
                        'description' => 'The search term.',
                    ],
                    'slug' => [
                        'type' => 'string',
                        'required' => false,
                        'description' => 'A specific slug for searching.',
                    ],
                    'filters' => [
                        'type' => 'object',
                        'required' => false,
                        'description' => 'A map of filters to apply to the search.',
                        'properties' => [
                            'orientation' => [
                                'type' => 'object',
                                'properties' => [
                                    'landscape' => ['type' => 'integer', 'options' => [0, 1]],
                                    'portrait' => ['type' => 'integer', 'options' => [0, 1]],
                                    'square' => ['type' => 'integer', 'options' => [0, 1]],
                                    'panoramic' => ['type' => 'integer', 'options' => [0, 1]],
                                ],
                            ],
                            'content_type' => [
                                'type' => 'object',
                                'properties' => [
                                    'photo' => ['type' => 'integer', 'options' => [0, 1]],
                                    'psd' => ['type' => 'integer', 'options' => [0, 1]],
                                    'vector' => ['type' => 'integer', 'options' => [0, 1]],
                                ],
                            ],
                            'license' => [
                                'type' => 'object',
                                'properties' => [
                                    'freemium' => ['type' => 'integer', 'options' => [0, 1]],
                                    'premium' => ['type' => 'integer', 'options' => [0, 1]],
                                ],
                            ],
                            'people' => [
                                'type' => 'object',
                                'properties' => [
                                    'include' => ['type' => 'integer', 'options' => [0, 1]],
                                    'exclude' => ['type' => 'integer', 'options' => [0, 1]],
                                    'number' => ['type' => 'string', 'options' => ['1', '2', '3', 'more_than_three']],
                                    'age' => ['type' => 'string', 'options' => ['infant', 'child', 'teen', 'young-adult', 'adult', 'senior', 'elder']],
                                    'gender' => ['type' => 'string', 'options' => ['male', 'female']],
                                    'ethnicity' => ['type' => 'string', 'options' => ['south-asian', 'middle-eastern', 'east-asian', 'black', 'hispanic', 'indian', 'white', 'multiracial', 'southeast-asian']],
                                ],
                            ],
                            'period' => [
                                'type' => 'string',
                                'options' => ['last-month', 'last-quarter', 'last-semester', 'last-year'],
                            ],
                            'color' => [
                                'type' => 'string',
                                'options' => ['black', 'blue', 'gray', 'green', 'orange', 'red', 'white', 'yellow', 'purple', 'cyan', 'pink'],
                            ],
                            'author' => ['type' => 'integer'],
                            'ai-generated' => [
                                'type' => 'object',
                                'properties' => [
                                    'excluded' => ['type' => 'integer', 'options' => [0, 1]],
                                    'only' => ['type' => 'integer', 'options' => [0, 1]],
                                ],
                            ],
                            'vector' => [
                                'type' => 'object',
                                'properties' => [
                                    'type' => ['type' => 'string', 'options' => ['jpg', 'ai', 'eps', 'svg']],
                                    'style' => ['type' => 'string', 'options' => ['watercolor', 'flat', 'cartoon', 'geometric', 'gradient', 'isometric', '3d', 'hand-drawn']],
                                ],
                            ],
                            'psd' => [
                                'type' => 'object',
                                'properties' => [
                                    'type' => ['type' => 'string', 'options' => ['jpg', 'psd']],
                                ],
                            ],
                            'ids' => ['type' => 'string'],
                        ],
                    ],
                ],
                'response' => [
                    'data' => [
                        [
                            'id' => 7663349,
                            'title' => "Father's day event hand drawn style",
                            'url' => 'https://www.freepik.com/free-vector/father-s-day-event-hand-drawn-style_7663349.htm',
                            'filename' => 'fathers-day-event-hand-drawn-style.zip',
                            'licenses' => [['type' => 'freemium', 'url' => 'https://www.freepik.com/profile/license/pdf/7663349?lang=en']],
                            'products' => [['type' => 'essential', 'url' => 'https://www.freepik.com/profile/license/pdf/7663349?lang=en']],
                            'meta' => [
                                'published_at' => '2020-04-15 17:50:35',
                                'is_new' => false,
                                'available_formats' => [
                                    'ai' => ['total' => 1, 'items' => [['size' => 340222, 'id' => 567457]]],
                                    'eps' => ['total' => 1, 'items' => [['size' => 1323126, 'id' => 567458]]],
                                    'jpg' => ['total' => 1, 'items' => [['size' => 1131441, 'id' => 567459]]],
                                    'fonts' => ['total' => 1, 'items' => [['size' => 203, 'id' => 567460]]],
                                ],
                            ],
                            'image' => [
                                'type' => 'vector',
                                'orientation' => 'square',
                                'source' => ['key' => 'large', 'url' => 'https://img.b2bpic.net/free-vector/father-s-day-event-hand-drawn-style_23-2148507324.jpg', 'size' => '626x626'],
                            ],
                            'related' => ['serie' => [], 'others' => [], 'keywords' => []],
                            'stats' => ['downloads' => 7198, 'likes' => 91],
                            'author' => ['id' => 23, 'name' => 'freepik', 'avatar' => 'https://avatar.cdnpk.net/23.jpg', 'assets' => 6403390, 'slug' => 'freepik'],
                            'active' => true,
                        ],
                    ],
                    'meta' => ['current_page' => 1, 'per_page' => 1, 'last_page' => 181651088, 'total' => 181651088, 'clean_search' => false],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => StockContentRequest::class,
                'function_name' => 'stockContent',
            ],
            [
                'name' => 'Resource Detail',
                'path_parameters' => [
                    'resource_id' => ['type' => 'string', 'required' => true, 'description' => 'The ID of the Freepik resource.', 'example' => '7663349'],
                ],
                'response' => [
                    'data' => [
                        'id' => 7663349,
                        'name' => 'father\'s day event hand drawn style',
                        'slug' => 'father-s-day-event-hand-drawn-style',
                        'type' => 'vector',
                        'premium' => false,
                        'url' => 'https://www.freepik.com/free-vector/father-s-day-event-hand-drawn-style_7663349.htm',
                        'created' => '2020-04-15T17:50:35Z',
                        'new' => false,
                        'download_size' => 1740322,
                        'author' => [
                            'id' => 23,
                            'name' => 'freepik',
                            'avatar' => 'https://avatar.cdnpk.net/23.jpg',
                            'assets' => 6404687,
                            'slug' => 'freepik',
                        ],
                        'preview' => [
                            'url' => 'https://img.b2bpic.net/free-vector/father-s-day-event-hand-drawn-style_23-2148507324.jpg',
                            'width' => 626,
                            'height' => 626,
                        ],
                        'license' => 'https://www.freepik.com/profile/license/pdf/7663349?lang=en',
                        'available_formats' => [
                            'ai' => ['total' => 1, 'items' => [['id' => 567457, 'colorspace' => 'RGB', 'name' => '7663349_3725127', 'size' => 340222]]],
                            'eps' => ['total' => 1, 'items' => [['id' => 567458, 'colorspace' => 'RGB', 'name' => '7663349_3725128', 'size' => 1323126]]],
                            'jpg' => ['total' => 1, 'items' => [['id' => 567459, 'colorspace' => 'UNKNOWN', 'name' => '7663349_3699294', 'size' => 1131441]]],
                            'fonts' => ['total' => 1, 'items' => [['id' => 567460, 'colorspace' => 'UNKNOWN', 'name' => '7663349_Fonts', 'size' => 203]]],
                        ],
                        'is_ai_generated' => false,
                        'has_prompt' => false,
                        'dimensions' => ['width' => 626, 'height' => 626],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => null,
                'function_name' => 'resourceDetail',
            ],
            [
                'name' => 'Download resource',
                'path_parameters' => [
                    'resource_id' => ['type' => 'string', 'required' => true, 'description' => 'The ID of the resource to download.', 'example' => '7663349'],
                ],
                'response' => [
                    'data' => [
                        'filename' => 'father-s-day-event-hand-drawn-style.zip',
                        'url' => 'https://downloadscdn5.freepik.com/d/7663349/23/2148508/2148507324/father-s-day-event-hand-drawn-style.zip?token=exp=1751005689~hmac=5e439aed0f35b50349bdecfaf8ce2856',
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => null,
                'function_name' => 'downloadResource',
            ],
            [
                'name' => 'Download Resource Format',
                'input_parameters' => [
                    'resource_id' => ['type' => 'string', 'required' => true, 'description' => 'The ID of the resource.', 'example' => '150898146'],
                    'format' => ['type' => 'string', 'required' => true, 'options' => ['psd', 'ai', 'eps', 'atn', 'fonts', 'resources', 'png', 'jpg', '3d-render', 'svg', 'mockup'], 'description' => 'The format to download.', 'example' => 'psd'],
                ],
                'response' => [
                    'data' => [
                        [
                            'filename' => '150898146_10483448.psd',
                            'url' => 'https://downloadscdn5.freepik.com/download_psd/psd/0/23/150/150898/150898146_10483448.psd?token=exp=1751005736~hmac=cfeb79bd582be116da3ba87b72a27b2f',
                        ],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => DownloadResourceFormatRequest::class,
                'function_name' => 'downloadResourceFormat',
            ],
            [
                'name' => 'AI Image Classifier',
                'input_parameters' => [
                    'image_url' => ['type' => 'string', 'required' => true, 'description' => 'URL of the image to classify.', 'example' => 'https://publiish.io/ipfs/QmTkm5aAqNPgc3rXKTjYJ1VVB86xWJGofZX5wiRXHeew7f'],
                ],
                'response' => [
                    'data' => [
                        ['class_name' => 'not_ai', 'probability' => 0.94891726970673],
                        ['class_name' => 'ai', 'probability' => 0.051082730293274],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => AiImageClassifierRequest::class,
                'function_name' => 'aiImageClassifier',
            ],
            [
                'name' => 'Icon Generation',
                'input_parameters' => [
                    'prompt' => ['type' => 'string', 'required' => true, 'description' => 'Text prompt describing the icon you want to generate', 'example' => 'Cute robot with camera in flat vector style'],
                ],
                'response' => [
                    'data' => [
                        'task_id' => 'd1686bd7-ffeb-4914-bffe-5a8db018ac3b',
                        'status' => 'CREATED',
                        'generated' => [],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => IconGenerationRequest::class,
                'function_name' => 'iconGeneration',
            ],
            [
                'name' => 'Icon Generation Task Status',
                'path_parameters' => [
                    'task_id' => ['type' => 'string', 'required' => true, 'description' => 'The task_id received from the iconGeneration endpoint.', 'example' => '796dd3c1-c50b-42bc-a9e2-a892eef53438'],
                ],
                'response' => [
                    'data' => [
                        'task_id' => 'd20eeb40-11fc-4402-a9da-d06eb9c66f23',
                        'status' => 'COMPLETED',
                        'generated' => ['https://cdn-magnific.freepik.com/imagen3_d20eeb40-11fc-4402-a9da-d06eb9c66f23_0.png?token=exp=1751007773~hmac=0efd3dde93656145f6b951922491d150e3e4a1cb8aba2ab06144d3629060215d'],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => null,
                'function_name' => 'getIconGenerationResult',
            ],
            [
                'name' => 'Video Generation Image to Video',
                'input_parameters' => [
                    'model' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'kling-v2-1-master',
                        'options' => [
                            'source' => 'collection',
                            'collection_name' => 'service_provider_model',
                            'value_field' => 'model_name',
                            'label_field' => 'display_name',
                            'filters' => [
                                'service_provider_id' => $serviceProvider->id,
                                'status' => 'active',
                                'supports_search' => true,
                            ],
                            'fallback_options' => ['kling-v2-1-master', 'kling-v2-1-pro', 'kling-v2-1-std', 'kling-v2', 'kling-pro', 'kling-std'],
                        ],
                        'description' => 'Model of the generated video. Available options: kling-v2-1-master, kling-v2-1-pro, kling-v2-1-std, kling-v2, kling-pro, kling-std.',
                        'example' => 'kling-v2-1-master',
                    ],
                    'duration' => ['type' => 'string', 'required' => true, 'options' => ['5', '10'], 'description' => 'Duration of the generated video in seconds. Available options: 5, 10.', 'example' => '10'],
                    'image' => ['type' => 'string', 'required' => false, 'description' => 'Reference image. Supports URL. Max 10MB, min 300x300px, aspect ratio 1:2.5 to 2.5:1.', 'example' => 'https://publiish.io/ipfs/QmePMNQ1BYCsaJwCpA4sbGpYxgiEznzJwPDMHir9FdUiYN'],
                    'image_tail' => ['type' => 'string', 'required' => false, 'description' => "Reference Image - End frame control. Supports URL. For URL, must be publicly accessible. Must follow the same format requirements as the 'image' field. (Optional) Not compatible with standard mode.", 'example' => 'https://publiish.io/ipfs/QmePMNQ1BYCsaJwCpA4sbGpYxgiEznzJwPDMHir9FdUiYN'],
                    'prompt' => ['type' => 'string', 'required' => false, 'description' => 'Text prompt describing the desired motion. Required if image is not provided.', 'example' => 'Cinematic view of a mountain range fading into mist, soft lighting, epic atmosphere'],
                    'negative_prompt' => ['type' => 'string', 'required' => false, 'description' => 'Describe what to avoid in the generated video.', 'example' => 'blurry, low-quality, distorted, overexposed'],
                    'cfg_scale' => ['type' => 'number', 'required' => false, 'description' => 'Higher = stronger relevance to prompt (0-1). Default is 0.5.', 'example' => 0.3, 'min' => 0, 'max' => 1],
                    'static_mask' => ['type' => 'string', 'required' => false, 'description' => 'Static mask image URL. Must match resolution and aspect ratio of input image.', 'example' => 'https://publiish.io/ipfs/Qme7SZ1t9PbGKAesTA24EDu7pEs3J1JkWDt9qwQdaocYRB'],
                    'dynamic_masks' => [
                        'type' => 'array',
                        'required' => false,
                        'description' => 'Array of dynamic masks with motion trajectories.',
                        'items' => [
                            'type' => 'object',
                            'properties' => [
                                'mask' => ['type' => 'string', 'description' => 'Dynamic mask image URL', 'example' => 'https://publiish.io/ipfs/QmRPNoFMcYFmzJuZgd4t3BDyfELAGCwNtGSb5i5AbXkcpf'],
                                'trajectories' => [
                                    'type' => 'array',
                                    'items' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'x' => ['type' => 'integer', 'example' => 120],
                                            'y' => ['type' => 'integer', 'example' => 200],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'response' => [
                    'data' => [
                        'task_id' => 'd1686bd7-ffeb-4914-bffe-5a8db018ac3b',
                        'status' => 'CREATED',
                        'generated' => [],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => KlingImageToVideoRequest::class,
                'function_name' => 'klingVideoGenerationImageToVideo',
            ],
            [
                'name' => 'Video Generation Image to Video Task Status',
                'path_parameters' => [
                    'task_id' => ['type' => 'string', 'required' => true, 'description' => 'ID of the video generation task', 'example' => '046b6c7f-0b8a-43b9-b35d-6489e6daee91'],
                ],
                'input_parameters' => [
                    'model' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'kling-v2-1-master',
                        'options' => [
                            'source' => 'collection',
                            'collection_name' => 'service_provider_model',
                            'value_field' => 'model_name',
                            'label_field' => 'display_name',
                            'filters' => [
                                'service_provider_id' => $serviceProvider->id,
                                'status' => 'active',
                                'supports_search' => true,
                            ],
                            'fallback_options' => ['kling-v2-1-master', 'kling-v2-1-pro', 'kling-v2-1-std', 'kling-v2', 'kling-pro', 'kling-std'],
                        ],
                        'description' => 'Model of the generated video. Available options: kling-v2-1-master, kling-v2-1-pro, kling-v2-1-std, kling-v2, kling-pro, kling-std.',
                        'example' => 'kling-v2-1-master',
                    ],
                ],
                'response' => [
                    'data' => [
                        'task_id' => '14eb9004-41ef-4a16-97f2-a558aab35578',
                        'status' => 'COMPLETED',
                        'generated' => ['https://ai-statics.freepik.com/content/mg-upscaler/syn56t4xlfgodjvkhlksx2t3be/output.png?token=exp=1751090771~hmac=8a99cf9fcbbecb72e35d8a6ddfea4b2d'],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => KlingImageToVideoStatusRequest::class,
                'function_name' => 'klingVideoGenerationImageToVideoStatus',
            ],
            [
                'name' => 'Video Generation Text to Video',
                'input_parameters' => [
                    'duration' => ['type' => 'string', 'required' => true, 'options' => ['5', '10'], 'description' => 'Duration of the generated video in seconds.', 'example' => '5'],
                    'prompt' => ['type' => 'string', 'required' => false, 'description' => 'Text prompt describing the desired motion. Max 2500 characters.', 'example' => 'A sunset over the ocean with crashing waves'],
                    'negative_prompt' => ['type' => 'string', 'required' => false, 'description' => 'Describe what to avoid in the generated video.', 'example' => 'low resolution, night scene'],
                    'aspect_ratio' => ['type' => 'string', 'required' => false, 'options' => ['widescreen_16_9', 'social_story_9_16', 'square_1_1'], 'description' => 'Aspect ratio for generated video (only used when image is not provided).', 'example' => 'widescreen_16_9'],
                    'cfg_scale' => ['type' => 'number', 'required' => false, 'description' => 'Higher = stronger relevance to prompt (0-1). Default is 0.5.', 'example' => 0.5, 'min' => 0, 'max' => 1],
                ],
                'response' => [
                    'data' => [
                        'task_id' => 'd1686bd7-ffeb-4914-bffe-5a8db018ac3b',
                        'status' => 'CREATED',
                        'generated' => [],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => KlingTextToVideoRequest::class,
                'function_name' => 'klingVideoGenerationTextToVideo',
            ],
            [
                'name' => 'Video Generation Text to Video Task Status',
                'path_parameters' => [
                    'task_id' => ['type' => 'string', 'required' => true, 'description' => 'ID of the video generation task', 'example' => '046b6c7f-0b8a-43b9-b35d-6489e6daee91'],
                ],
                'response' => [
                    'data' => [
                        'task_id' => '14eb9004-41ef-4a16-97f2-a558aab35578',
                        'status' => 'COMPLETED',
                        'generated' => ['https://ai-statics.freepik.com/content/mg-upscaler/syn56t4xlfgodjvkhlksx2t3be/output.png?token=exp=1751090771~hmac=8a99cf9fcbbecb72e35d8a6ddfea4b2d'],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => null,
                'function_name' => 'klingVideoGenerationTextToVideoStatus',
            ],
            [
                'name' => 'Video Generation Elements',
                'input_parameters' => [
                    'model' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'kling-elements-pro',
                        'options' => [
                            'source' => 'collection',
                            'collection_name' => 'service_provider_model',
                            'value_field' => 'model_name',
                            'label_field' => 'display_name',
                            'filters' => [
                                'service_provider_id' => $serviceProvider->id,
                                'status' => 'active',
                                'supports_search' => true,
                            ],
                            'fallback_options' => ['kling-elements-pro', 'kling-elements-std'],
                        ],
                        'description' => 'Model of the generated video.',
                        'example' => 'kling-elements-pro',
                    ],
                    'images[]' => ['type' => 'array', 'required' => true, 'description' => 'Array of up to 4 image URLs (publicly accessible)', 'items' => ['type' => 'string'], 'maxItems' => 4, 'example' => ['https://publiish.io/ipfs/QmePMNQ1BYCsaJwCpA4sbGpYxgiEznzJwPDMHir9FdUiYN', 'https://publiish.io/ipfs/QmRPNoFMcYFmzJuZgd4t3BDyfELAGCwNtGSb5i5AbXkcpf']],
                    'prompt' => ['type' => 'string', 'required' => false, 'maxLength' => 2500],
                    'negative_prompt' => ['type' => 'string', 'required' => false, 'maxLength' => 2500],
                    'duration' => ['type' => 'string', 'required' => false, 'options' => ['5', '10'], 'example' => '5'],
                    'aspect_ratio' => ['type' => 'string', 'required' => false, 'options' => ['widescreen_16_9', 'social_story_9_16', 'square_1_1']],
                ],
                'response' => [
                    'data' => [
                        'task_id' => 'd1686bd7-ffeb-4914-bffe-5a8db018ac3b',
                        'status' => 'CREATED',
                        'generated' => [],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => KlingElementsVideoRequest::class,
                'function_name' => 'klingElementsVideo',
            ],
            [
                'name' => 'Video Generation Elements Task Status',
                'path_parameters' => [
                    'task_id' => ['type' => 'string', 'required' => true, 'description' => 'ID of the video generation task', 'example' => '046b6c7f-0b8a-43b9-b35d-6489e6daee91'],
                ],
                'response' => [
                    'data' => [
                        'task_id' => '14eb9004-41ef-4a16-97f2-a558aab35578',
                        'status' => 'COMPLETED',
                        'generated' => ['https://ai-statics.freepik.com/content/mg-upscaler/syn56t4xlfgodjvkhlksx2t3be/output.png?token=exp=1751090771~hmac=8a99cf9fcbbecb72e35d8a6ddfea4b2d'],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => KlingElementsVideoStatusRequest::class,
                'function_name' => 'klingElementsVideoStatus',
            ],
            [
                'name' => 'Mystic LoRAs List',
                'input_parameters' => [],
                'response' => [
                    'data' => [
                        'default' => [
                            ['id' => 1, 'name' => 'vintage-japanese', 'description' => 'Expect bold red colors and a sense of nostalgia, bringing to life classic Japanese elements.', 'category' => 'illustration', 'type' => 'style', 'training' => ['status' => 'completed', 'defaultScale' => 1.2]],
                            ['id' => 2, 'name' => 'sara', 'description' => 'sara', 'category' => 'people', 'type' => 'character', 'training' => ['status' => 'completed', 'defaultScale' => 1.2]],
                            ['id' => 3, 'name' => 'glasses', 'description' => 'glasses', 'category' => 'product', 'type' => 'product', 'training' => ['status' => 'completed', 'defaultScale' => 1.2]],
                        ],
                        'customs' => [],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => null,
                'function_name' => 'getLoras',
            ],
            [
                'name' => 'Mystic Image Generation',
                'input_parameters' => [
                    'prompt' => ['type' => 'string', 'required' => true, 'description' => 'AI Model Prompt Description. The text that describes the image you want to generate.'],
                    'structure_reference' => ['type' => 'string', 'required' => false, 'description' => 'Base64 image to use as structure reference to influence the shape.'],
                    'structure_strength' => ['type' => 'integer', 'required' => false, 'min' => 0, 'max' => 100, 'default' => 50, 'description' => 'Strength to maintain the structure of the original image.'],
                    'style_reference' => ['type' => 'string', 'required' => false, 'description' => 'Base64 image to use as style reference to influence aesthetics.'],
                    'adherence' => ['type' => 'integer', 'required' => false, 'min' => 0, 'max' => 100, 'default' => 50, 'description' => 'Higher values make the generation more faithful to the prompt.'],
                    'hdr' => ['type' => 'integer', 'required' => false, 'min' => 0, 'max' => 100, 'default' => 50, 'description' => 'Controls image detail and "AI look" tradeoff.'],
                    'resolution' => ['type' => 'string', 'required' => false, 'options' => ['1k', '2k', '4k'], 'default' => '2k', 'description' => 'Resolution of the generated image.'],
                    'aspect_ratio' => ['type' => 'string', 'required' => false, 'options' => ['square_1_1', 'classic_4_3', 'traditional_3_4', 'widescreen_16_9', 'social_story_9_16', 'smartphone_horizontal_20_9', 'smartphone_vertical_9_20', 'standard_3_2', 'portrait_2_3', 'horizontal_2_1', 'vertical_1_2', 'social_5_4', 'social_post_4_5'], 'default' => 'square_1_1', 'description' => 'Aspect ratio of the generated image.'],
                    'model' => ['type' => 'string', 'required' => false, 'options' => ['realism', 'fluid', 'zen'], 'default' => 'realism', 'description' => 'Model to use for generation.'],
                    'creative_detailing' => ['type' => 'integer', 'required' => false, 'min' => 0, 'max' => 100, 'default' => 33, 'description' => 'Controls detail per pixel with tradeoff on HDR/artificial look.'],
                    'engine' => ['type' => 'string', 'required' => false, 'options' => ['automatic', 'magnific_illusio', 'magnific_sharpy', 'magnific_sparkle'], 'default' => 'automatic', 'description' => 'Engine choice for the AI model.'],
                    'fixed_generation' => ['type' => 'boolean', 'required' => false, 'default' => false, 'description' => 'If true, same input produces the same image (fixed randomness).'],
                    'filter_nsfw' => ['type' => 'boolean', 'required' => false, 'default' => true, 'description' => 'When enabled, NSFW images are replaced with a black image.'],
                    'styling' => [
                        'type' => 'object',
                        'required' => false,
                        'description' => 'Styling options for the image',
                        'properties' => [
                            'styles' => ['type' => 'array', 'maxItems' => 1, 'items' => ['type' => 'object', 'properties' => ['name' => ['type' => 'string', 'description' => 'Name of the style to apply'], 'strength' => ['type' => 'number', 'min' => 0, 'max' => 200, 'default' => 100, 'description' => 'Strength of the style']]]],
                            'characters' => ['type' => 'array', 'maxItems' => 1, 'items' => ['type' => 'object', 'properties' => ['id' => ['type' => 'string', 'description' => 'ID of the character'], 'strength' => ['type' => 'number', 'description' => 'Strength of the character']]]],
                            'colors' => ['type' => 'array', 'minItems' => 1, 'maxItems' => 5, 'items' => ['type' => 'object', 'properties' => ['color' => ['type' => 'string', 'pattern' => '^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$', 'description' => 'Hex color code, e.g. #FF0000'], 'weight' => ['type' => 'number', 'description' => 'Weight of the color in the generation']]]],
                        ],
                    ],
                ],
                'response' => [
                    'data' => [
                        'task_id' => 'd1686bd7-ffeb-4914-bffe-5a8db018ac3b',
                        'status' => 'CREATED',
                        'generated' => [],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => MysticGenerateRequest::class,
                'function_name' => 'generateMysticImage',
            ],
            [
                'name' => 'Mystic Image Generation Task Status',
                'path_parameters' => [
                    'task_id' => ['type' => 'string', 'required' => true, 'description' => 'ID of the Mystic generation task', 'example' => '046b6c7f-0b8a-43b9-b35d-6489e6daee91'],
                ],
                'response' => [
                    'data' => [
                        'task_id' => 'd1686bd7-ffeb-4914-bffe-5a8db018ac3b',
                        'status' => 'COMPLETED',
                        'generated' => ['https://ai-statics.freepik.com/content/mg-upscaler/hg37oeqmzjb2vng25rpj4gosxm/output.png?token=exp=1751089803~hmac=e733128992a3814c0dc28a8cc3f1ac64'],
                        'has_nsfw' => [false],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => null,
                'function_name' => 'getMysticTaskStatus',
            ],
            [
                'name' => 'LoRAs Custom Style Training',
                'input_parameters' => [
                    'name' => ['type' => 'string', 'required' => true, 'description' => 'Name of the LoRA style used to identify the style in the system.', 'example' => 'neon-cyberpunk-style'],
                    'quality' => ['type' => 'string', 'required' => true, 'options' => ['medium', 'high', 'ultra'], 'description' => 'Quality of the LoRA style.', 'example' => 'high'],
                    'images' => ['type' => 'array', 'required' => true, 'minItems' => 6, 'maxItems' => 20, 'description' => 'List of image URLs to train the LoRA style.', 'items' => ['type' => 'string', 'format' => 'uri']],
                    'description' => ['type' => 'string', 'required' => false, 'description' => 'Description of the LoRA style.', 'example' => 'A high-quality cyberpunk visual style with neon lights, futuristic elements, and strong contrast. Inspired by sci-fi films and urban night scenes.'],
                ],
                'response' => [
                    'data' => [
                        'task_id' => 'abe2666f-3977-4420-b591-082ba0b54790',
                        'status' => 'CREATED',
                        'generated' => [],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => LoraStyleTrainRequest::class,
                'function_name' => 'createLoraStyle',
            ],
            [
                'name' => 'LoRAs Custom Character Training',
                'input_parameters' => [
                    'name' => ['type' => 'string', 'required' => true, 'example' => 'cyber_hero_neo'],
                    'quality' => ['type' => 'string', 'required' => true, 'options' => ['medium', 'high', 'ultra'], 'description' => 'Quality of the LoRA character', 'example' => 'high'],
                    'gender' => ['type' => 'string', 'required' => true, 'options' => ['male', 'female', 'neutral', 'custom'], 'description' => 'Gender of the character', 'example' => 'male'],
                    'images' => ['type' => 'array', 'required' => true, 'minItems' => 8, 'maxItems' => 20, 'items' => ['type' => 'string', 'format' => 'uri']],
                    'description' => ['type' => 'string', 'required' => false, 'example' => 'A futuristic male character with a bold cyberpunk aesthetic, glowing eyes, and advanced tech gear. Suitable for sci-fi narratives, stylized storytelling, and visual AI applications.'],
                ],
                'response' => [
                    'data' => [
                        'task_id' => 'abe2666f-3977-4420-b591-082ba0b54790',
                        'status' => 'CREATED',
                        'generated' => [],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => LoraCharacterTrainRequest::class,
                'function_name' => 'trainLoraCharacter',
            ],
            [
                'name' => 'Classic Fast Image Generation',
                'input_parameters' => [
                    'prompt' => ['type' => 'string', 'required' => true, 'minLength' => 3, 'example' => 'Crazy dog in the space', 'description' => 'Text to generate image from'],
                    'negative_prompt' => ['type' => 'string', 'required' => false, 'minLength' => 3, 'example' => 'b&w, earth, cartoon, ugly', 'description' => 'Attributes to avoid in the generated image'],
                    'guidance_scale' => ['type' => 'number', 'required' => false, 'min' => 0, 'max' => 2, 'default' => 1.0, 'example' => 2, 'description' => 'Fidelity to the prompt'],
                    'seed' => ['type' => 'integer', 'required' => false, 'min' => 0, 'max' => 1000000, 'example' => 42, 'description' => 'Seed value for image reproducibility'],
                    'num_images' => ['type' => 'integer', 'required' => false, 'min' => 1, 'max' => 4, 'default' => 1, 'example' => 1, 'description' => 'Number of images to generate'],
                    'filter_nsfw' => ['type' => 'boolean', 'required' => false, 'default' => true, 'example' => true, 'description' => 'Filter NSFW content'],
                    'image' => [
                        'type' => 'object',
                        'required' => false,
                        'properties' => [
                            'size' => ['type' => 'string', 'options' => ['square_1_1', 'classic_4_3', 'traditional_3_4', 'widescreen_16_9', 'social_story_9_16', 'smartphone_horizontal_20_9', 'smartphone_vertical_9_20', 'standard_3_2', 'portrait_2_3', 'horizontal_2_1', 'vertical_1_2', 'social_5_4', 'social_post_4_5'], 'example' => 'square_1_1', 'description' => 'Aspect ratio of the image'],
                        ],
                    ],
                    'styling' => [
                        'type' => 'object',
                        'required' => false,
                        'properties' => [
                            'style' => ['type' => 'string', 'required' => false, 'options' => ['photo', 'digital-art', '3d', 'painting', 'low-poly', 'pixel-art', 'anime', 'cyberpunk', 'comic', 'vintage', 'cartoon', 'vector', 'studio-shot', 'dark', 'sketch', 'mockup', '2000s-pone', '70s-vibe', 'watercolor', 'art-nouveau', 'origami', 'surreal', 'fantasy', 'traditional-japan'], 'example' => 'anime', 'description' => 'Style to apply to the image'],
                            'effects' => [
                                'type' => 'object',
                                'required' => false,
                                'properties' => [
                                    'color' => ['type' => 'string', 'required' => false, 'options' => ['b&w', 'pastel', 'sepia', 'dramatic', 'vibrant', 'orange&teal', 'film-filter', 'split', 'electric', 'pastel-pink', 'gold-glow', 'autumn', 'muted-green', 'deep-teal', 'duotone', 'terracotta&teal', 'red&blue', 'cold-neon', 'burgundy&blue'], 'example' => 'pastel', 'description' => 'Effects - Color to apply'],
                                    'lightning' => ['type' => 'string', 'required' => false, 'options' => ['studio', 'warm', 'cinematic', 'volumetric', 'golden-hour', 'long-exposure', 'cold', 'iridescent', 'dramatic', 'hardlight', 'redscale', 'indoor-light'], 'example' => 'warm', 'description' => 'Effects - Lightning to apply'],
                                    'framing' => ['type' => 'string', 'required' => false, 'options' => ['portrait', 'macro', 'panoramic', 'aerial-view', 'close-up', 'cinematic', 'high-angle', 'low-angle', 'symmetry', 'fish-eye', 'first-person'], 'example' => 'portrait', 'description' => 'Effects - Framing to apply'],
                                ],
                            ],
                            'colors' => [
                                'type' => 'array',
                                'required' => false,
                                'minItems' => 1,
                                'maxItems' => 5,
                                'items' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'color' => ['type' => 'string', 'pattern' => '^#([A-Fa-f0-9]{6})$', 'example' => '#FF5733', 'description' => 'Hex color code'],
                                        'weight' => ['type' => 'number', 'min' => 0.05, 'max' => 1.0, 'example' => 1, 'description' => 'Weight of the color (0.05 - 1.0)'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'response' => [
                    'data' => [['base64' => '4AAQSkZJRgABAQAAAQABAAD00rU5WmFaCGQEUhFTFaTZQFj/2Q==', 'has_nsfw' => false]],
                    'meta' => ['prompt' => 'Crazy dog in the space', 'seed' => 42, 'image' => ['size' => 'square_1_1', 'width' => 1024, 'height' => 1024], 'num_inference_steps' => 8, 'guidance_scale' => 2],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => ClassicFastGenerateRequest::class,
                'function_name' => 'generateClassicFastImage',
            ],
            [
                'name' => 'Google Imagen 3 Image Generation',
                'input_parameters' => [
                    'prompt' => ['type' => 'string', 'required' => true, 'example' => 'Crazy dog in the space'],
                    'num_images' => ['type' => 'integer', 'required' => false, 'min' => 1, 'max' => 4, 'example' => 1],
                    'aspect_ratio' => ['type' => 'string', 'required' => false, 'options' => ['square_1_1', 'social_story_9_16', 'widescreen_16_9', 'traditional_3_4', 'classic_4_3'], 'example' => 'square_1_1'],
                    'styling' => [
                        'type' => 'object',
                        'required' => false,
                        'properties' => [
                            'style' => ['type' => 'string', 'required' => false, 'options' => ['photo', 'digital-art', '3d', 'painting', 'low-poly', 'pixel-art', 'anime', 'cyberpunk', 'comic', 'vintage', 'cartoon', 'vector', 'studio-shot', 'dark', 'sketch', 'mockup', '2000s-pone', '70s-vibe', 'watercolor', 'art-nouveau', 'origami', 'surreal', 'fantasy', 'traditional-japan'], 'example' => 'anime'],
                            'effects' => [
                                'type' => 'object',
                                'required' => false,
                                'properties' => [
                                    'color' => ['type' => 'string', 'required' => false, 'options' => ['b&w', 'pastel', 'sepia', 'dramatic', 'vibrant', 'orange&teal', 'film-filter', 'split', 'electric', 'pastel-pink', 'gold-glow', 'autumn', 'muted-green', 'deep-teal', 'duotone', 'terracotta&teal', 'red&blue', 'cold-neon', 'burgundy&blue'], 'example' => 'pastel'],
                                    'lightning' => ['type' => 'string', 'required' => false, 'options' => ['studio', 'warm', 'cinematic', 'volumetric', 'golden-hour', 'long-exposure', 'cold', 'iridescent', 'dramatic', 'hardlight', 'redscale', 'indoor-light'], 'example' => 'warm'],
                                    'framing' => ['type' => 'string', 'required' => false, 'options' => ['portrait', 'macro', 'panoramic', 'aerial-view', 'close-up', 'cinematic', 'high-angle', 'low-angle', 'symmetry', 'fish-eye', 'first-person'], 'example' => 'portrait'],
                                ],
                            ],
                            'colors' => [
                                'type' => 'array',
                                'required' => false,
                                'minItems' => 1,
                                'maxItems' => 5,
                                'items' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'color' => ['type' => 'string', 'example' => '#FF0000', 'description' => 'Hex color code'],
                                        'weight' => ['type' => 'number', 'min' => 0.05, 'max' => 1.0, 'example' => 0.5, 'description' => 'Weight of the color (0.05 to 1)'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'person_generation' => ['type' => 'string', 'required' => false, 'options' => ['dont_allow', 'allow_adult', 'allow_all'], 'example' => 'allow_adult'],
                    'safety_settings' => ['type' => 'string', 'required' => false, 'options' => ['block_low_and_above', 'block_medium_and_above', 'block_only_high', 'block_none'], 'example' => 'block_low_and_above'],
                ],
                'response' => [
                    'data' => [
                        'task_id' => 'd1686bd7-ffeb-4914-bffe-5a8db018ac3b',
                        'status' => 'CREATED',
                        'generated' => [],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => Imagen3GenerateRequest::class,
                'function_name' => 'generateImagen3',
            ],
            [
                'name' => 'Google Imagen 3 Image Generation Task Status',
                'path_parameters' => [
                    'task_id' => ['type' => 'string', 'required' => true, 'description' => 'ID of the Imagen3 generation task', 'example' => '046b6c7f-0b8a-43b9-b35d-6489e6daee91'],
                ],
                'response' => [
                    'data' => [
                        'task_id' => 'd20eeb40-11fc-4402-a9da-d06eb9c66f23',
                        'status' => 'COMPLETED',
                        'generated' => ['https://cdn-magnific.freepik.com/imagen3_d20eeb40-11fc-4402-a9da-d06eb9c66f23_0.png?token=exp=1751007773~hmac=0efd3dde93656145f6b951922491d150e3e4a1cb8aba2ab06144d3629060215d'],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => null,
                'function_name' => 'getImagen3TaskStatus',
            ],
            [
                'name' => 'Flux Dev Image Generation',
                'input_parameters' => [
                    'prompt' => ['type' => 'string', 'required' => false, 'example' => 'A futuristic city floating in the sky'],
                    'aspect_ratio' => ['type' => 'string', 'required' => false, 'options' => ['square_1_1', 'classic_4_3', 'traditional_3_4', 'widescreen_16_9', 'social_story_9_16', 'standard_3_2', 'portrait_2_3', 'horizontal_2_1', 'vertical_1_2', 'social_post_4_5'], 'example' => 'square_1_1'],
                    'styling' => [
                        'type' => 'object',
                        'required' => false,
                        'properties' => [
                            'effects' => [
                                'type' => 'object',
                                'required' => false,
                                'properties' => [
                                    'color' => ['type' => 'string', 'required' => false, 'options' => ['softhue', 'b&w', 'goldglow', 'vibrant', 'coldneon'], 'example' => 'softhue'],
                                    'framing' => ['type' => 'string', 'required' => false, 'options' => ['portrait', 'lowangle', 'midshot', 'wideshot', 'tiltshot', 'aerial'], 'example' => 'portrait'],
                                    'lightning' => ['type' => 'string', 'required' => false, 'options' => ['iridescent', 'dramatic', 'goldenhour', 'longexposure', 'indorlight', 'flash', 'neon'], 'example' => 'iridescent'],
                                ],
                            ],
                            'colors' => [
                                'type' => 'array',
                                'required' => false,
                                'minItems' => 1,
                                'maxItems' => 5,
                                'items' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'color' => ['type' => 'string', 'example' => '#FF0000'],
                                        'weight' => ['type' => 'number', 'min' => 0.05, 'max' => 1.0, 'example' => 0.5],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'seed' => ['type' => 'integer', 'required' => false, 'min' => 1, 'max' => 4294967295, 'example' => 2147483648],
                ],
                'response' => [
                    'data' => [
                        'task_id' => 'd1686bd7-ffeb-4914-bffe-5a8db018ac3b',
                        'status' => 'CREATED',
                        'generated' => [],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => FluxDevGenerateRequest::class,
                'function_name' => 'generateFluxDevImage',
            ],
            [
                'name' => 'Freepik Flux Dev Task Statu',
                'path_parameters' => [
                    'task_id' => ['type' => 'string', 'required' => true, 'description' => 'ID of the Flux Dev generation task', 'example' => '046b6c7f-0b8a-43b9-b35d-6489e6daee91'],
                ],
                'response' => [
                    'data' => [
                        'task_id' => 'd20eeb40-11fc-4402-a9da-d06eb9c66f23',
                        'status' => 'COMPLETED',
                        'generated' => ['https://cdn-magnific.freepik.com/imagen3_d20eeb40-11fc-4402-a9da-d06eb9c66f23_0.png?token=exp=1751007773~hmac=0efd3dde93656145f6b951922491d150e3e4a1cb8aba2ab06144d3629060215d'],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => null,
                'function_name' => 'getFluxDevTaskStatus',
            ],
            [
                'name' => 'Reimagine Flux Image Generation',
                'input_parameters' => [
                    'image' => ['type' => 'string', 'required' => true, 'format' => 'byte', 'description' => 'Base64-encoded input image', 'example' => 'https://publiish.io/ipfs/QmPE5opZZhpeHypZzG3qJE5cbCNZ28SibBa9xo4MqsgF9H'],
                    'prompt' => ['type' => 'string', 'required' => false, 'description' => 'Optional prompt for imagination', 'example' => 'A beautiful sunset over a calm ocean'],
                    'imagination' => ['type' => 'string', 'required' => false, 'options' => ['wild', 'subtle', 'vivid'], 'description' => 'Imagination type', 'example' => 'wild'],
                    'aspect_ratio' => ['type' => 'string', 'required' => false, 'options' => ['original', 'square_1_1', 'classic_4_3', 'traditional_3_4', 'widescreen_16_9', 'social_story_9_16', 'standard_3_2', 'portrait_2_3', 'horizontal_2_1', 'vertical_1_2', 'social_post_4_5'], 'description' => 'Aspect ratio of the generated image', 'example' => 'square_1_1'],
                ],
                'response' => [
                    'data' => [
                        'task_id' => 'a44b311d-7bb4-4ebe-8150-ae01947162c0',
                        'status' => 'COMPLETED',
                        'generated' => ['https://storage.googleapis.com/fc-magnific/a44b311d-7bb4-4ebe-8150-ae01947162c0.jpeg'],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => ReimagineFluxRequest::class,
                'function_name' => 'reimagineFlux',
            ],
            [
                'name' => 'Upscale Image Editing',
                'input_parameters' => [
                    'image' => ['type' => 'string', 'required' => true, 'description' => 'URL of the image to upscale', 'example' => 'https://publiish.io/ipfs/QmPE5opZZhpeHypZzG3qJE5cbCNZ28SibBa9xo4MqsgF9H'],
                    'scale_factor' => ['type' => 'string', 'required' => false, 'options' => ['2x', '4x', '8x', '16x'], 'example' => '2x'],
                    'optimized_for' => ['type' => 'string', 'required' => false, 'options' => ['standard', 'soft_portraits', 'hard_portraits', 'art_n_illustration', 'videogame_assets', 'nature_n_landscapes', 'films_n_photography', '3d_renders', 'science_fiction_n_horror'], 'example' => 'standard'],
                    'prompt' => ['type' => 'string', 'required' => false, 'example' => 'A vivid and high-detail fantasy landscape with towering crystal mountains, glowing waterfalls, and enchanted forests under a twilight sky'],
                    'creativity' => ['type' => 'integer', 'required' => false, 'min' => -10, 'max' => 10, 'example' => 5],
                    'hdr' => ['type' => 'integer', 'required' => false, 'min' => -10, 'max' => 10, 'example' => 3],
                    'resemblance' => ['type' => 'integer', 'required' => false, 'min' => -10, 'max' => 10, 'example' => 0],
                    'fractality' => ['type' => 'integer', 'required' => false, 'min' => -10, 'max' => 10, 'example' => -2],
                    'engine' => ['type' => 'string', 'required' => false, 'options' => ['automatic', 'magnific_illusio', 'magnific_sharpy', 'magnific_sparkle'], 'example' => 'automatic'],
                ],
                'response' => [
                    'data' => [
                        'task_id' => '14eb9004-41ef-4a16-97f2-a558aab35578',
                        'status' => 'CREATED',
                        'generated' => [],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => UpscaleImageRequest::class,
                'function_name' => 'upscale',
            ],
            [
                'name' => 'Upscale Image Editing Task Status',
                'path_parameters' => [
                    'task_id' => ['type' => 'string', 'required' => true, 'description' => 'ID of the upscaling task', 'example' => '046b6c7f-0b8a-43b9-b35d-6489e6daee91'],
                ],
                'response' => [
                    'data' => [
                        'task_id' => '14eb9004-41ef-4a16-97f2-a558aab35578',
                        'status' => 'COMPLETED',
                        'generated' => ['https://ai-statics.freepik.com/content/mg-upscaler/syn56t4xlfgodjvkhlksx2t3be/output.png?token=exp=1751090771~hmac=8a99cf9fcbbecb72e35d8a6ddfea4b2d'],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => null,
                'function_name' => 'getUpscalerTaskStatus',
            ],
            [
                'name' => 'Relight Image Editing',
                'input_parameters' => [
                    'image' => ['type' => 'string', 'required' => true, 'description' => 'Base64 image to relight', 'example' => 'https://publiish.io/ipfs/QmPE5opZZhpeHypZzG3qJE5cbCNZ28SibBa9xo4MqsgF9H'],
                    'prompt' => ['type' => 'string', 'required' => false, 'example' => 'A sunlit forest clearing at golden hour with rays piercing through the trees'],
                    'transfer_light_from_reference_image' => ['type' => 'string', 'required' => false, 'example' => 'https://publiish.io/ipfs/QmTjCdTXQ2M1JPQHMuciYGQ2BWLVXum73PEJ8KY1znV4TV'],
                    'transfer_light_from_lightmap' => ['type' => 'string', 'required' => false, 'example' => 'https://publiish.io/ipfs/QmPnAKihJS1shKqnA4UqQ6bvkw29j8yFW4MJTb6KZA1e6Q'],
                    'light_transfer_strength' => ['type' => 'integer', 'required' => false, 'min' => 0, 'max' => 100, 'default' => 100, 'example' => 100],
                    'interpolate_from_original' => ['type' => 'boolean', 'required' => false, 'default' => false, 'example' => false],
                    'change_background' => ['type' => 'boolean', 'required' => false, 'default' => true, 'example' => true],
                    'style' => ['type' => 'string', 'required' => false, 'options' => ['standard', 'darker_but_realistic', 'clean', 'smooth', 'brighter', 'contrasted_n_hdr', 'just_composition'], 'default' => 'standard', 'example' => 'standard'],
                    'preserve_details' => ['type' => 'boolean', 'required' => false, 'default' => true, 'example' => true],
                    'advanced_settings' => [
                        'type' => 'object',
                        'required' => false,
                        'properties' => [
                            'whites' => ['type' => 'integer', 'required' => false, 'min' => 0, 'max' => 100, 'default' => 50, 'example' => 50],
                            'blacks' => ['type' => 'integer', 'required' => false, 'min' => 0, 'max' => 100, 'default' => 50, 'example' => 50],
                            'brightness' => ['type' => 'integer', 'required' => false, 'min' => 0, 'max' => 100, 'default' => 50, 'example' => 50],
                            'contrast' => ['type' => 'integer', 'required' => false, 'min' => 0, 'max' => 100, 'default' => 50, 'example' => 50],
                            'saturation' => ['type' => 'integer', 'required' => false, 'min' => 0, 'max' => 100, 'default' => 50, 'example' => 50],
                            'engine' => ['type' => 'string', 'required' => false, 'options' => ['automatic', 'balanced', 'cool', 'real', 'illusio', 'fairy', 'colorful_anime', 'hard_transform', 'softy'], 'default' => 'automatic', 'example' => 'automatic'],
                            'transfer_light_a' => ['type' => 'string', 'required' => false, 'options' => ['automatic', 'low', 'medium', 'normal', 'high', 'high_on_faces'], 'default' => 'automatic', 'example' => 'automatic'],
                            'transfer_light_b' => ['type' => 'string', 'required' => false, 'options' => ['automatic', 'composition', 'straight', 'smooth_in', 'smooth_out', 'smooth_both', 'reverse_both', 'soft_in', 'soft_out', 'soft_mid', 'strong_mid', 'style_shift', 'strong_shift'], 'default' => 'automatic', 'example' => 'automatic'],
                            'fixed_generation' => ['type' => 'boolean', 'required' => false, 'default' => false, 'example' => false],
                        ],
                    ],
                ],
                'response' => [
                    'data' => [
                        'task_id' => 'd1686bd7-ffeb-4914-bffe-5a8db018ac3b',
                        'status' => 'CREATED',
                        'generated' => [],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => RelightImageRequest::class,
                'function_name' => 'relight',
            ],
            [
                'name' => 'Relight Image Editing Task Status',
                'path_parameters' => [
                    'task_id' => ['type' => 'string', 'required' => true, 'description' => 'ID of the Relight task', 'example' => '046b6c7f-0b8a-43b9-b35d-6489e6daee91'],
                ],
                'response' => [
                    'data' => [
                        'task_id' => '14eb9004-41ef-4a16-97f2-a558aab35578',
                        'status' => 'COMPLETED',
                        'generated' => ['https://ai-statics.freepik.com/content/mg-upscaler/syn56t4xlfgodjvkhlksx2t3be/output.png?token=exp=1751090771~hmac=8a99cf9fcbbecb72e35d8a6ddfea4b2d'],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => null,
                'function_name' => 'getRelightTaskStatus',
            ],
            [
                'name' => 'Style Transfer Image Editing',
                'input_parameters' => [
                    'image' => ['type' => 'string', 'required' => true, 'description' => 'Base64 Image to style transfer', 'example' => 'https://publiish.io/ipfs/QmPE5opZZhpeHypZzG3qJE5cbCNZ28SibBa9xo4MqsgF9H'],
                    'reference_image' => ['type' => 'string', 'required' => true, 'description' => 'Base64 Reference image for style transfer', 'example' => 'https://publiish.io/ipfs/QmTjCdTXQ2M1JPQHMuciYGQ2BWLVXum73PEJ8KY1znV4TV'],
                    'prompt' => ['type' => 'string', 'required' => false, 'example' => 'A peaceful mountain cabin at sunrise, surrounded by pine trees and light morning mist'],
                    'style_strength' => ['type' => 'integer', 'required' => false, 'min' => 0, 'max' => 100, 'default' => 100, 'example' => 100],
                    'structure_strength' => ['type' => 'integer', 'required' => false, 'min' => 0, 'max' => 100, 'default' => 50, 'example' => 50],
                    'is_portrait' => ['type' => 'boolean', 'required' => false, 'default' => false, 'example' => false],
                    'portrait_style' => ['type' => 'string', 'required' => false, 'options' => ['standard', 'pop', 'super_pop'], 'default' => 'standard', 'description' => 'Portrait style', 'example' => 'standard'],
                    'portrait_beautifier' => ['type' => 'string', 'required' => false, 'options' => ['beautify_face', 'beautify_face_max'], 'description' => 'Portrait beautifier', 'example' => 'beautify_face'],
                    'flavor' => ['type' => 'string', 'required' => false, 'options' => ['faithful', 'gen_z', 'psychedelia', 'detaily', 'clear', 'donotstyle', 'donotstyle_sharp'], 'default' => 'faithful', 'description' => 'Flavor of the transferring style', 'example' => 'faithful'],
                    'engine' => ['type' => 'string', 'required' => false, 'options' => ['balanced', 'definio', 'illusio', '3d_cartoon', 'colorful_anime', 'caricature', 'real', 'super_real', 'softy'], 'default' => 'balanced', 'description' => 'Engine for style transfer', 'example' => 'balanced'],
                    'fixed_generation' => ['type' => 'boolean', 'required' => false, 'default' => false, 'description' => 'Fixed generation flag', 'example' => false],
                ],
                'response' => [
                    'data' => [
                        'task_id' => 'd1686bd7-ffeb-4914-bffe-5a8db018ac3b',
                        'status' => 'CREATED',
                        'generated' => [],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => StyleTransferRequest::class,
                'function_name' => 'styleTransfer',
            ],
            [
                'name' => 'Style Transfer Image Editing Task Status',
                'path_parameters' => [
                    'task_id' => ['type' => 'string', 'required' => true, 'description' => 'ID of the Style Transfer task', 'example' => '046b6c7f-0b8a-43b9-b35d-6489e6daee91'],
                ],
                'response' => [
                    'data' => [
                        'task_id' => '14eb9004-41ef-4a16-97f2-a558aab35578',
                        'status' => 'COMPLETED',
                        'generated' => ['https://ai-statics.freepik.com/content/mg-upscaler/syn56t4xlfgodjvkhlksx2t3be/output.png?token=exp=1751090771~hmac=8a99cf9fcbbecb72e35d8a6ddfea4b2d'],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => null,
                'function_name' => 'getStyleTransferTaskStatus',
            ],
            [
                'name' => 'Remove Background Image Editing',
                'input_parameters' => [
                    'image_url' => ['type' => 'string', 'required' => true, 'description' => 'The URL of the image whose background needs to be removed', 'example' => 'https://publiish.io/ipfs/QmPE5opZZhpeHypZzG3qJE5cbCNZ28SibBa9xo4MqsgF9H'],
                ],
                'response' => [
                    'original' => 'https://api.freepik.com/v1/ai/beta/images/original/f6ff89df-f14e-4eca-936a-308ef404cfa8/thumbnail.jpg',
                    'high_resolution' => 'https://api.freepik.com/v1/ai/beta/images/download/f6ff89df-f14e-4eca-936a-308ef404cfa8/high.png',
                    'preview' => 'https://api.freepik.com/v1/ai/beta/images/download/f6ff89df-f14e-4eca-936a-308ef404cfa8/preview.png',
                    'url' => 'https://api.freepik.com/v1/ai/beta/images/download/f6ff89df-f14e-4eca-936a-308ef404cfa8/high.png',
                ],
                'response_path' => ['final_result' => '$'],
                'request_class_name' => RemoveBackgroundRequest::class,
                'function_name' => 'removeBackgroundFromImage',
            ],
            [
                'name' => 'Expand Image Editing',
                'input_parameters' => [
                    'image' => ['type' => 'string', 'required' => true, 'description' => 'Base64 image to expand', 'example' => 'https://publiish.io/ipfs/QmPE5opZZhpeHypZzG3qJE5cbCNZ28SibBa9xo4MqsgF9H'],
                    'prompt' => ['type' => 'string', 'required' => false, 'description' => 'Description to guide expansion', 'example' => 'A panoramic view of a serene beach with gentle waves, golden sand, and a vibrant sunset sky extending beyond the frame'],
                    'left' => ['type' => 'integer', 'required' => false, 'min' => 0, 'max' => 2048, 'description' => 'Pixels to expand on the left', 'example' => 2048],
                    'right' => ['type' => 'integer', 'required' => false, 'min' => 0, 'max' => 2048, 'description' => 'Pixels to expand on the right', 'example' => 2048],
                    'top' => ['type' => 'integer', 'required' => false, 'min' => 0, 'max' => 2048, 'description' => 'Pixels to expand on the top', 'example' => 2048],
                    'bottom' => ['type' => 'integer', 'required' => false, 'min' => 0, 'max' => 2048, 'description' => 'Pixels to expand on the bottom', 'example' => 2048],
                ],
                'response' => [
                    'data' => [
                        'task_id' => 'd1686bd7-ffeb-4914-bffe-5a8db018ac3b',
                        'status' => 'CREATED',
                        'generated' => [],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => ImageExpandFluxProRequest::class,
                'function_name' => 'imageExpandFluxPro',
            ],
            [
                'name' => 'Expand Image Editing Task Status',
                'path_parameters' => [
                    'task_id' => ['type' => 'string', 'required' => true, 'description' => 'ID of the image expand task', 'example' => '046b6c7f-0b8a-43b9-b35d-6489e6daee91'],
                ],
                'response' => [
                    'data' => [
                        'task_id' => '14eb9004-41ef-4a16-97f2-a558aab35578',
                        'status' => 'COMPLETED',
                        'generated' => ['https://ai-statics.freepik.com/content/mg-upscaler/syn56t4xlfgodjvkhlksx2t3be/output.png?token=exp=1751090771~hmac=8a99cf9fcbbecb72e35d8a6ddfea4b2d'],
                    ],
                ],
                'response_path' => ['final_result' => '$.data'],
                'request_class_name' => null,
                'function_name' => 'getImageExpandFluxProTaskStatus',
            ],
        ];

        foreach ($serviceTypes as $serviceType) {
            $serviceType = ServiceType::updateOrCreate(
                [
                    'name' => $serviceType['name'],
                    'service_provider_id' => $serviceProvider->id,
                ],
                [
                    'input_parameters' => $serviceType['input_parameters'] ?? null,
                    'path_parameters' => $serviceType['path_parameters'] ?? null,
                    'request_class_name' => $serviceType['request_class_name'],
                    'function_name' => $serviceType['function_name'],
                    'response' => $serviceType['response'],
                    'response_path' => $serviceType['response_path'],
                ]
            );

            if (!empty($serviceType['input_parameters']['model']['options']['fallback_options'])) {
                foreach ($serviceType['input_parameters']['model']['options']['fallback_options'] as $model) {
                    ServiceProviderModel::updateOrCreate(
                        [
                            'name' => $model,
                            'service_provider_id' => $serviceProvider->id,
                            'service_type_id' => $serviceType->id,
                        ]
                    );
                }
            }
        }
    }
}
