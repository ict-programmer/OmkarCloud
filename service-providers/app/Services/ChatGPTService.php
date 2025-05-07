<?php

namespace App\Services;

use App\Data\Request\ChatGPT\ChatCompletionData;
use App\Data\Request\ChatGPT\CodeCompletionData;
use App\Data\Request\ChatGPT\ImageGenerationData;
use App\Data\Request\ChatGPT\TextEmbeddingData;
use App\Data\Request\ChatGPT\UiFieldExtractionData;
use App\Http\Exceptions\BadRequest;
use App\Http\Exceptions\Forbidden;
use App\Http\Resources\OpenAI\CodeCompletionResource;
use App\Traits\OpenAIChatTrait;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
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

    /**
     * Create a chat completion using OpenAI's API.
     *
     * @param  ChatCompletionData  $data
     * @return CreateResponse
     */
    public function chatCompletion(ChatCompletionData $data): OpenAI\Responses\Chat\CreateResponse
    {
        $client = OpenAI::client(config('services.openai.api_key'));

        return $client->chat()->create([
            'model' => $data->model,
            'messages' => [json_decode($data->messages, JSON_UNESCAPED_UNICODE)],
            'max_tokens' => $data->max_tokens,
            'temperature' => $data->temperature,
        ]);
    }

    /**
     * Generate code completion using OpenAI's API
     * 
     * @param CodeCompletionData $data Request data
     * @return \OpenAI\Responses\Chat\CreateResponse
     * @throws Forbidden
     */
    public function codeCompletion(CodeCompletionData $data)
    {
        $client = OpenAI::client(config('services.openai.api_key'));

        try {
            $messages = [
                [
                    'role' => 'system',
                    'content' => config('chatGPT.system_prompts.code_generation')
                ],
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $data->description
                        ]
                    ]
                ]
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
                'stream' => false
            ];

            $response = $client->chat()->create($payload);
        } catch (Exception | ErrorException $e) {
            Log::error('ChatGPT request error: ' . $e->getMessage(), [
                'exception' => $e,
                'data' => [
                    'model' => $data->model,
                    'max_tokens' => $data->max_tokens,
                    'temperature' => $data->temperature,
                    'has_attachments' => !empty($data->attachments)
                ]
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
     * @throws ConnectionException
     */
    public function uiFieldExtraction(UiFieldExtractionData $data): array
    {
        $imageUrl = $data->image;

        $imageContents = file_get_contents($imageUrl);

        $tempImagePath = tempnam(sys_get_temp_dir(), 'openai_img_');
        file_put_contents($tempImagePath, $imageContents);

        $upload = Http::withToken(config('services.openai.api_key'))
            ->attach('file', fopen($tempImagePath, 'r'), 'screenshot.png')
            ->post('https://api.openai.com/v1/files', [
                'purpose' => 'assistants',
            ]);

        unlink($tempImagePath);

        $imageId = $upload->json('id');

        $assistant = $this->client->assistants()->create([
            'instructions' => '
            You are an expert in UI analysis. From the uploaded image of a user interface, extract all visible form field names and return them in a flat list using snake_case format (lowercase with underscores between words).

Instructions:
	•	Only include field titles/labels, not the values or input types.
	•	Ignore any buttons such as save, send, or submit.
	•	Exclude inputs and dropdowns that are embedded inside rich-text editors like TinyMCE, but retain any visible titles or labels associated with them.
	•	Do not include hierarchical or nested structure—return a flat list.
	•	Format all field names in lowercase with underscores, e.g., first_name, email_address.
	IMPORTANT: Do not include any other text or explanation in the response, just the field names.
	',
            'name' => 'UI Field Extraction Assistant',
            'model' => 'gpt-4-turbo',
        ]);
        $assistantId = $assistant->id;

        $thread = $this->client->threads()->create([]);
        $threadId = $thread->id;

        $this->client->threads()->messages()->create(
            threadId: $threadId,
            parameters: [
                'role' => 'user',
                'content' => [
                    ['type' => 'text', 'text' => 'Extract the UI field from the image below. I need just field name without any details or description'],
                    ['type' => 'image_file', 'image_file' => ['file_id' => $imageId]],
                ],
            ],
        );

        $run = $this->client->threads()->runs()->create(
            threadId: $threadId,
            parameters: ['assistant_id' => $assistantId],
        );

        do {
            sleep(2);
            $runStatus = $this->client->threads()->runs()->retrieve(threadId: $threadId, runId: $run->id);
        } while ($runStatus->status !== 'completed');

        $messages = $this->client->threads()->messages()->list(threadId: $threadId);
        $extractedFields = null;
        foreach ($messages->data as $message) {
            if ($message->role === 'assistant') {
                $extractedFields = str_replace(',', ' ', $message->content[0]->text->value);
                break;
            }
        }

        return preg_split('/\s+/', $extractedFields);
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
