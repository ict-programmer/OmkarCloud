<?php

namespace Database\Seeders;

use App\Http\Requests\Envato\ItemDetailsRequest;
use App\Http\Requests\Envato\ItemSearchRequest;
use App\Http\Requests\Envato\UserAccountDetailsRequest;
use App\Models\ServiceProvider;
use App\Models\ServiceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnvatoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceProviderId = ServiceProvider::query()->create([
            'name' => 'Envato',
            'parameter' => [
                'api_url' => 'https://api.evanto.com/v1'
            ],
            'controller_name' => 'App\Http\Controllers\EnvatoController',
        ]);

        $itemSearch = ServiceType::query()->create([
            'name' => 'Item Search',
            'function_name' => 'itemSearch',
            'request_class_name' => ItemSearchRequest::class,
        ]);

        $itemDetails = ServiceType::query()->create([
            'name' => 'Item Details',
            'function_name' => 'itemDetails',
            'request_class_name' => ItemDetailsRequest::class,
        ]);

        $userAccountDetails = ServiceType::query()->create([
            'name' => 'User Account Details',
            'function_name' => 'userAccountDetails',
            'request_class_name' => UserAccountDetailsRequest::class,
        ]);
    }
}
