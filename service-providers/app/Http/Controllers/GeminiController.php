<?php

namespace App\Http\Controllers;

use App\Data\Request\Gemini\CodeGenerationData;
use App\Data\Request\Gemini\DocumentSummarizationData;
use App\Data\Request\Gemini\ImageAnalysisData;
use App\Data\Request\Gemini\TextGenerationData;
use App\Http\Exceptions\Forbidden;
use App\Http\Exceptions\NotFound;
use App\Http\Requests\Gemini\CodeGenerationRequest;
use App\Http\Requests\Gemini\DocumentSummarizationRequest;
use App\Http\Requests\Gemini\ImageAnalysisRequest;
use App\Http\Requests\Gemini\TextGenerationRequest;
use App\Services\GeminiService;
use Illuminate\Http\Client\ConnectionException;
use OpenApi\Attributes as OA;
use Illuminate\Http\JsonResponse;

class GeminiController extends BaseController
{
    public function __construct(protected GeminiService $service) {}

    /**
     * @throws Forbidden
     * @throws ConnectionException
     * @throws NotFound
     */
    // #[OA\Post(
    //     path: '/api/gemini/text_generation',
    //     operationId: '/api/gemini/text_generation',
    //     description: 'Generate Text using Gemini',
    //     summary: 'Generate Text using Gemini',
    //     tags: ['Gemini'],
    // )]
    // #[OA\RequestBody(
    //     description: 'Generate Text using Gemini',
    //     required: true,
    //     content: new OA\MediaType(
    //         mediaType: 'multipart/form-data',
    //         schema: new OA\Schema(
    //             required: ['model', 'prompt', 'max_tokens', 'temperature'],
    //             properties: [
    //                 new OA\Property(
    //                     property: 'model',
    //                     type: 'string',
    //                     enum: ['gemini-2.5-flash', 'gemini-2.5-pro', 'gemini-2.0-flash', 'gemini-1.5-flash', 'gemini-1.5-pro'],
    //                     example: 'gemini-2.5-flash',
    //                 ),
    //                 new OA\Property(
    //                     property: 'prompt',
    //                     type: 'string',
    //                     example: 'Write a short story about a brave knight saving a village from a dragon.',
    //                 ),
    //                 new OA\Property(
    //                     property: 'max_tokens',
    //                     type: 'integer',
    //                     example: 200,
    //                 ),
    //                 new OA\Property(
    //                     property: 'temperature',
    //                     type: 'number',
    //                     format: 'float',
    //                     maximum: 1,
    //                     minimum: 0,
    //                     example: 0.7,
    //                 ),
    //             ],
    //             type: 'object'
    //         ),
    //     ),
    // )]
    // #[OA\Response(
    //     response: 200,
    //     description: 'Successful response',
    //     content: new OA\JsonContent(
    //         example: [
    //             'status' => 'success',
    //             'data' => [
    //                 'text' => 'Once upon a time, in a faraway land...',
    //             ],
    //             'timestamp' => '2025-05-01T12:45:30+00:00'
    //         ]
    //     )
    // )]
    // #[OA\Response(
    //     response: 422,
    //     description: 'Validation error',
    //     content: new OA\JsonContent(
    //         example: [
    //             'status' => 'validation_error',
    //             'message' => [
    //                 'prompt' => 'The prompt field is required.',
    //                 'max_tokens' => 'The max tokens field is required.',
    //             ],
    //         ]
    //     )
    // )]
    // #[OA\Response(
    //     response: 500,
    //     description: 'Server error',
    //     content: new OA\JsonContent(
    //         example: [
    //             'status' => 'error',
    //             'message' => 'An error occurred while processing your request.',
    //         ],
    //     )
    // )]
    public function textGeneration(TextGenerationRequest $request): JsonResponse
    {
        $data = TextGenerationData::from($request->validated());
        $result = $this->service->textGeneration($data);

        return $this->logAndResponse($result);
    }

