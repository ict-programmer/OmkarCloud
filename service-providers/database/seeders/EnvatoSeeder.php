<?php

namespace Database\Seeders;

use App\Http\Controllers\EnvatoController;
use App\Http\Requests\Envato\CategoriesBySiteRequest;
use App\Http\Requests\Envato\DownloadPurchasedItemRequest;
use App\Http\Requests\Envato\ItemDetailsRequest;
use App\Http\Requests\Envato\ItemSearchRequest;
use App\Http\Requests\Envato\PopularItemsRequest;
use App\Http\Requests\Envato\UserAccountDetailsRequest;
use App\Http\Requests\Envato\VerifyPurchaseCodeRequest;
use App\Models\ServiceProvider;
use App\Traits\ServiceProviderSeederTrait;
use Illuminate\Database\Seeder;

class EnvatoSeeder extends Seeder
{
    use ServiceProviderSeederTrait;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => 'Envato'],
            [
                'parameters' => [
                    'api_key' => 'YOUR_API_TOKEN',
                    'base_url' => 'https://api.envato.com',
                    'version' => 'v1',
                    'sites_supported' => [
                        'themeforest',
                        'codecanyon',
                        'videohive',
                        'audiojungle',
                        'graphicriver',
                        'photodune',
                        '3docean',
                    ],
                    'features' => [
                        'item_search',
                        'item_details',
                        'user_account_details',
                        'user_purchases',
                        'download_purchased_item',
                        'verify_purchase_code',
                        'user_identity',
                        'popular_items',
                        'categories',
                    ],
                ],
                'is_active' => true,
                'controller_name' => EnvatoController::class,
            ]
        );

        $serviceTypes = [
            [
                'name' => 'Item Search',
                'input_parameters' => [
                    'site' => [
                        'type' => 'string',
                        'required' => true,
                        'options' => [
                            'themeforest',
                            'codecanyon',
                            'videohive',
                            'audiojungle',
                            'graphicriver',
                            'photodune',
                            '3docean',
                        ],
                        'description' => 'Envato marketplace site to search',
                    ],
                    'term' => [
                        'type' => 'string', 
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 100,
                        'description' => 'Search term for items',
                    ],
                    'category' => [
                        'type' => 'string',
                        'required' => false,
                        'description' => 'Category to filter results',
                    ],
                    'page_size' => [
                        'type' => 'integer',
                        'required' => false,
                        'default' => 10,
                        'min' => 1,
                        'max' => 100,
                        'description' => 'Number of results per page',
                    ],
                ],
                'response' => [
                    'matches' => [
                        [
                            'id' => 12345,
                            'item' => 'Premium Portfolio Theme',
                            'url' => 'https://themeforest.net/item/premium-portfolio-theme/12345',
                            'user' => 'author_name',
                            'thumbnail' => 'https://previews.customer.envatousercontent.com/files/preview.jpg',
                            'tags' => ['portfolio', 'responsive', 'bootstrap'],
                            'category' => 'Site Templates/Corporate',
                            'live_preview_url' => 'https://themeforest.net/item/full_screen_preview/12345',
                            'rating' => [
                                'rating' => 4.5,
                                'count' => 150,
                            ],
                            'price_cents' => 5900,
                            'number_of_sales' => 2500,
                            'updated_at' => '2024-01-15T10:30:00Z',
                            'published_at' => '2023-06-01T12:00:00Z',
                        ],
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.matches',
                ],
                'request_class_name' => ItemSearchRequest::class,
                'function_name' => 'itemSearch',
            ],
            [
                'name' => 'Item Details',
                'input_parameters' => [
                    'item_id' => [
                        'type' => 'integer',
                        'required' => true,
                        'min' => 1,
                        'description' => 'Envato item ID',
                    ],
                ],
                'response' => [
                    'id' => 12345,
                    'item' => 'Premium Portfolio Theme',
                    'description' => 'A modern, responsive portfolio theme perfect for creative professionals.',
                    'site' => 'themeforest',
                    'classification' => 'Site Templates/Portfolio',
                    'price_cents' => 5900,
                    'number_of_sales' => 2500,
                    'author_username' => 'author_name',
                    'author_url' => 'https://themeforest.net/user/author_name',
                    'author_image' => 'https://0.s3.envato.com/files/author_image.jpg',
                    'summary' => 'Modern portfolio theme with 5 homepage layouts',
                    'rating' => [
                        'rating' => 4.5,
                        'count' => 150,
                    ],
                    'updated_at' => '2024-01-15T10:30:00Z',
                    'published_at' => '2023-06-01T12:00:00Z',
                    'trendy' => false,
                    'featured' => true,
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => ItemDetailsRequest::class,
                'function_name' => 'itemDetails',
            ],
            [
                'name' => 'User Account Details',
                'input_parameters' => [
                    'username' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 50,
                        'description' => 'Envato username',
                    ],
                ],
                'response' => [
                    'user' => [
                        'username' => 'john_doe',
                        'firstname' => 'John',
                        'surname' => 'Doe',
                        'image' => 'https://0.s3.envato.com/files/user_image.jpg',
                        'homepage' => 'https://johndoe.com',
                        'location' => 'San Francisco, CA',
                        'sales' => 125,
                        'followers' => 1500,
                        'country' => 'United States',
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.user',
                ],
                'request_class_name' => UserAccountDetailsRequest::class,
                'function_name' => 'userAccountDetails',
            ],
            [
                'name' => 'User Purchases',
                'input_parameters' => [],
                'response' => [
                    'results' => [
                        [
                            'item' => [
                                'id' => 12345,
                                'name' => 'Premium Portfolio Theme',
                                'author_username' => 'author_name',
                                'url' => 'https://themeforest.net/item/premium-portfolio-theme/12345',
                                'thumbnail_url' => 'https://previews.customer.envatousercontent.com/files/preview.jpg',
                                'site' => 'themeforest',
                                'price_cents' => 5900,
                            ],
                            'purchase_count' => 1,
                            'purchased_at' => '2024-01-10T14:30:00Z',
                            'supported_until' => '2024-07-10T14:30:00Z',
                            'license' => 'regular',
                            'download_id' => 'abc123def456',
                        ],
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.results',
                ],
                'request_class_name' => null,
                'function_name' => 'userPurchases',
            ],
            [
                'name' => 'Download Purchased Item',
                'input_parameters' => [
                    'item_id' => [
                        'type' => 'integer',
                        'required' => true,
                        'min' => 1,
                        'description' => 'Purchased item ID',
                    ],
                ],
                'response' => [
                    'download_url' => 'https://api.envato.com/v1/market/private/user/download/item.zip?token=abc123',
                    'wordpress_plugin' => false,
                    'wordpress_theme' => true,
                    'item' => [
                        'id' => 12345,
                        'name' => 'Premium Portfolio Theme',
                        'author_username' => 'author_name',
                    ],
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => DownloadPurchasedItemRequest::class,
                'function_name' => 'downloadPurchasedItem',
            ],
            [
                'name' => 'Verify Purchase Code',
                'input_parameters' => [
                    'purchase_code' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 36,
                        'max_length' => 36,
                        'pattern' => '^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$',
                        'description' => 'Purchase code in UUID format',
                    ],
                ],
                'response' => [
                    'item' => [
                        'id' => 12345,
                        'name' => 'Premium Portfolio Theme',
                        'description' => 'A modern, responsive portfolio theme',
                        'site' => 'themeforest',
                        'classification' => 'Site Templates/Portfolio',
                        'price_cents' => 5900,
                        'author_username' => 'author_name',
                    ],
                    'buyer' => 'john_doe',
                    'purchase_count' => 1,
                    'purchased_at' => '2024-01-10T14:30:00Z',
                    'supported_until' => '2024-07-10T14:30:00Z',
                    'license' => 'regular',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => VerifyPurchaseCodeRequest::class,
                'function_name' => 'verifyPurchaseCode',
            ],
            [
                'name' => 'User Identity',
                'input_parameters' => [],
                'response' => [
                    'username' => 'john_doe',
                    'email' => 'john@example.com',
                    'firstname' => 'John',
                    'surname' => 'Doe',
                    'image' => 'https://0.s3.envato.com/files/user_image.jpg',
                    'country' => 'United States',
                ],
                'response_path' => [
                    'final_result' => '$',
                ],
                'request_class_name' => null,
                'function_name' => 'userIdentity',
            ],
            [
                'name' => 'Popular Items',
                'input_parameters' => [
                    'site' => [
                        'type' => 'string',
                        'required' => true,
                        'options' => [
                            'themeforest',
                            'codecanyon',
                            'videohive',
                            'audiojungle',
                            'graphicriver',
                            'photodune',
                            '3docean',
                        ],
                        'description' => 'Envato marketplace site',
                    ],
                ],
                'response' => [
                    'popular' => [
                        'items_last_three_months' => [
                            [
                                'id' => 12345,
                                'item' => 'Premium Portfolio Theme',
                                'url' => 'https://themeforest.net/item/premium-portfolio-theme/12345',
                                'user' => 'author_name',
                                'thumbnail' => 'https://previews.customer.envatousercontent.com/files/preview.jpg',
                                'category' => 'Site Templates/Portfolio',
                                'price_cents' => 5900,
                                'rating' => [
                                    'rating' => 4.5,
                                    'count' => 150,
                                ],
                                'sales' => 2500,
                                'published_at' => '2023-06-01T12:00:00Z',
                            ],
                        ],
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.popular.items_last_three_months',
                ],
                'request_class_name' => PopularItemsRequest::class,
                'function_name' => 'popularItems',
            ],
            [
                'name' => 'Categories By Site',
                'input_parameters' => [
                    'site' => [
                        'type' => 'string',
                        'required' => true,
                        'options' => [
                            'themeforest',
                            'codecanyon',
                            'videohive',
                            'audiojungle',
                            'graphicriver',
                            'photodune',
                            '3docean',
                        ],
                        'description' => 'Envato marketplace site',
                    ],
                ],
                'response' => [
                    'categories' => [
                        [
                            'name' => 'Site Templates',
                            'path' => 'site-templates',
                            'matches' => 45000,
                            'children' => [
                                [
                                    'name' => 'Corporate',
                                    'path' => 'site-templates/corporate',
                                    'matches' => 8500,
                                ],
                                [
                                    'name' => 'Portfolio',
                                    'path' => 'site-templates/portfolio', 
                                    'matches' => 3200,
                                ],
                                [
                                    'name' => 'eCommerce',
                                    'path' => 'site-templates/ecommerce',
                                    'matches' => 12000,
                                ],
                            ],
                        ],
                        [
                            'name' => 'WordPress',
                            'path' => 'wordpress',
                            'matches' => 15000,
                            'children' => [
                                [
                                    'name' => 'Corporate',
                                    'path' => 'wordpress/corporate',
                                    'matches' => 4500,
                                ],
                                [
                                    'name' => 'Blog / Magazine',
                                    'path' => 'wordpress/blog-magazine',
                                    'matches' => 2800,
                                ],
                            ],
                        ],
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.categories',
                ],
                'request_class_name' => CategoriesBySiteRequest::class,
                'function_name' => 'categoriesBySite',
            ],
        ];

        $keptServiceTypeIds = $this->processServiceTypes($serviceProvider, $serviceTypes, 'Envato');

        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);

        $this->command->info('Cleanup completed:');
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info('- Kept ' . count($keptServiceTypeIds) . ' service types for Envato');
    }
}
