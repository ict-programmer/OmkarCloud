<?php

namespace App\Http\Requests\GettyImages;

use Illuminate\Foundation\Http\FormRequest;

class VideoSearchCreativeByImageRequest extends FormRequest
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
            'phrase'                            => 'nullable|string',
            'fields' => 'nullable|array',
            'fields.language'                    => 'nullable|string',
            'fields.countryCode'                   => 'nullable|string',
            'fields.asset_id'                     => 'nullable|string',
            'fields.exclude_editorial_use_only'      => 'nullable|boolean',
            'fields.facet_fields'        => 'nullable|array',
            'fields.facet_fields.*'      => 'string|in:artists,events,locations',
            'fields.facet_max_count'    => 'nullable|integer|min:1',
            'fields.fields'                         => 'nullable|array',
            'fields.fields.*'                       => 'string|in:allowed_use', 'artist', 'aspect_ratio', 'asset_family', 'call_for_image','caption', 'clip_length', 'collection_code', 'collection_id', 'collection_name','color_type', 'comp', 'copyright', 'date_created', 'date_submitted','detail_set', 'display_set', 'download_product', 'download_sizes', 'era','id', 'istock_collection', 'keywords', 'largest_downloads', 'license_model','mastered_to', 'object_name', 'orientation', 'originally_shot_on', 'preview','summary_set', 'thumb', 'title',
            'fields.image_url'            => 'nullable|image_url',
            'fields.include_facets'      => 'nullable|boolean',
            'fields.page'                => 'nullable|integer|min:1',
            'fields.page_size'           => 'nullable|integer|min:1|max:100',
            'fields.product_types'                    => 'nullable|array',
            'fields.product_types.*'                  => 'string',
        ];
    }
}
