<?php

namespace Database\Seeders;

use App\Http\Controllers\FFMpegServiceController;
use App\Http\Requests\FFMpeg\AudioProcessingRequest;
use App\Http\Requests\FFMpeg\FFProbeRequest;
use App\Http\Requests\FFMpeg\ImageProcessingRequest;
use App\Http\Requests\FFMpeg\LoudnessNormalizationRequest;
use App\Http\Requests\FFMpeg\TranscodingRequest;
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
                    'ffmpeg_path' => '/opt/homebrew/bin/ffmpeg',
                    'ffprobe_path' => '/opt/homebrew/bin/ffprobe',
                    'features' => [
                        'video_processing',
                        'audio_processing',
                        'image_processing',
                        'video_trimming',
                        'loudness_normalization',
                        'ffprobe',
                        'transcoding',
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
                        'userinput_rqd' => true,
                        'default' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_1mb.mp4',
                        'format' => 'url',
                        'description' => 'URL of the video file to process',
                    ],
                    'resolution' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => '1920x1080',
                        'description' => 'Output video resolution (e.g., 1920x1080, 1280x720)',
                    ],
                    'bitrate' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => '2000k',
                        'description' => 'Video bitrate (e.g., 2000k, 5000k)',
                    ],
                    'frame_rate' => [
                        'type' => 'integer',
                        'required' => true,
                        'userinput_rqd' => true,
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
                        'userinput_rqd' => true,
                        'default' => 'https://www.soundjay.com/misc/sounds/bell-ringing-05.wav',
                        'format' => 'url',
                        'description' => 'URL of the audio file to process',
                    ],
                    'bitrate' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => '192k',
                        'description' => 'Audio bitrate (e.g., 128k, 192k, 320k)',
                    ],
                    'channels' => [
                        'type' => 'integer',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => 2,
                        'min' => 1,
                        'max' => 8,
                        'description' => 'Number of audio channels (1=mono, 2=stereo)',
                    ],
                    'sample_rate' => [
                        'type' => 'integer',
                        'required' => true,
                        'userinput_rqd' => true,
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
                        'userinput_rqd' => true,
                        'default' => 'https://picsum.photos/2560/1440',
                        'format' => 'url',
                        'description' => 'URL of the image file to process',
                    ],
                    'width' => [
                        'type' => 'integer',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => 1920,
                        'min' => 1,
                        'max' => 8000,
                        'description' => 'Output image width in pixels',
                    ],
                    'height' => [
                        'type' => 'integer',
                        'required' => true,
                        'userinput_rqd' => true,
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
                        'userinput_rqd' => true,
                        'default' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_2mb.mp4',
                        'format' => 'url',
                        'description' => 'URL of the video file to trim',
                    ],
                    'start_time' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => '00:00:30',
                        'description' => 'Start time for trimming (format: HH:MM:SS)',
                    ],
                    'end_time' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
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
            [
                'name' => 'Loudness Normalization',
                'input_parameters' => [
                    'file_link' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
                        'format' => 'url',
                        'description' => 'URL of the audio/video file to normalize loudness',
                    ],
                    'target_lufs' => [
                        'type' => 'number',
                        'required' => false,
                        'userinput_rqd' => false,
                        'default' => -23.0,
                        'min' => -50,
                        'max' => 0,
                        'description' => 'Target integrated loudness in LUFS (default: -23.0 for broadcast)',
                    ],
                    'lra' => [
                        'type' => 'number',
                        'required' => false,
                        'userinput_rqd' => false,
                        'default' => 7.0,
                        'min' => 1,
                        'max' => 20,
                        'description' => 'Loudness range in LU (default: 7.0)',
                    ],
                    'tp' => [
                        'type' => 'number',
                        'required' => false,
                        'userinput_rqd' => false,
                        'default' => -2.0,
                        'min' => -6,
                        'max' => 0,
                        'description' => 'True peak in dBTP (default: -2.0)',
                    ],
                ],
                'response' => [
                    'message' => 'Loudness normalized successfully',
                    'output_file_link' => 'https://output.example.com/normalized_audio_123456.mp4',
                    'processing_time' => 25.3,
                    'input_lufs' => -18.5,
                    'output_lufs' => -23.0,
                    'loudness_range' => 7.0,
                    'true_peak' => -2.0,
                    'file_size' => '85MB',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => LoudnessNormalizationRequest::class,
                'function_name' => 'loudnessNormalization',
            ],
            [
                'name' => 'FFProbe Media Analysis',
                'input_parameters' => [
                    'file_link' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
                        'format' => 'url',
                        'description' => 'URL of the media file to analyze',
                    ],
                    'output_format' => [
                        'type' => 'string',
                        'required' => false,
                        'userinput_rqd' => false,
                        'default' => 'json',
                        'enum' => ['json', 'xml', 'csv', 'flat', 'ini', 'default'],
                        'description' => 'Output format for probe data (default: json)',
                    ],
                    'show_format' => [
                        'type' => 'boolean',
                        'required' => false,
                        'userinput_rqd' => false,
                        'default' => true,
                        'description' => 'Show format/container information',
                    ],
                    'show_streams' => [
                        'type' => 'boolean',
                        'required' => false,
                        'userinput_rqd' => false,
                        'default' => true,
                        'description' => 'Show streams information',
                    ],
                    'show_chapters' => [
                        'type' => 'boolean',
                        'required' => false,
                        'userinput_rqd' => false,
                        'default' => false,
                        'description' => 'Show chapters information',
                    ],
                    'show_programs' => [
                        'type' => 'boolean',
                        'required' => false,
                        'userinput_rqd' => false,
                        'default' => false,
                        'description' => 'Show programs information',
                    ],
                    'select_streams' => [
                        'type' => 'string',
                        'required' => false,
                        'userinput_rqd' => false,
                        'default' => '',
                        'description' => 'Select specific streams (e.g., "v:0" for first video stream, "a" for all audio)',
                    ],
                ],
                'response' => [
                    'message' => 'Media probed successfully',
                    'probe_data' => [
                        'format' => [
                            'filename' => 'example.mp4',
                            'nb_streams' => 2,
                            'nb_programs' => 0,
                            'format_name' => 'mov,mp4,m4a,3gp,3g2,mj2',
                            'format_long_name' => 'QuickTime / MOV',
                            'start_time' => '0.000000',
                            'duration' => '120.000000',
                            'size' => '15728640',
                            'bit_rate' => '1048576',
                        ],
                        'streams' => [
                            [
                                'index' => 0,
                                'codec_name' => 'h264',
                                'codec_type' => 'video',
                                'width' => 1920,
                                'height' => 1080,
                                'r_frame_rate' => '30/1',
                                'avg_frame_rate' => '30/1',
                                'duration' => '120.000000',
                            ],
                            [
                                'index' => 1,
                                'codec_name' => 'aac',
                                'codec_type' => 'audio',
                                'sample_rate' => '48000',
                                'channels' => 2,
                                'channel_layout' => 'stereo',
                                'duration' => '120.000000',
                            ],
                        ],
                    ],
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => FFProbeRequest::class,
                'function_name' => 'ffprobe',
            ],
            [
                'name' => 'Media Transcoding',
                'input_parameters' => [
                    'file_link' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
                        'format' => 'url',
                        'description' => 'URL of the media file to transcode',
                    ],
                    'output_format' => [
                        'type' => 'string',
                        'required' => false,
                        'userinput_rqd' => false,
                        'default' => 'mp4',
                        'enum' => ['mp4', 'avi', 'mov', 'mkv', 'webm', 'flv', 'wmv', 'm4v', '3gp', 'mp3', 'wav', 'flac', 'aac', 'ogg', 'm4a', 'wma'],
                        'description' => 'Output container format (defaults to mp4 with H.264/AAC)',
                    ],
                ],
                'response' => [
                    'message' => 'Media transcoded successfully',
                    'output_file_link' => 'https://output.example.com/transcoded_media_123456.mp4',
                    'processing_time' => 120.5,
                    'input_format' => 'mov,mp4,m4a,3gp,3g2,mj2',
                    'output_format' => 'mp4',
                    'input_duration' => '180.000000',
                    'output_duration' => '180.000000',
                    'video_codec' => 'libx264',
                    'audio_codec' => 'aac',
                    'file_size' => '125MB',
                    'compression_ratio' => '35.2%',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => TranscodingRequest::class,
                'function_name' => 'transcoding',
            ],
        ];

        $keptServiceTypeIds = $this->processServiceTypes($serviceProvider, $serviceTypes, 'FFmpeg');

        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);

        $this->command->info('Cleanup completed:');
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info('- Kept ' . count($keptServiceTypeIds) . ' service types for FFmpeg');
    }
}
