<?php

namespace App\Http\Schemas\GoogleSheetsAPI;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CreateSpreadsheetRequest',
    title: 'Create Spreadsheet Request',
    required: ['properties'],
    properties: [
        new OA\Property(
            property: 'properties',
            type: 'object',
            description: 'Properties of the spreadsheet',
            required: ['title'],
            properties: [
                new OA\Property(property: 'title', type: 'string', description: 'The title of the spreadsheet', maxLength: 255),
                new OA\Property(property: 'locale', type: 'string', description: 'The locale of the spreadsheet', maxLength: 10, enum: ['en', 'es', 'fr', 'de', 'ja', 'ko', 'pt', 'ru', 'zh_CN', 'zh_TW', 'en_US', 'es_419', 'pt_BR', 'fil', 'id', 'it', 'pl', 'vi', 'tr', 'he', 'ar', 'fa', 'hi', 'bn', 'th']),
                new OA\Property(property: 'timeZone', type: 'string', description: 'The time zone of the spreadsheet', maxLength: 255)
            ]
        ),
        new OA\Property(
            property: 'sheets',
            type: 'array',
            description: 'Properties of the sheets within the spreadsheet',
            items: new OA\Items(
                type: 'object',
                properties: [
                    new OA\Property(
                        property: 'properties',
                        type: 'object',
                        required: ['title'],
                        properties: [
                            new OA\Property(property: 'title', type: 'string', description: 'The title of the sheet', maxLength: 255),
                            new OA\Property(property: 'index', type: 'integer', description: 'The index of the sheet', minimum: 0),
                            new OA\Property(property: 'sheetType', type: 'string', description: 'The type of the sheet', enum: ['GRID', 'OBJECT', 'DATA_SOURCE']),
                            new OA\Property(property: 'hidden', type: 'boolean', description: 'Whether the sheet is hidden'),
                            new OA\Property(property: 'rightToLeft', type: 'boolean', description: 'Whether the sheet is right to left'),
                            new OA\Property(
                                property: 'gridProperties',
                                type: 'object',
                                description: 'Properties of the grid within the sheet',
                                properties: [
                                    new OA\Property(property: 'rowCount', type: 'integer', description: 'The number of rows in the grid', minimum: 1),
                                    new OA\Property(property: 'columnCount', type: 'integer', description: 'The number of columns in the grid', minimum: 1),
                                    new OA\Property(property: 'frozenRowCount', type: 'integer', description: 'The number of frozen rows in the grid', minimum: 0),
                                    new OA\Property(property: 'frozenColumnCount', type: 'integer', description: 'The number of frozen columns in the grid', minimum: 0),
                                    new OA\Property(property: 'hideGridlines', type: 'boolean', description: 'Whether the gridlines are hidden'),
                                    new OA\Property(property: 'rowGroupControlAfter', type: 'boolean', description: 'Whether the row group control is after'),
                                    new OA\Property(property: 'columnGroupControlAfter', type: 'boolean', description: 'Whether the column group control is after')
                                ]
                            ),
                            new OA\Property(
                                property: 'tabColor',
                                type: 'object',
                                description: 'The color of the tab',
                                properties: [
                                    new OA\Property(property: 'red', type: 'number', format: 'float', minimum: 0, maximum: 1),
                                    new OA\Property(property: 'green', type: 'number', format: 'float', minimum: 0, maximum: 1),
                                    new OA\Property(property: 'blue', type: 'number', format: 'float', minimum: 0, maximum: 1),
                                    new OA\Property(property: 'alpha', type: 'number', format: 'float', minimum: 0, maximum: 1)
                                ]
                            ),
                            new OA\Property(
                                property: 'tabColorStyle',
                                type: 'object',
                                description: 'The color style of the tab',
                                properties: [
                                    new OA\Property(
                                        property: 'rgbColor',
                                        type: 'object',
                                        properties: [
                                            new OA\Property(property: 'red', type: 'number', format: 'float', minimum: 0, maximum: 1),
                                            new OA\Property(property: 'green', type: 'number', format: 'float', minimum: 0, maximum: 1),
                                            new OA\Property(property: 'blue', type: 'number', format: 'float', minimum: 0, maximum: 1),
                                            new OA\Property(property: 'alpha', type: 'number', format: 'float', minimum: 0, maximum: 1)
                                        ]
                                    ),
                                    new OA\Property(property: 'themeColor', type: 'string', enum: ['THEME_COLOR_TYPE_UNSPECIFIED', 'TEXT', 'BACKGROUND', 'ACCENT1', 'ACCENT2', 'ACCENT3', 'ACCENT4', 'ACCENT5', 'ACCENT6', 'LINK', 'FOLLOWED_LINK'])
                                ]
                            ),
                            new OA\Property(
                                property: 'dataSourceSheetProperties',
                                type: 'object',
                                description: 'Properties for a data source sheet',
                                required: ['dataSourceId'],
                                properties: [
                                    new OA\Property(property: 'dataSourceId', type: 'string', description: 'The ID of the data source', maxLength: 255),
                                    new OA\Property(
                                        property: 'columns',
                                        type: 'array',
                                        items: new OA\Items(
                                            type: 'object',
                                            required: ['reference'],
                                            properties: [
                                                new OA\Property(
                                                    property: 'reference',
                                                    type: 'object',
                                                    required: ['name'],
                                                    properties: [
                                                        new OA\Property(property: 'name', type: 'string', description: 'The name of the column reference', maxLength: 255)
                                                    ]
                                                ),
                                                new OA\Property(property: 'formula', type: 'string', description: 'The formula for the column')
                                            ]
                                        )
                                    )
                                ]
                            )
                        ]
                    )
                ]
            )
        )
    ]
)]
class CreateSpreadsheetRequestSchema
{
    //
}