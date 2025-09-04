<?php

namespace App\Http\Controllers;

use App\Services\OmkarCloudMapsService;
use Illuminate\Http\JsonResponse;

// Requests
use App\Http\Requests\OmkarCloud\{
    BusinessSearchRequest, SearchLinksRequest, FetchReviewsRequest, TaskIdRequest,
    ExportRequest, ManageTasksRequest, FilterResultsRequest, SortLogicRequest
};
// Data
use App\Data\Request\OmkarCloud\{
    BusinessSearchData, SearchLinksData, FetchReviewsData, TaskIdData,
    ExportData as ExportDataDto, ManageTasksData, FilterResultsData, SortLogicData
};

class OmkarCloudMapsController extends Controller
{
    public function __construct(private OmkarCloudMapsService $svc) {}

    public function searchByQuery(BusinessSearchRequest $r): JsonResponse
    {
        $dto = BusinessSearchData::from($r->validated());
        $res = $this->svc->searchQuery($dto);
        return response()->json($res->json(), $res->status());
    }

    public function searchByLinks(SearchLinksRequest $r): JsonResponse
    {
        $dto = SearchLinksData::from($r->validated());
        $res = $this->svc->searchLinks($dto);
        return response()->json($res->json(), $res->status());
    }

    public function fetchReviews(FetchReviewsRequest $r): JsonResponse
    {
        $dto = FetchReviewsData::from($r->validated());
        $res = $this->svc->fetchReviews($dto);
        return response()->json($res->json(), $res->status());
    }

    public function getResultsStatus(TaskIdRequest $r): JsonResponse
    {
        $dto = TaskIdData::from($r->validated());
        $res = $this->svc->resultsStatus($dto);
        return response()->json($res->json(), $res->status());
    }

    public function getOutputData(TaskIdRequest $r): JsonResponse
    {
        $dto = TaskIdData::from($r->validated());
        $res = $this->svc->outputData($dto);
        return response()->json($res->json(), $res->status());
    }

    public function exportData(ExportRequest $r): JsonResponse
    {
        $dto = ExportDataDto::from($r->validated());
        $res = $this->svc->exportData($dto);
        return response()->json($res->json(), $res->status());
    }

    public function manageTasks(ManageTasksRequest $r): JsonResponse
    {
        $dto = ManageTasksData::from($r->validated());
        $res = $this->svc->manageTasks($dto);
        return response()->json($res->json(), $res->status());
    }

    public function filterResults(FilterResultsRequest $r): JsonResponse
    {
        $dto = FilterResultsData::from($r->validated());
        $res = $this->svc->filterResults($dto);
        return response()->json($res->json(), $res->status());
    }

    public function applySortLogic(SortLogicRequest $r): JsonResponse
    {
        $dto = SortLogicData::from($r->validated());
        $res = $this->svc->sortLogic($dto);
        return response()->json($res->json(), $res->status());
    }
}
