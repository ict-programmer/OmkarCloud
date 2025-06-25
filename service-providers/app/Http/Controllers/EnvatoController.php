<?php

namespace App\Http\Controllers;

use App\Data\Request\Envato\ItemSearchData;
use App\Data\Request\Envato\ItemDetailsData;
use App\Http\Requests\Envato\ItemSearchRequest;
use App\Http\Requests\Envato\ItemDetailsRequest;
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
}
