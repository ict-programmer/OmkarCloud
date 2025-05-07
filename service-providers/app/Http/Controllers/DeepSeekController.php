<?php

namespace App\Http\Controllers;

use App\Data\Request\DeepSeek\ChatCompletionData;
use App\Data\Request\DeepSeek\CodeCompletionData;
use App\Data\Request\DeepSeek\DocumentQaData;
use App\Data\Request\DeepSeek\mathematicalReasoningData;
use App\Http\Exceptions\Forbidden;
use App\Http\Requests\DeepSeek\ChatCompletionRequest;
use App\Http\Requests\DeepSeek\CodeCompletionRequest;
use App\Http\Requests\DeepSeek\DocumentQaRequest;
use App\Http\Requests\DeepSeek\MathematicalReasoningRequest;
use App\Services\DeepSeekService;
use Illuminate\Http\Client\ConnectionException;
use OpenApi\Attributes as OA;
use Illuminate\Http\JsonResponse;

class DeepSeekController extends BaseController
{
    public function __construct(protected DeepSeekService $service) {}

    /**
     * @throws Forbidden
     * @throws ConnectionException
     */
    #[OA\Post(
        path: '/api/deepseek/chat_completion',
        operationId: '/api/deepseek/chat_completion',
        description: 'Chat completion using Deep Seek',
        summary: 'Chat completion using Deep Seek',
        tags: ['Deep Seek'],
    )]
    #[OA\RequestBody(
        description: "Chat completion request",
        required: true,
        content: new OA\JsonContent(
            required: ["model", "messages"],
            properties: [
                new OA\Property(
                    property: "model",
                    description: "Model",
                    type: "string",
                    example: "deepseek-chat",
                ),
                new OA\Property(
                    property: "messages",
                    description: "Array of message objects",
                    type: "array",
                    items: new OA\Items(
                        required: ["role", "content"],
                        properties: [
                            new OA\Property(
                                property: "role",
                                type: "string",
                                enum: ["system", "user", "assistant"],
                                example: "user"
                            ),
                            new OA\Property(
                                property: "content",
                                type: "string",
                                example: "Hello, how are you?"
                            )
                        ]
                    )
                ),
                new OA\Property(
                    property: "temperature",
                    description: "Temperature",
                    type: "number",
                    example: 0.7,
                    nullable: true
                ),
                new OA\Property(
                    property: "max_tokens",
                    description: "Max Tokens",
                    type: "integer",
                    example: 4000,
                    nullable: true
                )
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'chat_completion' => 'Once upon a time, in a faraway land...',
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
    public function chatCompletion(ChatCompletionRequest $request): JsonResponse
    {
        $data = ChatCompletionData::from($request->validated());
        $result = $this->service->chatCompletion($data);

        return $this->logAndResponse($result);
    }

    /**
     * @throws Forbidden
     * @throws ConnectionException
     */
    #[OA\Post(
        path: '/api/deepseek/code_completion',
        operationId: '/api/deepseek/code_completion',
        description: 'Code completion using Deep Seek',
        summary: 'Code completion using Deep Seek',
        tags: ['Deep Seek'],
    )]
    #[OA\RequestBody(
        description: 'Code completion using Deep seek',
        required: true,
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(
                required: ['model', 'prompt', 'max_tokens', 'temperature'],
                properties: [
                    new OA\Property(
                        property: 'model',
                        type: 'string',
                        enum: ['deepseek-chat'],
                        example: 'deepseek-chat',
                    ),
                    new OA\Property(
                        property: 'prompt',
                        type: 'string',
                        example: 'Write a Python function to calculate the factorial of a number.',
                    ),
                    new OA\Property(
                        property: 'max_tokens',
                        type: 'integer',
                        example: 200,
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
                        property: 'attachments[]',
                        type: 'array',
                        items: new OA\Items(type: 'string', format: 'binary'),
                        nullable: true,
                        example: [],
                        description: 'Array of files to attach'
                    )
                ],
                type: 'object'
            ),
        ),
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'status' => 'success',
                'data' => [
                    'code' => 'def factorial(n):\n if n == 0:\n return 1\n else:\n return n * factorial(n-1)',
                ],
                'timestamp' => '2025-05-01T12:45:30+00:00'
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

        return $this->logAndResponse($result);
    }

    /**
     * @throws Forbidden
     * @throws ConnectionException
     */
    #[OA\Post(
        path: '/api/deepseek/document_qa',
        operationId: '/api/deepseek/document_qa',
        description: 'Document qa using Deep Seek',
        summary: 'Document qa using Deep Seek',
        tags: ['Deep Seek'],
    )]
    #[OA\RequestBody(
        description: 'Document qa using Deep Seek',
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                required: ['document_text', 'question'],
                properties: [
                    new OA\Property(
                        property: 'document_text',
                        type: 'string',
                        example: 'COVID-19 is a respiratory disease caused by the SARS-CoV-2 virus. Common symptoms include fever, dry cough, and fatigue. Less common symptoms may include loss of taste or smell, aches and pains, headache, sore throat, nasal congestion, red eyes, diarrhea, or a skin rash. Severe symptoms include difficulty breathing or shortness of breath, chest pain or pressure, and loss of speech or movement. People of all ages who experience fever and/or cough associated with difficulty breathing or shortness of breath should seek medical attention immediately.',
                    ),
                    new OA\Property(
                        property: 'question',
                        type: 'string',
                        example: 'What are the main symptoms of COVID-19?',
                    ),
                ],
                type: 'object'
            ),
        ),
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'document_qa' => 'Once upon a time, in a faraway land...',
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
    public function documentQa(DocumentQaRequest $request): JsonResponse
    {
        $data = DocumentQaData::from($request->validated());
        $result = $this->service->documentQa($data);

        return $this->logAndResponse($result);
    }

    /**
     * @throws Forbidden
     * @throws ConnectionException
     */
    #[OA\Post(
        path: '/api/deepseek/mathematical_reasoning',
        operationId: '/api/deepseek/mathematical_reasoning',
        description: 'Mathematical reasoning using Deep Seek',
        summary: 'Mathematical reasoning using Deep Seek',
        tags: ['Deep Seek'],
    )]
    #[OA\RequestBody(
        description: 'Send a problem statement to be solved using the DeepSeek Math model',
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                required: ['problem_statement', 'model', 'max_tokens'],
                properties: [
                    new OA\Property(
                        property: 'problem_statement',
                        type: 'string',
                        maxLength: 100000,
                        example: 'If a car travels 60 miles in 1.5 hours, what is its average speed?'
                    ),
                    new OA\Property(
                        property: 'model',
                        type: 'string',
                        enum: ['deepseek-chat'],
                        example: 'deepseek-chat'
                    ),
                    new OA\Property(
                        property: 'max_tokens',
                        type: 'integer',
                        maximum: 5000,
                        minimum: 1,
                        example: 1024
                    ),
                ],
                type: 'object'
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
                'mathematical_reason' => 'Once upon a time, in a faraway land...',
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
    public function mathematicalReasoning(MathematicalReasoningRequest $request): JsonResponse
    {
        $data = mathematicalReasoningData::from($request->validated());
        $result = $this->service->mathematicalReasoning($data);

        return $this->logAndResponse($result);
    }
}
