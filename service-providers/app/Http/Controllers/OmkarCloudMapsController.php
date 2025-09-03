<?php

// app/Http/Controllers/OmkarCloudMapsController.php
namespace App\Http\Controllers;

use App\Http\Requests\Maps\MapsRequest;
use App\Services\OmkarCloudMapsClient;
use Illuminate\Support\Facades\Log;
use Throwable;

class OmkarCloudMapsController extends Controller {
  public function __construct(private OmkarCloudMapsClient $c) {}

  private function run(callable $fn){
    try { $data = $fn(); Log::info('omkar_maps_success',['endpoint'=>request()->path()]);
      return response()->json(['ok'=>true,'data'=>$data],200);
    } catch (Throwable $e){
      Log::error('omkar_maps_error',['endpoint'=>request()->path(),'err'=>$e->getMessage()]);
      return response()->json(['ok'=>false,'error'=>$e->getMessage()],422);
    }
  }

  public function searchByQuery(MapsRequest $r){ return $this->run(fn()=> $this->c->searchQuery($r->validated())); }
  public function searchByLinks(MapsRequest $r){ return $this->run(fn()=> $this->c->searchLinks($r->validated())); }
  public function fetchReviews(MapsRequest $r){ return $this->run(fn()=> $this->c->fetchReviews($r->validated())); }
  public function getResultsStatus(MapsRequest $r){ return $this->run(fn()=> $this->c->resultsStatus($r->validated())); }
  public function getOutputData(MapsRequest $r){ return $this->run(fn()=> $this->c->outputData($r->validated())); }
  public function exportData(MapsRequest $r){ return $this->run(fn()=> $this->c->exportData($r->validated())); }
  public function manageTasks(MapsRequest $r){ return $this->run(fn()=> $this->c->manageTasks($r->validated())); }
  public function filterResults(MapsRequest $r){ return $this->run(fn()=> $this->c->filterResults($r->validated())); }
  public function applySortLogic(MapsRequest $r){ return $this->run(fn()=> $this->c->sortLogic($r->validated())); }
}
