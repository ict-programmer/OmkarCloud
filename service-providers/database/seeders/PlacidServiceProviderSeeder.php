<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlacidServiceProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::connection('mongodb')->collection('service_providers')->insert([
            'type' => 'Placid',
            'parameter' => [
                'api_key' => 'your-api-key',
                'version' => 'latest',
                'supported_templates' => ['image', 'video', 'pdf'],
                'max_requests' => 5000
            ]
        ]);

        $connection = DB::connection('mongodb');

        $placidProviderId = $connection
            ->collection('service_providers')
            ->where('type', 'Placid')
            ->value('_id');

        $connection->collection('service_types')->insert([
            [
                'name' => 'Image Generation',
                'service_provider_id' => $placidProviderId,
                'parameter' => [
                    'template_id' => 'your-template-id',
                    'text' => 'Custom text',
                    'image_url' => 'https://example.com/image.jpg',
                    'output_format' => 'png'
                ]
            ],
            [
                'name' => 'Video Generation',
                'service_provider_id' => $placidProviderId,
                'parameter' => [
                    'template_id' => 'your-template-id',
                    'text' => 'Overlay text',
                    'video_url' => 'https://example.com/video.mp4',
                    'output_format' => 'mp4'
                ]
            ],
            [
                'name' => 'PDF Generation',
                'service_provider_id' => $placidProviderId,
                'parameter' => [
                    'template_id' => 'your-template-id',
                    'content' => 'Custom content for the PDF',
                    'output_format' => 'pdf'
                ]
            ]
        ]);
    }
}
