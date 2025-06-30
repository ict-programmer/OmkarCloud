<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\CanvaController;
use App\Http\Controllers\CaptionsController;
use App\Http\Controllers\ChatGPTController;
use App\Http\Controllers\DescriptAIController;
use App\Http\Controllers\FreepikController;
use App\Http\Controllers\GettyimagesController;
use App\Http\Controllers\GoogleSheetsController;
use App\Http\Controllers\MainFunctionController;
use App\Http\Controllers\PerplexityController;
use App\Http\Controllers\PexelsController;
use App\Http\Controllers\PremierProController;
use App\Http\Controllers\ReactJsController;
use App\Http\Controllers\ServiceProviderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/extanal', [ServiceProviderController::class, 'list']);

Route::post('/google_spreadsheet/search', [GoogleSheetsController::class, 'search']);
Route::get('/google_spreadsheet/seed', [GoogleSheetsController::class, 'seed']);

Route::prefix('reactjs')->group(function () {
    Route::post('generate-code-by-collecting', [ReactJsController::class, 'generateCodeCollecting']);
    Route::post('generate-code-directly', [ReactJsController::class, 'generateCodeDirectly']);
    Route::post('merge-json', [ReactJsController::class, 'mergeJson']);
    Route::post('react-code-for-element', [ReactJsController::class, 'reactCodeForElement']);
});

// chatgpt
Route::prefix('chatgpt')->controller(ChatGPTController::class)->group(function () {
    Route::post('image_generation', 'imageGeneration');
    Route::post('ui_field_extraction', 'uiFieldExtraction');
});

Route::get('test', function () {
    $cursor = \App\Models\ProjectStructure::all();

    return response()->json($cursor);
});

Route::prefix('descriptai')->controller(DescriptAIController::class)->group(function () {
    Route::post('/generate', 'generateAsync');
    Route::get('/generate_async/{id}', 'getGenerateAsync');
    Route::get('/get_voices', 'getVoices');
});
Route::prefix('canva')->group(function () {
    Route::prefix('oauth')->group(function () {
        Route::post('authorize', [CanvaController::class, 'initiateOAuth']);
        Route::get('callback', [CanvaController::class, 'callback']);
        Route::post('refresh_token', [CanvaController::class, 'refreshToken']);
    });

    Route::post('create_design', [CanvaController::class, 'createDesign']);
    Route::get('list_design', [CanvaController::class, 'listDesigns']);
    Route::get('get_design_details', [CanvaController::class, 'getDesign']);
    Route::post('create_export_design', [CanvaController::class, 'createDesignExportJob']);
    Route::post('get_export_design/{exportID}', [CanvaController::class, 'getDesignExportJob']);
    Route::post('asset_upload', [CanvaController::class, 'uploadAsset']);
    Route::get('asset_upload_job', [CanvaController::class, 'getUploadJob']);

    // folder
    Route::post('create_folder', [CanvaController::class, 'createFolder']);
    Route::get('get_folder_details', [CanvaController::class, 'getFolder']);
    Route::put('update_folder', [CanvaController::class, 'updateFolder']);
    Route::delete('delete_folder/{folderID}', [CanvaController::class, 'deleteFolder']);
    Route::get('get_folder_items', [CanvaController::class, 'getFolderItems']);
    Route::post('move_folder_item', [CanvaController::class, 'moveFolderItem']);
});

// Perplexity
Route::prefix('perplexity')->controller(PerplexityController::class)->group(function () {
    Route::post('ai_search', 'aiSearch');
    Route::post('academic_research', 'academicResearch');
    Route::post('code_assistant', 'codeAssistant');
});

Route::prefix('premierpro')->group(function () {
    Route::post('reframe', [PremierProController::class, 'reframe']);
    Route::post('image-generation', [PremierProController::class, 'imageGeneration']);
    Route::post('status/{id}', [PremierProController::class, 'status']);
});

Route::prefix('assets')->controller(AssetController::class)->group(function () {
    Route::get('list', 'listAssets');
    Route::post('create', 'createAsset');
    Route::delete('delete/{id}', 'deleteAsset');
});
Route::prefix('users')->controller(UserController::class)->group(function () {
    Route::get('list', 'listUsers');
    Route::post('create', 'createUser');
    Route::delete('delete', 'deleteUser');
});

Route::prefix('pexels')->group(function () {
    Route::get('photos/search', [PexelsController::class, 'searchPhotos']);
    Route::get('photos/curated', [PexelsController::class, 'getCuratedPhotos']);
    Route::get('photos/{id}', [PexelsController::class, 'getPhoto']);
    Route::get('videos/search', [PexelsController::class, 'searchVideos']);
    Route::get('videos/popular', [PexelsController::class, 'getPopularVideos']);
    Route::get('videos/{id}', [PexelsController::class, 'getVideo']);
    Route::get('collections/featured', [PexelsController::class, 'getFeaturedCollections']);
    Route::get('collections', [PexelsController::class, 'getCollections']);
    Route::get('collections/{id}', [PexelsController::class, 'getCollection']);
});

