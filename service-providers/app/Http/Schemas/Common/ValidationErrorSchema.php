<?php

namespace App\Http\Schemas\Common;

// use OpenApi\Attributes as OA;

// #[OA\Schema(
//     schema: 'ValidationError',
//     title: 'Validation Error',
//     description: 'Standard validation error response format',
//     required: ['message', 'errors'],
//     properties: [
//         new OA\Property(
//             property: 'message',
//             type: 'string',
//             description: 'Error message describing the validation failure'
//         ),
//         new OA\Property(
//             property: 'errors',
//             type: 'object',
//             description: 'Field-specific validation errors',
//             additionalProperties: new OA\AdditionalProperties(
//                 type: 'array',
//                 items: new OA\Items(type: 'string')
//             )
//         )
//     ]
// )]
class ValidationErrorSchema
{
    //
}