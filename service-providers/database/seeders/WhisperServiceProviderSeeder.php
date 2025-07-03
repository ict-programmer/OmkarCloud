<?php

namespace Database\Seeders;

use App\Http\Controllers\WhisperController;
use App\Http\Requests\Whisper\AudioTranscribeRequest;
use App\Models\ServiceProvider;
use App\Traits\ServiceProviderSeederTrait;
use Illuminate\Database\Seeder;

class WhisperServiceProviderSeeder extends Seeder
{
    use ServiceProviderSeederTrait;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => 'Whisper'],
            [
                'parameters' => [
                    'api_key' => 'YOUR_OPENAI_API_KEY',
                    'base_url' => 'https://api.openai.com',
                    'version' => 'v1',
                    'features' => [
                        'audio_transcription',
                        'audio_translation',
                        'timestamp_support',
                        'language_detection',
                        'multiple_formats',
                        'prompt_guidance',
                    ],
                ],
                'is_active' => true,
                'controller_name' => WhisperController::class,
            ]
        );

        $serviceTypes = [
            [
                'name' => 'Audio Transcribe',
                'input_parameters' => [
                    'link' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'https://output.lemonfox.ai/wikipedia_ai.mp3',
                        'format' => 'url',
                        'description' => 'URL to audio file (alternative to file upload)',
                    ],
                    'language' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'en',
                        'description' => 'Language of the audio content (ISO 639-1 format)',
                    ],
                    'prompt' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'This is a business meeting discussing quarterly results.',
                        'description' => 'Optional text to guide the model\'s style or continue a previous audio segment',
                    ],
                ],
                'response' => [
                    'text' => 'This is a sample transcription of the audio file. The content has been accurately converted from speech to text using advanced AI models.',
                    'language' => 'en',
                    'duration' => 45.2,
                    'segments' => [
                        [
                            'id' => 0,
                            'seek' => 0,
                            'start' => 0.0,
                            'end' => 5.5,
                            'text' => 'This is a sample transcription',
                            'tokens' => [50364, 50365, 50366, 50367],
                            'temperature' => 0.0,
                            'avg_logprob' => -0.2,
                            'compression_ratio' => 1.2,
                            'no_speech_prob' => 0.1,
                        ],
                        [
                            'id' => 1,
                            'seek' => 550,
                            'start' => 5.5,
                            'end' => 12.0,
                            'text' => 'of the audio file.',
                            'tokens' => [50368, 50369, 50370],
                            'temperature' => 0.0,
                            'avg_logprob' => -0.15,
                            'compression_ratio' => 1.1,
                            'no_speech_prob' => 0.05,
                        ],
                    ],
                    'words' => [
                        [
                            'word' => 'This',
                            'start' => 0.0,
                            'end' => 0.5,
                        ],
                        [
                            'word' => 'is',
                            'start' => 0.5,
                            'end' => 0.8,
                        ],
                        [
                            'word' => 'a',
                            'start' => 0.8,
                            'end' => 1.0,
                        ],
                    ],
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => AudioTranscribeRequest::class,
                'function_name' => 'audioTranscribe',
            ],
            [
                'name' => 'Audio Transcribe with Timestamps',
                'input_parameters' => [
                    'link' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'https://output.lemonfox.ai/wikipedia_ai.mp3',
                        'format' => 'url',
                        'description' => 'URL to audio file (alternative to file upload)',
                    ],
                    'language' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'en',
                        'description' => 'Language of the audio content (ISO 639-1 format)',
                    ],
                    'prompt' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'This is a podcast interview about technology trends.',
                        'min_length' => 1,
                        'max_length' => 1000,
                        'description' => 'Optional text to guide the model\'s style or continue a previous audio segment',
                    ],
                ],
                'response' => [
                    'task' => 'transcribe',
                    'language' => 'en',
                    'duration' => 45.2,
                    'text' => 'This is a sample transcription with detailed timestamps. Each word and segment has precise timing information.',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => AudioTranscribeRequest::class,
                'function_name' => 'audioTranscribeTimestamps',
            ],
            [
                'name' => 'Audio Translate',
                'input_parameters' => [
                    'link' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'https://output.lemonfox.ai/wikipedia_ai.mp3',
                        'format' => 'url',
                        'description' => 'URL to audio file (alternative to file upload)',
                    ],
                    'language' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'es',
                        'description' => 'Source language of the audio content (will be translated to English)',
                    ],
                    'prompt' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'This is a formal business presentation that should be translated professionally.',
                        'min_length' => 1,
                        'max_length' => 1000,
                        'description' => 'Optional text to guide the translation style',
                    ],
                ],
                'response' => [
                    'text' => 'This is the English translation of the original audio content. The translation maintains the meaning and context while converting to fluent English.',
                    'language' => 'en',
                    'duration' => 42.8,
                    'task' => 'translate',
                    'source_language' => 'es',
                    'target_language' => 'en',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => AudioTranscribeRequest::class,
                'function_name' => 'audioTranslate',
            ],
        ];

        $keptServiceTypeIds = $this->processServiceTypes($serviceProvider, $serviceTypes, 'Whisper');

        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);

        $this->command->info('Cleanup completed:');
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info('- Kept ' . count($keptServiceTypeIds) . ' service types for Whisper');
    }
} 