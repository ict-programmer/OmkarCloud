<?php

namespace Database\Seeders;

use App\Http\Requests\FFMpeg\VideoProcessingRequest;
use App\Models\ServiceProvider;
use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class UpdateControllerNamesAndServiceTypeForAmerOwnershipServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //ffmpeg
        ServiceProvider::query()
            ->where('_id', '685abe49f873c0c0d50de282')
            ->update(['controller_name' => 'App\Http\Controllers\FFMpegServiceController']);
        ServiceType::query()->where('_id', '67fe36023877ee2db485463a')
            ->update([
                'request_class_name' => 'App\Http\Requests\FFMpeg\VideoProcessingRequest',
                'function_name' => 'videoProcessing',
            ]);
        ServiceType::query()->where('_id', '681a7dbb8ad97b908499d1a0')
            ->update([
                'request_class_name' => 'App\Http\Requests\FFMpeg\AudioProcessingRequest',
                'function_name' => 'audioProcessing',
            ]);
        ServiceType::query()->where('_id', '681a7dbb8ad97b908499d1a1')
            ->update([
                'request_class_name' => 'App\Http\Requests\FFMpeg\ImageProcessingRequest',
                'function_name' => 'imageProcessing',
            ]);
        ServiceType::query()->where('_id', '681a7dbb8ad97b908499d1a2')
            ->update([
                'request_class_name' => 'App\Http\Requests\FFMpeg\VideoTrimmingRequest',
                'function_name' => 'videoTrimming',
            ]);

        // whishper
        ServiceProvider::query()
            ->where('_id', '685ac48399970e4f48018022')
            ->update(['controller_name' => 'App\Http\Controllers\WhisperController']);
        ServiceType::query()->where('_id', '681a7c188ad97b908499d18b')
            ->update([
                'request_class_name' => 'App\Http\Requests\Whisper\AudioTranscribeRequest',
                'function_name' => 'audioTranscribe',
            ]);
        ServiceType::query()->where('_id', '681a7c188ad97b908499d18d')
            ->update([
                'request_class_name' => 'App\Http\Requests\Whisper\AudioTranscribeRequest',
                'function_name' => 'audioTranscribeTimestamps',
            ]);
        ServiceType::query()->where('_id', '681a7c188ad97b908499d18e')
            ->update([
                'request_class_name' => 'App\Http\Requests\Whisper\AudioTranscribeRequest',
                'function_name' => 'audioTranslate',
            ]);

        //shutterstock
        ServiceProvider::query()
            ->where('_id', '685abc565d8a11cd600acac2')
            ->update(['controller_name' => 'App\Http\Controllers\ShutterstockController']);
        ServiceType::query()->where('_id', '685ab7d75a9d28334c054fd2')
            ->update([
                'request_class_name' => 'App\Http\Requests\Shutterstock\SearchImagesRequest',
                'function_name' => 'searchImages',
            ]);
        ServiceType::query()->where('_id', '685ab7d75a9d28334c054fd3')
            ->update([
                'request_class_name' => 'App\Http\Requests\Shutterstock\GetImageRequest',
                'function_name' => 'getImage',
            ]);
        ServiceType::query()->where('_id', '685ab7d85a9d28334c054fd4')
            ->update([
                'request_class_name' => 'App\Http\Requests\Shutterstock\LicenseImageRequest',
                'function_name' => 'licenseImage',
            ]);
        ServiceType::query()->where('_id', '685ab7d85a9d28334c054fd5')
            ->update([
                'request_class_name' => 'App\Http\Requests\Shutterstock\DownloadImageRequest',
                'function_name' => 'downloadImage',
            ]);
        ServiceType::query()->where('_id', '685ab7d85a9d28334c054fd6')
            ->update([
                'request_class_name' => 'App\Http\Requests\Shutterstock\SearchVideosRequest',
                'function_name' => 'searchVideos',
            ]);
        ServiceType::query()->where('_id', '685ab7d95a9d28334c054fd7')
            ->update([
                'request_class_name' => 'App\Http\Requests\Shutterstock\GetVideoRequest',
                'function_name' => 'getVideo',
            ]);
        ServiceType::query()->where('_id', '685ab7d95a9d28334c054fd8')
            ->update([
                'request_class_name' => 'App\Http\Requests\Shutterstock\LicenseVideoRequest',
                'function_name' => 'licenseVideo',
            ]);
        ServiceType::query()->where('_id', '685ab7d95a9d28334c054fd9')
            ->update([
                'request_class_name' => 'App\Http\Requests\Shutterstock\DownloadVideoRequest',
                'function_name' => 'downloadVideo',
            ]);
        ServiceType::query()->where('_id', '685ab7da5a9d28334c054fda')
            ->update([
                'request_class_name' => 'App\Http\Requests\Shutterstock\SearchAudioRequest',
                'function_name' => 'searchAudio',
            ]);
        ServiceType::query()->where('_id', '685ab7da5a9d28334c054fdb')
            ->update([
                'request_class_name' => 'App\Http\Requests\Shutterstock\GetAudioRequest',
                'function_name' => 'getAudio',
            ]);
        ServiceType::query()->where('_id', '685ab7da5a9d28334c054fdc')
            ->update([
                'request_class_name' => 'App\Http\Requests\Shutterstock\LicenseAudioRequest',
                'function_name' => 'licenseAudio',
            ]);
        ServiceType::query()->where('_id', '685ab7da5a9d28334c054fdd')
            ->update([
                'request_class_name' => 'App\Http\Requests\Shutterstock\DownloadAudioRequest',
                'function_name' => 'downloadAudio',
            ]);

    }
} 