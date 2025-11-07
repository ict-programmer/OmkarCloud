<?php

namespace App\Http\Controllers;

use App\Data\Request\Freepik\AiImageClassifierData;
use App\Data\Request\Freepik\ClassicFastGenerateData;
use App\Data\Request\Freepik\DownloadResourceFormatData;
use App\Data\Request\Freepik\FluxDevGenerateData;
use App\Data\Request\Freepik\IconGenerationData;
use App\Data\Request\Freepik\ImageExpandFluxProData;
use App\Data\Request\Freepik\Imagen3GenerateData;
use App\Data\Request\Freepik\KlingElementsVideoData;
use App\Data\Request\Freepik\KlingImageToVideoData;
use App\Data\Request\Freepik\KlingTextToVideoData;
use App\Data\Request\Freepik\LoraCharacterTrainData;
use App\Data\Request\Freepik\LoraStyleTrainData;
use App\Data\Request\Freepik\MysticGenerateData;
use App\Data\Request\Freepik\ReimagineFluxData;
use App\Data\Request\Freepik\RelightImageData;
use App\Data\Request\Freepik\RemoveBackgroundData;
use App\Data\Request\Freepik\StockContentData;
use App\Data\Request\Freepik\StyleTransferData;
use App\Data\Request\Freepik\UpscaleImageData;
use App\Http\Requests\Freepik\AiImageClassifierRequest;
use App\Http\Requests\Freepik\ClassicFastGenerateRequest;
use App\Http\Requests\Freepik\DownloadResourceFormatRequest;
use App\Http\Requests\Freepik\FluxDevGenerateRequest;
use App\Http\Requests\Freepik\IconGenerationRequest;
use App\Http\Requests\Freepik\ImageExpandFluxProRequest;
use App\Http\Requests\Freepik\Imagen3GenerateRequest;
use App\Http\Requests\Freepik\KlingElementsVideoRequest;
use App\Http\Requests\Freepik\KlingImageToVideoRequest;
use App\Http\Requests\Freepik\KlingImageToVideoStatusRequest;
use App\Http\Requests\Freepik\KlingTextToVideoRequest;
use App\Http\Requests\Freepik\LoraCharacterTrainRequest;
use App\Http\Requests\Freepik\LoraStyleTrainRequest;
use App\Http\Requests\Freepik\MysticGenerateRequest;
use App\Http\Requests\Freepik\ReimagineFluxRequest;
use App\Http\Requests\Freepik\RelightImageRequest;
use App\Http\Requests\Freepik\RemoveBackgroundRequest;
use App\Http\Requests\Freepik\StockContentRequest;
use App\Http\Requests\Freepik\StyleTransferRequest;
use App\Http\Requests\Freepik\UpscaleImageRequest;
use App\Services\FreepikService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class FreepikController extends BaseController
{
    public function __construct(protected FreepikService $service) {}

    #[OA\Get(
        path: '/api/freepik/stock_content',
        operationId: 'stockContent',
        description: 'Get stock resources from Freepik with full filtering support',
        summary: 'Freepik stock content endpoint',
        tags: ['Freepik'],
    )]
    #[OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'limit', in: 'query', required: true, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100, example: 20))]
    #[OA\Parameter(name: 'order', in: 'query', required: true, schema: new OA\Schema(type: 'string', enum: ['relevance', 'recent']))]
    #[OA\Parameter(name: 'term', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'slug', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]

    #[OA\Parameter(name: 'filters[orientation][landscape]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]
    #[OA\Parameter(name: 'filters[orientation][portrait]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]
    #[OA\Parameter(name: 'filters[orientation][square]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]
    #[OA\Parameter(name: 'filters[orientation][panoramic]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]

    #[OA\Parameter(name: 'filters[content_type][photo]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]
    #[OA\Parameter(name: 'filters[content_type][psd]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]
    #[OA\Parameter(name: 'filters[content_type][vector]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]

    #[OA\Parameter(name: 'filters[license][freemium]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]
    #[OA\Parameter(name: 'filters[license][premium]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]

    #[OA\Parameter(name: 'filters[people][include]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]
    #[OA\Parameter(name: 'filters[people][exclude]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]
    #[OA\Parameter(name: 'filters[people][number]', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['1', '2', '3', 'more_than_three']))]
    #[OA\Parameter(name: 'filters[people][age]', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['infant', 'child', 'teen', 'young-adult', 'adult', 'senior', 'elder']))]
    #[OA\Parameter(name: 'filters[people][gender]', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['male', 'female']))]
    #[OA\Parameter(name: 'filters[people][ethnicity]', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['south-asian', 'middle-eastern', 'east-asian', 'black', 'hispanic', 'indian', 'white', 'multiracial', 'southeast-asian']))]

    #[OA\Parameter(name: 'filters[period]', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['last-month', 'last-quarter', 'last-semester', 'last-year']))]
    #[OA\Parameter(name: 'filters[color]', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['black', 'blue', 'gray', 'green', 'orange', 'red', 'white', 'yellow', 'purple', 'cyan', 'pink']))]
    #[OA\Parameter(name: 'filters[author]', in: 'query', required: false, schema: new OA\Schema(type: 'integer'))]

    #[OA\Parameter(name: 'filters[ai-generated][excluded]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]
    #[OA\Parameter(name: 'filters[ai-generated][only]', in: 'query', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1]))]

    #[OA\Parameter(name: 'filters[vector][type]', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['jpg', 'ai', 'eps', 'svg']))]
    #[OA\Parameter(name: 'filters[vector][style]', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['watercolor', 'flat', 'cartoon', 'geometric', 'gradient', 'isometric', '3d', 'hand-drawn']))]

    #[OA\Parameter(name: 'filters[psd][type]', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['jpg', 'psd']))]
    #[OA\Parameter(name: 'filters[ids]', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]

    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'data' => [
                    [
                        'id' => 7663349,
                        'title' => "Father's day event hand drawn style",
                        'url' => 'https://www.freepik.com/free-vector/father-s-day-event-hand-drawn-style_7663349.htm',
                        'filename' => 'fathers-day-event-hand-drawn-style.zip',
                        'licenses' => [
                            [
                                'type' => 'freemium',
                                'url' => 'https://www.freepik.com/profile/license/pdf/7663349?lang=en',
                            ],
                        ],
                        'products' => [
                            [
                                'type' => 'essential',
                                'url' => 'https://www.freepik.com/profile/license/pdf/7663349?lang=en',
                            ],
                        ],
                        'meta' => [
                            'published_at' => '2020-04-15 17:50:35',
                            'is_new' => false,
                            'available_formats' => [
                                'ai' => [
                                    'total' => 1,
                                    'items' => [
                                        [
                                            'size' => 340222,
                                            'id' => 567457,
                                        ],
                                    ],
                                ],
                                'eps' => [
                                    'total' => 1,
                                    'items' => [
                                        [
                                            'size' => 1323126,
                                            'id' => 567458,
                                        ],
                                    ],
                                ],
                                'jpg' => [
                                    'total' => 1,
                                    'items' => [
                                        [
                                            'size' => 1131441,
                                            'id' => 567459,
                                        ],
                                    ],
                                ],
                                'fonts' => [
                                    'total' => 1,
                                    'items' => [
                                        [
                                            'size' => 203,
                                            'id' => 567460,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'image' => [
                            'type' => 'vector',
                            'orientation' => 'square',
                            'source' => [
                                'key' => 'large',
                                'url' => 'https://img.b2bpic.net/free-vector/father-s-day-event-hand-drawn-style_23-2148507324.jpg',
                                'size' => '626x626',
                            ],
                        ],
                        'related' => [
                            'serie' => [],
                            'others' => [],
                            'keywords' => [],
                        ],
                        'stats' => [
                            'downloads' => 7198,
                            'likes' => 91,
                        ],
                        'author' => [
                            'id' => 23,
                            'name' => 'freepik',
                            'avatar' => 'https://avatar.cdnpk.net/23.jpg',
                            'assets' => 6403390,
                            'slug' => 'freepik',
                        ],
                        'active' => true,
                    ],
                ],
                'meta' => [
                    'current_page' => 1,
                    'per_page' => 1,
                    'last_page' => 181651088,
                    'total' => 181651088,
                    'clean_search' => false,
                ],
            ]
        )
    )]
    public function stockContent(StockContentRequest $request)
    {
        $data = StockContentData::from($request->validated());
        $result = $this->service->stockContent($data);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/freepik/resource_detail/{resource_id}',
        operationId: 'resourceDetail',
        description: 'Get detailed info about a specific Freepik resource',
        summary: 'Freepik resource detail',
        tags: ['Freepik'],
    )]
    #[OA\Parameter(
        name: 'resource_id',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'string', example: '7663349')
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'data' => [
                    'id' => 7663349,
                    'name' => 'father\'s day event hand drawn style',
                    'slug' => 'father-s-day-event-hand-drawn-style',
                    'type' => 'vector',
                    'premium' => false,
                    'url' => 'https://www.freepik.com/free-vector/father-s-day-event-hand-drawn-style_7663349.htm',
                    'created' => '2020-04-15T17:50:35Z',
                    'new' => false,
                    'download_size' => 1740322,
                    'author' => [
                        'id' => 23,
                        'name' => 'freepik',
                        'avatar' => 'https://avatar.cdnpk.net/23.jpg',
                        'assets' => 6404687,
                        'slug' => 'freepik',
                    ],
                    'preview' => [
                        'url' => 'https://img.b2bpic.net/free-vector/father-s-day-event-hand-drawn-style_23-2148507324.jpg',
                        'width' => 626,
                        'height' => 626,
                    ],
                    'license' => 'https://www.freepik.com/profile/license/pdf/7663349?lang=en',
                    'available_formats' => [
                        'ai' => [
                            'total' => 1,
                            'items' => [
                                [
                                    'id' => 567457,
                                    'colorspace' => 'RGB',
                                    'name' => '7663349_3725127',
                                    'size' => 340222,
                                ],
                            ],
                        ],
                        'eps' => [
                            'total' => 1,
                            'items' => [
                                [
                                    'id' => 567458,
                                    'colorspace' => 'RGB',
                                    'name' => '7663349_3725128',
                                    'size' => 1323126,
                                ],
                            ],
                        ],
                        'jpg' => [
                            'total' => 1,
                            'items' => [
                                [
                                    'id' => 567459,
                                    'colorspace' => 'UNKNOWN',
                                    'name' => '7663349_3699294',
                                    'size' => 1131441,
                                ],
                            ],
                        ],
                        'fonts' => [
                            'total' => 1,
                            'items' => [
                                [
                                    'id' => 567460,
                                    'colorspace' => 'UNKNOWN',
                                    'name' => '7663349_Fonts',
                                    'size' => 203,
                                ],
                            ],
                        ],
                    ],
                    'related_resources' => [
                        'suggested' => [],
                        'same_series' => null,
                        'same_collection' => null,
                        'same_author' => [],
                        'related_cross_sell' => [],
                        'related_videos' => [
                            [
                                'id' => 3663569,
                                'url' => 'https://www.freepik.com/premium-video/happy-fathers-day-lettering-with-shirt-necktie_3663569',
                                'name' => 'happy fathers day lettering with shirt and necktie',
                                'aspect_ratio' => '16:9',
                                'created' => '2024-12-05 21:59:51',
                                'code' => 'happy-fathers-day-lettering-with-shirt-and-necktie',
                                'quality' => '4k',
                                'premium' => 1,
                                'duration' => '00:00:11',
                                'author' => [
                                    'id' => 6145869,
                                    'name' => 'djvstock',
                                    'code' => 'djvstock',
                                    'avatar' => 'https://profile.freepik.com/accounts/avatar/default_04.png',
                                    'metas' => [
                                        'downloads' => 118776,
                                        'assets' => 1960859,
                                    ],
                                    'slug' => 'djvstock',
                                ],
                                'thumbnails' => [
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/5c13e196-74a7-5901-9feb-2c48c85ab1c2/horizontal/thumbnails/small.jpg',
                                        'width' => 460,
                                        'height' => 264,
                                        'aspect_ratio' => '16:9',
                                    ],
                                ],
                                'previews' => [
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/5c13e196-74a7-5901-9feb-2c48c85ab1c2/horizontal/previews/watermarked/small.mp4',
                                        'width' => 455,
                                        'height' => 235,
                                        'aspect_ratio' => '16:9',
                                    ],
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/5c13e196-74a7-5901-9feb-2c48c85ab1c2/horizontal/previews/watermarked/large.mp4',
                                        'width' => 1364,
                                        'height' => 720,
                                        'aspect_ratio' => '16:9',
                                    ],
                                ],
                                'active' => true,
                                'is_ai_generated' => false,
                            ],
                            [
                                'id' => 3663567,
                                'url' => 'https://www.freepik.com/premium-video/happy-fathers-day-lettering-with-mustache_3663567',
                                'name' => 'happy fathers day lettering with mustache',
                                'aspect_ratio' => '16:9',
                                'created' => '2024-12-05 21:59:51',
                                'code' => 'happy-fathers-day-lettering-with-mustache',
                                'quality' => '4k',
                                'premium' => 1,
                                'duration' => '00:00:11',
                                'author' => [
                                    'id' => 6145869,
                                    'name' => 'djvstock',
                                    'code' => 'djvstock',
                                    'avatar' => 'https://profile.freepik.com/accounts/avatar/default_04.png',
                                    'metas' => [
                                        'downloads' => 118776,
                                        'assets' => 1960859,
                                    ],
                                    'slug' => 'djvstock',
                                ],
                                'thumbnails' => [
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/708f61d9-6f97-58a1-b33e-4e12e48b3288/horizontal/thumbnails/small.jpg',
                                        'width' => 460,
                                        'height' => 264,
                                        'aspect_ratio' => '16:9',
                                    ],
                                ],
                                'previews' => [
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/708f61d9-6f97-58a1-b33e-4e12e48b3288/horizontal/previews/watermarked/small.mp4',
                                        'width' => 455,
                                        'height' => 235,
                                        'aspect_ratio' => '16:9',
                                    ],
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/708f61d9-6f97-58a1-b33e-4e12e48b3288/horizontal/previews/watermarked/large.mp4',
                                        'width' => 1364,
                                        'height' => 720,
                                        'aspect_ratio' => '16:9',
                                    ],
                                ],
                                'active' => true,
                                'is_ai_generated' => false,
                            ],
                            [
                                'id' => 3663592,
                                'url' => 'https://www.freepik.com/premium-video/happy-fathers-day-lettering-with-glasses-mustache-elegant-suit_3663592',
                                'name' => 'happy fathers day lettering with glasses and mustache in elegant suit',
                                'aspect_ratio' => '16:9',
                                'created' => '2024-12-05 22:00:11',
                                'code' => 'happy-fathers-day-lettering-with-glasses-and-mustache-in-elegant-suit',
                                'quality' => '4k',
                                'premium' => 1,
                                'duration' => '00:00:12',
                                'author' => [
                                    'id' => 6145869,
                                    'name' => 'djvstock',
                                    'code' => 'djvstock',
                                    'avatar' => 'https://profile.freepik.com/accounts/avatar/default_04.png',
                                    'metas' => [
                                        'downloads' => 118776,
                                        'assets' => 1960859,
                                    ],
                                    'slug' => 'djvstock',
                                ],
                                'thumbnails' => [
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/70047e77-5b27-5957-8a93-6d26401ccd1d/horizontal/thumbnails/small.jpg',
                                        'width' => 460,
                                        'height' => 264,
                                        'aspect_ratio' => '16:9',
                                    ],
                                ],
                                'previews' => [
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/70047e77-5b27-5957-8a93-6d26401ccd1d/horizontal/previews/watermarked/small.mp4',
                                        'width' => 455,
                                        'height' => 235,
                                        'aspect_ratio' => '16:9',
                                    ],
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/70047e77-5b27-5957-8a93-6d26401ccd1d/horizontal/previews/watermarked/large.mp4',
                                        'width' => 1364,
                                        'height' => 720,
                                        'aspect_ratio' => '16:9',
                                    ],
                                ],
                                'active' => true,
                                'is_ai_generated' => false,
                            ],
                            [
                                'id' => 3663582,
                                'url' => 'https://www.freepik.com/premium-video/happy-fathers-day-lettering-with-mustache-glasses_3663582',
                                'name' => 'happy fathers day lettering with mustache and glasses',
                                'aspect_ratio' => '16:9',
                                'created' => '2024-12-05 22:00:06',
                                'code' => 'happy-fathers-day-lettering-with-mustache-and-glasses',
                                'quality' => '4k',
                                'premium' => 1,
                                'duration' => '00:00:11',
                                'author' => [
                                    'id' => 6145869,
                                    'name' => 'djvstock',
                                    'code' => 'djvstock',
                                    'avatar' => 'https://profile.freepik.com/accounts/avatar/default_04.png',
                                    'metas' => [
                                        'downloads' => 118776,
                                        'assets' => 1960859,
                                    ],
                                    'slug' => 'djvstock',
                                ],
                                'thumbnails' => [
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/b00d6e7c-9ff7-59c1-86e1-a853e9dd898f/horizontal/thumbnails/small.jpg',
                                        'width' => 460,
                                        'height' => 264,
                                        'aspect_ratio' => '16:9',
                                    ],
                                ],
                                'previews' => [
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/b00d6e7c-9ff7-59c1-86e1-a853e9dd898f/horizontal/previews/watermarked/small.mp4',
                                        'width' => 455,
                                        'height' => 235,
                                        'aspect_ratio' => '16:9',
                                    ],
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/b00d6e7c-9ff7-59c1-86e1-a853e9dd898f/horizontal/previews/watermarked/large.mp4',
                                        'width' => 1364,
                                        'height' => 720,
                                        'aspect_ratio' => '16:9',
                                    ],
                                ],
                                'active' => true,
                                'is_ai_generated' => false,
                            ],
                            [
                                'id' => 3663842,
                                'url' => 'https://www.freepik.com/premium-video/happy-fathers-day-lettering-with-elegant-tie_3663842',
                                'name' => 'happy fathers day lettering with elegant tie',
                                'aspect_ratio' => '16:9',
                                'created' => '2024-12-05 22:02:39',
                                'code' => 'happy-fathers-day-lettering-with-elegant-tie',
                                'quality' => '4k',
                                'premium' => 1,
                                'duration' => '00:00:11',
                                'author' => [
                                    'id' => 6145869,
                                    'name' => 'djvstock',
                                    'code' => 'djvstock',
                                    'avatar' => 'https://profile.freepik.com/accounts/avatar/default_04.png',
                                    'metas' => [
                                        'downloads' => 118776,
                                        'assets' => 1960859,
                                    ],
                                    'slug' => 'djvstock',
                                ],
                                'thumbnails' => [
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/6fb36dca-8b47-5f78-be9e-509680e37bdf/horizontal/thumbnails/small.jpg',
                                        'width' => 460,
                                        'height' => 264,
                                        'aspect_ratio' => '16:9',
                                    ],
                                ],
                                'previews' => [
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/6fb36dca-8b47-5f78-be9e-509680e37bdf/horizontal/previews/watermarked/small.mp4',
                                        'width' => 455,
                                        'height' => 235,
                                        'aspect_ratio' => '16:9',
                                    ],
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/6fb36dca-8b47-5f78-be9e-509680e37bdf/horizontal/previews/watermarked/large.mp4',
                                        'width' => 1364,
                                        'height' => 720,
                                        'aspect_ratio' => '16:9',
                                    ],
                                ],
                                'active' => true,
                                'is_ai_generated' => false,
                            ],
                            [
                                'id' => 4913634,
                                'url' => 'https://www.freepik.com/premium-video/happy-fathers-day-lettering-card-with-elegant-hat-bowtie_4913634',
                                'name' => 'happy fathers day lettering card with elegant hat and bowtie',
                                'aspect_ratio' => '16:9',
                                'created' => '2025-03-19 02:12:03',
                                'code' => 'happy-fathers-day-lettering-card-with-elegant-hat-and-bowtie',
                                'quality' => '4k',
                                'premium' => 1,
                                'duration' => '00:00:10',
                                'author' => [
                                    'id' => 6145869,
                                    'name' => 'djvstock',
                                    'code' => 'djvstock',
                                    'avatar' => 'https://profile.freepik.com/accounts/avatar/default_04.png',
                                    'metas' => [
                                        'downloads' => 118776,
                                        'assets' => 1960859,
                                    ],
                                    'slug' => 'djvstock',
                                ],
                                'thumbnails' => [
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/43dc5448-bcd1-5cd4-837f-2b6d84fd971f/horizontal/thumbnails/small.jpg',
                                        'width' => 460,
                                        'height' => 264,
                                        'aspect_ratio' => '16:9',
                                    ],
                                ],
                                'previews' => [
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/43dc5448-bcd1-5cd4-837f-2b6d84fd971f/horizontal/previews/watermarked/small.mp4',
                                        'width' => 455,
                                        'height' => 235,
                                        'aspect_ratio' => '16:9',
                                    ],
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/43dc5448-bcd1-5cd4-837f-2b6d84fd971f/horizontal/previews/watermarked/large.mp4',
                                        'width' => 1364,
                                        'height' => 720,
                                        'aspect_ratio' => '16:9',
                                    ],
                                ],
                                'active' => true,
                                'is_ai_generated' => false,
                            ],
                            [
                                'id' => 3663588,
                                'url' => 'https://www.freepik.com/premium-video/happy-fathers-day-lettering-with-necktie-mustache_3663588',
                                'name' => 'happy fathers day lettering with necktie and mustache',
                                'aspect_ratio' => '16:9',
                                'created' => '2024-12-05 22:00:10',
                                'code' => 'happy-fathers-day-lettering-with-necktie-and-mustache',
                                'quality' => '4k',
                                'premium' => 1,
                                'duration' => '00:00:11',
                                'author' => [
                                    'id' => 6145869,
                                    'name' => 'djvstock',
                                    'code' => 'djvstock',
                                    'avatar' => 'https://profile.freepik.com/accounts/avatar/default_04.png',
                                    'metas' => [
                                        'downloads' => 118776,
                                        'assets' => 1960859,
                                    ],
                                    'slug' => 'djvstock',
                                ],
                                'thumbnails' => [
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/b6158b1e-0d00-5a8e-a7cc-98cc21eb2107/horizontal/thumbnails/small.jpg',
                                        'width' => 460,
                                        'height' => 264,
                                        'aspect_ratio' => '16:9',
                                    ],
                                ],
                                'previews' => [
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/b6158b1e-0d00-5a8e-a7cc-98cc21eb2107/horizontal/previews/watermarked/small.mp4',
                                        'width' => 455,
                                        'height' => 235,
                                        'aspect_ratio' => '16:9',
                                    ],
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/b6158b1e-0d00-5a8e-a7cc-98cc21eb2107/horizontal/previews/watermarked/large.mp4',
                                        'width' => 1364,
                                        'height' => 720,
                                        'aspect_ratio' => '16:9',
                                    ],
                                ],
                                'active' => true,
                                'is_ai_generated' => false,
                            ],
                            [
                                'id' => 4914071,
                                'url' => 'https://www.freepik.com/premium-video/happy-fathers-day-lettering-card-with-mustache_4914071',
                                'name' => 'happy fathers day lettering card with mustache',
                                'aspect_ratio' => '16:9',
                                'created' => '2025-03-19 02:16:23',
                                'code' => 'happy-fathers-day-lettering-card-with-mustache',
                                'quality' => '4k',
                                'premium' => 1,
                                'duration' => '00:00:10',
                                'author' => [
                                    'id' => 6145869,
                                    'name' => 'djvstock',
                                    'code' => 'djvstock',
                                    'avatar' => 'https://profile.freepik.com/accounts/avatar/default_04.png',
                                    'metas' => [
                                        'downloads' => 118776,
                                        'assets' => 1960859,
                                    ],
                                    'slug' => 'djvstock',
                                ],
                                'thumbnails' => [
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/d08d8399-9370-5072-841d-a66ded99a8a4/horizontal/thumbnails/small.jpg',
                                        'width' => 460,
                                        'height' => 264,
                                        'aspect_ratio' => '16:9',
                                    ],
                                ],
                                'previews' => [
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/d08d8399-9370-5072-841d-a66ded99a8a4/horizontal/previews/watermarked/small.mp4',
                                        'width' => 455,
                                        'height' => 235,
                                        'aspect_ratio' => '16:9',
                                    ],
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/d08d8399-9370-5072-841d-a66ded99a8a4/horizontal/previews/watermarked/large.mp4',
                                        'width' => 1364,
                                        'height' => 720,
                                        'aspect_ratio' => '16:9',
                                    ],
                                ],
                                'active' => true,
                                'is_ai_generated' => false,
                            ],
                            [
                                'id' => 3663855,
                                'url' => 'https://www.freepik.com/premium-video/happy-fathers-day-lettering-with-elegant-suit-bowtie_3663855',
                                'name' => 'happy fathers day lettering with elegant suit and bowtie',
                                'aspect_ratio' => '16:9',
                                'created' => '2024-12-05 22:02:46',
                                'code' => 'happy-fathers-day-lettering-with-elegant-suit-and-bowtie',
                                'quality' => '4k',
                                'premium' => 1,
                                'duration' => '00:00:11',
                                'author' => [
                                    'id' => 6145869,
                                    'name' => 'djvstock',
                                    'code' => 'djvstock',
                                    'avatar' => 'https://profile.freepik.com/accounts/avatar/default_04.png',
                                    'metas' => [
                                        'downloads' => 118776,
                                        'assets' => 1960859,
                                    ],
                                    'slug' => 'djvstock',
                                ],
                                'thumbnails' => [
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/195a58de-e5da-5af0-8039-a3a9cec1c546/horizontal/thumbnails/small.jpg',
                                        'width' => 460,
                                        'height' => 264,
                                        'aspect_ratio' => '16:9',
                                    ],
                                ],
                                'previews' => [
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/195a58de-e5da-5af0-8039-a3a9cec1c546/horizontal/previews/watermarked/small.mp4',
                                        'width' => 455,
                                        'height' => 235,
                                        'aspect_ratio' => '16:9',
                                    ],
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/195a58de-e5da-5af0-8039-a3a9cec1c546/horizontal/previews/watermarked/large.mp4',
                                        'width' => 1364,
                                        'height' => 720,
                                        'aspect_ratio' => '16:9',
                                    ],
                                ],
                                'active' => true,
                                'is_ai_generated' => false,
                            ],
                            [
                                'id' => 4913933,
                                'url' => 'https://www.freepik.com/premium-video/happy-fathers-day-lettering-card-with-accessories_4913933',
                                'name' => 'happy fathers day lettering card with accessories',
                                'aspect_ratio' => '16:9',
                                'created' => '2025-03-19 02:15:22',
                                'code' => 'happy-fathers-day-lettering-card-with-accessories',
                                'quality' => '4k',
                                'premium' => 1,
                                'duration' => '00:00:10',
                                'author' => [
                                    'id' => 6145869,
                                    'name' => 'djvstock',
                                    'code' => 'djvstock',
                                    'avatar' => 'https://profile.freepik.com/accounts/avatar/default_04.png',
                                    'metas' => [
                                        'downloads' => 118776,
                                        'assets' => 1960859,
                                    ],
                                    'slug' => 'djvstock',
                                ],
                                'thumbnails' => [
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/40bbcb0f-c1c6-5f82-8781-4f9cda8b8d75/horizontal/thumbnails/small.jpg',
                                        'width' => 460,
                                        'height' => 264,
                                        'aspect_ratio' => '16:9',
                                    ],
                                ],
                                'previews' => [
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/40bbcb0f-c1c6-5f82-8781-4f9cda8b8d75/horizontal/previews/watermarked/small.mp4',
                                        'width' => 455,
                                        'height' => 235,
                                        'aspect_ratio' => '16:9',
                                    ],
                                    [
                                        'url' => 'https://videocdn.cdnpk.net/videos/40bbcb0f-c1c6-5f82-8781-4f9cda8b8d75/horizontal/previews/watermarked/large.mp4',
                                        'width' => 1364,
                                        'height' => 720,
                                        'aspect_ratio' => '16:9',
                                    ],
                                ],
                                'active' => true,
                                'is_ai_generated' => false,
                            ],
                        ],
                        'related_psds' => [],
                        'related_vectors' => [],
                        'related_icons' => [],
                    ],
                    'related_tags' => [
                        [
                            'slug' => 'dad',
                            'name' => 'dad',
                        ],
                        [
                            'slug' => 'father',
                            'name' => 'father',
                        ],
                        [
                            'slug' => 'family-illustration',
                            'name' => 'family illustration',
                        ],
                        [
                            'slug' => 'family-love',
                            'name' => 'family love',
                        ],
                        [
                            'slug' => 'parenting',
                            'name' => 'parenting',
                        ],
                        [
                            'slug' => 'family',
                            'name' => 'family',
                        ],
                        [
                            'slug' => 'family-happy',
                            'name' => 'family happy',
                        ],
                        [
                            'slug' => 'celebration',
                            'name' => 'celebration',
                        ],
                        [
                            'slug' => 'lovely',
                            'name' => 'lovely',
                        ],
                        [
                            'slug' => 'greeting',
                            'name' => 'greeting',
                        ],
                        [
                            'slug' => 'illustrations',
                            'name' => 'illustrations',
                        ],
                        [
                            'slug' => 'event',
                            'name' => 'event',
                        ],
                        [
                            'slug' => 'relationship',
                            'name' => 'relationship',
                        ],
                        [
                            'slug' => 'design-illustration',
                            'name' => 'design illustration',
                        ],
                        [
                            'slug' => 'day',
                            'name' => 'day',
                        ],
                        [
                            'slug' => 'design',
                            'name' => 'design',
                        ],
                    ],
                    'is_ai_generated' => false,
                    'has_prompt' => false,
                    'dimensions' => [
                        'width' => 626,
                        'height' => 626,
                    ],
                ],
            ]
        )
    )]
    public function resourceDetail(?string $resource_id = null): JsonResponse
    {
        $resource_id = $resource_id ?? request()->input('resource_id');

        $validator = Validator::make(['resource_id' => $resource_id], [
            'resource_id' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $this->service->resourceDetail($resource_id);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/freepik/download_resource/{resource_id}',
        operationId: 'downloadResource',
        description: 'Get download link for a Freepik resource',
        summary: 'Freepik download resource',
        tags: ['Freepik'],
    )]
    #[OA\Parameter(
        name: 'resource_id',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'string', example: '7663349')
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response with download link',
        content: new OA\JsonContent(
            example: [
                'data' => [
                    'filename' => 'father-s-day-event-hand-drawn-style.zip',
                    'url' => 'https://downloadscdn5.freepik.com/d/7663349/23/2148508/2148507324/father-s-day-event-hand-drawn-style.zip?token=exp=1751005689~hmac=5e439aed0f35b50349bdecfaf8ce2856',
                ],
            ]
        )
    )]
    public function downloadResource(?string $resource_id = null): JsonResponse
    {
        $resource_id = $resource_id ?? request()->input('resource_id');

        $validator = Validator::make(['resource_id' => $resource_id], [
            'resource_id' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $this->service->downloadResource($resource_id);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/freepik/download_resource_format',
        operationId: 'downloadResourceFormat',
        description: 'Download a Freepik resource in the specified format',
        summary: 'Download Freepik resource by query (resource_id & format)',
        tags: ['Freepik'],
    )]
    #[OA\Parameter(
        name: 'resource_id',
        in: 'query',
        required: true,
        schema: new OA\Schema(type: 'string', example: '150898146')
    )]
    #[OA\Parameter(
        name: 'format',
        in: 'query',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            enum: ['psd', 'ai', 'eps', 'atn', 'fonts', 'resources', 'png', 'jpg', '3d-render', 'svg', 'mockup'],
            example: 'psd'
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'data' => [
                    [
                        'filename' => '150898146_10483448.psd',
                        'url' => 'https://downloadscdn5.freepik.com/download_psd/psd/0/23/150/150898/150898146_10483448.psd?token=exp=1751005736~hmac=cfeb79bd582be116da3ba87b72a27b2f',
                    ],
                ],
            ]
        )
    )]
    public function downloadResourceFormat(DownloadResourceFormatRequest $request): JsonResponse
    {
        $data = DownloadResourceFormatData::from($request->validated());
        $result = $this->service->downloadResourceFormat($data);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/freepik/ai_image_classifier',
        operationId: 'aiImageClassifier',
        summary: 'AI Image Classifier',
        description: 'Classify an image via URL to determine if it was AI-generated.',
        tags: ['Freepik']
    )]
    #[OA\Parameter(
        name: 'image_cid',
        in: 'query',
        required: true,
        schema: new OA\Schema(type: 'string', format: 'string', example: 'QmTkm5aAqNPgc3rXKTjYJ1VVB86xWJGofZX5wiRXHeew7f')
    )]
    #[OA\Response(
        response: 200,
        description: 'Classification result',
        content: new OA\JsonContent(
            example: [
                'data' => [
                    [
                        'class_name' => 'not_ai',
                        'probability' => 0.94891726970673,
                    ],
                    [
                        'class_name' => 'ai',
                        'probability' => 0.051082730293274,
                    ],
                ],
            ]
        )
    )]
    public function aiImageClassifier(AiImageClassifierRequest $request): JsonResponse
    {
        $data = AiImageClassifierData::from($request->validated());
        $result = $this->service->aiImageClassifier($data);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/freepik/icon_generation',
        operationId: 'iconGeneration',
        summary: 'Generate icon preview using Freepik AI',
        description: 'Submit a text prompt to generate AI icon previews. Result is sent via webhook.',
        tags: ['Freepik']
    )]
    #[OA\Parameter(
        name: 'prompt',
        in: 'query',
        required: true,
        description: 'Text prompt describing the icon you want to generate',
        schema: new OA\Schema(
            type: 'string',
            example: 'Cute robot with camera in flat vector style'
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Task accepted',
        content: new OA\JsonContent(
            example: [
                'data' => [
                    'task_id' => 'd1686bd7-ffeb-4914-bffe-5a8db018ac3b',
                    'status' => 'CREATED',
                    'generated' => [],
                ],
            ]
        )
    )]
    public function iconGeneration(IconGenerationRequest $request): JsonResponse
    {
        $data = IconGenerationData::from($request->validated());
        $result = $this->service->iconGeneration($data);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/freepik/icon_generation/result/{task_id}',
        operationId: 'getIconGenerationResult',
        summary: 'Get result of icon generation by task_id',
        description: 'Retrieve the result of a previously submitted icon generation task using task_id.',
        tags: ['Freepik']
    )]
    #[OA\Parameter(
        name: 'task_id',
        in: 'path',
        required: true,
        description: 'The task_id received from the iconGeneration endpoint.',
        schema: new OA\Schema(type: 'string', example: '796dd3c1-c50b-42bc-a9e2-a892eef53438')
    )]
    #[OA\Response(
        response: 200,
        description: 'Result of icon generation task',
        content: new OA\JsonContent(
            example: [
                'data' => [
                    'task_id' => 'd20eeb40-11fc-4402-a9da-d06eb9c66f23',
                    'status' => 'COMPLETED',
                    'generated' => [
                        'https://cdn-magnific.freepik.com/imagen3_d20eeb40-11fc-4402-a9da-d06eb9c66f23_0.png?token=exp=1751007773~hmac=0efd3dde93656145f6b951922491d150e3e4a1cb8aba2ab06144d3629060215d',
                    ],
                ],
            ]
        )
    )]
    public function getIconGenerationResult(?string $task_id = null): JsonResponse
    {
        $taskId = $task_id ?? request()->input('task_id');

        $validator = Validator::make(['task_id' => $taskId], [
            'task_id' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $this->service->getWebhookResult($taskId);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/freepik/kling_video_generation/image_to_video',
        operationId: 'kling_video_generation_image_to_video',
        summary: 'Generate video using Kling v2.1 Master model',
        description: 'Generate a video from an image prompt using Freepik’s Kling v2.1 Master model. Supports Image to Video modes with detailed input parameters.',
        tags: ['Freepik']
    )]
    #[OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    title: 'Image to Video',
                    type: 'object',
                    required: ['duration'],
                    properties: [
                        new OA\Property(
                            property: 'model',
                            type: 'string',
                            enum: ['kling-v2-1-master', 'kling-v2-1-pro', 'kling-v2-1-std', 'kling-v2', 'kling-pro', 'kling-std'],
                            description: 'Model of the generated video. Available options: kling-v2-1-master, kling-v2-1-pro, kling-v2-1-std, kling-v2, kling-pro, kling-std.',
                            example: 'kling-v2-1-master'
                        ),
                        new OA\Property(
                            property: 'duration',
                            type: 'string',
                            enum: ['5', '10'],
                            description: 'Duration of the generated video in seconds. Available options: 5, 10.',
                            example: '10'
                        ),
                        new OA\Property(
                            property: 'image_cid',
                            type: 'string',
                            description: 'Reference image. Supports cid. Max 10MB, min 300x300px, aspect ratio 1:2.5 to 2.5:1.',
                            example: 'QmePMNQ1BYCsaJwCpA4sbGpYxgiEznzJwPDMHir9FdUiYN'
                        ),
                        new OA\Property(
                            property: 'prompt',
                            type: 'string',
                            description: 'Text prompt describing the desired motion. Required if image is not provided.',
                            example: 'Cinematic view of a mountain range fading into mist, soft lighting, epic atmosphere'
                        ),
                        new OA\Property(
                            property: 'negative_prompt',
                            type: 'string',
                            description: 'Describe what to avoid in the generated video.',
                            example: 'blurry, low-quality, distorted, overexposed'
                        ),
                        new OA\Property(
                            property: 'cfg_scale',
                            type: 'number',
                            format: 'float',
                            description: 'Higher = stronger relevance to prompt (0-1). Default is 0.5.',
                            example: 0.3,
                            minimum: 0,
                            maximum: 1
                        ),
                        new OA\Property(
                            property: 'static_mask',
                            type: 'string',
                            description: 'Static mask image cid. Must match resolution and aspect ratio of input image.',
                            example: 'QmePMNQ1BYCsaJwCpA4sbGpYxgiEznzJwPDMHir9FdUiYN'
                        ),
                        new OA\Property(
                            property: 'dynamic_masks',
                            type: 'array',
                            description: 'Array of dynamic masks with motion trajectories.',
                            items: new OA\Items(
                                type: 'object',
                                required: ['mask', 'trajectories'],
                                properties: [
                                    new OA\Property(
                                        property: 'mask_cid',
                                        type: 'string',
                                        description: 'Dynamic mask image cid',
                                        example: 'QmePMNQ1BYCsaJwCpA4sbGpYxgiEznzJwPDMHir9FdUiYN'
                                    ),
                                    new OA\Property(
                                        property: 'trajectories',
                                        type: 'array',
                                        items: new OA\Items(
                                            type: 'object',
                                            required: ['x', 'y'],
                                            properties: [
                                                new OA\Property(property: 'x', type: 'integer', example: 120),
                                                new OA\Property(property: 'y', type: 'integer', example: 200),
                                            ]
                                        )
                                    ),
                                ]
                            )
                        ),
                    ]
                )
            ),
        ]
    )]
    #[OA\Response(
        response: 200,
        description: 'Video generation started',
        content: new OA\JsonContent(
            type: 'object',
            example: [
                'data' => [
                    'task_id' => 'd1686bd7-ffeb-4914-bffe-5a8db018ac3b',
                    'status' => 'CREATED',
                    'generated' => [],
                ],
            ]
        )
    )]
    public function klingVideoGenerationImageToVideo(KlingImageToVideoRequest $request): JsonResponse
    {
        $data = KlingImageToVideoData::from($request->validated());

        $result = $this->service->klingImageToVideo($data);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/freepik/kling_video_generation/image_to_video/status',
        operationId: 'klingVideoGenerationImageToVideoStatus',
        summary: 'Get status of Kling v2.1 video generation task',
        description: 'Check the current status of a Kling v2.1 Master image-to-video generation task by task ID.',
        tags: ['Freepik'],
    )]
    #[OA\QueryParameter(
        name: 'task_id',
        required: true,
        description: 'ID of the video generation task',
        schema: new OA\Schema(type: 'string'),
        example: '046b6c7f-0b8a-43b9-b35d-6489e6daee91'
    )]
    #[OA\QueryParameter(
        name: 'model',
        required: true,
        schema: new OA\Schema(type: 'string', enum: ['kling-v2-1-master', 'kling-v2-1-pro', 'kling-v2-1-std', 'kling-v2', 'kling-pro', 'kling-std']),
        description: 'Model of the generated video in seconds. Available options: kling-v2-1-master,kling-v2-1-pro,kling-v2-1-std,kling-v2,kling-pro,kling-std.',
        example: 'kling-v2-1-master'
    )]
    #[OA\Response(
        response: 200,
        description: 'Task status response',
        content: new OA\JsonContent(
            type: 'object',
            example: [
                'data' => [
                    'task_id' => '14eb9004-41ef-4a16-97f2-a558aab35578',
                    'status' => 'COMPLETED',
                    'generated' => [
                        'https://ai-statics.freepik.com/content/mg-upscaler/syn56t4xlfgodjvkhlksx2t3be/output.png?token=exp=1751090771~hmac=8a99cf9fcbbecb72e35d8a6ddfea4b2d',
                    ],
                ],
            ]
        )
    )]
    public function klingVideoGenerationImageToVideoStatus(KlingImageToVideoStatusRequest $request): JsonResponse
    {
        $data = $request->validated();

        $result = $this->service->klingImageToVideoStatus($data['model'], $data['task_id']);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/freepik/kling_video_generation/text_to_video',
        operationId: 'kling_video_generation_text_to_video',
        summary: 'Generate video using Kling v2.1 Master model',
        description: 'Generate a video from an text prompt using Freepik’s Kling v2.1 Master model. Supports Text to Video modes with detailed input parameters.',
        tags: ['Freepik']
    )]
    #[OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    title: 'Text to Video',
                    type: 'object',
                    required: ['duration'],
                    properties: [
                        new OA\Property(
                            property: 'duration',
                            type: 'string',
                            enum: ['5', '10'],
                            description: 'Duration of the generated video in seconds. Available options: 5, 10.',
                            example: '5'
                        ),
                        new OA\Property(
                            property: 'prompt',
                            type: 'string',
                            description: 'Text prompt describing the desired motion. Max 2500 characters.',
                            example: 'A sunset over the ocean with crashing waves'
                        ),
                        new OA\Property(
                            property: 'negative_prompt',
                            type: 'string',
                            description: 'Describe what to avoid in the generated video.',
                            example: 'low resolution, night scene'
                        ),
                        new OA\Property(
                            property: 'aspect_ratio',
                            type: 'string',
                            enum: ['widescreen_16_9', 'social_story_9_16', 'square_1_1'],
                            description: 'Aspect ratio for generated video (only used when image is not provided).',
                            example: 'widescreen_16_9'
                        ),
                        new OA\Property(
                            property: 'cfg_scale',
                            type: 'number',
                            format: 'float',
                            description: 'Higher = stronger relevance to prompt (0-1). Default is 0.5.',
                            example: 0.5,
                            minimum: 0,
                            maximum: 1
                        ),
                    ]
                )
            ),
        ]
    )]
    #[OA\Response(
        response: 200,
        description: 'Video generation started',
        content: new OA\JsonContent(
            type: 'object',
            example: [
                'data' => [
                    'task_id' => 'd1686bd7-ffeb-4914-bffe-5a8db018ac3b',
                    'status' => 'CREATED',
                    'generated' => [],
                ],
            ]
        )
    )]
    public function klingVideoGenerationTextToVideo(KlingTextToVideoRequest $request): JsonResponse
    {
        $data = KlingTextToVideoData::from($request->validated());

        $result = $this->service->klingTextToVideo($data);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/freepik/kling_video_generation/text_to_video/status/{task_id}',
        operationId: 'klingVideoGenerationStatus',
        summary: 'Get status of Kling v2.1 video generation task',
        description: 'Check the current status of a Kling v2.1 Master text-to-video generation task by task ID.',
        tags: ['Freepik'],
    )]
    #[OA\Parameter(
        name: 'task_id',
        in: 'path',
        required: true,
        description: 'ID of the video generation task',
        schema: new OA\Schema(type: 'string'),
        example: '6546ca62-0ac4-464d-bfc9-5644be643f34'
    )]
    #[OA\Response(
        response: 200,
        description: 'Task status response',
        content: new OA\JsonContent(
            type: 'object',
            example: [
                'data' => [
                    'task_id' => '14eb9004-41ef-4a16-97f2-a558aab35578',
                    'status' => 'COMPLETED',
                    'generated' => [
                        'https://ai-statics.freepik.com/content/mg-upscaler/syn56t4xlfgodjvkhlksx2t3be/output.png?token=exp=1751090771~hmac=8a99cf9fcbbecb72e35d8a6ddfea4b2d',
                    ],
                ],
            ]
        )
    )]
    public function klingVideoGenerationTextToVideoStatus(?string $task_id = null): JsonResponse
    {
        $taskId = $task_id ?? request()->input('task_id');

        $validator = Validator::make(['task_id' => $taskId], [
            'task_id' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $this->service->klingTextToVideoStatus($taskId);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/freepik/kling_video_generation/image_to_video_elements',
        operationId: 'klingElementsVideo',
        summary: 'Generate video using Kling Elements Pro model',
        description: 'Create a video from 1–4 images with optional prompts, duration, aspect ratio, and webhook.',
        tags: ['Freepik'],
    )]
    #[OA\QueryParameter(
        name: 'model',
        required: true,
        schema: new OA\Schema(type: 'string', enum: ['kling-elements-pro', 'kling-elements-std']),
        description: 'Model of the generated video in seconds. Available options: kling-elements-pro,kling-elements-std.',
        example: 'kling-elements-pro'
    )]
    #[OA\Parameter(
        name: 'image_cids[]',
        in: 'query',
        required: true,
        description: 'Array of up to 4 image cids (publicly accessible)',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', format: 'uri'), maxItems: 4, example: ['QmePMNQ1BYCsaJwCpA4sbGpYxgiEznzJwPDMHir9FdUiYN', 'QmRPNoFMcYFmzJuZgd4t3BDyfELAGCwNtGSb5i5AbXkcpf'])
    )]
    #[OA\Parameter(name: 'prompt', in: 'query', required: false, schema: new OA\Schema(type: 'string', maxLength: 2500))]
    #[OA\Parameter(name: 'negative_prompt', in: 'query', required: false, schema: new OA\Schema(type: 'string', maxLength: 2500))]
    #[OA\Parameter(name: 'duration', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['5', '10'], example: '5'))]
    #[OA\Parameter(name: 'aspect_ratio', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['widescreen_16_9', 'social_story_9_16', 'square_1_1']))]
    #[OA\Response(
        response: 200,
        description: 'Video generation task started',
        content: new OA\JsonContent(
            example: [
                'data' => [
                    'task_id' => 'd1686bd7-ffeb-4914-bffe-5a8db018ac3b',
                    'status' => 'CREATED',
                    'generated' => [],
                ],
            ]
        )
    )]
    public function klingElementsVideo(KlingElementsVideoRequest $request): JsonResponse
    {
        $data = KlingElementsVideoData::from($request->validated());

        $result = $this->service->klingElementsVideo($data);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/freepik/kling_video_generation/image_to_video_elements/status/{task_id}',
        operationId: 'klingElementsVideoStatus',
        summary: 'Get status of Kling v2.1 video generation task',
        description: 'Check the current status of a Kling v2.1 Master image-to-video generation task by task ID.',
        tags: ['Freepik'],
    )]
    #[OA\Parameter(
        name: 'task_id',
        in: 'path',
        required: true,
        description: 'ID of the video generation task',
        schema: new OA\Schema(type: 'string'),
        example: '19a82de5-7bb9-4c88-a122-5c2b14571cac'
    )]
    #[OA\Response(
        response: 200,
        description: 'Task status response',
        content: new OA\JsonContent(
            type: 'object',
            example: [
                'data' => [
                    'task_id' => '14eb9004-41ef-4a16-97f2-a558aab35578',
                    'status' => 'COMPLETED',
                    'generated' => [
                        'https://ai-statics.freepik.com/content/mg-upscaler/syn56t4xlfgodjvkhlksx2t3be/output.png?token=exp=1751090771~hmac=8a99cf9fcbbecb72e35d8a6ddfea4b2d',
                    ],
                ],
            ]
        )
    )]
    public function klingElementsVideoStatus(?string $task_id = null): JsonResponse
    {
        $taskId = $task_id ?? request()->input('task_id');

        $validator = Validator::make(['task_id' => $taskId], [
            'task_id' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $this->service->klingElementsVideoStatus($taskId);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/freepik/mystic/loras',
        operationId: 'getLoras',
        description: 'Get list of LoRAs (custom styles and defaults) for Mystic AI',
        summary: 'Freepik Mystic LoRAs List',
        tags: ['Freepik'],
    )]
    #[OA\Response(
        response: 200,
        description: 'List of LoRAs with default and custom styles',
        content: new OA\JsonContent(
            required: ['data'],
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'default',
                            type: 'array',
                            description: 'Default LoRA styles available',
                            items: new OA\Items(
                                type: 'object',
                                required: ['id', 'name', 'description', 'category', 'type', 'training'],
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer'),
                                    new OA\Property(property: 'name', type: 'string'),
                                    new OA\Property(property: 'description', type: 'string'),
                                    new OA\Property(property: 'category', type: 'string'),
                                    new OA\Property(property: 'type', type: 'string'),
                                    new OA\Property(
                                        property: 'training',
                                        type: 'object',
                                        required: ['status', 'defaultScale'],
                                        properties: [
                                            new OA\Property(property: 'status', type: 'string'),
                                            new OA\Property(property: 'defaultScale', type: 'number'),
                                        ]
                                    ),
                                ]
                            )
                        ),
                        new OA\Property(
                            property: 'customs',
                            type: 'array',
                            description: 'Custom LoRA styles created by users',
                            items: new OA\Items(type: 'object')
                        ),
                    ]
                ),
            ],
            example: [
                'data' => [
                    'default' => [
                        [
                            'id' => 1,
                            'name' => 'vintage-japanese',
                            'description' => 'Expect bold red colors and a sense of nostalgia, bringing to life classic Japanese elements.',
                            'category' => 'illustration',
                            'type' => 'style',
                            'training' => [
                                'status' => 'completed',
                                'defaultScale' => 1.2,
                            ],
                        ],
                        [
                            'id' => 2,
                            'name' => 'sara',
                            'description' => 'sara',
                            'category' => 'people',
                            'type' => 'character',
                            'training' => [
                                'status' => 'completed',
                                'defaultScale' => 1.2,
                            ],
                        ],
                        [
                            'id' => 3,
                            'name' => 'glasses',
                            'description' => 'glasses',
                            'category' => 'product',
                            'type' => 'product',
                            'training' => [
                                'status' => 'completed',
                                'defaultScale' => 1.2,
                            ],
                        ],
                    ],
                    'customs' => [],
                ],
            ]
        )
    )]
    public function getLoras()
    {
        $result = $this->service->getLoras();

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/freepik/mystic',
        operationId: 'generateMysticImage',
        description: 'Create ultra-realistic AI images using Mystic from Freepik',
        summary: 'Freepik Mystic Image Generation',
        tags: ['Freepik'],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['prompt'],
            properties: [
                new OA\Property(property: 'prompt', type: 'string', description: 'AI Model Prompt Description. The text that describes the image you want to generate.'),
                new OA\Property(property: 'structure_reference', type: 'string', nullable: true, description: 'Base64 image to use as structure reference to influence the shape.'),
                new OA\Property(property: 'structure_strength', type: 'integer', minimum: 0, maximum: 100, nullable: true, default: 50, description: 'Strength to maintain the structure of the original image.'),
                new OA\Property(property: 'style_reference', type: 'string', nullable: true, description: 'Base64 image to use as style reference to influence aesthetics.'),
                new OA\Property(property: 'adherence', type: 'integer', minimum: 0, maximum: 100, nullable: true, default: 50, description: 'Higher values make the generation more faithful to the prompt.'),
                new OA\Property(property: 'hdr', type: 'integer', minimum: 0, maximum: 100, nullable: true, default: 50, description: 'Controls image detail and "AI look" tradeoff.'),
                new OA\Property(property: 'resolution', type: 'string', enum: ['1k', '2k', '4k'], nullable: true, default: '2k', description: 'Resolution of the generated image.'),
                new OA\Property(property: 'aspect_ratio', type: 'string', enum: [
                    'square_1_1',
                    'classic_4_3',
                    'traditional_3_4',
                    'widescreen_16_9',
                    'social_story_9_16',
                    'smartphone_horizontal_20_9',
                    'smartphone_vertical_9_20',
                    'standard_3_2',
                    'portrait_2_3',
                    'horizontal_2_1',
                    'vertical_1_2',
                    'social_5_4',
                    'social_post_4_5',
                ], nullable: true, default: 'square_1_1', description: 'Aspect ratio of the generated image.'),
                new OA\Property(property: 'model', type: 'string', enum: ['realism', 'fluid', 'zen'], nullable: true, default: 'realism', description: 'Model to use for generation.'),
                new OA\Property(property: 'creative_detailing', type: 'integer', minimum: 0, maximum: 100, nullable: true, default: 33, description: 'Controls detail per pixel with tradeoff on HDR/artificial look.'),
                new OA\Property(property: 'engine', type: 'string', enum: ['automatic', 'magnific_illusio', 'magnific_sharpy', 'magnific_sparkle'], nullable: true, default: 'automatic', description: 'Engine choice for the AI model.'),
                new OA\Property(property: 'fixed_generation', type: 'boolean', nullable: true, default: false, description: 'If true, same input produces the same image (fixed randomness).'),
                new OA\Property(property: 'filter_nsfw', type: 'boolean', nullable: true, default: true, description: 'When enabled, NSFW images are replaced with a black image.'),

                new OA\Property(
                    property: 'styling',
                    type: 'object',
                    nullable: true,
                    properties: [
                        new OA\Property(
                            property: 'styles',
                            type: 'array',
                            maxItems: 1,
                            items: new OA\Items(
                                type: 'object',
                                required: ['name'],
                                properties: [
                                    new OA\Property(property: 'name', type: 'string', description: 'Name of the style to apply'),
                                    new OA\Property(property: 'strength', type: 'number', minimum: 0, maximum: 200, nullable: true, default: 100, description: 'Strength of the style'),
                                ]
                            )
                        ),
                        new OA\Property(
                            property: 'characters',
                            type: 'array',
                            maxItems: 1,
                            items: new OA\Items(
                                type: 'object',
                                properties: [
                                    new OA\Property(property: 'id', type: 'string', description: 'ID of the character'),
                                    new OA\Property(property: 'strength', type: 'number', nullable: true, description: 'Strength of the character'),
                                ]
                            )
                        ),
                        new OA\Property(
                            property: 'colors',
                            type: 'array',
                            minItems: 1,
                            maxItems: 5,
                            items: new OA\Items(
                                type: 'object',
                                required: ['color'],
                                properties: [
                                    new OA\Property(property: 'color', type: 'string', pattern: '^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$', description: 'Hex color code, e.g. #FF0000'),
                                    new OA\Property(property: 'weight', type: 'number', nullable: true, description: 'Weight of the color in the generation'),
                                ]
                            )
                        ),
                    ],
                    description: 'Styling options for the image'
                ),
            ],
            example: [
                'prompt' => 'A futuristic cityscape at sunset, flying cars, glowing neon lights, cyberpunk vibe',
                'structure_reference_cid' => 'QmPE5opZZhpeHypZzG3qJE5cbCNZ28SibBa9xo4MqsgF9H',
                'structure_strength' => 60,
                'style_reference_cid' => 'QmTjCdTXQ2M1JPQHMuciYGQ2BWLVXum73PEJ8KY1znV4TV',
                'adherence' => 70,
                'hdr' => 80,
                'resolution' => '2k',
                'aspect_ratio' => 'square_1_1',
                'model' => 'realism',
                'creative_detailing' => 45,
                'engine' => 'automatic',
                'fixed_generation' => false,
                'filter_nsfw' => true,
                'styling' => [
                    'styles' => [
                        [
                            'name' => 'cyberpunk',
                            'strength' => 100,
                        ],
                    ],
                    'characters' => [
                        [
                            'id' => '110',
                            'strength' => 100,
                        ],
                    ],
                    'colors' => [
                        [
                            'color' => '#00FFFF',
                            'weight' => 0.6,
                        ],
                        [
                            'color' => '#FF69B4',
                            'weight' => 0.4,
                        ],
                    ],
                ],
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Mystic image generation task started',
        content: new OA\JsonContent(
            example: [
                'data' => [
                    'task_id' => 'd1686bd7-ffeb-4914-bffe-5a8db018ac3b',
                    'status' => 'CREATED',
                    'generated' => [],
                ],
            ]
        )
    )]
    public function generateMysticImage(MysticGenerateRequest $request)
    {
        $data = MysticGenerateData::from($request->validated());

        $result = $this->service->generateMysticImage($data);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/freepik/mystic/status/{task_id}',
        operationId: 'getMysticTaskStatus',
        description: 'Get the status of the Mystic task',
        summary: 'Freepik Mystic Task Status',
        tags: ['Freepik'],
    )]
    #[OA\Parameter(
        name: 'task_id',
        in: 'path',
        required: true,
        description: 'ID of the Mystic generation task',
        schema: new OA\Schema(type: 'string', example: '046b6c7f-0b8a-43b9-b35d-6489e6daee91'),
    )]
    #[OA\Response(
        response: 200,
        description: 'The task status and generated images if available',
        content: new OA\JsonContent(
            required: ['data'],
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'object',
                    required: ['generated', 'task_id', 'status'],
                    properties: [
                        new OA\Property(
                            property: 'generated',
                            type: 'array',
                            description: 'List of generated image URLs',
                            items: new OA\Items(type: 'string', format: 'uri')
                        ),
                        new OA\Property(
                            property: 'task_id',
                            type: 'string',
                            description: 'The task ID'
                        ),
                        new OA\Property(
                            property: 'status',
                            type: 'string',
                            description: 'Status of the task',
                            enum: ['IN_PROGRESS', 'COMPLETED', 'FAILED']
                        ),
                        new OA\Property(
                            property: 'has_nsfw',
                            type: 'array',
                            nullable: true,
                            description: 'List indicating if generated images contain NSFW content',
                            items: new OA\Items(type: 'boolean')
                        ),
                    ]
                ),
            ],
            example: [
                'data' => [
                    'task_id' => 'd1686bd7-ffeb-4914-bffe-5a8db018ac3b',
                    'status' => 'COMPLETED',
                    'generated' => [
                        'https://ai-statics.freepik.com/content/mg-upscaler/hg37oeqmzjb2vng25rpj4gosxm/output.png?token=exp=1751089803~hmac=e733128992a3814c0dc28a8cc3f1ac64',
                    ],
                    'has_nsfw' => [
                        false,
                    ],
                ],
            ]
        )
    )]
    public function getMysticTaskStatus(?string $task_id = null)
    {
        $taskId = $task_id ?? request()->input('task_id');

        $validator = Validator::make(['task_id' => $taskId], [
            'task_id' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $this->service->getMysticTaskStatus($taskId);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/freepik/mystic/loras/styles',
        operationId: 'createLoraStyle',
        description: 'Create your own custom LoRA style by training with images',
        summary: 'LoRAs Custom Style Training',
        tags: ['Freepik'],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'quality', 'images'],
            properties: [
                new OA\Property(
                    property: 'name',
                    type: 'string',
                    description: 'Name of the LoRA style used to identify the style in the system.',
                    example: 'neon-cyberpunk-style'
                ),
                new OA\Property(
                    property: 'quality',
                    type: 'string',
                    enum: ['medium', 'high', 'ultra'],
                    description: 'Quality of the LoRA style.',
                    example: 'high'
                ),
                new OA\Property(
                    property: 'image_cids',
                    type: 'array',
                    minItems: 6,
                    maxItems: 20,
                    description: 'List of image URLs to train the LoRA style.',
                    items: new OA\Items(type: 'string', format: 'uri'),
                    example: [
                        'QmRBe3ZDEBDH18JEgDuFdTShzw3Xy1s94bY6dtcBAnH4tu',
                        'QmRPNoFMcYFmzJuZgd4t3BDyfELAGCwNtGSb5i5AbXkcpf',
                        'QmePMNQ1BYCsaJwCpA4sbGpYxgiEznzJwPDMHir9FdUiYN',
                        'QmPnAKihJS1shKqnA4UqQ6bvkw29j8yFW4MJTb6KZA1e6Q',
                        'QmdHRXQ8sX2d648gnCa2CXUjeJmreS8654vqwd9JS6m8GN',
                        'QmPE5opZZhpeHypZzG3qJE5cbCNZ28SibBa9xo4MqsgF9H',
                    ]
                ),
                new OA\Property(
                    property: 'description',
                    type: 'string',
                    nullable: true,
                    description: 'Description of the LoRA style.',
                    example: 'A high-quality cyberpunk visual style with neon lights, futuristic elements, and strong contrast. Inspired by sci-fi films and urban night scenes.'
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'LoRA style training started and processing',
        content: new OA\JsonContent(
            example: [
                'data' => [
                    'task_id' => 'abe2666f-3977-4420-b591-082ba0b54790',
                    'status' => 'CREATED',
                    'generated' => [],
                ],
            ]
        )
    )]
    public function createLoraStyle(LoraStyleTrainRequest $request)
    {
        $data = LoraStyleTrainData::from($request->validated());

        $result = $this->service->trainLoraStyle($data);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/freepik/mystic/loras/characters',
        operationId: 'trainLoraCharacter',
        summary: 'Train a custom character using Freepik LoRA',
        tags: ['Freepik'],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'quality', 'gender', 'images'],
            properties: [
                new OA\Property(
                    property: 'name',
                    type: 'string',
                    example: 'cyber_hero_neo'
                ),
                new OA\Property(
                    property: 'quality',
                    type: 'string',
                    enum: ['medium', 'high', 'ultra'],
                    description: 'Quality of the LoRA character',
                    example: 'high'
                ),
                new OA\Property(
                    property: 'gender',
                    type: 'string',
                    enum: ['male', 'female', 'neutral', 'custom'],
                    description: 'Gender of the character',
                    example: 'male'
                ),
                new OA\Property(
                    property: 'image_cids',
                    type: 'array',
                    minItems: 8,
                    maxItems: 20,
                    items: new OA\Items(type: 'string', format: 'string'),
                    example: [
                        'QmRBe3ZDEBDH18JEgDuFdTShzw3Xy1s94bY6dtcBAnH4tu',
                        'QmRPNoFMcYFmzJuZgd4t3BDyfELAGCwNtGSb5i5AbXkcpf',
                        'QmePMNQ1BYCsaJwCpA4sbGpYxgiEznzJwPDMHir9FdUiYN',
                        'QmPnAKihJS1shKqnA4UqQ6bvkw29j8yFW4MJTb6KZA1e6Q',
                        'QmdHRXQ8sX2d648gnCa2CXUjeJmreS8654vqwd9JS6m8GN',
                        'QmPE5opZZhpeHypZzG3qJE5cbCNZ28SibBa9xo4MqsgF9H',
                        'QmRTn93jATdpcpCg1nRfAExc8shh9Hmhbd8PHnXBrLJYAa',
                        'QmTjCdTXQ2M1JPQHMuciYGQ2BWLVXum73PEJ8KY1znV4TV',
                    ]
                ),
                new OA\Property(
                    property: 'description',
                    type: 'string',
                    nullable: true,
                    example: 'A futuristic male character with a bold cyberpunk aesthetic, glowing eyes, and advanced tech gear. Suitable for sci-fi narratives, stylized storytelling, and visual AI applications.'
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Character training started',
        content: new OA\JsonContent(
            example: [
                'data' => [
                    'task_id' => 'abe2666f-3977-4420-b591-082ba0b54790',
                    'status' => 'CREATED',
                    'generated' => [],
                ],
            ]
        )
    )]
    public function trainLoraCharacter(LoraCharacterTrainRequest $request)
    {
        $data = LoraCharacterTrainData::from($request->validated());

        $result = $this->service->trainLoraCharacter($data);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/freepik/text-to-image/classic-fast',
        operationId: 'generateClassicFastImage',
        summary: 'Generate image using Freepik Classic Fast',
        description: 'Convert descriptive text into images using Freepik Classic Fast AI engine.',
        tags: ['Freepik']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['prompt'],
            properties: [
                new OA\Property(
                    property: 'prompt',
                    type: 'string',
                    minLength: 3,
                    example: 'Crazy dog in the space',
                    description: 'Text to generate image from'
                ),
                new OA\Property(
                    property: 'negative_prompt',
                    type: 'string',
                    minLength: 3,
                    nullable: true,
                    example: 'b&w, earth, cartoon, ugly',
                    description: 'Attributes to avoid in the generated image'
                ),
                new OA\Property(
                    property: 'guidance_scale',
                    type: 'number',
                    minimum: 0,
                    maximum: 2,
                    default: 1.0,
                    nullable: true,
                    example: 2,
                    description: 'Fidelity to the prompt'
                ),
                new OA\Property(
                    property: 'seed',
                    type: 'integer',
                    minimum: 0,
                    maximum: 1000000,
                    nullable: true,
                    example: 42,
                    description: 'Seed value for image reproducibility'
                ),
                new OA\Property(
                    property: 'num_images',
                    type: 'integer',
                    minimum: 1,
                    maximum: 4,
                    default: 1,
                    nullable: true,
                    example: 1,
                    description: 'Number of images to generate'
                ),
                new OA\Property(
                    property: 'filter_nsfw',
                    type: 'boolean',
                    default: true,
                    nullable: true,
                    example: true,
                    description: 'Filter NSFW content'
                ),
                new OA\Property(
                    property: 'image',
                    type: 'object',
                    nullable: true,
                    properties: [
                        new OA\Property(
                            property: 'size',
                            type: 'string',
                            enum: [
                                'square_1_1',
                                'classic_4_3',
                                'traditional_3_4',
                                'widescreen_16_9',
                                'social_story_9_16',
                                'smartphone_horizontal_20_9',
                                'smartphone_vertical_9_20',
                                'standard_3_2',
                                'portrait_2_3',
                                'horizontal_2_1',
                                'vertical_1_2',
                                'social_5_4',
                                'social_post_4_5',
                            ],
                            example: 'square_1_1',
                            description: 'Aspect ratio of the image'
                        ),
                    ]
                ),
                new OA\Property(
                    property: 'styling',
                    type: 'object',
                    nullable: true,
                    properties: [
                        new OA\Property(
                            property: 'style',
                            type: 'string',
                            nullable: true,
                            enum: [
                                'photo',
                                'digital-art',
                                '3d',
                                'painting',
                                'low-poly',
                                'pixel-art',
                                'anime',
                                'cyberpunk',
                                'comic',
                                'vintage',
                                'cartoon',
                                'vector',
                                'studio-shot',
                                'dark',
                                'sketch',
                                'mockup',
                                '2000s-pone',
                                '70s-vibe',
                                'watercolor',
                                'art-nouveau',
                                'origami',
                                'surreal',
                                'fantasy',
                                'traditional-japan',
                            ],
                            example: 'anime',
                            description: 'Style to apply to the image'
                        ),
                        new OA\Property(
                            property: 'effects',
                            type: 'object',
                            nullable: true,
                            properties: [
                                new OA\Property(
                                    property: 'color',
                                    type: 'string',
                                    nullable: true,
                                    enum: [
                                        'b&w',
                                        'pastel',
                                        'sepia',
                                        'dramatic',
                                        'vibrant',
                                        'orange&teal',
                                        'film-filter',
                                        'split',
                                        'electric',
                                        'pastel-pink',
                                        'gold-glow',
                                        'autumn',
                                        'muted-green',
                                        'deep-teal',
                                        'duotone',
                                        'terracotta&teal',
                                        'red&blue',
                                        'cold-neon',
                                        'burgundy&blue',
                                    ],
                                    example: 'pastel',
                                    description: 'Effects - Color to apply'
                                ),
                                new OA\Property(
                                    property: 'lightning',
                                    type: 'string',
                                    nullable: true,
                                    enum: [
                                        'studio',
                                        'warm',
                                        'cinematic',
                                        'volumetric',
                                        'golden-hour',
                                        'long-exposure',
                                        'cold',
                                        'iridescent',
                                        'dramatic',
                                        'hardlight',
                                        'redscale',
                                        'indoor-light',
                                    ],
                                    example: 'warm',
                                    description: 'Effects - Lightning to apply'
                                ),
                                new OA\Property(
                                    property: 'framing',
                                    type: 'string',
                                    nullable: true,
                                    enum: [
                                        'portrait',
                                        'macro',
                                        'panoramic',
                                        'aerial-view',
                                        'close-up',
                                        'cinematic',
                                        'high-angle',
                                        'low-angle',
                                        'symmetry',
                                        'fish-eye',
                                        'first-person',
                                    ],
                                    example: 'portrait',
                                    description: 'Effects - Framing to apply'
                                ),
                            ]
                        ),
                        new OA\Property(
                            property: 'colors',
                            type: 'array',
                            minItems: 1,
                            maxItems: 5,
                            items: new OA\Items(
                                type: 'object',
                                required: ['color'],
                                properties: [
                                    new OA\Property(
                                        property: 'color',
                                        type: 'string',
                                        pattern: '^#([A-Fa-f0-9]{6})$',
                                        example: '#FF5733',
                                        description: 'Hex color code'
                                    ),
                                    new OA\Property(
                                        property: 'weight',
                                        type: 'number',
                                        minimum: 0.05,
                                        maximum: 1.0,
                                        nullable: true,
                                        example: 1,
                                        description: 'Weight of the color (0.05 - 1.0)'
                                    ),
                                ]
                            )
                        ),
                    ]
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Image(s) generated successfully',
        content: new OA\JsonContent(
            example: [
                'data' => [
                    [
                        'base64' => '4AAQSkZJRgABAQAAAQABAAD00rU5WmFaCGQEUhFTFaTZQFj/2Q==',
                        'has_nsfw' => false,
                    ],
                ],
                'meta' => [
                    'prompt' => 'Crazy dog in the space',
                    'seed' => 42,
                    'image' => [
                        'size' => 'square_1_1',
                        'width' => 1024,
                        'height' => 1024,
                    ],
                    'num_inference_steps' => 8,
                    'guidance_scale' => 2,
                ],
            ]
        )
    )]
    public function generateClassicFastImage(ClassicFastGenerateRequest $request)
    {
        $data = ClassicFastGenerateData::from($request->validated());

        $result = $this->service->generateClassicFastImage($data);

        return response()->json($result);
    }

    #[OA\Post(
        path: '/api/freepik/text-to-image/imagen3',
        operationId: 'generateImagen3Image',
        tags: ['Freepik'],
        summary: 'Generate image using Google Imagen 3',
        description: 'Convert descriptive text into images using Google Imagen 3 AI engine.',
        security: [['bearerAuth' => []]],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['prompt'],
            properties: [
                new OA\Property(property: 'prompt', type: 'string', example: 'Crazy dog in the space'),
                new OA\Property(property: 'num_images', type: 'integer', minimum: 1, maximum: 4, example: 1),
                new OA\Property(
                    property: 'aspect_ratio',
                    type: 'string',
                    enum: [
                        'square_1_1',
                        'social_story_9_16',
                        'widescreen_16_9',
                        'traditional_3_4',
                        'classic_4_3',
                    ],
                    example: 'square_1_1'
                ),
                new OA\Property(
                    property: 'styling',
                    type: 'object',
                    nullable: true,
                    properties: [
                        new OA\Property(
                            property: 'style',
                            type: 'string',
                            enum: [
                                'photo',
                                'digital-art',
                                '3d',
                                'painting',
                                'low-poly',
                                'pixel-art',
                                'anime',
                                'cyberpunk',
                                'comic',
                                'vintage',
                                'cartoon',
                                'vector',
                                'studio-shot',
                                'dark',
                                'sketch',
                                'mockup',
                                '2000s-pone',
                                '70s-vibe',
                                'watercolor',
                                'art-nouveau',
                                'origami',
                                'surreal',
                                'fantasy',
                                'traditional-japan',
                            ],
                            example: 'anime'
                        ),
                        new OA\Property(
                            property: 'effects',
                            type: 'object',
                            nullable: true,
                            properties: [
                                new OA\Property(
                                    property: 'color',
                                    type: 'string',
                                    enum: [
                                        'b&w',
                                        'pastel',
                                        'sepia',
                                        'dramatic',
                                        'vibrant',
                                        'orange&teal',
                                        'film-filter',
                                        'split',
                                        'electric',
                                        'pastel-pink',
                                        'gold-glow',
                                        'autumn',
                                        'muted-green',
                                        'deep-teal',
                                        'duotone',
                                        'terracotta&teal',
                                        'red&blue',
                                        'cold-neon',
                                        'burgundy&blue',
                                    ],
                                    example: 'pastel'
                                ),
                                new OA\Property(
                                    property: 'lightning',
                                    type: 'string',
                                    enum: [
                                        'studio',
                                        'warm',
                                        'cinematic',
                                        'volumetric',
                                        'golden-hour',
                                        'long-exposure',
                                        'cold',
                                        'iridescent',
                                        'dramatic',
                                        'hardlight',
                                        'redscale',
                                        'indoor-light',
                                    ],
                                    example: 'warm'
                                ),
                                new OA\Property(
                                    property: 'framing',
                                    type: 'string',
                                    enum: [
                                        'portrait',
                                        'macro',
                                        'panoramic',
                                        'aerial-view',
                                        'close-up',
                                        'cinematic',
                                        'high-angle',
                                        'low-angle',
                                        'symmetry',
                                        'fish-eye',
                                        'first-person',
                                    ],
                                    example: 'portrait'
                                ),
                            ]
                        ),
                        new OA\Property(
                            property: 'colors',
                            type: 'array',
                            minItems: 1,
                            maxItems: 5,
                            nullable: true,
                            items: new OA\Items(
                                type: 'object',
                                required: ['color', 'weight'],
                                properties: [
                                    new OA\Property(
                                        property: 'color',
                                        type: 'string',
                                        example: '#FF0000',
                                        description: 'Hex color code'
                                    ),
                                    new OA\Property(
                                        property: 'weight',
                                        type: 'number',
                                        minimum: 0.05,
                                        maximum: 1.0,
                                        example: 0.5,
                                        description: 'Weight of the color (0.05 to 1)'
                                    ),
                                ]
                            )
                        ),
                    ]
                ),
                new OA\Property(
                    property: 'person_generation',
                    type: 'string',
                    enum: ['dont_allow', 'allow_adult', 'allow_all'],
                    example: 'allow_adult'
                ),
                new OA\Property(
                    property: 'safety_settings',
                    type: 'string',
                    enum: ['block_low_and_above', 'block_medium_and_above', 'block_only_high', 'block_none'],
                    example: 'block_low_and_above'
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Image(s) generated successfully',
        content: new OA\JsonContent(
            example: [
                'data' => [
                    'task_id' => 'd1686bd7-ffeb-4914-bffe-5a8db018ac3b',
                    'status' => 'CREATED',
                    'generated' => [],
                ],
            ]
        )
    )]
    public function generateImagen3(Imagen3GenerateRequest $request)
    {
        $data = Imagen3GenerateData::from($request->validated());

        $result = $this->service->generateImagen3Image($data);

        return response()->json($result);
    }

    #[OA\Get(
        path: '/api/freepik/text-to-image/imagen3/status/{task_id}',
        operationId: 'getImagen3TaskStatus',
        description: 'Get the status of the Imagen3 task',
        summary: 'Freepik Imagen3 Task Status',
        tags: ['Freepik'],
    )]
    #[OA\Parameter(
        name: 'task_id',
        in: 'path',
        required: true,
        description: 'ID of the Imagen3 generation task',
        schema: new OA\Schema(type: 'string', example: '046b6c7f-0b8a-43b9-b35d-6489e6daee91'),
    )]
    #[OA\Response(
        response: 200,
        description: 'The task status and generated images if available',
        content: new OA\JsonContent(
            required: ['data'],
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'object',
                    required: ['generated', 'task_id', 'status'],
                    properties: [
                        new OA\Property(
                            property: 'generated',
                            type: 'array',
                            description: 'List of generated image URLs',
                            items: new OA\Items(type: 'string', format: 'uri')
                        ),
                        new OA\Property(
                            property: 'task_id',
                            type: 'string',
                            description: 'The task ID'
                        ),
                        new OA\Property(
                            property: 'status',
                            type: 'string',
                            description: 'Status of the task',
                            enum: ['IN_PROGRESS', 'COMPLETED', 'FAILED']
                        ),
                        new OA\Property(
                            property: 'has_nsfw',
                            type: 'array',
                            nullable: true,
                            description: 'List indicating if generated images contain NSFW content',
                            items: new OA\Items(type: 'boolean')
                        ),
                    ]
                ),
            ],
            example: [
                'data' => [
                    'task_id' => 'd20eeb40-11fc-4402-a9da-d06eb9c66f23',
                    'status' => 'COMPLETED',
                    'generated' => [
                        'https://cdn-magnific.freepik.com/imagen3_d20eeb40-11fc-4402-a9da-d06eb9c66f23_0.png?token=exp=1751007773~hmac=0efd3dde93656145f6b951922491d150e3e4a1cb8aba2ab06144d3629060215d',
                    ],
                ],
            ]
        )
    )]
    public function getImagen3TaskStatus(?string $task_id = null)
    {
        $taskId = $task_id ?? request()->input('task_id');

        $validator = Validator::make(['task_id' => $taskId], [
            'task_id' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $this->service->getImagen3TaskStatus($taskId);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/freepik/text-to-image/flux-dev',
        operationId: 'generateFluxDevImage',
        tags: ['Freepik'],
        summary: 'Generate image using Flux Dev engine',
        description: 'Convert descriptive text input into images using Freepik Flux Dev AI engine.',
        security: [['bearerAuth' => []]],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'prompt', type: 'string', example: 'A futuristic city floating in the sky'),
                new OA\Property(
                    property: 'aspect_ratio',
                    type: 'string',
                    enum: [
                        'square_1_1',
                        'classic_4_3',
                        'traditional_3_4',
                        'widescreen_16_9',
                        'social_story_9_16',
                        'standard_3_2',
                        'portrait_2_3',
                        'horizontal_2_1',
                        'vertical_1_2',
                        'social_post_4_5',
                    ],
                    example: 'square_1_1'
                ),
                new OA\Property(
                    property: 'styling',
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'effects',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'color', type: 'string', enum: ['softhue', 'b&w', 'goldglow', 'vibrant', 'coldneon'], example: 'softhue'),
                                new OA\Property(property: 'framing', type: 'string', enum: ['portrait', 'lowangle', 'midshot', 'wideshot', 'tiltshot', 'aerial'], example: 'portrait'),
                                new OA\Property(property: 'lightning', type: 'string', enum: ['iridescent', 'dramatic', 'goldenhour', 'longexposure', 'indorlight', 'flash', 'neon'], example: 'iridescent'),
                            ]
                        ),
                        new OA\Property(
                            property: 'colors',
                            type: 'array',
                            minItems: 1,
                            maxItems: 5,
                            items: new OA\Items(
                                type: 'object',
                                required: ['color', 'weight'],
                                properties: [
                                    new OA\Property(property: 'color', type: 'string', example: '#FF0000'),
                                    new OA\Property(property: 'weight', type: 'number', minimum: 0.05, maximum: 1.0, example: 0.5),
                                ]
                            )
                        ),
                    ]
                ),
                new OA\Property(property: 'seed', type: 'integer', minimum: 1, maximum: 4294967295, example: 2147483648),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Task created successfully',
        content: new OA\JsonContent(
            example: [
                'data' => [
                    'task_id' => 'd1686bd7-ffeb-4914-bffe-5a8db018ac3b',
                    'status' => 'CREATED',
                    'generated' => [],
                ],
            ]
        )
    )]
    public function generateFluxDevImage(FluxDevGenerateRequest $request)
    {
        $data = FluxDevGenerateData::from($request->validated());

        $result = $this->service->generateFluxDevImage($data);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/freepik/text-to-image/flux-dev/status/{task_id}',
        operationId: 'getFluxDevTaskStatus',
        description: 'Get the status of the Flux Dev image generation task',
        summary: 'Freepik Flux Dev Task Status',
        tags: ['Freepik'],
        security: [['bearerAuth' => []]]
    )]
    #[OA\Parameter(
        name: 'task_id',
        in: 'path',
        required: true,
        description: 'ID of the Flux Dev generation task',
        schema: new OA\Schema(type: 'string', example: '046b6c7f-0b8a-43b9-b35d-6489e6daee91')
    )]
    #[OA\Response(
        response: 200,
        description: 'The task status and generated images if available',
        content: new OA\JsonContent(
            required: ['data'],
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'object',
                    required: ['generated', 'task_id', 'status'],
                    properties: [
                        new OA\Property(
                            property: 'generated',
                            type: 'array',
                            description: 'List of generated image URLs',
                            items: new OA\Items(type: 'string', format: 'uri')
                        ),
                        new OA\Property(
                            property: 'task_id',
                            type: 'string',
                            description: 'The task ID'
                        ),
                        new OA\Property(
                            property: 'status',
                            type: 'string',
                            enum: ['IN_PROGRESS', 'COMPLETED', 'FAILED'],
                            description: 'Status of the task'
                        ),
                    ]
                ),
            ],
            example: [
                'data' => [
                    'task_id' => 'd20eeb40-11fc-4402-a9da-d06eb9c66f23',
                    'status' => 'COMPLETED',
                    'generated' => [
                        'https://cdn-magnific.freepik.com/imagen3_d20eeb40-11fc-4402-a9da-d06eb9c66f23_0.png?token=exp=1751007773~hmac=0efd3dde93656145f6b951922491d150e3e4a1cb8aba2ab06144d3629060215d',
                    ],
                ],
            ]
        )
    )]
    public function getFluxDevTaskStatus(?string $task_id = null)
    {
        $taskId = $task_id ?? request()->input('task_id');

        $validator = Validator::make(['task_id' => $taskId], [
            'task_id' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $this->service->getFluxDevTaskStatus($taskId);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/freepik/text-to-image/reimagine-flux',
        operationId: 'reimagineFluxImage',
        summary: '(Beta) Reimagine Flux - Generate image from base64 input + prompt',
        description: 'Reimagine an input image using a text prompt and Freepik’s Flux AI engine.',
        tags: ['Freepik'],
        security: [['bearerAuth' => []]],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['image'],
            properties: [
                new OA\Property(property: 'image_cid', type: 'string', format: 'byte', description: 'cid of image', example: 'QmPE5opZZhpeHypZzG3qJE5cbCNZ28SibBa9xo4MqsgF9H'),
                new OA\Property(property: 'prompt', type: 'string', description: 'Optional prompt for imagination', example: 'A beautiful sunset over a calm ocean'),
                new OA\Property(property: 'imagination', type: 'string', enum: ['wild', 'subtle', 'vivid'], description: 'Imagination type', example: 'wild'),
                new OA\Property(property: 'aspect_ratio', type: 'string', enum: [
                    'original',
                    'square_1_1',
                    'classic_4_3',
                    'traditional_3_4',
                    'widescreen_16_9',
                    'social_story_9_16',
                    'standard_3_2',
                    'portrait_2_3',
                    'horizontal_2_1',
                    'vertical_1_2',
                    'social_post_4_5',
                ], description: 'Aspect ratio of the generated image', example: 'square_1_1'),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Success - Image reimagined and task status returned',
        content: new OA\JsonContent(
            example: [
                'data' => [
                    'task_id' => 'a44b311d-7bb4-4ebe-8150-ae01947162c0',
                    'status' => 'COMPLETED',
                    'generated' => [
                        'https://storage.googleapis.com/fc-magnific/a44b311d-7bb4-4ebe-8150-ae01947162c0.jpeg?X-Goog-Algorithm=GOOG4-RSA-SHA256&X-Goog-Credential=magnific-ai-api-sa%40fc-gke-pro-rev1.iam.gserviceaccount.com%2F20250627%2Fauto%2Fstorage%2Fgoog4_request&X-Goog-Date=20250627T060456Z&X-Goog-Expires=604800&X-Goog-SignedHeaders=host&X-Goog-Signature=80f8a54465c7f939d645f2a95e86533aeff4cb3fdc2f63955e31b163ad8adb5fe6b0ac8be9ea5772f4abc06a8ec0a8e1a47b0dac3096caf6cd526523f0346a1c70cef2620c22cb71d375c57b8656e66f29b16f023db3f42a3c8967e9fb75a51e14d7d31f8c56f39a2c0ef08064fdb914b6779510a65347044fd69ca61e12903019a4f5c29b27906e62de430d6898cee00f1c21768235890c954f26931b1ce32840d4b138acdb29afe337db5ba243b34352e3df8599b27af2109bca9f84eac636865ea571e4ac75d36cac4661db5b879fab1095fcf3840f80525bd5d92418c0755d1d4031ed0e9a62f7aff8581016c241a7cafa5c074c49c741330cff34f08633',
                    ],
                ],
            ]
        )
    )]
    public function reimagineFlux(ReimagineFluxRequest $request)
    {
        $data = ReimagineFluxData::from($request->validated());

        $result = $this->service->reimagineFluxImage($data);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/freepik/image-editing/upscaler',
        operationId: 'upscaleImage',
        tags: ['Freepik'],
        summary: 'Upscale an image using Magnific AI',
        description: 'Upscale an image using Freepik\'s Magnific AI. Accepts a base64 image, aspect ratio, scale factor, optimization and styling preferences.',
        security: [['bearerAuth' => []]],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['image'],
            properties: [
                new OA\Property(
                    property: 'image_cid',
                    type: 'string',
                    description: 'URL of the image to upscale',
                    example: 'QmPE5opZZhpeHypZzG3qJE5cbCNZ28SibBa9xo4MqsgF9H'
                ),
                new OA\Property(
                    property: 'scale_factor',
                    type: 'string',
                    enum: ['2x', '4x', '8x', '16x'],
                    example: '2x'
                ),
                new OA\Property(
                    property: 'optimized_for',
                    type: 'string',
                    enum: [
                        'standard',
                        'soft_portraits',
                        'hard_portraits',
                        'art_n_illustration',
                        'videogame_assets',
                        'nature_n_landscapes',
                        'films_n_photography',
                        '3d_renders',
                        'science_fiction_n_horror',
                    ],
                    example: 'standard'
                ),
                new OA\Property(
                    property: 'prompt',
                    type: 'string',
                    nullable: true,
                    example: 'A vivid and high-detail fantasy landscape with towering crystal mountains, glowing waterfalls, and enchanted forests under a twilight sky'
                ),
                new OA\Property(
                    property: 'creativity',
                    type: 'integer',
                    minimum: -10,
                    maximum: 10,
                    example: 5
                ),
                new OA\Property(
                    property: 'hdr',
                    type: 'integer',
                    minimum: -10,
                    maximum: 10,
                    example: 3
                ),
                new OA\Property(
                    property: 'resemblance',
                    type: 'integer',
                    minimum: -10,
                    maximum: 10,
                    example: 0
                ),
                new OA\Property(
                    property: 'fractality',
                    type: 'integer',
                    minimum: -10,
                    maximum: 10,
                    example: -2
                ),
                new OA\Property(
                    property: 'engine',
                    type: 'string',
                    enum: ['automatic', 'magnific_illusio', 'magnific_sharpy', 'magnific_sparkle'],
                    example: 'automatic'
                ),
            ],
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Upscaling task initiated successfully',
        content: new OA\JsonContent(
            example: [
                'data' => [
                    'task_id' => '14eb9004-41ef-4a16-97f2-a558aab35578',
                    'status' => 'CREATED',
                    'generated' => [],
                ],
            ]
        )
    )]
    public function upscale(UpscaleImageRequest $request)
    {
        $data = UpscaleImageData::from($request->validated());

        $result = $this->service->upscaleImage($data);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/freepik/image-editing/upscaler/status/{task_id}',
        operationId: 'getUpscalerTaskStatus',
        description: 'Get the status of the Magnific image upscaling task',
        summary: 'Check Magnific Upscaler Task Status',
        tags: ['Freepik'],
        security: [['bearerAuth' => []]],
    )]
    #[OA\Parameter(
        name: 'task_id',
        in: 'path',
        required: true,
        description: 'ID of the upscaling task',
        schema: new OA\Schema(type: 'string', example: '046b6c7f-0b8a-43b9-b35d-6489e6daee91'),
    )]
    #[OA\Response(
        response: 200,
        description: 'Task status and generated images if available',
        content: new OA\JsonContent(
            example: [
                'data' => [
                    'task_id' => '14eb9004-41ef-4a16-97f2-a558aab35578',
                    'status' => 'COMPLETED',
                    'generated' => [
                        'https://ai-statics.freepik.com/content/mg-upscaler/syn56t4xlfgodjvkhlksx2t3be/output.png?token=exp=1751090771~hmac=8a99cf9fcbbecb72e35d8a6ddfea4b2d',
                    ],
                ],
            ]
        )
    )]
    public function getUpscalerTaskStatus(?string $task_id = null)
    {
        $taskId = $task_id ?? request()->input('task_id');

        $validator = Validator::make(['task_id' => $taskId], [
            'task_id' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $this->service->getUpscalerTaskStatus($taskId);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/freepik/image-editing/relight',
        operationId: 'relightImage',
        description: 'Relight an image using AI with custom styling, light source, and enhancement parameters.',
        summary: 'Relight Image via Freepik AI',
        tags: ['Freepik'],
        security: [['bearerAuth' => []]]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['image_cid'],
            properties: [
                new OA\Property(
                    property: 'image_cid',
                    type: 'string',
                    description: 'cid image to relight',
                    example: 'QmPE5opZZhpeHypZzG3qJE5cbCNZ28SibBa9xo4MqsgF9H'
                ),
                new OA\Property(
                    property: 'prompt',
                    type: 'string',
                    example: 'A sunlit forest clearing at golden hour with rays piercing through the trees'
                ),
                new OA\Property(
                    property: 'transfer_light_from_reference_image_cid',
                    type: 'string',
                    nullable: true,
                    example: 'QmTjCdTXQ2M1JPQHMuciYGQ2BWLVXum73PEJ8KY1znV4TV'
                ),
                new OA\Property(
                    property: 'transfer_light_from_lightmap_cid',
                    type: 'string',
                    nullable: true,
                    example: 'QmPnAKihJS1shKqnA4UqQ6bvkw29j8yFW4MJTb6KZA1e6Q'
                ),
                new OA\Property(
                    property: 'light_transfer_strength',
                    type: 'integer',
                    minimum: 0,
                    maximum: 100,
                    default: 100,
                    example: 100
                ),
                new OA\Property(
                    property: 'interpolate_from_original',
                    type: 'boolean',
                    default: false,
                    example: false
                ),
                new OA\Property(
                    property: 'change_background',
                    type: 'boolean',
                    default: true,
                    example: true
                ),
                new OA\Property(
                    property: 'style',
                    type: 'string',
                    enum: ['standard', 'darker_but_realistic', 'clean', 'smooth', 'brighter', 'contrasted_n_hdr', 'just_composition'],
                    default: 'standard',
                    example: 'standard'
                ),
                new OA\Property(
                    property: 'preserve_details',
                    type: 'boolean',
                    default: true,
                    example: true
                ),
                new OA\Property(
                    property: 'advanced_settings',
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'whites',
                            type: 'integer',
                            minimum: 0,
                            maximum: 100,
                            default: 50,
                            example: 50
                        ),
                        new OA\Property(
                            property: 'blacks',
                            type: 'integer',
                            minimum: 0,
                            maximum: 100,
                            default: 50,
                            example: 50
                        ),
                        new OA\Property(
                            property: 'brightness',
                            type: 'integer',
                            minimum: 0,
                            maximum: 100,
                            default: 50,
                            example: 50
                        ),
                        new OA\Property(
                            property: 'contrast',
                            type: 'integer',
                            minimum: 0,
                            maximum: 100,
                            default: 50,
                            example: 50
                        ),
                        new OA\Property(
                            property: 'saturation',
                            type: 'integer',
                            minimum: 0,
                            maximum: 100,
                            default: 50,
                            example: 50
                        ),
                        new OA\Property(
                            property: 'engine',
                            type: 'string',
                            enum: ['automatic', 'balanced', 'cool', 'real', 'illusio', 'fairy', 'colorful_anime', 'hard_transform', 'softy'],
                            default: 'automatic',
                            example: 'automatic'
                        ),
                        new OA\Property(
                            property: 'transfer_light_a',
                            type: 'string',
                            enum: ['automatic', 'low', 'medium', 'normal', 'high', 'high_on_faces'],
                            default: 'automatic',
                            example: 'automatic'
                        ),
                        new OA\Property(
                            property: 'transfer_light_b',
                            type: 'string',
                            enum: ['automatic', 'composition', 'straight', 'smooth_in', 'smooth_out', 'smooth_both', 'reverse_both', 'soft_in', 'soft_out', 'soft_mid', 'strong_mid', 'style_shift', 'strong_shift'],
                            default: 'automatic',
                            example: 'automatic'
                        ),
                        new OA\Property(
                            property: 'fixed_generation',
                            type: 'boolean',
                            default: false,
                            example: false
                        ),
                    ],
                ),
            ],
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'The relight process has started.',
        content: new OA\JsonContent(
            example: [
                'data' => [
                    'task_id' => 'd1686bd7-ffeb-4914-bffe-5a8db018ac3b',
                    'status' => 'CREATED',
                    'generated' => [],
                ],
            ]
        )
    )]
    public function relight(RelightImageRequest $request)
    {
        $data = RelightImageData::from($request->validated());

        $result = $this->service->relightImage($data);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/freepik/image-editing/relight/status/{task_id}',
        operationId: 'getRelightTaskStatus',
        description: 'Get the status of the Relight task',
        summary: 'Freepik Relight Task Status',
        tags: ['Freepik'],
        security: [['bearerAuth' => []]],
    )]
    #[OA\Parameter(
        name: 'task_id',
        in: 'path',
        required: true,
        description: 'ID of the Relight task',
        schema: new OA\Schema(type: 'string', example: '046b6c7f-0b8a-43b9-b35d-6489e6daee91'),
    )]
    #[OA\Response(
        response: 200,
        description: 'The task status and generated images if available',
        content: new OA\JsonContent(
            example: [
                'data' => [
                    'task_id' => '14eb9004-41ef-4a16-97f2-a558aab35578',
                    'status' => 'COMPLETED',
                    'generated' => [
                        'https://ai-statics.freepik.com/content/mg-upscaler/syn56t4xlfgodjvkhlksx2t3be/output.png?token=exp=1751090771~hmac=8a99cf9fcbbecb72e35d8a6ddfea4b2d',
                    ],
                ],
            ]
        )
    )]
    public function getRelightTaskStatus(?string $task_id = null)
    {
        $taskId = $task_id ?? request()->input('task_id');

        $validator = Validator::make(['task_id' => $taskId], [
            'task_id' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $this->service->getRelightTaskStatus($taskId);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/freepik/image-editing/style-transfer',
        operationId: 'createStyleTransfer',
        description: 'Style transfer an image using AI',
        summary: 'Freepik Image Style Transfer',
        tags: ['Freepik'],
        security: [['bearerAuth' => []]],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['image_cid', 'reference_image_cid'],
            properties: [
                new OA\Property(
                    property: 'image_cid',
                    type: 'string',
                    description: 'Base64 Image to style transfer',
                    example: 'QmPE5opZZhpeHypZzG3qJE5cbCNZ28SibBa9xo4MqsgF9H'
                ),
                new OA\Property(
                    property: 'reference_image_cid',
                    type: 'string',
                    description: 'Base64 Reference image for style transfer',
                    example: 'QmTjCdTXQ2M1JPQHMuciYGQ2BWLVXum73PEJ8KY1znV4TV'
                ),
                new OA\Property(
                    property: 'prompt',
                    type: 'string',
                    nullable: true,
                    example: 'A peaceful mountain cabin at sunrise, surrounded by pine trees and light morning mist'
                ),
                new OA\Property(
                    property: 'style_strength',
                    type: 'integer',
                    minimum: 0,
                    maximum: 100,
                    default: 100,
                    example: 100
                ),
                new OA\Property(
                    property: 'structure_strength',
                    type: 'integer',
                    minimum: 0,
                    maximum: 100,
                    default: 50,
                    example: 50
                ),
                new OA\Property(
                    property: 'is_portrait',
                    type: 'boolean',
                    default: false,
                    example: false
                ),
                new OA\Property(
                    property: 'portrait_style',
                    type: 'string',
                    enum: ['standard', 'pop', 'super_pop'],
                    default: 'standard',
                    description: 'Portrait style',
                    example: 'standard'
                ),
                new OA\Property(
                    property: 'portrait_beautifier',
                    type: 'string',
                    enum: ['beautify_face', 'beautify_face_max'],
                    nullable: true,
                    description: 'Portrait beautifier',
                    example: 'beautify_face'
                ),
                new OA\Property(
                    property: 'flavor',
                    type: 'string',
                    enum: ['faithful', 'gen_z', 'psychedelia', 'detaily', 'clear', 'donotstyle', 'donotstyle_sharp'],
                    default: 'faithful',
                    description: 'Flavor of the transferring style',
                    example: 'faithful'
                ),
                new OA\Property(
                    property: 'engine',
                    type: 'string',
                    enum: ['balanced', 'definio', 'illusio', '3d_cartoon', 'colorful_anime', 'caricature', 'real', 'super_real', 'softy'],
                    default: 'balanced',
                    description: 'Engine for style transfer',
                    example: 'balanced'
                ),
                new OA\Property(
                    property: 'fixed_generation',
                    type: 'boolean',
                    default: false,
                    description: 'Fixed generation flag',
                    example: false
                ),
            ],
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'The request has succeeded and the Style Transfer process has started.',
        content: new OA\JsonContent(
            example: [
                'data' => [
                    'task_id' => 'd1686bd7-ffeb-4914-bffe-5a8db018ac3b',
                    'status' => 'CREATED',
                    'generated' => [],
                ],
            ]
        ),
    )]
    public function styleTransfer(StyleTransferRequest $request)
    {
        $data = StyleTransferData::from($request->validated());

        $result = $this->service->styleTransfer($data);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/freepik/image-editing/style-transfer/status/{task_id}',
        operationId: 'getStyleTransferTaskStatus',
        description: 'Get the status of the Style Transfer task',
        summary: 'Freepik Style Transfer Task Status',
        tags: ['Freepik'],
        security: [['bearerAuth' => []]],
    )]
    #[OA\Parameter(
        name: 'task_id',
        in: 'path',
        required: true,
        description: 'ID of the Style Transfer task',
        schema: new OA\Schema(type: 'string', example: '046b6c7f-0b8a-43b9-b35d-6489e6daee91'),
    )]
    #[OA\Response(
        response: 200,
        description: 'The task status and generated images if available',
        content: new OA\JsonContent(
            example: [
                'data' => [
                    'task_id' => '14eb9004-41ef-4a16-97f2-a558aab35578',
                    'status' => 'COMPLETED',
                    'generated' => [
                        'https://ai-statics.freepik.com/content/mg-upscaler/syn56t4xlfgodjvkhlksx2t3be/output.png?token=exp=1751090771~hmac=8a99cf9fcbbecb72e35d8a6ddfea4b2d',
                    ],
                ],
            ]
        )
    )]
    public function getStyleTransferTaskStatus(?string $task_id = null)
    {
        $taskId = $task_id ?? request()->input('task_id');

        $validator = Validator::make(['task_id' => $taskId], [
            'task_id' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $this->service->getStyleTransferTaskStatus($taskId);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/freepik/image-editing/remove-background',
        operationId: 'removeImageBackground',
        description: 'Remove the background of an image via URL',
        summary: 'Freepik Remove Background',
        tags: ['Freepik'],
        security: [['bearerAuth' => []]],
    )]
    #[OA\RequestBody(
        required: true,
        description: 'Image URL form data',
        content: new OA\MediaType(
            mediaType: 'application/x-www-form-urlencoded',
            schema: new OA\Schema(
                type: 'object',
                required: ['image_cid'],
                properties: [
                    new OA\Property(
                        property: 'image_cid',
                        type: 'string',
                        format: 'uri',
                        description: 'The URL of the image whose background needs to be removed',
                        example: 'QmPE5opZZhpeHypZzG3qJE5cbCNZ28SibBa9xo4MqsgF9H',
                    ),
                ]
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful background removal',
        content: new OA\JsonContent(
            type: 'object',
            required: ['original', 'high_resolution', 'preview', 'url'],
            properties: [
                new OA\Property(
                    property: 'original',
                    type: 'string',
                    format: 'uri',
                    description: 'URL of the original image'
                ),
                new OA\Property(
                    property: 'high_resolution',
                    type: 'string',
                    format: 'uri',
                    description: 'URL of the high-resolution image with background removed'
                ),
                new OA\Property(
                    property: 'preview',
                    type: 'string',
                    format: 'uri',
                    description: 'URL of the preview image'
                ),
                new OA\Property(
                    property: 'url',
                    type: 'string',
                    format: 'uri',
                    description: 'Direct URL to download the high-resolution image'
                ),
            ],
            example: [
                'original' => 'https://api.freepik.com/v1/ai/beta/images/original/f6ff89df-f14e-4eca-936a-308ef404cfa8/thumbnail.jpg',
                'high_resolution' => 'https://api.freepik.com/v1/ai/beta/images/download/f6ff89df-f14e-4eca-936a-308ef404cfa8/high.png',
                'preview' => 'https://api.freepik.com/v1/ai/beta/images/download/f6ff89df-f14e-4eca-936a-308ef404cfa8/preview.png',
                'url' => 'https://api.freepik.com/v1/ai/beta/images/download/f6ff89df-f14e-4eca-936a-308ef404cfa8/high.png',
            ]
        )
    )]
    public function removeBackgroundFromImage(RemoveBackgroundRequest $request)
    {
        $data = RemoveBackgroundData::from($request->validated());

        $result = $this->service->removeBackground($data);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/freepik/image-editing/image-expand/flux-pro',
        operationId: 'imageExpandFluxPro',
        description: 'Expand an image using AI Flux Pro',
        summary: 'Image Expand - Flux Pro',
        tags: ['Freepik'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['image_cid'],
                properties: [
                    new OA\Property(
                        property: 'image_cid',
                        type: 'string',
                        description: 'CID image to expand',
                        example: 'QmPE5opZZhpeHypZzG3qJE5cbCNZ28SibBa9xo4MqsgF9H'
                    ),
                    new OA\Property(
                        property: 'prompt',
                        type: 'string',
                        nullable: true,
                        description: 'Description to guide expansion',
                        example: 'A panoramic view of a serene beach with gentle waves, golden sand, and a vibrant sunset sky extending beyond the frame'
                    ),
                    new OA\Property(
                        property: 'left',
                        type: 'integer',
                        nullable: true,
                        minimum: 0,
                        maximum: 2048,
                        description: 'Pixels to expand on the left',
                        example: 2048
                    ),
                    new OA\Property(
                        property: 'right',
                        type: 'integer',
                        nullable: true,
                        minimum: 0,
                        maximum: 2048,
                        description: 'Pixels to expand on the right',
                        example: 2048
                    ),
                    new OA\Property(
                        property: 'top',
                        type: 'integer',
                        nullable: true,
                        minimum: 0,
                        maximum: 2048,
                        description: 'Pixels to expand on the top',
                        example: 2048
                    ),
                    new OA\Property(
                        property: 'bottom',
                        type: 'integer',
                        nullable: true,
                        minimum: 0,
                        maximum: 2048,
                        description: 'Pixels to expand on the bottom',
                        example: 2048
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'The task exists and the status is returned',
                content: new OA\JsonContent(
                    example: [
                        'data' => [
                            'task_id' => 'd1686bd7-ffeb-4914-bffe-5a8db018ac3b',
                            'status' => 'CREATED',
                            'generated' => [],
                        ],
                    ]
                )
            ),
        ]
    )]
    public function imageExpandFluxPro(ImageExpandFluxProRequest $request)
    {
        $data = ImageExpandFluxProData::from($request->validated());

        $result = $this->service->imageExpandFluxPro($data);

        return $this->logAndResponse($result);
    }

    #[OA\Get(
        path: '/api/freepik/image-editing/image-expand/flux-pro/status/{task_id}',
        operationId: 'getImageExpandFluxProTaskStatus',
        description: 'Get the status of one image expand task',
        summary: 'Freepik Image Expand Flux Pro Task Status',
        tags: ['Freepik'],
    )]
    #[OA\Parameter(
        name: 'task_id',
        in: 'path',
        required: true,
        description: 'ID of the image expand task',
        schema: new OA\Schema(type: 'string', example: '046b6c7f-0b8a-43b9-b35d-6489e6daee91'),
    )]
    #[OA\Response(
        response: 200,
        description: 'The task status and generated images if available',
        content: new OA\JsonContent(
            example: [
                'data' => [
                    'task_id' => '14eb9004-41ef-4a16-97f2-a558aab35578',
                    'status' => 'COMPLETED',
                    'generated' => [
                        'https://ai-statics.freepik.com/content/mg-upscaler/syn56t4xlfgodjvkhlksx2t3be/output.png?token=exp=1751090771~hmac=8a99cf9fcbbecb72e35d8a6ddfea4b2d',
                    ],
                ],
            ]
        )
    )]
    public function getImageExpandFluxProTaskStatus(?string $task_id = null): JsonResponse
    {

        $taskId = $task_id ?? request()->input('task_id');

        $validator = Validator::make(['task_id' => $taskId], [
            'task_id' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $this->service->getImageExpandFluxProTaskStatus($taskId);

        return $this->logAndResponse($result);
    }

    public function handleWebhook(Request $request)
    {
        $webhookId = $request->header('webhook-id');
        $timestamp = $request->header('webhook-timestamp');
        $signatureHeader = $request->header('webhook-signature');
        $rawBody = $request->getContent();

        if (!$webhookId || !$timestamp || !$signatureHeader) {
            Log::warning('Missing Freepik webhook headers');

            return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $content = "{$webhookId}.{$timestamp}.{$rawBody}";
        $secret = config('services.freepik.webhook_secret');
        $computedSignature = base64_encode(hash_hmac('sha256', $content, $secret, true));

        $valid = collect(explode(' ', $signatureHeader))
            ->map(fn ($pair) => explode(',', $pair)[1] ?? null)
            ->contains(fn ($sig) => hash_equals($sig, $computedSignature));

        if (!$valid) {
            Log::error('Invalid Freepik webhook signature', ['computed' => $computedSignature]);

            return response()->json(['message' => 'Invalid signature'], Response::HTTP_UNAUTHORIZED);
        }

        $payload = $request->all();
        Log::info('Verified Freepik webhook received', $payload);

        // 🔁 Log the webhook to cache
        $this->service->setWebhookResult($payload);

        return response()->json(['message' => 'Webhook verified and logged'], Response::HTTP_OK);
    }
}
