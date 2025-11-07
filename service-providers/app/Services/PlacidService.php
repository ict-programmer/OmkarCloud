<?php

namespace App\Services;

use App\Data\Request\Placid\ImageGenerationData;
use App\Data\Request\Placid\RetrievePdfData;
use App\Data\Request\Placid\RetrieveTemplateData;
use App\Data\Request\Placid\RetrieveVideoData;
use App\Data\Request\Placid\VideoGenerationData;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class PlacidService
{
    public function imageGeneration(ImageGenerationData $data): string
    {
        $response = $this->callPlacidAPI(
            url: config('placid.image_link'),
            data: [
                'template_uuid' => $data->template_uuid,
                'create_now' => true,
                'layers' => $data->layers,
            ]);

        return $response->json('image_url');
    }

    private function callPlacidAPI(string $url, array $data = [], $type = 'post'): Response
    {
        $response = Http::withToken(config('placid.api_key'))
            ->timeout(0)
            ->{$type}($url, $data);

        if ($response->failed()) {
            throw new ConnectionException('Failed to generate ' . $response->body());
        }

        return $response;
    }

    public function pdfGeneration(ImageGenerationData $data): int
    {
        $response = $this->callPlacidAPI(
            url: config('placid.pdf_link'),
            data: [
                'pages' => [
                    [
                        'template_uuid' => $data->template_uuid,
                        'layers' => $data->layers,
                    ],
                ],
            ]);

        return $response->json();
    }

    public function retrieveTemplate(RetrieveTemplateData $data): array
    {
        $response = $this->callPlacidAPI(
            url: config('placid.retrieve_template_link') . $data->template_uuid,
            type: 'get'
        );

        return $response->json();
    }

    public function retrievePdf(RetrievePdfData $data): array
    {
        $response = $this->callPlacidAPI(
            url: config('placid.pdf_link') . $data->pdf_id,
            type: 'get'
        );

        return $response->json();
    }

    public function videoGeneration(VideoGenerationData $data)
    {
        $response = $this->callPlacidAPI(
            url: config('placid.video_link'),
            data: [
                'clips' => $data->clips,
            ]);

        return $response->json();
    }

    public function retrieveVideo(RetrieveVideoData $data)
    {
        $response = $this->callPlacidAPI(
            url: config('placid.retrieve_video_link') . $data->video_id,
            type: 'get'
        );

        return $response->json();
    }
}
