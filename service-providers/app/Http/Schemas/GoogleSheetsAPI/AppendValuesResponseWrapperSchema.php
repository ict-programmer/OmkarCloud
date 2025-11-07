<?php

namespace App\Http\Schemas\GoogleSheetsAPI;

// use OpenApi\Attributes as OA;

// #[OA\Schema(
//     schema: 'AppendValuesResponseWrapper',
//     title: 'Append Values Response Wrapper',
//     description: 'Response format for appending values to Google Sheets',
//     required: ['spreadsheetId', 'tableRange', 'updates'],
//     properties: [
//         new OA\Property(
//             property: 'spreadsheetId',
//             type: 'string',
//             description: 'The ID of the spreadsheet that was updated'
//         ),
//         new OA\Property(
//             property: 'tableRange',
//             type: 'string',
//             description: 'The range of the table that was updated in A1 notation'
//         ),
//         new OA\Property(
//             property: 'updates',
//             type: 'object',
//             description: 'Update information',
//             required: ['updatedRange', 'updatedRows', 'updatedColumns', 'updatedCells'],
//             properties: [
//                 new OA\Property(
//                     property: 'updatedRange',
//                     type: 'string',
//                     description: 'The range that was updated in A1 notation'
//                 ),
//                 new OA\Property(
//                     property: 'updatedRows',
//                     type: 'integer',
//                     description: 'The number of rows that were updated',
//                     minimum: 0
//                 ),
//                 new OA\Property(
//                     property: 'updatedColumns',
//                     type: 'integer',
//                     description: 'The number of columns that were updated',
//                     minimum: 0
//                 ),
//                 new OA\Property(
//                     property: 'updatedCells',
//                     type: 'integer',
//                     description: 'The number of cells that were updated',
//                     minimum: 0
//                 )
//             ]
//         )
//     ]
// )]
class AppendValuesResponseWrapperSchema
{
    //
}