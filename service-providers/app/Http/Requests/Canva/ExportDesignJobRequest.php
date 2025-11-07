<?php

namespace App\Http\Requests\Canva;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class ExportDesignJobRequest extends FormRequest
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
            'design_id' => ['required', 'string'],

            'format' => [
                'required',
                'array',
            ],
            'format.type' => [
                'required_with:format',
                'string',
                'in:pdf,jpg,png,pptx,gif,mp4'
            ],
            'format.quality' => [
                'nullable',
                'array',
            ],
            'format.quality.orientation' => [
                'required_with:format.quality',
                'string',
                'in:horizontal,vertical',
            ],
            'format.quality.resolution' => [
                'required_with:format.quality',
                'string',
                'in:480p,720p,1080p,4k',
            ],
            'format.page' => [
                'nullable',
                'array',
            ],
            'format.page.*' => [
                'integer',
            ],
            'format.export_quality' => [
                'nullable',
                'string',
                'in:regular,pro'
            ],
            'format.size' => [
                'nullable',
                'string',
                'in:a4,a3,letter,legal'
            ],
            'format.height' => [
                'nullable',
                'integer',
                'min:40',
                'max:25000',
            ],
            'format.width' => [
                'nullable',
                'integer',
                'min:40',
                'max:25000',
            ],
            'format.lossless' => [
                'nullable',
                'boolean',
            ],
            'format.transparent_background' => [
                'nullable',
                'boolean',
            ],
            'format.as_single_image' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    /**
     * Get custom validation messages for the export format validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'design_id.required' => 'A design ID is required for export.',
            'design_id.string' => 'The design ID must be a valid string.',

            'format.required' => 'Export format configuration is required.',
            'format.array' => 'Export format must be provided as a structured configuration.',

            'format.type.required_with' => 'Please specify an export file type.',
            'format.type.string' => 'The file type must be a string value.',
            'format.type.in' => 'Invalid file type. Supported formats are: PDF, JPG, PNG, PPTX, GIF, and MP4.',

            'format.quality.array' => 'Quality settings must be provided as a structured configuration.',

            'format.quality.orientation.required_with' => 'Please specify an orientation when providing quality settings.',
            'format.quality.orientation.string' => 'Orientation must be a string value.',
            'format.quality.orientation.in' => 'Invalid orientation. Choose either horizontal or vertical.',

            'format.quality.resolution.required_with' => 'Please specify a resolution when providing quality settings.',
            'format.quality.resolution.string' => 'Resolution must be a string value.',
            'format.quality.resolution.in' => 'Invalid resolution. Choose from 480p, 720p, 1080p, or 4k.',

            'format.page.array' => 'Page selection must be provided as an array.',
            'format.page.*.integer' => 'Page numbers must be integers.',

            'format.export_quality.string' => 'Export quality must be a string value.',
            'format.export_quality.in' => 'Invalid export quality. Choose either regular or pro.',

            'format.size.string' => 'Paper size must be a string value.',
            'format.size.in' => 'Invalid paper size. Choose from A4, A3, Letter, or Legal.',

            'format.height.integer' => 'Height must be an integer value.',
            'format.height.min' => 'Height must be at least 40 pixels.',
            'format.height.max' => 'Height cannot exceed 25,000 pixels.',

            'format.width.integer' => 'Width must be an integer value.',
            'format.width.min' => 'Width must be at least 40 pixels.',
            'format.width.max' => 'Width cannot exceed 25,000 pixels.',

            'format.lossless.boolean' => 'Lossless option must be a boolean value (true/false).',
            'format.transparent_background.boolean' => 'Transparent background option must be a boolean value (true/false).',
            'format.as_single_image.boolean' => 'Single image export option must be a boolean value (true/false).',
        ];
    }
}