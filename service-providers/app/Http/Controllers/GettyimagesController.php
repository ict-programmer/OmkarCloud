<?php

namespace App\Http\Controllers;

use App\Data\GettyImages\AffiliateImageSearchData;
use App\Data\GettyImages\AffiliateVideoSearchData;
use App\Data\GettyImages\ImageSearchData;
use App\Data\GettyImages\VideoSearchData;
use App\Http\Requests\GettyImages\AffiliateImageSearchRequest;
use App\Http\Requests\GettyImages\AffiliateVideoSearchRequest;
use App\Http\Requests\GettyImages\ImageSearchRequest;
use App\Http\Requests\GettyImages\VideoSearchRequest;
use App\Services\GettyimagesService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class GettyimagesController extends BaseController
{
    public function __construct(protected GettyimagesService $service) {}

    #[OA\Get(
        path: '/api/gettyimages/image_search',
        summary: 'Search images',
        description: 'Retrieve metadata for a specific image.',
        tags: ["Getty Images"],
    )]
    #[OA\Parameter(
        name: 'phrase',
        in: 'query',
        required: false,
        description: 'Search phrase (nullable, string)',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'sort_order',
        in: 'query',
        required: false,
        description: 'Sort order (nullable, string, allowed: best_match, most_popular, newest, random)',
        schema: new OA\Schema(type: 'string', enum: ['best_match', 'most_popular', 'newest', 'random'])
    )]
    #[OA\Parameter(
        name: 'fields',
        in: 'query',
        required: false,
        description: 'Optional fields to include (array)',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string'))
    )]
    #[OA\Parameter(
        name: 'fields.language',
        in: 'query',
        required: false,
        description: 'Language for the fields (nullable, string)',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'fields.countryCode',
        in: 'query',
        required: false,
        description: 'Country code (nullable, string)',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'fields.age_of_people',
        in: 'query',
        required: false,
        description: 'Age of people (nullable, array)',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(type: 'string', enum: [
                'newborn','baby','child','teenager','young_adult','adult','adults_only','mature_adult','senior_adult',
                '0-1_months','2-5_months','6-11_months','12-17_months','18-19_years','20-24_years','20-29_years','25-29_years',
                '30-34_years','30-39_years','35-39_years','40-44_years','40-49_years','45-49_years','50-54_years','50-59_years',
                '55-59_years','60-64_years','60-69_years','65-69_years','70-79_years','80-89_years','90_plus_years','100_over'
            ])
        )
    )]
    #[OA\Parameter(
        name: 'fields.artists',
        in: 'query',
        required: false,
        description: 'Artists (nullable, string)',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'fields.collection_codes',
        in: 'query',
        required: false,
        description: 'Collection codes (nullable, array of string)',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string'))
    )]
    #[OA\Parameter(
        name: 'fields.collections_filter_type',
        in: 'query',
        required: false,
        description: 'Collections filter type (nullable, string, allowed: exclude, include)',
        schema: new OA\Schema(type: 'string', enum: ['exclude', 'include'])
    )]
    #[OA\Parameter(
        name: 'fields.color',
        in: 'query',
        required: false,
        description: 'Color (nullable, string, size:6)',
        schema: new OA\Schema(type: 'string', minLength: 6, maxLength: 6)
    )]
    #[OA\Parameter(
        name: 'fields.compositions',
        in: 'query',
        required: false,
        description: 'Compositions (nullable, array)',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(type: 'string', enum: [
                'abstract','candid','close_up','copy_space','cut_out','full_frame','full_length','headshot','looking_at_camera',
                'macro','medium_shot','part_of_a_series','portrait','sparse','still_life','three_quarter_length','waist_up','wide_shot'
            ])
        )
    )]
    #[OA\Parameter(
        name: 'fields.download_product',
        in: 'query',
        required: false,
        description: 'Download product (nullable, string)',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'fields.embed_content_only',
        in: 'query',
        required: false,
        description: 'Embed content only (nullable, boolean)',
        schema: new OA\Schema(type: 'boolean')
    )]
    #[OA\Parameter(
        name: 'fields.event_ids',
        in: 'query',
        required: false,
        description: 'Event IDs (nullable, array of integer)',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'))
    )]
    #[OA\Parameter(
        name: 'fields.ethnicity',
        in: 'query',
        required: false,
        description: 'Ethnicity (nullable, array)',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(type: 'string', enum: [
                'black','white','east_asian','hispanic_latinx','japanese','middle_eastern','multiracial_person','multiethnic_group',
                'native_american_first_nations','pacific_islander','south_asian','southeast_asian'
            ])
        )
    )]
    #[OA\Parameter(
        name: 'fields.exclude_nudity',
        in: 'query',
        required: false,
        description: 'Exclude nudity (nullable, boolean)',
        schema: new OA\Schema(type: 'boolean')
    )]
    #[OA\Parameter(
        name: 'fields.fields',
        in: 'query',
        required: false,
        description: 'Fields (nullable, array)',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(type: 'string', enum: [
                'accessories','allowed_use','alternative_ids','artist','asset_family','call_for_image','caption','collection_code',
                'collection_id','collection_name','color_type','comp','comp_webp','copyright','date_camera_shot','date_created',
                'date_submitted','detail_set','display_set','download_product','download_sizes','editorial_segments','editorial_source',
                'event_ids','graphical_style','idistock_collection','keywords','largest_downloads','license_model','max_dimensions',
                'orientation','people','preview','product_types','quality_rank','referral_destinations','summary_set','thumb','title',
                'uri_ormbed'
            ])
        )
    )]
    #[OA\Parameter(
        name: 'fields.file_types',
        in: 'query',
        required: false,
        description: 'File types (nullable, array, allowed: eps)',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['eps']))
    )]
    #[OA\Parameter(
        name: 'fields.graphical_styles',
        in: 'query',
        required: false,
        description: 'Graphical styles (nullable, array)',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(type: 'string', enum: ['fine_art','illustration','photograph','vector'])
        )
    )]
    #[OA\Parameter(
        name: 'fields.graphical_style_filter_type',
        in: 'query',
        required: false,
        description: 'Graphical style filter type (nullable, string, allowed: exclude, include)',
        schema: new OA\Schema(type: 'string', enum: ['exclude', 'include'])
    )]
    #[OA\Parameter(
        name: 'fields.include_related_searches',
        in: 'query',
        required: false,
        description: 'Include related searches (nullable, boolean)',
        schema: new OA\Schema(type: 'boolean')
    )]
    #[OA\Parameter(
        name: 'fields.keyword_ids',
        in: 'query',
        required: false,
        description: 'Keyword IDs (nullable, array of integer)',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'))
    )]
    #[OA\Parameter(
        name: 'fields.minimum_size',
        in: 'query',
        required: false,
        description: 'Minimum size (nullable, string, allowed: x_small,small,medium,large,x_large,xx_large,vector)',
        schema: new OA\Schema(type: 'string', enum: ['x_small','small','medium','large','x_large','xx_large','vector'])
    )]
    #[OA\Parameter(
        name: 'fields.number_of_people',
        in: 'query',
        required: false,
        description: 'Number of people (nullable, array)',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(type: 'string', enum: ['none','one','two','group'])
        )
    )]
    #[OA\Parameter(
        name: 'fields.orientations',
        in: 'query',
        required: false,
        description: 'Orientations (nullable, array)',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(type: 'string', enum: [
                'horizontal','vertical','square','panoramic_horizontal','panoramic_vertical'
            ])
        )
    )]
    #[OA\Parameter(
        name: 'fields.page',
        in: 'query',
        required: false,
        description: 'Page (nullable, integer, min:1)',
        schema: new OA\Schema(type: 'integer', minimum: 1)
    )]
    #[OA\Parameter(
        name: 'fields.page_size',
        in: 'query',
        required: false,
        description: 'Page size (nullable, integer, min:1, max:200)',
        schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 200)
    )]
    #[OA\Parameter(
        name: 'fields.specific_people',
        in: 'query',
        required: false,
        description: 'Specific people (nullable, array of string)',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string'))
    )]
    #[OA\Response(
        response: 200,
        description: 'Getty Images API - Image Search Response',
        content: new OA\JsonContent(
            example: [
                "result_count" => 0,
                "images" => [
                    [
                        "istock_collection" => "string",
                        "editorial_segments" => ["string"],
                        "editorial_source" => [
                            "id" => 0
                        ],
                        "event_ids" => [0],
                        "era" => "string",
                        "people" => ["string"],
                        "allowed_use" => [
                            "how_can_i_use_it" => "string",
                            "release_info" => "string",
                            "usage_restrictions" => ["string"]
                        ],
                        "alternative_ids" => [
                            "additionalProp1" => "string",
                            "additionalProp2" => "string",
                            "additionalProp3" => "string"
                        ],
                        "artist" => "string",
                        "asset_family" => "string",
                        "asset_type" => "string",
                        "call_for_image" => true,
                        "caption" => "string",
                        "collection_code" => "string",
                        "collection_id" => 0,
                        "collection_name" => "string",
                        "color_type" => "string",
                        "contributor" => [
                            "member_name" => "string",
                            "display_name" => "string"
                        ],
                        "copyright" => "string",
                        "date_camera_shot" => "2025-06-20T10:09:48.285Z",
                        "date_created" => "2025-06-20T10:09:48.285Z",
                        "display_sizes" => [
                            [
                                "is_watermarked" => true,
                                "name" => "string",
                                "uri" => "string"
                            ]
                        ],
                        "download_product" => "string",
                        "download_sizes" => [
                            [
                                "bytes" => 0,
                                "downloads" => [
                                    [
                                        "product_id" => "string",
                                        "product_type" => "string",
                                        "uri" => "string",
                                        "agreement_name" => "string"
                                    ]
                                ],
                                "height" => 0,
                                "media_type" => "string",
                                "name" => "x_small",
                                "width" => 0,
                                "dpi" => 0
                            ]
                        ],
                        "graphical_style" => "string",
                        "id" => "string",
                        "keywords" => [
                            [
                                "keyword_id" => "string",
                                "text" => "string",
                                "type" => "string",
                                "relevance" => 0
                            ]
                        ],
                        "largest_downloads" => [
                            [
                                "product_id" => "string",
                                "product_type" => "string",
                                "uri" => "string",
                                "agreement_name" => "string"
                            ]
                        ],
                        "license_model" => "string",
                        "max_dimensions" => [
                            "height" => 0,
                            "width" => 0
                        ],
                        "orientation" => "string",
                        "product_types" => ["string"],
                        "quality_rank" => 0,
                        "referral_destinations" => [
                            [
                                "site_name" => "string",
                                "uri" => "string"
                            ]
                        ],
                        "territory_restrictions" => [
                            [
                                "country_code" => "string",
                                "type" => "string",
                                "description" => "string"
                            ]
                        ],
                        "title" => "string",
                        "uri_oembed" => "string",
                        "date_submitted" => "2025-06-20T10:09:48.285Z"
                    ]
                ],
                "facets" => [
                    "specific_people" => [
                        [
                            "id" => 0,
                            "name" => "string"
                        ]
                    ],
                    "events" => [
                        [
                            "id" => 0,
                            "name" => "string",
                            "date" => "2025-06-20T10:09:48.285Z"
                        ]
                    ],
                    "locations" => [
                        [
                            "id" => 0,
                            "name" => "string"
                        ]
                    ],
                    "artists" => [
                        [
                            "name" => "string"
                        ]
                    ],
                    "entertainment" => [
                        [
                            "id" => 0,
                            "name" => "string"
                        ]
                    ]
                ],
                "auto_corrections" => [
                    "phrase" => "string"
                ],
                "related_searches" => [
                    [
                        "phrase" => "string",
                        "url" => "string"
                    ]
                ]
            ]
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'validation_error',
                'message' => [],
            ],
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while processing your request',
            ]
        )
    )]
    public function imageSearch(ImageSearchRequest $request) : JsonResponse {

        $data = ImageSearchData::from($request->validated());

        $result = $this->service->searchImages($data);

        return $this->logAndResponse($result);
    }

    // public function videoSearch(VideoSearchRequest $request) : JsonResponse {

    //     $data = VideoSearchData::from($request->validated());

    //     $result = $this->service->searchVideos($data);
        
    //     return $this->logAndResponse($result);
    // }

    #[OA\Get(
        path: '/api/gettyimages/image_metadata/{id}',
        summary: 'Get image metadata',
        description: 'Retrieve metadata for a specific image.',
        tags: ["Getty Images"],
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID of the image',
        schema: new OA\Schema(
            type: 'string',
            example: '17f20503-6c24-4c16-946b-35dbbce2af2f',
        ),
    )]
    #[OA\Response(
        response: 200,
        description: 'Getty Images API - Image Search Response',
        content: new OA\JsonContent(
            example: [
                'images' => [
                    [
                        'id' => 'IMG123456789',
                        'allowed_use' => [
                            'how_can_i_use_it' => 'Editorial use only.',
                            'release_info' => 'No property release.',
                            'usage_restrictions' => [
                                'Not for commercial use',
                                'Editorial only'
                            ]
                        ],
                        'alternative_ids' => [
                            'additionalProp1' => 'ALT001',
                            'additionalProp2' => 'ALT002',
                            'additionalProp3' => 'ALT003'
                        ],
                        'artist' => 'Jane Doe',
                        'artist_title' => 'Photographer',
                        'asset_family' => 'creative',
                        'call_for_image' => true,
                        'caption' => 'A bustling market in Kigali, Rwanda.',
                        'city' => 'Kigali',
                        'collection_code' => 'COL123',
                        'collection_id' => 101,
                        'collection_name' => 'Africa Life',
                        'color_type' => 'color',
                        'copyright' => '© 2025 Jane Doe',
                        'country' => 'Rwanda',
                        'credit_line' => 'Getty Images/Jane Doe',
                        'date_camera_shot' => '2025-06-20T08:30:32.179Z',
                        'date_created' => '2025-06-20T08:30:32.179Z',
                        'date_submitted' => '2025-06-20T08:30:32.179Z',
                        'display_sizes' => [
                            [
                                'height' => 300,
                                'width' => 400,
                                'is_watermarked' => true,
                                'name' => 'thumbnail',
                                'uri' => 'https://example.com/thumb.jpg'
                            ]
                        ],
                        'download_product' => 'standard_image',
                        'download_sizes' => [
                            [
                                'bytes' => 204800,
                                'height' => 1200,
                                'width' => 1600,
                                'dpi' => 300,
                                'media_type' => 'image/jpeg',
                                'name' => 'x_small',
                                'downloads' => [
                                    [
                                        'product_id' => 'prod001',
                                        'product_type' => 'image',
                                        'uri' => 'https://example.com/download.jpg',
                                        'agreement_name' => 'Standard License'
                                    ]
                                ]
                            ]
                        ],
                        'editorial_segments' => ['news'],
                        'editorial_source' => [
                            'id' => 9,
                            'name' => 'Getty Editorial'
                        ],
                        'event_ids' => [101, 202],
                        'graphical_style' => 'photography',
                        'is_ai_editable' => true,
                        'keywords' => [
                            [
                                'keyword_id' => 'kw1',
                                'text' => 'market',
                                'type' => 'theme',
                                'relevance' => 90
                            ]
                        ],
                        'largest_downloads' => [
                            [
                                'bytes' => 10485760,
                                'height' => 3000,
                                'width' => 4000,
                                'dpi' => 300,
                                'media_type' => 'image/jpeg',
                                'name' => 'original',
                                'downloads' => [
                                    [
                                        'product_id' => 'prod002',
                                        'product_type' => 'image',
                                        'uri' => 'https://example.com/large.jpg',
                                        'agreement_name' => 'Extended License'
                                    ]
                                ]
                            ]
                        ],
                        'license_model' => 'royalty_free',
                        'links' => [
                            [
                                'rel' => 'self',
                                'uri' => 'https://example.com/api/images/IMG123456789'
                            ]
                        ],
                        'max_dimensions' => [
                            'height' => 4000,
                            'width' => 6000
                        ],
                        'orientation' => 'horizontal',
                        'people' => ['Person A', 'Person B'],
                        'product_types' => ['image'],
                        'quality_rank' => 1,
                        'referral_destinations' => [
                            [
                                'site_name' => 'GettyImages',
                                'uri' => 'https://www.gettyimages.com'
                            ]
                        ],
                        'state_province' => 'Kigali Province',
                        'title' => 'Marketplace in Rwanda',
                        'uri_oembed' => 'https://embed.gettyimages.com/oembed/image123',
                        'istock_licenses' => [
                            [
                                'license_type' => 'Standard',
                                'credits' => 10
                            ]
                        ],
                        'istock_collection' => 'Signature',
                        'contributor' => [
                            'member_name' => 'janedoe_001',
                            'display_name' => 'Jane Doe'
                        ],
                        'object_name' => 'Marketplace_Rwanda'
                    ]
                ],
                'images_not_found' => ['IMG_NOT_EXIST_001', 'IMG_NOT_EXIST_002']
            ]
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'validation_error',
                'message' => [],
            ],
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while processing your request',
            ]
        )
    )]
    public function imageMetadata(string $id): JsonResponse
    {
        $result = $this->service->getImageMetadata($id);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/gettyimages/video_metadata/{id}',
        summary: 'Get video metadata',
        description: 'Retrieve metadata for a specific video.',
        tags: ["Getty Images"],
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID of the video',
        schema: new OA\Schema(
            type: 'string',
            example: '17f20503-6c24-4c16-946b-35dbbce2af2f',
        ),
    )]
    #[OA\Response(
        response: 200,
        description: 'Getty Image Asset Details',
        content: new OA\JsonContent(
            example: [
                'id' => '12345',
                'allowed_use' => [
                    'how_can_i_use_it' => 'Editorial use only.',
                    'release_info' => 'No model or property release.',
                    'usage_restrictions' => [
                        'Not for commercial use',
                        'No modifications allowed'
                    ]
                ],
                'artist' => 'John Doe',
                'asset_family' => 'creative',
                'caption' => 'A scenic view of the mountains at sunset.',
                'city' => 'Kigali',
                'clip_length' => '00:15',
                'collection_id' => 789,
                'collection_code' => 'ABC',
                'collection_name' => 'Nature Shots',
                'color_type' => 'color',
                'copyright' => '© 2025 John Doe',
                'country' => 'Rwanda',
                'date_created' => '2025-06-20T08:11:19.523Z',
                'date_submitted' => '2025-06-20T08:11:19.523Z',
                'download_product' => 'standard_video',
                'display_sizes' => [
                    [
                        'is_watermarked' => true,
                        'name' => 'preview',
                        'uri' => 'https://example.com/preview.jpg',
                        'aspect_ratio' => '16:9',
                    ]
                ],
                'download_sizes' => [
                    [
                        'bit_depth' => '8-bit',
                        'broadcast_video_standard' => 'HD',
                        'compression' => 'H.264',
                        'content_type' => 'video/mp4',
                        'description' => 'Full HD download',
                        'downloads' => [
                            [
                                'product_id' => 'prod-001',
                                'product_type' => 'video',
                                'uri' => 'https://example.com/download.mp4',
                                'agreement_name' => 'Standard License'
                            ]
                        ],
                        'format' => 'MP4',
                        'frame_rate' => 30,
                        'frame_size' => '1920x1080',
                        'height' => 1080,
                        'interlaced' => false,
                        'bytes' => 10485760,
                        'name' => 'HD',
                        'width' => 1920
                    ]
                ],
                'keywords' => [
                    [
                        'keyword_id' => 'k123',
                        'text' => 'sunset',
                        'type' => 'theme',
                        'relevance' => 95
                    ]
                ],
                'license_model' => 'royalty_free',
                'quality_rank' => 1,
                'title' => 'Sunset Over Mountains',
                'orientation' => 'horizontal',
                'people' => ['Person A', 'Person B'],
                'istock_licenses' => [
                    [
                        'license_type' => 'Standard',
                        'credits' => 10
                    ]
                ]
            ]
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'validation_error',
                'message' => [],
            ],
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while processing your request',
            ]
        )
    )]
    public function videoMetadata(string $id): JsonResponse
    {
        $result = $this->service->getVideoMetadata($id);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/gettyimages/image_download/{id}',
        summary: 'Get image download',
        description: 'Retrieve download link for a specific image.',
        tags: ["Getty Images"],
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID of the image',
        schema: new OA\Schema(
            type: 'string',
            example: '17f20503-6c24-4c16-946b-35dbbce2af2f',
        ),
    )]
    #[OA\Response(
        response: 200,
        description: 'Image download link retrieved successfully'
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'validation_error',
                'message' => [],
            ],
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while processing your request',
            ]
        )
    )]
    public function imageDownload(string $id): JsonResponse
    {
        $result = $this->service->downloadImage($id);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/gettyimages/video_download/{id}',
        summary: 'Get video download',
        description: 'Retrieve download link for a specific video.',
        tags: ["Getty Images"],
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID of the video',
        schema: new OA\Schema(
            type: 'string',
            example: '17f20503-6c24-4c16-946b-35dbbce2af2f',
        ),
    )]
    #[OA\Response(
        response: 200,
        description: 'Video download link retrieved successfully'
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'validation_error',
                'message' => [],
            ],
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while processing your request',
            ]
        )
    )]
    public function videoDownload(string $id): JsonResponse
    {
        $result = $this->service->downloadVideo($id);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/gettyimages/affiliate_image_search',
        summary: 'Search photos',
        description: 'Search photos',
        tags: ["Getty Images"],
        security: [['authentication' => []]],
    )]
    #[OA\Parameter(
        name: 'query',
        in: 'query',
        required: true,
        description: 'Search query (required, string, max:255)',
        schema: new OA\Schema(type: 'string', maxLength: 255, example: 'dog')
    )]
    #[OA\Parameter(
        name: 'fields',
        in: 'query',
        required: false,
        description: 'Optional fields to include (array)',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(type: 'string')
        )
    )]
    #[OA\Parameter(
        name: 'fields.language',
        in: 'query',
        required: false,
        description: 'Language for the fields (nullable, string, allowed: cs,de,en-GB,en-US,es,fi,fr,hu,id,it,ja,ko,nl,pl,pt-BR,pt-PT,ro,ru,sv,th,tr,uk,vi,zh-HK)',
        schema: new OA\Schema(
            type: 'string',
            enum: ['cs','de','en-GB','en-US','es','fi','fr','hu','id','it','ja','ko','nl','pl','pt-BR','pt-PT','ro','ru','sv','th','tr','uk','vi','zh-HK'],
            example: 'en-US'
        )
    )]
    #[OA\Parameter(
        name: 'fields.countryCode',
        in: 'query',
        required: false,
        description: 'Country code (nullable, string, size:3, alpha)',
        schema: new OA\Schema(type: 'string', minLength: 3, maxLength: 3, example: 'USA')
    )]
    #[OA\Parameter(
        name: 'fields.style',
        in: 'query',
        required: false,
        description: 'Style (nullable, string, allowed: vector, photograph)',
        schema: new OA\Schema(type: 'string', enum: ['vector', 'photograph'], example: 'photograph')
    )]
    #[OA\Response(
        response: 200,
        description: 'Affiliate image search results',
        content: new OA\JsonContent(
            example: [
                'images' => [
                    [
                        'id' => 'IMG123456789',
                        'title' => 'Marketplace in Rwanda',
                        'caption' => 'A bustling market in Kigali, Rwanda.',
                        'preview_urls' => [
                            'small' => 'https://example.com/small.jpg',
                            'small_height' => 150,
                            'small_width' => 200,
                            'medium' => 'https://example.com/medium.jpg',
                            'medium_height' => 300,
                            'medium_width' => 400,
                            'large' => 'https://example.com/large.jpg',
                            'large_height' => 600,
                            'large_width' => 800,
                        ],
                        'destination_url' => 'https://example.com/image/IMG123456789'
                    ]
                ],
                'auto_corrections' => [
                    'phrase' => 'market'
                ]
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad request',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Invalid parameters provided. Ensure "fields" are specified and valid.',
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while processing your request',
            ]
        )
    )]
    public function affiliateImageSearch(AffiliateImageSearchRequest $request): JsonResponse
    {
        $data = AffiliateImageSearchData::from($request->validated());

        $result = $this->service->searchAffiliateImages($data);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/gettyimages/affiliate_video_search',
        summary: 'Search videos',
        description: 'Search videos',
        tags: ["Getty Images"],
        security: [['authentication' => []]],
    )]
    #[OA\Parameter(
        name: 'phrase',
        in: 'query',
        required: true,
        description: 'Search phrase (required, string, max:255)',
        schema: new OA\Schema(type: 'string', maxLength: 255, example: 'cat')
    )]
    #[OA\Parameter(
        name: 'fields',
        in: 'query',
        required: false,
        description: 'Optional fields to include (array)',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(type: 'string')
        )
    )]
    #[OA\Parameter(
        name: 'fields.language',
        in: 'query',
        required: false,
        description: 'Language for the fields (nullable, string, allowed: cs,de,en-GB,en-US,es,fi,fr,hu,id,it,ja,ko,nl,pl,pt-BR,pt-PT,ro,ru,sv,th,tr,uk,vi,zh-HK)',
        schema: new OA\Schema(
            type: 'string',
            enum: ['cs','de','en-GB','en-US','es','fi','fr','hu','id','it','ja','ko','nl','pl','pt-BR','pt-PT','ro','ru','sv','th','tr','uk','vi','zh-HK'],
            example: 'en-US'
        )
    )]
    #[OA\Parameter(
        name: 'fields.countryCode',
        in: 'query',
        required: false,
        description: 'Country code (nullable, string, size:3, alpha)',
        schema: new OA\Schema(type: 'string', minLength: 3, maxLength: 3, example: 'USA')
    )]
    #[OA\Response(
        response: 200,
        description: 'Affiliate video search results',
        content: new OA\JsonContent(
            example: [
                'videos' => [
                    [
                        'id' => 'VID123456789',
                        'title' => 'Wildlife in Rwanda',
                        'caption' => 'A lioness walking in Akagera National Park.',
                        'preview_urls' => [
                            'small_still' => 'https://example.com/small_still.jpg',
                            'medium_still' => 'https://example.com/medium_still.jpg',
                            'large_still' => 'https://example.com/large_still.jpg',
                            'small_motion' => 'https://example.com/small_motion.mp4',
                            'large_motion' => 'https://example.com/large_motion.mp4'
                        ],
                        'destination_url' => 'https://example.com/video/VID123456789',
                        'clip_length' => '00:30'
                    ]
                ],
                'auto_corrections' => [
                    'phrase' => 'wildlife'
                ]
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad request',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Invalid parameters provided. Ensure "fields" are specified and valid.',
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while processing your request',
            ]
        )
    )]
    public function affiliateVideoSearch(AffiliateVideoSearchRequest $request): JsonResponse
    {
        $data = AffiliateVideoSearchData::from($request->validated());

        $result = $this->service->searchAffiliateVideos($data);

        return $this->logAndResponse($result);
    }
}
