<?php

namespace App\Http\Requests;

use App\Enums\SortOrderEnum;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseFormRequest extends FormRequest
{
    protected bool $havePagination = false;

    protected array $passed = [];

    protected function prepareForValidation(): void
    {
        if ($this->havePagination) {
            $this->havePagination();
        }
    }

    private function havePagination(): void
    {
        $this->validate([
            'page_limit' => 'nullable|integer|min:1',
            'page_size' => 'nullable|integer|min:1',
            'sort_by' => 'nullable|string',
            'sort_order' => 'nullable|string|in:' . SortOrderEnum::getValuesInString(),
        ]);

        $this->passed = array_merge($this->passed, [
            'page_limit' => $this->input('page_limit', 20),
            'page_size' => $this->input('page_size', 1),
            'sort_by' => $this->input('sort_by', 'created_at'),
            'sort_order' => $this->input('sort_order', 'desc'),
        ]);
    }

    public function validated($key = null, $default = null): array
    {
        return array_merge(parent::validated(), $this->passed);
    }
}
