<?php

namespace App\Http\Requests\Freepik;

use App\Enums\Freepik\KlingModelEnum;
use App\Enums\Freepik\KlingVideoDurationEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KlingImageToVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $model = $this->input('model');

        $rules = [];

        if (in_array($model, KlingModelEnum::getValuesInArray())) {
            $model = KlingModelEnum::from($model);

            if ($model->supportsImageTail()) {
                $rules['image_tail'] = ['nullable', 'string', 'url'];
            }

            if ($model->supportsMasking()) {
                $rules = array_merge($rules, [
                    'static_mask' => ['nullable', 'string', 'url'],
                    'dynamic_masks' => ['nullable', 'array'],
                    'dynamic_masks.*.mask' => ['required_with:dynamic_masks', 'string', 'url'],
                    'dynamic_masks.*.trajectories' => ['required_with:dynamic_masks', 'array'],
                    'dynamic_masks.*.trajectories.*.x' => ['required_with:dynamic_masks', 'integer'],
                    'dynamic_masks.*.trajectories.*.y' => ['required_with:dynamic_masks', 'integer'],
                ]);
            }
        }

        return array_merge($rules, [
            'model' => ['required', Rule::in(KlingModelEnum::getValuesInArray())],
            'duration' => ['required', Rule::in(KlingVideoDurationEnum::getValuesInArray())],
            'image' => ['nullable', 'string', 'url'],
            'prompt' => ['nullable', 'string', 'max:2500'],
            'negative_prompt' => ['nullable', 'string', 'max:2500'],
            'cfg_scale' => ['nullable', 'numeric', 'between:0,1'],
        ]);
    }

    public function messages()
    {
        return [
            'model.in' => __('The selected model is invalid. Valid values: :values', ['values' => KlingModelEnum::getValuesInString()]),
            'duration.in' => __('The selected duration is invalid. Valid values: :values', ['values' => KlingVideoDurationEnum::getValuesInString()]),
        ];
    }
}
