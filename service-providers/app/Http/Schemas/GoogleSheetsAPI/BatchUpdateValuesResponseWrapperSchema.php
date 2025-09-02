<?php

namespace App\Http\Schemas\GoogleSheetsAPI;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BatchUpdateValuesResponseWrapper',
    title: 'Batch Update Values Response Wrapper',
    description: 'Response format for batch updating values in Google Sheets',
    required: ['spreadsheetId', 'totalUpdatedRows', 'totalUpdatedColumns', 'totalUpdatedCells', 'totalUpdatedSheets', 'responses'],
    properties: [
        new OA\Property(
            property: 'spreadsheetId',
            type: 'string',
            description: 'The ID of the spreadsheet that was updated'
        ),
        new OA\Property(
            property: 'totalUpdatedRows',
            type: 'integer',
            description: 'The total number of rows that were updated across all responses',
            minimum: 0
        ),
        new OA\Property(
            property: 'totalUpdatedColumns',
            type: 'integer',
            description: 'The total number of columns that were updated across all responses',
            minimum: 0
        ),
        new OA\Property(
            property: 'totalUpdatedCells',
            type: 'integer',
            description: 'The total number of cells that were updated across all responses',
            minimum: 0
        ),
        new OA\Property(
            property: 'totalUpdatedSheets',
            type: 'integer',
            description: 'The total number of sheets that were updated',
            minimum: 0
        ),
        new OA\Property(
            property: 'responses',
            type: 'array',
            description: 'Individual update responses',
            items: new OA\Items(
                type: 'object',
                required: ['updatedRange', 'updatedRows', 'updatedColumns', 'updatedCells'],
                properties: [
                    new OA\Property(
                        property: 'updatedRange',
                        type: 'string',
                        description: 'The range that was updated in A1 notation'
                    ),
                    new OA\Property(
                        property: 'updatedRows',
                        type: 'integer',
                        description: 'The number of rows that were updated',
                        minimum: 0
                    ),
                    new OA\Property(
                        property: 'updatedColumns',
                        type: 'integer',
                        description: 'The number of columns that were updated',
                        minimum: 0
                    ),
                    new OA\Property(
                        property: 'updatedCells',
                        type: 'integer',
                        description: 'The number of cells that were updated',
                        minimum: 0
                    )
                ]
            )
        )
    ]
)]
class BatchUpdateValuesResponseWrapperSchema
{
    //
}