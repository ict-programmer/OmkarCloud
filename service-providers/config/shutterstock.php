<?php

return [
    'base_url' => 'https://api.shutterstock.com',
    'search_images_endpoint' => '/v2/images/search',
    'get_image_endpoint' => '/v2/images',
    'license_image_endpoint' => '/v2/images/licenses',
    'download_image_endpoint' => '/v2/images/licenses',
    'create_collection_endpoint' => '/v2/images/collections',
    'api_token' => env('SHUTTERSTOCK_API_TOKEN'),
]; 