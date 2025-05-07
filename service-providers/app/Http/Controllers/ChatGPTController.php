<?php

namespace App\Http\Controllers;

use App\Data\Request\ChatGPT\ChatCompletionData;
use App\Data\Request\ChatGPT\CodeCompletionData;
use App\Data\Request\ChatGPT\ImageGenerationData;
use App\Data\Request\ChatGPT\TextEmbeddingData;
use App\Data\Request\ChatGPT\UiFieldExtractionData;
use App\Http\Requests\ChatGPT\ChatCompletionRequest;
use App\Http\Requests\ChatGPT\CodeCompletionRequest;
use App\Http\Requests\ChatGPT\ImageGenerationRequest;
use App\Http\Requests\ChatGPT\TextEmbeddingRequest;
use App\Http\Requests\ChatGPT\UiFieldExtractionRequest;
use App\Services\ChatGPTService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ChatGPTController extends BaseController
{
    public function __construct(protected ChatGPTService $service) {}

    #[OA\Post(
        path: '/api/chatgpt/chat_completion',
        operationId: 'chat_completion',
        description: 'ChatGPT Chat Completion',
        summary: 'ChatGPT Chat Completion',
        security: [['authentication' => []]],
        tags: ['ChatGPT'],
    )]
    #[OA\QueryParameter(
        name: 'model',
        description: 'Model',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            example: 'gpt-4'
        )
    )]
    #[OA\QueryParameter(
        name: 'messages',
        description: 'Messages',
        required: true,
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'role', type: 'string', example: 'user'),
                    new OA\Property(property: 'content', type: 'string', example: 'Hello, how are you?'),
                ],
                type: 'object'
            )
        )
    )]
    #[OA\QueryParameter(
        name: 'temperature',
        description: 'Temperature',
        required: false,
        schema: new OA\Schema(
            type: 'number',
            example: 0.7
        )
    )]
    #[OA\QueryParameter(
        name: 'max_tokens',
        description: 'Max Tokens',
        required: false,
        schema: new OA\Schema(
            type: 'integer',
            example: 4000
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'id' => 'chatgpt_response_id',
                'object' => 'chatgpt_response_object',
                'created' => 1234567890,
                'model' => 'gpt-4',
                'choices' => [
                    [
                        'index' => 0,
                        'message' => [
                            'role' => 'assistant',
                            'content' => 'Hello! I am doing well, thank you!',
                        ],
                        'finish_reason' => 'stop',
                    ],
                ],
                'usage' => [
                    'prompt_tokens' => 10,
                    'completion_tokens' => 20,
                    'total_tokens' => 30,
                ],
            ],
        )
    )]
    public function chatCompletion(ChatCompletionRequest $request): JsonResponse
    {
        $data = ChatCompletionData::from($request->validated());

        $result = $this->service->chatCompletion($data);

        return $this->logAndResponse([
            'message' => 'Chat completion successful.',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/chatgpt/code_generation',
        operationId: 'code_generation',
        description: 'ChatGPT Code Completion',
        summary: 'ChatGPT Code Completion',
        security: [['authentication' => []]],
        tags: ['ChatGPT'],
    )]
    #[OA\RequestBody(
        description: 'Generate code using ChatGPT',
        required: true,
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(
                required: ['description'],
                properties: [
                    new OA\Property(
                        property: 'model',
                        type: 'string',
                        enum: ['gpt-4o-mini', 'gpt-4', 'gpt-3.5-turbo', 'gpt-3.5-turbo-0301'],
                        example: 'gpt-4o-mini',
                    ),
                    new OA\Property(
                        property: 'description',
                        type: 'string',
                        example: 'Write a Python function to calculate the factorial of a number.',
                    ),
                    new OA\Property(
                        property: 'temperature',
                        type: 'number',
                        format: 'float',
                        maximum: 1,
                        minimum: 0,
                        example: 0.7,
                    ),
                    new OA\Property(
                        property: 'max_tokens',
                        type: 'integer',
                        example: 200,
                    ),
                    new OA\Property(
                        property: 'attachments[]',
                        description: 'Array of files to attach',
                        type: 'array',
                        items: new OA\Items(type: 'string', format: 'binary'),
                        example: [],
                        nullable: true
                    ),
                ],
                type: 'object'
            ),
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'id' => 'chatgpt_response_id',
                'object' => 'chatgpt_response_object',
                'created' => 1234567890,
                'model' => 'gpt-4',
                'choices' => [
                    [
                        'index' => 0,
                        'message' => [
                            'role' => 'assistant',
                            'content' => 'Hello! I am doing well, thank you!',
                        ],
                        'finish_reason' => 'stop',
                    ],
                ],
                'usage' => [
                    'prompt_tokens' => 10,
                    'completion_tokens' => 20,
                    'total_tokens' => 30,
                ],
            ],
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'validation_error',
                'message' => [
                    'prompt' => 'The prompt field is required.',
                    'max_tokens' => 'The max tokens field is required.',
                ],
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
            ],
        )
    )]
    public function codeCompletion(CodeCompletionRequest $request): JsonResponse
    {
        $data = CodeCompletionData::from($request->validated());

        $result = $this->service->codeCompletion($data);

        return $this->logAndResponse([
            'message' => 'Code generation successful.',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/chatgpt/image_generation',
        operationId: 'image_generation',
        description: 'ChatGPT Image Generation',
        summary: 'ChatGPT Image Generation',
        security: [['authentication' => []]],
        tags: ['ChatGPT'],
    )]
    #[OA\QueryParameter(
        name: 'model',
        description: 'Model',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            example: 'dall-e-3'
        )
    )]
    #[OA\QueryParameter(
        name: 'prompt',
        description: 'Prompt',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            example: 'A futuristic city skyline at sunset'
        )
    )]
    #[OA\QueryParameter(
        name: 'size',
        description: 'Size',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            example: '1024x1024'
        )
    )]
    #[OA\QueryParameter(
        name: 'n',
        description: 'Number of images to generate',
        required: true,
        schema: new OA\Schema(
            type: 'integer',
            example: 1
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
    )]
    public function imageGeneration(ImageGenerationRequest $request): JsonResponse
    {
        $data = ImageGenerationData::from($request->validated());

        $result = $this->service->imageGeneration($data);

        return $this->logAndResponse([
            'message' => 'Image generation successful.',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/chatgpt/text_embedding',
        operationId: 'text_embedding',
        description: 'ChatGPT Text Embedding',
        summary: 'ChatGPT Text Embedding',
        security: [['authentication' => []]],
        tags: ['ChatGPT'],
    )]
    #[OA\QueryParameter(
        name: 'model',
        description: 'Model',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            example: 'text-embedding-3-large'
        )
    )]
    #[OA\QueryParameter(
        name: 'input',
        description: 'Input text to embed',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            example: 'The food was delicious and the waiter...'
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
    )]
    public function textEmbedding(TextEmbeddingRequest $request): JsonResponse
    {
        $data = TextEmbeddingData::from($request->validated());

        $result = $this->service->textEmbedding($data);

        return $this->logAndResponse([
            'message' => 'Text embedding successful.',
            'data' => $result,
        ]);
    }

    #[OA\Post(
        path: '/api/chatgpt/ui_field_extraction',
        operationId: 'ui_field_extraction',
        description: 'ChatGPT UI Field Extraction',
        summary: 'ChatGPT UI Field Extraction',
        security: [['authentication' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['image'],
                    properties: [
                        new OA\Property(
                            property: 'image',
                            type: 'string',
                            example: 'https://figma-alpha-api.s3.us-west-2.amazonaws.com/images/2c94a50d-b88a-4148-96bc-4453c288fd7b'
                        ),
                    ],
                    type: 'object'
                )
            )
        ),
        tags: ['ChatGPT']
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful import',
        content: new OA\JsonContent(
            example: [
                'message' => 'UI field extraction successful.',
                'data' => [
                    'role',
                    'name',
                ]
            ]
        )
    )]
    public function uiFieldExtraction(UiFieldExtractionRequest $request): JsonResponse
    {
        $data = UiFieldExtractionData::from($request->validated());

        $result = $this->service->uiFieldExtraction($data);

        return $this->logAndResponse([
            'message' => 'UI field extraction successful.',
            'data' => $result,
        ]);
    }
}