    /**
     * @throws Forbidden
     * @throws ConnectionException
     * @throws NotFound
     */
    // #[OA\Post(
    //     path: '/api/gemini/code_generation',
    //     operationId: '/api/gemini/code_generation',
    //     description: 'Generate Code using Gemini',
    //     summary: 'Generate Code using Gemini',
    //     tags: ['Gemini'],
    // )]
    // #[OA\RequestBody(
    //     description: 'Generate Code using Gemini',
    //     required: true,
    //     content: new OA\MediaType(
    //         mediaType: 'multipart/form-data',
    //         schema: new OA\Schema(
    //             required: ['model', 'prompt', 'max_tokens', 'temperature'],
    //             properties: [
    //                 new OA\Property(
    //                     property: 'model',
    //                     type: 'string',
    //                     enum: ['gemini-2.5-flash', 'gemini-2.5-pro', 'gemini-2.0-flash', 'gemini-1.5-flash', 'gemini-1.5-pro'],
    //                     example: 'gemini-1.5-pro',
    //                 ),
    //                 new OA\Property(
    //                     property: 'prompt',
    //                     type: 'string',
    //                     example: 'Give me simple html code',
    //                 ),
    //                 new OA\Property(
    //                     property: 'max_tokens',
    //                     type: 'integer',
    //                     example: 200,
    //                 ),
    //                 new OA\Property(
    //                     property: 'temperature',
    //                     type: 'number',
    //                     format: 'float',
    //                     maximum: 1,
    //                     minimum: 0,
    //                     example: 0.7,
    //                 ),
    //                 new OA\Property(
    //                     property: 'attachments[]',
    //                     description: 'Array of files to attach',
    //                     type: 'array',
    //                     items: new OA\Items(type: 'string', format: 'binary'),
    //                     example: [],
    //                     nullable: true
    //                 )
    //             ],
    //             type: 'object'
    //         ),
    //     ),
    // )]
    // #[OA\Response(
    //     response: 200,
    //     description: 'Successful response',
    //     content: new OA\JsonContent(
    //         example: [
    //             'status' => 'success',
    //             'data' => [
    //                 'code' => 'def factorial(n):\n if n == 0:\n return 1\n else:\n return n * factorial(n-1)',
    //             ],
    //             'timestamp' => '2025-05-01T12:45:30+00:00'
    //         ]
    //     )
    // )]
    // #[OA\Response(
    //     response: 422,
    //     description: 'Validation error',
    //     content: new OA\JsonContent(
    //         example: [
    //             'status' => 'validation_error',
    //             'message' => [
    //                 'prompt' => 'The prompt field is required.',
    //                 'max_tokens' => 'The max tokens field is required.',
    //             ],
    //         ]
    //     )
    // )]
    // #[OA\Response(
    //     response: 500,
    //     description: 'Server error',
    //     content: new OA\JsonContent(
    //         example: [
    //             'status' => 'error',
    //             'message' => 'An error occurred while processing your request',
    //         ],
    //     )
    // )]
    public function codeGeneration(CodeGenerationRequest $request): JsonResponse
    {
        $data = CodeGenerationData::from($request->validated());
        $result = $this->service->codeGeneration($data);

        return $this->logAndResponse($result);
    }

    /**
     * @throws Forbidden
     * @throws ConnectionException
     * @throws NotFound
     */
    // #[OA\Post(
    //     path: '/api/gemini/image_analysis',
    //     operationId: '/api/gemini/image_analysis',
    //     description: 'Image analysis using Gemini',
    //     summary: 'Image analysis using Gemini',
    //     tags: ['Gemini'],
    // )]
    // #[OA\RequestBody(
    //     description: 'Image analysis using Gemini',
    //     required: true,
    //     content: new OA\MediaType(
    //         mediaType: 'multipart/form-data',
    //         schema: new OA\Schema(
    //             required: ['image_url', 'description_required'],
    //             properties: [
    //                 new OA\Property(
    //                     property: 'image_url',
    //                     type: 'string',
    //                     example: 'https://cdn.firstcry.com/education/2022/11/29101350/AI-Words-For-Kids-To-Improve-Vocabulary-Skills.jpg'
    //                 ),
    //                 new OA\Property(
    //                     property: 'description_required',
    //                     type: 'string',
    //                     example: 'give me the content of the image',
    //                 ),
    //             ],
    //             type: 'object'
    //         ),
    //     ),
    // )]
    // #[OA\Response(
    //     response: 200,
    //     description: 'Successful response',
    //     content: new OA\JsonContent(
    //         example: [
    //             'status' => 'success',
    //             'data' => [
    //                 'analysis' => 'The image is a painting of a landscape with a mountain in the background and a river flowing through it. The colors are warm and vibrant, and the painting is very detailed and realistic. The image is a beautiful representation of nature.',
    //             ],
    //             'timestamp' => '2025-05-01T12:45:30+00:00'
    //         ]
    //     )
    // )]
    // #[OA\Response(
    //     response: 422,
    //     description: 'Validation error',
    //     content: new OA\JsonContent(
    //         example: [
    //             'status' => 'validation_error',
    //             'message' => [
    //                 'prompt' => 'The prompt field is required.',
    //                 'max_tokens' => 'The max tokens field is required.',
    //             ],
    //         ]
    //     )
    // )]
    // #[OA\Response(
    //     response: 500,
    //     description: 'Server error',
    //     content: new OA\JsonContent(
    //         example: [
    //             'status' => 'error',
    //             'message' => 'An error occurred while processing your request',
    //         ],
    //     )
    // )]
    public function imageAnalysis(ImageAnalysisRequest $request): string
    {
        $data = ImageAnalysisData::from($request->validated());
        $result = $this->service->imageAnalysis($data);

        return $this->logAndResponse($result);
    }

