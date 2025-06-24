<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WhisperAIServiceProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::connection('mongodb')
            ->table('service_providers')
            ->insert([
                'type' => 'Whisper AI',
                'parameter' => [
                    'api_url' => 'https://api.whisper.ai',
                    'api_key' => 'fqhhdcxhsxjomtkjeigglyydgyjpaepp'
                ]
            ]);

        DB::connection('mongodb')
            ->table('service_types')
            ->insert([
                ['name' => 'Audio Transcription'],
                ['name' => 'Language Detection'],
                ['name' => 'Transcription with Timestamps'],
                ['name' => 'Translation']
            ]);

        $connection = DB::connection('mongodb');

        $providerId = $connection->table('service_providers')
            ->where('type', 'Whisper AI')
            ->value('_id');

        $serviceTypes = $connection->table('service_types')
            ->whereIn('name', [
                'Audio Transcription',
                'Language Detection',
                'Transcription with Timestamps',
                'Translation'
            ])
            ->pluck('_id', 'name');

        $connection->table('service_providers_types')->insert([
            [
                'service_type_id' => $serviceTypes['Audio Transcription'],
                'service_provider_id' => $providerId,
                'parameter' => [
                    'file' => 'audio_file_path_or_base64_string',
                    'language' => 'en',
                    'model' => 'whisper_large',
                    'timestamps' => false
                ]
            ],
            [
                'service_type_id' => $serviceTypes['Language Detection'],
                'service_provider_id' => $providerId,
                'parameter' => [
                    'file' => 'audio_file_path_or_base64_string'
                ]
            ],
            [
                'service_type_id' => $serviceTypes['Transcription with Timestamps'],
                'service_provider_id' => $providerId,
                'parameter' => [
                    'file' => 'audio_file_path_or_base64_string',
                    'language' => 'en',
                    'model' => 'whisper_large',
                    'timestamps' => true
                ]
            ],
            [
                'service_type_id' => $serviceTypes['Translation'],
                'service_provider_id' => $providerId,
                'parameter' => [
                    'file' => 'audio_file_path_or_base64_string',
                    'source_language' => 'en',
                    'target_language' => 'es'
                ]
            ]
        ]);
    }
}
