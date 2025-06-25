<?php

namespace Database\Seeders;

use App\Http\Requests\Envato\CategoriesBySiteRequest;
use App\Http\Requests\Envato\DownloadPurchasedItemRequest;
use App\Http\Requests\Envato\ItemDetailsRequest;
use App\Http\Requests\Envato\ItemSearchRequest;
use App\Http\Requests\Envato\PopularItemsRequest;
use App\Http\Requests\Envato\UserAccountDetailsRequest;
use App\Http\Requests\Envato\VerifyPurchaseCodeRequest;
use App\Models\ServiceProvider;
use App\Models\ServiceProviderType;
use App\Models\ServiceType;
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

        $userPurchases = ServiceType::query()->create([
            'name' => 'User Purchases',
            'function_name' => 'userPurchases',
        ]);

        $downloadPurchasedItem = ServiceType::query()->create([
            'name' => 'Download Purchased Item',
            'function_name' => 'downloadPurchasedItem',
            'request_class_name' => DownloadPurchasedItemRequest::class,
        ]);

        $verifyPurchaseCode = ServiceType::query()->create([
            'name' => 'Verify Purchase Code',
            'function_name' => 'verifyPurchaseCode',
            'request_class_name' => VerifyPurchaseCodeRequest::class,
        ]);

        $userIdentity = ServiceType::query()->create([
            'name' => 'User Identity',
            'function_name' => 'userIdentity',
        ]);

        $popularItems = ServiceType::query()->create([
            'name' => 'Popular Items',
            'function_name' => 'popularItems',
            'request_class_name' => PopularItemsRequest::class,
        ]);

        $categoriesBySite = ServiceType::query()->create([
            'name' => 'Categories By Site',
            'function_name' => 'categoriesBySite',
            'request_class_name' => CategoriesBySiteRequest::class,
        ]);

        ServiceProviderType::query()->create([
            'service_type_id' => $itemSearch->id,
            'service_provider_id' => $serviceProviderId->id,
            'parameter' => [
                'site' => 'themeforest.net',
                'term' => 'portfolio',
            ],
        ]);

        ServiceProviderType::query()->create([
            'service_type_id' => $itemDetails->id,
            'service_provider_id' => $serviceProviderId->id,
            'parameter' => [
                'item_id' => 123456,
            ],
        ]);

        ServiceProviderType::query()->create([
            'service_type_id' => $userAccountDetails->id,
            'service_provider_id' => $serviceProviderId->id,
        ]);

        ServiceProviderType::query()->create([
            'service_type_id' => $userPurchases->id,
            'service_provider_id' => $serviceProviderId->id,
        ]);

        ServiceProviderType::query()->create([
            'service_type_id' => $downloadPurchasedItem->id,
            'service_provider_id' => $serviceProviderId->id,
            'parameter' => [
                'item_id' => 123456,
            ],
        ]);

        ServiceProviderType::query()->create([
            'service_type_id' => $verifyPurchaseCode->id,
            'service_provider_id' => $serviceProviderId->id,
            'parameter' => [
                'purchase_code' => 'abcd-1234-efgh-5678',
            ],
        ]);

        ServiceProviderType::query()->create([
            'service_type_id' => $userIdentity->id,
            'service_provider_id' => $serviceProviderId->id,
        ]);

        ServiceProviderType::query()->create([
            'service_type_id' => $popularItems->id,
            'service_provider_id' => $serviceProviderId->id,
            'parameter' => [
                'site' => 'themeforest.net',
            ],
        ]);

        ServiceProviderType::query()->create([
            'service_type_id' => $categoriesBySite->id,
            'service_provider_id' => $serviceProviderId->id,
            'parameter' => [
                'site' => 'themeforest.net',
            ],
        ]);
    }
}
