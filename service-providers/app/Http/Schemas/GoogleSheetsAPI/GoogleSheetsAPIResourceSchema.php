<?php

namespace App\Http\Schemas\GoogleSheetsAPI;

// use OpenApi\Attributes as OA;

// #[OA\Schema(
//     schema: 'GoogleSheetsAPIResource',
//     title: 'Google Sheets API Resource Response',
//     description: 'Standardized response format for Google Sheets API operations',
//     required: ['status', 'data', 'timestamp'],
//     properties: [
//         new OA\Property(
//             property: 'status',
//             type: 'string',
//             description: 'Response status (success or error)',
//             enum: ['success', 'error']
//         ),
//         new OA\Property(
//             property: 'data',
//             type: 'object',
//             description: 'Response data payload',
//             properties: [
//                 new OA\Property(
//                     property: 'message',
//                     type: 'string',
//                     description: 'Error message (only present when status is error)'
//                 ),
//                 new OA\Property(
//                     property: 'code',
//                     type: 'integer',
//                     description: 'Error code (only present when status is error)'
//                 )
//             ],
//             additionalProperties: true
//         ),
//         new OA\Property(
//             property: 'timestamp',
//             type: 'string',
//             format: 'date-time',
//             description: 'Timestamp of the response in ISO 8601 format'
//         )
//     ]
// )]
class GoogleSheetsAPIResourceSchema
{
    //
}