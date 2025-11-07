<?php

namespace App\Http\Requests\GettyImages;

use Illuminate\Foundation\Http\FormRequest;

class VideoSearchEditorialRequest extends FormRequest
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
            'fields.aspect_ratios'            => 'nullable|array',
            'fields.aspect_ratios.*'         => 'string|in:16:9,9:16,3:4,4:3,4:5,2:1,17:9,9:17',
            'fields.collection_codes'            => 'nullable|array',
            'fields.collection_codes.*'         => 'string',
            'fields.collections_filter_type'     => 'nullable|string|in:exclude,include',
            'fields.color'                       => 'nullable|string|size:6',
            'fields.compositions'                => 'nullable|array',
            'fields.compositions.*'              => 'string|in:abstract,candid,close_up,copy_space,cut_out,full_frame,full_length,headshot,looking_at_camera,macro,medium_shot,part_of_a_series,portrait,sparse,still_life,three_quarter_length,waist_up,wide_shot',
            'fields.date_from' => 'nullable|date',
            'fields.date_to'   => 'nullable|date',
            'fields.download_product'            => 'nullable|string',
            'fields.editorial_video_types'            => 'nullable|array',
            'fields.editorial_video_types.*'         => 'string|in:raw,produced',
            'fields.event_ids'                   => 'nullable|array',
            'fields.event_ids.*'                 => 'integer',
            'fields.format_available'                   => 'nullable|string|in:sd,hd,4k,hd_web',
            'fields.frame_rates'     => 'nullable|array',
            'fields.frame_rates.*'   => 'string|in:23.98,24,25,29.97,30,50,59.94,60',
            'fields.image_techniques'     => 'nullable|array',
            'fields.image_techniques.*'   => 'string|in:realtime,time_lapse,slow_motion,color,black_and_white,animation,selective_focus',
            'fields.include_related_searches'      => 'nullable|boolean',
            'fields.keyword_ids'                    => 'nullable|array',
            'fields.keyword_ids.*'                  => 'integer',
            'fields.min_clip_length'     => 'nullable|integer|min:0',
            'fields.max_clip_length'     => 'nullable|integer|min:0',
            'fields.orientations'        => 'nullable|array',
            'fields.orientations.*'      => 'string|in:horizontal,vertical',
            'fields.page'                => 'nullable|integer|min:1',
            'fields.page_size'           => 'nullable|integer|min:1|max:100',
            'fields.specific_people'     => 'nullable|array',
            'fields.specific_people.*'   => 'string',
            'fields.release_status'      => 'nullable|string|in:release_not_important,full_eleased', // adjust enum as needed
            'fields.facet_fields'        => 'nullable|array',
            'fields.facet_fields.*'      => 'string|in:artists,events,locations,specific_people', // expand based on full list if available
            'fields.include_facets'     => 'nullable|boolean',
            'fields.facet_max_count'    => 'nullable|integer|min:1',
            'fields.viewpoints'       => 'nullable|array',
            'fields.viewpoints.*'     => 'string|in:lockdown,panning,tracking_shot,aerial_view,high_angle_view,low_angle_view,tilt,point_of_view',
        ];
    }
}
