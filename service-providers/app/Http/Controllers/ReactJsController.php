<?php

namespace App\Http\Controllers;

use App\Data\Request\ReactJs\ReactJsCodeForElementData;
use App\Data\Request\ReactJs\ReactJsCodeGenerationData;
use App\Data\Request\ReactJs\ReactJsMergeJsonData;
use App\Http\Requests\ReactJs\ReactJsCodeForElementRequest;
use App\Http\Requests\ReactJs\ReactJsCodeGenerationRequest;
use App\Http\Requests\ReactJs\ReactJsMergeJsonRequest;
use App\Services\ReactJsService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ReactJsController extends Controller
{
    public function __construct(protected ReactJsService $service) {}

    #[OA\Post(
        path: '/api/reactjs/generate-code-by-collecting',
        operationId: 'generateCodeCollecting',
        description: 'ReactJS Code Generation By Collecting',
        summary: 'Generate ReactJS code from a prompt.',
        security: [['authentication' => []]],
        tags: ['ReactJS'],
    )]
    #[OA\QueryParameter(
        name: 'design_id',
        description: 'The design ID to generate code for.',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            example: '13285:138691'
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(),
    )]
    public function generateCodeCollecting(ReactJsCodeGenerationRequest $request): JsonResponse
    {
        $data = ReactJsCodeGenerationData::from($request->validated());

        $response = $this->service->generateCodeCollecting($data);

        return response()->json($response);
    }

    #[OA\Post(
        path: '/api/reactjs/generate-code-directly',
        operationId: 'generateCodeDirectly',
        description: 'ReactJS Code Generation Directly',
        summary: 'Generate ReactJS code from a prompt.',
        security: [['authentication' => []]],
        tags: ['ReactJS'],
    )]
    #[OA\QueryParameter(
        name: 'design_id',
        description: 'The design ID to generate code for.',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            example: '13285:138691'
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(),
    )]
    public function generateCodeDirectly(ReactJsCodeGenerationRequest $request): JsonResponse
    {
        $data = ReactJsCodeGenerationData::from($request->validated());

        $response = $this->service->generateCodeDirectly($data);

        return response()->json($response);
    }

    #[OA\Post(
        path: '/api/reactjs/merge-json',
        operationId: 'mergeJson',
        description: 'Merge JSON for ReactJS code generation',
        summary: 'Merge JSON for ReactJS code generation',
        security: [['authentication' => []]],
        tags: ['ReactJS'],
    )]
    #[OA\QueryParameter(
        name: 'design_id',
        description: 'The design ID to generate code for.',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            example: '540:273203'
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(),
    )]
    public function mergeJson(ReactJsMergeJsonRequest $request): JsonResponse
    {
        $data = ReactJsMergeJsonData::from($request->validated());

        $response = $this->service->mergeJson($data);

        return response()->json([
            'success' => $response,
            'message' => 'JSON merged successfully',
        ]);
    }

    #[OA\Post(
        path: '/api/reactjs/react-code-for-element',
        operationId: 'reactCodeForElement',
        description: 'ReactJS Code Generation for Element',
        summary: 'Generate ReactJS code for a specific element.',
        security: [['authentication' => []]],
        tags: ['ReactJS'],
    )]
    #[OA\QueryParameter(
        name: 'element_id',
        description: 'The element ID to generate code for.',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            example: '540:299199'
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(),
    )]
    public function reactCodeForElement(ReactJsCodeForElementRequest $request): JsonResponse
    {
        $data = ReactJsCodeForElementData::from($request->validated());

        $response = $this->service->reactCodeForElement($data);

        return response()->json([
            'success' => $response,
            'message' => 'Code generated successfully',
        ]);
    }
}
