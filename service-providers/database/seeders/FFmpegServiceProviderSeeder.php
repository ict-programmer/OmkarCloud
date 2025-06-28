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
                    'supported_video_formats' => [
                        'mp4',
                        'mov',
                        'avi',
                        'wmv',
                        'flv',
                        'mkv',
                        'webm',
                    ],
                    'supported_audio_formats' => [
                        'mp3',
                        'wav',
                        'ogg',
                        'm4a',
                        'aac',
                        'flac',
                    ],
                    'supported_image_formats' => [
                        'jpeg',
                        'png',
                        'jpg',
                        'gif',
                        'svg',
                        'bmp',
                        'tiff',
                    ],
                    'video_codecs' => [
                        'h264',
                        'h265',
                        'vp8',
                        'vp9',
                        'av1',
                    ],
                    'audio_codecs' => [
                        'aac',
                        'mp3',
                        'vorbis',
                        'opus',
                        'flac',
                    ],
                    'features' => [
                        'video_processing',
                        'audio_processing',
                        'image_processing',
                        'video_trimming',
                        'format_conversion',
                        'quality_adjustment',
                        'resolution_scaling',
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
                    'input_file' => [
                        'type' => 'file',
                        'required' => true,
                        'mime_types' => ['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/x-ms-wmv', 'video/x-flv'],
                        'extensions' => ['mp4', 'mov', 'avi', 'wmv', 'flv'],
                        'description' => 'Video file to process',
                        'example' => 'input_video.mp4',
                    ],
                    'resolution' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'Target resolution for the video (e.g., 1920x1080, 1280x720)',
                        'example' => '1920x1080',
                        'pattern' => '^\d+x\d+$',
                    ],
                    'bitrate' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'Video bitrate (e.g., 1000k, 2M)',
                        'example' => '2M',
                        'pattern' => '^\d+[kKmM]?$',
                    ],
                    'frame_rate' => [
                        'type' => 'integer',
                        'required' => true,
                        'min' => 1,
                        'max' => 120,
                        'description' => 'Frames per second for the output video',
                        'example' => 30,
                    ],
                ],
                'response' => [
                    'message' => 'Video processed successfully',
                    'output_file_link' => 'https://storage.example.com/processed_video_1234567890_abc123.mp4',
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
                    'input_file' => [
                        'type' => 'file',
                        'required' => true,
                        'mime_types' => ['audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/mp4', 'audio/aac'],
                        'extensions' => ['mp3', 'wav', 'ogg', 'm4a', 'aac'],
                        'description' => 'Audio file to process',
                        'example' => 'input_audio.mp3',
                    ],
                    'bitrate' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'Audio bitrate (e.g., 128k, 320k)',
                        'example' => '128k',
                        'pattern' => '^\d+[kK]?$',
                    ],
                    'sample_rate' => [
                        'type' => 'integer',
                        'required' => true,
                        'min' => 1,
                        'description' => 'Audio sample rate in Hz (e.g., 44100, 48000)',
                        'example' => 44100,
                    ],
                    'channels' => [
                        'type' => 'integer',
                        'required' => true,
                        'min' => 1,
                        'max' => 2,
                        'description' => 'Number of audio channels (1 for mono, 2 for stereo)',
                        'example' => 2,
                    ],
                ],
                'response' => [
                    'message' => 'Audio processed successfully',
                    'output_file_link' => 'https://storage.example.com/processed_audio_1234567890_abc123.mp3',
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
                    'input_file' => [
                        'type' => 'file',
                        'required' => true,
                        'mime_types' => ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml'],
                        'extensions' => ['jpeg', 'png', 'jpg', 'gif', 'svg'],
                        'description' => 'Image file to process',
                        'example' => 'input_image.jpg',
                    ],
                    'width' => [
                        'type' => 'integer',
                        'required' => true,
                        'min' => 1,
                        'description' => 'Target width for the image in pixels',
                        'example' => 1920,
                    ],
                    'height' => [
                        'type' => 'integer',
                        'required' => true,
                        'min' => 1,
                        'description' => 'Target height for the image in pixels',
                        'example' => 1080,
                    ],
                ],
                'response' => [
                    'message' => 'Image processed successfully',
                    'output_file_link' => 'https://storage.example.com/processed_image_1234567890_abc123.jpg',
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
                    'input_file' => [
                        'type' => 'file',
                        'required' => true,
                        'mime_types' => ['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/x-ms-wmv', 'video/x-flv'],
                        'extensions' => ['mp4', 'mov', 'avi', 'wmv', 'flv'],
                        'description' => 'Video file to trim',
                        'example' => 'input_video.mp4',
                    ],
                    'start_time' => [
                        'type' => 'string',
                        'required' => true,
                        'format' => 'time',
                        'pattern' => '^([0-1]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$',
                        'description' => 'Start time for trimming in HH:MM:SS format',
                        'example' => '00:01:30',
                    ],
                    'end_time' => [
                        'type' => 'string',
                        'required' => true,
                        'format' => 'time',
                        'pattern' => '^([0-1]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$',
                        'description' => 'End time for trimming in HH:MM:SS format (must be after start_time)',
                        'example' => '00:05:45',
                    ],
                ],
                'response' => [
                    'message' => 'Video trimmed successfully',
                    'output_file_link' => 'https://storage.example.com/trimmed_video_1234567890_abc123.mp4',
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
