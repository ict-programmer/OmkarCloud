<?php

namespace App\Services;

use App\Data\Request\FFMpeg\AudioProcessingData;
use App\Data\Request\FFMpeg\ImageProcessingData;
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
     * @return string
     * @throws ConnectionException
     */
    private function downloadFile(string $fileUrl): string
    {
        $extension = pathinfo(parse_url($fileUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
        $fileName = time() . '_' . uniqid() . '.' . $extension;
        $localPath = storage_path($fileName);

        $response = Http::timeout(300)->get($fileUrl);
        
        if ($response->failed()) {
            throw new ConnectionException('Failed to download file from URL: ' . $fileUrl);
        }

        file_put_contents($localPath, $response->body());
        
        return $localPath;
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
        $inputFilePath = $this->downloadFile($data->input_file);
        
        return $this->runAndUpload(
            $inputFilePath,
            [
                '-i', $inputFilePath,
                '-s', $data->resolution,
                '-b:v', $data->bitrate,
                '-r', $data->frame_rate,
            ]
        );
    }

    /**
     * Process the file and upload the result.
     *
     * @param string $inputFilePath
     * @param array $options
     * @return string
     * @throws ConnectionException
     * @throws RequestException
     */
    private function runAndUpload(string $inputFilePath, array $options): string
    {
        $this->generateOutputFileName($inputFilePath);

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
     */
    public function generateOutputFileName(string $inputFilePath): void
    {
        $inputExt = pathinfo($inputFilePath, PATHINFO_EXTENSION);
        $inputName = time() . '_' . uniqid();
        $this->fileName = $inputName . '.' . $inputExt;
        $this->filePath = storage_path($this->fileName);
    }

    protected function run(array $command): void
    {
        $process = new Process($command);
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
        $inputFilePath = $this->downloadFile($data->input_file);
        
        return $this->runAndUpload(
            $inputFilePath,
            [
                '-i', $inputFilePath,
                '-ab', $data->bitrate,
                '-ac', $data->channels,
                '-ar', $data->sample_rate,
            ]
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
        $inputFilePath = $this->downloadFile($data->input_file);
        
        return $this->runAndUpload(
            $inputFilePath,
            [
                '-i', $inputFilePath,
                '-vf', 'scale=' . $data->width . ':' . $data->height,
            ]
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
        $inputFilePath = $this->downloadFile($data->input_file);
        
        return $this->runAndUpload(
            $inputFilePath,
            [
                '-ss', $data->start_time,
                '-to', $data->end_time,
                '-i', $inputFilePath,
                '-c', 'copy',
            ]
        );
    }
}