<?php

namespace App\Http\Requests\Maps;

use Illuminate\Foundation\Http\FormRequest;

class MapsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // shared
            'format'        => ['sometimes','in:json,csv,excel'],
            'filters'       => ['sometimes','array'],
            'filters.city'  => ['sometimes','string','max:100'],
            'filters.country' => ['sometimes','string','max:100'],
            'filters.rating'=> ['sometimes','numeric','min:0','max:5'],

            // search_query
            'query'         => ['sometimes','string','max:512'],

            // search_links
            'links'         => ['sometimes','array','min:1','max:100'],
            'links.*'       => ['url'],

            // fetch_reviews
            'identifier'    => ['sometimes','string','max:1024'],
            'place_id'      => ['sometimes','string','max:256'],
            'link'          => ['sometimes','url'],
            'limit'         => ['sometimes','integer','min:1','max:1000'],

            // task-id based
            'task_id'       => ['sometimes','string','max:128'],

            // manage_tasks
            'action'        => ['sometimes','in:start,abort,delete'],

            // sort_logic
            'mode'          => ['sometimes','in:best_customer'], // extend if you add more modes
        ];
    }
}
