<?php

namespace App\Http\Schemas\GoogleSheetsAPI;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ClearValuesResponseWrapper',
    title: 'Clear Values Response Wrapper',
    description: 'Response format for clearing values from Google Sheets',
    required: ['spreadsheetId', 'clearedRange'],
    properties: [
        new OA\Property(
            property: 'spreadsheetId',
            type: 'string',
            description: 'The ID of the spreadsheet that was cleared'
        ),
        new OA\Property(
            property: 'clearedRange',
            type: 'string',
            description: 'The range that was cleared in A1 notation'
        )
    ]
)]
class ClearValuesResponseWrapperSchema
{
    //
}