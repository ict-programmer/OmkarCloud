<?php

namespace App\Services;

use App\Data\Request\FFMpeg\AudioProcessingData;
use App\Data\Request\FFMpeg\ImageProcessingData;
use App\Data\Request\FFMpeg\VideoProcessingData;
use App\Data\Request\FFMpeg\VideoTrimmingData;
use App\Traits\PubliishIOTrait;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
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
     * Process the video with the given parameters.
     *
     * @param VideoProcessingData $data
     * @return string
     * @throws ConnectionException
     * @throws RequestException
     */
    public function processVideo(VideoProcessingData $data): string
    {
        return $this->runAndUpload(
            $data->input_file,
            [
                '-i', $data->input_file,
                '-s', $data->resolution,
                '-b:v', $data->bitrate,
                '-r', $data->frame_rate,
            ]
        );
    }

    /**
     * Process the video trimming.
     *
     * @param mixed $file
     * @param array $options
     * @return string
     * @throws ConnectionException
     * @throws RequestException
     */
    private function runAndUpload(mixed $file, array $options): string
    {
        $this->generateOutputFileName($file);

        $mergedOptions = array_merge([
            $this->ffmpeg,
        ], $options, [
            $this->filePath
        ]);
        $this->run($mergedOptions);

        $path = $this->uploadImage($this->filePath);
        $this->deleteTempFile();

        return $this->getPublishUrl($path);
    }

    /**
     * Generate a unique output file name based on the input file.
     *
     * @param mixed $inputFile The input file.
     */
    public function generateOutputFileName(mixed $inputFile): void
    {
        $inputExt = $inputFile->getClientOriginalExtension();
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
     * Process the audio with the given parameters.
     *
     * @param AudioProcessingData $data
     * @return string
     * @throws ConnectionException
     * @throws RequestException
     */
    public function processAudio(AudioProcessingData $data): string
    {
        return $this->runAndUpload(
            $data->input_file,
            [
                '-i', $data->input_file,
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
        return $this->runAndUpload(
            $data->input_file,
            [
                '-i', $data->input_file,
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
        return $this->runAndUpload(
            $data->input_file,
            [
                '-ss', $data->start_time,
                '-to', $data->end_time,
                '-i', $data->input_file,
                '-c', 'copy',
            ]
        );
    }
}