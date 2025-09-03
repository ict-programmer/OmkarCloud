<?php

namespace Database\Seeders;

use App\Http\Controllers\FFMpegServiceController;
use App\Http\Requests\FFMpeg\AudioFadesRequest;
use App\Http\Requests\FFMpeg\AudioOverlayRequest;
use App\Http\Requests\FFMpeg\AudioProcessingRequest;
use App\Http\Requests\FFMpeg\AudioVolumeRequest;
use App\Http\Requests\FFMpeg\BatchProcessRequest;
use App\Http\Requests\FFMpeg\BitrateControlRequest;
use App\Http\Requests\FFMpeg\ConcatenateRequest;
use App\Http\Requests\FFMpeg\FileInspectionRequest;
use App\Http\Requests\FFMpeg\FrameExtractionRequest;
use App\Http\Requests\FFMpeg\ImageProcessingRequest;
use App\Http\Requests\FFMpeg\LoudnessNormalizationRequest;
use App\Http\Requests\FFMpeg\ScaleRequest;
use App\Http\Requests\FFMpeg\StreamCopyRequest;
use App\Http\Requests\FFMpeg\ThumbnailRequest;
use App\Http\Requests\FFMpeg\TranscodingRequest;
use App\Http\Requests\FFMpeg\VideoEncodeRequest;
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
                    'features' => [
                        'video_processing',
                        'audio_processing',
                        'image_processing',
                        'video_trimming',
                        'loudness_normalization',
                        'transcoding',
                        'audio_overlay',
                        'frame_extraction',
                        'audio_volume',
                        'audio_fades',
                        'scale',
                        'concatenate',
                        'file_inspection',
                        'thumbnail',
                        'bitrate_control',
                        'stream_copy',
                        'video_encode',
                        'batch_process',
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
            [
                'name' => 'Audio Overlay',
                'input_parameters' => [
                    'background_track' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => 'https://commondatastorage.googleapis.com/codeskulptor-assets/Evillaugh.ogg',
                        'format' => 'url',
                        'description' => 'URL of the background audio track (base layer)',
                    ],
                    'overlay_track' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => 'https://commondatastorage.googleapis.com/codeskulptor-assets/week7-brrring.m4a',
                        'format' => 'url',
                        'description' => 'URL of the overlay audio track (to be mixed on top)',
                    ],
                    'output_format' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => 'mp3',
                        'enum' => ['mp3', 'wav', 'flac', 'aac', 'ogg', 'm4a', 'wma'],
                        'description' => 'Required output audio format for the mixed result',
                    ],
                ],
                'response' => [
                    'message' => 'Audio overlay completed successfully',
                    'output_file_link' => 'https://output.example.com/audio_overlay_123456.mp3',
                    'processing_time' => 15.8,
                    'main_audio_duration' => '120.000000',
                    'overlay_audio_duration' => '45.000000',
                    'output_duration' => '120.000000',
                    'mix_method' => 'amix',
                    'inputs_mixed' => 2,
                    'file_size' => '4.2MB',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => AudioOverlayRequest::class,
                'function_name' => 'audioOverlay',
            ],
            [
                'name' => 'Frame Extraction',
                'input_parameters' => [
                    'input_file' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
                        'format' => 'url',
                        'description' => 'URL of the video file to extract frames from',
                    ],
                    'frame_rate' => [
                        'type' => 'number',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => 1.0,
                        'min' => 0.1,
                        'max' => 30,
                        'description' => 'Frame extraction rate (frames per second). Lower values = fewer frames extracted',
                    ],
                ],
                'response' => [
                    'message' => 'Frame extraction completed successfully',
                    'total_frames' => 12,
                    'frame_urls' => [
                        'https://output.example.com/frame_0001.jpg',
                        'https://output.example.com/frame_0002.jpg',
                        'https://output.example.com/frame_0003.jpg',
                        'https://output.example.com/frame_0004.jpg',
                        'https://output.example.com/frame_0005.jpg',
                        'https://output.example.com/frame_0006.jpg',
                        '...'
                    ],
                    'processing_time' => 8.5,
                    'video_duration' => '12.000000',
                    'extracted_frame_rate' => 1.0,
                    'image_format' => 'JPEG',
                    'image_quality' => 'high',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => FrameExtractionRequest::class,
                'function_name' => 'frameExtraction',
            ],
            [
                'name' => 'Audio Volume Adjustment',
                'input_parameters' => [
                    'input' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => 'https://commondatastorage.googleapis.com/codeskulptor-assets/Evillaugh.ogg',
                        'format' => 'url',
                        'description' => 'URL of the audio/video file to adjust volume',
                    ],
                    'volume_factor' => [
                        'type' => 'number',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => 1.5,
                        'min' => 0,
                        'max' => 10,
                        'description' => 'Volume multiplication factor (1.0 = original, 2.0 = double, 0.5 = half)',
                    ],
                ],
                'response' => [
                    'message' => 'Audio volume adjusted successfully',
                    'output_file_link' => 'https://output.example.com/volume_adjusted_123456.ogg',
                    'processing_time' => 3.2,
                    'original_volume' => '1.0',
                    'new_volume_factor' => '1.5',
                    'volume_change' => '+50%',
                    'output_format' => 'ogg',
                    'file_size' => '2.8MB',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => AudioVolumeRequest::class,
                'function_name' => 'audioVolume',
            ],
            [
                'name' => 'Audio Fades / Ducking',
                'input_parameters' => [
                    'input' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => 'https://commondatastorage.googleapis.com/codeskulptor-assets/Evillaugh.ogg',
                        'format' => 'url',
                        'description' => 'URL of the audio/video file to apply fades',
                    ],
                    'fade_in_duration' => [
                        'type' => 'number',
                        'required' => false,
                        'userinput_rqd' => false,
                        'default' => 2.0,
                        'min' => 0,
                        'max' => 60,
                        'description' => 'Fade in duration in seconds (null to skip fade in)',
                    ],
                    'fade_out_duration' => [
                        'type' => 'number',
                        'required' => false,
                        'userinput_rqd' => false,
                        'default' => 3.0,
                        'min' => 0,
                        'max' => 60,
                        'description' => 'Fade out duration in seconds (null to skip fade out)',
                    ],
                ],
                'response' => [
                    'message' => 'Audio fades applied successfully',
                    'output_file_link' => 'https://output.example.com/faded_audio_123456.ogg',
                    'processing_time' => 4.1,
                    'fade_in_applied' => true,
                    'fade_in_duration' => '2.0s',
                    'fade_out_applied' => true,
                    'fade_out_duration' => '3.0s',
                    'total_fade_duration' => '5.0s',
                    'output_format' => 'ogg',
                    'file_size' => '3.1MB',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => AudioFadesRequest::class,
                'function_name' => 'audioFades',
            ],
            [
                'name' => 'Video Scaling / Resizing',
                'input_parameters' => [
                    'input' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
                        'format' => 'url',
                        'description' => 'URL of the video file to scale/resize',
                    ],
                    'resolution_target' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => '1280x720',
                        'description' => 'Target resolution (e.g., "1920x1080", "1280x720") or preset ("720p", "1080p", "1440p", "2160p", "4K", "8K")',
                        'examples' => [
                            '1920x1080',
                            '1280x720',
                            '720p',
                            '1080p',
                            '1440p',
                            '2160p',
                            '4K',
                            '8K'
                        ],
                    ],
                ],
                'response' => [
                    'message' => 'Video scaled successfully',
                    'output_file_link' => 'https://output.example.com/scaled_video_123456.mp4',
                    'processing_time' => 45.7,
                    'original_resolution' => '1920x1080',
                    'target_resolution' => '1280x720',
                    'scaling_ratio' => '66.7%',
                    'output_format' => 'mp4',
                    'video_codec' => 'libx264',
                    'audio_codec' => 'aac',
                    'file_size' => '78MB',
                    'compression_achieved' => '35%',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => ScaleRequest::class,
                'function_name' => 'scale',
            ],
            [
                'name' => 'Video Concatenation',
                'input_parameters' => [
                    'input_files' => [
                        'type' => 'array',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => [
                            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
                            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4'
                        ],
                        'min_items' => 2,
                        'max_items' => 20,
                        'items' => [
                            'type' => 'string',
                            'format' => 'url',
                        ],
                        'description' => 'Array of video file URLs to concatenate (minimum 2, maximum 20 files)',
                        'validation' => 'Each URL must be a valid video file. All files should have similar properties (resolution, codec) for best results.',
                    ],
                ],
                'response' => [
                    'message' => 'Videos concatenated successfully',
                    'output_file_link' => 'https://output.example.com/concatenated_video_123456.mp4',
                    'total_input_files' => 2,
                    'processing_time' => 8.5,
                    'total_duration' => '00:00:30',
                    'individual_durations' => [
                        'file_1' => '00:00:15',
                        'file_2' => '00:00:15'
                    ],
                    'output_format' => 'mp4',
                    'video_codec' => 'libx264',
                    'audio_codec' => 'aac',
                    'file_size' => '12MB',
                    'concatenation_method' => 'concat_demuxer',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => ConcatenateRequest::class,
                'function_name' => 'concatenate',
            ],
            [
                'name' => 'File Inspection / Metadata Analysis',
                'input_parameters' => [
                    'input' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
                        'format' => 'url',
                        'description' => 'URL of the media file to inspect and analyze',
                    ],
                ],
                'response' => [
                    'message' => 'File inspection completed successfully',
                    'metadata' => [
                        'file' => [
                            'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
                            'filename' => 'ForBiggerJoyrides.mp4'
                        ],
                        'format' => [
                            'container' => 'mov,mp4,m4a,3gp,3g2,mj2',
                            'duration' => '00:00:15.32',
                            'duration_seconds' => 15.32,
                            'bitrate' => '1086 kb/s',
                            'bitrate_kbps' => 1086
                        ],
                        'video_streams' => [
                            [
                                'index' => 0,
                                'codec' => 'h264',
                                'resolution' => '1920x1080',
                                'fps' => 30.0
                            ]
                        ],
                        'audio_streams' => [
                            [
                                'index' => 1,
                                'codec' => 'aac',
                                'sample_rate' => '48000 Hz',
                                'sample_rate_hz' => 48000,
                                'channels' => 'stereo'
                            ]
                        ],
                        'subtitle_streams' => [],
                        'metadata' => [
                            'title' => 'Sample Video',
                            'comment' => 'Created with FFmpeg'
                        ],
                        'summary' => [
                            'has_video' => true,
                            'has_audio' => true,
                            'has_subtitles' => false,
                            'total_streams' => 2,
                            'video_count' => 1,
                            'audio_count' => 1,
                            'subtitle_count' => 0
                        ]
                    ]
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => FileInspectionRequest::class,
                'function_name' => 'fileInspection',
            ],
            [
                'name' => 'Video Thumbnail Generation',
                'input_parameters' => [
                    'input' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
                        'format' => 'url',
                        'description' => 'URL of the video file to generate thumbnail from',
                    ],
                    'timestamp' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => '00:00:05',
                        'pattern' => '^\\d{2}:\\d{2}:\\d{2}(\\.\\d+)?$',
                        'description' => 'Timestamp to extract thumbnail from (format: HH:MM:SS or HH:MM:SS.MS). Required field.',
                        'examples' => [
                            '00:00:05',
                            '00:01:30',
                            '00:02:15.5',
                            '01:30:45'
                        ],
                    ],
                ],
                'response' => [
                    'message' => 'Thumbnail generated successfully',
                    'thumbnail_url' => 'https://output.example.com/thumbnail_123456.jpg',
                    'timestamp' => '00:00:05',
                    'processing_time' => 2.1,
                    'thumbnail_size' => '1280x720',
                    'image_format' => 'JPEG',
                    'image_quality' => 'high',
                    'file_size' => '245KB',
                    'source_video' => 'ForBiggerJoyrides.mp4'
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => ThumbnailRequest::class,
                'function_name' => 'thumbnail',
            ],
            [
                'name' => 'Video Bitrate Control',
                'input_parameters' => [
                    'input' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
                        'format' => 'url',
                        'description' => 'URL of the video file to apply bitrate control',
                    ],
                    'crf' => [
                        'type' => 'integer',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => 23,
                        'min' => 0,
                        'max' => 51,
                        'description' => 'Constant Rate Factor (0-51). Lower values = higher quality. 18-28 is typical range. Required field.',
                        'examples' => [18, 20, 23, 28, 35],
                    ],
                    'preset' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => 'medium',
                        'enum' => ['ultrafast', 'superfast', 'veryfast', 'faster', 'fast', 'medium', 'slow', 'slower', 'veryslow'],
                        'description' => 'Encoding speed vs quality preset. Slower = better compression but longer processing time. Required field.',
                    ],
                    'cbr' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => '2000k',
                        'pattern' => '^\\d+[kmKM]?$',
                        'description' => 'Constant Bitrate (e.g., "2000k", "5M", "1000"). Sets fixed bitrate for consistent file sizes. Required field.',
                        'examples' => ['1000k', '2000k', '5M', '8000k', '10M'],
                    ],
                ],
                'response' => [
                    'message' => 'Bitrate control applied successfully',
                    'output_file_link' => 'https://output.example.com/bitrate_controlled_123456.mp4',
                    'processing_time' => 65.8,
                    'encoding_settings' => [
                        'crf' => 23,
                        'preset' => 'medium',
                        'video_codec' => 'libx264',
                        'audio_codec' => 'aac'
                    ],
                    'quality_optimization' => [
                        'target_quality' => 'high',
                        'compression_efficiency' => '85%',
                        'file_size_reduction' => '45%'
                    ],
                    'file_size' => '98MB',
                    'bitrate' => '2150k',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => BitrateControlRequest::class,
                'function_name' => 'bitrateControl',
            ],
            [
                'name' => 'Stream Copy',
                'input_parameters' => [
                    'input' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
                        'format' => 'url',
                        'description' => 'URL of the media file to copy streams from',
                    ],
                    'streams' => [
                        'type' => 'array',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => ['video:0', 'audio:0'],
                        'min_items' => 1,
                        'max_items' => 10,
                        'items' => [
                            'type' => 'string',
                            'pattern' => '^(video|audio|subtitle|data|all):\\d+$|^(all)$',
                        ],
                        'description' => 'Array of stream specifications to copy. Format: "type:index" (e.g., "video:0", "audio:1") or "all" for all streams.',
                        'examples' => [
                            ['video:0', 'audio:0'],
                            ['video:0'],
                            ['audio:0', 'audio:1'],
                            ['all'],
                            ['video:0', 'subtitle:0'],
                        ],
                        'stream_types' => [
                            'video' => 'Video streams (e.g., video:0, video:1)',
                            'audio' => 'Audio streams (e.g., audio:0, audio:1)',
                            'subtitle' => 'Subtitle streams (e.g., subtitle:0)',
                            'data' => 'Data streams (e.g., data:0)',
                            'all' => 'Copy all streams without re-encoding'
                        ],
                    ],
                ],
                'response' => [
                    'message' => 'Stream copy completed successfully',
                    'output_file_link' => 'https://output.example.com/stream_copy_123456.mp4',
                    'processing_time' => 5.2,
                    'copied_streams' => [
                        'video:0' => 'h264, 1920x1080, 30fps',
                        'audio:0' => 'aac, stereo, 44100Hz'
                    ],
                    'operation_type' => 'stream_copy',
                    'encoding_method' => 'copy (no re-encoding)',
                    'quality_preservation' => '100% lossless',
                    'speed_benefit' => 'fast (no encoding overhead)',
                    'file_size' => '95MB',
                    'original_format' => 'mp4',
                    'output_format' => 'mp4',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => StreamCopyRequest::class,
                'function_name' => 'streamCopy',
            ],
            [
                'name' => 'Video Encoding',
                'input_parameters' => [
                    'input' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
                        'format' => 'url',
                        'description' => 'URL of the video file to encode',
                    ],
                    'codec' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => 'libx264',
                        'enum' => ['libx264', 'libx265', 'libvpx', 'libvpx-vp9', 'libaom-av1', 'libsvtav1', 'mpeg4', 'libxvid', 'h264_nvenc', 'hevc_nvenc', 'h264_videotoolbox', 'hevc_videotoolbox'],
                        'description' => 'Video codec to use for encoding. Required field.',
                        'codec_info' => [
                            'libx264' => 'H.264/AVC - Most compatible, good quality',
                            'libx265' => 'H.265/HEVC - Better compression, newer standard',
                            'libvpx' => 'VP8 - Open source, web-friendly',
                            'libvpx-vp9' => 'VP9 - Better than VP8, YouTube uses this',
                            'libaom-av1' => 'AV1 - Next-gen codec, excellent compression',
                            'libsvtav1' => 'SVT-AV1 - Intel\'s fast AV1 encoder',
                            'mpeg4' => 'MPEG-4 Part 2 - Legacy codec',
                            'libxvid' => 'Xvid - Legacy MPEG-4 implementation',
                            'h264_nvenc' => 'H.264 NVIDIA GPU encoder',
                            'hevc_nvenc' => 'H.265 NVIDIA GPU encoder',
                            'h264_videotoolbox' => 'H.264 Apple hardware encoder',
                            'hevc_videotoolbox' => 'H.265 Apple hardware encoder'
                        ],
                    ],
                    'params' => [
                        'type' => 'array',
                        'required' => true,
                        'userinput_rqd' => true,
                        'default' => ['crf=23', 'preset=medium'],
                        'min_items' => 1,
                        'max_items' => 20,
                        'items' => [
                            'type' => 'string',
                            'pattern' => '^[a-zA-Z_][a-zA-Z0-9_-]*(?:=[a-zA-Z0-9_.-]+)?$',
                        ],
                        'description' => 'Array of encoding parameters in "key=value" or "key" format. Required field.',
                        'examples' => [
                            ['crf=23', 'preset=medium'],
                            ['b:v=2000k', 'maxrate=2000k', 'bufsize=4000k'],
                            ['qp=28', 'profile=main'],
                            ['crf=18', 'preset=slow', 'tune=film'],
                            ['pix_fmt=yuv420p', 'movflags=+faststart'],
                        ],
                        'common_params' => [
                            'crf' => 'Constant Rate Factor (0-51, lower=better quality)',
                            'preset' => 'Encoding speed (ultrafast, superfast, veryfast, faster, fast, medium, slow, slower, veryslow)',
                            'tune' => 'Optimize for content type (film, animation, grain, stillimage, psnr, ssim, fastdecode, zerolatency)',
                            'profile' => 'Encoder profile (baseline, main, high)',
                            'level' => 'Encoder level (3.0, 3.1, 4.0, 4.1, 5.0, 5.1)',
                            'b:v' => 'Video bitrate (e.g., 2000k, 5M)',
                            'maxrate' => 'Maximum bitrate for CBR',
                            'bufsize' => 'Buffer size for rate control',
                            'pix_fmt' => 'Pixel format (yuv420p, yuv444p)',
                            'movflags' => 'MP4 optimization flags (+faststart)'
                        ],
                    ],
                ],
                'response' => [
                    'message' => 'Video encoded successfully',
                    'output_file_link' => 'https://output.example.com/video_encoded_123456.mp4',
                    'processing_time' => 85.4,
                    'encoding_settings' => [
                        'codec' => 'libx264',
                        'parameters_applied' => [
                            'crf' => '23',
                            'preset' => 'medium'
                        ],
                        'video_codec' => 'libx264',
                        'audio_codec' => 'aac'
                    ],
                    'quality_metrics' => [
                        'original_size' => '120MB',
                        'encoded_size' => '85MB',
                        'compression_ratio' => '29.2%',
                        'quality_setting' => 'crf=23 (high quality)',
                        'encoding_speed' => 'medium preset'
                    ],
                    'technical_details' => [
                        'resolution' => '1920x1080',
                        'frame_rate' => '30fps',
                        'pixel_format' => 'yuv420p',
                        'bitrate' => '1890k',
                        'duration' => '15.32s'
                    ],
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => VideoEncodeRequest::class,
                'function_name' => 'videoEncode',
            ],
            [
                'name' => 'Batch Processing',
                'input_parameters' => [
                    'jobs' => [
                        'type' => 'array',
                        'required' => true,
                        'userinput_rqd' => true,
                        'min_items' => 1,
                        'max_items' => 10,
                        'items' => [
                            'type' => 'object',
                            'properties' => [
                                'service_type_id' => [
                                    'type' => 'string',
                                    'required' => true,
                                    'description' => 'MongoDB ObjectId of the FFmpeg service type to execute',
                                ],
                                'input_data' => [
                                    'type' => 'object',
                                    'required' => true,
                                    'description' => 'Input parameters specific to the selected service type',
                                ],
                            ],
                        ],
                        'default' => [
                            [
                                'service_type_id' => '507f1f77bcf86cd799439011',
                                'input_data' => [
                                    'file_link' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_1mb.mp4',
                                    'resolution' => '1920x1080',
                                    'bitrate' => '2000k',
                                    'frame_rate' => 30,
                                ],
                            ],
                            [
                                'service_type_id' => '507f1f77bcf86cd799439012',
                                'input_data' => [
                                    'file_link' => 'https://www.soundjay.com/misc/sounds/bell-ringing-05.wav',
                                    'bitrate' => '192k',
                                    'channels' => 2,
                                    'sample_rate' => 44100,
                                ],
                            ],
                        ],
                        'description' => 'Array of FFmpeg processing jobs to execute concurrently. Each job contains a service_type_id and corresponding inputs.',
                    ],
                ],
                'response' => [
                    'message' => 'Batch processing completed',
                    'total_jobs' => 2,
                    'successful_jobs' => 2,
                    'failed_jobs' => 0,
                    'results' => [
                        [
                            'job_id' => 'chunk_0_job_0',
                            'service_type_id' => '507f1f77bcf86cd799439011',
                            'function_name' => 'processVideo',
                            'status' => 'success',
                            'result' => 'https://output.example.com/processed_video_123456.mp4',
                            'processing_time' => 45.2,
                        ],
                        [
                            'job_id' => 'chunk_0_job_1',
                            'service_type_id' => '507f1f77bcf86cd799439012',
                            'function_name' => 'processAudio',
                            'status' => 'success',
                            'result' => 'https://output.example.com/processed_audio_123456.mp3',
                            'processing_time' => 12.5,
                        ],
                    ],
                    'batch_processing_time' => 57.7,
                    'efficiency_ratio' => '85%',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => BatchProcessRequest::class,
                'function_name' => 'batchProcess',
            ],
        ];

        $keptServiceTypeIds = $this->processServiceTypes($serviceProvider, $serviceTypes, 'FFmpeg');

        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);

        $this->command->info('Cleanup completed:');
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info('- Kept ' . count($keptServiceTypeIds) . ' service types for FFmpeg');
    }
}
