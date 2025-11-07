<?php

namespace App\Http\Requests\ReactJs;

use App\Models\ProjectStructure;
use Illuminate\Foundation\Http\FormRequest;

class ReactJsMergeJsonRequest extends FormRequest
{
    public mixed $project_structure;

    public function rules(): array
    {
        return [
            'design_id' => 'required|string',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $structure = ProjectStructure::query()
                ->where('design_id', $this->input('design_id'))
                ->first([
                    '_id', 'design_json', 'design_id',
                ]);
            if (!$structure) {
                $validator->errors()->add('design_id', 'Project structure not found');
            } else {
                $this->project_structure = $structure;
            }
        });
    }

    public function validated($key = null, $default = null): array
    {
        return array_merge(parent::validated(), [
            'project_structure' => $this->project_structure,
        ]);
    }
}
