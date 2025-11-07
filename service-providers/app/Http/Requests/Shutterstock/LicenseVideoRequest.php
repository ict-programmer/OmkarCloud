<?php

namespace App\Http\Requests\Shutterstock;

use Illuminate\Foundation\Http\FormRequest;

class LicenseVideoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'videos' => 'required|array|min:1',
            'videos.*.video_id' => 'required|string|regex:/^\d+$/',
            'videos.*.subscription_id' => 'required|string',
            'videos.*.size' => 'required|string|in:web,sd,hd,4k',
            'search_id' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'videos.required' => 'The videos field is required.',
            'videos.array' => 'The videos field must be an array.',
            'videos.min' => 'At least one video must be specified.',
            'videos.*.video_id.required' => 'Each video must have a video_id.',
            'videos.*.video_id.regex' => 'Video ID must be numeric.',
            'videos.*.subscription_id.required' => 'Each video must have a subscription_id.',
            'videos.*.size.required' => 'Each video must have a size specified.',
            'videos.*.size.in' => 'Video size must be one of: web, sd, hd, 4k.',
        ];
    }
} 