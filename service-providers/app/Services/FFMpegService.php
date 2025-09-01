<?php

namespace App\Services;

use App\Data\Request\FFMpeg\AudioFadesData;
use App\Data\Request\FFMpeg\AudioOverlayData;
use App\Data\Request\FFMpeg\AudioProcessingData;
use App\Data\Request\FFMpeg\AudioVolumeData;
use App\Data\Request\FFMpeg\ConcatenateData;
use App\Data\Request\FFMpeg\FFProbeData;
use App\Data\Request\FFMpeg\FrameExtractionData;
use App\Data\Request\FFMpeg\ImageProcessingData;
use App\Data\Request\FFMpeg\LoudnessNormalizationData;
use App\Data\Request\FFMpeg\ScaleData;
use App\Data\Request\FFMpeg\TranscodingData;
use App\Data\Request\FFMpeg\VideoProcessingData;
use App\Data\Request\FFMpeg\VideoTrimmingData;
use App\Traits\PubliishIOTrait;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class FFMpegService
{
    use PubliishIOTrait;

    protected string $ffmpeg, $ffprobe, $fileName, $filePath;

    public function __construct()
    {
        $this->ffmpeg = config('services.ffmpeg.path');
        $this->ffprobe = config('services.ffprobe.path');
    }

    /**
     * Download file from URL to local temporary file.
     *
     * @param string $fileUrl
     * @param string $defaultExtension Default extension to use if URL doesn't have one
     * @return string
     * @throws ConnectionException
     */
    private function downloadFile(string $fileUrl, string $defaultExtension = 'tmp'): string
    {
        $urlPath = parse_url($fileUrl, PHP_URL_PATH);
        $extension = pathinfo($urlPath, PATHINFO_EXTENSION);
        
        // If no extension found in URL, use default or detect from content
        if (empty($extension)) {
            $extension = $defaultExtension;
        }
        
        $fileName = time() . '_' . uniqid() . '.' . $extension;
        $localPath = storage_path($fileName);

        $response = Http::timeout(300)->get($fileUrl);
        
        if ($response->failed()) {
            throw new ConnectionException('Failed to download file from URL: ' . $fileUrl);
        }

        // Try to detect actual file type from content headers if extension was defaulted
        if ($extension === $defaultExtension) {
            $contentType = $response->header('content-type');
            if ($contentType) {
                $detectedExtension = $this->getExtensionFromContentType($contentType);
                if ($detectedExtension) {
                    $extension = $detectedExtension;
                    $fileName = time() . '_' . uniqid() . '.' . $extension;
                    $localPath = storage_path($fileName);
                }
            }
        }

        file_put_contents($localPath, $response->body());
        
        return $localPath;
    }

    /**
     * Get file extension from content type.
     *
     * @param string $contentType
     * @return string|null
     */
    private function getExtensionFromContentType(string $contentType): ?string
    {
        $mimeToExtension = [
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'image/bmp' => 'bmp',
            'video/mp4' => 'mp4',
            'video/avi' => 'avi',
            'video/mov' => 'mov',
            'video/quicktime' => 'mov',
            'video/x-msvideo' => 'avi',
            'audio/mpeg' => 'mp3',
            'audio/mp3' => 'mp3',
            'audio/wav' => 'wav',
            'audio/ogg' => 'ogg',
            'audio/aac' => 'aac',
        ];

        return $mimeToExtension[$contentType] ?? null;
    }

    /**
     * Process the video with the given parameters.
     *
     * @param VideoProcessingData $data
     * @return string
     * @throws ConnectionException
     * @throws RequestException
     */
    public function processVideo(VideoProcessingData $data): string
    {
        $inputFilePath = $this->downloadFile($data->file_link, 'mp4');
        
        return $this->runAndUpload(
            $inputFilePath,
            [
                '-i', $inputFilePath,
                '-s', $data->resolution,
                '-b:v', $data->bitrate,
                '-r', $data->frame_rate,
            ],
            'mp4'
        );
    }

    /**
     * Process the file and upload the result.
     *
     * @param string $inputFilePath
     * @param array $options
     * @param string $outputExtension
     * @return string
     * @throws ConnectionException
     * @throws RequestException
     */
    private function runAndUpload(string $inputFilePath, array $options, string $outputExtension = null): string
    {
        $this->generateOutputFileName($inputFilePath, $outputExtension);

        $mergedOptions = array_merge([
            $this->ffmpeg,
        ], $options, [
            $this->filePath
        ]);
        $this->run($mergedOptions);

        $path = $this->uploadFile($this->filePath);
        $this->deleteTempFile();
        $this->deleteInputFile($inputFilePath);

        return $this->getPublishUrl($path);
    }

    /**
     * Generate a unique output file name based on the input file.
     *
     * @param string $inputFilePath The input file path.
     * @param string $outputExtension Optional specific extension for output file.
     */
    public function generateOutputFileName(string $inputFilePath, string $outputExtension = null): void
    {
        if ($outputExtension) {
            $extension = $outputExtension;
        } else {
            $extension = pathinfo($inputFilePath, PATHINFO_EXTENSION);
            // Ensure we have a valid extension
            if (empty($extension)) {
                $extension = 'tmp';
            }
        }
        
        $inputName = time() . '_' . uniqid();
        $this->fileName = $inputName . '.' . $extension;
        $this->filePath = storage_path($this->fileName);
    }

    protected function run(array $command): void
    {
        $process = new Process($command);
        $process->setTimeout(null);
        $process->run();

        if (!$process->isSuccessful())
            throw new ProcessFailedException($process);
    }

    /**
     * Delete the temporary file.
     */
    public function deleteTempFile(): void
    {
        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }
    }

    /**
     * Delete the downloaded input file.
     */
    public function deleteInputFile(string $inputFilePath): void
    {
        if (file_exists($inputFilePath)) {
            unlink($inputFilePath);
        }
    }

    /**
     * Process the audio with the given parameters.
     *
     * @param AudioProcessingData $data
     * @return string
     * @throws ConnectionException
     * @throws RequestException
     */
    public function processAudio(AudioProcessingData $data): string
    {
        $inputFilePath = $this->downloadFile($data->file_link, 'mp3');
        
        return $this->runAndUpload(
            $inputFilePath,
            [
                '-i', $inputFilePath,
                '-ab', $data->bitrate,
                '-ac', $data->channels,
                '-ar', $data->sample_rate,
            ],
            'mp3'
        );
    }

    /**
     * Process the image with the given parameters.
     *
     * @param ImageProcessingData $data
     * @return string
     * @throws ConnectionException
     * @throws RequestException
     */
    public function processImage(ImageProcessingData $data): string
    {
        $inputFilePath = $this->downloadFile($data->file_link, 'jpg');
        
        return $this->runAndUpload(
            $inputFilePath,
            [
                '-i', $inputFilePath,
                '-vf', 'scale=' . $data->width . ':' . $data->height,
            ],
            'jpg'
        );
    }

    /**
     * Trim the video based on the provided data.
     *
     * @param VideoTrimmingData $data
     * @return string
     * @throws ConnectionException
     * @throws RequestException
     */
    public function trimVideo(VideoTrimmingData $data): string
    {
        $inputFilePath = $this->downloadFile($data->file_link, 'mp4');
        
        return $this->runAndUpload(
            $inputFilePath,
            [
                '-ss', $data->start_time,
                '-to', $data->end_time,
                '-i', $inputFilePath,
                '-c', 'copy',
            ],
            'mp4'
        );
    }

    /**
     * Normalize loudness of audio/video based on the provided data.
     *
     * @param LoudnessNormalizationData $data
     * @return string
     * @throws ConnectionException
     * @throws RequestException
     */
    public function normalizeLoudness(LoudnessNormalizationData $data): string
    {
        $inputFilePath = $this->downloadFile($data->file_link, 'mp4');
        
        return $this->runAndUpload(
            $inputFilePath,
            [
                '-i', $inputFilePath,
                '-af', 'loudnorm=I=' . $data->target_lufs . ':LRA=' . $data->lra . ':TP=' . $data->tp,
                '-c:v', 'copy',
            ],
            'mp4'
        );
    }

    /**
     * Probe media file and return information.
     *
     * @param FFProbeData $data
     * @return array
     * @throws ConnectionException
     * @throws RequestException
     */
    public function probeMedia(FFProbeData $data): array
    {
        $inputFilePath = $this->downloadFile($data->file_link);
        
        // Build ffprobe command
        $command = [
            $this->ffprobe,
            '-v', 'quiet',
            '-print_format', $data->output_format,
        ];

        // Add show options
        if ($data->show_format) {
            $command[] = '-show_format';
        }
        if ($data->show_streams) {
            $command[] = '-show_streams';
        }
        if ($data->show_chapters) {
            $command[] = '-show_chapters';
        }
        if ($data->show_programs) {
            $command[] = '-show_programs';
        }

        // Add stream selection if specified
        if (!empty($data->select_streams)) {
            $command[] = '-select_streams';
            $command[] = $data->select_streams;
        }

        // Add input file
        $command[] = $inputFilePath;

        // Run ffprobe command
        $process = new Process($command);
        $process->setTimeout(300);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // Clean up input file
        $this->deleteInputFile($inputFilePath);

        $output = $process->getOutput();
        
        // Parse output based on format
        if ($data->output_format === 'json') {
            return json_decode($output, true) ?? [];
        }
        
        // For other formats, return raw output
        return ['raw_output' => $output];
    }

    /**
     * Transcode media file with specified parameters.
     *
     * @param TranscodingData $data
     * @return string
     * @throws ConnectionException
     * @throws RequestException
     */
    public function transcodeMedia(TranscodingData $data): string
    {
        $inputFilePath = $this->downloadFile($data->file_link);
        
        // Set default transcoding parameters
        $videoCodec = 'libx264';
        $audioCodec = 'aac';
        $preset = 'medium';
        
        // Build ffmpeg command for transcoding
        $command = [
            '-i', $inputFilePath,
        ];

        // Determine output type based on format
        $isAudioOnly = in_array($data->output_format, ['mp3', 'wav', 'flac', 'aac', 'ogg', 'm4a', 'wma']);
        
        if ($isAudioOnly) {
            // Audio-only output - remove video stream
            $command[] = '-vn';
            $command[] = '-c:a';
            
            // Set appropriate audio codec based on format
            switch ($data->output_format) {
                case 'mp3':
                    $command[] = 'libmp3lame';
                    break;
                case 'wav':
                    $command[] = 'pcm_s16le';
                    break;
                case 'flac':
                    $command[] = 'flac';
                    break;
                case 'ogg':
                    $command[] = 'libvorbis';
                    break;
                case 'm4a':
                case 'aac':
                default:
                    $command[] = 'aac';
                    break;
            }
        } else {
            // Video output with both video and audio
            
            // Video codec
            $command[] = '-c:v';
            $command[] = $videoCodec;
            
            // Video preset for encoding speed vs compression
            $command[] = '-preset';
            $command[] = $preset;
            
            // Audio codec
            $command[] = '-c:a';
            $command[] = $audioCodec;
        }

        return $this->runAndUpload(
            $inputFilePath,
            $command,
            $data->output_format
        );
    }

    /**
     * Overlay audio files using FFmpeg amix filter.
     *
     * @param AudioOverlayData $data
     * @return string
     * @throws ConnectionException
     * @throws RequestException
     */
    public function overlayAudio(AudioOverlayData $data): string
    {
        // Download both audio files
        $mainAudioPath = $this->downloadFile($data->background_track);
        $overlayAudioPath = $this->downloadFile($data->overlay_track);
        
        // Set audio codec based on output format
        $audioCodec = match ($data->output_format) {
            'wav' => 'pcm_s16le',
            'flac' => 'flac',
            'aac', 'm4a' => 'aac',
            'ogg' => 'libvorbis',
            'wma' => 'wmav2',
            default => 'libmp3lame',
        };
        
        // Build FFmpeg command for audio overlay using amix filter
        $command = [
            '-analyzeduration', '10M',
            '-probesize', '10M',
            '-i', $mainAudioPath,
            '-i', $overlayAudioPath,
            '-filter_complex', '[0:a][1:a]amix=inputs=2:duration=first:dropout_transition=3',
            '-c:a', $audioCodec,
            '-ar', '44100',
            '-ac', '2',
        ];

        // Use a temporary name for the main audio file for runAndUpload
        $result = $this->runAndUpload(
            $mainAudioPath,
            $command,
            $data->output_format
        );

        // Clean up the overlay audio file
        $this->deleteInputFile($overlayAudioPath);

        return $result;
    }

    /**
     * Extract frames from video at specified frame rate.
     *
     * @param FrameExtractionData $data
     * @return array
     * @throws ConnectionException
     * @throws RequestException
     */
    public function extractFrames(FrameExtractionData $data): array
    {
        // Download input video file
        $inputFilePath = $this->downloadFile($data->input_file, 'mp4');
        
        // Create a temporary directory for extracted frames
        $frameDir = storage_path('frames_' . time() . '_' . uniqid());
        mkdir($frameDir, 0755, true);
        
        // Frame filename pattern
        $framePattern = $frameDir . '/frame_%04d.jpg';
        
        // Build FFmpeg command for frame extraction
        $command = [
            $this->ffmpeg,
            '-i', $inputFilePath,
            '-r', (string) $data->frame_rate,  // Frame rate
            '-f', 'image2',                    // Output format
            '-q:v', '2',                       // High quality (1-31, lower is better)
            $framePattern
        ];

        // Run FFmpeg command
        $process = new Process($command);
        $process->setTimeout(300);
        $process->run();

        if (!$process->isSuccessful()) {
            // Clean up on failure
            $this->deleteInputFile($inputFilePath);
            $this->deleteDirectory($frameDir);
            throw new ProcessFailedException($process);
        }

        // Get all extracted frame files
        $frameFiles = glob($frameDir . '/*.jpg');
        sort($frameFiles); // Ensure proper order
        
        $frameUrls = [];
        
        // Upload each frame and collect URLs
        foreach ($frameFiles as $frameFile) {
            $uploadedPath = $this->uploadFile($frameFile);
            $frameUrls[] = $this->getPublishUrl($uploadedPath);
            unlink($frameFile); // Clean up local frame file
        }
        
        // Clean up
        $this->deleteInputFile($inputFilePath);
        $this->deleteDirectory($frameDir);
        
        return $frameUrls;
    }

    /**
     * Delete directory and its contents.
     *
     * @param string $dirPath
     */
    private function deleteDirectory(string $dirPath): void
    {
        if (is_dir($dirPath)) {
            $files = array_diff(scandir($dirPath), ['.', '..']);
            foreach ($files as $file) {
                $filePath = $dirPath . '/' . $file;
                is_dir($filePath) ? $this->deleteDirectory($filePath) : unlink($filePath);
            }
            rmdir($dirPath);
        }
    }

    /**
     * Adjust audio volume using FFmpeg volume filter.
     *
     * @param AudioVolumeData $data
     * @return string
     * @throws ConnectionException
     * @throws RequestException
     */
    public function adjustAudioVolume(AudioVolumeData $data): string
    {
        // Download input audio/video file
        $inputFilePath = $this->downloadFile($data->input);
        
        // Determine output format based on input file extension
        $inputExtension = pathinfo($data->input, PATHINFO_EXTENSION);
        $outputFormat = in_array(strtolower($inputExtension), ['mp3', 'wav', 'flac', 'aac', 'ogg', 'm4a', 'wma']) 
            ? strtolower($inputExtension) 
            : 'mp3';
        
        // Set audio codec based on output format
        $audioCodec = match ($outputFormat) {
            'wav' => 'pcm_s16le',
            'flac' => 'flac',
            'aac', 'm4a' => 'aac',
            'ogg' => 'libvorbis',
            'wma' => 'wmav2',
            default => 'libmp3lame',
        };
        
        // Build FFmpeg command for volume adjustment
        $command = [
            '-i', $inputFilePath,
            '-af', 'volume=' . $data->volume_factor,  // Audio filter for volume
            '-c:a', $audioCodec,
            '-c:v', 'copy',  // Copy video stream if present (for video files)
        ];

        return $this->runAndUpload(
            $inputFilePath,
            $command,
            $outputFormat
        );
    }

    /**
     * Apply audio fade in/out effects using FFmpeg afade filter.
     *
     * @param AudioFadesData $data
     * @return string
     * @throws ConnectionException
     * @throws RequestException
     */
    public function applyAudioFades(AudioFadesData $data): string
    {
        // Download input audio/video file
        $inputFilePath = $this->downloadFile($data->input);
        
        // Determine output format based on input file extension
        $inputExtension = pathinfo($data->input, PATHINFO_EXTENSION);
        $outputFormat = in_array(strtolower($inputExtension), ['mp3', 'wav', 'flac', 'aac', 'ogg', 'm4a', 'wma']) 
            ? strtolower($inputExtension) 
            : 'mp3';
        
        // Set audio codec based on output format
        $audioCodec = match ($outputFormat) {
            'wav' => 'pcm_s16le',
            'flac' => 'flac',
            'aac', 'm4a' => 'aac',
            'ogg' => 'libvorbis',
            'wma' => 'wmav2',
            default => 'libmp3lame',
        };

        // Build audio filter chain for fades
        $audioFilters = [];
        
        // Add fade in filter if specified
        if ($data->fade_in_duration !== null && $data->fade_in_duration > 0) {
            $audioFilters[] = 'afade=t=in:d=' . $data->fade_in_duration;
        }
        
        // Add fade out filter if specified
        if ($data->fade_out_duration !== null && $data->fade_out_duration > 0) {
            $audioFilters[] = 'afade=t=out:d=' . $data->fade_out_duration;
        }
        
        // Build FFmpeg command
        $command = ['-i', $inputFilePath];
        
        // Apply audio filters if any fades are specified
        if (!empty($audioFilters)) {
            $command[] = '-af';
            $command[] = implode(',', $audioFilters);
        }
        
        // Add codec and stream copy options
        $command[] = '-c:a';
        $command[] = $audioCodec;
        $command[] = '-c:v';
        $command[] = 'copy';  // Copy video stream if present

        return $this->runAndUpload(
            $inputFilePath,
            $command,
            $outputFormat
        );
    }

    /**
     * Scale/resize video using FFmpeg scale filter.
     *
     * @param ScaleData $data
     * @return string
     * @throws ConnectionException
     * @throws RequestException
     */
    public function scaleVideo(ScaleData $data): string
    {
        // Download input video file
        $inputFilePath = $this->downloadFile($data->input);
        
        // Determine output format based on input file extension
        $inputExtension = pathinfo($data->input, PATHINFO_EXTENSION);
        $outputFormat = in_array(strtolower($inputExtension), ['mp4', 'avi', 'mov', 'mkv', 'webm', 'flv', 'wmv', 'm4v', '3gp']) 
            ? strtolower($inputExtension) 
            : 'mp4';

        // Convert resolution presets to actual dimensions
        $resolution = $this->convertResolutionPreset($data->resolution_target);
        
        // Set video codec based on output format
        $videoCodec = match ($outputFormat) {
            'webm' => 'libvpx-vp9',
            'avi' => 'libxvid',
            'mov' => 'libx264',
            'mkv' => 'libx264',
            'flv' => 'libx264',
            'wmv' => 'wmv2',
            'm4v' => 'libx264',
            '3gp' => 'libx264',
            default => 'libx264', // mp4 and others
        };

        // Build FFmpeg command for scaling
        $command = [
            '-i', $inputFilePath,
            '-vf', 'scale=' . $resolution,  // Video filter for scaling
            '-c:v', $videoCodec,
            '-c:a', 'aac',  // Keep audio codec consistent
            '-preset', 'medium',  // Encoding speed vs quality balance
            '-crf', '23',  // Quality setting (lower = better quality)
        ];

        return $this->runAndUpload(
            $inputFilePath,
            $command,
            $outputFormat
        );
    }

    /**
     * Convert resolution presets to actual dimensions.
     *
     * @param string $resolution
     * @return string
     */
    private function convertResolutionPreset(string $resolution): string
    {
        return match (strtolower($resolution)) {
            '720p' => '1280:720',
            '1080p' => '1920:1080',
            '1440p' => '2560:1440',
            '2160p', '4k' => '3840:2160',
            '8k' => '7680:4320',
            default => str_replace('x', ':', $resolution), // Convert WxH to W:H format
        };
    }

    /**
     * Concatenate multiple video files using FFmpeg concat demuxer.
     *
     * @param ConcatenateData $data
     * @return string
     * @throws ConnectionException
     * @throws RequestException
     */
    public function concatenateVideos(ConcatenateData $data): string
    {
        // Download all input files
        $inputFilePaths = [];
        $outputFormat = 'mp4'; // Default output format
        
        foreach ($data->input_files as $index => $fileUrl) {
            $inputFilePath = $this->downloadFile($fileUrl);
            $inputFilePaths[] = $inputFilePath;
            
            // Determine output format from first file
            if ($index === 0) {
                $inputExtension = pathinfo($fileUrl, PATHINFO_EXTENSION);
                $outputFormat = in_array(strtolower($inputExtension), ['mp4', 'avi', 'mov', 'mkv', 'webm', 'flv', 'wmv', 'm4v', '3gp']) 
                    ? strtolower($inputExtension) 
                    : 'mp4';
            }
        }

        try {
            // Use filter_complex method for more reliable concatenation
            $command = [];
            
            // Add all input files
            foreach ($inputFilePaths as $filePath) {
                $command[] = '-i';
                $command[] = $filePath;
            }
            
            // Build filter_complex for concatenation
            $inputCount = count($inputFilePaths);
            $filterInputs = '';
            for ($i = 0; $i < $inputCount; $i++) {
                $filterInputs .= "[$i:v][$i:a]";
            }
            
            $command[] = '-filter_complex';
            $command[] = $filterInputs . "concat=n=$inputCount:v=1:a=1[outv][outa]";
            $command[] = '-map';
            $command[] = '[outv]';
            $command[] = '-map';
            $command[] = '[outa]';
            $command[] = '-c:v';
            $command[] = 'libx264';
            $command[] = '-c:a';
            $command[] = 'aac';
            $command[] = '-preset';
            $command[] = 'medium';
            $command[] = '-crf';
            $command[] = '23';

            // Use the first input file path as reference for runAndUpload
            $result = $this->runAndUpload(
                $inputFilePaths[0],
                $command,
                $outputFormat
            );

            // Clean up input files
            foreach ($inputFilePaths as $filePath) {
                $this->deleteInputFile($filePath);
            }

            return $result;

        } catch (\Exception $e) {
            // Clean up on error
            foreach ($inputFilePaths as $filePath) {
                $this->deleteInputFile($filePath);
            }
            throw $e;
        }
    }
}