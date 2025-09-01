<?php

namespace App\Http\Controllers;

use App\Data\Request\GoogleSheetsAPI\CreateSpreadsheetData;
use App\Data\Request\GoogleSheetsAPI\BatchUpdateData;
use App\Data\Request\GoogleSheetsAPI\ClearRangeData;
use App\Data\Request\GoogleSheetsAPI\ReadRangeData;
use App\Data\Request\GoogleSheetsAPI\SheetsManagementData;
use App\Data\Request\GoogleSheetsAPI\WriteRangeData;
use App\Http\Requests\GoogleSheetAPI\BatchUpdateRequest;
use App\Http\Requests\GoogleSheetAPI\ClearRangeRequest;
use App\Http\Requests\GoogleSheetAPI\CreateSpreadsheetRequest;
use App\Http\Requests\GoogleSheetAPI\ReadRangeRequest;
use App\Http\Requests\GoogleSheetAPI\SheetsManagementRequest;
use App\Http\Requests\GoogleSheetAPI\WriteRangeRequest;
use App\Services\GoogleSheetsAPIService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="GoogleSheets API Documentation",
 *      description="API Endpoints for GoogleSheets Integration",
 *      @OA\Contact(
 *          email="support@example.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="API Server"
 * )
 */

/**
 * @OA\Schema(
 *      schema="CreateSpreadsheetRequest",
 *      title="Create Spreadsheet Request",
 *      required={"properties"},
 *      @OA\Property(
 *          property="properties",
 *          type="object",
 *          description="Properties of the spreadsheet",
 *          @OA\Property(property="title", type="string", example="My New Spreadsheet", description="Title of the spreadsheet")
 *      ),
 *      @OA\Property(
 *          property="sheets",
 *          type="array",
 *          description="Array of sheet objects",
 *          @OA\Items(
 *              type="object",
 *              @OA\Property(
 *                  property="properties",
 *                  type="object",
 *                  description="Properties of a single sheet",
 *                  @OA\Property(property="title", type="string", example="Sheet1", description="Title of the sheet"),
 *                  @OA\Property(
 *                      property="gridProperties",
 *                      type="object",
 *                      description="Grid properties of the sheet",
 *                      @OA\Property(property="rowCount", type="integer", example=1000, description="Number of rows"),
 *                      @OA\Property(property="columnCount", type="integer", example=26, description="Number of columns")
 *                  )
 *              )
 *          )
 *      )
 * )
 */
class GoogleSheetsAPIController extends Controller
{
    protected GoogleSheetsAPIService $googleSheetsAPIService;

    public function __construct(GoogleSheetsAPIService $googleSheetsAPIService)
    {
        $this->googleSheetsAPIService = $googleSheetsAPIService;
    }

    /**
     * @OA\Post(
     *      path="/api/sheets/create_spreadsheet",
     *      operationId="createGoogleSpreadsheet",
     *      tags={"GoogleSheetsAPI"},
     *      summary="Create a new Google Spreadsheet",
     *      description="Creates a new Google Spreadsheet with specified properties and sheets.",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CreateSpreadsheetRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="spreadsheetId", type="string", example="1_abc123def456ghi789jkl0mnO_pqr"),
     *              @OA\Property(property="spreadsheetUrl", type="string", example="https://docs.google.com/spreadsheets/d/1_abc123def456ghi789jkl0mnO_pqr/edit"),
     *              @OA\Property(property="properties", type="object",
     *                  @OA\Property(property="title", type="string", example="New Spreadsheet Title")
     *              ),
     *              @OA\Property(property="sheets", type="array", @OA\Items(
     *                  type="object",
     *                  @OA\Property(property="properties", type="object",
     *                      @OA\Property(property="sheetId", type="integer", example=0),
     *                      @OA\Property(property="title", type="string", example="Sheet1")
     *                  )
     *              ))
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object",
     *                  @OA\Property(property="properties.title", type="array", @OA\Items(type="string", example="The properties.title field is required."))
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Failed to create Google Spreadsheet due to API error or unexpected error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Failed to create Google Spreadsheet due to an external API error."),
     *              @OA\Property(property="code", type="integer", example=500)
     *          )
     *      )
     * )
     */
    public function create(CreateSpreadsheetRequest $request): JsonResponse
    {
        $validatedRequest = $request->validated();
        $spreadsheetData = CreateSpreadsheetData::from($validatedRequest);

        return $this->googleSheetsAPIService
            ->createSpreadsheet($spreadsheetData);
    }


