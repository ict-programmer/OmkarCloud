<?php

namespace App\Http\Schemas\GoogleSheetsAPI;

// use OpenApi\Attributes as OA;

// #[OA\Schema(
//     schema: 'SheetsManagementRequest',
//     title: 'Sheets Management Request',
//     description: 'Request format for managing sheets in Google Sheets (add, delete, copy)',
//     required: ['spreadSheetId', 'type'],
//     properties: [
//         new OA\Property(
//             property: 'spreadSheetId',
//             type: 'string',
//             description: 'The ID of the spreadsheet to manage',
//             maxLength: 255
//         ),
//         new OA\Property(
//             property: 'type',
//             type: 'string',
//             description: 'The type of operation to perform',
//             enum: ['addSheet', 'deleteSheet', 'copySheet']
//         ),
//         new OA\Property(
//             property: 'title',
//             type: 'string',
//             description: 'The title of the new sheet (required for addSheet operation)',
//             maxLength: 255,
//             nullable: true
//         ),
//         new OA\Property(
//             property: 'sheetId',
//             type: 'integer',
//             description: 'The ID of the sheet to delete or copy (required for deleteSheet and copySheet operations)',
//             minimum: 0,
//             nullable: true
//         ),
//         new OA\Property(
//             property: 'destinationSpreadsheetId',
//             type: 'string',
//             description: 'The ID of the destination spreadsheet (required for copySheet operation)',
//             maxLength: 255,
//             nullable: true
//         )
//     ]
// )]
class SheetsManagementRequestSchema
{
    //
}