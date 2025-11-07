<?php

namespace App\Http\Requests\ReactJs;

use App\Models\Element;
use Illuminate\Foundation\Http\FormRequest;

class ReactJsCodeForElementRequest extends FormRequest
{
    public mixed $element;

    public function rules(): array
    {
        return [
            'element_id' => 'required|string',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $element = Element::query()
                ->where('element_id', $this->input('element_id'))
                ->first([
                    '_id', 'element_fused_json',
                ]);
            if (!$element) {
                $validator->errors()->add('element_id', 'Element not found');
            } elseif (empty($element->element_fused_json)) {
                $validator->errors()->add('element_id', 'Element fused JSON is empty');
            } else {
                $this->element = $element;
            }
        });
    }

    public function validated($key = null, $default = null): array
    {
        return array_merge(parent::validated(), [
            'element' => $this->element,
        ]);
    }
}
