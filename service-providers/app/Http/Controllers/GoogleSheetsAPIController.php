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
use OpenApi\Attributes as OA;

class GoogleSheetsAPIController extends BaseController
{
    protected GoogleSheetsAPIService $googleSheetsAPIService;

    public function __construct(GoogleSheetsAPIService $googleSheetsAPIService)
    {
        $this->googleSheetsAPIService = $googleSheetsAPIService;
    }

    #[OA\Post(
        path: '/api/sheets/create_spreadsheet',
        summary: 'Create a new Google Spreadsheet',
        tags: ['Google Sheets API'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/CreateSpreadsheetRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(ref: '#/components/schemas/GoogleSheetsAPIResource')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            )
        ]
    )]
    public function create(CreateSpreadsheetRequest $request)
    {
        $validatedRequest = $request->validated();
        $spreadsheetData = CreateSpreadsheetData::from($validatedRequest);

        $result = $this->googleSheetsAPIService->createSpreadsheet($spreadsheetData);
        return $this->logAndResponse(GoogleSheetsAPIResource::make($result));
    }

    
    #[OA\Get(
        path: '/api/sheets/read_range',
        summary: 'Read a range of values from a Google Spreadsheet',
        tags: ['Google Sheets API'],
        parameters: [
            new OA\QueryParameter(name: 'spreadSheetId', in: 'query', required: true, description: 'The ID of the spreadsheet to retrieve data from.', schema: new OA\Schema(type: 'string', maxLength: 255)),
            new OA\QueryParameter(name: 'range', in: 'query', required: true, description: 'The A1 notation of the range to retrieve values from.', schema: new OA\Schema(type: 'string', maxLength: 255)),
            new OA\QueryParameter(name: 'majorDimensions', in: 'query', description: 'The major dimension that results should use.', schema: new OA\Schema(type: 'string', enum: ['ROWS', 'COLUMNS'])),
            new OA\QueryParameter(name: 'valueRenderOption', in: 'query', description: 'How values should be represented in the output.', schema: new OA\Schema(type: 'string', enum: ['FORMATTED_VALUE', 'UNFORMATTED_VALUE', 'FORMULA'])),
            new OA\QueryParameter(name: 'dateTimeRenderOption', in: 'query', description: 'How dates, times, and durations should be represented in the output.', schema: new OA\Schema(type: 'string', enum: ['SERIAL_NUMBER', 'FORMATTED_STRING']))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(ref: '#/components/schemas/ReadRangeResponse')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            )
        ]
    )]
    public function readRange(ReadRangeRequest $request)
    {
        $validatedRequest = $request->validated();
        $readRangeData = ReadRangeData::from($validatedRequest);

        $result = $this->googleSheetsAPIService->readRange($readRangeData);
        return $this->logAndResponse(GoogleSheetsAPIResource::make($result));
    }

    
    #[OA\Put(
        path: '/api/sheets/write_range',
        summary: 'Write a range of values to a Google Spreadsheet',
        tags: ['Google Sheets API'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['spreadSheetId', 'range', 'valueInputOption', 'values'],
                properties: [
                    new OA\Property(property: 'spreadSheetId', type: 'string', description: 'The ID of the spreadsheet to write data to.', maxLength: 255),
                    new OA\Property(property: 'range', type: 'string', description: 'The A1 notation of the range to write values to.', maxLength: 255),
                    new OA\Property(property: 'valueInputOption', type: 'string', description: 'How the input data should be interpreted.', enum: ['RAW', 'USER_ENTERED']),
                    new OA\Property(property: 'values', type: 'array', description: 'The data to write, as a list of lists.', items: new OA\Items(type: 'array', items: new OA\Items(type: 'string')))
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(ref: '#/components/schemas/WriteRangeResponse')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            )
        ]
    )]
    public function writeRange(WriteRangeRequest $request)
    {
        $validatedRequest = $request->validated();
        $writeRangeData = WriteRangeData::from($validatedRequest);

        $result = $this->googleSheetsAPIService->writeRange($writeRangeData);
        return $this->logAndResponse(GoogleSheetsAPIResource::make($result));
    }

    
    #[OA\Post(
        path: '/api/sheets/append_values',
        summary: 'Append values to a Google Spreadsheet',
        tags: ['Google Sheets API'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['spreadSheetId', 'range', 'valueInputOption', 'values'],
                properties: [
                    new OA\Property(property: 'spreadSheetId', type: 'string', description: 'The ID of the spreadsheet to append data to.', maxLength: 255),
                    new OA\Property(property: 'range', type: 'string', description: 'The A1 notation of the range to append values to.', maxLength: 255),
                    new OA\Property(property: 'valueInputOption', type: 'string', description: 'How the input data should be interpreted.', enum: ['RAW', 'USER_ENTERED']),
                    new OA\Property(property: 'values', type: 'array', description: 'The data to append, as a list of lists.', items: new OA\Items(type: 'array', items: new OA\Items(type: 'string')))
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(ref: '#/components/schemas/AppendValuesResponseWrapper')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            )
        ]
    )]
    public function appendValues(AppendValuesRequest $request)
    {
        $validatedRequest = $request->validated();
        $appendValuesData = AppendValuesData::from($validatedRequest);

        $result = $this->googleSheetsAPIService->appendValues($appendValuesData);
        return $this->logAndResponse(GoogleSheetsAPIResource::make($result));
    }

    
    #[OA\Post(
        path: '/api/sheets/batch_update',
        summary: 'Batch update values in a Google Spreadsheet',
        tags: ['Google Sheets API'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/BatchUpdateRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(ref: '#/components/schemas/BatchUpdateValuesResponseWrapper')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            )
        ]
    )]
    public function batchUpdate(BatchUpdateRequest $request)
    {
        $validatedRequest = $request->validated();
        $batchUpdateData = BatchUpdateData::from($validatedRequest);

        $result = $this->googleSheetsAPIService->batchUpdate($batchUpdateData);
        return $this->logAndResponse(GoogleSheetsAPIResource::make($result));
    }


    #[OA\Post(
        path: '/api/sheets/clear_range',
        summary: 'Clear a range of values from a Google Spreadsheet',
        tags: ['Google Sheets API'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/ClearRangeRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(ref: '#/components/schemas/ClearValuesResponseWrapper')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            )
        ]
    )]
    public function clearRange(ClearRangeRequest $request)
    {
        $validatedRequest = $request->validated();
        $clearRangeData = ClearRangeData::from($validatedRequest);

        $result = $this->googleSheetsAPIService->clearRange($clearRangeData);
        return $this->logAndResponse(GoogleSheetsAPIResource::make($result));
    }


    #[OA\Post(
        path: '/api/sheets/management',
        summary: 'Perform various management operations on Google Sheets (add, delete, copy)',
        tags: ['Google Sheets API'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/SheetsManagementRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(ref: '#/components/schemas/GoogleSheetsAPIResource')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
            )
        ]
    )]
    public function sheetsManagement(SheetsManagementRequest $request)
    {
        $validatedRequest = $request->validated();
        $sheetsManagementData = SheetsManagementData::from($validatedRequest);

        $result = $this->googleSheetsAPIService->sheetsManagement($sheetsManagementData);
        return $this->logAndResponse(GoogleSheetsAPIResource::make($result));
    }
    
}
