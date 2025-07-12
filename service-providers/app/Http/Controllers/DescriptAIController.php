<?php

namespace App\Http\Controllers;

use App\Data\Request\DescriptAI\GenerateAsyncData;
use App\Http\Requests\DescriptAI\GenerateAsyncRequest;
use App\Services\DescriptAIService;
use OpenApi\Attributes as OA;

class DescriptAIController extends Controller
{
    public function __construct(protected DescriptAIService $service) {}

    // #[OA\Post(
    //     path: '/api/descriptai/generate',
    //     operationId: 'generateOverdubAsyncTask',
    //     description: 'It will create an Overdub generate async task',
    //     summary: 'It will create an Overdub generate async task',
    //     security: [['authentication' => []]],
    //     tags: ['DescriptAI'],
    // )]
    // #[OA\RequestBody(
    //     description: 'It will create an Overdub generate async task',
    //     required: true,
    //     content: new OA\MediaType(
    //         mediaType: 'application/json',
    //         schema: new OA\Schema(
    //             type: 'object',
    //             required: ['text', 'voice_id', 'voice_style_id'],
    //             properties: [
    //                 new OA\Property(
    //                     property: 'text',
    //                     type: 'string',
    //                     example: 'Hey everyone, this is a generated audio.',
    //                 ),
    //                 new OA\Property(
    //                     property: 'voice_id',
    //                     type: 'string',
    //                     example: '2235c634-e4db-4ad6-aa15-066fb69b1c8f',
    //                 ),
    //                 new OA\Property(
    //                     property: 'voice_style_id',
    //                     type: 'string',
    //                     example: 'b9a0ebf3-b259-4b98-975c-a847678f9faf',
    //                 ),
    //                 new OA\Property(
    //                     property: 'prefix_text',
    //                     type: 'string',
    //                     example: 'string',
    //                 ),
    //                 new OA\Property(
    //                     property: 'prefix_audio_url',
    //                     type: 'string',
    //                     example: 'string',
    //                 ),
    //                 new OA\Property(
    //                     property: 'suffix_text',
    //                     type: 'string',
    //                     example: 'string',
    //                 ),
    //                 new OA\Property(
    //                     property: 'suffix_audio_url',
    //                     type: 'string',
    //                     example: 'string',
    //                 ),
    //                 new OA\Property(
    //                     property: 'callback_url',
    //                     type: 'string',
    //                     example: 'string',
    //                 ),
    //             ]
    //         ),
    //     ),
    // )]
    // #[OA\Response(
    //     response: 200,
    //     description: 'It will create an Overdub generate async task',
    //     content: new OA\MediaType(
    //         mediaType: 'application/json',
    //         schema: new OA\Schema(
    //             type: 'object',
    //             properties: [
    //                 new OA\Property(
    //                     property: 'id',
    //                     type: 'string',
    //                     description: 'The unique identifier for the generated task.',
    //                     example: '497f6eca-6276-4993-bfeb-53cbbbba6f08',
    //                 ),
    //                 new OA\Property(
    //                     property: 'state',
    //                     type: 'string',
    //                     description: 'The current state of the task.',
    //                     example: 'queued',
    //                 ),
    //                 new OA\Property(
    //                     property: 'url',
    //                     type: 'string',
    //                     description: 'The URL to access the generated audio.',
    //                     example: 'https://a-url-to-audio.com',
    //                 ),
    //             ]
    //         ),
    //     ),
    // )]
    // #[OA\Response(
    //     response: 422,
    //     description: 'Validation error',
    //     content: new OA\JsonContent(
    //         example: [
    //             'status' => 'validation_error',
    //             'message' => [
    //                 'text' => 'The text field is required.',
    //                 'voice_id' => 'The voice_id field is required.',
    //                 'voice_style_id' => 'The voice_style_id field is required.',
    //             ],
    //         ],
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
    public function generateAsync(GenerateAsyncRequest $request)
    {
        $data = GenerateAsyncData::from($request->validated());

        $result = $this->service->generateAsync($data);

        return $result;
    }


