<?php

namespace Database\Seeders;

use App\Enums\common\ServiceProviderEnum;
use App\Http\Controllers\GoogleSheetsAPIController;
use App\Http\Requests\GoogleSheetAPI\AppendValuesRequest;
use App\Http\Requests\GoogleSheetAPI\BatchUpdateRequest;
use App\Http\Requests\GoogleSheetAPI\ClearRangeRequest;
use App\Http\Requests\GoogleSheetAPI\CreateSpreadsheetRequest;
use App\Http\Requests\GoogleSheetAPI\ReadRangeRequest;
use App\Models\ServiceProvider;
use App\Models\ServiceType;
use App\Traits\ServiceProviderSeederTrait;
use Illuminate\Database\Seeder;

class GoogleSheetsAPIServiceProviderSeeder extends Seeder
{
    use ServiceProviderSeederTrait;

    /**
     * The database connection that should be used by the seeder.
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $googleSheetsProvider = $this->createServiceProvider();

        $this->createServiceTypes($googleSheetsProvider);

        $this->command->info('GoogleSheetsAPI Service Provider and related types seeded successfully.');
    }

    protected function createServiceProvider(): ServiceProvider
    {
        return ServiceProvider::updateOrCreate(
            ['type' => ServiceProviderEnum::GOOGLE_SHEETS_API->value],
            [
                'parameter' => [
                    'api_url' => 'https://sheets.googleapis.com/v4',
                    'api_key' => 'YOUR_OAUTH2_ACCESS_TOKEN',
                ],
                'is_active' => true,
                'controller_name' => GoogleSheetsAPIController::class,
            ]
        );
    }

    protected function createServiceTypes(ServiceProvider $serviceProvider): void
    {
        $serviceTypeData = $this->serviceTypeData();


        foreach ($serviceTypeData as $serviceType) {
            ServiceType::updateOrCreate([
                'service_provider_id' => $serviceProvider->id,
                'name' => $serviceType['name']
            ], [
                'name' => $serviceType['name'],
                'input_parameters' => $serviceType['input_parameters'],
                'response' => $serviceType['response'],
                'request_class_name' => $serviceType['request_class_name'],
                'function_name' => $serviceType['function_name'],
                'status' => $serviceType['status'],
            ]);
        }
    }

    protected function serviceTypeData() : array 
    {
        return  [
            [
                'name' => 'Create Spreadsheet',
                'input_parameters' => [
                    'properties' => [
                        'type' => 'array',
                        'required' => false,
                        'userinput_rqd' => true,
                        'description' => 'An array of properties for the spreadsheet. If provided, it should contain a "title" key.',
                        'example' => ['title' => 'My Spreadsheet'],
                        'validation' => 'nullable|array',
                    ],
                    'properties.title' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'The title of the spreadsheet. Required if "properties" is provided.',
                        'example' => 'My Spreadsheet Title',
                        'validation' => 'required_with:properties|string|max:255',
                    ],
                    'sheets' => [
                        'type' => 'array',
                        'required' => false,
                        'userinput_rqd' => true,
                        'description' => 'An array of sheets within the spreadsheet. Each sheet can have its own properties, including a title.',
                        'example' => [['properties' => ['title' => 'Sheet1']], ['properties' => ['title' => 'Sheet2']]],
                        'validation' => 'nullable|array',
                    ],
                    'sheets.*.properties.title' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'The title of a specific sheet. Required if "sheets" is provided.',
                        'example' => 'Sheet Title',
                        'validation' => 'required_with:sheets|string|max:255',
                    ],
                ],
                'response' => [
                    'spreadsheetId' => '1pkgT-KFlwDUFlaBVTC6rvenuCoKL6VpAuDw05cQsPVk',
                    'properties' => [
                        'title' => 'spreadSheetTitle',
                        'locale' => 'en_GB',
                        'autoRecalc' => 'ON_CHANGE',
                        'timeZone' => 'Etc/GMT',
                        'defaultFormat' => [
                            'backgroundColor' => [
                                'red' => 1,
                                'green' => 1,
                                'blue' => 1,
                            ],
                            'padding' => [
                                'top' => 2,
                                'right' => 3,
                                'bottom' => 2,
                                'left' => 3,
                            ],
                            'verticalAlignment' => 'BOTTOM',
                            'wrapStrategy' => 'OVERFLOW_CELL',
                            'textFormat' => [
                                'foregroundColor' => (object) [],
                                'fontFamily' => 'arial,sans,sans-serif',
                                'fontSize' => 10,
                                'bold' => false,
                                'italic' => false,
                                'strikethrough' => false,
                                'underline' => false,
                                'foregroundColorStyle' => [
                                    'rgbColor' => (object) [],
                                ],
                            ],
                            'backgroundColorStyle' => [
                                'rgbColor' => [
                                    'red' => 1,
                                    'green' => 1,
                                    'blue' => 1,
                                ],
                            ],
                        ],
                        'spreadsheetTheme' => [
                            'primaryFontFamily' => 'Arial',
                            'themeColors' => [
                                [
                                    'colorType' => 'TEXT',
                                    'color' => [
                                        'rgbColor' => (object) [],
                                    ],
                                ],
                                [
                                    'colorType' => 'BACKGROUND',
                                    'color' => [
                                        'rgbColor' => [
                                            'red' => 1,
                                            'green' => 1,
                                            'blue' => 1,
                                        ],
                                    ],
                                ],
                                [
                                    'colorType' => 'ACCENT1',
                                    'color' => [
                                        'rgbColor' => [
                                            'red' => 0.25882354,
                                            'green' => 0.52156866,
                                            'blue' => 0.95686275,
                                        ],
                                    ],
                                ],
                                [
                                    'colorType' => 'ACCENT2',
                                    'color' => [
                                        'rgbColor' => [
                                            'red' => 0.91764706,
                                            'green' => 0.2627451,
                                            'blue' => 0.20784314,
                                        ],
                                    ],
                                ],
                                [
                                    'colorType' => 'ACCENT3',
                                    'color' => [
                                        'rgbColor' => [
                                            'red' => 0.9843137,
                                            'green' => 0.7372549,
                                            'blue' => 0.015686275,
                                        ],
                                    ],
                                ],
                                [
                                    'colorType' => 'ACCENT4',
                                    'color' => [
                                        'rgbColor' => [
                                            'red' => 0.20392157,
                                            'green' => 0.65882355,
                                            'blue' => 0.3254902,
                                        ],
                                    ],
                                ],
                                [
                                    'colorType' => 'ACCENT5',
                                    'color' => [
                                        'rgbColor' => [
                                            'red' => 1,
                                            'green' => 0.42745098,
                                            'blue' => 0.003921569,
                                        ],
                                    ],
                                ],
                                [
                                    'colorType' => 'ACCENT6',
                                    'color' => [
                                        'rgbColor' => [
                                            'red' => 0.27450982,
                                            'green' => 0.7411765,
                                            'blue' => 0.7764706,
                                        ],
                                    ],
                                ],
                                [
                                    'colorType' => 'LINK',
                                    'color' => [
                                        'rgbColor' => [
                                            'red' => 0.06666667,
                                            'green' => 0.33333334,
                                            'blue' => 0.8,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'sheets' => [
                        [
                            'properties' => [
                                'sheetId' => 0,
                                'title' => 'Sheet1',
                                'index' => 0,
                                'sheetType' => 'GRID',
                                'gridProperties' => [
                                    'rowCount' => 1000,
                                    'columnCount' => 26,
                                ],
                            ],
                        ],
                    ],
                    'spreadsheetUrl' => 'https://docs.google.com/spreadsheets/d/1pkgT-KFlwDUFlaBVTC6rvenuCoKL6VpAuDw05cQsPVk/edit?ouid=10717529180057761',
                ],
                'response_path' => [
                    'spreadsheetId' => '$.spreadsheetId',
                    'properties.title' => '$.properties.title',
                    'properties.locale' => '$.properties.locale',
                    'properties.autoRecalc' => '$.properties.autoRecalc',
                    'properties.timeZone' => '$.properties.timeZone',
                    'properties.defaultFormat.backgroundColor.red' => '$.properties.defaultFormat.backgroundColor.red',
                    'properties.defaultFormat.backgroundColor.green' => '$.properties.defaultFormat.backgroundColor.green',
                    'properties.defaultFormat.backgroundColor.blue' => '$.properties.defaultFormat.backgroundColor.blue',
                    'properties.defaultFormat.padding.top' => '$.properties.defaultFormat.padding.top',
                    'properties.defaultFormat.padding.right' => '$.properties.defaultFormat.padding.right',
                    'properties.defaultFormat.padding.bottom' => '$.properties.defaultFormat.padding.bottom',
                    'properties.defaultFormat.padding.left' => '$.properties.defaultFormat.padding.left',
                    'properties.defaultFormat.verticalAlignment' => '$.properties.defaultFormat.verticalAlignment',
                    'properties.defaultFormat.wrapStrategy' => '$.properties.defaultFormat.wrapStrategy',
                    'properties.defaultFormat.textFormat.fontFamily' => '$.properties.defaultFormat.textFormat.fontFamily',
                    'properties.defaultFormat.textFormat.fontSize' => '$.properties.defaultFormat.textFormat.fontSize',
                    'properties.defaultFormat.textFormat.bold' => '$.properties.defaultFormat.textFormat.bold',
                    'properties.defaultFormat.textFormat.italic' => '$.properties.defaultFormat.textFormat.italic',
                    'properties.defaultFormat.textFormat.strikethrough' => '$.properties.defaultFormat.textFormat.strikethrough',
                    'properties.defaultFormat.textFormat.underline' => '$.properties.defaultFormat.textFormat.underline',
                    'properties.defaultFormat.backgroundColorStyle.rgbColor.red' => '$.properties.defaultFormat.backgroundColorStyle.rgbColor.red',
                    'properties.defaultFormat.backgroundColorStyle.rgbColor.green' => '$.properties.defaultFormat.backgroundColorStyle.rgbColor.green',
                    'properties.defaultFormat.backgroundColorStyle.rgbColor.blue' => '$.properties.defaultFormat.backgroundColorStyle.rgbColor.blue',
                    'properties.spreadsheetTheme.primaryFontFamily' => '$.properties.spreadsheetTheme.primaryFontFamily',
                    'properties.spreadsheetTheme.themeColors.*.colorType' => '$.properties.spreadsheetTheme.themeColors.*.colorType',
                    'properties.spreadsheetTheme.themeColors.*.color.rgbColor.red' => '$.properties.spreadsheetTheme.themeColors.*.color.rgbColor.red',
                    'properties.spreadsheetTheme.themeColors.*.color.rgbColor.green' => '$.properties.spreadsheetTheme.themeColors.*.color.rgbColor.green',
                    'properties.spreadsheetTheme.themeColors.*.color.rgbColor.blue' => '$.properties.spreadsheetTheme.themeColors.*.color.rgbColor.blue',
                    'sheets.*.properties.sheetId' => '$.sheets.*.properties.sheetId',
                    'sheets.*.properties.title' => '$.sheets.*.properties.title',
                    'sheets.*.properties.index' => '$.sheets.*.properties.index',
                    'sheets.*.properties.sheetType' => '$.sheets.*.properties.sheetType',
                    'sheets.*.properties.gridProperties.rowCount' => '$.sheets.*.properties.gridProperties.rowCount',
                    'sheets.*.properties.gridProperties.columnCount' => '$.sheets.*.properties.gridProperties.columnCount',
                    'spreadsheetUrl' => '$.spreadsheetUrl',
                ],
                'request_class_name' => CreateSpreadsheetRequest::class,
                'function_name' => 'createSpreadsheet',
                'status' => 1,
            ],
            [
                'name' => 'Read Ranges',
                'input_parameters' => [
                    'spreadsheetId' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'The ID of the spreadsheet to read from.',
                        'example' => '1Upj2w03_aB4C5D6E7F8G9H0I1J2K3L4M5N6O7P8Q',
                        'validation' => 'required|string',
                    ],
                    'range' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'The A1 notation of the range to retrieve values from (e.g., "Sheet1!A1:D5").',
                        'example' => 'Sheet1!A1:D5',
                        'validation' => 'required|string',
                    ],
                    'majorDimension' => [
                        'type' => 'string',
                        'required' => false,
                        'userinput_rqd' => true,
                        'description' => 'The dimension that data should be organized by. Valid values are "ROWS" or "COLUMNS". Defaults to "ROWS" if not specified.',
                        'example' => 'ROWS',
                        'validation' => 'nullable|string|in:ROWS,COLUMNS',
                    ],
                ],
                'response' => [
                    "range" => "Sheet1!A1:B10",
                    "majorDimension" => "ROWS",
                    "values" => [
                        ["Name", "Age"],
                        ["Alice", "30"],
                        ["Bob", "25"]
                    ]
                ],
                'response_path' => [
                    'range' => '$.range',
                    'majorDimension' => '$.majorDimension',
                    'values' => '$.values',
                ],
                'request_class_name' => ReadRangeRequest::class,
                'function_name' => 'readRange',
                'status' => 1,
            ],
            [
                'name' => 'Write Ranges',
                'input_parameters' => [
                    'spreadsheetId' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'The ID of the spreadsheet to write to.',
                        'example' => '1pkgT-KFlwDUFlaBVTC6rvenuCoKL6VpAuDw05cQsPVk',
                        'validation' => 'required|string',
                    ],
                    'range' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'The A1 notation of the range to write values to (e.g., "Sheet1!A1:D5").',
                        'example' => 'Sheet1!A1:D5',
                        'validation' => 'required|string',
                    ],
                    'values' => [
                        'type' => 'array',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'The data to be written to the spreadsheet. This is an array of arrays, where each inner array represents a row of values.',
                        'example' => [['Name', 'Age'], ['John Doe', 30], ['Jane Smith', 25]],
                        'validation' => 'required|array',
                    ],
                    'valueInputOption' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'How the input data should be interpreted. Valid values are "RAW" (values are not parsed) or "USER_ENTERED" (values are parsed as if entered into the UI).',
                        'example' => 'USER_ENTERED',
                        'validation' => 'required|string|in:RAW,USER_ENTERED',
                    ],
                ],
                'response' => [
                    'spreadsheetId' => '1pkgT-KFlwDUFlaBVTC6rvenuCoKL6VpAuDw05cOsPVk',
                    'updatedRange' => 'Sheet1!A1:B2',
                    'updatedRows' => 2,
                    'updatedColumns' => 2,
                    'updatedCells' => 4,
                ],
                'response_path' => [
                    'spreadsheetId' => '$.spreadsheetId',
                    'updatedRange' => '$.updatedRange',
                    'updatedRows' => '$.updatedRows',
                    'updatedColumns' => '$.updatedColumns',
                    'updatedCells' => '$.updatedCells',
                ],
                'request_class_name' => ReadRangeRequest::class,
                'function_name' => 'writeRange',
                'status' => 1,
            ],
            [
                'name' => 'Append Values',
                'input_parameters' => [
                    'spreadsheetId' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'The ID of the spreadsheet to append values to.',
                        'example' => '1pkgT-KFlwDUFlaBVTC6rvenuCoKL6VpAuDw05cQsPVk',
                        'validation' => 'required|string',
                    ],
                    'range' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'The A1 notation of the range to search for the data logic table. Values are appended after the last row of the table.',
                        'example' => 'Sheet1!A1:B10',
                        'validation' => 'required|string',
                    ],
                    'values' => [
                        'type' => 'array',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'The data to be appended to the spreadsheet. This is an array of arrays, where each inner array represents a row of values.',
                        'example' => [['New A', 'New B'], ['New C', 'New D']],
                        'validation' => 'required|array',
                    ],
                    'valueInputOption' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'How the input data should be interpreted. Valid values are "RAW" (values are not parsed) or "USER_ENTERED" (values are parsed as if entered into the UI).',
                        'example' => 'RAW',
                        'validation' => 'required|string|in:RAW,USER_ENTERED',
                    ],
                    'insertDataOption' => [
                        'type' => 'string',
                        'required' => false,
                        'userinput_rqd' => true,
                        'description' => 'How the input data should be inserted. Valid values are "OVERWRITE" (new data overwrites existing data) or "INSERT_ROWS" (rows are inserted for new data).',
                        'example' => 'INSERT_ROWS',
                        'validation' => 'nullable|string|in:OVERWRITE,INSERT_ROWS',
                    ],
                ],
                'response' => [
                    'spreadsheetId' => '1pkgT-KFlwDUFlaBVTC6rvenuCoKL6VpAuDw05cQsPVk',
                    'tableRange' => 'Sheet1!A1:B2',
                    'updates' => [
                        'spreadsheetId' => '1pkgT-KFlwDUFlaBVTC6rvenuCoKL6VpAuDw05cQsPVk',
                        'updatedRange' => 'Sheet1!A3:B4',
                        'updatedRows' => 2,
                        'updatedColumns' => 2,
                        'updatedCells' => 4,
                    ],
                ],
                'response_path' => [
                    'spreadsheetId' => '$.spreadsheetId',
                    'tableRange' => '$.tableRange',
                    'updates.spreadsheetId' => '$.updates.spreadsheetId',
                    'updates.updatedRange' => '$.updates.updatedRange',
                    'updates.updatedRows' => '$.updates.updatedRows',
                    'updates.updatedColumns' => '$.updates.updatedColumns',
                    'updates.updatedCells' => '$.updates.updatedCells',
                ],
                'request_class_name' => AppendValuesRequest::class,
                'function_name' => 'appendValues',
                'status' => 1,
            ],
            [
                'name' => 'Batch Update',
                'input_parameters' => [
                    'spreadsheetId' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'The ID of the spreadsheet to apply batch updates to.',
                        'example' => '1pkgT-KFlwDUFlaBVTC6rvenuCoKL6VpAuDw05cQsPVk',
                        'validation' => 'required|string',
                    ],
                    'requests' => [
                        'type' => 'array',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'A list of requests to apply to the spreadsheet. Each request object specifies an operation (e.g., addSheet, updateCells).',
                        'example' => [['addSheet' => ['properties' => ['title' => 'New Sheet']]]],
                        'validation' => 'required|array',
                    ],
                ],
                'response' => [
                    'spreadsheetId' => '1pkgT-KFlwDUFlaBVTC6rvenuCoKL6VpAuDw05cQsPVk',
                    'replies' => [
                        ['addSheet' => ['properties' => ['sheetId' => 123456789, 'title' => 'New Sheet', 'index' => 0]]],
                    ],
                ],
                'response_path' => [
                    'spreadsheetId' => '$.spreadsheetId',
                    'replies.*.addSheet.properties.sheetId' => '$.replies.*.addSheet.properties.sheetId',
                    'replies.*.addSheet.properties.title' => '$.replies.*.addSheet.properties.title',
                    'replies.*.addSheet.properties.index' => '$.replies.*.addSheet.properties.index',
                ],
                'request_class_name' => BatchUpdateRequest::class,
                'function_name' => 'batchUpdate',
                'status' => 1,
            ],
            [
                'name' => 'Clear Ranges',
                'input_parameters' => [
                    'spreadsheetId' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'The ID of the spreadsheet to clear values from.',
                        'example' => '1pkgT-KFlwDUFlaBVTC6rvenuCoKL6VpAuDw05cQsPVk',
                        'validation' => 'required|string',
                    ],
                    'range' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'The A1 notation of the range to clear values from (e.g., "Sheet1!A1:D5").',
                        'example' => 'Sheet1!A1:D5',
                        'validation' => 'required|string',
                    ],
                ],
                'response' => [
                    'spreadsheetId' => '1pkgT-KFlwDUFlaBVTC6rvenuCoKL6VpAuDw05cQsPVk',
                    'clearedRange' => 'Sheet1!A1:D5',
                ],
                'response_path' => [
                    'spreadsheetId' => '$.spreadsheetId',
                    'clearedRange' => '$.clearedRange',
                ],
                'request_class_name' => ClearRangeRequest::class,
                'function_name' => 'clearRange',
                'status' => 1,
            ],
            [
                'name' => 'Sheet Management',
                'input_parameters' => [
                    'spreadsheetId' => [
                        'type' => 'string',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'The ID of the spreadsheet to manage sheets in.',
                        'example' => '1pkgT-KFlwDUFlaBVTC6rvenuCoKL6VpAuDw05cQsPVk',
                        'validation' => 'required|string',
                    ],
                    'requests' => [
                        'type' => 'array',
                        'required' => true,
                        'userinput_rqd' => true,
                        'description' => 'A list of sheet management requests (e.g., addSheet, deleteSheet, duplicateSheet, updateSheetProperties). These requests are part of a batch update operation.',
                        'example' => [['addSheet' => ['properties' => ['title' => 'New Managed Sheet']]]],
                        'validation' => 'required|array',
                    ],
                ],
                'response' => [
                    'spreadsheetId' => '1pkgT-KFlwDUFlaBVTC6rvenuCoKL6VpAuDw05cQsPVk',
                    'replies' => [
                        ['addSheet' => ['properties' => ['sheetId' => 987654321, 'title' => 'New Managed Sheet', 'index' => 0]]],
                    ],
                ],
                'response_path' => [
                    'spreadsheetId' => '$.spreadsheetId',
                    'replies.*.addSheet.properties.sheetId' => '$.replies.*.addSheet.properties.sheetId',
                    'replies.*.addSheet.properties.title' => '$.replies.*.addSheet.properties.title',
                    'replies.*.addSheet.properties.index' => '$.replies.*.addSheet.properties.index',
                ],
                'request_class_name' => BatchUpdateRequest::class, 
                'function_name' => 'batchUpdate', 
                'status' => 1,
            ],
        ];
    }
}
