<?php

namespace App\Http\Controllers;

use App\Data\Request\Envato\ItemSearchData;
use App\Data\Request\Envato\ItemDetailsData;
use App\Data\Request\Envato\UserAccountDetailsData;
use App\Data\Request\Envato\DownloadPurchasedItemData;
use App\Data\Request\Envato\VerifyPurchaseCodeData;
use App\Data\Request\Envato\PopularItemsData;
use App\Data\Request\Envato\CategoriesBySiteData;
use App\Http\Requests\Envato\ItemSearchRequest;
use App\Http\Requests\Envato\ItemDetailsRequest;
use App\Http\Requests\Envato\UserAccountDetailsRequest;
use App\Http\Requests\Envato\DownloadPurchasedItemRequest;
use App\Http\Requests\Envato\VerifyPurchaseCodeRequest;
use App\Http\Requests\Envato\PopularItemsRequest;
use App\Http\Requests\Envato\CategoriesBySiteRequest;
use App\Services\EnvatoService;
use Illuminate\Http\JsonResponse;

class EnvatoController extends BaseController
{
    public function __construct(protected EnvatoService $service) {}

    public function itemSearch(ItemSearchRequest $request): JsonResponse
    {
        $data = ItemSearchData::from($request->validated());

        $result = $this->service->itemSearch($data);

        return $this->logAndResponse([
            'message' => 'Item search successful.',
            'data' => $result,
        ]);
    }

    public function itemDetails(ItemDetailsRequest $request): JsonResponse
    {
        $data = ItemDetailsData::from($request->validated());

        $result = $this->service->itemDetails($data);

        return $this->logAndResponse([
            'message' => 'Item details retrieved successfully.',
            'data' => $result,
        ]);
    }

    public function userAccountDetails(UserAccountDetailsRequest $request): JsonResponse
    {
        $data = UserAccountDetailsData::from($request->validated());

        $result = $this->service->userAccountDetails($data);

        return $this->logAndResponse([
            'message' => 'User account details retrieved successfully.',
            'data' => $result,
        ]);
    }

    public function userPurchases(): JsonResponse
    {
        $result = $this->service->userPurchases();

        return $this->logAndResponse([
            'message' => 'User purchases retrieved successfully.',
            'data' => $result,
        ]);
    }

    public function downloadPurchasedItem(DownloadPurchasedItemRequest $request): JsonResponse
    {
        $data = DownloadPurchasedItemData::from($request->validated());

        $result = $this->service->downloadPurchasedItem($data);

        return $this->logAndResponse([
            'message' => 'Download link generated successfully.',
            'data' => $result,
        ]);
    }

    public function verifyPurchaseCode(VerifyPurchaseCodeRequest $request): JsonResponse
    {
        $data = VerifyPurchaseCodeData::from($request->validated());

        $result = $this->service->verifyPurchaseCode($data);

        return $this->logAndResponse([
            'message' => 'Purchase code verified successfully.',
            'data' => $result,
        ]);
    }

    public function userIdentity(): JsonResponse
    {
        $result = $this->service->userIdentity();

        return $this->logAndResponse([
            'message' => 'User identity retrieved successfully.',
            'data' => $result,
        ]);
    }

    public function popularItems(PopularItemsRequest $request): JsonResponse
    {
        $data = PopularItemsData::from($request->validated());

        $result = $this->service->popularItems($data);

        return $this->logAndResponse([
            'message' => 'Popular items retrieved successfully.',
            'data' => $result,
        ]);
    }

    public function categoriesBySite(CategoriesBySiteRequest $request): JsonResponse
    {
        $data = CategoriesBySiteData::from($request->validated());

        $result = $this->service->categoriesBySite($data);

        return $this->logAndResponse([
            'message' => 'Categories retrieved successfully.',
            'data' => $result,
        ]);
    }
}
