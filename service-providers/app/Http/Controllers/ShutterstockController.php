<?php

namespace App\Http\Controllers;

use App\Data\Request\Shutterstock\AddToCollectionData;
use App\Data\Request\Shutterstock\CreateCollectionData;
use App\Data\Request\Shutterstock\DownloadImageData;
use App\Data\Request\Shutterstock\DownloadVideoData;
use App\Data\Request\Shutterstock\GetImageData;
use App\Data\Request\Shutterstock\GetVideoData;
use App\Data\Request\Shutterstock\LicenseImageData;
use App\Data\Request\Shutterstock\LicenseVideoData;
use App\Data\Request\Shutterstock\SearchImagesData;
use App\Data\Request\Shutterstock\SearchVideosData;
use App\Data\Request\Shutterstock\SearchAudioData;
use App\Data\Request\Shutterstock\GetAudioData;
use App\Data\Request\Shutterstock\DownloadAudioData;
use App\Http\Requests\Shutterstock\AddToCollectionRequest;
use App\Http\Requests\Shutterstock\CreateCollectionRequest;
use App\Http\Requests\Shutterstock\DownloadImageRequest;
use App\Http\Requests\Shutterstock\DownloadVideoRequest;
use App\Http\Requests\Shutterstock\GetImageRequest;
use App\Http\Requests\Shutterstock\GetVideoRequest;
use App\Http\Requests\Shutterstock\LicenseImageRequest;
use App\Http\Requests\Shutterstock\LicenseVideoRequest;
use App\Http\Requests\Shutterstock\SearchImagesRequest;
use App\Http\Requests\Shutterstock\SearchVideosRequest;
use App\Http\Requests\Shutterstock\SearchAudioRequest;
use App\Http\Requests\Shutterstock\GetAudioRequest;
use App\Http\Requests\Shutterstock\DownloadAudioRequest;
use App\Services\ShutterstockService;
use Illuminate\Http\JsonResponse;

class ShutterstockController extends BaseController
{
    public function __construct(protected ShutterstockService $service) {}

    public function createCollection(CreateCollectionRequest $request): JsonResponse
    {
        $data = CreateCollectionData::from($request->validated());

        $result = $this->service->createCollection($data);

        return $this->logAndResponse([
            'message' => 'Collection created successfully.',
            'data' => $result,
        ]);
    }

    public function addToCollection(AddToCollectionRequest $request): JsonResponse
    {
        $data = AddToCollectionData::from($request->validated());

        $this->service->addToCollection($data);

        return $this->logAndResponse([
            'message' => 'Item added to collection successfully.',
        ]);
    }

    public function searchImages(SearchImagesRequest $request): JsonResponse
    {
        $data = SearchImagesData::from($request->validated());

        $result = $this->service->searchImages($data);

        return $this->logAndResponse([
            'message' => 'Images search successful.',
            'data' => $result,
        ]);
    }

    public function getImage(GetImageRequest $request): JsonResponse
    {
        $data = GetImageData::from($request->validated());

        $result = $this->service->getImage($data);

        return $this->logAndResponse([
            'message' => 'Image details retrieved successfully.',
            'data' => $result,
        ]);
    }

    public function licenseImage(LicenseImageRequest $request): JsonResponse
    {
        $data = LicenseImageData::from($request->validated());

        $result = $this->service->licenseImage($data);

        return $this->logAndResponse([
            'message' => 'Image licensed successfully.',
            'data' => $result,
        ]);
    }

    public function downloadImage(DownloadImageRequest $request): JsonResponse
    {
        $data = DownloadImageData::from($request->validated());

        $result = $this->service->downloadImage($data);

        return $this->logAndResponse([
            'message' => 'Download link generated successfully.',
            'data' => $result,
        ]);
    }

    public function searchVideos(SearchVideosRequest $request): JsonResponse
    {
        $data = SearchVideosData::from($request->validated());
        $result = $this->service->searchVideos($data);

        return $this->logAndResponse([
            'message' => 'Videos search successful.',
            'data' => $result,
        ]);
    }

    public function getVideo(GetVideoRequest $request): JsonResponse
    {
        $data = GetVideoData::from($request->validated());
        $result = $this->service->getVideo($data);

        return $this->logAndResponse([
            'message' => 'Video details retrieved successfully.',
            'data' => $result,
        ]);
    }

    public function licenseVideo(LicenseVideoRequest $request): JsonResponse
    {
        $data = LicenseVideoData::from($request->validated());
        $result = $this->service->licenseVideo($data);

        return $this->logAndResponse([
            'message' => 'Videos licensed successfully.',
            'data' => $result,
        ]);
    }

    public function downloadVideo(DownloadVideoRequest $request): JsonResponse
    {
        $data = DownloadVideoData::from($request->validated());
        $result = $this->service->downloadVideo($data);

        return $this->logAndResponse([
            'message' => 'Download link generated successfully.',
            'data' => $result,
        ]);
    }

    public function searchAudio(SearchAudioRequest $request): JsonResponse
    {
        $data = SearchAudioData::from($request->validated());
        $result = $this->service->searchAudio($data);

        return $this->logAndResponse([
            'message' => 'Audio search successful.',
            'data' => $result,
        ]);
    }

    public function getAudio(GetAudioRequest $request): JsonResponse
    {
        $data = GetAudioData::from($request->validated());
        $result = $this->service->getAudio($data);

        return $this->logAndResponse([
            'message' => 'Audio track details retrieved successfully.',
            'data' => $result,
        ]);
    }

    public function downloadAudio(DownloadAudioRequest $request): JsonResponse
    {
        $data = DownloadAudioData::from($request->validated());
        $result = $this->service->downloadAudio($data);

        return $this->logAndResponse([
            'message' => 'Download link generated successfully.',
            'data' => $result,
        ]);
    }

    public function listUserSubscriptions(): JsonResponse
    {
        $result = $this->service->listUserSubscriptions();

        return $this->logAndResponse([
            'message' => 'User subscriptions retrieved successfully.',
            'data' => $result,
        ]);
    }
} 