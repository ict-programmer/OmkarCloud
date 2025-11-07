<?php

namespace Database\Seeders;

use App\Http\Controllers\GoogleController;
use App\Http\Requests\Google\SearchImageWithOperatorsRequest;
use App\Http\Requests\Google\SearchWebWithOperatorsRequest;
use App\Models\ServiceProvider;
use App\Traits\ServiceProviderSeederTrait;
use Illuminate\Database\Seeder;

class GoogleCustomSearchServiceProviderSeeder extends Seeder
{
    use ServiceProviderSeederTrait;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => 'Google Custom Search With Operators'],
            [
                'parameters' => [
                    'api_key' => 'YOUR_API_KEY',
                    'cx' => 'YOUR_CUSTOM_SEARCH_ENGINE_ID',
                    'base_url' => 'https://customsearch.googleapis.com/customsearch/v1',
                    'features' => [
                        'search_web_with_operators',
                        'search_image_with_operators',
                    ],
                ],
                'is_active' => true,
                'controller_name' => GoogleController::class,
            ]
        );

        $serviceTypes = [
            [
                'name' => 'Web Search with Operators',
                'input_parameters' => [
                    'q' => ['type' => 'string', 'required' => true, 'description' => 'Search query', 'default' => 'openai GPT-4', 'userinput_rqd' => true],
                    'c2coff' => ['type' => 'string', 'required' => false, 'description' => 'Turns country restriction off or on.', 'options' => ['0', '1'], 'default' => null, 'userinput_rqd' => false],
                    'cr' => ['type' => 'string', 'required' => false, 'description' => 'Country restricts search results (e.g., "countryUS")', 'default' => 'countryUS', 'userinput_rqd' => true],
                    'dateRestrict' => ['type' => 'string', 'required' => false, 'description' => 'Restricts search results to a date range (e.g., "d[number]" for days, "w[number]" for weeks, "m[number]" for months)', 'default' => 'd10', 'userinput_rqd' => true],
                    'exactTerms' => ['type' => 'string', 'required' => false, 'description' => 'Exact phrase to search for', 'default' => 'generative ai', 'userinput_rqd' => true],
                    'excludeTerms' => ['type' => 'string', 'required' => false, 'description' => 'Terms to exclude from search', 'default' => 'language model', 'userinput_rqd' => true],
                    'fileType' => ['type' => 'string', 'required' => false, 'description' => 'Restricts results to specific file type (e.g., pdf)', 'default' => 'pdf', 'userinput_rqd' => true],
                    'filter' => ['type' => 'string', 'required' => false, 'description' => 'Duplicate content filter (0=off, 1=on)', 'options' => ['0', '1'], 'default' => '1', 'userinput_rqd' => false],
                    'gl' => ['type' => 'string', 'required' => false, 'description' => 'Geolocation country code (2-letter ISO)', 'default' => 'us', 'userinput_rqd' => true],
                    'highRange' => ['type' => 'string', 'required' => false, 'description' => 'High price range filter', 'default' => '500', 'userinput_rqd' => true],
                    'hl' => ['type' => 'string', 'required' => false, 'description' => 'Interface language (e.g., en)', 'default' => 'en', 'userinput_rqd' => true],
                    'hq' => ['type' => 'string', 'required' => false, 'description' => 'Additional search terms to append', 'default' => 'review', 'userinput_rqd' => true],
                    'linkSite' => ['type' => 'string', 'required' => false, 'description' => 'Restricts results to pages linking to a site', 'default' => 'openai.com', 'userinput_rqd' => true],
                    'lowRange' => ['type' => 'string', 'required' => false, 'description' => 'Low price range filter', 'default' => '100', 'userinput_rqd' => true],
                    'lr' => ['type' => 'string', 'required' => false, 'description' => 'Restricts by language', 'options' => ['lang_ar', 'lang_bg', 'lang_ca', 'lang_cs', 'lang_da', 'lang_de', 'lang_el', 'lang_en', 'lang_es', 'lang_et', 'lang_fi', 'lang_fr', 'lang_hr', 'lang_hu', 'lang_id', 'lang_is', 'lang_it', 'lang_iw', 'lang_ja', 'lang_ko', 'lang_lt', 'lang_lv', 'lang_nl', 'lang_no', 'lang_pl', 'lang_pt', 'lang_ro', 'lang_ru', 'lang_sk', 'lang_sl', 'lang_sr', 'lang_sv', 'lang_tr', 'lang_zh-CN', 'lang_zh-TW'], 'default' => 'lang_en', 'userinput_rqd' => false],
                    'num' => ['type' => 'integer', 'required' => false, 'description' => 'Number of results', 'min' => 1, 'max' => 10, 'default' => 10, 'userinput_rqd' => true],
                    'orTerms' => ['type' => 'string', 'required' => false, 'description' => 'Alternate terms for the search', 'default' => 'GPT-3 GPT-4', 'userinput_rqd' => true],
                    'rights' => ['type' => 'string', 'required' => false, 'description' => 'Usage rights filter', 'options' => ['cc_publicdomain', 'cc_attribute', 'cc_sharealike', 'cc_noncommercial', 'cc_nonderived'], 'default' => 'cc_publicdomain', 'userinput_rqd' => false],
                    'safe' => ['type' => 'string', 'required' => false, 'description' => 'SafeSearch setting', 'options' => ['active', 'off'], 'default' => 'active', 'userinput_rqd' => false],
                    'siteSearch' => ['type' => 'string', 'required' => false, 'description' => 'Restricts results to a specific site', 'default' => 'openai.com', 'userinput_rqd' => true],
                    'siteSearchFilter' => ['type' => 'string', 'required' => false, 'description' => 'Include or exclude site search', 'options' => ['i', 'e'], 'default' => 'i', 'userinput_rqd' => false],
                    'sort' => ['type' => 'string', 'required' => false, 'description' => 'Sort results by date', 'default' => 'date', 'userinput_rqd' => true],
                    'start' => ['type' => 'integer', 'required' => false, 'description' => 'Index of the first result to return', 'min' => 1, 'max' => 100, 'default' => 1, 'userinput_rqd' => true],
                ],
                'response' => [
                    'kind' => 'customsearch#search',
                    'url' => ['type' => 'application/json', 'template' => 'https://www.googleapis.com/customsearch/v1?q={searchTerms}&num={count?}&start={startIndex?}&lr={language?}&safe={safe?}&cx={cx?}&sort={sort?}&filter={filter?}&gl={gl?}&cr={cr?}&googlehost={googleHost?}&c2coff={disableCnTwTranslation?}&hq={hq?}&hl={hl?}&siteSearch={siteSearch?}&siteSearchFilter={siteSearchFilter?}&exactTerms={exactTerms?}&excludeTerms={excludeTerms?}&linkSite={linkSite?}&orTerms={orTerms?}&dateRestrict={dateRestrict?}&lowRange={lowRange?}&highRange={highRange?}&searchType={searchType}&fileType={fileType?}&rights={rights?}&imgSize={imgSize?}&imgType={imgType?}&imgColorType={imgColorType?}&imgDominantColor={imgDominantColor?}&alt=json'],
                    'queries' => ['request' => [['title' => 'Google Custom Search - openai GPT-4', 'totalResults' => '109000000', 'searchTerms' => 'openai GPT-4', 'count' => 1, 'startIndex' => 1, 'inputEncoding' => 'utf8', 'outputEncoding' => 'utf8', 'safe' => 'off', 'cx' => '670eb2e277d2a4540']], 'nextPage' => [['title' => 'Google Custom Search - openai GPT-4', 'totalResults' => '109000000', 'searchTerms' => 'openai GPT-4', 'count' => 1, 'startIndex' => 2, 'inputEncoding' => 'utf8', 'outputEncoding' => 'utf8', 'safe' => 'off', 'cx' => '670eb2e277d2a4540']]],
                    'context' => ['title' => 'my_app'],
                    'searchInformation' => ['searchTime' => 0.435615, 'formattedSearchTime' => '0.44', 'totalResults' => '109000000', 'formattedTotalResults' => '109,000,000'],
                    'items' => [['kind' => 'customsearch#result', 'title' => 'GPT-4 | OpenAI', 'htmlTitle' => '<b>GPT</b>-<b>4</b> | <b>OpenAI</b>', 'link' => 'https://openai.com/index/gpt-4-research/', 'displayLink' => 'openai.com', 'snippet' => 'Mar 14, 2023 ... We\'ve created GPT-4, the latest milestone in OpenAI\'s effort in scaling up deep learning. GPT-4 is a large multimodal model (accepting image ...', 'htmlSnippet' => 'Mar 14, 2023 <b>...</b> We&#39;ve created <b>GPT</b>-<b>4</b>, the latest milestone in <b>OpenAI&#39;s</b> effort in scaling up deep learning. <b>GPT</b>-<b>4</b> is a large multimodal model (accepting image&nbsp;...', 'formattedUrl' => 'https://openai.com/index/gpt-4-research/', 'htmlFormattedUrl' => 'https://<b>openai</b>.com/index/<b>gpt-4</b>-research/', 'pagemap' => ['cse_thumbnail' => [['src' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRAXoe52pkgS4TDFN93HShju7mx3Km4sJcib6Wd9cQ8RI-AJJJ2FXedhsG5&s', 'width' => '300', 'height' => '168']], 'metatags' => [['og:image' => 'https://images.ctfassets.net/kftzwdyauwt9/243b509f-9d19-438e-4692c3b389a2/21ae8969e83c14dc33d559d77ce6bc46/image-21.webp?w=1600&h=900&fit=fill', 'og:image:width' => '1600', 'og:type' => 'website', 'twitter:card' => 'summary_large_image', 'twitter:title' => 'GPT-4', 'og:title' => 'GPT-4', 'og:image:height' => '900', 'twitter:image:height' => '900', 'og:description' => 'We’ve created GPT-4, the latest milestone in OpenAI’s effort in scaling up deep learning. GPT-4 is a large multimodal model (accepting image and text inputs, emitting text outputs) that, while less capable than humans in many real-world scenarios, exhibits human-level performance on various professional and academic benchmarks.', 'twitter:image' => 'https://images.ctfassets.net/kftzwdyauwt9/243b509f-9d19-438e-4692c3b389a2/21ae8969e83c14dc33d559d77ce6bc46/image-21.webp?w=1600&h=900&fit=fill', 'twitter:site' => '@OpenAI', 'twitter:image:width' => '1600', 'viewport' => 'width=device-width, initial-scale=1', 'twitter:description' => 'We’ve created GPT-4, the latest milestone in OpenAI’s effort in scaling up deep learning. GPT-4 is a large multimodal model (accepting image and text inputs, emitting text outputs) that, while less capable than humans in many real-world scenarios, exhibits human-level performance on various professional and academic benchmarks.', 'og:locale' => 'en-US']], 'cse_image' => [['src' => 'https://images.ctfassets.net/kftzwdyauwt9/243b509f-9d19-438e-4692c3b389a2/21ae8969e83c14dc33d559d77ce6bc46/image-21.webp?w=1600&h=900&fit=fill']]]]],
                ],
                'response_path' => ['final_result' => '$.items'],
                'request_class_name' => SearchWebWithOperatorsRequest::class,
                'function_name' => 'searchWeb',
            ],
            [
                'name' => 'Image Search with Operators',
                'input_parameters' => [
                    'q' => ['type' => 'string', 'required' => true, 'description' => 'Search query', 'default' => 'espresso machine', 'userinput_rqd' => true],
                    'c2coff' => ['type' => 'string', 'required' => false, 'description' => 'Turns country restriction off or on.', 'options' => ['0', '1'], 'default' => null, 'userinput_rqd' => false],
                    'cr' => ['type' => 'string', 'required' => false, 'description' => 'Country restricts search results', 'default' => null, 'userinput_rqd' => true],
                    'dateRestrict' => ['type' => 'string', 'required' => false, 'description' => 'Restricts search results to a date range', 'default' => null, 'userinput_rqd' => true],
                    'exactTerms' => ['type' => 'string', 'required' => false, 'description' => 'Exact phrase to search for', 'default' => null, 'userinput_rqd' => true],
                    'excludeTerms' => ['type' => 'string', 'required' => false, 'description' => 'Terms to exclude from search', 'default' => null, 'userinput_rqd' => true],
                    'fileType' => ['type' => 'string', 'required' => false, 'description' => 'Restricts results to specific file type', 'default' => null, 'userinput_rqd' => true],
                    'filter' => ['type' => 'string', 'required' => false, 'description' => 'Duplicate content filter', 'options' => ['0', '1'], 'default' => null, 'userinput_rqd' => false],
                    'gl' => ['type' => 'string', 'required' => false, 'description' => 'Geolocation country code', 'default' => null, 'userinput_rqd' => true],
                    'highRange' => ['type' => 'string', 'required' => false, 'description' => 'High price range filter', 'default' => null, 'userinput_rqd' => true],
                    'hl' => ['type' => 'string', 'required' => false, 'description' => 'Interface language', 'default' => null, 'userinput_rqd' => true],
                    'hq' => ['type' => 'string', 'required' => false, 'description' => 'Additional search terms to append', 'default' => null, 'userinput_rqd' => true],
                    'imgColorType' => ['type' => 'string', 'required' => false, 'description' => 'Restricts to images of a certain color type', 'options' => ['color', 'gray', 'mono', 'trans'], 'default' => null, 'userinput_rqd' => false],
                    'imgDominantColor' => ['type' => 'string', 'required' => false, 'description' => 'Restricts to images with a specific dominant color', 'options' => ['black', 'blue', 'brown', 'gray', 'green', 'orange', 'pink', 'purple', 'red', 'teal', 'white', 'yellow'], 'default' => null, 'userinput_rqd' => false],
                    'imgSize' => ['type' => 'string', 'required' => false, 'description' => 'Restricts results to a specific image size', 'options' => ['huge', 'icon', 'large', 'medium', 'small', 'xlarge', 'xxlarge'], 'default' => null, 'userinput_rqd' => false],
                    'imgType' => ['type' => 'string', 'required' => false, 'description' => 'Restricts to a specific image type', 'options' => ['clipart', 'face', 'lineart', 'stock', 'photo', 'animated'], 'default' => null, 'userinput_rqd' => false],
                    'linkSite' => ['type' => 'string', 'required' => false, 'description' => 'Restricts results to pages linking to a site', 'default' => null, 'userinput_rqd' => true],
                    'lowRange' => ['type' => 'string', 'required' => false, 'description' => 'Low price range filter', 'default' => null, 'userinput_rqd' => true],
                    'lr' => ['type' => 'string', 'required' => false, 'description' => 'Restricts by language', 'options' => ['lang_ar', 'lang_bg', 'lang_ca', 'lang_cs', 'lang_da', 'lang_de', 'lang_el', 'lang_en', 'lang_es', 'lang_et', 'lang_fi', 'lang_fr', 'lang_hr', 'lang_hu', 'lang_id', 'lang_is', 'lang_it', 'lang_iw', 'lang_ja', 'lang_ko', 'lang_lt', 'lang_lv', 'lang_nl', 'lang_no', 'lang_pl', 'lang_pt', 'lang_ro', 'lang_ru', 'lang_sk', 'lang_sl', 'lang_sr', 'lang_sv', 'lang_tr', 'lang_zh-CN', 'lang_zh-TW'], 'default' => null, 'userinput_rqd' => false],
                    'num' => ['type' => 'integer', 'required' => false, 'description' => 'Number of results', 'min' => 1, 'max' => 10, 'default' => 10, 'userinput_rqd' => true],
                    'orTerms' => ['type' => 'string', 'required' => false, 'description' => 'Alternate terms for the search', 'default' => null, 'userinput_rqd' => true],
                    'rights' => ['type' => 'string', 'required' => false, 'description' => 'Usage rights filter', 'options' => ['cc_publicdomain', 'cc_attribute', 'cc_sharealike', 'cc_noncommercial', 'cc_nonderived'], 'default' => null, 'userinput_rqd' => false],
                    'safe' => ['type' => 'string', 'required' => false, 'description' => 'SafeSearch setting', 'options' => ['active', 'off'], 'default' => null, 'userinput_rqd' => false],
                    'siteSearch' => ['type' => 'string', 'required' => false, 'description' => 'Restricts results to a specific site', 'default' => null, 'userinput_rqd' => true],
                    'siteSearchFilter' => ['type' => 'string', 'required' => false, 'description' => 'Include or exclude site search', 'options' => ['i', 'e'], 'default' => null, 'userinput_rqd' => false],
                    'sort' => ['type' => 'string', 'required' => false, 'description' => 'Sort results by date', 'default' => 'date', 'userinput_rqd' => true],
                    'start' => ['type' => 'integer', 'required' => false, 'description' => 'Index of the first result to return', 'min' => 1, 'max' => 100, 'default' => null, 'userinput_rqd' => true],
                ],
                'response' => [
                    'kind' => 'customsearch#search',
                    'url' => ['type' => 'application/json', 'template' => 'https://www.googleapis.com/customsearch/v1?q={searchTerms}&num={count?}&start={startIndex?}&lr={language?}&safe={safe?}&cx={cx?}&sort={sort?}&filter={filter?}&gl={gl?}&cr={cr?}&googlehost={googleHost?}&c2coff={disableCnTwTranslation?}&hq={hq?}&hl={hl?}&siteSearch={siteSearch?}&siteSearchFilter={siteSearchFilter?}&exactTerms={exactTerms?}&excludeTerms={excludeTerms?}&linkSite={linkSite?}&orTerms={orTerms?}&dateRestrict={dateRestrict?}&lowRange={lowRange?}&highRange={highRange?}&searchType={searchType}&fileType={fileType?}&rights={rights?}&imgSize={imgSize?}&imgType={imgType?}&imgColorType={imgColorType?}&imgDominantColor={imgDominantColor?}&alt=json'],
                    'queries' => ['request' => [['title' => 'Google Custom Search - espresso machine', 'totalResults' => '1470000000', 'searchTerms' => 'espresso machine', 'count' => 1, 'startIndex' => 1, 'inputEncoding' => 'utf8', 'outputEncoding' => 'utf8', 'safe' => 'off', 'cx' => '670eb2e277d2a4540', 'searchType' => 'image']], 'nextPage' => [['title' => 'Google Custom Search - espresso machine', 'totalResults' => '1470000000', 'searchTerms' => 'espresso machine', 'count' => 1, 'startIndex' => 2, 'inputEncoding' => 'utf8', 'outputEncoding' => 'utf8', 'safe' => 'off', 'cx' => '670eb2e277d2a4540', 'searchType' => 'image']]],
                    'context' => ['title' => 'my_app'],
                    'searchInformation' => ['searchTime' => 0.400568, 'formattedSearchTime' => '0.40', 'totalResults' => '1470000000', 'formattedTotalResults' => '1,470,000,000'],
                    'items' => [['kind' => 'customsearch#result', 'title' => 'Breville Barista Express Espresso Machine | Williams Sonoma', 'htmlTitle' => 'Breville Barista Express <b>Espresso Machine</b> | Williams Sonoma', 'link' => 'https://assets.wsimgs.com/wsimgs/rk/images/dp/wcm/202512/0020/img24o.jpg', 'displayLink' => 'www.williams-sonoma.com', 'snippet' => 'Breville Barista Express Espresso Machine | Williams Sonoma', 'htmlSnippet' => 'Breville Barista Express <b>Espresso Machine</b> | Williams Sonoma', 'mime' => 'image/jpeg', 'fileFormat' => 'image/jpeg', 'image' => ['contextLink' => 'https://www.williams-sonoma.com/products/breville-barista-express-espresso-maker/?bvstate=pg:3/ct:r', 'height' => 710, 'width' => 710, 'byteSize' => 85306, 'thumbnailLink' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRq7bJ0eCHr1qIBfn1LNS4obA9GDG5uidKwfb6P-NDO9t7wuY4qJahYljyR&s', 'thumbnailHeight' => 140, 'thumbnailWidth' => 140]]],
                ],
                'response_path' => ['final_result' => '$.items'],
                'request_class_name' => SearchImageWithOperatorsRequest::class,
                'function_name' => 'searchImage',
            ],
        ];

        $keptServiceTypeIds = $this->processServiceTypes($serviceProvider, $serviceTypes, 'Google Custom Search With Operators');

        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);

        $this->command->info('Cleanup completed:');
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info('- Kept ' . count($keptServiceTypeIds) . ' service types for Google Custom Search With Operators');
    }
}
