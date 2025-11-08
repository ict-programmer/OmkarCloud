<?php

namespace App\Http\Schemas\Common;

// use OpenApi\Attributes as OA;

// #[OA\Schema(
//     schema: 'ErrorResponse',
//     title: 'Error Response',
//     description: 'Standard error response format for internal server errors',
//     required: ['message', 'code'],
//     properties: [
//         new OA\Property(
//             property: 'message',
//             type: 'string',
//             description: 'Error message describing the failure'
//         ),
//         new OA\Property(
//             property: 'code',
//             type: 'integer',
//             description: 'Error code indicating the type of error'
//         ),
//         new OA\Property(
//             property: 'details',
//             type: 'object',
//             description: 'Additional error details if available',
//             nullable: true
//         )
//     ]
// )]
class ErrorResponseSchema
{
    //
}