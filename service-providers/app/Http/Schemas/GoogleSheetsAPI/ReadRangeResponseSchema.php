<?php

namespace App\Http\Schemas\GoogleSheetsAPI;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ReadRangeResponse',
    title: 'Read Range Response',
    description: 'Response format for reading a range from Google Sheets',
    required: ['range', 'majorDimension', 'values'],
    properties: [
        new OA\Property(
            property: 'range',
            type: 'string',
            description: 'The A1 notation of the range that was read'
        ),
        new OA\Property(
            property: 'majorDimension',
            type: 'string',
            description: 'The major dimension of the values',
            enum: ['ROWS', 'COLUMNS']
        ),
        new OA\Property(
            property: 'values',
            type: 'array',
            description: 'The data that was read from the spreadsheet',
            items: new OA\Items(
                type: 'array',
                items: new OA\Items(type: 'string')
            )
        )
    ]
)]
class ReadRangeResponseSchema
{
    //
}