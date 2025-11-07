<?php

namespace App\Services;

use App\Data\Request\ChatGPT\ChatCompletionData;
use App\Data\Request\ChatGPT\CodeCompletionData;
use App\Data\Request\ChatGPT\ImageGenerationData;
use App\Data\Request\ChatGPT\TextEmbeddingData;
use App\Data\Request\ChatGPT\UiFieldExtractionData;
use App\Http\Exceptions\Forbidden;
use App\Http\Resources\OpenAI\CodeCompletionResource;
use App\Traits\OpenAIChatTrait;
use Exception;
use finfo;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use OpenAI;
use OpenAI\Exceptions\ErrorException;
use OpenAI\Responses\Chat\CreateResponse;

class ChatGPTService
{
    use OpenAIChatTrait;

    public OpenAI\Client $client;

    public function __construct()
    {
        $this->client = OpenAI::client(config('services.openai.api_key'));
    }

    public function chatCompletion(ChatCompletionData $data)
    {
        if (is_null($data->knowledge_base) && is_null($data->schema_tool)) {
            return $this->chatCompletionWithoutThread($data);
        }

        $fileIds = [];

        if ($data->knowledge_base) {
            $fileId = $this->uploadFile($data->knowledge_base);
            if ($fileId) {
                $fileIds[] = $fileId;
            }
        }

        $vectorStoreResponse = Http::withToken(env('OPENAI_API_KEY'))
            ->withHeader('OpenAI-Beta', 'assistants=v2')
            ->timeout(60)
            ->post('https://api.openai.com/v1/vector_stores', [
                'name' => 'Dynamic Vector Store',
                'file_ids' => $fileIds,
            ]);

        $vectorStoreId = $vectorStoreResponse->json('id');
        if (!$vectorStoreId) {
            throw new \Exception('Failed to create vector store.');
        }

        $assistantResponse = Http::withToken(env('OPENAI_API_KEY'))
            ->withHeader('OpenAI-Beta', 'assistants=v2')
            ->timeout(60)
            ->post('https://api.openai.com/v1/assistants', [
                'name' => 'Dynamic Assistant',
                'instructions' => 'Use the uploaded knowledge and optional tools.',
                'model' => 'gpt-4-turbo',
                'tools' => [['type' => 'file_search']],
                'tool_resources' => [
                    'file_search' => [
                        'vector_store_ids' => [$vectorStoreId],
                    ],
                ],
            ]);

        $assistantId = $assistantResponse->json('id');
        if (!$assistantId) {
            throw new \Exception('Failed to create assistant.');
        }

        $threadResponse = Http::withToken(env('OPENAI_API_KEY'))
            ->withHeader('OpenAI-Beta', 'assistants=v2')
            ->timeout(30)
            ->post('https://api.openai.com/v1/threads');

        $threadId = $threadResponse->json('id');
        if (!$threadId) {
            throw new \Exception('Failed to create thread.');
        }

        if ($data->schema_tool) {
            try {
                $content = file_get_contents($data->schema_tool->getRealPath());
                $json = json_decode($content, true);
                $url = $json['servers'][0]['url'] ?? null;
                $path = $url . array_key_first($json['paths']);

                $schemaToolResponse = Http::timeout(30)->get($path);
                if ($schemaToolResponse->failed()) {
                    throw new \Exception('Failed to fetch schema tool data');
                }

                $schemaToolData = $schemaToolResponse->json();
                Http::withToken(env('OPENAI_API_KEY'))
                    ->withHeader('OpenAI-Beta', 'assistants=v2')
                    ->timeout(30)
                    ->post("https://api.openai.com/v1/threads/{$threadId}/messages", [
                        'role' => 'user',
                        'content' => json_encode($schemaToolData),
                    ]);
            } catch (\Exception $e) {
                throw new \Exception('Invalid schema tool file: ' . $e->getMessage());
            }
        }

        Http::withToken(env('OPENAI_API_KEY'))
            ->withHeader('OpenAI-Beta', 'assistants=v2')
            ->timeout(30)
            ->post("https://api.openai.com/v1/threads/{$threadId}/messages", [
                'role' => 'user',
                'content' => $data->messages,
            ]);

        $runResponse = Http::withToken(env('OPENAI_API_KEY'))
            ->withHeader('OpenAI-Beta', 'assistants=v2')
            ->timeout(30)
            ->post("https://api.openai.com/v1/threads/{$threadId}/runs", [
                'assistant_id' => $assistantId,
            ]);

        $runId = $runResponse->json('id');
        if (!$runId) {
            throw new \Exception('Failed to start run.');
        }

        do {
            sleep(2);
            $statusCheck = Http::withToken(env('OPENAI_API_KEY'))
                ->withHeader('OpenAI-Beta', 'assistants=v2')
                ->timeout(30)
                ->get("https://api.openai.com/v1/threads/{$threadId}/runs/{$runId}");

            if ($statusCheck->failed()) {
                throw new \Exception('Failed to check run status.');
            }
        } while ($statusCheck->json('status') !== 'completed');

        $messages = Http::withToken(env('OPENAI_API_KEY'))
            ->withHeader('OpenAI-Beta', 'assistants=v2')
            ->timeout(30)
            ->get("https://api.openai.com/v1/threads/{$threadId}/messages");

        return collect($messages->json('data'))
            ->firstWhere('role', 'assistant')['content'][0]['text']['value'] ?? 'No response from assistant';
    }

