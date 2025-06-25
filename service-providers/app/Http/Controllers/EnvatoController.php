<?php

namespace App\Http\Controllers;

use App\Data\Request\Envato\ItemSearchData;
use App\Http\Requests\Envato\ItemSearchRequest;
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
}
