<?php

return [
    'base_url' => 'https://api.shutterstock.com',
    'search_images_endpoint' => '/v2/images/search',
    'get_image_endpoint' => '/v2/images',
    'license_image_endpoint' => '/v2/images/licenses',
    'download_image_endpoint' => '/v2/images/licenses',
    'create_collection_endpoint' => '/v2/images/collections',
    'add_to_collection_endpoint' => '/v2/images/collections',
    'search_videos_endpoint' => '/v2/videos/search',
    'get_video_endpoint' => '/v2/videos',
    'license_video_endpoint' => '/v2/videos/licenses',
    'download_video_endpoint' => '/v2/videos/licenses',
    'list_user_subscriptions_endpoint' => '/v2/user/subscriptions',
    'api_token' => env('SHUTTERSTOCK_API_TOKEN'),
]; 