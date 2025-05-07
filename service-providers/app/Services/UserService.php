<?php

namespace App\Services;

use App\Data\Request\User\CreateUsersData;
use App\Data\Request\User\DeleteUsersData;
use App\Data\Request\User\ListUsersData;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService
{
    /**
     * Get all suppliers with pagination and sorting.
     *
     * @param ListUsersData $data
     * @return LengthAwarePaginator
     */
    public function listUsers(ListUsersData $data): LengthAwarePaginator
    {
        return User::query()
            ->orderBy($data->sort_by, $data->sort_order)
            ->paginate($data->page_size, ['*'], 'page', $data->page_limit);
    }

    /**
     * Create a new asset.
     *
     * @param CreateUsersData $data
     * @return User
     */
    public function createUser(CreateUsersData $data): User
    {
        return User::create($data->toArray());
    }

    /**
     * Delete asset
     *
     * @param DeleteUsersData $data
     * @return void
     */
    public function deleteUser(DeleteUsersData $data): void
    {
        $asset = User::query()->findOrFail($data->id);
        $asset->delete();
    }
}