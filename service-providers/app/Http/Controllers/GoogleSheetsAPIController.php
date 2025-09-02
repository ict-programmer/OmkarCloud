<?php

namespace App\Http\Controllers;

use App\Data\Request\GoogleSheetsAPI\CreateSpreadsheetData;
use App\Data\Request\GoogleSheetsAPI\BatchUpdateData;
use App\Data\Request\GoogleSheetsAPI\ClearRangeData;
use App\Data\Request\GoogleSheetsAPI\AppendValuesData;
use App\Data\Request\GoogleSheetsAPI\ReadRangeData;
use App\Data\Request\GoogleSheetsAPI\SheetsManagementData;
use App\Data\Request\GoogleSheetsAPI\WriteRangeData;
use App\Http\Requests\GoogleSheetAPI\AppendValuesRequest;
use App\Http\Requests\GoogleSheetAPI\BatchUpdateRequest;
use App\Http\Requests\GoogleSheetAPI\ClearRangeRequest;
use App\Http\Requests\GoogleSheetAPI\CreateSpreadsheetRequest;
use App\Http\Requests\GoogleSheetAPI\ReadRangeRequest;
use App\Http\Requests\GoogleSheetAPI\SheetsManagementRequest;
use App\Http\Requests\GoogleSheetAPI\WriteRangeRequest;
use App\Http\Resources\GoogleSheetsAPI\GoogleSheetsAPIResource;
use App\Services\GoogleSheetsAPIService;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CreateSpreadsheetRequest",
 *     title="Create Spreadsheet Request",
 *     required={"properties"},
 *     @OA\Property(
 *         property="properties",
 *         type="object",
 *         description="Properties of the spreadsheet",
 *         required={"title"},
 *         @OA\Property(
 *             property="title",
 *             type="string",
 *             description="The title of the spreadsheet",
 *             maxLength=255
 *         ),
 *         @OA\Property(
 *             property="locale",
 *             type="string",
 *             description="The locale of the spreadsheet",
 *             maxLength=10,
 *             enum={"en", "es", "fr", "de", "ja", "ko", "pt", "ru", "zh_CN", "zh_TW", "en_US", "es_419", "pt_BR", "fil", "id", "it", "pl", "vi", "tr", "he", "ar", "fa", "hi", "bn", "th"}
 *         ),
 *         @OA\Property(
 *             property="timeZone",
 *             type="string",
 *             description="The time zone of the spreadsheet",
 *             maxLength=255
 *         )
 *     ),
 *     @OA\Property(
 *         property="sheets",
 *         type="array",
 *         description="Properties of the sheets within the spreadsheet",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(
 *                 property="properties",
 *                 type="object",
 *                 required={"title"},
 *                 @OA\Property(
 *                     property="title",
 *                     type="string",
 *                     description="The title of the sheet",
 *                     maxLength=255
 *                 ),
 *                 @OA\Property(
 *                     property="index",
 *                     type="integer",
 *                     description="The index of the sheet",
 *                     minimum=0
 *                 ),
 *                 @OA\Property(
 *                     property="sheetType",
 *                     type="string",
 *                     description="The type of the sheet",
 *                     enum={"GRID", "OBJECT", "DATA_SOURCE"}
 *                 ),
 *                 @OA\Property(
 *                     property="hidden",
 *                     type="boolean",
 *                     description="Whether the sheet is hidden"
 *                 ),
 *                 @OA\Property(
 *                     property="rightToLeft",
 *                     type="boolean",
 *                     description="Whether the sheet is right to left"
 *                 ),
 *                 @OA\Property(
 *                     property="gridProperties",
 *                     type="object",
 *                     description="Properties of the grid within the sheet",
 *                     @OA\Property(
 *                         property="rowCount",
 *                         type="integer",
 *                         description="The number of rows in the grid",
 *                         minimum=1
 *                     ),
 *                     @OA\Property(
 *                         property="columnCount",
 *                         type="integer",
 *                         description="The number of columns in the grid",
 *                         minimum=1
 *                     ),
 *                     @OA\Property(
 *                         property="frozenRowCount",
 *                         type="integer",
 *                         description="The number of frozen rows in the grid",
 *                         minimum=0
 *                     ),
 *                     @OA\Property(
 *                         property="frozenColumnCount",
 *                         type="integer",
 *                         description="The number of frozen columns in the grid",
 *                         minimum=0
 *                     ),
 *                     @OA\Property(
 *                         property="hideGridlines",
 *                         type="boolean",
 *                         description="Whether the gridlines are hidden"
 *                     ),
 *                     @OA\Property(
 *                         property="rowGroupControlAfter",
 *                         type="boolean",
 *                         description="Whether the row group control is after"
 *                     ),
 *                     @OA\Property(
 *                         property="columnGroupControlAfter",
 *                         type="boolean",
 *                         description="Whether the column group control is after"
 *                     )
 *                 ),
 *                 @OA\Property(
 *                     property="tabColor",
 *                     type="object",
 *                     description="The color of the tab",
 *                     @OA\Property(property="red", type="number", format="float", minimum=0, maximum=1),
 *                     @OA\Property(property="green", type="number", format="float", minimum=0, maximum=1),
 *                     @OA\Property(property="blue", type="number", format="float", minimum=0, maximum=1),
 *                     @OA\Property(property="alpha", type="number", format="float", minimum=0, maximum=1)
 *                 ),
 *                 @OA\Property(
 *                     property="tabColorStyle",
 *                     type="object",
 *                     description="The color style of the tab",
 *                     @OA\Property(
 *                         property="rgbColor",
 *                         type="object",
 *                         @OA\Property(property="red", type="number", format="float", minimum=0, maximum=1),
 *                         @OA\Property(property="green", type="number", format="float", minimum=0, maximum=1),
 *                         @OA\Property(property="blue", type="number", format="float", minimum=0, maximum=1),
 *                         @OA\Property(property="alpha", type="number", format="float", minimum=0, maximum=1)
 *                     ),
 *                     @OA\Property(
 *                         property="themeColor",
 *                         type="string",
 *                         enum={"THEME_COLOR_TYPE_UNSPECIFIED", "TEXT", "BACKGROUND", "ACCENT1", "ACCENT2", "ACCENT3", "ACCENT4", "ACCENT5", "ACCENT6", "LINK", "FOLLOWED_LINK"}
 *                     )
 *                 ),
 *                 @OA\Property(
 *                     property="dataSourceSheetProperties",
 *                     type="object",
 *                     description="Properties for a data source sheet",
 *                     required={"dataSourceId"},
 *                     @OA\Property(
 *                         property="dataSourceId",
 *                         type="string",
 *                         description="The ID of the data source",
 *                         maxLength=255
 *                     ),
 *                     @OA\Property(
 *                         property="columns",
 *                         type="array",
 *                         @OA\Items(
 *                             type="object",
 *                             required={"reference"},
 *                             @OA\Property(
 *                                 property="reference",
 *                                 type="object",
 *                                 required={"name"},
 *                                 @OA\Property(
 *                                     property="name",
 *                                     type="string",
 *                                     description="The name of the column reference",
 *                                     maxLength=255
 *                                 )
 *                             ),
 *                             @OA\Property(
 *                                 property="formula",
 *                                 type="string",
 *                                 description="The formula for the column"
 *                             )
 *                         )
 *                     )
 *                 )
 *             )
 *         )
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="GoogleSheetsAPIResource",
 *     title="Google Sheets API Resource",
 *     description="Represents a Google Spreadsheet API response.",
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         example="success",
 *         description="The status of the API response."
 *     ),
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         description="The Google Spreadsheet resource data.",
 *         ref="#/components/schemas/GoogleSheetsAPIResourceData"
 *     ),
 *     @OA\Property(
 *         property="timestamp",
 *         type="string",
 *         format="date-time",
 *         example="2023-10-27T10:00:00Z",
 *         description="The timestamp of the API response."
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="GoogleSheetsAPIResourceData",
 *     title="Google Sheets API Resource Data",
 *     description="Represents the actual Google Spreadsheet resource.",
 *     @OA\Property(
 *         property="spreadsheetId",
 *         type="string",
 *         description="The ID of the spreadsheet."
 *     ),
 *     @OA\Property(
 *         property="spreadsheetUrl",
 *         type="string",
 *         description="The URL of the spreadsheet."
 *     ),
 *     @OA\Property(
 *         property="properties",
 *         type="object",
 *         description="Properties of the spreadsheet.",
 *         @OA\Property(
 *             property="title",
 *             type="string",
 *             description="The title of the spreadsheet."
 *         ),
 *         @OA\Property(
 *             property="locale",
 *             type="string",
 *             description="The locale of the spreadsheet."
 *         ),
 *         @OA\Property(
 *             property="timeZone",
 *             type="string",
 *             description="The time zone of the spreadsheet."
 *         )
 *     ),
 *     @OA\Property(
 *         property="sheets",
 *         type="array",
 *         description="The sheets in the spreadsheet.",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(
 *                 property="properties",
 *                 type="object",
 *                 @OA\Property(
 *                     property="sheetId",
 *                     type="integer",
 *                     description="The ID of the sheet."
 *                 ),
 *                 @OA\Property(
 *                     property="title",
 *                     type="string",
 *                     description="The title of the sheet."
 *                 ),
 *                 @OA\Property(
 *                     property="index",
 *                     type="integer",
 *                     description="The index of the sheet."
 *                 )
 *             )
 *         )
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="ValidationError",
 *     title="Validation Error",
 *     @OA\Property(property="status", type="string", example="error"),
 *     @OA\Property(property="message", type="string", example="Validation failed"),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         additionalProperties={"type": "array", "items": {"type": "string"}}
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     title="Error Response",
 *     @OA\Property(property="message", type="string", example="An unexpected error occurred."),
 *     @OA\Property(property="statusCode", type="integer", example=500),
 *     @OA\Property(property="details", type="string", example="Error details here.")
 * )
 *
 * @OA\Schema(
 *     schema="ClearRangeRequest",
 *     title="Clear Range Request",
 *     required={"spreadSheetId", "range"},
 *     @OA\Property(
 *         property="spreadSheetId",
 *         type="string",
 *         description="The ID of the spreadsheet to clear a range from.",
 *         maxLength=255
 *     ),
 *     @OA\Property(
 *         property="range",
 *         type="string",
 *         description="The A1 notation of the range to clear values from.",
 *         maxLength=255
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="ClearValuesResponse",
 *     title="Clear Values Response",
 *     description="Response for clearing a range of values in a spreadsheet.",
 *     @OA\Property(
 *         property="spreadsheetId",
 *         type="string",
 *         description="The ID of the spreadsheet."
 *     ),
 *     @OA\Property(
 *         property="clearedRange",
 *         type="string",
 *         description="The range that was cleared, in A1 notation."
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="ReadRangeResponse",
 *     title="Read Range Response",
 *     description="Response for reading a range of values from a spreadsheet.",
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         example="success",
 *         description="The status of the API response."
 *     ),
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         description="The value range data.",
 *         ref="#/components/schemas/ValueRange"
 *     ),
 *     @OA\Property(
 *         property="timestamp",
 *         type="string",
 *         format="date-time",
 *         example="2023-10-27T10:00:00Z",
 *         description="The timestamp of the API response."
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="WriteRangeResponse",
 *     title="Write Range Response",
 *     description="Response for writing a range of values to a spreadsheet.",
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         example="success",
 *         description="The status of the API response."
 *     ),
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         description="The update values response data.",
 *         ref="#/components/schemas/UpdateValuesResponse"
 *     ),
 *     @OA\Property(
 *         property="timestamp",
 *         type="string",
 *         format="date-time",
 *         example="2023-10-27T10:00:00Z",
 *         description="The timestamp of the API response."
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="AppendValuesResponseWrapper",
 *     title="Append Values Response Wrapper",
 *     description="Response for appending values to a spreadsheet.",
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         example="success",
 *         description="The status of the API response."
 *     ),
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         description="The append values response data.",
 *         ref="#/components/schemas/AppendValuesResponse"
 *     ),
 *     @OA\Property(
 *         property="timestamp",
 *         type="string",
 *         format="date-time",
 *         example="2023-10-27T10:00:00Z",
 *         description="The timestamp of the API response."
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="BatchUpdateValuesResponseWrapper",
 *     title="Batch Update Values Response Wrapper",
 *     description="Response for batch updating values in a spreadsheet.",
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         example="success",
 *         description="The status of the API response."
 *     ),
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         description="The batch update values response data.",
 *         ref="#/components/schemas/BatchUpdateValuesResponse"
 *     ),
 *     @OA\Property(
 *         property="timestamp",
 *         type="string",
 *         format="date-time",
 *         example="2023-10-27T10:00:00Z",
 *         description="The timestamp of the API response."
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="ClearValuesResponseWrapper",
 *     title="Clear Values Response Wrapper",
 *     description="Response for clearing a range of values in a spreadsheet.",
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         example="success",
 *         description="The status of the API response."
 *     ),
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         description="The clear values response data.",
 *         ref="#/components/schemas/ClearValuesResponse"
 *     ),
 *     @OA\Property(
 *         property="timestamp",
 *         type="string",
 *         format="date-time",
 *         example="2023-10-27T10:00:00Z",
 *         description="The timestamp of the API response."
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="ValueRange",
 *     title="Value Range",
 *     description="Represents a range of values in a spreadsheet.",
 *     @OA\Property(
 *         property="range",
 *         type="string",
 *         description="The range the values cover, in A1 notation."
 *     ),
 *     @OA\Property(
 *         property="majorDimension",
 *         type="string",
 *         description="The major dimension of the values.",
 *         enum={"ROWS", "COLUMNS"}
 *     ),
 *     @OA\Property(
 *         property="values",
 *         type="array",
 *         description="The data in the range, as a list of lists.",
 *         @OA\Items(
 *             type="array",
 *             @OA\Items(type="string")
 *         )
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="UpdateValuesResponse",
 *     title="Update Values Response",
 *     description="Response for updating a range of values in a spreadsheet.",
 *     @OA\Property(
 *         property="spreadsheetId",
 *         type="string",
 *         description="The ID of the spreadsheet."
 *     ),
 *     @OA\Property(
 *         property="updatedRange",
 *         type="string",
 *         description="The range that the values cover, in A1 notation."
 *     ),
 *     @OA\Property(
 *         property="updatedRows",
 *         type="integer",
 *         description="The number of rows that were updated."
 *     ),
 *     @OA\Property(
 *         property="updatedColumns",
 *         type="integer",
 *         description="The number of columns that were updated."
 *     ),
 *     @OA\Property(
 *         property="updatedCells",
 *         type="integer",
 *         description="The number of cells that were updated."
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="AppendValuesResponse",
 *     title="Append Values Response",
 *     description="Response for appending values to a spreadsheet.",
 *     @OA\Property(
 *         property="spreadsheetId",
 *         type="string",
 *         description="The ID of the spreadsheet."
 *     ),
 *     @OA\Property(
 *         property="tableRange",
 *         type="string",
 *         description="The table range where the values were appended, in A1 notation."
 *     ),
 *     @OA\Property(
 *         property="updates",
 *         type="object",
 *         description="Information about the updates that were applied.",
 *         @OA\Property(
 *             property="spreadsheetId",
 *             type="string",
 *             description="The ID of the spreadsheet."
 *         ),
 *         @OA\Property(
 *             property="updatedRange",
 *             type="string",
 *             description="The range that the values cover, in A1 notation."
 *         ),
 *         @OA\Property(
 *             property="updatedRows",
 *             type="integer",
 *             description="The number of rows that were updated."
 *         ),
 *         @OA\Property(
 *             property="updatedColumns",
 *             type="integer",
 *             description="The number of columns that were updated."
 *         ),
 *         @OA\Property(
 *             property="updatedCells",
 *             type="integer",
 *             description="The number of cells that were updated."
 *         )
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="BatchUpdateRequest",
 *     title="Batch Update Request",
 *     required={"spreadSheetId", "data", "valueInputOption"},
 *     @OA\Property(
 *         property="spreadSheetId",
 *         type="string",
 *         description="The ID of the spreadsheet to update.",
 *         maxLength=255
 *     ),
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         description="The data to update, as a list of ValueRange objects.",
 *         @OA\Items(
 *             type="object",
 *             required={"range", "values"},
 *             @OA\Property(
 *                 property="range",
 *                 type="string",
 *                 description="The A1 notation of the range to update values in.",
 *                 maxLength=255
 *             ),
 *             @OA\Property(
 *                 property="values",
 *                 type="array",
 *                 description="The data to write, as a list of lists.",
 *                 @OA\Items(
 *                     type="array",
 *                     @OA\Items(type="string")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Property(
 *         property="valueInputOption",
 *         type="string",
 *         description="How the input data should be interpreted.",
 *         enum={"RAW", "USER_ENTERED"}
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="BatchUpdateValuesResponse",
 *     title="Batch Update Values Response",
 *     description="Response for batch updating values in a spreadsheet.",
 *     @OA\Property(
 *         property="spreadsheetId",
 *         type="string",
 *         description="The ID of the spreadsheet."
 *     ),
 *     @OA\Property(
 *         property="totalUpdatedRows",
 *         type="integer",
 *         description="The total number of rows updated."
 *     ),
 *     @OA\Property(
 *         property="totalUpdatedColumns",
 *         type="integer",
 *         description="The total number of columns updated."
 *     ),
 *     @OA\Property(
 *         property="totalUpdatedCells",
 *         type="integer",
 *         description="The total number of cells updated."
 *     ),
 *     @OA\Property(
 *         property="responses",
 *         type="array",
 *         description="Information about the updates that were applied to each range.",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(
 *                 property="spreadsheetId",
 *                 type="string",
 *                 description="The ID of the spreadsheet."
 *             ),
 *             @OA\Property(
 *                 property="updatedRange",
 *                 type="string",
 *                 description="The range that the values cover, in A1 notation."
 *             ),
 *             @OA\Property(
 *                 property="updatedRows",
 *                 type="integer",
 *                 description="The number of rows that were updated."
 *             ),
 *             @OA\Property(
 *                 property="updatedColumns",
 *                 type="integer",
 *                 description="The number of columns that were updated."
 *             ),
 *             @OA\Property(
 *                 property="updatedCells",
 *                 type="integer",
 *                 description="The number of cells that were updated."
 *             )
 *         )
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="SheetsManagementRequest",
 *     title="Sheets Management Request",
 *     required={"spreadSheetId", "type"},
 *     @OA\Property(
 *         property="spreadSheetId",
 *         type="string",
 *         description="The ID of the spreadsheet to perform operations on.",
 *         maxLength=255
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="The type of sheet management operation.",
 *         enum={"addSheet", "deleteSheet", "copySheet"}
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="The title of the new sheet (required for 'addSheet' type).",
 *         maxLength=255,
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="sheetId",
 *         type="integer",
 *         description="The ID of the sheet to delete or copy (required for 'deleteSheet' or 'copySheet' types).",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="destinationSpreadsheetId",
 *         type="string",
 *         description="The ID of the destination spreadsheet for copying a sheet (required for 'copySheet' type).",
 *         maxLength=255,
 *         nullable=true
 *     )
 * )
 *
 */
class GoogleSheetsAPIController extends BaseController
{
    protected GoogleSheetsAPIService $googleSheetsAPIService;

    public function __construct(GoogleSheetsAPIService $googleSheetsAPIService)
    {
        $this->googleSheetsAPIService = $googleSheetsAPIService;
    }

    /**
     * @OA\Post(
     *     path="/api/sheets/create_spreadsheet",
     *     summary="Create a new Google Spreadsheet",
     *     tags={"Google Sheets API"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateSpreadsheetRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/GoogleSheetsAPIResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function create(CreateSpreadsheetRequest $request)
    {
        $validatedRequest = $request->validated();
        $spreadsheetData = CreateSpreadsheetData::from($validatedRequest);

        $result = $this->googleSheetsAPIService->createSpreadsheet($spreadsheetData);
        return $this->logAndResponse(GoogleSheetsAPIResource::make($result));
    }

    /**
     * @OA\Get(
     *     path="/api/sheets/read_range",
     *     summary="Read a range of values from a Google Spreadsheet",
     *     tags={"Google Sheets API"},
     *     @OA\Parameter(
     *         name="spreadSheetId",
     *         in="query",
     *         required=true,
     *         description="The ID of the spreadsheet to retrieve data from.",
     *         @OA\Schema(type="string", maxLength=255)
     *     ),
     *     @OA\Parameter(
     *         name="range",
     *         in="query",
     *         required=true,
     *         description="The A1 notation of the range to retrieve values from.",
     *         @OA\Schema(type="string", maxLength=255)
     *     ),
     *     @OA\Parameter(
     *         name="majorDimensions",
     *         in="query",
     *         description="The major dimension that results should use.",
     *         @OA\Schema(type="string", enum={"ROWS", "COLUMNS"})
     *     ),
     *     @OA\Parameter(
     *         name="valueRenderOption",
     *         in="query",
     *         description="How values should be represented in the output.",
     *         @OA\Schema(type="string", enum={"FORMATTED_VALUE", "UNFORMATTED_VALUE", "FORMULA"})
     *     ),
     *     @OA\Parameter(
     *         name="dateTimeRenderOption",
     *         in="query",
     *         description="How dates, times, and durations should be represented in the output.",
     *         @OA\Schema(type="string", enum={"SERIAL_NUMBER", "FORMATTED_STRING"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/ReadRangeResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function readRange(ReadRangeRequest $request)
    {
        $validatedRequest = $request->validated();
        $readRangeData = ReadRangeData::from($validatedRequest);

        $result = $this->googleSheetsAPIService->readRange($readRangeData);
        return $this->logAndResponse(GoogleSheetsAPIResource::make($result));
    }

    /**
     * @OA\Put(
     *     path="/api/sheets/write_range",
     *     summary="Write a range of values to a Google Spreadsheet",
     *     tags={"Google Sheets API"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"spreadSheetId", "range", "valueInputOption", "values"},
     *             @OA\Property(
     *                 property="spreadSheetId",
     *                 type="string",
     *                 description="The ID of the spreadsheet to write data to.",
     *                 maxLength=255
     *             ),
     *             @OA\Property(
     *                 property="range",
     *                 type="string",
     *                 description="The A1 notation of the range to write values to.",
     *                 maxLength=255
     *             ),
     *             @OA\Property(
     *                 property="valueInputOption",
     *                 type="string",
     *                 description="How the input data should be interpreted.",
     *                 enum={"RAW", "USER_ENTERED"}
     *             ),
     *             @OA\Property(
     *                 property="values",
     *                 type="array",
     *                 description="The data to write, as a list of lists.",
     *                 @OA\Items(
     *                     type="array",
     *                     @OA\Items(type="string")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/WriteRangeResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function writeRange(WriteRangeRequest $request)
    {
        $validatedRequest = $request->validated();
        $writeRangeData = WriteRangeData::from($validatedRequest);

        $result = $this->googleSheetsAPIService->writeRange($writeRangeData);
        return $this->logAndResponse(GoogleSheetsAPIResource::make($result));
    }

    /**
     * @OA\Post(
     *     path="/api/sheets/append_values",
     *     summary="Append values to a Google Spreadsheet",
     *     tags={"Google Sheets API"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"spreadSheetId", "range", "valueInputOption", "values"},
     *             @OA\Property(
     *                 property="spreadSheetId",
     *                 type="string",
     *                 description="The ID of the spreadsheet to append data to.",
     *                 maxLength=255
     *             ),
     *             @OA\Property(
     *                 property="range",
     *                 type="string",
     *                 description="The A1 notation of the range to append values to.",
     *                 maxLength=255
     *             ),
     *             @OA\Property(
     *                 property="valueInputOption",
     *                 type="string",
     *                 description="How the input data should be interpreted.",
     *                 enum={"RAW", "USER_ENTERED"}
     *             ),
     *             @OA\Property(
     *                 property="values",
     *                 type="array",
     *                 description="The data to append, as a list of lists.",
     *                 @OA\Items(
     *                     type="array",
     *                     @OA\Items(type="string")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/AppendValuesResponseWrapper")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function appendValues(AppendValuesRequest $request)
    {
        $validatedRequest = $request->validated();
        $appendValuesData = AppendValuesData::from($validatedRequest);

        $result = $this->googleSheetsAPIService->appendValues($appendValuesData);
        return $this->logAndResponse(GoogleSheetsAPIResource::make($result));
    }

    /**
     * @OA\Post(
     *     path="/api/sheets/batch_update",
     *     summary="Batch update values in a Google Spreadsheet",
     *     tags={"Google Sheets API"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/BatchUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/BatchUpdateValuesResponseWrapper")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function batchUpdate(BatchUpdateRequest $request)
    {
        $validatedRequest = $request->validated();
        $batchUpdateData = BatchUpdateData::from($validatedRequest);

        $result = $this->googleSheetsAPIService->batchUpdate($batchUpdateData);
        return $this->logAndResponse(GoogleSheetsAPIResource::make($result));
    }

    /**
     * @OA\Post(
     *     path="/api/sheets/clear_range",
     *     summary="Clear a range of values from a Google Spreadsheet",
     *     tags={"Google Sheets API"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ClearRangeRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/ClearValuesResponseWrapper")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function clearRange(ClearRangeRequest $request)
    {
        $validatedRequest = $request->validated();
        $clearRangeData = ClearRangeData::from($validatedRequest);

        $result = $this->googleSheetsAPIService->clearRange($clearRangeData);
        return $this->logAndResponse(GoogleSheetsAPIResource::make($result));
    }

    /**
     * @OA\Post(
     *     path="/api/sheets/management",
     *     summary="Perform various management operations on Google Sheets (add, delete, copy)",
     *     tags={"Google Sheets API"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SheetsManagementRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/GoogleSheetsAPIResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function sheetsManagement(SheetsManagementRequest $request)
    {
        $validatedRequest = $request->validated();
        $sheetsManagementData = SheetsManagementData::from($validatedRequest);

        $result = $this->googleSheetsAPIService->sheetsManagement($sheetsManagementData);
        return $this->logAndResponse(GoogleSheetsAPIResource::make($result));
    }


}
