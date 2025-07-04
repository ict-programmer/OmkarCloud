<?php

namespace Database\Seeders;

use App\Http\Controllers\FFMpegServiceController;
use App\Http\Requests\FFMpeg\AudioProcessingRequest;
use App\Http\Requests\FFMpeg\ImageProcessingRequest;
use App\Http\Requests\FFMpeg\VideoProcessingRequest;
use App\Http\Requests\FFMpeg\VideoTrimmingRequest;
use App\Models\ServiceProvider;
use App\Traits\ServiceProviderSeederTrait;
use Illuminate\Database\Seeder;

class FFmpegServiceProviderSeeder extends Seeder
{
    use ServiceProviderSeederTrait;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => 'FFmpeg'],
            [
                'parameters' => [
                    'ffmpeg_path' => '/usr/bin/ffmpeg',
                    'features' => [
                        'video_processing',
                        'audio_processing',
                        'image_processing',
                        'video_trimming',
                    ],
                ],
                'is_active' => true,
                'controller_name' => FFMpegServiceController::class,
            ]
        );

        $serviceTypes = [
            [
                'name' => 'Video Processing',
                'input_parameters' => [
                    'file_link' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_1mb.mp4',
                        'format' => 'url',
                        'description' => 'URL of the video file to process',
                    ],
                    'resolution' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => '1920x1080',
                        'description' => 'Output video resolution (e.g., 1920x1080, 1280x720)',
                    ],
                    'bitrate' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => '2000k',
                        'description' => 'Video bitrate (e.g., 2000k, 5000k)',
                    ],
                    'frame_rate' => [
                        'type' => 'integer',
                        'required' => true,
                        'default' => 30,
                        'min' => 1,
                        'max' => 120,
                        'description' => 'Output frame rate (frames per second)',
                    ],
                ],
                'response' => [
                    'message' => 'Video processed successfully',
                    'output_file_link' => 'https://output.example.com/processed_video_123456.mp4',
                    'processing_time' => 45.2,
                    'input_size' => '120MB',
                    'output_size' => '95MB',
                    'compression_ratio' => '20.8%',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => VideoProcessingRequest::class,
                'function_name' => 'videoProcessing',
            ],
            [
                'name' => 'Audio Processing',
                'input_parameters' => [
                    'file_link' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'https://www.soundjay.com/misc/sounds/bell-ringing-05.wav',
                        'format' => 'url',
                        'description' => 'URL of the audio file to process',
                    ],
                    'bitrate' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => '192k',
                        'description' => 'Audio bitrate (e.g., 128k, 192k, 320k)',
                    ],
                    'channels' => [
                        'type' => 'integer',
                        'required' => true,
                        'default' => 2,
                        'min' => 1,
                        'max' => 8,
                        'description' => 'Number of audio channels (1=mono, 2=stereo)',
                    ],
                    'sample_rate' => [
                        'type' => 'integer',
                        'required' => true,
                        'default' => 44100,
                        'description' => 'Audio sample rate in Hz (e.g., 44100, 48000)',
                    ],
                ],
                'response' => [
                    'message' => 'Audio processed successfully',
                    'output_file_link' => 'https://output.example.com/processed_audio_123456.wav',
                    'processing_time' => 12.5,
                    'input_size' => '25MB',
                    'output_size' => '18MB',
                    'compression_ratio' => '28%',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => AudioProcessingRequest::class,
                'function_name' => 'audioProcessing',
            ],
            [
                'name' => 'Image Processing',
                'input_parameters' => [
                    'file_link' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'https://picsum.photos/2560/1440',
                        'format' => 'url',
                        'description' => 'URL of the image file to process',
                    ],
                    'width' => [
                        'type' => 'integer',
                        'required' => true,
                        'default' => 1920,
                        'min' => 1,
                        'max' => 8000,
                        'description' => 'Output image width in pixels',
                    ],
                    'height' => [
                        'type' => 'integer',
                        'required' => true,
                        'default' => 1080,
                        'min' => 1,
                        'max' => 8000,
                        'description' => 'Output image height in pixels',
                    ],
                ],
                'response' => [
                    'message' => 'Image processed successfully',
                    'output_file_link' => 'https://output.example.com/processed_image_123456.jpg',
                    'processing_time' => 2.1,
                    'input_size' => '5MB',
                    'output_size' => '3.2MB',
                    'original_dimensions' => '2560x1440',
                    'new_dimensions' => '1920x1080',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => ImageProcessingRequest::class,
                'function_name' => 'imageProcessing',
            ],
            [
                'name' => 'Video Trimming',
                'input_parameters' => [
                    'file_link' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_2mb.mp4',
                        'format' => 'url',
                        'description' => 'URL of the video file to trim',
                    ],
                    'start_time' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => '00:00:30',
                        'description' => 'Start time for trimming (format: HH:MM:SS)',
                    ],
                    'end_time' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => '00:01:30',
                        'description' => 'End time for trimming (format: HH:MM:SS)',
                    ],
                ],
                'response' => [
                    'message' => 'Video trimmed successfully',
                    'output_file_link' => 'https://output.example.com/trimmed_video_123456.mp4',
                    'processing_time' => 8.7,
                    'input_duration' => '00:05:30',
                    'output_duration' => '00:01:00',
                    'trim_start' => '00:00:30',
                    'trim_end' => '00:01:30',
                    'file_size' => '45MB',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => VideoTrimmingRequest::class,
                'function_name' => 'videoTrimming',
            ],
        ];

        $keptServiceTypeIds = $this->processServiceTypes($serviceProvider, $serviceTypes, 'FFmpeg');

        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);

        $this->command->info('Cleanup completed:');
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info('- Kept ' . count($keptServiceTypeIds) . ' service types for FFmpeg');
    }
}
