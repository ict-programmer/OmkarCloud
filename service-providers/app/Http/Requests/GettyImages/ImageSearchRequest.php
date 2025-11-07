<?php

namespace App\Http\Requests\GettyImages;

use Illuminate\Foundation\Http\FormRequest;

class ImageSearchRequest extends FormRequest
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
            'fields.event_ids'                   => 'nullable|array',
            'fields.event_ids.*'                 => 'integer',
            'fields.ethnicity'                   => 'nullable|array',
            'fields.ethnicity.*'                 => 'string|in:black,white,east_asian,hispanic_latinx,japanese,middle_eastern,multiracial_person,multiethnic_group,native_american_first_nations,pacific_islander,south_asian,southeast_asian',
            'fields.exclude_nudity'               => 'nullable|boolean',
            'fields.fields'                         => 'nullable|array',
            'fields.fields.*'                       => 'string|in:accessories,allowed_use,alternative_ids,artist,asset_family,call_for_image,caption,collection_code,collection_id,collection_name,color_type,comp,comp_webp,copyright,date_camera_shot,date_created,date_submitted,detail_set,display_set,download_product,download_sizes,editorial_segments,editorial_source,event_ids,graphical_style,idistock_collection,keywords,largest_downloads,license_model,max_dimensions,orientation,people,preview,product_types,quality_rank,referral_destinations,summary_set,thumb,title,uri_ormbed',
            'fields.file_types'                     => 'nullable|array',
            'fields.file_types.*'                   => 'string|in:eps',
            'fields.graphical_styles'               => 'nullable|array',
            'fields.graphical_styles.*'             => 'string|in:fine_art,illustration,photograph,vector',
            'fields.graphical_style_filter_type'  => 'nullable|string|in:exclude,include',
            'fields.include_related_searches'      => 'nullable|boolean',
            'fields.keyword_ids'                    => 'nullable|array',
            'fields.keyword_ids.*'                  => 'integer',
            'fields.minimum_size'                   => 'nullable|string|in:x_small,small,medium,large,x_large,xx_large,vector',
            'fields.number_of_people'               => 'nullable|array',
            'fields.number_of_people.*'             => 'string|in:none,one,two,group',
            'fields.orientations'                     => 'nullable|array',
            'fields.orientations.*'                   => 'string|in:horizontal,vertical,square,panoramic_horizontal,panoramic_vertical',
            'fields.page'                              => 'nullable|integer|min:1',
            'fields.page_size'                         => 'nullable|integer|min:1|max:200',
            'fields.specific_people'                   => 'nullable|array',
            'fields.specific_people.*'                 => 'string',
        ];
    }
}
