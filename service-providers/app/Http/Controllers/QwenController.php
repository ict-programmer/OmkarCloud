<?php

namespace App\Http\Controllers;

use App\Data\Qwen\Request\QwenChatbotData;
use App\Data\Qwen\Request\QwenCodeGenerationData;
use App\Data\Qwen\Request\QwenNLPData;
use App\Data\Qwen\Request\QwenTextSummarizationData;
use App\Http\Requests\Qwen\QwenChatbotRequest;
use App\Http\Requests\Qwen\QwenCodeGenerationRequest;
use App\Http\Requests\Qwen\QwenNLPRequest;
use App\Http\Requests\Qwen\QwenTextSummarizationRequest;
use App\Services\QwenService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class QwenController extends BaseController
{
    public function __construct(protected QwenService $service) {}

    // #[OA\Post(
    //     path: '/api/qwen/nlp',
    //     operationId: 'nlp',
    //     description: 'Qwen NLP',
    //     summary: 'Qwen NLP',
    //     security: [['authentication' => []]],
    //     tags: ['Qwen'],
    // )]
    // #[OA\QueryParameter(
    //     name: 'model',
    //     description: 'Model',
    //     required: true,
    //     schema: new OA\Schema(
    //         type: 'string',
    //         example: 'qwen/qwq-32b:free'
    //     )
    // )]
    // #[OA\QueryParameter(
    //     name: 'prompt',
    //     description: 'Prompt',
    //     required: true,
    //     schema: new OA\Schema(
    //         type: 'string',
    //         example: 'What is the capital of France?'
    //     )
    // )]
    // #[OA\QueryParameter(
    //     name: 'max_tokens',
    //     description: 'Max Tokens',
    //     required: false,
    //     schema: new OA\Schema(
    //         type: 'integer',
    //         example: 2000
    //     )
    // )]
    // #[OA\QueryParameter(
    //     name: 'temperature',
    //     description: 'Temperature',
    //     required: false,
    //     schema: new OA\Schema(
    //         type: 'number',
    //         example: 0.7
    //     )
    // )]
    // #[OA\QueryParameter(
    //     name: 'endpoint_interface',
    //     description: 'Endpoint Interface',
    //     required: false,
    //     schema: new OA\Schema(
    //         type: 'string',
    //         example: 'generate'
    //     )
    // )]
    // #[OA\Response(
    //     response: 200,
    //     description: 'Successful response',
    //     content: new OA\JsonContent(
    //         example: [
    //             'id' => 'qwen_response_id',
    //             'object' => 'qwen_response_object',
    //             'created' => 1234567890,
    //             'model' => 'qwen/qwq-32b:free',
    //             'choices' => [
    //                 [
    //                     'index' => 0,
    //                     'message' => [
    //                         'role' => 'assistant',
    //                         'content' => 'The capital of France is Paris.',
    //                     ],
    //                     'finish_reason' => 'stop',
    //                 ],
    //             ],
    //             'usage' => [
    //                 'prompt_tokens' => 10,
    //                 'completion_tokens' => 20,
    //                 'total_tokens' => 30,
    //             ],
    //         ],
    //     )
    // )]
    public function nlp(QwenNLPRequest $request): JsonResponse
    {
        $data = QwenNLPData::from($request->validated());

        $result = $this->service->nlp($data);

        return $this->logAndResponse($result);
    }

    // #[OA\Post(
    //     path: '/api/qwen/code_generation',
    //     operationId: 'qwen_code_generation',
    //     description: 'Qwen Code Generation',
    //     summary: 'Qwen Code Generation',
    //     security: [['authentication' => []]],
    //     tags: ['Qwen'],
    // )]
    // #[OA\RequestBody(
    //     description: 'Generate code using Claude API',
    //     required: true,
    //     content: new OA\MediaType(
    //         mediaType: 'multipart/form-data',
    //         schema: new OA\Schema(
    //             type: 'object',
    //             required: ['model', 'prompt'],
    //             properties: [
    //                 new OA\Property(
    //                     property: 'model',
    //                     type: 'string',
    //                     example: 'qwen/qwq-32b:free'
    //                 ),
    //                 new OA\Property(
    //                     property: 'prompt',
    //                     type: 'string',
    //                     example: 'Write a Python function to calculate the factorial of a number.'
    //                 ),
    //                 new OA\Property(
    //                     property: 'max_tokens',
    //                     type: 'integer',
    //                     example: 2000
    //                 ),
    //                 new OA\Property(
    //                     property: 'temperature',
    //                     type: 'number',
    //                     example: 0.7
    //                 ),
    //                 new OA\Property(
    //                     property: 'endpoint_interface',
    //                     type: 'string',
    //                     example: 'generate'
    //                 ),
    //                 new OA\Property(
    //                     property: 'attachments[]',
    //                     type: 'array',
    //                     items: new OA\Items(type: 'string', format: 'binary'),
    //                     nullable: true,
    //                     example: [],
    //                     description: 'Array of files to attach'
    //                 )
    //             ]
    //         ),
    //     ),
    // )]
    // #[OA\Response(
    //     response: 200,
    //     description: 'Successful response',
    //     content: new OA\JsonContent(
    //         example: [
    //             'id' => 'qwen_response_id',
    //             'object' => 'qwen_response_object',
    //             'created' => 1234567890,
    //             'model' => 'qwen/qwq-32b:free',
    //             'choices' => [
    //                 [
    //                     'index' => 0,
    //                     'message' => [
    //                         'role' => 'assistant',
    //                         'content' => 'def factorial(n):
    // if n == 0:
    //     return 1
    // else:
    //     return n * factorial(n-1)',
    //                     ],
    //                     'finish_reason' => 'stop',
    //                 ],
    //             ],
    //             'usage' => [
    //                 'prompt_tokens' => 10,
    //                 'completion_tokens' => 20,
    //                 'total_tokens' => 30,
    //             ],
    //         ],
    //     )
    // )]
    public function codeGeneration(QwenCodeGenerationRequest $request): JsonResponse
    {
        $data = QwenCodeGenerationData::from($request->validated());

        $result = $this->service->codeGeneration($data);

        return $this->logAndResponse($result);
    }

    // #[OA\Post(
    //     path: '/api/qwen/text_summarization',
    //     operationId: 'text_summarization',
    //     description: 'Qwen Text Summarization',
    //     summary: 'Qwen Text Summarization',
    //     security: [['authentication' => []]],
    //     tags: ['Qwen'],
    // )]
    // #[OA\QueryParameter(
    //     name: 'model',
    //     description: 'Model',
    //     required: true,
    //     schema: new OA\Schema(
    //         type: 'string',
    //         example: 'qwen/qwq-32b:free'
    //     )
    // )]
    // #[OA\QueryParameter(
    //     name: 'text',
    //     description: 'Text to summarize',
    //     required: true,
    //     schema: new OA\Schema(
    //         type: 'string',
    //         example: 'Summarize the following text: The food was delicious and the waiter...'
    //     )
    // )]
    // #[OA\QueryParameter(
    //     name: 'text_length',
    //     description: 'Text length',
    //     required: false,
    //     schema: new OA\Schema(
    //         type: 'integer',
    //         example: 200
    //     )
    // )]
    // #[OA\QueryParameter(
    //     name: 'max_tokens',
    //     description: 'Max Tokens',
    //     required: false,
    //     schema: new OA\Schema(
    //         type: 'integer',
    //         example: 2000
    //     )
    // )]
    // #[OA\QueryParameter(
    //     name: 'temperature',
    //     description: 'Temperature',
    //     required: false,
    //     schema: new OA\Schema(
    //         type: 'number',
    //         example: 0.7
    //     )
    // )]
    // #[OA\QueryParameter(
    //     name: 'endpoint_interface',
    //     description: 'Endpoint Interface',
    //     required: false,
    //     schema: new OA\Schema(
    //         type: 'string',
    //         example: 'generate'
    //     )
    // )]
    // #[OA\Response(
    //     response: 200,
    //     description: 'Successful response',
    //     content: new OA\JsonContent(
    //         example: [
    //             'id' => 'qwen_response_id',
    //             'object' => 'qwen_response_object',
    //             'created' => 1234567890,
    //             'model' => 'qwen/qwq-32b:free',
    //             'choices' => [
    //                 [
    //                     'index' => 0,
    //                     'message' => [
    //                         'role' => 'assistant',
    //                         'content' => 'The food was delicious and the waiter was attentive.',
    //                     ],
    //                     'finish_reason' => 'stop',
    //                 ],
    //             ],
    //             'usage' => [
    //                 'prompt_tokens' => 10,
    //                 'completion_tokens' => 20,
    //                 'total_tokens' => 30,
    //             ],
    //         ],
    //     )
    // )]
    public function textSummarization(QwenTextSummarizationRequest $request): JsonResponse
    {
        $data = QwenTextSummarizationData::from($request->validated());

        $result = $this->service->textSummarization($data);

        return $this->logAndResponse($result);
    }

    // #[OA\Post(
    //     path: '/api/qwen/chatbot',
    //     operationId: 'chatbot',
    //     description: 'Qwen Chatbot',
    //     summary: 'Qwen Chatbot',
    //     security: [['authentication' => []]],
    //     tags: ['Qwen'],
    //     requestBody: new OA\RequestBody(
    //         required: true,
    //         content: new OA\JsonContent(
    //             type: 'object',
    //             required: ['model', 'conversation_history'],
    //             properties: [
    //                 new OA\Property(
    //                     property: 'model',
    //                     type: 'string',
    //                     example: 'qwen/qwq-32b:free'
    //                 ),
    //                 new OA\Property(
    //                     property: 'conversation_history',
    //                     type: 'array',
    //                     items: new OA\Items(
    //                         type: 'object',
    //                         required: ['role', 'content'],
    //                         properties: [
    //                             new OA\Property(property: 'role', type: 'string', example: 'user'),
    //                             new OA\Property(property: 'content', type: 'string', example: 'Hello, how are you?'),
    //                         ]
    //                     )
    //                 ),
    //                 new OA\Property(
    //                     property: 'temperature',
    //                     type: 'number',
    //                     example: 0.7
    //                 ),
    //                 new OA\Property(
    //                     property: 'max_tokens',
    //                     type: 'integer',
    //                     example: 4000
    //                 ),
    //                 new OA\Property(
    //                     property: 'endpoint_interface',
    //                     type: 'string',
    //                     example: 'generate'
    //                 ),
    //             ]
    //         )
    //     )
    // )]
    // #[OA\Response(
    //     response: 200,
    //     description: 'Successful response',
    //     content: new OA\JsonContent(
    //         example: [
    //             'id' => 'qwen_response_id',
    //             'object' => 'qwen_response_object',
    //             'created' => 1234567890,
    //             'model' => 'qwen/qwq-32b:free',
    //             'choices' => [
    //                 [
    //                     'index' => 0,
    //                     'message' => [
    //                         'role' => 'assistant',
    //                         'content' => 'Hello! I am doing well, thank you!',
    //                     ],
    //                     'finish_reason' => 'stop',
    //                 ],
    //             ],
    //             'usage' => [
    //                 'prompt_tokens' => 10,
    //                 'completion_tokens' => 20,
    //                 'total_tokens' => 30,
    //             ],
    //         ]
    //     )
    // )]
    public function chatbot(QwenChatbotRequest $request): JsonResponse
    {
        $data = QwenChatbotData::from($request->validated());

        $result = $this->service->chatbot($data);

        return $this->logAndResponse($result);
    }
}
