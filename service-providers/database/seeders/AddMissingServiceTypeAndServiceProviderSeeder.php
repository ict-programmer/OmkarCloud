<?php

namespace Database\Seeders;

use App\Http\Controllers\ChatGPTController;
use App\Http\Requests\ChatGPT\ChatCompletionRequest;
use App\Http\Requests\ChatGPT\ImageGenerationRequest;
use App\Http\Requests\ChatGPT\TextEmbeddingRequest;
use App\Http\Requests\Placid\RetrievePdfRequest;
use App\Http\Requests\Placid\RetrieveTemplateRequest;
use App\Http\Requests\Placid\RetrieveVideoRequest;
use App\Http\Requests\Shutterstock\AddToCollectionRequest;
use App\Http\Requests\Shutterstock\CreateCollectionRequest;
use App\Models\ServiceProvider;
use App\Models\ServiceProviderType;
use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class AddMissingServiceTypeAndServiceProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ServiceType::query()->create([
            'name' => 'Retrieve Template',
            'request_class_name' => RetrieveTemplateRequest::class,
            'function_name' => 'retrieveTemplate',
        ]);

        ServiceType::query()->create([
            'name' => 'Retrieve PDF',
            'request_class_name' => RetrievePdfRequest::class,
            'function_name' => 'retrievePdf',
        ]);

        ServiceType::query()->create([
            'name' => 'Retrieve Video',
            'request_class_name' => RetrieveVideoRequest::class,
            'function_name' => 'retrieveVideo',
        ]);

        ServiceType::query()->create([
            'name' => 'Create Collection',
            'request_class_name' => CreateCollectionRequest::class,
            'function_name' => 'createCollection',
        ]);

        ServiceType::query()->create([
            'name' => 'Add to Collection',
            'request_class_name' => AddToCollectionRequest::class,
            'function_name' => 'addToCollection',
        ]);

        ServiceType::query()->create([
            'name' => 'List User Subscriptions',
            'function_name' => 'listUserSubscriptions',
        ]);

        $chatGptService = ServiceProvider::query()->create([
            'name' => 'ChatGPT',
            'parameter' => [
                'base_url' => 'https://api.openai.com',
                'version' => 'v1',
                'models_supported' => ['gpt-4', 'gpt-3.5-turbo'],
                'features' => [
                    'chat_completion',
                    'image_generation',
                    'text_embedding'
                ],
            ],
            'controller_name' => ChatGPTController::class,
        ]);
        $chat = ServiceType::query()->create([
            'name' => 'Chat Completion',
            'request_class_name' => ChatCompletionRequest::class,
            'function_name' => 'chatCompletion',
        ]);
        $image = ServiceType::query()->create([
            'name' => 'Image Generation',
            'function_name' => 'imageGeneration',
            'request_class_name' => ImageGenerationRequest::class,
        ]);
        $text = ServiceType::query()->create([
            'name' => 'Text Embedding',
            'function_name' => 'textEmbedding',
            'request_class_name' => TextEmbeddingRequest::class,
        ]);
        ServiceProviderType::query()->create([
            'service_type_id' => $chat->id,
            'service_provider_id' => $chatGptService->id,
            'parameter' => [
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'user', 'content' => 'query']
                ],
                'temperature' => 0.7,
                'max_tokens' => 1000,
            ],
        ]);
        ServiceProviderType::query()->create([
            'service_type_id' => $image->id,
            'service_provider_id' => $chatGptService->id,
            'parameter' => [
                'model' => 'dall-e-3',
                'prompt' => 'image description',
                'size' => '1024x1024',
                'quality' => 'standard',
                'n' => 1,
            ],
        ]);
        ServiceProviderType::query()->create([
            'service_type_id' => $text->id,
            'service_provider_id' => $chatGptService->id,
            'parameter' => [
                'model' => 'text-embedding-ada-002',
                'input' => 'text to embed',
                'encoding_format' => 'float',
            ],
        ]);
    }
}
