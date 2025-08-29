<?php

namespace App\Services;

use App\Data\Request\FFMpeg\AudioProcessingData;
use App\Data\Request\FFMpeg\ImageProcessingData;
use App\Data\Request\FFMpeg\LoudnessNormalizationData;
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

    protected string $ffmpeg, $fileName, $filePath;

    public function __construct()
    {
        $this->ffmpeg = config('services.ffmpeg.path');
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

        $path = $this->uploadImage($this->filePath);
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
}