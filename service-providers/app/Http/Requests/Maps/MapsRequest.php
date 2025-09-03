<?php
// app/Http/Requests/Maps/MapsRequest.php
namespace App\Http\Requests\Maps;
use Illuminate\Foundation\Http\FormRequest;

class MapsRequest extends FormRequest {
  public function authorize(): bool { return true; }
  public function rules(): array {
    return [
      'query' => ['sometimes','string','max:512'],
      'links' => ['sometimes','array','max:100'],
      'links.*' => ['url'],
      'filters' => ['sometimes','array'],
      'format' => ['sometimes','in:json,csv,excel'],
      'task_id' => ['sometimes','string','max:128'],
    ];
  }
}
