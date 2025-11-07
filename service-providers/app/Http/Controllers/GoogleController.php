<?php

namespace App\Http\Controllers;

use App\Data\Request\Google\SearchImageWithOperatorsData;
use App\Data\Request\Google\SearchWebWithOperatorsData;
use App\Http\Requests\Google\SearchImageWithOperatorsRequest;
use App\Http\Requests\Google\SearchWebWithOperatorsRequest;
use App\Services\GoogleService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class GoogleController extends BaseController
{
    public function __construct(protected GoogleService $service) {}

    #[OA\Post(
        path: '/api/google/search_web_with_operators',
        operationId: 'searchWebWithOperators',
        summary: 'Web Search with Operators',
        description: 'Performs a web search using various Google API operators.',
        tags: ['Google']
    )]
    #[OA\QueryParameter(name: 'q', description: 'Search query', required: true, schema: new OA\Schema(type: 'string', example: 'openai GPT-4'))]
    #[OA\QueryParameter(name: 'c2coff', description: 'Turns country restriction off or on. 1: Disabled, 0: Enabled (default)', required: false, schema: new OA\Schema(type: 'string', enum: ['0', '1'], nullable: true))]
    #[OA\QueryParameter(name: 'cr', description: 'Country restricts search results (e.g., "countryUS")', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'dateRestrict', description: 'Restricts search results to a date range (e.g., "d[number]" for days, "w[number]" for weeks, "m[number]" for months)', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'exactTerms', description: 'Exact phrase to search for', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'excludeTerms', description: 'Terms to exclude from search', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'fileType', description: 'Restricts results to specific file type (e.g., pdf)', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'filter', description: 'Duplicate content filter (0=off, 1=on)', required: false, schema: new OA\Schema(type: 'string', enum: ['0', '1'], nullable: true))]
    #[OA\QueryParameter(name: 'gl', description: 'Geolocation country code (2-letter ISO)', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'highRange', description: 'High price range filter', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'hl', description: 'Interface language (e.g., en)', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'hq', description: 'Additional search terms to append', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'linkSite', description: 'Restricts results to pages linking to a site', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'lowRange', description: 'Low price range filter', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'lr', description: 'Restricts by language', required: false, schema: new OA\Schema(type: 'string', enum: ['lang_ar', 'lang_bg', 'lang_ca', 'lang_cs', 'lang_da', 'lang_de', 'lang_el', 'lang_en', 'lang_es', 'lang_et', 'lang_fi', 'lang_fr', 'lang_hr', 'lang_hu', 'lang_id', 'lang_is', 'lang_it', 'lang_iw', 'lang_ja', 'lang_ko', 'lang_lt', 'lang_lv', 'lang_nl', 'lang_no', 'lang_pl', 'lang_pt', 'lang_ro', 'lang_ru', 'lang_sk', 'lang_sl', 'lang_sr', 'lang_sv', 'lang_tr', 'lang_zh-CN', 'lang_zh-TW'], nullable: true))]
    #[OA\QueryParameter(name: 'num', description: 'Number of results', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 10, nullable: true))]
    #[OA\QueryParameter(name: 'orTerms', description: 'Alternate terms for the search', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'rights', description: 'Usage rights filter', required: false, schema: new OA\Schema(type: 'string', enum: ['cc_publicdomain', 'cc_attribute', 'cc_sharealike', 'cc_noncommercial', 'cc_nonderived'], nullable: true))]
    #[OA\QueryParameter(name: 'safe', description: 'SafeSearch setting', required: false, schema: new OA\Schema(type: 'string', enum: ['active', 'off'], nullable: true))]
    #[OA\QueryParameter(name: 'siteSearch', description: 'Restricts results to a specific site', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'siteSearchFilter', description: 'Include or exclude site search', required: false, schema: new OA\Schema(type: 'string', enum: ['i', 'e'], nullable: true))]
    #[OA\QueryParameter(name: 'sort', description: 'Sort results by date', required: false, schema: new OA\Schema(type: 'string', example: 'date', nullable: true))]
    #[OA\QueryParameter(name: 'start', description: 'Index of the first result to return', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100, nullable: true))]
    #[OA\Response(
        response: 200,
        description: 'Successful search result',
        content: new OA\JsonContent(
            example: [
                'kind' => 'customsearch#search',
                'url' => [
                    'type' => 'application/json',
                    'template' => 'https://www.googleapis.com/customsearch/v1?q={searchTerms}&num={count?}&start={startIndex?}&lr={language?}&safe={safe?}&cx={cx?}&sort={sort?}&filter={filter?}&gl={gl?}&cr={cr?}&googlehost={googleHost?}&c2coff={disableCnTwTranslation?}&hq={hq?}&hl={hl?}&siteSearch={siteSearch?}&siteSearchFilter={siteSearchFilter?}&exactTerms={exactTerms?}&excludeTerms={excludeTerms?}&linkSite={linkSite?}&orTerms={orTerms?}&dateRestrict={dateRestrict?}&lowRange={lowRange?}&highRange={highRange?}&searchType={searchType}&fileType={fileType?}&rights={rights?}&imgSize={imgSize?}&imgType={imgType?}&imgColorType={imgColorType?}&imgDominantColor={imgDominantColor?}&alt=json',
                ],
                'queries' => [
                    'request' => [
                        [
                            'title' => 'Google Custom Search - openai GPT-4',
                            'totalResults' => '109000000',
                            'searchTerms' => 'openai GPT-4',
                            'count' => 1,
                            'startIndex' => 1,
                            'inputEncoding' => 'utf8',
                            'outputEncoding' => 'utf8',
                            'safe' => 'off',
                            'cx' => '670eb2e277d2a4540',
                        ],
                    ],
                    'nextPage' => [
                        [
                            'title' => 'Google Custom Search - openai GPT-4',
                            'totalResults' => '109000000',
                            'searchTerms' => 'openai GPT-4',
                            'count' => 1,
                            'startIndex' => 2,
                            'inputEncoding' => 'utf8',
                            'outputEncoding' => 'utf8',
                            'safe' => 'off',
                            'cx' => '670eb2e277d2a4540',
                        ],
                    ],
                ],
                'context' => [
                    'title' => 'my_app',
                ],
                'searchInformation' => [
                    'searchTime' => 0.435615,
                    'formattedSearchTime' => '0.44',
                    'totalResults' => '109000000',
                    'formattedTotalResults' => '109,000,000',
                ],
                'items' => [
                    [
                        'kind' => 'customsearch#result',
                        'title' => 'GPT-4 | OpenAI',
                        'htmlTitle' => '<b>GPT</b>-<b>4</b> | <b>OpenAI</b>',
                        'link' => 'https://openai.com/index/gpt-4-research/',
                        'displayLink' => 'openai.com',
                        'snippet' => 'Mar 14, 2023 ... We\'ve created GPT-4, the latest milestone in OpenAI\'s effort in scaling up deep learning. GPT-4 is a large multimodal model (accepting image ...',
                        'htmlSnippet' => 'Mar 14, 2023 <b>...</b> We&#39;ve created <b>GPT</b>-<b>4</b>, the latest milestone in <b>OpenAI&#39;s</b> effort in scaling up deep learning. <b>GPT</b>-<b>4</b> is a large multimodal model (accepting image&nbsp;...',
                        'formattedUrl' => 'https://openai.com/index/gpt-4-research/',
                        'htmlFormattedUrl' => 'https://<b>openai</b>.com/index/<b>gpt-4</b>-research/',
                        'pagemap' => [
                            'cse_thumbnail' => [
                                [
                                    'src' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRAXoe52pkgS4TDFN93HShju7mx3Km4sJcib6Wd9cQ8RI-AJJJ2FXedhsG5&s',
                                    'width' => '300',
                                    'height' => '168',
                                ],
                            ],
                            'metatags' => [
                                [
                                    'og:image' => 'https://images.ctfassets.net/kftzwdyauwt9/243b509f-9d19-438e-4692c3b389a2/21ae8969e83c14dc33d559d77ce6bc46/image-21.webp?w=1600&h=900&fit=fill',
                                    'og:image:width' => '1600',
                                    'og:type' => 'website',
                                    'twitter:card' => 'summary_large_image',
                                    'twitter:title' => 'GPT-4',
                                    'og:title' => 'GPT-4',
                                    'og:image:height' => '900',
                                    'twitter:image:height' => '900',
                                    'og:description' => 'We’ve created GPT-4, the latest milestone in OpenAI’s effort in scaling up deep learning. GPT-4 is a large multimodal model (accepting image and text inputs, emitting text outputs) that, while less capable than humans in many real-world scenarios, exhibits human-level performance on various professional and academic benchmarks.',
                                    'twitter:image' => 'https://images.ctfassets.net/kftzwdyauwt9/243b509f-9d19-438e-4692c3b389a2/21ae8969e83c14dc33d559d77ce6bc46/image-21.webp?w=1600&h=900&fit=fill',
                                    'twitter:site' => '@OpenAI',
                                    'twitter:image:width' => '1600',
                                    'viewport' => 'width=device-width, initial-scale=1',
                                    'twitter:description' => 'We’ve created GPT-4, the latest milestone in OpenAI’s effort in scaling up deep learning. GPT-4 is a large multimodal model (accepting image and text inputs, emitting text outputs) that, while less capable than humans in many real-world scenarios, exhibits human-level performance on various professional and academic benchmarks.',
                                    'og:locale' => 'en-US',
                                ],
                            ],
                            'cse_image' => [
                                [
                                    'src' => 'https://images.ctfassets.net/kftzwdyauwt9/243b509f-9d19-438e-4692c3b389a2/21ae8969e83c14dc33d559d77ce6bc46/image-21.webp?w=1600&h=900&fit=fill',
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        )
    )]
    public function searchWeb(SearchWebWithOperatorsRequest $request): JsonResponse
    {
        $data = SearchWebWithOperatorsData::from($request->validated());
        $response = $this->service->searchWeb($data);

        return $this->logAndResponse($response);
    }

    #[OA\Post(
        path: '/api/google/search_image_with_operators',
        operationId: 'searchImageWithOperators',
        summary: 'Image Search with Operators',
        description: 'Performs an image search using various Google API operators.',
        tags: ['Google']
    )]
    #[OA\QueryParameter(name: 'q', description: 'Search query', required: true, schema: new OA\Schema(type: 'string', example: 'espresso machine'))]
    #[OA\QueryParameter(name: 'c2coff', description: 'Turns country restriction off or on. 1: Disabled, 0: Enabled (default)', required: false, schema: new OA\Schema(type: 'string', enum: ['0', '1'], nullable: true))]
    #[OA\QueryParameter(name: 'cr', description: 'Country restricts search results', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'dateRestrict', description: 'Restricts search results to a date range', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'exactTerms', description: 'Exact phrase to search for', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'excludeTerms', description: 'Terms to exclude from search', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'fileType', description: 'Restricts results to specific file type', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'filter', description: 'Duplicate content filter', required: false, schema: new OA\Schema(type: 'string', enum: ['0', '1'], nullable: true))]
    #[OA\QueryParameter(name: 'gl', description: 'Geolocation country code', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'highRange', description: 'High price range filter', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'hl', description: 'Interface language', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'hq', description: 'Additional search terms to append', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'imgColorType', description: 'Restricts to images of a certain color type', required: false, schema: new OA\Schema(type: 'string', enum: ['color', 'gray', 'mono', 'trans'], nullable: true))]
    #[OA\QueryParameter(name: 'imgDominantColor', description: 'Restricts to images with a specific dominant color', required: false, schema: new OA\Schema(type: 'string', enum: ['black', 'blue', 'brown', 'gray', 'green', 'orange', 'pink', 'purple', 'red', 'teal', 'white', 'yellow'], nullable: true))]
    #[OA\QueryParameter(name: 'imgSize', description: 'Restricts results to a specific image size', required: false, schema: new OA\Schema(type: 'string', enum: ['huge', 'icon', 'large', 'medium', 'small', 'xlarge', 'xxlarge'], nullable: true))]
    #[OA\QueryParameter(name: 'imgType', description: 'Restricts to a specific image type', required: false, schema: new OA\Schema(type: 'string', enum: ['clipart', 'face', 'lineart', 'stock', 'photo', 'animated'], nullable: true))]
    #[OA\QueryParameter(name: 'linkSite', description: 'Restricts results to pages linking to a site', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'lowRange', description: 'Low price range filter', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'lr', description: 'Restricts by language', required: false, schema: new OA\Schema(type: 'string', enum: ['lang_ar', 'lang_bg', 'lang_ca', 'lang_cs', 'lang_da', 'lang_de', 'lang_el', 'lang_en', 'lang_es', 'lang_et', 'lang_fi', 'lang_fr', 'lang_hr', 'lang_hu', 'lang_id', 'lang_is', 'lang_it', 'lang_iw', 'lang_ja', 'lang_ko', 'lang_lt', 'lang_lv', 'lang_nl', 'lang_no', 'lang_pl', 'lang_pt', 'lang_ro', 'lang_ru', 'lang_sk', 'lang_sl', 'lang_sr', 'lang_sv', 'lang_tr', 'lang_zh-CN', 'lang_zh-TW'], nullable: true))]
    #[OA\QueryParameter(name: 'num', description: 'Number of results', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 10, nullable: true))]
    #[OA\QueryParameter(name: 'orTerms', description: 'Alternate terms for the search', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'rights', description: 'Usage rights filter', required: false, schema: new OA\Schema(type: 'string', enum: ['cc_publicdomain', 'cc_attribute', 'cc_sharealike', 'cc_noncommercial', 'cc_nonderived'], nullable: true))]
    #[OA\QueryParameter(name: 'safe', description: 'SafeSearch setting', required: false, schema: new OA\Schema(type: 'string', enum: ['active', 'off'], nullable: true))]
    #[OA\QueryParameter(name: 'siteSearch', description: 'Restricts results to a specific site', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\QueryParameter(name: 'siteSearchFilter', description: 'Include or exclude site search', required: false, schema: new OA\Schema(type: 'string', enum: ['i', 'e'], nullable: true))]
    #[OA\QueryParameter(name: 'sort', description: 'Sort results by date', required: false, schema: new OA\Schema(type: 'string', example: 'date', nullable: true))]
    #[OA\QueryParameter(name: 'start', description: 'Index of the first result to return', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100, nullable: true))]
    #[OA\Response(
        response: 200,
        description: 'Successful image search result',
        content: new OA\JsonContent(
            example: [
                'kind' => 'customsearch#search',
                'url' => [
                    'type' => 'application/json',
                    'template' => 'https://www.googleapis.com/customsearch/v1?q={searchTerms}&num={count?}&start={startIndex?}&lr={language?}&safe={safe?}&cx={cx?}&sort={sort?}&filter={filter?}&gl={gl?}&cr={cr?}&googlehost={googleHost?}&c2coff={disableCnTwTranslation?}&hq={hq?}&hl={hl?}&siteSearch={siteSearch?}&siteSearchFilter={siteSearchFilter?}&exactTerms={exactTerms?}&excludeTerms={excludeTerms?}&linkSite={linkSite?}&orTerms={orTerms?}&dateRestrict={dateRestrict?}&lowRange={lowRange?}&highRange={highRange?}&searchType={searchType}&fileType={fileType?}&rights={rights?}&imgSize={imgSize?}&imgType={imgType?}&imgColorType={imgColorType?}&imgDominantColor={imgDominantColor?}&alt=json',
                ],
                'queries' => [
                    'request' => [
                        [
                            'title' => 'Google Custom Search - espresso machine',
                            'totalResults' => '1470000000',
                            'searchTerms' => 'espresso machine',
                            'count' => 1,
                            'startIndex' => 1,
                            'inputEncoding' => 'utf8',
                            'outputEncoding' => 'utf8',
                            'safe' => 'off',
                            'cx' => '670eb2e277d2a4540',
                            'searchType' => 'image',
                        ],
                    ],
                    'nextPage' => [
                        [
                            'title' => 'Google Custom Search - espresso machine',
                            'totalResults' => '1470000000',
                            'searchTerms' => 'espresso machine',
                            'count' => 1,
                            'startIndex' => 2,
                            'inputEncoding' => 'utf8',
                            'outputEncoding' => 'utf8',
                            'safe' => 'off',
                            'cx' => '670eb2e277d2a4540',
                            'searchType' => 'image',
                        ],
                    ],
                ],
                'context' => [
                    'title' => 'my_app',
                ],
                'searchInformation' => [
                    'searchTime' => 0.400568,
                    'formattedSearchTime' => '0.40',
                    'totalResults' => '1470000000',
                    'formattedTotalResults' => '1,470,000,000',
                ],
                'items' => [
                    [
                        'kind' => 'customsearch#result',
                        'title' => 'Breville Barista Express Espresso Machine | Williams Sonoma',
                        'htmlTitle' => 'Breville Barista Express <b>Espresso Machine</b> | Williams Sonoma',
                        'link' => 'https://assets.wsimgs.com/wsimgs/rk/images/dp/wcm/202512/0020/img24o.jpg',
                        'displayLink' => 'www.williams-sonoma.com',
                        'snippet' => 'Breville Barista Express Espresso Machine | Williams Sonoma',
                        'htmlSnippet' => 'Breville Barista Express <b>Espresso Machine</b> | Williams Sonoma',
                        'mime' => 'image/jpeg',
                        'fileFormat' => 'image/jpeg',
                        'image' => [
                            'contextLink' => 'https://www.williams-sonoma.com/products/breville-barista-express-espresso-maker/?bvstate=pg:3/ct:r',
                            'height' => 710,
                            'width' => 710,
                            'byteSize' => 85306,
                            'thumbnailLink' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRq7bJ0eCHr1qIBfn1LNS4obA9GDG5uidKwfb6P-NDO9t7wuY4qJahYljyR&s',
                            'thumbnailHeight' => 140,
                            'thumbnailWidth' => 140,
                        ],
                    ],
                ],
            ]
        )
    )]
    public function searchImage(SearchImageWithOperatorsRequest $request): JsonResponse
    {
        $data = SearchImageWithOperatorsData::from($request->validated());
        $response = $this->service->searchImage($data);

        return $this->logAndResponse($response);
    }
}