    /**
     * @OA\Get(
     *      path="/api/sheets/read_range",
     *      operationId="readGoogleSheetRange",
     *      tags={"GoogleSheetsAPI"},
     *      summary="Read a range of values from a Google Spreadsheet",
     *      description="Reads a specified range of values from a Google Spreadsheet.",
     *      @OA\Parameter(
     *          name="spreadSheetId",
     *          in="query",
     *          required=true,
     *          description="The ID of the spreadsheet to retrieve data from.",
     *          @OA\Schema(type="string", example="1_abc123def456ghi789jkl0mnO_pqr")
     *      ),
     *      @OA\Parameter(
     *          name="range",
     *          in="query",
     *          required=true,
     *          description="The A1 notation of the range to retrieve values from.",
     *          @OA\Schema(type="string", example="Sheet1!A1:D5")
     *      ),
     *      @OA\Parameter(
     *          name="majorDimensions",
     *          in="query",
     *          required=false,
     *          description="The major dimension that results should use. Either ROWS or COLUMNS.",
     *          @OA\Schema(type="string", enum={"ROWS", "COLUMNS"}, default="ROWS")
     *      ),
     *      @OA\Parameter(
     *          name="valueRenderOption",
     *          in="query",
     *          required=false,
     *          description="How values should be represented in the output. The default render option is FORMATTED_VALUE.",
     *          @OA\Schema(type="string", enum={"FORMATTED_VALUE", "UNFORMATTED_VALUE", "FORMULA"}, default="FORMATTED_VALUE")
     *      ),
     *      @OA\Parameter(
     *          name="dateTimeRenderOption",
     *          in="query",
     *          required=false,
     *          description="How dates, times, and durations should be represented in the output. The default render option is SERIAL_NUMBER.",
     *          @OA\Schema(type="string", enum={"SERIAL_NUMBER", "FORMATTED_STRING"}, default="SERIAL_NUMBER")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="range", type="string", example="Sheet1!A1:D5"),
     *              @OA\Property(property="majorDimension", type="string", example="ROWS"),
     *              @OA\Property(property="values", type="array",
     *                  @OA\Items(type="array",
     *                      @OA\Items(type="string", example="Value1")
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object",
     *                  @OA\Property(property="spreadSheetId", type="array", @OA\Items(type="string", example="The spreadsheet ID is required."))
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Failed to read Google Spreadsheet range due to API error or unexpected error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Failed to read Google Spreadsheet range due to an external API error."),
     *              @OA\Property(property="code", type="integer", example=500)
     *          )
     *      )
     * )
     */
    public function readRange(ReadRangeRequest $request): JsonResponse
    {
        $validatedRequest = $request->validated();

        $readRangeData = ReadRangeData::from($validatedRequest);

        return $this->googleSheetsAPIService->readRange($readRangeData);
    }


    public function writeRange(WriteRangeRequest $request): JsonResponse
    {
        $validatedRequest = $request->validated();
        $writeRangeData = WriteRangeData::from($validatedRequest);

        return $this->googleSheetsAPIService->writeRange($writeRangeData);
    }

    /**
     * @OA\Post(
     *      path="/api/sheets/batch_update",
     *      operationId="batchUpdateGoogleSheetValues",
     *      tags={"GoogleSheetsAPI"},
     *      summary="Batch update values in a Google Spreadsheet",
     *      description="Updates multiple ranges of values in a Google Spreadsheet in a single request.",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              required={"spreadSheetId", "data", "valueInputOption"},
     *              @OA\Property(property="spreadSheetId", type="string", example="1pkgT-KFlwDUFlaBVTC6rvenuCoKL6VpAuDw05cQsPVk", description="The ID of the spreadsheet to update."),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  description="Array of ValueRange objects to update.",
     *                  @OA\Items(
     *                      type="object",
     *                      required={"range", "values"},
     *                      @OA\Property(property="range", type="string", example="Sheet1!A1:B2", description="The A1 notation of the range to update."),
     *                      @OA\Property(
     *                          property="values",
     *                          type="array",
     *                          description="The data to be written, a 2D array.",
     *                          @OA\Items(
     *                              type="array",
     *                              @OA\Items(type="string", example="A1 Value")
     *                          )
     *                      )
     *                  )
     *              ),
     *              @OA\Property(property="valueInputOption", type="string", example="RAW", enum={"RAW", "USER_ENTERED"}, description="How the input data should be interpreted.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="spreadsheetId", type="string", example="1pkgT-KFlwDUFlaBVTC6rvenuCoKL6VpAuDw05cQsPVk"),
     *              @OA\Property(property="totalUpdatedCells", type="integer", example=4),
     *              @OA\Property(property="responses", type="array", @OA\Items(
     *                  type="object",
     *                  @OA\Property(property="spreadsheetId", type="string", example="1pkgT-KFlwDUFlaBVTC6rvenuCoKL6VpAuDw05cQsPVk"),
     *                  @OA\Property(property="updatedRange", type="string", example="Sheet1!A1:B2"),
     *                  @OA\Property(property="updatedRows", type="integer", example=2),
     *                  @OA\Property(property="updatedColumns", type="integer", example=2),
     *                  @OA\Property(property="updatedCells", type="integer", example=4)
     *              ))
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object",
     *                  @OA\Property(property="spreadSheetId", type="array", @OA\Items(type="string", example="The spreadsheet ID is required."))
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Failed to batch update Google Spreadsheet values due to API error or unexpected error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Failed to batch update Google Spreadsheet values due to an external API error."),
     *              @OA\Property(property="code", type="integer", example=500)
     *          )
     *      )
     * )
     */
    public function batchUpdate(BatchUpdateRequest $request): JsonResponse
    {
        $validatedRequest = $request->validated();
        $batchUpdateData = BatchUpdateData::from($validatedRequest);

        return $this->googleSheetsAPIService->batchUpdate($batchUpdateData);
    }

