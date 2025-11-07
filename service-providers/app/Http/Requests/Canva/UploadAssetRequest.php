<?php

namespace App\Http\Requests\Canva;

use Illuminate\Foundation\Http\FormRequest;

class UploadAssetRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => 'required|file|max:512000|mimes:jpeg,png,heic,tiff,webp,gif,m4v,mkv,mp4,mpeg,webm,quicktime',
        ];
    }

    /**
     * Get error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'file.required' => 'The file is required.',
            'file.file' => 'The upload must be a valid file.',
            'file.mimes' => 'The file must be a valid image or video. Allowed file types: jpeg, png, heic, tiff, webp, gif, m4v, mkv, mp4, mpeg, webm, quicktime.',
        ];
    }
}