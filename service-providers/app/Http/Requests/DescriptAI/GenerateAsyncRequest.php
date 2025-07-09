<?php

namespace App\Http\Requests\DescriptAI;

use Illuminate\Foundation\Http\FormRequest;

class GenerateAsyncRequest extends FormRequest
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
            'text' => ['required', 'string'],
            'voice_id' => ['required', 'uuid'],
            'voice_style_id' => ['required', 'uuid'],
            'prefix_text' => ['nullable', 'string'],
            'prefix_audio_url' => ['nullable', 'string'],
            'suffix_text' => ['nullable', 'string'],
            'suffix_audio_url' => ['nullable', 'string'],
            'callback_url' => ['required', 'url'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'text.required' => __('The text field is required.'),
            'text.string' => __('The text field must be a string.'),
            'voice_id.required' => __('The voice ID is required.'),
            'voice_id.uuid' => __('The voice ID must be a valid UUID.'),
            'voice_style_id.required' => __('The voice style ID is required.'),
            'voice_style_id.uuid' => __('The voice style ID must be a valid UUID.'),
            'prefix_text.string' => __('The prefix text must be a string.'),
            'prefix_audio_url.string' => __('The prefix audio string must be a valid string.'),
            'suffix_text.string' => __('The suffix text must be a string.'),
            'suffix_audio_url.string' => __('The suffix audio string must be a valid string.'),
            'callback_url.required' => __('The callback URL is required.'),
            'callback_url.url' => __('The callback URL must be a valid URL.'),
        ];
    }
}
