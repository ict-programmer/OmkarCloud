<?php

return [
    'base_url' => 'https://api.shutterstock.com/v2',
    'search_images_endpoint' => '/images/search',
    'get_image_endpoint' => '/images',
    'license_image_endpoint' => '/images/licenses',
    'api_token' => env('SHUTTERSTOCK_API_TOKEN'),
]; 