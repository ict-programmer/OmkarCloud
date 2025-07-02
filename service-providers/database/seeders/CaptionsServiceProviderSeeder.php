<?php

namespace Database\Seeders;

use App\Http\Controllers\CaptionsController;
use App\Http\Requests\Captions\AiAdsPollRequest;
use App\Http\Requests\Captions\AiAdsSubmitRequest;
use App\Http\Requests\Captions\AiCreatorPollRequest;
use App\Http\Requests\Captions\AiCreatorSubmitRequest;
use App\Http\Requests\Captions\AiTranslatePollRequest;
use App\Http\Requests\Captions\AiTranslateSubmitRequest;
use App\Http\Requests\Captions\AiTwinCreateRequest;
use App\Http\Requests\Captions\AiTwinDeleteRequest;
use App\Http\Requests\Captions\AiTwinScriptRequest;
use App\Http\Requests\Captions\AiTwinStatusRequest;
use App\Models\ServiceProvider;
use App\Traits\ServiceProviderSeederTrait;
use Illuminate\Database\Seeder;

class CaptionsServiceProviderSeeder extends Seeder
{
    use ServiceProviderSeederTrait;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => 'Captions'],
            [
                'parameters' => [
                    'api_key' => 'YOUR_API_KEY',
                    'base_url' => 'https://api.captions.ai/api',
                    'version' => null,
                    'models_supported' => [],
                    'features' => [
                        'AI Creator',
                        'AI Video Translation',
                        'AI Ads Generation',
                        'AI Twin',
                    ],
                ],
                'is_active' => true,
                'controller_name' => CaptionsController::class,
            ]
        );

        $serviceTypes = [
            [
                'name' => 'List available AI Creators',
                'input_parameters' => [],
                'response' => [
                    'supportedCreators' => ['Jason', 'Grace-1'],
                    'thumbnails' => [
                        'Jason' => [
                            'imageUrl' => 'https://captions-cdn.xyz/ai-avatars/ugc/v2/oo0RAIESofBfKGEes2BL/thumb.jpg',
                            'videoUrl' => 'https://captions-cdn.xyz/ai-avatars/ugc/v2/oo0RAIESofBfKGEes2BL/preview.mp4',
                        ],
                        'Grace-1' => [
                            'imageUrl' => 'https://captions-cdn.xyz/ai-avatars/ugc/v2/hKUDZndVfPfMT3YUTYSd/thumb.jpg',
                            'videoUrl' => 'https://captions-cdn.xyz/ai-avatars/ugc/v2/hKUDZndVfPfMT3YUTYSd/preview.mp4',
                        ],
                        'Grace-2' => [
                            'imageUrl' => 'https://captions-cdn.xyz/ai-avatars/ugc/v2/Nmxmr9QhaNeJB1CqNuxO/thumb.jpg',
                            'videoUrl' => 'https://captions-cdn.xyz/ai-avatars/ugc/v2/Nmxmr9QhaNeJB1CqNuxO/preview.mp4',
                        ],
                    ],
                ],
                'response_path' => ['final_result' => '$'],
                'request_class_name' => null,
                'function_name' => 'listCreators',
            ],
            [
                'name' => 'AI Creator',
                'input_parameters' => [
                    'script' => ['type' => 'string', 'required' => true, 'maxLength' => 800, 'description' => 'Script for the AI Creator (max 800 characters)', 'example' => 'Hello, welcome to our brand new tutorial!'],
                    'creatorName' => ['type' => 'string', 'required' => false, 'description' => 'Name of the AI Creator. Default is "Kate".', 'example' => 'Kate'],
                    'resolution' => ['type' => 'string', 'required' => false, 'options' => ['fhd', '4k'], 'description' => 'Desired output resolution (default is 4k).', 'example' => 'fhd'],
                ],
                'response' => ['operationId' => 'tg7FaiVgmGqTQfuDhyGA'],
                'response_path' => ['final_result' => '$.operationId'],
                'request_class_name' => AiCreatorSubmitRequest::class,
                'function_name' => 'submitCreatorVideo',
            ],
            [
                'name' => 'AI Creator Status',
                'input_parameters' => [
                    'operationId' => ['type' => 'string', 'required' => true, 'description' => 'The unique operation ID of the submitted video generation task.', 'example' => 'tg7FaiVgmGqTQfuDhyGA'],
                ],
                'response' => [
                    'url' => 'https://storage.googleapis.com/captions-avatar-orc/orc/studio/video_clip__crop_video/aiA8TwJfHR6TbiTlQZgC/69f3642a-fdb4-47e1-9552-5e22911482de/cropped_result.mp4',
                    'state' => 'COMPLETE',
                ],
                'response_path' => ['final_result' => '$'],
                'request_class_name' => AiCreatorPollRequest::class,
                'function_name' => 'pollCreatorStatus',
            ],
            [
                'name' => 'List supported languages for AI Translate',
                'input_parameters' => [],
                'response' => ['supportedLanguages' => ['Arabic', 'Baby']],
                'response_path' => ['final_result' => '$.supportedLanguages'],
                'request_class_name' => null,
                'function_name' => 'getSupportedLanguages',
            ],
            [
                'name' => 'AI Translate',
                'input_parameters' => [
                    'videoUrl' => ['type' => 'string', 'required' => true, 'format' => 'url', 'description' => 'Public direct link to the video.', 'example' => 'https://publiish.io/ipfs/QmXc3tZ8bKpwnetxSeXadVkYKtPdVQqByHxaMD1Cs2Cika'],
                    'sourceLanguage' => ['type' => 'string', 'required' => true, 'description' => 'Language spoken in the original video.', 'example' => 'English'],
                    'targetLanguage' => ['type' => 'string', 'required' => true, 'description' => 'Desired translation language.', 'example' => 'Japanese'],
                ],
                'response' => ['operationId' => 'GHOk0bF4wCZ2V5ntWLpG'],
                'response_path' => ['final_result' => '$.operationId'],
                'request_class_name' => AiTranslateSubmitRequest::class,
                'function_name' => 'submitVideoTranslation',
            ],
            [
                'name' => 'AI Translate Status',
                'input_parameters' => [
                    'operationId' => ['type' => 'string', 'required' => true, 'description' => 'The operation ID returned when the translation was submitted.', 'example' => 'GHOk0bF4wCZ2V5ntWLpG'],
                ],
                'response' => [
                    'url' => 'https://storage.googleapis.com/captions-autolipdub/SKBSas0SgowfnH8EGH2y/ac3b781d-036b-4d40-a851-701aa7473589_stitched_video.mp4',
                    'state' => 'COMPLETE',
                ],
                'response_path' => ['final_result' => '$'],
                'request_class_name' => AiTranslatePollRequest::class,
                'function_name' => 'pollTranslationStatus',
            ],
            [
                'name' => 'List available AI Ad Creators',
                'input_parameters' => [],
                'response' => [
                    'supportedCreators' => ['Jason', 'Grace-1', 'Grace-2'],
                    'thumbnails' => [
                        'Jason' => ['imageUrl' => 'https://captions-cdn.xyz/ai-avatars/ugc/v2/oo0RAIESofBfKGEes2BL/thumb.jpg', 'videoUrl' => 'https://captions-cdn.xyz/ai-avatars/ugc/v2/oo0RAIESofBfKGEes2BL/preview.mp4'],
                        'Grace-1' => ['imageUrl' => 'https://captions-cdn.xyz/ai-avatars/ugc/v2/hKUDZndVfPfMT3YUTYSd/thumb.jpg', 'videoUrl' => 'https://captions-cdn.xyz/ai-avatars/ugc/v2/hKUDZndVfPfMT3YUTYSd/preview.mp4'],
                        'Grace-2' => ['imageUrl' => 'https://captions-cdn.xyz/ai-avatars/ugc/v2/Nmxmr9QhaNeJB1CqNuxO/thumb.jpg', 'videoUrl' => 'https://captions-cdn.xyz/ai-avatars/ugc/v2/Nmxmr9QhaNeJB1CqNuxO/preview.mp4'],
                    ],
                ],
                'response_path' => ['final_result' => '$'],
                'request_class_name' => null,
                'function_name' => 'listAdsCreators',
            ],
            [
                'name' => 'AI Ads',
                'input_parameters' => [
                    'script' => ['type' => 'string', 'required' => true, 'maxLength' => 800, 'description' => 'Script for the AI Ad (max 800 characters)', 'example' => 'Introducing our latest product!'],
                    'creatorName' => ['type' => 'string', 'required' => true, 'description' => 'Name of the AI Creator.', 'example' => 'Jason'],
                    'mediaUrls' => ['type' => 'array', 'required' => true, 'description' => 'URLs to media files (JPEG, PNG, MOV, MP4). Minimum 1, maximum 10.', 'minItems' => 1, 'maxItems' => 10, 'items' => ['type' => 'string', 'format' => 'url'], 'example' => ['https://publiish.io/ipfs/QmVeajukWWf2Yy6oQD61YGJywqfZrm5kbRR31iKQQQahwJ', 'https://publiish.io/ipfs/QmYdLz3cJr49qPh2vyZn55W6NwsAmLQpzrUZyHFjE3dmaY', 'https://publiish.io/ipfs/QmaHawrk9Lp1PzF3mh9HdpyaSiTUepmAgaj5Ai7DDTyuAK']],
                    'resolution' => ['type' => 'string', 'required' => false, 'options' => ['fhd', '4k'], 'description' => 'Desired output resolution (default is 4k).', 'example' => 'fhd'],
                ],
                'response' => ['operationId' => 'R61piXz8jjxcBuQgK7UM'],
                'response_path' => ['final_result' => '$.operationId'],
                'request_class_name' => AiAdsSubmitRequest::class,
                'function_name' => 'submitAdVideo',
            ],
            [
                'name' => 'AI Ads Status',
                'input_parameters' => [
                    'operationId' => ['type' => 'string', 'required' => true, 'description' => 'The unique operation ID of the submitted ad generation task.', 'example' => 'R61piXz8jjxcBuQgK7UM'],
                ],
                'response' => [
                    'url' => 'https://storage.googleapis.com/captions-avatar-orc/orc/studio/writer__ugc_variant_result/LHElp6sX7OlrIh6DyoNA/52f97ad4-6b0c-4a9e-b35d-4e664e3a6a57/hd_result.mp4',
                    'state' => 'COMPLETE',
                ],
                'response_path' => ['final_result' => '$'],
                'request_class_name' => AiAdsPollRequest::class,
                'function_name' => 'pollAdVideoStatus',
            ],
            [
                'name' => 'List supported languages for AI Twin',
                'input_parameters' => [],
                'response' => ['supportedLanguages' => ['Arabic', 'Chinese-Simplified', 'Chinese-Traditional']],
                'response_path' => ['final_result' => '$.supportedLanguages'],
                'request_class_name' => null,
                'function_name' => 'getTwinSupportedLanguages',
            ],
            [
                'name' => 'List AI Twins',
                'input_parameters' => [],
                'response' => ['twins' => ['TwinA', 'TwinB', 'TwinC']],
                'response_path' => ['final_result' => '$.twins'],
                'request_class_name' => null,
                'function_name' => 'listAiTwins',
            ],
            [
                'name' => 'AI Twin',
                'input_parameters' => [
                    'name' => ['type' => 'string', 'required' => true, 'description' => 'Name of the AI Twin.', 'example' => 'John AI'],
                    'videoUrl' => ['type' => 'string', 'required' => true, 'description' => 'Link to the calibration video.', 'example' => 'https://publiish.io/ipfs/QmVUmnhpHTqbwBgTnCSTuB6VfHZWyqYxAqgZQPwvFyDueh'],
                    'calibrationImageUrls' => ['type' => 'array', 'required' => true, 'description' => 'List of calibration image URLs.', 'items' => ['type' => 'string'], 'example' => ['https://publiish.io/ipfs/QmavjZjVCCXnKsU5mXfEssKmMEwrcQLAdud6LW2RgW5DgV', 'https://publiish.io/ipfs/QmamhvTk5YZPEbSVSGQTnkk4Ep1c33V4ByoxXAD4Zr1rst', 'https://publiish.io/ipfs/QmaZnwGpkKSMPR9i4jy5baHfs9LU2YKFu7HaBvViNxfXho', 'https://publiish.io/ipfs/QmcEkcsoQDT5Nh24WoMWUmJnr6PhBni2gEeApboVNHDo1R', 'https://publiish.io/ipfs/Qmcc5J7vXXCTDjtuWCcT4RFLWT67jEGh8NP4P8jhw8dSAD']],
                    'language' => ['type' => 'string', 'required' => false, 'description' => 'Language spoken in the video. Default is English.', 'example' => 'English'],
                ],
                'response' => ['operationId' => 'bEV7DFjDk4o2Cfl8chGp'],
                'response_path' => ['final_result' => '$.operationId'],
                'request_class_name' => AiTwinCreateRequest::class,
                'function_name' => 'createAiTwin',
            ],
            [
                'name' => 'AI Twin Status',
                'input_parameters' => [
                    'operationId' => ['type' => 'string', 'required' => true, 'description' => 'The unique operation ID of the AI Twin creation request.', 'example' => 'bEV7DFjDk4o2Cfl8chGp'],
                ],
                'response' => ['state' => 'COMPLETE', 'progress' => 87],
                'response_path' => ['final_result' => '$'],
                'request_class_name' => AiTwinStatusRequest::class,
                'function_name' => 'checkAiTwinStatus',
            ],
            [
                'name' => 'Fetch AI Twin calibration script',
                'input_parameters' => [
                    'language' => ['type' => 'string', 'required' => false, 'description' => 'Language of the script. Default is English.', 'example' => 'English'],
                ],
                'response' => ['script' => 'Hello everyone! Today, we embark on a journey of self-discovery...'],
                'response_path' => ['final_result' => '$.script'],
                'request_class_name' => AiTwinScriptRequest::class,
                'function_name' => 'getAiTwinScript',
            ],
            [
                'name' => 'Delete an AI Twin',
                'input_parameters' => [
                    'name' => ['type' => 'string', 'required' => true, 'description' => 'Name of the AI Twin to delete.', 'example' => 'JohnTwin'],
                ],
                'response' => ['success' => true],
                'response_path' => ['final_result' => '$.success'],
                'request_class_name' => AiTwinDeleteRequest::class,
                'function_name' => 'deleteAiTwin',
            ],
        ];

        $keptServiceTypeIds = $this->processServiceTypes($serviceProvider, $serviceTypes, 'Captions');

        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);

        $this->command->info('Cleanup completed:');
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info('- Kept ' . count($keptServiceTypeIds) . ' service types for Captions');
    }
}
