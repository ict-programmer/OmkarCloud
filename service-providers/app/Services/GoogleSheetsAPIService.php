<?php

namespace App\Services;

use App\Data\Request\GoogleSheetsAPI\BatchUpdateSheetData;
use App\Data\Request\GoogleSheetsAPI\CreateSpreadsheetData;
use App\Data\Request\GoogleSheetsAPI\BatchUpdateData;
use App\Data\Request\GoogleSheetsAPI\ClearRangeData;
use App\Data\Request\GoogleSheetsAPI\WriteRangeData;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Sheets;
use App\Data\Request\GoogleSheetsAPI\ReadRangeData;
use App\Data\Request\GoogleSheetsAPI\SheetsManagementData;
use Google\Service\Sheets\BatchUpdateSpreadsheetRequest;
use Google\Service\Sheets\BatchUpdateValuesRequest;
use Google\Service\Sheets\ClearValuesRequest;
use Google\Service\Sheets\CopySheetToAnotherSpreadsheetRequest;
use Google\Service\Sheets\Request;
use Google\Service\Sheets\Spreadsheet;
use Google\Service\Sheets\ValueRange;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class GoogleSheetsAPIService
{
    protected Client $client;
    protected Sheets $sheetsService;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApplicationName(config('app.name'));

        $this->client->setScopes([
            Sheets::SPREADSHEETS,
            Drive::DRIVE_FILE,
        ]);

        if (config('app.env') !== 'production') {
            $this->client->useApplicationDefaultCredentials();
        } else {
            $this->client->setAuthConfig(storage_path('app/credentials.json'));
        }

        $this->sheetsService = new Sheets($this->client);
    }

    /**
     * Create a new Google Spreadsheet.
     *
     * @param CreateSpreadsheetData $data
     * @return JsonResponse
     */
    public function createSpreadsheet(CreateSpreadsheetData $data): JsonResponse
    {
        $spreadsheetProperties = $data->properties ? new Sheets\SpreadsheetProperties($data->properties) : null;

        $sheets = collect($data->sheets ?? [])->map(function ($sheetData) {
            $gridPropertiesData = $sheetData['properties']['gridProperties'] ?? [];
            $gridProperties = new Sheets\GridProperties([
                'rowCount' => $gridPropertiesData['rowCount'] ?? null,
                'columnCount' => $gridPropertiesData['columnCount'] ?? null,
            ]);

            $sheetProperties = new Sheets\SheetProperties([
                'title' => $sheetData['properties']['title'] ?? null,
                'gridProperties' => $gridProperties,
            ]);

            return new Sheets\Sheet([
                'properties' => $sheetProperties,
            ]);
        })->all();

        $spreadsheet = new Spreadsheet([
            'properties' => $spreadsheetProperties,
            'sheets' => $sheets,
        ]);

        try {
            $response = $this->sheetsService->spreadsheets->create($spreadsheet);

            return response()->json($response, 201);
        } catch (\Google\Service\Exception $e) {

            Log::error('Google Sheets API Error: '.$e->getMessage(), ['code' => $e->getCode(), 'errors' => $e->getErrors()]);

            $httpStatusCode = $e->getCode();

            if (! is_int($httpStatusCode) || $httpStatusCode < 100 || $httpStatusCode > 599) {
                $httpStatusCode = 500;
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create Google Spreadsheet due to an external API error.',
                'code' => $httpStatusCode,
            ], $httpStatusCode);

        } catch (\Exception $e) {
            Log::error('Error creating Google Spreadsheet: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred while creating Google Spreadsheet.'
            ], 500);
        }
    }

    /**
     * Read a range of values from a Google Spreadsheet.
     *
     * @param ReadRangeData $data
     * @return JsonResponse
     */
    public function readRange(ReadRangeData $data): JsonResponse
    {
        try {
            $response = $this->sheetsService
                ->spreadsheets_values
                ->get($data->spreadSheetId, $data->range, [
                    'majorDimension' => $data->majorDimensions,
                    'valueRenderOption' => $data->valueRenderOption,
                    'dateTimeRenderOption' => $data->dateTimeRenderOption,
                ]);

            return response()->json($response, 200);

        } catch (\Google\Service\Exception $e) {

            Log::error('Google Sheets API Error: '.$e->getMessage(), ['code' => $e->getCode(), 'errors' => $e->getErrors()]);

            $httpStatusCode = $e->getCode();

            if (! is_int($httpStatusCode) || $httpStatusCode < 100 || $httpStatusCode > 599) {
                $httpStatusCode = 500;
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to read Google Spreadsheet range due to an external API error.',
                'code' => $httpStatusCode,
            ], $httpStatusCode);
        } catch (\Exception $e) {
            Log::error('Error reading Google Spreadsheet range: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred while reading Google Spreadsheet range.'
            ], 500);
        }
    }

    /**
     * Write a range of values to a Google Spreadsheet.
     *
     * @param WriteRangeData $data
     * @return JsonResponse
     */
    public function writeRange(WriteRangeData $data): JsonResponse
    {
        $valueRange = new Sheets\ValueRange([
            'values' => $data->values,
        ]);

        $options = [
            'valueInputOption' => $data->valueInputOption,
        ];

        try {
            $response = $this->sheetsService
                ->spreadsheets_values
                ->update($data->spreadSheetId, $data->range, $valueRange, $options);

            return response()->json($response, 200);

        } catch (\Google\Service\Exception $e) {

            Log::error('Google Sheets API Error: '.$e->getMessage(), ['code' => $e->getCode(), 'errors' => $e->getErrors()]);

            $httpStatusCode = $e->getCode();

            if (! is_int($httpStatusCode) || $httpStatusCode < 100 || $httpStatusCode > 599) {
                $httpStatusCode = 500;
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to write Google Spreadsheet range due to an external API error.',
                'code' => $httpStatusCode,
            ], $httpStatusCode);
        } catch (\Exception $e) {
            Log::error('Error writing Google Spreadsheet range: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred while writing Google Spreadsheet range.'
            ], 500);
        }
    }

    /**
     * Batch update values in a Google Spreadsheet.
     *
     * @param BatchUpdateData $data
     * @return JsonResponse
     */
    public function batchUpdate(BatchUpdateData $data): JsonResponse
    {
        $valueRanges = collect($data->data->all())->map(function (BatchUpdateSheetData $item) {
            return new ValueRange([
                'range' => $item->range,
                'values' => $item->values,
            ]);
        })->all();

        $body = new BatchUpdateValuesRequest([
            'data' => $valueRanges,
            'valueInputOption' => $data->valueInputOption,
        ]);

        try {
            $response = $this->sheetsService
                ->spreadsheets_values
                ->batchUpdate($data->spreadSheetId, $body);

            return response()->json($response, 200);

        } catch (\Google\Service\Exception $e) {
            Log::error('Google Sheets API Error: '.$e->getMessage(), ['code' => $e->getCode(), 'errors' => $e->getErrors()]);

            $httpStatusCode = $e->getCode();

            if (! is_int($httpStatusCode) || $httpStatusCode < 100 || $httpStatusCode > 599) {
                $httpStatusCode = 500;
            }

            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Failed to batch update Google Spreadsheet values due to an external API error.',
                    'code' => $httpStatusCode,
                ]
                , $httpStatusCode
            );
        } catch (\Exception $e) {
            Log::error('Error batch updating Google Spreadsheet values: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred while batch updating Google Spreadsheet values.'
            ], 500);
        }
    }

    /**
     * Clear a range of values from a Google Spreadsheet.
     *
     * @param ClearRangeData $data
     * @return JsonResponse
     */
    public function clearRange(ClearRangeData $data): JsonResponse
    {
        try {
            $body = new ClearValuesRequest();
            $response = $this->sheetsService
                ->spreadsheets_values
                ->clear($data->spreadSheetId, $data->range, $body);

            return response()->json($response, 200);

        } catch (\Google\Service\Exception $e) {

            Log::error('Google Sheets API Error: '.$e->getMessage(), ['code' => $e->getCode(), 'errors' => $e->getErrors()]);

            $httpStatusCode = $e->getCode();

            if (! is_int($httpStatusCode) || $httpStatusCode < 100 || $httpStatusCode > 599) {
                $httpStatusCode = 500;
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to clear Google Spreadsheet range due to an external API error.',
                'code' => $httpStatusCode,
            ], $httpStatusCode);
        } catch (\Exception $e) {
            Log::error('Error clearing Google Spreadsheet range: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred while clearing Google Spreadsheet range.'
            ], 500);
        }
    }

    /**
     * Perform various management operations on Google Sheets (add, delete, copy).
     *
     * @param SheetsManagementData $data
     * @return JsonResponse
     */
    public function sheetsManagement(SheetsManagementData $data): JsonResponse
    {
        try {
            switch ($data->type) {
                case 'addSheet':
                    $request = new Sheets\Request([
                        'addSheet' => [
                            'properties' => [
                                'title' => $data->title,
                            ],
                        ],
                    ]);
                    break;
                case 'deleteSheet':
                    $request = new Sheets\Request([
                        'deleteSheet' => [
                            'sheetId' => $data->sheetId,
                        ],
                    ]);
                    break;
                case 'copySheet':
                    $copySheetToAnotherSpreadsheetRequest = new Sheets\CopySheetToAnotherSpreadsheetRequest([
                        'destinationSpreadsheetId' => $data->destinationSpreadsheetId,
                    ]);

                    $response = $this->sheetsService
                        ->spreadsheets_sheets
                        ->copyTo($data->spreadSheetId, $data->sheetId, $copySheetToAnotherSpreadsheetRequest);

                    return response()->json($response, 200);
                default:
                    return response()->json(['status' => 'error', 'message' => 'Invalid sheet management type.'], 400);
            }

            $batchUpdateRequest = new Sheets\BatchUpdateSpreadsheetRequest([
                'requests' => [$request],
            ]);

            $response = $this->sheetsService
                ->spreadsheets
                ->batchUpdate($data->spreadSheetId, $batchUpdateRequest);

            return response()->json($response, 200);

        } catch (\Google\Service\Exception $e) {
            Log::error('Google Sheets API Error: '.$e->getMessage(), ['code' => $e->getCode(), 'errors' => $e->getErrors()]);

            $httpStatusCode = $e->getCode();

            if (! is_int($httpStatusCode) || $httpStatusCode < 100 || $httpStatusCode > 599) {
                $httpStatusCode = 500;
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to manage Google Sheet due to an external API error.',
                'code' => $httpStatusCode,
            ], $httpStatusCode);
        } catch (\Exception $e) {
            Log::error('Error managing Google Sheet: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred while managing Google Sheet.'
            ], 500);
        }
    }
}
