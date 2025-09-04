<?php

namespace App\Http\Controllers;

use App\Http\Requests\Maps\MapsRequest;
use App\Services\OmkarCloudMapsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

use App\Data\OmkarCloud\Requests\{
    SearchQuery, SearchLinks, FetchReviews, TaskId, ExportData,
    ManageTasks, FilterResults, SortLogic
};
use App\Data\OmkarCloud\Responses\GenericResponse;

class OmkarCloudMapsController extends Controller
{
    public function __construct(private OmkarCloudMapsService $service) {}

    private function respond(GenericResponse $g): JsonResponse
    {
        return response()->json(
            ['ok' => $g->error === null, 'data' => $g->data, 'error' => $g->error],
            $g->status
        );
    }

    private function exec(callable $fn): JsonResponse
    {
        try {
            $res = $fn(); // GenericResponse
            Log::info('omkar_maps_success', ['endpoint' => request()->path(), 'status' => $res->status]);
            return $this->respond($res);
        } catch (Throwable $e) {
            Log::error('omkar_maps_error', ['endpoint' => request()->path(), 'err' => $e->getMessage()]);
            return response()->json(['ok' => false, 'error' => 'Internal error'], 500);
        }
    }

    public function searchByQuery(MapsRequest $r): JsonResponse
    {
        $dto = new SearchQuery(
            query:   (string)$r->input('query'),
            filters: $r->input('filters'),
            format:  $r->input('format', 'json')
        );
        return $this->exec(fn() => $this->service->searchByQuery($dto));
    }

    public function searchByLinks(MapsRequest $r): JsonResponse
    {
        $dto = new SearchLinks(
            links:   (array)$r->input('links', []),
            filters: $r->input('filters'),
            format:  $r->input('format', 'json')
        );
        return $this->exec(fn() => $this->service->searchByLinks($dto));
    }

    public function fetchReviews(MapsRequest $r): JsonResponse
    {
        $identifier = $r->input('identifier') ?? $r->input('place_id') ?? $r->input('link');
        $dto = new FetchReviews(
            identifier: (string)$identifier,
            limit:      $r->integer('limit'),
            format:     $r->input('format', 'json')
        );
        return $this->exec(fn() => $this->service->fetchReviews($dto));
    }

    public function getResultsStatus(MapsRequest $r): JsonResponse
    {
        $dto = new TaskId(
            taskId: (string)$r->input('task_id'),
            format: $r->input('format', 'json')
        );
        return $this->exec(fn() => $this->service->resultsStatus($dto));
    }

    public function getOutputData(MapsRequest $r): JsonResponse
    {
        $dto = new TaskId(
            taskId: (string)$r->input('task_id'),
            format: $r->input('format', 'json')
        );
        return $this->exec(fn() => $this->service->outputData($dto));
    }

    public function exportData(MapsRequest $r): JsonResponse
    {
        $dto = new ExportData(
            taskId: (string)$r->input('task_id'),
            format: (string)$r->input('format', 'csv') // csv|json|excel
        );
        return $this->exec(fn() => $this->service->exportData($dto));
    }

    public function manageTasks(MapsRequest $r): JsonResponse
    {
        $payload = null;
        if ($r->filled('query')) {
            $payload = new SearchQuery($r->input('query'), $r->input('filters'), $r->input('format', 'json'));
        } elseif ($r->filled('links')) {
            $payload = new SearchLinks((array)$r->input('links'), $r->input('filters'), $r->input('format', 'json'));
        }

        $dto = new ManageTasks(
            action: (string)$r->input('action'),  // 'start'|'abort'|'delete'
            taskId: $r->input('task_id'),
            payload: $payload
        );
        return $this->exec(fn() => $this->service->manageTasks($dto));
    }

    public function filterResults(MapsRequest $r): JsonResponse
    {
        $dto = new FilterResults(
            taskId:  $r->input('task_id'),
            filters: $r->input('filters'),
            format:  $r->input('format', 'json')
        );
        return $this->exec(fn() => $this->service->filterResults($dto));
    }

    public function applySortLogic(MapsRequest $r): JsonResponse
    {
        $dto = new SortLogic(
            taskId: $r->input('task_id'),
            mode:   $r->input('mode', 'best_customer'),
            format: $r->input('format', 'json')
        );
        return $this->exec(fn() => $this->service->sortLogic($dto));
    }
}
