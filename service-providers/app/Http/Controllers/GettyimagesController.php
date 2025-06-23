<?php

namespace App\Http\Controllers;

use App\Data\GettyImages\AffiliateImageSearchData;
use App\Data\GettyImages\AffiliateVideoSearchData;
use App\Data\GettyImages\DownloadImageAsyncData;
use App\Data\GettyImages\ExtendImageData;
use App\Data\GettyImages\GenerateBackgroundsData;
use App\Data\GettyImages\ImageGenerationData;
use App\Data\GettyImages\ImageSearchByImageUploadData;
use App\Data\GettyImages\ImageSearchCreativeByImageData;
use App\Data\GettyImages\ImageSearchCreativeData;
use App\Data\GettyImages\ImageSearchData;
use App\Data\GettyImages\ImageSearchEditorialData;
use App\Data\GettyImages\ImageVariationsData;
use App\Data\GettyImages\InfluenceColorByImageData;
use App\Data\GettyImages\InfluenceCompositionByImageData;
use App\Data\GettyImages\RefineImageData;
use App\Data\GettyImages\RemoveBackgroundData;
use App\Data\GettyImages\RemoveObjectFromImageData;
use App\Data\GettyImages\ReplaceBackgroundData;
use App\Data\GettyImages\VideoSearchCreativeByImageData;
use App\Data\GettyImages\VideoSearchCreativeData;
use App\Data\GettyImages\VideoSearchData;
use App\Data\GettyImages\VideoSearchEditorialData;
use App\Http\Requests\GettyImages\AffiliateImageSearchRequest;
use App\Http\Requests\GettyImages\AffiliateVideoSearchRequest;
use App\Http\Requests\GettyImages\DownloadImageAsyncRequest;
use App\Http\Requests\GettyImages\ExtendImageRequest;
use App\Http\Requests\GettyImages\GenerateBackgroundsRequest;
use App\Http\Requests\GettyImages\ImageGenerationRequest;
use App\Http\Requests\GettyImages\ImageSearchByImageUploadRequest;
use App\Http\Requests\GettyImages\ImageSearchCreativeByImageRequest;
use App\Http\Requests\GettyImages\ImageSearchCreativeRequest;
use App\Http\Requests\GettyImages\ImageSearchEditorialRequest;
use App\Http\Requests\GettyImages\ImageSearchRequest;
use App\Http\Requests\GettyImages\ImageVariationsRequest;
use App\Http\Requests\GettyImages\InfluenceColorByImageRequest;
use App\Http\Requests\GettyImages\InfluenceCompositionByImageRequest;
use App\Http\Requests\GettyImages\RefineImageRequest;
use App\Http\Requests\GettyImages\RemoveBackgroundRequest;
use App\Http\Requests\GettyImages\RemoveObjectFromImageRequest;
use App\Http\Requests\GettyImages\ReplaceBackgroundRequest;
use App\Http\Requests\GettyImages\VideoSearchCreativeByImageRequest;
use App\Http\Requests\GettyImages\VideoSearchCreativeRequest;
use App\Http\Requests\GettyImages\VideoSearchEditorialRequest;
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
                'newborn',
                'baby',
                'child',
                'teenager',
                'young_adult',
                'adult',
                'adults_only',
                'mature_adult',
                'senior_adult',
                '0-1_months',
                '2-5_months',
                '6-11_months',
                '12-17_months',
                '18-19_years',
                '20-24_years',
                '20-29_years',
                '25-29_years',
                '30-34_years',
                '30-39_years',
                '35-39_years',
                '40-44_years',
                '40-49_years',
                '45-49_years',
                '50-54_years',
                '50-59_years',
                '55-59_years',
                '60-64_years',
                '60-69_years',
                '65-69_years',
                '70-79_years',
                '80-89_years',
                '90_plus_years',
                '100_over'
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
                'abstract',
                'candid',
                'close_up',
                'copy_space',
                'cut_out',
                'full_frame',
                'full_length',
                'headshot',
                'looking_at_camera',
                'macro',
                'medium_shot',
                'part_of_a_series',
                'portrait',
                'sparse',
                'still_life',
                'three_quarter_length',
                'waist_up',
                'wide_shot'
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
                'black',
                'white',
                'east_asian',
                'hispanic_latinx',
                'japanese',
                'middle_eastern',
                'multiracial_person',
                'multiethnic_group',
                'native_american_first_nations',
                'pacific_islander',
                'south_asian',
                'southeast_asian'
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
                'accessories',
                'allowed_use',
                'alternative_ids',
                'artist',
                'asset_family',
                'call_for_image',
                'caption',
                'collection_code',
                'collection_id',
                'collection_name',
                'color_type',
                'comp',
                'comp_webp',
                'copyright',
                'date_camera_shot',
                'date_created',
                'date_submitted',
                'detail_set',
                'display_set',
                'download_product',
                'download_sizes',
                'editorial_segments',
                'editorial_source',
                'event_ids',
                'graphical_style',
                'idistock_collection',
                'keywords',
                'largest_downloads',
                'license_model',
                'max_dimensions',
                'orientation',
                'people',
                'preview',
                'product_types',
                'quality_rank',
                'referral_destinations',
                'summary_set',
                'thumb',
                'title',
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
            items: new OA\Items(type: 'string', enum: ['fine_art', 'illustration', 'photograph', 'vector'])
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
        schema: new OA\Schema(type: 'string', enum: ['x_small', 'small', 'medium', 'large', 'x_large', 'xx_large', 'vector'])
    )]
    #[OA\Parameter(
        name: 'fields.number_of_people',
        in: 'query',
        required: false,
        description: 'Number of people (nullable, array)',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(type: 'string', enum: ['none', 'one', 'two', 'group'])
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
                'horizontal',
                'vertical',
                'square',
                'panoramic_horizontal',
                'panoramic_vertical'
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
    public function imageSearch(ImageSearchRequest $request): JsonResponse
    {

        $data = ImageSearchData::from($request->validated());

        $result = $this->service->searchImages($data);

        return $this->logAndResponse($result);
    }


    #[OA\Get(
        path: '/api/gettyimages/image_search/creative',
        summary: 'Search Getty Images Creative Photos',
        description: 'Search for creative images from Getty Images using query parameters like phrase, color, artist, aspect ratio, people attributes, and more.',
        tags: ['Getty Images']
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
                'newborn',
                'baby',
                'child',
                'teenager',
                'young_adult',
                'adult',
                'adults_only',
                'mature_adult',
                'senior_adult',
                '0-1_months',
                '2-5_months',
                '6-11_months',
                '12-17_months',
                '18-19_years',
                '20-24_years',
                '20-29_years',
                '25-29_years',
                '30-34_years',
                '30-39_years',
                '35-39_years',
                '40-44_years',
                '40-49_years',
                '45-49_years',
                '50-54_years',
                '50-59_years',
                '55-59_years',
                '60-64_years',
                '60-69_years',
                '65-69_years',
                '70-79_years',
                '80-89_years',
                '90_plus_years',
                '100_over'
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
                'abstract',
                'candid',
                'close_up',
                'copy_space',
                'cut_out',
                'full_frame',
                'full_length',
                'headshot',
                'looking_at_camera',
                'macro',
                'medium_shot',
                'part_of_a_series',
                'portrait',
                'sparse',
                'still_life',
                'three_quarter_length',
                'waist_up',
                'wide_shot'
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
        name: 'fields.enhanced_search',
        in: 'query',
        required: false,
        description: 'Enhanced search (nullable, boolean)',
        schema: new OA\Schema(type: 'boolean')
    )]
    #[OA\Parameter(
        name: 'fields.ethnicity',
        in: 'query',
        required: false,
        description: 'Ethnicity (nullable, array)',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(type: 'string', enum: [
                'black',
                'white',
                'east_asian',
                'hispanic_latinx',
                'japanese',
                'middle_eastern',
                'multiracial_person',
                'multiethnic_group',
                'native_american_first_nations',
                'pacific_islander',
                'south_asian',
                'southeast_asian'
            ])
        )
    )]
    #[OA\Parameter(
        name: 'fields.exclude_editorial_use_only',
        in: 'query',
        required: false,
        description: 'Exclude editorial use only (nullable, boolean)',
        schema: new OA\Schema(type: 'boolean')
    )]
    #[OA\Parameter(
        name: 'fields.exclude_keyword_ids',
        in: 'query',
        required: false,
        description: 'Exclude keyword IDs (nullable, array of integer)',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'))
    )]
    #[OA\Parameter(
        name: 'fields.exclude_nudity',
        in: 'query',
        required: false,
        description: 'Exclude nudity (nullable, boolean)',
        schema: new OA\Schema(type: 'boolean')
    )]
    #[OA\Parameter(
        name: 'fields.facet_fields',
        in: 'query',
        required: false,
        description: 'Facet fields (nullable, array, allowed: artists,events,locations)',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['artists', 'events', 'locations']))
    )]
    #[OA\Parameter(
        name: 'fields.facet_max_count',
        in: 'query',
        required: false,
        description: 'Facet max count (nullable, integer, min:1)',
        schema: new OA\Schema(type: 'integer', minimum: 1)
    )]
    #[OA\Parameter(
        name: 'fields.fields',
        in: 'query',
        required: false,
        description: 'Fields (nullable, array, allowed: allowed_use, artist, aspect_ratio, asset_family, call_for_image, caption, clip_length, collection_code, collection_id, collection_name, color_type, comp, copyright, date_created, date_submitted, detail_set, display_set, download_product, download_sizes, era, id, istock_collection, keywords, largest_downloads, license_model, mastered_to, object_name, orientation, originally_shot_on, preview, summary_set, thumb, title)',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(type: 'string', enum: [
                'allowed_use',
                'artist',
                'aspect_ratio',
                'asset_family',
                'call_for_image',
                'caption',
                'clip_length',
                'collection_code',
                'collection_id',
                'collection_name',
                'color_type',
                'comp',
                'copyright',
                'date_created',
                'date_submitted',
                'detail_set',
                'display_set',
                'download_product',
                'download_sizes',
                'era',
                'id',
                'istock_collection',
                'keywords',
                'largest_downloads',
                'license_model',
                'mastered_to',
                'object_name',
                'orientation',
                'originally_shot_on',
                'preview',
                'summary_set',
                'thumb',
                'title'
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
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['fine_art', 'illustration', 'photograph', 'vector']))
    )]
    #[OA\Parameter(
        name: 'fields.graphical_styles_filter_type',
        in: 'query',
        required: false,
        description: 'Graphical styles filter type (nullable, string, allowed: exclude, include)',
        schema: new OA\Schema(type: 'string', enum: ['exclude', 'include'])
    )]
    #[OA\Parameter(
        name: 'fields.include_facets',
        in: 'query',
        required: false,
        description: 'Include facets (nullable, boolean)',
        schema: new OA\Schema(type: 'boolean')
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
        schema: new OA\Schema(type: 'string', enum: ['x_small', 'small', 'medium', 'large', 'x_large', 'xx_large', 'vector'])
    )]
    #[OA\Parameter(
        name: 'fields.moods',
        in: 'query',
        required: false,
        description: 'Moods (nullable, array)',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['black_and_white', 'bold', 'cool', 'dramatic', 'natural', 'vivid', 'warm']))
    )]
    #[OA\Parameter(
        name: 'fields.number_of_people',
        in: 'query',
        required: false,
        description: 'Number of people (nullable, array)',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['none', 'one', 'two', 'group']))
    )]
    #[OA\Parameter(
        name: 'fields.orientations',
        in: 'query',
        required: false,
        description: 'Orientations (nullable, array)',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['horizontal', 'vertical', 'square', 'panoramic_horizontal', 'panoramic_vertical']))
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
        name: 'fields.safe_search',
        in: 'query',
        required: false,
        description: 'Safe search (nullable, boolean)',
        schema: new OA\Schema(type: 'boolean')
    )]
    #[OA\Response(
        response: 200,
        description: 'Getty Images API - Creative Image Search Response',
        content: new OA\JsonContent(
            example: [
                "result_count" => 0,
                "images" => [
                    [
                        "istock_collection" => "string",
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
                        "date_camera_shot" => "2025-06-23T11:12:00.446Z",
                        "date_created" => "2025-06-23T11:12:00.446Z",
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
                        "product_types" => [
                            "string"
                        ],
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
                        "date_submitted" => "2025-06-23T11:12:00.446Z"
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
                            "date" => "2025-06-23T11:12:00.446Z"
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
    public function imageSearchCreative(ImageSearchCreativeRequest $request): JsonResponse
    {
        $data = ImageSearchCreativeData::from($request->validated());
        $result = $this->service->searchImagesCreative($data);
        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/gettyimages/image_search/creative/by-image',
        summary: 'Search Getty Images Creative Photos By Image',
        description: 'Search for creative images from Getty Images using an uploaded image or image URL. Supports advanced filtering by phrase, language, country, asset ID, and more. Enables discovery of visually similar creative content.',
        tags: ['Getty Images']
    )]
    #[OA\Parameter(name: 'fields.phrase', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'fields.language', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'fields.countryCode', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'fields.asset_id', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'fields.exclude_editorial_use_only', in: 'query', required: false, schema: new OA\Schema(type: 'boolean'))]
    #[OA\Parameter(
        name: 'fields.facet_fields[]',
        in: 'query',
        required: false,
        description: 'Facet fields to filter by (must be one of artists, events, locations)',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(
                type: 'string',
                enum: ['artists', 'events', 'locations']
            )
        )
    )]
    #[OA\Parameter(name: 'fields.facet_max_count', in: 'query', required: false, schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(
        name: 'fields.fields[]',
        in: 'query',
        required: false,
        description: 'Specify which video metadata fields to include in the response',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(
                type: 'string',
                enum: [
                    'allowed_use',
                    'artist',
                    'aspect_ratio',
                    'asset_family',
                    'call_for_image',
                    'caption',
                    'clip_length',
                    'collection_code',
                    'collection_id',
                    'collection_name',
                    'color_type',
                    'comp',
                    'copyright',
                    'date_created',
                    'date_submitted',
                    'detail_set',
                    'display_set',
                    'download_product',
                    'download_sizes',
                    'era',
                    'id',
                    'istock_collection',
                    'keywords',
                    'largest_downloads',
                    'license_model',
                    'mastered_to',
                    'object_name',
                    'orientation',
                    'originally_shot_on',
                    'preview',
                    'product_types',
                    'quality_rank',
                    'referral_destinations',
                    'shot_speed',
                    'summary_set',
                    'thumb',
                    'title'
                ]
            )
        )
    )]
    #[OA\Parameter(name: 'fields.image_url', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'fields.include_facets', in: 'query', required: false, schema: new OA\Schema(type: 'boolean'))]
    #[OA\Parameter(name: 'fields.page', in: 'query', required: false, schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'fields.page_size', in: 'query', required: false, schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'fields.product_types[]', in: 'query', required: false, schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string')))]
    #[OA\Response(
        response: 200,
        description: 'Getty Images API - Creative Image Search By Image Response',
        content: new OA\JsonContent(
            example: [
                "image_fingerprint" => "string",
                "result_count" => 0,
                "images" => [
                    [
                        "istock_collection" => "string",
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
                        "date_camera_shot" => "2025-06-23T11:10:47.026Z",
                        "date_created" => "2025-06-23T11:10:47.026Z",
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
                        "date_submitted" => "2025-06-23T11:10:47.026Z"
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
                            "date" => "2025-06-23T11:10:47.026Z"
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
    public function imageSearchCreativeByImage(ImageSearchCreativeByImageRequest $request): JsonResponse
    {
        $data = ImageSearchCreativeByImageData::from($request->validated());
        $result = $this->service->searchImagesCreativeByImage($data);
        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/gettyimages/image_search/editorial',
        summary: 'Search editorial images',
        description: 'Search for editorial images from Getty Images with advanced filters such as age of people, artists, collection codes, color, compositions, ethnicity, orientation, and more. Supports pagination and multiple filter combinations for precise editorial content discovery.',
        tags: ['Getty Images']
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
                'newborn',
                'baby',
                'child',
                'teenager',
                'young_adult',
                'adult',
                'adults_only',
                'mature_adult',
                'senior_adult',
                '0-1_months',
                '2-5_months',
                '6-11_months',
                '12-17_months',
                '18-19_years',
                '20-24_years',
                '20-29_years',
                '25-29_years',
                '30-34_years',
                '30-39_years',
                '35-39_years',
                '40-44_years',
                '40-49_years',
                '45-49_years',
                '50-54_years',
                '50-59_years',
                '55-59_years',
                '60-64_years',
                '60-69_years',
                '65-69_years',
                '70-79_years',
                '80-89_years',
                '90_plus_years',
                '100_over'
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
        name: 'fields.compositions',
        in: 'query',
        required: false,
        description: 'Compositions (nullable, array)',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(type: 'string', enum: [
                'abstract',
                'candid',
                'close_up',
                'copy_space',
                'cut_out',
                'full_frame',
                'full_length',
                'headshot',
                'looking_at_camera',
                'macro',
                'medium_shot',
                'part_of_a_series',
                'portrait',
                'sparse',
                'still_life',
                'three_quarter_length',
                'waist_up',
                'wide_shot'
            ])
        )
    )]
    #[OA\Parameter(
        name: 'fields.date_from',
        in: 'query',
        required: false,
        description: 'Start date (nullable, date, ISO 8601)',
        schema: new OA\Schema(type: 'string', format: 'date')
    )]
    #[OA\Parameter(
        name: 'fields.date_to',
        in: 'query',
        required: false,
        description: 'End date (nullable, date, ISO 8601)',
        schema: new OA\Schema(type: 'string', format: 'date')
    )]
    #[OA\Parameter(
        name: 'fields.download_product',
        in: 'query',
        required: false,
        description: 'Download product (nullable, string)',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'fields.editorial_segments',
        in: 'query',
        required: false,
        description: 'Editorial segments (nullable, boolean)',
        schema: new OA\Schema(type: 'boolean')
    )]
    #[OA\Parameter(
        name: 'fields.embed_content_only',
        in: 'query',
        required: false,
        description: 'Embed content only (nullable, boolean)',
        schema: new OA\Schema(type: 'boolean')
    )]
    #[OA\Parameter(
        name: 'fields.ethnicity',
        in: 'query',
        required: false,
        description: 'Ethnicity (nullable, array)',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(type: 'string', enum: [
                'black',
                'white',
                'east_asian',
                'hispanic_latinx',
                'japanese',
                'middle_eastern',
                'multiracial_person',
                'multiethnic_group',
                'native_american_first_nations',
                'pacific_islander',
                'south_asian',
                'southeast_asian'
            ])
        )
    )]
    #[OA\Parameter(
        name: 'fields.event_ids',
        in: 'query',
        required: false,
        description: 'Event IDs (nullable, array of integer)',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'))
    )]
    #[OA\Parameter(
        name: 'fields.exclude_keyword_ids',
        in: 'query',
        required: false,
        description: 'Exclude keyword IDs (nullable, array of integer)',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'))
    )]
    #[OA\Parameter(
        name: 'fields.fields',
        in: 'query',
        required: false,
        description: 'Fields (nullable, array, allowed: allowed_use, artist, aspect_ratio, asset_family, call_for_image, caption, clip_length, collection_code, collection_id, collection_name, color_type, comp, copyright, date_created, date_submitted, detail_set, display_set, download_product, download_sizes, era, id, istock_collection, keywords, largest_downloads, license_model, mastered_to, object_name, orientation, originally_shot_on, preview, summary_set, thumb, title)',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(type: 'string', enum: [
                'allowed_use',
                'artist',
                'aspect_ratio',
                'asset_family',
                'call_for_image',
                'caption',
                'clip_length',
                'collection_code',
                'collection_id',
                'collection_name',
                'color_type',
                'comp',
                'copyright',
                'date_created',
                'date_submitted',
                'detail_set',
                'display_set',
                'download_product',
                'download_sizes',
                'era',
                'id',
                'istock_collection',
                'keywords',
                'largest_downloads',
                'license_model',
                'mastered_to',
                'object_name',
                'orientation',
                'originally_shot_on',
                'preview',
                'summary_set',
                'thumb',
                'title'
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
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['fine_art', 'illustration', 'photograph', 'vector']))
    )]
    #[OA\Parameter(
        name: 'fields.graphical_styles_filter_type',
        in: 'query',
        required: false,
        description: 'Graphical styles filter type (nullable, string, allowed: exclude, include)',
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
        schema: new OA\Schema(type: 'string', enum: ['x_small', 'small', 'medium', 'large', 'x_large', 'xx_large', 'vector'])
    )]
    #[OA\Parameter(
        name: 'fields.number_of_people',
        in: 'query',
        required: false,
        description: 'Number of people (nullable, array, allowed: none,one,two,group)',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['none', 'one', 'two', 'group']))
    )]
    #[OA\Parameter(
        name: 'fields.orientations',
        in: 'query',
        required: false,
        description: 'Orientations (nullable, array, allowed: horizontal,vertical,square,panoramic_horizontal,panoramic_vertical)',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['horizontal', 'vertical', 'square', 'panoramic_horizontal', 'panoramic_vertical']))
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
    #[OA\Parameter(
        name: 'fields.minimum_quality_rank',
        in: 'query',
        required: false,
        description: 'Minimum quality rank (nullable, integer, allowed: 1,2,3)',
        schema: new OA\Schema(type: 'integer', enum: [1, 2, 3])
    )]
    #[OA\Parameter(
        name: 'fields.facet_fields',
        in: 'query',
        required: false,
        description: 'Facet fields (nullable, array, allowed: artists,events,locations,specific_people)',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['artists', 'events', 'locations', 'specific_people']))
    )]
    #[OA\Parameter(
        name: 'fields.facet_max_count',
        in: 'query',
        required: false,
        description: 'Facet max count (nullable, integer, min:1)',
        schema: new OA\Schema(type: 'integer', minimum: 1)
    )]
    #[OA\Response(
        response: 200,
        description: 'Getty Images API - Editorial Image Search Response',
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
                        "date_camera_shot" => "2025-06-23T10:28:21.408Z",
                        "date_created" => "2025-06-23T10:28:21.408Z",
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
                        "date_submitted" => "2025-06-23T10:28:21.408Z"
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
                            "date" => "2025-06-23T10:28:21.408Z"
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
    public function imageSearchEditorial(ImageSearchEditorialRequest $request): JsonResponse
    {
        $data = ImageSearchEditorialData::from($request->validated());
        $result = $this->service->searchImagesEditorial($data);
        return $this->logAndResponse($result);
    }

    #[OA\Put(
        path: '/api/gettyimages/image_search/by-image/upload',
        summary: 'Upload image for Getty image search',
        description: 'Uploads an image (JPEG/PNG) for later use in Getty creative search by image.',
        tags: ['Getty Images']
    )]
    #[OA\Parameter(
        name: 'file-name',
        in: 'query',
        required: true,
        description: 'The name to assign to the uploaded file.',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'image/jpeg',
            schema: new OA\Schema(
                required: ['file'],
                properties: [
                    new OA\Property(
                        property: 'file',
                        type: 'string',
                        format: 'binary',
                        description: 'JPEG or PNG image to upload'
                    )
                ]
            )
        )
    )]
    #[OA\Response(response: 200, description: 'OK')]
    #[OA\Response(response: 400, description: 'InvalidParameterValue')]
    #[OA\Response(response: 401, description: 'AuthorizationTokenRequired')]
    #[OA\Response(response: 403, description: 'UnauthorizedDisplaySize')]

    public function imageSearchByImageUpload(ImageSearchByImageUploadRequest $request): JsonResponse
    {
        $data = ImageSearchByImageUploadData::from($request->validated());
        $result = $this->service->searchImagesByImageUpload($data);
        return $this->logAndResponse($result);
    }


    #[OA\Get(
        path: '/api/gettyimages/video_search/creative',
        summary: 'Search Getty Images Creative Videos',
        description: 'Search for creative video content from Getty Images using advanced filters such as phrase, color, artist, aspect ratio, people attributes, and more. Supports discovery of creative video assets by metadata and visual similarity.',
        tags: ['Getty Images']
    )]
    #[OA\Parameter(name: 'phrase', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'sort_order', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['best_match', 'most_popular', 'newest', 'random']))]
    #[OA\Parameter(name: 'fields.language', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'fields.countryCode', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'fields.age_of_people[]', in: 'query', required: false, schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: [
        'newborn',
        'baby',
        'child',
        'teenager',
        'young_adult',
        'adult',
        'adults_only',
        'mature_adult',
        'senior_adult',
        '0-1_months',
        '2-5_months',
        '6-11_months',
        '12-17_months',
        '18-19_years',
        '20-24_years',
        '20-29_years',
        '25-29_years',
        '30-34_years',
        '30-39_years',
        '35-39_years',
        '40-44_years',
        '40-49_years',
        '45-49_years',
        '50-54_years',
        '50-59_years',
        '55-59_years',
        '60-64_years',
        '60-69_years',
        '65-69_years',
        '70-79_years',
        '80-89_years',
        '90_plus_years',
        '100_over'
    ])))]
    #[OA\Parameter(name: 'fields.artists', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'fields.aspect_ratios[]', in: 'query', required: false, schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['16:9', '9:16', '3:4', '4:3', '4:5', '2:1', '17:9', '9:17'])))]
    #[OA\Parameter(name: 'fields.collection_codes[]', in: 'query', required: false, schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string')))]
    #[OA\Parameter(name: 'fields.collections_filter_type', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['exclude', 'include']))]
    #[OA\Parameter(name: 'fields.color', in: 'query', required: false, schema: new OA\Schema(type: 'string', minLength: 6, maxLength: 6))]
    #[OA\Parameter(name: 'fields.compositions[]', in: 'query', required: false, schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['abstract', 'candid', 'close_up', 'copy_space', 'cut_out', 'full_frame', 'full_length', 'headshot', 'looking_at_camera', 'macro', 'medium_shot', 'part_of_a_series', 'portrait', 'sparse', 'still_life', 'three_quarter_length', 'waist_up', 'wide_shot'])))]
    #[OA\Parameter(name: 'fields.download_product', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'fields.enhanced_search', in: 'query', required: false, schema: new OA\Schema(type: 'boolean'))]
    #[OA\Parameter(name: 'fields.ethnicity[]', in: 'query', required: false, schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['black', 'white', 'east_asian', 'hispanic_latinx', 'japanese', 'middle_eastern', 'multiracial_person', 'multiethnic_group', 'native_american_first_nations', 'pacific_islander', 'south_asian', 'southeast_asian'])))]
    #[OA\Parameter(name: 'fields.exclude_editorial_use_only', in: 'query', required: false, schema: new OA\Schema(type: 'boolean'))]
    #[OA\Parameter(name: 'fields.exclude_keyword_ids[]', in: 'query', required: false, schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer')))]
    #[OA\Parameter(name: 'fields.exclude_nudity', in: 'query', required: false, schema: new OA\Schema(type: 'boolean'))]
    #[OA\Parameter(name: 'fields.facet_fields[]', in: 'query', required: false, schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['artists', 'events', 'locations'])))]
    #[OA\Parameter(name: 'fields.facet_max_count', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1))]
    #[OA\Parameter(name: 'fields.fields[]', in: 'query', required: false, schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string')))]
    #[OA\Parameter(name: 'fields.format_available', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['sd', 'hd', '4k', 'hd_web']))]
    #[OA\Parameter(name: 'fields.frame_rates[]', in: 'query', required: false, schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['23.98', '24', '25', '29.97', '30', '50', '59.94', '60'])))]
    #[OA\Parameter(name: 'fields.image_techniques[]', in: 'query', required: false, schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['realtime', 'time_lapse', 'slow_motion', 'color', 'black_and_white', 'animation', 'selective_focus'])))]
    #[OA\Parameter(name: 'fields.include_facets', in: 'query', required: false, schema: new OA\Schema(type: 'boolean'))]
    #[OA\Parameter(name: 'fields.keyword_ids[]', in: 'query', required: false, schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer')))]
    #[OA\Parameter(name: 'fields.license_models[]', in: 'query', required: false, schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['rightsready', 'royaltyfree'])))]
    #[OA\Parameter(name: 'fields.min_clip_length', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 0))]
    #[OA\Parameter(name: 'fields.max_clip_length', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 0))]
    #[OA\Parameter(name: 'fields.number_of_people[]', in: 'query', required: false, schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['none', 'one', 'two', 'group'])))]
    #[OA\Parameter(name: 'fields.orientations[]', in: 'query', required: false, schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['horizontal', 'vertical'])))]
    #[OA\Parameter(name: 'fields.page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1))]
    #[OA\Parameter(name: 'fields.page_size', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 200))]
    #[OA\Parameter(name: 'fields.safe_search', in: 'query', required: false, schema: new OA\Schema(type: 'boolean'))]
    #[OA\Parameter(name: 'fields.release_status', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['release_not_important', 'full_eleased']))]
    #[OA\Parameter(name: 'fields.viewpoints[]', in: 'query', required: false, schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['lockdown', 'panning', 'tracking_shot', 'aerial_view', 'high_angle_view', 'low_angle_view', 'tilt', 'point_of_view'])))]
    #[OA\Response(
        response: 200,
        description: 'Creative video search results',
        content: new OA\JsonContent(
            example: [
                "videos" => [
                    [
                        "id" => "string",
                        "allowed_use" => [
                            "how_can_i_use_it" => "string",
                            "release_info" => "string",
                            "usage_restrictions" => [
                                "string"
                            ]
                        ],
                        "alternative_ids" => [
                            "additionalProp1" => "string",
                            "additionalProp2" => "string",
                            "additionalProp3" => "string"
                        ],
                        "artist" => "string",
                        "aspect_ratio" => "string",
                        "asset_family" => "string",
                        "asset_type" => "string",
                        "call_for_image" => true,
                        "caption" => "string",
                        "clip_length" => "string",
                        "collection_id" => 0,
                        "collection_code" => "string",
                        "collection_name" => "string",
                        "color_type" => "string",
                        "copyright" => "string",
                        "date_camera_shot" => "2025-06-23T10:02:01.975Z",
                        "date_created" => "2025-06-23T10:02:01.975Z",
                        "date_submitted" => "2025-06-23T10:02:01.975Z",
                        "display_sizes" => [
                            [
                                "is_watermarked" => true,
                                "name" => "string",
                                "uri" => "string",
                                "aspect_ratio" => "string"
                            ]
                        ],
                        "download_product" => "string",
                        "download_sizes" => [
                            [
                                "bit_depth" => "string",
                                "broadcast_video_standard" => "string",
                                "compression" => "string",
                                "content_type" => "string",
                                "description" => "string",
                                "downloads" => [
                                    [
                                        "product_id" => "string",
                                        "product_type" => "string",
                                        "uri" => "string",
                                        "agreement_name" => "string"
                                    ]
                                ],
                                "format" => "string",
                                "frame_rate" => 0,
                                "frame_size" => "string",
                                "height" => 0,
                                "interlaced" => true,
                                "bytes" => 0,
                                "name" => "string",
                                "width" => 0
                            ]
                        ],
                        "istock_collection" => "string",
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
                        "mastered_to" => "string",
                        "max_dimensions" => [
                            "height" => 0,
                            "width" => 0
                        ],
                        "orientation" => "string",
                        "originally_shot_on" => "string",
                        "product_types" => [
                            "string"
                        ],
                        "quality_rank" => 0,
                        "referral_destinations" => [
                            [
                                "site_name" => "string",
                                "uri" => "string"
                            ]
                        ],
                        "shot_speed" => "string",
                        "title" => "string",
                        "istock_licenses" => [
                            [
                                "license_type" => "Standard",
                                "credits" => 0
                            ]
                        ],
                        "object_name" => "string"
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
                            "date" => "2025-06-23T10:02:01.975Z"
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
                "related_searches" => [
                    [
                        "phrase" => "string",
                        "url" => "string"
                    ]
                ],
                "result_count" => 0,
                "auto_corrections" => [
                    "phrase" => "string"
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
    public function videoSearchCreative(VideoSearchCreativeRequest $request): JsonResponse
    {
        $data = VideoSearchCreativeData::from($request->validated());
        $result = $this->service->searchVideosCreative($data);
        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/gettyimages/video_search/creative/by-image',
        summary: 'Search Getty Creative Videos By Image',
        description: 'Search for creative video content from Getty Images using an uploaded image or image URL.',
        tags: ['Getty Images']
    )]
    #[OA\Parameter(name: 'fields.phrase', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'fields.language', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'fields.countryCode', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'fields.asset_id', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'fields.exclude_editorial_use_only', in: 'query', required: false, schema: new OA\Schema(type: 'boolean'))]
    #[OA\Parameter(
        name: 'fields.facet_fields[]',
        in: 'query',
        required: false,
        description: 'Facet fields to filter by (must be one of artists, events, locations)',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(
                type: 'string',
                enum: ['artists', 'events', 'locations']
            )
        )
    )]
    #[OA\Parameter(name: 'fields.facet_max_count', in: 'query', required: false, schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(
        name: 'fields.fields[]',
        in: 'query',
        required: false,
        description: 'Specify which video metadata fields to include in the response',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(
                type: 'string',
                enum: [
                    'allowed_use',
                    'artist',
                    'aspect_ratio',
                    'asset_family',
                    'call_for_image',
                    'caption',
                    'clip_length',
                    'collection_code',
                    'collection_id',
                    'collection_name',
                    'color_type',
                    'comp',
                    'copyright',
                    'date_created',
                    'date_submitted',
                    'detail_set',
                    'display_set',
                    'download_product',
                    'download_sizes',
                    'era',
                    'id',
                    'istock_collection',
                    'keywords',
                    'largest_downloads',
                    'license_model',
                    'mastered_to',
                    'object_name',
                    'orientation',
                    'originally_shot_on',
                    'preview',
                    'product_types',
                    'quality_rank',
                    'referral_destinations',
                    'shot_speed',
                    'summary_set',
                    'thumb',
                    'title'
                ]
            )
        )
    )]
    #[OA\Parameter(name: 'fields.image_url', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'fields.include_facets', in: 'query', required: false, schema: new OA\Schema(type: 'boolean'))]
    #[OA\Parameter(name: 'fields.page', in: 'query', required: false, schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'fields.page_size', in: 'query', required: false, schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'fields.product_types[]', in: 'query', required: false, schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string')))]
    #[OA\Response(
        response: 200,
        description: 'Creative video search results by image',
        content: new OA\JsonContent(
            example: [
                [
                    'videos' => [
                        [
                            'id' => 'string',
                            'allowed_use' => [
                                'how_can_i_use_it' => 'string',
                                'release_info' => 'string',
                                'usage_restrictions' => ['string']
                            ],
                            'alternative_ids' => [
                                'additionalProp1' => 'string',
                                'additionalProp2' => 'string',
                                'additionalProp3' => 'string'
                            ],
                            'artist' => 'string',
                            'aspect_ratio' => 'string',
                            'asset_family' => 'string',
                            'asset_type' => 'string',
                            'call_for_image' => true,
                            'caption' => 'string',
                            'clip_length' => 'string',
                            'collection_id' => 0,
                            'collection_code' => 'string',
                            'collection_name' => 'string',
                            'color_type' => 'string',
                            'copyright' => 'string',
                            'date_camera_shot' => '2025-06-23T09:33:31.222Z',
                            'date_created' => '2025-06-23T09:33:31.222Z',
                            'date_submitted' => '2025-06-23T09:33:31.222Z',
                            'display_sizes' => [
                                [
                                    'is_watermarked' => true,
                                    'name' => 'string',
                                    'uri' => 'string',
                                    'aspect_ratio' => 'string'
                                ]
                            ],
                            'download_product' => 'string',
                            'download_sizes' => [
                                [
                                    'bit_depth' => 'string',
                                    'broadcast_video_standard' => 'string',
                                    'compression' => 'string',
                                    'content_type' => 'string',
                                    'description' => 'string',
                                    'downloads' => [
                                        [
                                            'product_id' => 'string',
                                            'product_type' => 'string',
                                            'uri' => 'string',
                                            'agreement_name' => 'string'
                                        ]
                                    ],
                                    'format' => 'string',
                                    'frame_rate' => 0,
                                    'frame_size' => 'string',
                                    'height' => 0,
                                    'interlaced' => true,
                                    'bytes' => 0,
                                    'name' => 'string',
                                    'width' => 0
                                ]
                            ],
                            'istock_collection' => 'string',
                            'keywords' => [
                                [
                                    'keyword_id' => 'string',
                                    'text' => 'string',
                                    'type' => 'string',
                                    'relevance' => 0
                                ]
                            ],
                            'largest_downloads' => [
                                [
                                    'product_id' => 'string',
                                    'product_type' => 'string',
                                    'uri' => 'string',
                                    'agreement_name' => 'string'
                                ]
                            ],
                            'license_model' => 'string',
                            'mastered_to' => 'string',
                            'max_dimensions' => ['height' => 0, 'width' => 0],
                            'orientation' => 'string',
                            'originally_shot_on' => 'string',
                            'product_types' => ['string'],
                            'quality_rank' => 0,
                            'referral_destinations' => [
                                ['site_name' => 'string', 'uri' => 'string']
                            ],
                            'shot_speed' => 'string',
                            'title' => 'string',
                            'istock_licenses' => [
                                ['license_type' => 'Standard', 'credits' => 0]
                            ],
                            'object_name' => 'string'
                        ]
                    ],
                    'facets' => [
                        'specific_people' => [
                            ['id' => 0, 'name' => 'string']
                        ],
                        'events' => [
                            ['id' => 0, 'name' => 'string', 'date' => '2025-06-23T09:33:31.223Z']
                        ],
                        'locations' => [
                            ['id' => 0, 'name' => 'string']
                        ],
                        'artists' => [
                            ['name' => 'string']
                        ],
                        'entertainment' => [
                            ['id' => 0, 'name' => 'string']
                        ]
                    ],
                    'result_count' => 0,
                    'auto_corrections' => [
                        'phrase' => 'string'
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
                'message' => []
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while processing your request'
            ]
        )
    )]
    public function videoSearchCreativeByImage(VideoSearchCreativeByImageRequest $request): JsonResponse
    {
        $data = VideoSearchCreativeByImageData::from($request->validated());
        $result = $this->service->searchVideosCreativeByImage($data);
        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/gettyimages/video-search/editorial',
        summary: 'Search editorial videos',
        description: 'Performs a search for editorial videos with advanced filters such as aspect ratios, frame rates, image techniques, clip lengths, release status, and more.',
        tags: ['Getty Images'],
    )]
    #[OA\Parameter(
        name: 'phrase',
        in: 'query',
        required: false,
        description: 'Free-text search query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'sort_order',
        in: 'query',
        required: false,
        description: 'Sort order: best_match, most_popular, newest, or random',
        schema: new OA\Schema(type: 'string', enum: ['best_match', 'most_popular', 'newest', 'random'])
    )]
    #[OA\Parameter(
        name: 'fields.language',
        in: 'query',
        required: false,
        description: 'Language code',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'fields.countryCode',
        in: 'query',
        required: false,
        description: 'Country code',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'fields.age_of_people[]',
        in: 'query',
        required: false,
        description: 'Filter by age group',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string'))
    )]
    #[OA\Parameter(
        name: 'fields.artists',
        in: 'query',
        required: false,
        description: 'Artist name filter',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'fields.aspect_ratios[]',
        in: 'query',
        required: false,
        description: 'Aspect ratios filter',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['16:9', '9:16', '3:4', '4:3', '4:5', '2:1', '17:9', '9:17']))
    )]
    #[OA\Parameter(
        name: 'fields.collection_codes[]',
        in: 'query',
        required: false,
        description: 'Collection codes filter',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string'))
    )]
    #[OA\Parameter(
        name: 'fields.collections_filter_type',
        in: 'query',
        required: false,
        description: 'Include or exclude collections',
        schema: new OA\Schema(type: 'string', enum: ['exclude', 'include'])
    )]
    #[OA\Parameter(
        name: 'fields.color',
        in: 'query',
        required: false,
        description: 'Hex color code (6 characters)',
        schema: new OA\Schema(type: 'string', minLength: 6, maxLength: 6)
    )]
    #[OA\Parameter(
        name: 'fields.compositions[]',
        in: 'query',
        required: false,
        description: 'Composition styles',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string'))
    )]
    #[OA\Parameter(
        name: 'fields.date_from',
        in: 'query',
        required: false,
        description: 'Start date (ISO 8601)',
        schema: new OA\Schema(type: 'string', format: 'date-time')
    )]
    #[OA\Parameter(
        name: 'fields.date_to',
        in: 'query',
        required: false,
        description: 'End date (ISO 8601)',
        schema: new OA\Schema(type: 'string', format: 'date-time')
    )]
    #[OA\Parameter(
        name: 'fields.download_product',
        in: 'query',
        required: false,
        description: 'Product type to download',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'fields.editorial_video_types[]',
        in: 'query',
        required: false,
        description: 'Editorial video types (raw, produced)',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['raw', 'produced']))
    )]
    #[OA\Parameter(
        name: 'fields.event_ids[]',
        in: 'query',
        required: false,
        description: 'Filter by event IDs',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'))
    )]
    #[OA\Parameter(
        name: 'fields.format_available',
        in: 'query',
        required: false,
        description: 'Available video format',
        schema: new OA\Schema(type: 'string', enum: ['sd', 'hd', '4k', 'hd_web'])
    )]
    #[OA\Parameter(
        name: 'fields.frame_rates[]',
        in: 'query',
        required: false,
        description: 'Video frame rates',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string'))
    )]
    #[OA\Parameter(
        name: 'fields.image_techniques[]',
        in: 'query',
        required: false,
        description: 'Image techniques',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string'))
    )]
    #[OA\Parameter(
        name: 'fields.include_related_searches',
        in: 'query',
        required: false,
        description: 'Whether to include related searches',
        schema: new OA\Schema(type: 'boolean')
    )]
    #[OA\Parameter(
        name: 'fields.keyword_ids[]',
        in: 'query',
        required: false,
        description: 'Keyword ID filters',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'))
    )]
    #[OA\Parameter(
        name: 'fields.min_clip_length',
        in: 'query',
        required: false,
        description: 'Minimum video length (seconds)',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'fields.max_clip_length',
        in: 'query',
        required: false,
        description: 'Maximum video length (seconds)',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'fields.orientations[]',
        in: 'query',
        required: false,
        description: 'Video orientations',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['horizontal', 'vertical']))
    )]
    #[OA\Parameter(
        name: 'fields.page',
        in: 'query',
        required: false,
        description: 'Page number (starting from 1)',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'fields.page_size',
        in: 'query',
        required: false,
        description: 'Page size (max 100)',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'fields.specific_people[]',
        in: 'query',
        required: false,
        description: 'Filter by specific people',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string'))
    )]
    #[OA\Parameter(
        name: 'fields.release_status',
        in: 'query',
        required: false,
        description: 'Release status',
        schema: new OA\Schema(type: 'string', enum: ['release_not_important', 'full_eleased'])
    )]
    #[OA\Parameter(
        name: 'fields.facet_fields[]',
        in: 'query',
        required: false,
        description: 'Facets to return in response',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['artists', 'events', 'locations', 'specific_people']))
    )]
    #[OA\Parameter(
        name: 'fields.include_facets',
        in: 'query',
        required: false,
        description: 'Include facets in response',
        schema: new OA\Schema(type: 'boolean')
    )]
    #[OA\Parameter(
        name: 'fields.facet_max_count',
        in: 'query',
        required: false,
        description: 'Max number of facets to return per type (default is 300)',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'fields.viewpoints[]',
        in: 'query',
        required: false,
        description: 'Viewpoint filter',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string'))
    )]
    #[OA\Response(
        response: 200,
        description: 'Editorial video search results',
        content: new OA\JsonContent(
            example: [
                'videos' => [
                    [
                        'editorial_segments' => ['string'],
                        'editorial_source' => ['id' => 0],
                        'era' => 'string',
                        'event_ids' => [0],
                        'people' => ['string'],
                        'source' => 'string',
                        'id' => 'string',
                        'allowed_use' => [
                            'how_can_i_use_it' => 'string',
                            'release_info' => 'string',
                            'usage_restrictions' => ['string']
                        ],
                        'alternative_ids' => [
                            'additionalProp1' => 'string',
                            'additionalProp2' => 'string',
                            'additionalProp3' => 'string'
                        ],
                        'artist' => 'string',
                        'aspect_ratio' => 'string',
                        'asset_family' => 'string',
                        'asset_type' => 'string',
                        'call_for_image' => true,
                        'caption' => 'string',
                        'clip_length' => 'string',
                        'collection_id' => 0,
                        'collection_code' => 'string',
                        'collection_name' => 'string',
                        'color_type' => 'string',
                        'copyright' => 'string',
                        'date_camera_shot' => '2025-06-23T08:34:41.135Z',
                        'date_created' => '2025-06-23T08:34:41.135Z',
                        'date_submitted' => '2025-06-23T08:34:41.135Z',
                        'display_sizes' => [
                            [
                                'is_watermarked' => true,
                                'name' => 'string',
                                'uri' => 'string',
                                'aspect_ratio' => 'string'
                            ]
                        ],
                        'download_product' => 'string',
                        'download_sizes' => [
                            [
                                'bit_depth' => 'string',
                                'broadcast_video_standard' => 'string',
                                'compression' => 'string',
                                'content_type' => 'string',
                                'description' => 'string',
                                'downloads' => [
                                    [
                                        'product_id' => 'string',
                                        'product_type' => 'string',
                                        'uri' => 'string',
                                        'agreement_name' => 'string'
                                    ]
                                ],
                                'format' => 'string',
                                'frame_rate' => 0,
                                'frame_size' => 'string',
                                'height' => 0,
                                'interlaced' => true,
                                'bytes' => 0,
                                'name' => 'string',
                                'width' => 0
                            ]
                        ],
                        'istock_collection' => 'string',
                        'keywords' => [
                            [
                                'keyword_id' => 'string',
                                'text' => 'string',
                                'type' => 'string',
                                'relevance' => 0
                            ]
                        ],
                        'largest_downloads' => [
                            [
                                'product_id' => 'string',
                                'product_type' => 'string',
                                'uri' => 'string',
                                'agreement_name' => 'string'
                            ]
                        ],
                        'license_model' => 'string',
                        'mastered_to' => 'string',
                        'max_dimensions' => [
                            'height' => 0,
                            'width' => 0
                        ],
                        'orientation' => 'string',
                        'originally_shot_on' => 'string',
                        'product_types' => ['string'],
                        'quality_rank' => 0,
                        'referral_destinations' => [
                            [
                                'site_name' => 'string',
                                'uri' => 'string'
                            ]
                        ],
                        'shot_speed' => 'string',
                        'title' => 'string',
                        'istock_licenses' => [
                            [
                                'license_type' => 'Standard',
                                'credits' => 0
                            ]
                        ],
                        'object_name' => 'string'
                    ]
                ],
                'facets' => [
                    'specific_people' => [['id' => 0, 'name' => 'string']],
                    'events' => [['id' => 0, 'name' => 'string', 'date' => '2025-06-23T08:34:41.135Z']],
                    'locations' => [['id' => 0, 'name' => 'string']],
                    'artists' => [['name' => 'string']],
                    'entertainment' => [['id' => 0, 'name' => 'string']]
                ],
                'related_searches' => [
                    [
                        'phrase' => 'string',
                        'url' => 'string'
                    ]
                ],
                'result_count' => 0,
                'auto_corrections' => [
                    'phrase' => 'string'
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
    public function videoSearchEditorial(VideoSearchEditorialRequest $request): JsonResponse
    {
        $data = VideoSearchEditorialData::from($request->validated());
        $result = $this->service->searchVideosEditorial($data);
        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/gettyimages/ai_image/remove-background',
        summary: 'Remove background from an image',
        description: 'Removes the background of an image using a reference asset ID or previously generated image.',
        tags: ["Getty Images"],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["reference_asset_id"],
            properties: [
                new OA\Property(
                    property: "reference_asset_id",
                    type: "string",
                    example: "abcde-12345"
                ),
                new OA\Property(
                    property: "reference_generation",
                    type: "object",
                    nullable: true,
                    properties: [
                        new OA\Property(property: "generation_request_id", type: "string", example: "req-789"),
                        new OA\Property(property: "index", type: "integer", example: 0),
                    ]
                ),
                new OA\Property(
                    property: "product_id",
                    type: "integer",
                    nullable: true,
                    example: 101
                ),
                new OA\Property(
                    property: "project_code",
                    type: "string",
                    nullable: true,
                    example: "internal-use"
                ),
                new OA\Property(
                    property: "notes",
                    type: "string",
                    nullable: true,
                    example: "Remove only solid backgrounds."
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Background removed successfully',
        content: new OA\JsonContent(
            example: [
                'status' => 'success',
                'data' => [
                    'generation_request_id' => 'bg-remove-001',
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
                'message' => [
                    'reference_asset_id' => ['The reference_asset_id field is required.']
                ]
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while processing your request.'
            ]
        )
    )]
    public function removeBackground(RemoveBackgroundRequest $request): JsonResponse
    {
        $data = RemoveBackgroundData::from($request->validated());
        $result = $this->service->removeBackground($data);
        return $this->logAndResponse($result);
    }

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
            enum: ['cs', 'de', 'en-GB', 'en-US', 'es', 'fi', 'fr', 'hu', 'id', 'it', 'ja', 'ko', 'nl', 'pl', 'pt-BR', 'pt-PT', 'ro', 'ru', 'sv', 'th', 'tr', 'uk', 'vi', 'zh-HK'],
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
            enum: ['cs', 'de', 'en-GB', 'en-US', 'es', 'fi', 'fr', 'hu', 'id', 'it', 'ja', 'ko', 'nl', 'pl', 'pt-BR', 'pt-PT', 'ro', 'ru', 'sv', 'th', 'tr', 'uk', 'vi', 'zh-HK'],
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

    #[OA\Post(
        path: '/api/gettyimages/ai_generate/image-generation',
        summary: 'Generate AI Image',
        description: 'Initiate AI image generation using Getty Images AI engine. Supports various filters like mood, media type, aspect ratio, and seed.',
        tags: ['Getty Images'],
        security: [['authentication' => []]],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            required: [],
            properties: [
                new OA\Property(
                    property: 'prompt',
                    type: 'string',
                    nullable: true,
                    description: 'The prompt or instruction for the image to be generated',
                    example: 'A mountain landscape with fog'
                ),
                new OA\Property(
                    property: 'seed',
                    type: 'integer',
                    nullable: true,
                    description: 'Optional seed to reproduce generation',
                    example: 42
                ),
                new OA\Property(
                    property: 'aspect_ratio',
                    type: 'string',
                    nullable: true,
                    description: 'Optional aspect ratio of the image',
                    example: '16:9'
                ),
                new OA\Property(
                    property: 'media_type',
                    type: 'string',
                    nullable: true,
                    description: 'Media type of the image',
                    enum: ['photography'],
                    example: 'photography'
                ),
                new OA\Property(
                    property: 'mood',
                    type: 'string',
                    nullable: true,
                    description: 'Mood filter to apply on image generation',
                    enum: ['black_and_white', 'bold', 'cool', 'dramatic', 'natural', 'vivid', 'warm'],
                    example: 'vivid'
                ),
                new OA\Property(
                    property: 'product_id',
                    type: 'integer',
                    nullable: true,
                    description: 'Product ID the image is associated with',
                    example: 123
                ),
                new OA\Property(
                    property: 'project_code',
                    type: 'string',
                    nullable: true,
                    description: 'Project code used for grouping generations',
                    example: 'PROJ-2025-IMG'
                ),
                new OA\Property(
                    property: 'notes',
                    type: 'string',
                    nullable: true,
                    description: 'Additional notes related to the image generation request',
                    example: 'Sample generation for marketing team review'
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Image generation successfully started',
        content: new OA\JsonContent(
            example: [
                'generation_request_id' => 'gen_abc123',
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad request due to invalid input',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Validation failed: invalid media_type or mood value',
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Internal server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while processing the request',
            ]
        )
    )]

    public function imageGeneration(ImageGenerationRequest $request): JsonResponse
    {
        $data = ImageGenerationData::from($request->validated());

        $result = $this->service->imageGeneration($data);

        return $this->logAndResponse($result);
    }


    #[OA\Post(
        path: '/api/gettyimages/ai_generate/image-generation/{generationRequestId}',
        summary: 'Get Image Generation Details',
        description: 'Retrieve the details of a previously submitted image generation request using its generationRequestId.',
        tags: ['Getty Images'],
        security: [['authentication' => []]]
    )]
    #[OA\Parameter(
        name: 'generationRequestId',
        in: 'path',
        required: true,
        description: 'The unique identifier of the image generation request',
        schema: new OA\Schema(type: 'string', example: 'gen_abc123')
    )]
    #[OA\Response(
        response: 200,
        description: 'Image generation details retrieved successfully',
        content: new OA\JsonContent(
            example: [
                'generation_request_id' => 'gen_abc123',
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Image generation request not found',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Generation request ID not found'
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error while retrieving generation details',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An unexpected error occurred'
            ]
        )
    )]
    public function getImageGeneration(string $generationRequestId): JsonResponse
    {
        $result = $this->service->getImageGeneration($generationRequestId);
        return $this->logAndResponse($result);
    }


    #[OA\Get(
        path: '/api/gettyimages/ai_generate/image-generation/{generationRequestId}/images/{index}/variations',
        summary: 'Get Image Variations',
        description: 'Retrieve variations for a specific image generated from a previous generation request.',
        tags: ['Getty Images'],
        security: [['authentication' => []]]
    )]
    #[OA\Parameter(
        name: 'generationRequestId',
        in: 'path',
        required: true,
        description: 'The unique identifier of the image generation request',
        schema: new OA\Schema(type: 'string', example: 'gen_abc123')
    )]
    #[OA\Parameter(
        name: 'index',
        in: 'path',
        required: true,
        description: 'Index of the generated image to get variations for',
        schema: new OA\Schema(type: 'integer', example: 0)
    )]
    #[OA\RequestBody(
        required: false,
        content: new OA\JsonContent(
            required: [],
            properties: [
                new OA\Property(
                    property: 'product_id',
                    type: 'integer',
                    nullable: true,
                    example: 123
                ),
                new OA\Property(
                    property: 'project_code',
                    type: 'string',
                    nullable: true,
                    example: 'PROJ-456'
                ),
                new OA\Property(
                    property: 'notes',
                    type: 'string',
                    nullable: true,
                    example: 'Need 3 more variations for internal review'
                )
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Image variations retrieved successfully',
        content: new OA\JsonContent(
            example: [
                'status' => 'success',
                'data' => [
                    [
                        'index' => 1,
                        'url' => 'https://cdn.gettyimages.ai/generated/variation1.jpg',
                        'thumbnail_url' => 'https://cdn.gettyimages.ai/generated/thumb1.jpg'
                    ],
                    [
                        'index' => 2,
                        'url' => 'https://cdn.gettyimages.ai/generated/variation2.jpg',
                        'thumbnail_url' => 'https://cdn.gettyimages.ai/generated/thumb2.jpg'
                    ]
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
                'message' => 'Invalid or missing parameters.'
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Image generation or image index not found',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Generation request or image not found.'
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An unexpected error occurred'
            ]
        )
    )]

    public function imageVariations(string $generationRequestId, int $index, ImageVariationsRequest $request): JsonResponse
    {
        $data = ImageVariationsData::from($request->validated());

        $result = $this->service->getImageVariations($generationRequestId, $index, $data);
        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/gettyimages/ai_generate/image-generation/refine',
        summary: 'Refine an Image',
        description: 'Refines an existing AI-generated image using reference asset ID and additional parameters such as mask and prompt.',
        tags: ['Getty Images'],
        security: [['authentication' => []]]
    )]
    #[OA\RequestBody(
        required: false,
        content: new OA\JsonContent(
            required: [],
            properties: [
                new OA\Property(
                    property: 'reference_asset_id',
                    type: 'string',
                    nullable: true,
                    example: 'asset_abc123'
                ),
                new OA\Property(
                    property: 'reference_generation',
                    type: 'object',
                    nullable: true,
                    properties: [
                        new OA\Property(
                            property: 'generation_request_id',
                            type: 'string',
                            example: 'gen_xyz789'
                        ),
                        new OA\Property(
                            property: 'index',
                            type: 'integer',
                            example: 0
                        )
                    ]
                ),
                new OA\Property(
                    property: 'prompt',
                    type: 'string',
                    nullable: true,
                    example: 'Enhance details around the eyes'
                ),
                new OA\Property(
                    property: 'product_id',
                    type: 'integer',
                    nullable: true,
                    example: 101
                ),
                new OA\Property(
                    property: 'project_code',
                    type: 'string',
                    nullable: true,
                    example: 'PROJ-2025'
                ),
                new OA\Property(
                    property: 'notes',
                    type: 'string',
                    nullable: true,
                    example: 'Client requested finer detail around facial features'
                ),
                new OA\Property(
                    property: 'mask_url',
                    type: 'string',
                    format: 'url',
                    nullable: true,
                    example: 'https://cdn.gettyimages.ai/masks/region-mask.png'
                )
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Image refinement successful',
        content: new OA\JsonContent(
            example: [
                'generation_request_id' => 'gen_xyz789',
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Invalid parameters',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'The mask_url must be a valid URL.'
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Internal server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Something went wrong while refining the image.'
            ]
        )
    )]

    public function refineImage(RefineImageRequest $request): JsonResponse
    {
        $data = RefineImageData::from($request->validated());
        $result = $this->service->refineImage($data);
        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/gettyimages/ai_generate/image-generation/extend',
        summary: 'Extend an Image',
        description: 'Extends a generated image using a reference asset and optional prompt with control over left, right, top, and bottom extension percentages.',
        tags: ['Getty Images'],
        security: [['authentication' => []]]
    )]
    #[OA\RequestBody(
        required: false,
        content: new OA\JsonContent(
            required: [],
            properties: [
                new OA\Property(
                    property: 'reference_asset_id',
                    type: 'string',
                    nullable: true,
                    example: 'asset_00123xyz'
                ),
                new OA\Property(
                    property: 'reference_generation',
                    type: 'object',
                    nullable: true,
                    properties: [
                        new OA\Property(
                            property: 'generation_request_id',
                            type: 'string',
                            example: 'gen_98765abc'
                        ),
                        new OA\Property(
                            property: 'index',
                            type: 'integer',
                            example: 1
                        )
                    ]
                ),
                new OA\Property(
                    property: 'prompt',
                    type: 'string',
                    nullable: true,
                    example: 'Extend the background to the left and right with more forest'
                ),
                new OA\Property(
                    property: 'product_id',
                    type: 'integer',
                    nullable: true,
                    example: 123
                ),
                new OA\Property(
                    property: 'project_code',
                    type: 'string',
                    nullable: true,
                    example: 'EXT-PROJ-2025'
                ),
                new OA\Property(
                    property: 'notes',
                    type: 'string',
                    nullable: true,
                    example: 'This is for the billboard extension request'
                ),
                new OA\Property(
                    property: 'left_percentage',
                    type: 'number',
                    nullable: true,
                    example: 10
                ),
                new OA\Property(
                    property: 'right_percentage',
                    type: 'number',
                    nullable: true,
                    example: 15
                ),
                new OA\Property(
                    property: 'top_percentage',
                    type: 'number',
                    nullable: true,
                    example: 5
                ),
                new OA\Property(
                    property: 'bottom_percentage',
                    type: 'number',
                    nullable: true,
                    example: 5
                )
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Image extension generated successfully',
        content: new OA\JsonContent(
            example: [
                'generation_request_id' => 'gen_98765abc',
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'The left_percentage must be a numeric value.'
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Failed to extend the image due to an internal server error.'
            ]
        )
    )]
    public function extendImage(ExtendImageRequest $request): JsonResponse
    {
        $data = ExtendImageData::from($request->validated());

        $result = $this->service->extendImage($data);

        return $this->logAndResponse($result);
    }


    #[OA\Post(
        path: '/api/gettyimages/ai_generate/image-generation/object-removal',
        summary: 'Remove Object from Image',
        description: 'Removes an object from a generated image using a mask image URL. Accepts reference asset and generation metadata.',
        tags: ['Getty Images'],
        security: [['authentication' => []]]
    )]
    #[OA\RequestBody(
        required: false,
        content: new OA\JsonContent(
            required: [],
            properties: [
                new OA\Property(
                    property: 'reference_asset_id',
                    type: 'string',
                    nullable: true,
                    example: 'asset_abc123'
                ),
                new OA\Property(
                    property: 'reference_generation',
                    type: 'object',
                    nullable: true,
                    properties: [
                        new OA\Property(
                            property: 'generation_request_id',
                            type: 'string',
                            example: 'gen_xyz789'
                        ),
                        new OA\Property(
                            property: 'index',
                            type: 'integer',
                            example: 2
                        )
                    ]
                ),
                new OA\Property(
                    property: 'product_id',
                    type: 'integer',
                    nullable: true,
                    example: 101
                ),
                new OA\Property(
                    property: 'project_code',
                    type: 'string',
                    nullable: true,
                    example: 'OBJ-REM-2025'
                ),
                new OA\Property(
                    property: 'notes',
                    type: 'string',
                    nullable: true,
                    example: 'Removing person from background'
                ),
                new OA\Property(
                    property: 'mask_url',
                    type: 'string',
                    format: 'url',
                    nullable: true,
                    example: 'https://cdn.getty.ai/masks/mask-1.png'
                )
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Object removal completed successfully',
        content: new OA\JsonContent(
            example: [
                'generation_request_id' => 'gen_xyz789',
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'The mask_url must be a valid URL.'
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Internal server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while removing the object from the image.'
            ]
        )
    )]

    public function removeObjectFromImage(RemoveObjectFromImageRequest $request): JsonResponse
    {
        $data = RemoveObjectFromImageData::from($request->validated());

        $result = $this->service->removeObjectFromImage($data);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/gettyimages/ai_generate/image-generation/background-replacement',
        summary: 'Replace Background of Image',
        description: 'Replaces the background of a generated image using AI. Allows controlling prompt, media type, seed, and background color.',
        tags: ['Getty Images'],
        security: [['authentication' => []]]
    )]
    #[OA\RequestBody(
        required: false,
        content: new OA\JsonContent(
            required: [],
            properties: [
                new OA\Property(
                    property: 'prompt',
                    type: 'string',
                    nullable: true,
                    example: 'A scenic beach with sunrise'
                ),
                new OA\Property(
                    property: 'reference_asset_id',
                    type: 'string',
                    nullable: true,
                    example: 'asset_123456'
                ),
                new OA\Property(
                    property: 'reference_generation',
                    type: 'object',
                    nullable: true,
                    properties: [
                        new OA\Property(
                            property: 'generation_request_id',
                            type: 'string',
                            example: 'gen_abc789'
                        ),
                        new OA\Property(
                            property: 'index',
                            type: 'integer',
                            example: 0
                        )
                    ]
                ),
                new OA\Property(
                    property: 'product_id',
                    type: 'integer',
                    nullable: true,
                    example: 42
                ),
                new OA\Property(
                    property: 'media_type',
                    type: 'string',
                    enum: ['photography', 'illustration', 'vector'],
                    nullable: true,
                    example: 'photography'
                ),
                new OA\Property(
                    property: 'negative_prompt',
                    type: 'string',
                    nullable: true,
                    example: 'blurry, distorted'
                ),
                new OA\Property(
                    property: 'seed',
                    type: 'integer',
                    nullable: true,
                    example: 1234
                ),
                new OA\Property(
                    property: 'background_color',
                    type: 'string',
                    nullable: true,
                    example: '#ffffff'
                ),
                new OA\Property(
                    property: 'project_code',
                    type: 'string',
                    nullable: true,
                    example: 'BG-REP-001'
                ),
                new OA\Property(
                    property: 'notes',
                    type: 'string',
                    nullable: true,
                    example: 'Replace sky background with beach scene.'
                )
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Background replacement completed successfully',
        content: new OA\JsonContent(
            example: [
                'generation_request_id' => 'gen_abc789',
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Invalid media_type. Must be one of: photography, illustration, vector.'
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while replacing the background of the image.'
            ]
        )
    )]

    public function replaceBackground(ReplaceBackgroundRequest $request): JsonResponse
    {
        $data = ReplaceBackgroundData::from($request->validated());

        $result = $this->service->replaceBackground($data);

        return $this->logAndResponse($result);
    }


    #[OA\Post(
        path: '/api/gettyimages/ai_generate/image-generation/influence-color-by-image',
        summary: 'Influence Color by Reference Image',
        description: 'Generates an image influenced by the color of a reference image. Accepts options such as noise level, media type, and generation seed.',
        tags: ['Getty Images'],
        security: [['authentication' => []]]
    )]
    #[OA\RequestBody(
        required: false,
        content: new OA\JsonContent(
            required: [],
            properties: [
                new OA\Property(
                    property: 'reference_asset_id',
                    type: 'string',
                    nullable: true,
                    example: 'asset_78910'
                ),
                new OA\Property(
                    property: 'reference_generation',
                    type: 'object',
                    nullable: true,
                    properties: [
                        new OA\Property(
                            property: 'generation_request_id',
                            type: 'string',
                            example: 'gen_xyz123'
                        ),
                        new OA\Property(
                            property: 'index',
                            type: 'integer',
                            example: 1
                        )
                    ]
                ),
                new OA\Property(
                    property: 'reference_file_registration_id',
                    type: 'string',
                    nullable: true,
                    example: 'file_reg_23456'
                ),
                new OA\Property(
                    property: 'prompt',
                    type: 'string',
                    nullable: true,
                    example: 'A futuristic cityscape at dusk'
                ),
                new OA\Property(
                    property: 'noise_level',
                    type: 'integer',
                    nullable: true,
                    example: 5
                ),
                new OA\Property(
                    property: 'media_type',
                    type: 'string',
                    enum: ['photography', 'illustration', 'vector'],
                    nullable: true,
                    example: 'illustration'
                ),
                new OA\Property(
                    property: 'seed',
                    type: 'integer',
                    nullable: true,
                    example: 987654
                ),
                new OA\Property(
                    property: 'product_id',
                    type: 'integer',
                    nullable: true,
                    example: 112
                ),
                new OA\Property(
                    property: 'project_code',
                    type: 'string',
                    nullable: true,
                    example: 'COLOR-INFL-2025'
                ),
                new OA\Property(
                    property: 'notes',
                    type: 'string',
                    nullable: true,
                    example: 'Use pastel tones from reference image'
                )
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successfully generated image with color influence',
        content: new OA\JsonContent(
            example: [
                'generation_request_id' => 'gen_xyz123',
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Invalid noise_level or media_type',
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while processing the color influence request.'
            ]
        )
    )]

    public function influenceColorByImage(InfluenceColorByImageRequest $request): JsonResponse
    {
        $data = InfluenceColorByImageData::from($request->validated());

        $result = $this->service->influenceColorByImage($data);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/gettyimages/ai_generate/image-generation/influence-composition-by-image',
        summary: 'Influence Composition by Reference Image',
        description: 'Generates an image whose composition is influenced by a reference image. Includes options like influence level, media type, mood, and seed.',
        tags: ['Getty Images'],
        security: [['authentication' => []]]
    )]
    #[OA\RequestBody(
        required: false,
        content: new OA\JsonContent(
            required: [],
            properties: [
                new OA\Property(property: 'reference_asset_id', type: 'string', nullable: true, example: 'asset_1234'),
                new OA\Property(
                    property: 'reference_generation',
                    type: 'object',
                    nullable: true,
                    properties: [
                        new OA\Property(property: 'generation_request_id', type: 'string', example: 'gen_5678'),
                        new OA\Property(property: 'index', type: 'integer', example: 0)
                    ]
                ),
                new OA\Property(property: 'reference_file_registration_id', type: 'string', nullable: true, example: 'file_reg_8910'),
                new OA\Property(property: 'prompt', type: 'string', nullable: true, example: 'A minimalistic interior design with plants'),
                new OA\Property(property: 'influence_level', type: 'integer', minimum: 0, nullable: true, example: 3),
                new OA\Property(property: 'media_type', type: 'string', enum: ['photography', 'illustration', 'vector'], nullable: true, example: 'vector'),
                new OA\Property(property: 'mood', type: 'string', nullable: true, example: 'natural'),
                new OA\Property(property: 'seed', type: 'integer', nullable: true, example: 123456),
                new OA\Property(property: 'product_id', type: 'integer', nullable: true, example: 42),
                new OA\Property(property: 'project_code', type: 'string', nullable: true, example: 'COMP-INF-2025'),
                new OA\Property(property: 'notes', type: 'string', nullable: true, example: 'Make sure the lighting matches the reference')
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successfully generated composition-influenced image',
        content: new OA\JsonContent(
            example: [
                'generation_request_id' => 'gen_5678',
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Invalid influence_level or unsupported mood value',
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while processing the composition influence request.'
            ]
        )
    )]

    public function influenceCompositionByImage(InfluenceCompositionByImageRequest $request): JsonResponse
    {
        $data = InfluenceCompositionByImageData::from($request->validated());

        $result = $this->service->influenceCompositionByImage($data);

        return $this->logAndResponse($result);
    }


    #[OA\Post(
        path: '/api/gettyimages/ai_generate/image-generation/background-generations',
        summary: 'Generate Backgrounds',
        description: 'Generates background images based on a prompt and optional positioning percentages.',
        tags: ['Getty Images'],
        security: [['authentication' => []]]
    )]
    #[OA\RequestBody(
        required: false,
        content: new OA\JsonContent(
            required: [],
            properties: [
                new OA\Property(property: 'reference_file_registration_id', type: 'string', nullable: true, example: 'ref_file_9876'),
                new OA\Property(property: 'prompt', type: 'string', nullable: true, example: 'A tropical beach with palm trees'),
                new OA\Property(property: 'product_id', type: 'integer', nullable: true, example: 123),
                new OA\Property(property: 'project_code', type: 'string', nullable: true, example: 'BG-2025-001'),
                new OA\Property(property: 'notes', type: 'string', nullable: true, example: 'Use vibrant colors'),
                new OA\Property(property: 'left_percentage', type: 'number', format: 'float', minimum: 0, maximum: 100, nullable: true, example: 10.5),
                new OA\Property(property: 'right_percentage', type: 'number', format: 'float', minimum: 0, maximum: 100, nullable: true, example: 12.0),
                new OA\Property(property: 'top_percentage', type: 'number', format: 'float', minimum: 0, maximum: 100, nullable: true, example: 5.0),
                new OA\Property(property: 'bottom_percentage', type: 'number', format: 'float', minimum: 0, maximum: 100, nullable: true, example: 8.5)
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Background generation success',
        content: new OA\JsonContent(
            example: [
                "generation_request_id" => "string"
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'left_percentage must be between 0 and 100.',
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while generating the background image.'
            ]
        )
    )]
    public function generateBackgrounds(GenerateBackgroundsRequest $request): JsonResponse
    {
        $data = GenerateBackgroundsData::from($request->validated());
        $result = $this->service->generateBackgrounds($data);
        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/gettyimages/ai_generate/image-generation/{generationRequestId}/images/{index}/download-sizes',
        summary: 'Get Download Sizes',
        description: 'Retrieve available download sizes for a generated image.',
        tags: ['Getty Images'],
        security: [['authentication' => []]]
    )]
    #[OA\Parameter(
        name: 'generationRequestId',
        in: 'path',
        required: true,
        description: 'The unique identifier of the generation request.',
        schema: new OA\Schema(type: 'string', example: 'gen_abc123')
    )]
    #[OA\Parameter(
        name: 'index',
        in: 'path',
        required: true,
        description: 'Index of the generated image within the generation request.',
        schema: new OA\Schema(type: 'integer', example: 0)
    )]
    #[OA\Response(
        response: 200,
        description: 'Available download sizes',
        content: new OA\JsonContent(
            example: [
                'status' => 'success',
                'data' => [
                    'sizes' => [
                        ['label' => 'small', 'width' => 640, 'height' => 480, 'url' => 'https://cdn.getty.ai/images/small.jpg'],
                        ['label' => 'medium', 'width' => 1280, 'height' => 960, 'url' => 'https://cdn.getty.ai/images/medium.jpg'],
                        ['label' => 'large', 'width' => 1920, 'height' => 1080, 'url' => 'https://cdn.getty.ai/images/large.jpg'],
                    ]
                ]
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Image not found or invalid index',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'No image found for the given generation request ID and index.'
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Internal server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while retrieving download sizes.'
            ]
        )
    )]

    public function getDownloadSizes(string $generationRequestId, int $index): JsonResponse
    {
        $result = $this->service->getDownloadSizes($generationRequestId, $index);
        return $this->logAndResponse($result);
    }

    #[OA\Put(
        path: '/api/gettyimages/ai_generate/image-generation/{generationRequestId}/images/{index}/download',
        summary: 'Async Download Image',
        description: 'Initiate an asynchronous download for a generated image with optional metadata.',
        tags: ['Getty Images'],
        security: [['authentication' => []]]
    )]
    #[OA\Parameter(
        name: 'generationRequestId',
        in: 'path',
        required: true,
        description: 'The generation request ID for the image.',
        schema: new OA\Schema(type: 'string', example: 'gen_abc123')
    )]
    #[OA\Parameter(
        name: 'index',
        in: 'path',
        required: true,
        description: 'The index of the image in the generation request.',
        schema: new OA\Schema(type: 'integer', example: 0)
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: [],
            properties: [
                new OA\Property(property: 'notes', type: 'string', nullable: true, example: 'Download for campaign'),
                new OA\Property(property: 'project_code', type: 'string', nullable: true, example: 'PRJ202506'),
                new OA\Property(property: 'size_name', type: 'string', nullable: true, example: 'medium'),
                new OA\Property(property: 'product_id', type: 'integer', nullable: true, example: 1001)
            ]
        )
    )]
    #[OA\Response(
        response: 202,
        description: 'Image download request accepted',
        content: new OA\JsonContent(
            example: [
                'url' => 'https://cdn.gettyimages.com/images/abc123.jpg',
                "generated_asset_id" => "string"
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Invalid input',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Invalid data provided. Check your input fields.'
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An unexpected error occurred while processing the download request.'
            ]
        )
    )]
    public function downloadImageAsync(string $generationRequestId, int $index, DownloadImageAsyncRequest $request): JsonResponse
    {
        $data = DownloadImageAsyncData::from($request->validated());
        $result = $this->service->downloadImageAsync($generationRequestId, $index, $data);
        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/gettyimages/ai_generate/image-generation/{generationRequestId}/images/{index}/download',
        summary: 'Download Image',
        description: 'Download a generated image by generationRequestId and index.',
        tags: ['Getty Images'],
        security: [['authentication' => []]]
    )]
    #[OA\Parameter(
        name: 'generationRequestId',
        in: 'path',
        required: true,
        description: 'The generation request ID.',
        schema: new OA\Schema(type: 'string', example: 'gen_xyz789')
    )]
    #[OA\Parameter(
        name: 'index',
        in: 'path',
        required: true,
        description: 'The index of the image in the generation response.',
        schema: new OA\Schema(type: 'integer', example: 0)
    )]
    #[OA\Response(
        response: 200,
        description: 'Image download successful',
        content: new OA\JsonContent(
            example: [
                'url' => 'https://cdn.gettyimages.com/images/abc123.jpg',
                "generated_asset_id" => "string"
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Image not found',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'Image not found for the given generationRequestId and index.'
            ]
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Internal server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An unexpected error occurred while retrieving the image.'
            ]
        )
    )]

    public function downloadImage(string $generationRequestId, int $index): JsonResponse
    {
        $result = $this->service->downloadImageByIndex($generationRequestId, $index);
        return $this->logAndResponse($result);
    }
}
