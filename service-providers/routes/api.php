<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\CanvaController;
use App\Http\Controllers\ChatGPTController;
use App\Http\Controllers\ClaudeAPIController;
use App\Http\Controllers\DeepSeekController;
use App\Http\Controllers\DescriptAIController;
use App\Http\Controllers\FFMpegServiceController;
use App\Http\Controllers\FreepikController;
use App\Http\Controllers\GeminiController;
use App\Http\Controllers\GoogleSheetsController;
use App\Http\Controllers\PerplexityController;
use App\Http\Controllers\PexelsController;
use App\Http\Controllers\PlacidController;
use App\Http\Controllers\PremierProController;
use App\Http\Controllers\QwenController;
use App\Http\Controllers\ReactJsController;
use App\Http\Controllers\RunwaymlAPIController;
use App\Http\Controllers\ServiceProviderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WhisperController;
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
    Route::post('chat_completion', 'chatCompletion');
    Route::post('code_generation', 'codeCompletion');
    Route::post('image_generation', 'imageGeneration');
    Route::post('text_embedding', 'textEmbedding');
    Route::post('ui_field_extraction', 'uiFieldExtraction');
});

Route::get('test', function () {
    $cursor = \App\Models\ProjectStructure::all();

    return response()->json($cursor);
});

Route::prefix('claudeapi')->group(function () {
    Route::post('text_generation', [ClaudeAPIController::class, 'textGeneration']);
    Route::post('text_summarize', [ClaudeAPIController::class, 'textSummarize']);
    Route::post('question_answer', [ClaudeAPIController::class, 'questionAnswer']);
    Route::post('text_classify', [ClaudeAPIController::class, 'textClassify']);
    Route::post('text_translate', [ClaudeAPIController::class, 'textTranslate']);
    Route::post('codegen', [ClaudeAPIController::class, 'codegen']);
    Route::post('data_analysis_insight_service', [ClaudeAPIController::class, 'dataAnalysisAndInsight']);
    Route::post('personalize', [ClaudeAPIController::class, 'personalize']);
});

Route::prefix('runwayml')->controller(RunwaymlAPIController::class)->group(function () {
    Route::post('video_processing', 'videoProcessing');
    Route::post('task_management/{id}', 'taskManagement');
});

Route::prefix('gemini')->controller(GeminiController::class)->group(function () {
    Route::post('text_generation', 'textGeneration');
    Route::post('code_generation', 'codeGeneration');
    Route::post('image_analysis', 'imageAnalysis');
    Route::post('document_summarization', 'documentSummarization');
});

Route::prefix('deepseek')->controller(DeepSeekController::class)->group(function () {
    Route::post('chat_completion', 'chatCompletion');
    Route::post('code_completion', 'codeCompletion');
    Route::post('document_qa', 'documentQa');
    Route::post('mathematical_reasoning', 'mathematicalReasoning');
});

Route::prefix('qwen')->group(function () {
    Route::post('nlp', [QwenController::class, 'nlp']);
    Route::post('code_generation', [QwenController::class, 'codeGeneration']);
    Route::post('text_summarization', [QwenController::class, 'textSummarization']);
    Route::post('chatbot', [QwenController::class, 'chatbot']);
});

Route::prefix('ffmpeg')->controller(FFMpegServiceController::class)->group(function () {
    Route::post('/video_processing', 'videoProcessing');
    Route::post('/audio_processing', 'audioProcessing');
    Route::post('/image_processing', 'imageProcessing');
    Route::post('/video_trimming', 'videoTrimming');
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

Route::prefix('whisper')->controller(WhisperController::class)->group(function () {
    Route::post('audio-transcribe', 'audioTranscribe');
    Route::post('audio-translate', 'audioTranslate');
    Route::post('audio-transcribe-timestamps', 'audioTranscribeTimestamps');
});

Route::prefix('placid')->controller(PlacidController::class)->group(function () {
    Route::get('retrieve-template', 'retrieveTemplate');
    Route::post('image-generation', 'imageGeneration');
    Route::post('pdf-generation', 'pdfGeneration');
    Route::get('retrieve-pdf', 'retrievePdf');
    Route::post('video-generation', 'videoGeneration');
    Route::get('retrieve-video', 'retrieveVideo');
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

// Perplexity
Route::prefix('freepik')->controller(FreepikController::class)->group(function () {
    Route::get('stock_content', 'stockContent');
    Route::get('resource_detail/{resource_id}', 'resourceDetail');
    Route::get('download_resource/{resource_id}', 'downloadResource');
    Route::get('download_resource_format', 'downloadResourceFormat');
    Route::post('ai_image_classifier', 'aiImageClassifier');
    Route::post('icon_generation', 'iconGeneration');
    Route::get('icon_generation/result/{task_id}', 'getIconGenerationResult');
    Route::post('kling_video_generation/image_to_video', 'klingVideoGenerationImageToVideo');
    Route::get('kling_video_generation/image_to_video/status/{task_id}', 'klingVideoGenerationImageToVideoStatus');
    Route::post('kling_video_generation/text_to_video', 'klingVideoGenerationTextToVideo');
    Route::get('kling_video_generation/text_to_video/status/{task_id}', 'klingVideoGenerationTextToVideoStatus');
    Route::post('image_to_video_kling_elements', 'klingVideoGenerationTextToVideo');
    Route::get('image_to_video_kling_elements/status/{task_id}', 'klingElementsVideoStatus');

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
    });
});

Route::post('freepik/webhook', [FreepikController::class, 'handleWebhook'])->name('freepik.webhook');
