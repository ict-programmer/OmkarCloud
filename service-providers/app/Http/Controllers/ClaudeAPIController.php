<?php

namespace App\Http\Controllers;

use App\Data\Request\Claude\CodegenData;
use App\Data\Request\Claude\DataAnalysisInsightData;
use App\Data\Request\Claude\PersonalizationData;
use App\Data\Request\Claude\TextTranslateData;
use App\Data\Request\Claude\QuestionAnswerData;
use App\Data\Request\Claude\TextClassifyData;
use App\Data\Request\Claude\TextGenerationData;
use App\Data\Request\Claude\TextSummarizeData;
use App\Http\Requests\ClaudeAPI\CodegenRequest;
use App\Http\Requests\ClaudeAPI\DataAnalysisInsightRequest;
use App\Http\Requests\ClaudeAPI\PersonalizationRequest;
use App\Http\Requests\ClaudeAPI\QuestionAnswerRequest;
use App\Http\Requests\ClaudeAPI\TextClassifyRequest;
use App\Http\Requests\ClaudeAPI\TextGenerationRequest;
use App\Http\Requests\ClaudeAPI\TextSummarizeRequest;
use App\Http\Requests\ClaudeAPI\TextTranslateRequest;
use App\Services\ClaudeAPIService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ClaudeAPIController extends BaseController
{
    public function __construct(protected ClaudeAPIService $service) {}

    #[OA\Post(
        path: '/api/claudeapi/text_generation',
        operationId: '/api/claude/text_generation',
        description: 'Generate Text using Claude API',
        summary: 'Generate Text using Claude API',
        security: [['authentication' => []]],
        tags: ['ClaudeAPI'],
    )]
    #[OA\Parameter(
        name: 'prompt',
        in: 'query',
        required: true,
        description: 'The prompt for generating text.',
        schema: new OA\Schema(
            type: 'string',
            example: 'Write a short story about a brave knight saving a village from a dragon.',
        ),
    )]
    #[OA\Parameter(
        name: 'max_tokens',
        in: 'query',
        required: true,
        description: 'The maximum number of tokens to generate.',
        schema: new OA\Schema(
            type: 'integer',
            example: 200,
        ),
    )]
    #[OA\Response(
        response: 200,
        description: 'Generate Text using Claude API',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                type: 'object',
                properties: [
                    new OA\Property(
                        property: 'status',
                        type: 'boolean',
                        example: true,
                    ),
                    new OA\Property(
                        property: 'data',
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'response',
                                type: 'string',
                                description: 'The generated text based on the provided prompt.',
                                example: 'Once upon a time, in a faraway land, there lived a brave knight named Sir Lancelot. He was known throughout the kingdom for his courage and strength. One day, a terrible dragon appeared and began terrorizing the nearby village. The villagers were frightened and begged Sir Lancelot to help them. Without hesitation, he donned his armor and set off to confront the dragon. After a fierce battle, Sir Lancelot emerged victorious, saving the village and earning the gratitude of its people.',
                            ),
                            new OA\Property(
                                property: 'error',
                                type: 'string',
                                description: 'Any error message encountered during the summarization process.',
                                example: null,
                            ),
                        ]
                    ),
                ]
            ),
        ),
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
    public function textGeneration(TextGenerationRequest $request): JsonResponse
    {
        $data = TextGenerationData::from($request->validated());

        $result = $this->service->textGeneration($data);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/claudeapi/text_summarize',
        operationId: '/api/claude/text_summarize',
        description: 'Summarize Text using Claude API',
        summary: 'Summarize Text using Claude API',
        security: [['authentication' => []]],
        tags: ['ClaudeAPI'],
    )]
    #[OA\Parameter(
        name: 'text',
        in: 'query',
        required: true,
        description: 'The text to be summarized.',
        schema: new OA\Schema(
            type: 'string',
            example: 'Once upon a time, in a faraway land, there lived a brave knight named Sir Lancelot. He was known throughout the kingdom for his courage and strength. One day, a terrible dragon appeared and began terrorizing the nearby village. The villagers were frightened and begged Sir Lancelot to help them. Without hesitation, he donned his armor and set off to confront the dragon. After a fierce battle, Sir Lancelot emerged victorious, saving the village and earning the gratitude of its people.',
        ),
    )]
    #[OA\Parameter(
        name: 'summary_length',
        in: 'query',
        required: true,
        description: 'The desired length of the summary.',
        schema: new OA\Schema(
            type: 'string',
            example: 'short',
        ),
    )]
    #[OA\Response(
        response: 200,
        description: 'Summarize Text using Claude API',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                type: 'object',
                properties: [
                    new OA\Property(
                        property: 'status',
                        type: 'boolean',
                        example: true,
                    ),
                    new OA\Property(
                        property: 'data',
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'text',
                                type: 'string',
                                description: 'The original text provided for summarization.',
                                example: 'Once upon a time, in a faraway land, there lived a brave knight named Sir Lancelot. He was known throughout the kingdom for his courage and strength. One day, a terrible dragon appeared and began terrorizing the nearby village. The villagers were frightened and begged Sir Lancelot to help them. Without hesitation, he donned his armor and set off to confront the dragon. After a fierce battle, Sir Lancelot emerged victorious, saving the village and earning the gratitude of its people.',
                            ),
                            new OA\Property(
                                property: 'summary_length',
                                type: 'string',
                                description: 'The length of the summary generated.',
                                example: 'short',
                            ),
                            new OA\Property(
                                property: 'error',
                                type: 'string',
                                description: 'Any error message encountered during the summarization process.',
                                example: null,
                            ),
                        ]
                    ),
                ]
            ),
        ),
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'validation_error',
                'message' => [
                    'text' => 'The text field is required.',
                    'summary_length' => 'The summary length field is required.',
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
    public function textSummarize(TextSummarizeRequest $request): JsonResponse
    {
        $data = TextSummarizeData::from($request->validated());

        $result = $this->service->textSummarize($data);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/claudeapi/question_answer',
        operationId: '/api/claude/question_answer',
        description: 'Answer question using Claude API',
        summary: 'Answer question using Claude API',
        security: [['authentication' => []]],
        tags: ['ClaudeAPI'],
    )]
    #[OA\Parameter(
        name: 'question',
        in: 'query',
        required: true,
        description: 'The question to be answered.',
        schema: new OA\Schema(
            type: 'string',
            example: 'What are the main symptoms of COVID-19?',
        ),
    )]
    #[OA\Parameter(
        name: 'context',
        in: 'query',
        required: true,
        description: 'The context for answering the question.',
        schema: new OA\Schema(
            type: 'string',
            example: 'COVID-19 is a respiratory disease caused by the SARS-CoV-2 virus. Common symptoms include fever, dry cough, and fatigue. Less common symptoms may include loss of taste or smell, aches and pains, headache, sore throat, nasal congestion, red eyes, diarrhea, or a skin rash. Severe symptoms include difficulty breathing or shortness of breath, chest pain or pressure, and loss of speech or movement. People of all ages who experience fever and/or cough associated with difficulty breathing or shortness of breath should seek medical attention immediately.',
        ),
    )]
    #[OA\Response(
        response: 200,
        description: 'Answer question using Claude API',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                type: 'object',
                properties: [
                    new OA\Property(
                        property: 'status',
                        type: 'boolean',
                        example: true,
                    ),
                    new OA\Property(
                        property: 'data',
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'answer',
                                type: 'string',
                                description: 'The answer to the question based on the provided context.',
                                example: "The moral of the story is that courage and selflessness can overcome even the greatest challenges. Sir Lancelot's bravery in facing the dragon and his willingness to help the villagers demonstrate the importance of standing up for others in times of need.",
                            ),
                            new OA\Property(
                                property: 'error',
                                type: 'string',
                                description: 'Any error message encountered during the summarization process.',
                                example: null,
                            ),
                        ]
                    ),
                ]
            ),
        ),
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'validation_error',
                'message' => [
                    'question' => 'The question field is required.',
                    'context' => 'The context field is required.',
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
    public function questionAnswer(QuestionAnswerRequest $request): JsonResponse
    {
        $data = QuestionAnswerData::from($request->validated());

        $result = $this->service->questionAnswer($data);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/claudeapi/text_classify',
        operationId: '/api/claude/text_classify',
        description: 'Classify text using Claude API',
        summary: 'Classify text using Claude API',
        security: [['authentication' => []]],
        tags: ['ClaudeAPI'],
    )]
    #[OA\Parameter(
        name: 'text',
        in: 'query',
        required: true,
        description: 'The text to classify.',
        schema: new OA\Schema(
            type: 'string',
            example: 'This is a story about a brave knight who saved a village from a dragon.',
        ),
    )]
    #[OA\Parameter(
        name: 'categories',
        in: 'query',
        required: true,
        description: 'A comma-separated list of categories to classify the text into, such as genres or topics.',
        schema: new OA\Schema(
            type: 'string',
            example: 'Adventure, Fantasy, Drama',
        ),
    )]
    #[OA\Response(
        response: 200,
        description: 'Classify text using Claude API',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                type: 'object',
                properties: [
                    new OA\Property(
                        property: 'status',
                        type: 'boolean',
                        example: true,
                    ),
                    new OA\Property(
                        property: 'data',
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'sentiment',
                                type: 'string',
                                example: 'positive'
                            ),
                            new OA\Property(
                                property: 'category',
                                type: 'string',
                                example: 'product_review'
                            ),
                        ]
                    ),
                ]
            ),
        ),
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'validation_error',
                'message' => [
                    'text' => 'The text field is required.',
                    'categories' => 'The categories field is required.',
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
    public function textClassify(TextClassifyRequest $request): JsonResponse
    {
        $data = TextClassifyData::from($request->validated());

        $result = $this->service->textClassify($data);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/claudeapi/text_translate',
        operationId: '/api/claude/text_translate',
        description: 'Translate text using Claude API',
        summary: 'Translate text using Claude API',
        security: [['authentication' => []]],
        tags: ['ClaudeAPI'],
    )]
    #[OA\Parameter(
        name: 'text',
        in: 'query',
        required: true,
        description: 'The text to translate.',
        schema: new OA\Schema(
            type: 'string',
            example: 'Hello, how are you?',
        ),
    )]
    #[OA\Parameter(
        name: 'source_language',
        in: 'query',
        required: true,
        description: 'The source language of the text.',
        schema: new OA\Schema(
            type: 'string',
            example: 'en',
        ),
    )]
    #[OA\Parameter(
        name: 'target_language',
        in: 'query',
        required: true,
        description: 'The target language for the translation.',
        schema: new OA\Schema(
            type: 'string',
            example: 'es',
        ),
    )]
    #[OA\Response(
        response: 200,
        description: 'Translate text using Claude API',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                type: 'object',
                properties: [
                    new OA\Property(
                        property: 'status',
                        type: 'boolean',
                        example: true,
                    ),
                    new OA\Property(
                        property: 'data',
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'translated_text',
                                type: 'string',
                                example: 'Hola, ¿cómo estás?',
                            ),
                        ]
                    ),
                ]
            ),
        ),
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'validation_error',
                'message' => [
                    'text' => 'The text field is required.',
                    'source_language' => 'The source language field is required.',
                    'target_language' => 'The target language field is required.',
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
    public function textTranslate(TextTranslateRequest $request): JsonResponse
    {
        $data = TextTranslateData::from($request->validated());

        $result = $this->service->textTranslate($data);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/claudeapi/codegen',
        operationId: '/api/claudeapi/codegen',
        description: 'Generate code using Claude API',
        summary: 'Generate code using Claude API',
        security: [['authentication' => []]],
        tags: ['ClaudeAPI'],
    )]
    #[OA\Parameter(
        name: 'description',
        in: 'query',
        required: true,
        description: 'Write a Python function to calculate the factorial of a number.',
        schema: new OA\Schema(
            type: 'string',
            example: 'Write a Python function to calculate the factorial of a number.',
        ),
    )]
    #[OA\Parameter(
        name: 'attachments[]',
        in: 'query',
        required: false,
        description: 'Array of files to attach',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(type: 'string', format: 'binary'),
            example: [],
        ),
    )]
    #[OA\Response(
        response: 200,
        description: 'Generate code using Claude API',
        content: new OA\JsonContent(
            example: [
                'status' => 'success',
                'data' => [
                    'code' => 'def factorial(n):\n if n == 0:\n return 1\n else:\n return n * factorial(n-1)',
                ],
            ],
        ),
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(
            example: [
                'status' => 'validation_error',
                'message' => [
                    'description' => 'The description field is required.',
                ],
            ],
        ),
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while processing your request',
            ],
        ),
    )]
    public function codegen(CodegenRequest $request): JsonResponse
    {
        $data = CodegenData::from($request->validated());

        $result = $this->service->codegen($data);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/claudeapi/data_analysis_insight_service',
        operationId: '/api/claudeapi/data_analysis_insight_service',
        description: 'Perform data analysis and insights using Claude API',
        summary: 'Perform data analysis and insights using Claude API',
        security: [['authentication' => []]],
        tags: ['ClaudeAPI'],
    )]
    #[OA\RequestBody(
        description: 'Perform data analysis and insights using Claude API',
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                type: 'object',
                required: ['data', 'task'],
                properties: [
                    new OA\Property(
                        property: 'data',
                        type: 'array',
                        items: new OA\Items(
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'name', type: 'string', example: 'Alice'),
                                new OA\Property(property: 'age', type: 'integer', example: 30),
                                new OA\Property(property: 'score', type: 'integer', example: 85),
                            ]
                        ),
                        example: [
                            ['name' => 'Alice', 'age' => 30, 'score' => 85],
                            ['name' => 'Bob', 'age' => 25, 'score' => 90],
                        ]
                    ),
                    new OA\Property(
                        property: 'task',
                        type: 'string',
                        example: 'average_score',
                    ),
                ]
            ),
        ),
    )]
    #[OA\Response(
        response: 200,
        description: 'Perform data analysis and insights using Claude API',
        content: new OA\JsonContent(
            example: [
                'status' => 'success',
                'data' => [
                    'average_score' => 85,
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
                    'data' => 'The data field is required.',
                    'task' => 'The task field is required.',
                ],
            ],
        ),
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while processing your request',
            ],
        ),
    )]
    public function dataAnalysisAndInsight(DataAnalysisInsightRequest $request): JsonResponse
    {
        $data = DataAnalysisInsightData::from($request->validated());

        $result = $this->service->dataAnalysisAndInsight($data);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/claudeapi/personalize',
        operationId: '/api/claudeapi/personalize',
        description: 'Personalize your own content using Claude AI',
        summary: 'Personalize your own content using Claude AI',
        security: [['authentication' => []]],
        tags: ['ClaudeAPI'],
    )]
    #[OA\Parameter(
        name: 'user_id',
        in: 'query',
        required: true,
        description: 'The ID of the user.',
        schema: new OA\Schema(
            type: 'string',
            example: '12345',
        ),
    )]
    #[OA\Parameter(
        name: 'preferences',
        in: 'query',
        required: true,
        description: 'The preferences of the user.',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(type: 'string'),
            example: ['technology', 'science'],
        ),
    )]
    #[OA\Response(
        response: 200,
        description: 'Personalize content using Claude API',
        content: new OA\JsonContent(
            example: [
                'status' => 'success',
                'data' => [
                    'personalized_content' => 'I am a software engineer with a strong interest in technology and science. I enjoy working on projects that involve complex algorithms and data analysis.',
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
                    'user_id' => 'The user_id field is required.',
                    'preferences' => 'The preferences field is required.',
                ],
            ],
        ),
    )]
    #[OA\Response(
        response: 500,
        description: 'Server error',
        content: new OA\JsonContent(
            example: [
                'status' => 'error',
                'message' => 'An error occurred while processing your request',
            ],
        ),
    )]
    public function personalize(PersonalizationRequest $request): JsonResponse
    {
        $data = PersonalizationData::from($request->validated());

        $result = $this->service->personalize($data);

        return $this->logAndResponse($result);
    }
}
