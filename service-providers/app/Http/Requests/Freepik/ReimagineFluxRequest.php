<?php

namespace App\Http\Requests\Freepik;

use App\Enums\Freepik\Image\ReimagineFlux\AspectRatioEnum;
use App\Enums\Freepik\Image\ReimagineFlux\ImaginationTypeEnum;
use App\Rules\ValidIpfsCid;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReimagineFluxRequest extends FormRequest
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
        return [
            'image_cid' => ['required', 'string', new ValidIpfsCid],
            'prompt' => ['nullable', 'string'],
            'imagination' => [
                'nullable',
                'string',
                Rule::in(ImaginationTypeEnum::getValuesInArray()),
            ],
            'aspect_ratio' => [
                'nullable',
                'string',
                Rule::in(AspectRatioEnum::getValuesInArray()),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'aspect_ratio.in' => __('The selected aspect ratio is invalid. Valid values: :values', [
                'values' => AspectRatioEnum::getValuesInString(),
            ]),
            'imagination.in' => __('The selected imagination is invalid. Valid values: :values', [
                'values' => ImaginationTypeEnum::getValuesInString(),
            ]),

        ];
    }
}
