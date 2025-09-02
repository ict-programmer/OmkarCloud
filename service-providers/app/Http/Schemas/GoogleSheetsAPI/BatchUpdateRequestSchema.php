<?php

namespace App\Http\Schemas\GoogleSheetsAPI;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BatchUpdateRequest',
    title: 'Batch Update Request',
    description: 'Request format for batch updating values in Google Sheets',
    required: ['spreadSheetId', 'data', 'valueInputOption'],
    properties: [
        new OA\Property(
            property: 'spreadSheetId',
            type: 'string',
            description: 'The ID of the spreadsheet to update',
            maxLength: 255
        ),
        new OA\Property(
            property: 'data',
            type: 'array',
            description: 'Array of data objects to update',
            items: new OA\Items(
                type: 'object',
                required: ['range', 'values'],
                properties: [
                    new OA\Property(
                        property: 'range',
                        type: 'string',
                        description: 'The A1 notation of the range to update',
                        maxLength: 255
                    ),
                    new OA\Property(
                        property: 'values',
                        type: 'array',
                        description: 'The data to write, as a list of lists',
                        items: new OA\Items(
                            type: 'array',
                            items: new OA\Items(type: 'string')
                        )
                    )
                ]
            )
        ),
        new OA\Property(
            property: 'valueInputOption',
            type: 'string',
            description: 'How the input data should be interpreted',
            enum: ['RAW', 'USER_ENTERED']
        )
    ]
)]
class BatchUpdateRequestSchema
{
    //
}