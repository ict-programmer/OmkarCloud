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
        ServiceProvider::query()
            ->where('_id', '67fe35ad3877ee2db4854635')
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
    }
} 