    /**
     * @throws Forbidden
     * @throws ConnectionException
     * @throws NotFound
     */
    // #[OA\Post(
    //     path: '/api/gemini/document_summarization',
    //     operationId: '/api/gemini/document_summarization',
    //     description: 'Document summarization using Gemini',
    //     summary: 'Document summarization using Gemini',
    //     tags: ['Gemini'],
    // )]
    // #[OA\RequestBody(
    //     description: 'Image analysis using Gemini',
    //     required: true,
    //     content: new OA\MediaType(
    //         mediaType: 'multipart/form-data',
    //         schema: new OA\Schema(
    //             required: ['document_text', 'model', 'summary_length'],
    //             properties: [
    //                 new OA\Property(
    //                     property: 'document_text',
    //                     type: 'string',
    //                     example: 'nce upon a time, in a faraway land, there lived a brave knight named Sir Lancelot. He was known throughout the kingdom for his courage and strength. One day, a terrible dragon appeared and began terrorizing the nearby village. The villagers were frightened and begged Sir Lancelot to help them. Without hesitation, he donned his armor and set off to confront the dragon. After a fierce battle, Sir Lancelot emerged victorious, saving the village and earning the gratitude of its people.'
    //                 ),
    //                 new OA\Property(
    //                     property: 'model',
    //                     type: 'string',
    //                     enum: ['gemini-2.5-flash', 'gemini-2.5-pro', 'gemini-2.0-flash', 'gemini-1.5-flash', 'gemini-1.5-pro'],
    //                     example: 'gemini-1.5-pro',
    //                 ),
    //                 new OA\Property(
    //                     property: 'summary_length',
    //                     type: 'integer',
    //                     example: 20
    //                 ),
    //             ],
    //             type: 'object'
    //         ),
    //     ),
    // )]
    // #[OA\Response(
    //     response: 200,
    //     description: 'Successful response',
    //     content: new OA\JsonContent(
    //         example: [
    //             'status' => 'success',
    //             'data' => [
    //                 'summary' => 'The document is a short story about a young boy who discovers a magical portal to another world. The story is written in a simple and engaging style, and the characters are well-developed and relatable. The summary is a concise summary of the main points and key takeaways from the document.',
    //             ],
    //             'timestamp' => '2025-05-01T12:45:30+00:00'
    //         ]
    //     )
    // )]
    // #[OA\Response(
    //     response: 422,
    //     description: 'Validation error',
    //     content: new OA\JsonContent(
    //         example: [
    //             'status' => 'validation_error',
    //             'message' => [
    //                 'prompt' => 'The prompt field is required.',
    //                 'max_tokens' => 'The max tokens field is required.',
    //             ],
    //         ]
    //     )
    // )]
    // #[OA\Response(
    //     response: 500,
    //     description: 'Server error',
    //     content: new OA\JsonContent(
    //         example: [
    //             'status' => 'error',
    //             'message' => 'An error occurred while processing your request',
    //         ],
    //     )
    // )]
    public function documentSummarization(DocumentSummarizationRequest $request): string
    {
        $data = DocumentSummarizationData::from($request->validated());
        $result = $this->service->documentSummarization($data);

        return $this->logAndResponse($result);
    }
}
