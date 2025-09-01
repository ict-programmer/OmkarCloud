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

class GoogleSheetsAPIController extends BaseController
{
    protected GoogleSheetsAPIService $googleSheetsAPIService;

    public function __construct(GoogleSheetsAPIService $googleSheetsAPIService)
    {
        $this->googleSheetsAPIService = $googleSheetsAPIService;
    }

    public function create(CreateSpreadsheetRequest $request)
    {
        $validatedRequest = $request->validated();
        $spreadsheetData = CreateSpreadsheetData::from($validatedRequest);

        $result = $this->googleSheetsAPIService->createSpreadsheet($spreadsheetData);
        return $this->logAndResponse(GoogleSheetsAPIResource::make($result));
    }

    public function readRange(ReadRangeRequest $request)
    {
        $validatedRequest = $request->validated();
        $readRangeData = ReadRangeData::from($validatedRequest);

        $result = $this->googleSheetsAPIService->readRange($readRangeData);
        return $this->logAndResponse(GoogleSheetsAPIResource::make($result));
    }

    public function writeRange(WriteRangeRequest $request)
    {
        $validatedRequest = $request->validated();
        $writeRangeData = WriteRangeData::from($validatedRequest);

        $result = $this->googleSheetsAPIService->writeRange($writeRangeData);
        return $this->logAndResponse(GoogleSheetsAPIResource::make($result));
    }

    public function appendValues(AppendValuesRequest $request)
    {
        $validatedRequest = $request->validated();
        $appendValuesData = AppendValuesData::from($validatedRequest);

        $result = $this->googleSheetsAPIService->appendValues($appendValuesData);
        return $this->logAndResponse(GoogleSheetsAPIResource::make($result));
    }

    public function batchUpdate(BatchUpdateRequest $request)
    {
        $validatedRequest = $request->validated();
        $batchUpdateData = BatchUpdateData::from($validatedRequest);

        $result = $this->googleSheetsAPIService->batchUpdate($batchUpdateData);
        return $this->logAndResponse(GoogleSheetsAPIResource::make($result));
    }

    public function clearRange(ClearRangeRequest $request)
    {
        $validatedRequest = $request->validated();
        $clearRangeData = ClearRangeData::from($validatedRequest);

        $result = $this->googleSheetsAPIService->clearRange($clearRangeData);
        return $this->logAndResponse(GoogleSheetsAPIResource::make($result));
    }

    public function sheetsManagement(SheetsManagementRequest $request)
    {
        $validatedRequest = $request->validated();
        $sheetsManagementData = SheetsManagementData::from($validatedRequest);

        $result = $this->googleSheetsAPIService->sheetsManagement($sheetsManagementData);
        return $this->logAndResponse(GoogleSheetsAPIResource::make($result));
    }


}
