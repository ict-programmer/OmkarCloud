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
    }
} 