Route::prefix('gettyimages')->group(function () {
    Route::prefix('image_search')->group(function () {
        Route::get('', [GettyimagesController::class, 'imageSearch']);
        Route::get('creative', [GettyimagesController::class, 'imageSearchCreative']);
        Route::get('creative/by-image', [GettyimagesController::class, 'imageSearchCreativeByImage']);
        Route::get('editorial', [GettyimagesController::class, 'imageSearchEditorial']);
        Route::put('by-image/upload', [GettyimagesController::class, 'imageSearchByImageUpload']);
    });

    Route::prefix('video_search')->group(function () {
        Route::get('creative', [GettyimagesController::class, 'videoSearchCreative']);
        Route::get('creative/by-image', [GettyimagesController::class, 'videoSearchCreativeByImage']);
        Route::get('editorial', [GettyimagesController::class, 'videoSearchEditorial']);
    });

    Route::prefix('ai_generate/image-generation')->group(function () {
        Route::post('', [GettyimagesController::class, 'imageGeneration']);
        Route::get('{generationRequestId}', [GettyimagesController::class, 'imageGeneration']);
        Route::post('{generationRequestId}/images/{index}/variations', [GettyimagesController::class, 'imageVariations']);
        Route::post('refine', [GettyimagesController::class, 'refineImage']);
        Route::post('extend', [GettyimagesController::class, 'extendImage']);
        Route::post('object-removal', [GettyimagesController::class, 'removeObjectFromImage']);
        Route::post('background-replacement', [GettyimagesController::class, 'replaceBackground']);
        Route::post('influence-color-by-image', [GettyimagesController::class, 'influenceColorByImage']);
        Route::post('influence-composition-by-image', [GettyimagesController::class, 'influenceCompositionByImage']);
        Route::post('background-generations', [GettyimagesController::class, 'generateBackgrounds']);
        Route::get('{generationRequestId}/images/{index}/download-sizes', [GettyimagesController::class, 'getDownloadSizes']);
        Route::put('{generationRequestId}/images/{index}/download', [GettyimagesController::class, 'downloadImageAsync']);
        Route::get('{generationRequestId}/images/{index}/download', [GettyimagesController::class, 'downloadImage']);
    });

    Route::post('remove_background', [GettyimagesController::class, 'removeBackground']);
    Route::get('image_metadata/{id}', [GettyimagesController::class, 'imageMetadata']);
    Route::get('video_metadata/{id}', [GettyimagesController::class, 'videoMetadata']);
    Route::post('image_download/{id}', [GettyimagesController::class, 'imageDownload']);
    Route::post('video_download/{id}', [GettyimagesController::class, 'videoDownload']);
    Route::get('affiliate_image_search', [GettyimagesController::class, 'affiliateImageSearch']);
    Route::get('affiliate_video_search', [GettyimagesController::class, 'affiliateVideoSearch']);
});

// Freepik
Route::prefix('freepik')->controller(FreepikController::class)->group(function () {
    Route::get('stock_content', 'stockContent');
    Route::get('resource_detail/{resource_id}', 'resourceDetail');
    Route::get('download_resource/{resource_id}', 'downloadResource');
    Route::get('download_resource_format', 'downloadResourceFormat');
    Route::post('ai_image_classifier', 'aiImageClassifier');
    Route::post('icon_generation', 'iconGeneration');
    Route::get('icon_generation/result/{task_id}', 'getIconGenerationResult');

    Route::prefix('kling_video_generation')->group(function () {
        Route::post('image_to_video', 'klingVideoGenerationImageToVideo');
        Route::get('image_to_video/status', 'klingVideoGenerationImageToVideoStatus');
        Route::post('text_to_video', 'klingVideoGenerationTextToVideo');
        Route::get('text_to_video/status/{task_id}', 'klingVideoGenerationTextToVideoStatus');
        Route::post('image_to_video_elements', 'klingElementsVideo');
        Route::get('image_to_video_elements/status/{task_id}', 'klingElementsVideoStatus');
    });

    Route::prefix('mystic')->group(function () {
        Route::post('/', 'generateMysticImage');
        Route::get('/status/{task_id}', 'getMysticTaskStatus');

        Route::prefix('loras')->group(function () {
            Route::get('/', 'getLoras');
            Route::post('styles', 'createLoraStyle');
            Route::post('characters', 'trainLoraCharacter');
        });
    });

    Route::prefix('text-to-image')->group(function () {
        Route::post('/classic-fast', 'generateClassicFastImage');
        Route::post('/imagen3', 'generateImagen3');
        Route::get('/imagen3/status/{task_id}', 'getImagen3TaskStatus');
        Route::post('/flux-dev', 'generateFluxDevImage');
        Route::get('/flux-dev/status/{task_id}', 'getFluxDevTaskStatus');
        Route::post('/reimagine-flux', 'reimagineFlux');
    });

    Route::prefix('image-editing')->group(function () {
        Route::post('/upscaler', 'upscale');
        Route::get('/upscaler/status/{task_id}', 'getUpscalerTaskStatus');
        Route::post('/relight', 'relight');
        Route::get('/relight/status/{task_id}', 'getRelightTaskStatus');
        Route::post('/style-transfer', 'styleTransfer');
        Route::get('/style-transfer/status/{task_id}', 'getStyleTransferTaskStatus');
        Route::post('/remove-background', 'removeBackgroundFromImage');
        Route::post('/image-expand/flux-pro', 'imageExpandFluxPro');
        Route::get('/image-expand/flux-pro/status/{task_id}', 'getImageExpandFluxProTaskStatus');
    });
});

Route::post('freepik/webhook', [FreepikController::class, 'handleWebhook'])->name('freepik.webhook');

// Captions
Route::prefix('captions')->controller(CaptionsController::class)->group(function () {
    Route::post('creator/list', 'listCreators');
    Route::post('creator/submit', 'submitCreatorVideo');
    Route::post('creator/poll', 'pollCreatorStatus');
});

Route::post('services/{service_provider_id}/{service_type_id}', MainFunctionController::class);