    protected function uploadFile(UploadedFile $file)
    {
        $content = file_get_contents($file->getRealPath());

        $upload = Http::withToken(env('OPENAI_API_KEY'))
            ->withHeader('OpenAI-Beta', 'assistants=v2')
            ->attach('file', $content, $file->getClientOriginalName())
            ->post('https://api.openai.com/v1/files', [
                'purpose' => 'assistants',
            ]);

        return $upload->json('id');
    }

    /**
     * Create a chat completion using OpenAI's API.
     *
     * @param  ChatCompletionData  $data
     * @return CreateResponse
     */
    public function chatCompletionWithoutThread(ChatCompletionData $data): OpenAI\Responses\Chat\CreateResponse
    {
        $client = OpenAI::client(config('services.openai.api_key'));

        return $client->chat()->create([
            'model' => $data->model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $data->messages,
                ],
            ],
            'max_tokens' => $data->max_tokens,
            'temperature' => $data->temperature,
        ]);
    }

    /**
     * Generate code completion using OpenAI's API
     *
     * @param  CodeCompletionData  $data  Request data
     * @return CodeCompletionResource
     *
     * @throws Forbidden
     */
    public function codeCompletion(CodeCompletionData $data): CodeCompletionResource
    {
        $client = OpenAI::client(config('services.openai.api_key'));

        try {
            $messages = [
                [
                    'role' => 'system',
                    'content' => config('chatGPT.system_prompts.code_generation'),
                ],
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $data->description,
                        ],
                    ],
                ],
            ];

            if (!empty($data->attachments)) {
                foreach ($data->attachments as $attachment) {
                    if ($attachment instanceof UploadedFile) {
                        $contentItem = self::prepareAttachment($attachment, $client);
                        $messages[1]['content'][] = $contentItem;
                    }
                }
            }

            $payload = [
                'model' => $data->model,
                'messages' => $messages,
                'max_tokens' => $data->max_tokens,
                'temperature' => $data->temperature,
                'stream' => false,
            ];

            $response = $client->chat()->create($payload);
        } catch (Exception|ErrorException $e) {
            Log::error('ChatGPT request error: ' . $e->getMessage(), [
                'exception' => $e,
                'data' => [
                    'model' => $data->model,
                    'max_tokens' => $data->max_tokens,
                    'temperature' => $data->temperature,
                    'has_attachments' => !empty($data->attachments),
                ],
            ]);

            throw new Forbidden('ChatGPT request failed with error: ' . $e->getMessage());
        }

        $result = $this->handleOpenAiResponse($response);

        return CodeCompletionResource::make($result);
    }

    /**
     * Generate an image using OpenAI's API.
     *
     * @param  ImageGenerationData  $data
     * @return OpenAI\Responses\Images\CreateResponse
     */
    public function imageGeneration(ImageGenerationData $data): OpenAI\Responses\Images\CreateResponse
    {
        $client = OpenAI::client(config('services.openai.api_key'));

        return $client->images()->create([
            'model' => $data->model,
            'prompt' => $data->prompt,
            'n' => $data->n,
            'size' => $data->size,
            'response_format' => 'url',
        ]);
    }

    /**
     * Generate a text embedding using OpenAI's API.
     *
     * @param  TextEmbeddingData  $data
     * @return OpenAI\Responses\Embeddings\CreateResponse
     */
    public function textEmbedding(TextEmbeddingData $data): OpenAI\Responses\Embeddings\CreateResponse
    {
        $client = OpenAI::client(config('services.openai.api_key'));

        return $client->embeddings()->create([
            'model' => $data->model,
            'input' => $data->input,
        ]);
    }

    /**
     * Extract UI fields from an image using OpenAI's API.
     *
     * @param  UiFieldExtractionData  $data
     * @return array
     *
     * @throws ConnectionException|Exception
     */
    public function uiFieldExtraction(UiFieldExtractionData $data): array
    {
        $client = OpenAI::client(config('services.openai.api_key'));

        $response = $client->chat()->create([
            'model' => 'gpt-4-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a UI field extraction assistant. DO NOT return screen type, reasoning, or any explanation. Just return a comma-separated list of visible UI field labels related to backend database values. No headings, no intros, no explanations.',
                ],
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => 'Extract UI field labels from this form.',
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => $data->image,
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        if (!isset($response->choices[0]->message->content))
            throw new Exception('No content returned from OpenAI');

        return explode(',', str_replace(' ', '', $response->choices[0]->message->content));
    }

    private function selectInterface($image_path): string
    {
        $client = OpenAI::client(config('services.openai.api_key'));

        $messages = [
            [
                'role' => 'system',
                'content' => 'You are a UI interface assistant. Your task is to extract the UI elements from the image and identify the interface type (list, create, edit, show).',
            ],
            [
                'role' => 'user',
                'content' => [
                    [
                        'type' => 'text',
                        'text' => 'Extract the UI elements from the image below. And select if this screenshot is related to (list - create - edit - show) interface. Just give interface type (Ex: "list") without any explanation.',
                    ],
                    [
                        'type' => 'image_url',
                        'image_url' => [
                            'url' => $image_path,
                        ],
                    ],
                ],
            ],
        ];
        $payload = [
            'model' => 'gpt-4-turbo',
            'messages' => $messages,
        ];

        $response = $client->chat()->create($payload);

        return $response->choices[0]->message->content;
    }

    protected function handleOpenAiResponse(CreateResponse $response): \stdClass
    {
        $result = new \stdClass();
        $result->status = false;
        $result->message = '';
        $result->error = null;

        if (empty($response->choices)) {
            $result->error = 'No choices returned from the API';

            return $result;
        }

        $firstChoice = $response->choices[0];

        $messageContent = $firstChoice->message->content ?? null;

        if (empty(trim($messageContent))) {
            $result->error = 'Empty response content';

            return $result;
        }

        $result->status = true;
        $result->message = $messageContent;

        // Optional: detect soft failures based on AI-generated responses
        if (preg_match('/\b(error|sorry|unable|failed)\b/i', $result->message)) {
            $result->status = false;
            $result->error = $result->message;
            $result->message = '';
            Log::warning('AI-generated error detected: ' . $result->error);
        }

        return $result;
    }
}
