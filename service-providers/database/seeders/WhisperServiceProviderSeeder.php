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
                    'endpoints' => [
                        'transcriptions' => '/v1/audio/transcriptions',
                        'translations' => '/v1/audio/translations',
                    ],
                    'models_supported' => [
                        'whisper-1',
                    ],
                    'supported_formats' => [
                        'mp3',
                        'mp4',
                        'mpeg',
                        'mpga',
                        'm4a',
                        'wav',
                        'webm',
                    ],
                    'max_file_size' => '25MB',
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
                    'file' => [
                        'type' => 'file',
                        'required' => false,
                        'description' => 'Audio file to transcribe (max 25MB)',
                        'formats' => ['mp3', 'mp4', 'mpeg', 'mpga', 'm4a', 'wav', 'webm'],
                        'max_size' => '25MB',
                    ],
                    'link' => [
                        'type' => 'string',
                        'required' => false,
                        'format' => 'url',
                        'description' => 'URL to audio file (alternative to file upload)',
                        'example' => 'https://example.com/audio.mp3',
                    ],
                    'language' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'Language of the audio content (ISO 639-1 format)',
                        'example' => 'en',
                        'options' => [
                            'en', 'es', 'fr', 'de', 'it', 'pt', 'ru', 'ja', 'ko', 'zh',
                            'ar', 'hi', 'nl', 'sv', 'da', 'no', 'fi', 'pl', 'tr', 'he',
                            'th', 'vi', 'id', 'ms', 'uk', 'cs', 'sk', 'hu', 'ro', 'bg',
                            'hr', 'sr', 'sl', 'et', 'lv', 'lt', 'mt', 'ga', 'cy', 'is',
                        ],
                    ],
                    'prompt' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 1000,
                        'description' => 'Optional text to guide the model\'s style or continue a previous audio segment',
                        'example' => 'This is a business meeting discussing quarterly results.',
                    ],
                    'model' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'whisper-1',
                        'options' => ['whisper-1'],
                        'description' => 'Model to use for transcription',
                    ],
                    'response_format' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'json',
                        'options' => ['json', 'text', 'srt', 'verbose_json', 'vtt'],
                        'description' => 'Format of the transcript output',
                    ],
                    'temperature' => [
                        'type' => 'float',
                        'required' => false,
                        'default' => 0,
                        'min' => 0,
                        'max' => 1,
                        'description' => 'Sampling temperature between 0 and 1',
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
                    'file' => [
                        'type' => 'file',
                        'required' => false,
                        'description' => 'Audio file to transcribe with timestamps (max 25MB)',
                        'formats' => ['mp3', 'mp4', 'mpeg', 'mpga', 'm4a', 'wav', 'webm'],
                        'max_size' => '25MB',
                    ],
                    'link' => [
                        'type' => 'string',
                        'required' => false,
                        'format' => 'url',
                        'description' => 'URL to audio file (alternative to file upload)',
                        'example' => 'https://example.com/audio.mp3',
                    ],
                    'language' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'Language of the audio content (ISO 639-1 format)',
                        'example' => 'en',
                        'options' => [
                            'en', 'es', 'fr', 'de', 'it', 'pt', 'ru', 'ja', 'ko', 'zh',
                            'ar', 'hi', 'nl', 'sv', 'da', 'no', 'fi', 'pl', 'tr', 'he',
                            'th', 'vi', 'id', 'ms', 'uk', 'cs', 'sk', 'hu', 'ro', 'bg',
                        ],
                    ],
                    'prompt' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 1000,
                        'description' => 'Optional text to guide the model\'s style or continue a previous audio segment',
                        'example' => 'This is a podcast interview about technology trends.',
                    ],
                    'timestamp_granularities' => [
                        'type' => 'array',
                        'required' => false,
                        'default' => ['segment'],
                        'options' => ['word', 'segment'],
                        'description' => 'Timestamp granularities to populate',
                        'example' => ['word', 'segment'],
                    ],
                    'response_format' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'verbose_json',
                        'options' => ['verbose_json', 'srt', 'vtt'],
                        'description' => 'Format of the transcript output with timestamps',
                    ],
                ],
                'response' => [
                    'task' => 'transcribe',
                    'language' => 'en',
                    'duration' => 45.2,
                    'text' => 'This is a sample transcription with detailed timestamps. Each word and segment has precise timing information.',
                    'segments' => [
                        [
                            'id' => 0,
                            'seek' => 0,
                            'start' => 0.0,
                            'end' => 8.5,
                            'text' => 'This is a sample transcription with detailed timestamps.',
                            'tokens' => [50364, 50365, 50366, 50367, 50368, 50369, 50370, 50371],
                            'temperature' => 0.0,
                            'avg_logprob' => -0.18,
                            'compression_ratio' => 1.25,
                            'no_speech_prob' => 0.08,
                        ],
                        [
                            'id' => 1,
                            'seek' => 850,
                            'start' => 8.5,
                            'end' => 15.2,
                            'text' => 'Each word and segment has precise timing information.',
                            'tokens' => [50372, 50373, 50374, 50375, 50376],
                            'temperature' => 0.0,
                            'avg_logprob' => -0.22,
                            'compression_ratio' => 1.18,
                            'no_speech_prob' => 0.12,
                        ],
                    ],
                    'words' => [
                        [
                            'word' => 'This',
                            'start' => 0.0,
                            'end' => 0.4,
                        ],
                        [
                            'word' => 'is',
                            'start' => 0.4,
                            'end' => 0.6,
                        ],
                        [
                            'word' => 'a',
                            'start' => 0.6,
                            'end' => 0.8,
                        ],
                        [
                            'word' => 'sample',
                            'start' => 0.8,
                            'end' => 1.3,
                        ],
                        [
                            'word' => 'transcription',
                            'start' => 1.3,
                            'end' => 2.1,
                        ],
                    ],
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
                    'file' => [
                        'type' => 'file',
                        'required' => false,
                        'description' => 'Audio file to translate to English (max 25MB)',
                        'formats' => ['mp3', 'mp4', 'mpeg', 'mpga', 'm4a', 'wav', 'webm'],
                        'max_size' => '25MB',
                    ],
                    'link' => [
                        'type' => 'string',
                        'required' => false,
                        'format' => 'url',
                        'description' => 'URL to audio file (alternative to file upload)',
                        'example' => 'https://example.com/spanish-audio.mp3',
                    ],
                    'language' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'Source language of the audio content (will be translated to English)',
                        'example' => 'es',
                        'options' => [
                            'es', 'fr', 'de', 'it', 'pt', 'ru', 'ja', 'ko', 'zh',
                            'ar', 'hi', 'nl', 'sv', 'da', 'no', 'fi', 'pl', 'tr', 'he',
                            'th', 'vi', 'id', 'ms', 'uk', 'cs', 'sk', 'hu', 'ro', 'bg',
                        ],
                    ],
                    'prompt' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 1000,
                        'description' => 'Optional text to guide the translation style',
                        'example' => 'This is a formal business presentation that should be translated professionally.',
                    ],
                    'model' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'whisper-1',
                        'options' => ['whisper-1'],
                        'description' => 'Model to use for translation',
                    ],
                    'response_format' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'json',
                        'options' => ['json', 'text', 'srt', 'verbose_json', 'vtt'],
                        'description' => 'Format of the translation output',
                    ],
                    'temperature' => [
                        'type' => 'float',
                        'required' => false,
                        'default' => 0,
                        'min' => 0,
                        'max' => 1,
                        'description' => 'Sampling temperature between 0 and 1',
                    ],
                ],
                'response' => [
                    'text' => 'This is the English translation of the original audio content. The translation maintains the meaning and context while converting to fluent English.',
                    'language' => 'en',
                    'duration' => 42.8,
                    'segments' => [
                        [
                            'id' => 0,
                            'seek' => 0,
                            'start' => 0.0,
                            'end' => 6.2,
                            'text' => 'This is the English translation of the original audio content.',
                            'tokens' => [50364, 50365, 50366, 50367, 50368, 50369],
                            'temperature' => 0.0,
                            'avg_logprob' => -0.25,
                            'compression_ratio' => 1.15,
                            'no_speech_prob' => 0.07,
                        ],
                        [
                            'id' => 1,
                            'seek' => 620,
                            'start' => 6.2,
                            'end' => 13.5,
                            'text' => 'The translation maintains the meaning and context while converting to fluent English.',
                            'tokens' => [50370, 50371, 50372, 50373, 50374],
                            'temperature' => 0.0,
                            'avg_logprob' => -0.19,
                            'compression_ratio' => 1.22,
                            'no_speech_prob' => 0.09,
                        ],
                    ],
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