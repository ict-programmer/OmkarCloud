<?php

namespace App\Services;

use App\Data\Request\GoogleSheetsAPI\BatchUpdateSheetData;
use App\Data\Request\GoogleSheetsAPI\CreateSpreadsheetData;
use App\Data\Request\GoogleSheetsAPI\BatchUpdateData;
use App\Data\Request\GoogleSheetsAPI\ClearRangeData;
use App\Data\Request\GoogleSheetsAPI\WriteRangeData;
use App\Data\Request\GoogleSheetsAPI\AppendValuesData;
use App\Data\Request\GoogleSheetsAPI\ReadRangeData;
use App\Data\Request\GoogleSheetsAPI\SheetsManagementData;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Sheets;
use Google\Service\Sheets\BatchUpdateValuesRequest;
use Google\Service\Sheets\ClearValuesRequest;
use Google\Service\Sheets\Spreadsheet;
use App\Exceptions\ApiException;
use Google\Service\Sheets\ValueRange;
use Illuminate\Support\Facades\Log;

class GoogleSheetsAPIService
{
    protected Client $client;
    protected Sheets $sheetsService;

    public function __construct(array $serviceAccountCredentials)
    {
        $this->client = new Client();
        $this->client->setApplicationName(config('app.name'));

        $this->client->setScopes([
            Sheets::SPREADSHEETS,
            Drive::DRIVE_FILE,
        ]);

        $this->client->setAuthConfig($serviceAccountCredentials);

        $this->sheetsService = new Sheets($this->client);
    }

    /**
     * Handles Google Sheets API calls and common error logging/response.
     *
     * @param callable $callback The API call to execute.
     * @param string $errorMessagePrefix A prefix for error messages.
     * @return array|object
     * @throws \Exception
     */
    private function handleGoogleSheetsApiCall(callable $callback, string $errorMessagePrefix)
    {
        try {
            return $callback();
        } catch (\Google\Service\Exception $e) {
            Log::error('Google Sheets API Error: '.$e->getMessage(), ['code' => $e->getCode(), 'errors' => $e->getErrors()]);
            $httpStatusCode = $e->getCode();

            if (! is_int($httpStatusCode) || $httpStatusCode < 100 || $httpStatusCode > 599) {
                $httpStatusCode = 500;
            }

            throw new ApiException(
                message: "{$errorMessagePrefix} due to an external API error.",
                statusCode: $httpStatusCode,
                details: json_decode($e->getMessage())
            );
        } catch (\Exception $e) {
            Log::error($errorMessagePrefix.': '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);

            throw new ApiException(
                message: "An unexpected error occurred while {$errorMessagePrefix}.",
                statusCode: 500,
                details: $e->getMessage()
            );
        }
    }

    /**
     * Create a new Google Spreadsheet.
     *
     * @param CreateSpreadsheetData $data
     * @return object
     */
    public function createSpreadsheet(CreateSpreadsheetData $data): object
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

        return $this->handleGoogleSheetsApiCall(
            fn () => $this->sheetsService->spreadsheets->create($spreadsheet),
            'Failed to create Google Spreadsheet'
        );
    }

    /**
     * Read a range of values from a Google Spreadsheet.
     *
     * @param ReadRangeData $data
     * @return object
     */
    public function readRange(ReadRangeData $data): object
    {
        return $this->handleGoogleSheetsApiCall(
            fn () => $this->sheetsService
                ->spreadsheets_values
                ->get($data->spreadSheetId, $data->range, [
                    'majorDimension' => $data->majorDimensions,
                    'valueRenderOption' => $data->valueRenderOption,
                    'dateTimeRenderOption' => $data->dateTimeRenderOption,
                ]),
            'Failed to read Google Spreadsheet range'
        );
    }

    /**
     * Write a range of values to a Google Spreadsheet.
     *
     * @param WriteRangeData $data
     * @return object
     */
    public function writeRange(WriteRangeData $data): object
    {
        $valueRange = new Sheets\ValueRange([
            'values' => $data->values,
        ]);

        $options = [
            'valueInputOption' => $data->valueInputOption,
        ];

        return $this->handleGoogleSheetsApiCall(
            fn () => $this->sheetsService
                ->spreadsheets_values
                ->update($data->spreadSheetId, $data->range, $valueRange, $options),
            'Failed to write Google Spreadsheet range'
        );
    }

    /**
     * Append values to a Google Spreadsheet.
     *
     * @param AppendValuesData $data
     * @return object
     */
    public function appendValues(AppendValuesData $data): object
    {
        $valueRange = new Sheets\ValueRange([
            'values' => $data->values,
        ]);

        $options = [
            'valueInputOption' => $data->valueInputOption,
        ];

        return $this->handleGoogleSheetsApiCall(
            fn () => $this->sheetsService
                ->spreadsheets_values
                ->append($data->spreadSheetId, $data->range, $valueRange, $options),
            'Failed to append values to Google Spreadsheet'
        );
    }

    /**
     * Batch update values in a Google Spreadsheet.
     *
     * @param BatchUpdateData $data
     * @return object
     */
    public function batchUpdate(BatchUpdateData $data): object
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

        return $this->handleGoogleSheetsApiCall(
            fn () => $this->sheetsService
                ->spreadsheets_values
                ->batchUpdate($data->spreadSheetId, $body),
            'Failed to batch update Google Spreadsheet values'
        );
    }

    /**
     * Clear a range of values from a Google Spreadsheet.
     *
     * @param ClearRangeData $data
     * @return object
     */
    public function clearRange(ClearRangeData $data): object
    {
        $body = new ClearValuesRequest();
        return $this->handleGoogleSheetsApiCall(
            fn () => $this->sheetsService
                ->spreadsheets_values
                ->clear($data->spreadSheetId, $data->range, $body),
            'Failed to clear Google Spreadsheet range'
        );
    }

    /**
     * Perform various management operations on Google Sheets (add, delete, copy).
     *
     * @param SheetsManagementData $data
     * @return object
     */
    public function sheetsManagement(SheetsManagementData $data): object
    {
        return $this->handleGoogleSheetsApiCall(function () use ($data) {
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

                    return $this->sheetsService
                        ->spreadsheets_sheets
                        ->copyTo($data->spreadSheetId, $data->sheetId, $copySheetToAnotherSpreadsheetRequest);
                default:
                    throw new \Exception('Invalid sheet management type.', 400);
            }

            $batchUpdateRequest = new Sheets\BatchUpdateSpreadsheetRequest([
                'requests' => [$request],
            ]);

            return $this->sheetsService
                ->spreadsheets
                ->batchUpdate($data->spreadSheetId, $batchUpdateRequest);
        }, 'Failed to manage Google Sheet');
    }


}
