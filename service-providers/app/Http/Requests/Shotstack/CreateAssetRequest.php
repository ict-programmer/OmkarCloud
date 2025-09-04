<?php

namespace App\Http\Requests\Shotstack;

use Illuminate\Foundation\Http\FormRequest;

class CreateAssetRequest extends FormRequest
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
            'clips' => 'required|array',
            'clips.*.asset.type' => 'required|string',
            'clips.*.asset.text' => 'nullable|string',
            'clips.*.asset.src' => 'nullable|string|url',
            'clips.*.asset.font.family' => 'nullable|string',
            'clips.*.asset.font.size' => 'nullable|integer',
            'clips.*.asset.font.color' => 'nullable|string',
            'clips.*.asset.alignment.horizontal' => 'nullable|string',
            'clips.*.asset.alignment.vertical' => 'nullable|string',
            'clips.*.start' => 'required|string',
            'clips.*.length' => 'required|string',
            'clips.*.transition.in' => 'nullable|string',
            'clips.*.transition.out' => 'nullable|string',
            'clips.*.offset.x' => 'nullable|integer',
            'clips.*.offset.y' => 'nullable|integer',
            'clips.*.effect' => 'nullable|string',
            // output
            'output' => 'required|array',
            'output.format' => 'required|string',
            "output.resolution" => 'nullable|required_without:output.size|string',
            'output.size' => 'nullable|required_without:output.resolution|array',
            'output.size.width' => 'nullable|integer',
            'output.size.height' => 'nullable|integer',
            'output.aspect_ratio' => 'nullable|string',
            'output.fps' => 'nullable|integer',
            'output.scale_to' => 'nullable|string',
            'output.quality' => 'nullable|string',
            'output.repeat' => 'nullable|string',
            'output.mute' => 'nullable|string',
        ];
    }
}
