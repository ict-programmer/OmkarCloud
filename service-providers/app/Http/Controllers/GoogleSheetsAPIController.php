<?php

namespace App\Http\Controllers;

use App\Data\Request\GoogleSheetsAPI\CreateSpreadsheetData;
use App\Data\Request\GoogleSheetsAPI\ReadRangeData;
use App\Http\Requests\GoogleSheetAPI\CreateSpreadsheetRequest;
use App\Http\Requests\GoogleSheetAPI\ReadRangeRequest;
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


    public function readRange(ReadRangeRequest $request): JsonResponse
    {
        $validatedRequest = $request->validated();

        $readRangeData = ReadRangeData::from($validatedRequest);

        return $this->googleSheetsAPIService->readRange($readRangeData);
    }
}
