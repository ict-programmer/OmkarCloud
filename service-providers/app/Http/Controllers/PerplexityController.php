<?php

namespace App\Http\Controllers;

use App\Data\Request\Perplexity\AcademicResearchData;
use App\Data\Request\Perplexity\AiSearchData;
use App\Data\Request\Perplexity\CodeAssistantData;
use App\Http\Requests\Perplexity\AcademicResearchRequest;
use App\Http\Requests\Perplexity\AiSearchRequest;
use App\Http\Requests\Perplexity\CodeAssistantRequest;
use App\Services\PerplexityService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class PerplexityController extends BaseController
{
    public function __construct(protected PerplexityService $service) {}

    #[OA\Post(
        path: '/api/perplexity/ai_search',
        operationId: 'ai_search',
        description: 'Perplexity AI Search',
        summary: 'Perplexity AI Search',
        tags: ['Perplexity'],
    )]
    #[OA\Parameter(
        name: 'model',
        in: 'query',
        description: 'Model name',
        required: false,
        schema: new OA\Schema(type: 'string', enum: ['sonar-pro', 'sonar'], nullable: true, example: 'sonar')
    )]
    #[OA\Parameter(
        name: 'query',
        in: 'query',
        description: 'Search query text',
        required: true,
        schema: new OA\Schema(type: 'string', example: 'What is AI?')
    )]
    #[OA\Parameter(
        name: 'search_type',
        in: 'query',
        description: 'Type of search',
        required: true,
        schema: new OA\Schema(type: 'string', enum: ['web', 'news'], example: 'web')
    )]
    #[OA\Parameter(
        name: 'max_results',
        in: 'query',
        description: 'Maximum number of results',
        required: false,
        schema: new OA\Schema(type: 'integer', minimum: 0, nullable: true, example: 0)
    )]
    #[OA\Parameter(
        name: 'temperature',
        in: 'query',
        description: 'Temperature for search randomness (0 to <2)',
        required: false,
        schema: new OA\Schema(type: 'number', format: 'float', minimum: 0, exclusiveMaximum: 2, nullable: true, example: 0.2)
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
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
            ],
        )
    )]
    public function aiSearch(AiSearchRequest $request): JsonResponse
    {
        $data = AiSearchData::from($request->validated());

        $result = $this->service->aiSearch($data);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/perplexity/academic_research',
        operationId: 'academic_research',
        description: 'Perplexity Academic Research',
        summary: 'Perplexity Academic Research',
        tags: ['Perplexity'],
    )]
    #[OA\Parameter(
        name: 'model',
        in: 'query',
        description: 'Model name',
        required: false,
        schema: new OA\Schema(type: 'string', enum: ['sonar-deep-research'], nullable: true, example: 'sonar-deep-research')
    )]
    #[OA\Parameter(
        name: 'query',
        in: 'query',
        description: 'Research query text',
        required: true,
        schema: new OA\Schema(type: 'string', example: 'Impact of artificial intelligence on healthcare')
    )]
    #[OA\Parameter(
        name: 'search_type',
        in: 'query',
        description: 'Type of research',
        required: false,
        schema: new OA\Schema(type: 'string', enum: ['academic'], nullable: true, example: 'academic')
    )]
    #[OA\Parameter(
        name: 'max_results',
        in: 'query',
        description: 'Maximum number of results',
        required: false,
        schema: new OA\Schema(type: 'integer', minimum: 0, nullable: true, example: 0)
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
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
            ],
        )
    )]
    public function academicResearch(AcademicResearchRequest $request): JsonResponse
    {
        $data = AcademicResearchData::from($request->validated());

        $result = $this->service->academicResearch($data);

        return $this->logAndResponse($result);
    }

    #[OA\Post(
        path: '/api/perplexity/code_assistant',
        operationId: 'code_assistant',
        description: 'Perplexity Code Assistant',
        summary: 'Perplexity Code Assistant',
        security: [['authentication' => []]],
        tags: ['Perplexity'],
    )]
    #[OA\QueryParameter(
        name: 'model',
        description: 'Model name (Optional). Options: sonar-reasoning, sonar-reasoning-pro',
        required: false,
        schema: new OA\Schema(type: 'string', enum: ['sonar-reasoning', 'sonar-reasoning-pro'], example: 'sonar-reasoning')
    )]
    #[OA\QueryParameter(
        name: 'query',
        description: 'Coding question',
        required: true,
        schema: new OA\Schema(type: 'string', example: 'How to reverse a string in Python?')
    )]
    #[OA\QueryParameter(
        name: 'programming_language',
        description: 'Programming language (Optional)',
        required: false,
        schema: new OA\Schema(type: 'string', example: 'python')
    )]
    #[OA\QueryParameter(
        name: 'code_length',
        description: 'Desired code length (Optional). Options: short, medium, long',
        required: false,
        schema: new OA\Schema(type: 'string', enum: ['short', 'medium', 'long'], example: 'medium')
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(
            example: [
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
            ]
        )
    )]
    public function codeAssistant(CodeAssistantRequest $request): JsonResponse
    {
        $data = CodeAssistantData::from($request->validated());

        $result = $this->service->codeAssistant($data);

        return $this->logAndResponse($result);
    }
}
