<?php

namespace App\Services;

use App\Data\Request\GoogleSheetsAPI\CreateSpreadsheetData;
use App\Data\Request\GoogleSheetsAPI\WriteRangeData;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Sheets;
use App\Data\Request\GoogleSheetsAPI\ReadRangeData;
use Google\Service\Sheets\Spreadsheet;
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
}
