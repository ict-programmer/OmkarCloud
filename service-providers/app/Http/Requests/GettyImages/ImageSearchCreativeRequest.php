<?php

namespace App\Http\Requests\GettyImages;

use Illuminate\Foundation\Http\FormRequest;

class ImageSearchCreativeRequest extends FormRequest
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
            'sort_order'                        => 'nullable|string|in:best_match,most_popular,newest,random',
            'fields' => 'nullable|array',
            'fields.language'                    => 'nullable|string',
            'fields.countryCode'                   => 'nullable|string',
            'fields.age_of_people'              => 'nullable|array',
            'fields.age_of_people.*'            => 'string|in:newborn,baby,child,teenager,young_adult,adult,adults_only,mature_adult,senior_adult,0-1_months,2-5_months,6-11_months,12-17_months,18-19_years,20-24_years,20-29_years,25-29_years,30-34_years,30-39_years,35-39_years,40-44_years,40-49_years,45-49_years,50-54_years,50-59_years,55-59_years,60-64_years,60-69_years,65-69_years,70-79_years,80-89_years,90_plus_years,100_over',
            'fields.artists'                     => 'nullable|string',
            'fields.collection_codes'            => 'nullable|array',
            'fields.collection_codes.*'         => 'string',
            'fields.collections_filter_type'     => 'nullable|string|in:exclude,include',
            'fields.color'                       => 'nullable|string|size:6',
            'fields.compositions'                => 'nullable|array',
            'fields.compositions.*'              => 'string|in:abstract,candid,close_up,copy_space,cut_out,full_frame,full_length,headshot,looking_at_camera,macro,medium_shot,part_of_a_series,portrait,sparse,still_life,three_quarter_length,waist_up,wide_shot',
            'fields.download_product'            => 'nullable|string',
            'fields.embed_content_only'          => 'nullable|boolean',
            'fields.enhanced_search'          => 'nullable|boolean',
            'fields.ethnicity'                   => 'nullable|array',
            'fields.ethnicity.*'                 => 'string|in:black,white,east_asian,hispanic_latinx,japanese,middle_eastern,multiracial_person,multiethnic_group,native_american_first_nations,pacific_islander,south_asian,southeast_asian',
            'fields.exclude_editorial_use_only'          => 'nullable|boolean',
            'fields.exclude_keyword_ids'                   => 'nullable|array',
            'fields.exclude_keyword_ids.*'                 => 'integer',
            'fields.exclude_nudity'               => 'nullable|boolean',
            'fields.facet_fields'        => 'nullable|array',
            'fields.facet_fields.*'      => 'string|in:artists,events,locations',
            'fields.facet_max_count'    => 'nullable|integer|min:1',
            'fields.fields'                         => 'nullable|array',
            'fields.fields.*'                       => 'string|in:allowed_use',
            'artist',
            'aspect_ratio',
            'asset_family',
            'call_for_image',
            'caption',
            'clip_length',
            'collection_code',
            'collection_id',
            'collection_name',
            'color_type',
            'comp',
            'copyright',
            'date_created',
            'date_submitted',
            'detail_set',
            'display_set',
            'download_product',
            'download_sizes',
            'era',
            'id',
            'istock_collection',
            'keywords',
            'largest_downloads',
            'license_model',
            'mastered_to',
            'object_name',
            'orientation',
            'originally_shot_on',
            'preview',
            'summary_set',
            'thumb',
            'title',
            'fields.file_types'                     => 'nullable|array',
            'fields.file_types.*'                   => 'string|in:eps',
            'fields.graphical_styles'               => 'nullable|array',
            'fields.graphical_styles.*'             => 'string|in:fine_art,illustration,photograph,vector',
            'fields.graphical_styles_filter_type'  => 'nullable|string|in:exclude,include',
            'fields.include_facets'      => 'nullable|boolean',
            'fields.include_related_searches'      => 'nullable|boolean',
            'fields.keyword_ids'                    => 'nullable|array',
            'fields.keyword_ids.*'                  => 'integer',
            'fields.minimum_size'                   => 'nullable|string|in:x_small,small,medium,large,x_large,xx_large,vector',
            'fields.moods' => 'nullable|array',
            'fields.moods.*' => 'string|in:black_and_white,bold,cool,dramatic,natural,vivid,warm',
            'fields.number_of_people'               => 'nullable|array',
            'fields.number_of_people.*'             => 'string|in:none,one,two,group',
            'fields.orientations'                     => 'nullable|array',
            'fields.orientations.*'                   => 'string|in:horizontal,vertical,square,panoramic_horizontal,panoramic_vertical',
            'fields.page'                              => 'nullable|integer|min:1',
            'fields.page_size'                         => 'nullable|integer|min:1|max:200',
            'fields.safe_search'      => 'nullable|boolean',
        ];
    }
}
