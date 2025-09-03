<?php
// app/Services/OmkarCloudMapsClient.php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class OmkarCloudMapsClient {
  private string $base;
  private string $key;
  public function __construct() {
    $this->base = rtrim(env('OMKAR_MAPS_BASE_URL'),'/');
    $this->key  = env('OMKAR_MAPS_API_KEY');
  }
  private function call(string $method, string $path, array $payload = []) {
    $r = Http::timeout(120)->acceptJson()
      ->withHeaders(['Authorization'=>"Bearer {$this->key}"])
      ->{$method}("{$this->base}/{$path}", $payload);
    if ($r->failed()) throw RequestException::create($r);
    return $r->json() ?? $r->body();
  }
  // Map features to paths; adjust to real API once confirmed
  public function searchQuery(array $p){ return $this->call('post','search/query',$p); }
  public function searchLinks(array $p){ return $this->call('post','search/links',$p); }
  public function fetchReviews(array $p){ return $this->call('post','reviews/fetch',$p); }
  public function resultsStatus(array $p){ return $this->call('get','results/status',$p); }
  public function outputData(array $p){ return $this->call('get','results/output',$p); }
  public function exportData(array $p){ return $this->call('post','export',$p); }
  public function manageTasks(array $p){ return $this->call('post','tasks/manage',$p); }
  public function filterResults(array $p){ return $this->call('post','results/filter',$p); }
  public function sortLogic(array $p){ return $this->call('post','results/sort',$p); }
}
