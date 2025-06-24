<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FFmpegServiceProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::connection('mongodb')->table('service_providers')->insert([
            'type' => 'FFmpeg',
            'parameter' => [
                'binary_path' => '/usr/bin/ffmpeg',
                'version' => 'latest',
                'supported_formats' => ['mp4', 'mp3', 'avi', 'mov', 'mkv'],
                'commands_supported' => ['convert', 'trim', 'resize', 'extract_audio']
            ]
        ]);

        $connection = DB::connection('mongodb');

        $ffmpegProviderId = $connection
            ->table('service_providers')
            ->where('type', 'FFmpeg')
            ->value('_id');

        $connection->table('service_types')->insert([
            [
                'name' => 'Video Processing',
                'service_provider_id' => $ffmpegProviderId,
                'parameter' => [
                    'command' => 'convert',
                    'input_file' => 'video.mp4',
                    'output_file' => 'output.avi',
                    'resolution' => '1920x1080',
                    'bitrate' => '2000k',
                    'frame_rate' => 30
                ]
            ],
            [
                'name' => 'Audio Processing',
                'service_provider_id' => $ffmpegProviderId,
                'parameter' => [
                    'command' => 'extract_audio',
                    'input_file' => 'video.mp4',
                    'output_file' => 'audio.mp3',
                    'bitrate' => '320k',
                    'channels' => 2,
                    'sample_rate' => 44100
                ]
            ],
            [
                'name' => 'Image Processing',
                'service_provider_id' => $ffmpegProviderId,
                'parameter' => [
                    'command' => 'resize',
                    'input_file' => 'image.png',
                    'output_file' => 'resized.jpg',
                    'width' => 1024,
                    'height' => 768
                ]
            ],
            [
                'name' => 'Video Trimming',
                'service_provider_id' => $ffmpegProviderId,
                'parameter' => [
                    'command' => 'trim',
                    'input_file' => 'video.mp4',
                    'output_file' => 'trimmed.mp4',
                    'start_time' => '00:00:30',
                    'end_time' => '00:01:30'
                ]
            ]
        ]);
    }
}
