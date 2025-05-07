<?php

namespace App\Services;

use App\Data\Request\Asset\CreateAssetsData;
use App\Data\Request\Asset\DeleteAssetsData;
use App\Data\Request\Asset\ListAssetsData;
use App\Models\Asset;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AssetService
{
    /**
     * Get all suppliers with pagination and sorting.
     *
     * @param ListAssetsData $data
     * @return LengthAwarePaginator
     */
    public function listAssets(ListAssetsData $data): LengthAwarePaginator
    {
        return Asset::query()
            ->orderBy($data->sort_by, $data->sort_order)
            ->paginate($data->page_size, ['*'], 'page', $data->page_limit);
    }

    /**
     * Create a new asset.
     *
     * @param CreateAssetsData $data
     * @return Asset
     */
    public function createAsset(CreateAssetsData $data): Asset
    {
        return Asset::create([
            'name' => $data->name,
            'status' => $data->status,
        ]);
    }

    /**
     * Delete asset
     *
     * @param DeleteAssetsData $data
     * @return void
     */
    public function deleteAsset(DeleteAssetsData $data): void
    {
        $asset = Asset::query()->findOrFail($data->id);
        $asset->delete();
    }
}