    /**
     * @OA\Post(
     *      path="/api/sheets/clear_range",
     *      operationId="clearGoogleSheetRange",
     *      tags={"GoogleSheetsAPI"},
     *      summary="Clear a range of values from a Google Spreadsheet",
     *      description="Clears a specified range of values from a Google Spreadsheet.",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              required={"spreadSheetId", "range"},
     *              @OA\Property(property="spreadSheetId", type="string", example="1pkgT-KFlwDUFlaBVTC6rvenuCoKL6VpAuDw05cQsPVk", description="The ID of the spreadsheet to clear data from."),
     *              @OA\Property(property="range", type="string", example="Sheet1!A1:B10", description="The A1 notation of the range to clear.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="spreadsheetId", type="string", example="1pkgT-KFlwDUFlaBVTC6rvenuCoKL6VpAuDw05cQsPVk"),
     *              @OA\Property(property="clearedRange", type="string", example="Sheet1!A1:B10")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object",
     *                  @OA\Property(property="spreadSheetId", type="array", @OA\Items(type="string", example="The spreadsheet ID is required."))
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Failed to clear Google Spreadsheet range due to API error or unexpected error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Failed to clear Google Spreadsheet range due to an external API error."),
     *              @OA\Property(property="code", type="integer", example=500)
     *          )
     *      )
     * )
     */
    public function clearRange(ClearRangeRequest $request): JsonResponse
    {
        $validatedRequest = $request->validated();
        $clearRangeData = ClearRangeData::from($validatedRequest);

        return $this->googleSheetsAPIService->clearRange($clearRangeData);
    }

    /**
     * @OA\Post(
     *      path="/api/sheets/management",
     *      operationId="manageGoogleSheets",
     *      tags={"GoogleSheetsAPI"},
     *      summary="Perform various management operations on Google Sheets (add, delete, copy)",
     *      description="Allows adding a new sheet, deleting an existing sheet, or copying a sheet to another spreadsheet.",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              required={"spreadSheetId", "type"},
     *              @OA\Property(property="spreadSheetId", type="string", example="1pkgT-KFlwDUFlaBVTC6rvenuCoKL6VpAuDw05cQsPVk", description="The ID of the spreadsheet to manage."),
     *              @OA\Property(property="type", type="string", example="addSheet", enum={"addSheet", "deleteSheet", "copySheet"}, description="The type of operation to perform."),
     *              @OA\Property(property="title", type="string", example="New Sheet from Management", description="Required if type is 'addSheet'. The title of the new sheet."),
     *              @OA\Property(property="sheetId", type="integer", example=0, description="Required if type is 'deleteSheet' or 'copySheet'. The ID of the sheet to delete or copy."),
     *              @OA\Property(property="destinationSpreadsheetId", type="string", example="YOUR_DESTINATION_SPREADSHEET_ID", description="Required if type is 'copySheet'. The ID of the destination spreadsheet.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="Sheet operation completed successfully.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object",
     *                  @OA\Property(property="type", type="array", @OA\Items(type="string", example="The type field is required."))
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Failed to manage Google Sheet due to API error or unexpected error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="Failed to manage Google Sheet due to an external API error."),
     *              @OA\Property(property="code", type="integer", example=500)
     *          )
     *      )
     * )
     */
    public function sheetsManagement(SheetsManagementRequest $request): JsonResponse
    {
        $validatedRequest = $request->validated();
        $sheetsManagementData = SheetsManagementData::from($validatedRequest);

        return $this->googleSheetsAPIService->sheetsManagement($sheetsManagementData);
    }
}