    // #[OA\Get(
    //     path: '/api/descriptai/generate_async/{id}',
    //     operationId: 'getGenerateAsync',
    //     description: 'Retrieve the status and details of a previously submitted Overdub generate async.',
    //     summary: 'Retrieve Overdub generate async details',
    //     security: [['authentication' => []]],
    //     tags: ['DescriptAI'],
    // )]
    // #[OA\Parameter(
    //     name: 'id',
    //     in: 'path',
    //     required: true,
    //     description: 'The unique identifier of the Overdub to retrieve.',
    //     schema: new OA\Schema(
    //         type: 'string',
    //         example: '497f6eca-6276-4993-bfeb-53cbbbba6f08',
    //     ),
    // )]
    // #[OA\Response(
    //     response: 200,
    //     description: 'Successfully retrieved the Overdub details.',
    //     content: new OA\MediaType(
    //         mediaType: 'application/json',
    //         schema: new OA\Schema(
    //             type: 'object',
    //             properties: [
    //                 new OA\Property(
    //                     property: 'id',
    //                     type: 'string',
    //                     description: 'The unique identifier for the Overdub.',
    //                     example: '497f6eca-6276-4993-bfeb-53cbbbba6f08',
    //                 ),
    //                 new OA\Property(
    //                     property: 'state',
    //                     type: 'string',
    //                     description: 'The current state of the Overdub.',
    //                     example: 'queued',
    //                 ),
    //                 new OA\Property(
    //                     property: 'url',
    //                     type: 'string',
    //                     description: 'The URL to access the generated audio.',
    //                     example: 'https://a-url-to-audio.com',
    //                 ),
    //             ]
    //         ),
    //     ),
    // )]
    // #[OA\Response(
    //     response: 422,
    //     description: 'Validation error occurred while retrieving the Overdub.',
    //     content: new OA\JsonContent(
    //         example: [
    //             'status' => 'validation_error',
    //             'message' => [
    //                 'id' => 'The id field is required.',
    //             ],
    //         ],
    //     )
    // )]
    // #[OA\Response(
    //     response: 500,
    //     description: 'Server error occurred while retrieving the Overdub.',
    //     content: new OA\JsonContent(
    //         example: [
    //             'status' => 'error',
    //             'message' => 'An error occurred while processing your request.',
    //         ],
    //     )
    // )]
    public function getGenerateAsync(string $id)
    {
        $result = $this->service->getGenerateAsync($id);

        return $result->data;
    }

    // #[OA\Get(
    //     path: '/api/descriptai/get_voices',
    //     operationId: 'getVoices',
    //     description: 'Retrieve the list of available voices along with their styles.',
    //     summary: 'Retrieve available voices and styles',
    //     security: [['authentication' => []]],
    //     tags: ['DescriptAI'],
    // )]
    // #[OA\Response(
    //     response: 200,
    //     description: 'Successfully retrieved the list of voices and their styles.',
    //     content: new OA\MediaType(
    //         mediaType: 'application/json',
    //         schema: new OA\Schema(
    //             type: 'array',
    //             items: new OA\Items(
    //                 type: 'object',
    //                 properties: [
    //                     new OA\Property(
    //                         property: 'id',
    //                         type: 'string',
    //                         description: 'The unique identifier for the voice.',
    //                         example: '497f6eca-6276-4993-bfeb-53cbbbba6f08',
    //                     ),
    //                     new OA\Property(
    //                         property: 'name',
    //                         type: 'string',
    //                         description: 'The name of the voice.',
    //                         example: 'string',
    //                     ),
    //                     new OA\Property(
    //                         property: 'styles',
    //                         type: 'array',
    //                         description: 'The list of styles associated with the voice.',
    //                         items: new OA\Items(
    //                             type: 'object',
    //                             properties: [
    //                                 new OA\Property(
    //                                     property: 'id',
    //                                     type: 'string',
    //                                     description: 'The unique identifier for the voice style.',
    //                                     example: '497f6eca-6276-4993-bfeb-53cbbbba6f08',
    //                                 ),
    //                                 new OA\Property(
    //                                     property: 'name',
    //                                     type: 'string',
    //                                     description: 'The name of the voice style.',
    //                                     example: 'string',
    //                                 ),
    //                             ]
    //                         ),
    //                     ),
    //                     new OA\Property(
    //                         property: 'is_public',
    //                         type: 'boolean',
    //                         description: 'Indicates if the voice is public.',
    //                         example: true,
    //                     ),
    //                     new OA\Property(
    //                         property: 'status',
    //                         type: 'string',
    //                         description: 'The current status of the voice.',
    //                         example: 'active',
    //                     ),
    //                 ]
    //             ),
    //         ),
    //     ),
    // )]
    // #[OA\Response(
    //     response: 500,
    //     description: 'Server error occurred while retrieving the voices.',
    //     content: new OA\JsonContent(
    //         example: [
    //             'status' => 'error',
    //             'message' => 'An error occurred while processing your request.',
    //         ],
    //     )
    // )]
    public function getVoices()
    {
        $result = $this->service->getVoices();

        return $result->data;
    }
}
