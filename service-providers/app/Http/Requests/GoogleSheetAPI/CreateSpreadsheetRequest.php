<?php

namespace App\Http\Requests\GoogleSheetAPI;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CreateSpreadsheetRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        return [
            // --- Spreadsheet Properties ---
            "properties" => ["sometimes", "array"],
            "properties.title" => [
                "required_with:properties",
                "string",
                "max:255",
            ],
            "properties.locale" => [
                "sometimes",
                "string",
                "max:10",
                Rule::in([
                    "en",
                    "es",
                    "fr",
                    "de",
                    "ja",
                    "ko",
                    "pt",
                    "ru",
                    "zh_CN",
                    "zh_TW",
                    "en_US",
                    "es_419",
                    "pt_BR",
                    "fil",
                    "id",
                    "it",
                    "pl",
                    "vi",
                    "tr",
                    "he",
                    "ar",
                    "fa",
                    "hi",
                    "bn",
                    "th",
                ]),
            ],
            "properties.timeZone" => [
                "sometimes",
                "string",
                "max:255",
                "timezone",
            ],

            // Sheets Properties
            "sheets" => ["sometimes", "array"],
            "sheets.*" => ["array"],
            "sheets.*.properties" => ["sometimes", "array"],
            "sheets.*.properties.title" => [
                "required_with:sheets.*.properties",
                "string",
                "max:255",
            ],
            "sheets.*.properties.index" => ["sometimes", "integer", "min:0"],
            "sheets.*.properties.sheetType" => [
                "sometimes",
                Rule::in(["GRID", "OBJECT", "DATA_SOURCE"]),
            ],
            "sheets.*.properties.hidden" => ["sometimes", "boolean"],
            "sheets.*.properties.rightToLeft" => ["sometimes", "boolean"],

            // --- Grid Properties for Sheets ---
            "sheets.*.properties.gridProperties" => ["sometimes", "array"],
            "sheets.*.properties.gridProperties.rowCount" => [
                "sometimes",
                "integer",
                "min:1",
            ],
            "sheets.*.properties.gridProperties.columnCount" => [
                "sometimes",
                "integer",
                "min:1",
            ],
            "sheets.*.properties.gridProperties.frozenRowCount" => [
                "sometimes",
                "integer",
                "min:0",
            ],
            "sheets.*.properties.gridProperties.frozenColumnCount" => [
                "sometimes",
                "integer",
                "min:0",
            ],
            "sheets.*.properties.gridProperties.hideGridlines" => [
                "sometimes",
                "boolean",
            ],
            "sheets.*.properties.gridProperties.rowGroupControlAfter" => [
                "sometimes",
                "boolean",
            ],
            "sheets.*.properties.gridProperties.columnGroupControlAfter" => [
                "sometimes",
                "boolean",
            ],

            // --- Tab Color for Sheets (Color and ColorStyle are identical in structure) ---
            "sheets.*.properties.tabColor" => ["sometimes", "array"],
            "sheets.*.properties.tabColor.red" => [
                "sometimes",
                "numeric",
                "min:0",
                "max:1",
            ],
            "sheets.*.properties.tabColor.green" => [
                "sometimes",
                "numeric",
                "min:0",
                "max:1",
            ],
            "sheets.*.properties.tabColor.blue" => [
                "sometimes",
                "numeric",
                "min:0",
                "max:1",
            ],
            "sheets.*.properties.tabColor.alpha" => [
                "sometimes",
                "numeric",
                "min:0",
                "max:1",
            ],
            "sheets.*.properties.tabColorStyle" => ["sometimes", "array"],
            "sheets.*.properties.tabColorStyle.rgbColor" => [
                "sometimes",
                "array",
            ],
            "sheets.*.properties.tabColorStyle.rgbColor.red" => [
                "sometimes",
                "numeric",
                "min:0",
                "max:1",
            ],
            "sheets.*.properties.tabColorStyle.rgbColor.green" => [
                "sometimes",
                "numeric",
                "min:0",
                "max:1",
            ],
            "sheets.*.properties.tabColorStyle.rgbColor.blue" => [
                "sometimes",
                "numeric",
                "min:0",
                "max:1",
            ],
            "sheets.*.properties.tabColorStyle.rgbColor.alpha" => [
                "sometimes",
                "numeric",
                "min:0",
                "max:1",
            ],
            "sheets.*.properties.tabColorStyle.themeColor" => [
                "sometimes",
                Rule::in([
                    "THEME_COLOR_TYPE_UNSPECIFIED",
                    "TEXT",
                    "BACKGROUND",
                    "ACCENT1",
                    "ACCENT2",
                    "ACCENT3",
                    "ACCENT4",
                    "ACCENT5",
                    "ACCENT6",
                    "LINK",
                    "FOLLOWED_LINK",
                ]),
            ],

            // --- DataSourceSheetProperties (if sheetType is DATA_SOURCE) ---
            "sheets.*.properties.dataSourceSheetProperties" => [
                "sometimes",
                "array",
            ],
            "sheets.*.properties.dataSourceSheetProperties.dataSourceId" => [
                "required_with:sheets.*.properties.dataSourceSheetProperties",
                "string",
                "max:255",
            ],
            "sheets.*.properties.dataSourceSheetProperties.columns" => [
                "sometimes",
                "array",
            ],
            "sheets.*.properties.dataSourceSheetProperties.columns.*.reference" => [
                "required_with:sheets.*.properties.dataSourceSheetProperties.columns.*",
                "array",
            ],
            "sheets.*.properties.dataSourceSheetProperties.columns.*.reference.name" => [
                "required_with:sheets.*.properties.dataSourceSheetProperties.columns.*.reference",
                "string",
                "max:255",
            ],
            "sheets.*.properties.dataSourceSheetProperties.columns.*.formula" => [
                "sometimes",
                "string",
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            "properties.array" => __(
                "The spreadsheet properties must be an array.",
            ),
            "properties.title.required_with" => __(
                "The spreadsheet title is required when properties are provided.",
            ),
            "properties.title.string" => __(
                "The spreadsheet title must be a string.",
            ),
            "properties.title.max" => __(
                "The spreadsheet title must not exceed :max characters.",
            ),
            "properties.locale.string" => __(
                "The spreadsheet locale must be a string.",
            ),
            "properties.locale.max" => __(
                "The spreadsheet locale must not exceed :max characters.",
            ),
            "properties.locale.in" => __(
                "The selected spreadsheet locale is invalid. Please choose from the allowed options.",
            ),
            "properties.timeZone.string" => __(
                "The spreadsheet time zone must be a string.",
            ),
            "properties.timeZone.max" => __(
                "The spreadsheet time zone must not exceed :max characters.",
            ),
            "properties.timeZone.timezone" => __(
                "The spreadsheet time zone must be a valid timezone identifier.",
            ),

            "sheets.array" => __("The sheets must be an array."),
            "sheets.*.array" => __("Each sheet entry must be an array."),
            "sheets.*.properties.array" => __(
                "The sheet properties for sheet :index must be an array.",
            ),
            "sheets.*.properties.title.required_with" => __(
                "The sheet title for sheet :index is required when sheet properties are provided.",
            ),
            "sheets.*.properties.title.string" => __(
                "The sheet title for sheet :index must be a string.",
            ),
            "sheets.*.properties.title.max" => __(
                "The sheet title for sheet :index must not exceed :max characters.",
            ),
            "sheets.*.properties.index.integer" => __(
                "The sheet index for sheet :index must be an integer.",
            ),
            "sheets.*.properties.index.min" => __(
                "The sheet index for sheet :index must be a positive number or zero.",
            ),
            "sheets.*.properties.sheetType.in" => __(
                "The selected sheet type for sheet :index is invalid. Allowed types are GRID, OBJECT, or DATA_SOURCE.",
            ),
            "sheets.*.properties.hidden.boolean" => __(
                "The hidden property for sheet :index must be a boolean value.",
            ),
            "sheets.*.properties.rightToLeft.boolean" => __(
                "The rightToLeft property for sheet :index must be a boolean value.",
            ),

            "sheets.*.properties.gridProperties.array" => __(
                "The grid properties for sheet :index must be an array.",
            ),
            "sheets.*.properties.gridProperties.rowCount.integer" => __(
                "The row count for grid properties in sheet :index must be an integer.",
            ),
            "sheets.*.properties.gridProperties.rowCount.min" => __(
                "The row count for grid properties in sheet :index must be at least :min.",
            ),
            "sheets.*.properties.gridProperties.columnCount.integer" => __(
                "The column count for grid properties in sheet :index must be an integer.",
            ),
            "sheets.*.properties.gridProperties.columnCount.min" => __(
                "The column count for grid properties in sheet :index must be at least :min.",
            ),
            "sheets.*.properties.gridProperties.frozenRowCount.integer" => __(
                "The frozen row count for grid properties in sheet :index must be an integer.",
            ),
            "sheets.*.properties.gridProperties.frozenRowCount.min" => __(
                "The frozen row count for grid properties in sheet :index must be a positive number or zero.",
            ),
            "sheets.*.properties.gridProperties.frozenColumnCount.integer" => __(
                "The frozen column count for grid properties in sheet :index must be an integer.",
            ),
            "sheets.*.properties.gridProperties.frozenColumnCount.min" => __(
                "The frozen column count for grid properties in sheet :index must be a positive number or zero.",
            ),
            "sheets.*.properties.gridProperties.hideGridlines.boolean" => __(
                "The hide gridlines property for sheet :index must be a boolean value.",
            ),
            "sheets.*.properties.gridProperties.rowGroupControlAfter.boolean" => __(
                "The row group control after property for sheet :index must be a boolean value.",
            ),
            "sheets.*.properties.gridProperties.columnGroupControlAfter.boolean" => __(
                "The column group control after property for sheet :index must be a boolean value.",
            ),

            "sheets.*.properties.tabColor.array" => __(
                "The tab color for sheet :index must be an array.",
            ),
            "sheets.*.properties.tabColor.red.numeric" => __(
                "The red color component for tab color in sheet :index must be a number.",
            ),
            "sheets.*.properties.tabColor.red.min" => __(
                "The red color component for tab color in sheet :index must be at least :min.",
            ),
            "sheets.*.properties.tabColor.red.max" => __(
                "The red color component for tab color in sheet :index must not be greater than :max.",
            ),
            "sheets.*.properties.tabColor.green.numeric" => __(
                "The green color component for tab color in sheet :index must be a number.",
            ),
            "sheets.*.properties.tabColor.green.min" => __(
                "The green color component for tab color in sheet :index must be at least :min.",
            ),
            "sheets.*.properties.tabColor.green.max" => __(
                "The green color component for tab color in sheet :index must not be greater than :max.",
            ),
            "sheets.*.properties.tabColor.blue.numeric" => __(
                "The blue color component for tab color in sheet :index must be a number.",
            ),
            "sheets.*.properties.tabColor.blue.min" => __(
                "The blue color component for tab color in sheet :index must be at least :min.",
            ),
            "sheets.*.properties.tabColor.blue.max" => __(
                "The blue color component for tab color in sheet :index must not be greater than :max.",
            ),
            "sheets.*.properties.tabColor.alpha.numeric" => __(
                "The alpha color component for tab color in sheet :index must be a number.",
            ),
            "sheets.*.properties.tabColor.alpha.min" => __(
                "The alpha color component for tab color in sheet :index must be at least :min.",
            ),
            "sheets.*.properties.tabColor.alpha.max" => __(
                "The alpha color component for tab color in sheet :index must not be greater than :max.",
            ),

            "sheets.*.properties.tabColorStyle.array" => __(
                "The tab color style for sheet :index must be an array.",
            ),
            "sheets.*.properties.tabColorStyle.rgbColor.array" => __(
                "The RGB color for tab color style in sheet :index must be an array.",
            ),
            "sheets.*.properties.tabColorStyle.rgbColor.red.numeric" => __(
                "The red component for RGB color in tab color style for sheet :index must be a number.",
            ),
            "sheets.*.properties.tabColorStyle.rgbColor.red.min" => __(
                "The red component for RGB color in tab color style for sheet :index must be at least :min.",
            ),
            "sheets.*.properties.tabColorStyle.rgbColor.red.max" => __(
                "The red component for RGB color in tab color style for sheet :index must not be greater than :max.",
            ),
            "sheets.*.properties.tabColorStyle.rgbColor.green.numeric" => __(
                "The green component for RGB color in tab color style for sheet :index must be a number.",
            ),
            "sheets.*.properties.tabColorStyle.rgbColor.green.min" => __(
                "The green component for RGB color in tab color style for sheet :index must be at least :min.",
            ),
            "sheets.*.properties.tabColorStyle.rgbColor.green.max" => __(
                "The green component for RGB color in tab color style for sheet :index must not be greater than :max.",
            ),
            "sheets.*.properties.tabColorStyle.rgbColor.blue.numeric" => __(
                "The blue component for RGB color in tab color style for sheet :index must be a number.",
            ),
            "sheets.*.properties.tabColorStyle.rgbColor.blue.min" => __(
                "The blue component for RGB color in tab color style for sheet :index must be at least :min.",
            ),
            "sheets.*.properties.tabColorStyle.rgbColor.blue.max" => __(
                "The blue component for RGB color in tab color style for sheet :index must not be greater than :max.",
            ),
            "sheets.*.properties.tabColorStyle.rgbColor.alpha.numeric" => __(
                "The alpha component for RGB color in tab color style for sheet :index must be a number.",
            ),
            "sheets.*.properties.tabColorStyle.rgbColor.alpha.min" => __(
                "The alpha component for RGB color in tab color style for sheet :index must be at least :min.",
            ),
            "sheets.*.properties.tabColorStyle.rgbColor.alpha.max" => __(
                "The alpha component for RGB color in tab color style for sheet :index must not be greater than :max.",
            ),
            "sheets.*.properties.tabColorStyle.themeColor.in" => __(
                "The selected theme color for tab color style in sheet :index is invalid.",
            ),

            "sheets.*.properties.dataSourceSheetProperties.array" => __(
                "The data source sheet properties for sheet :index must be an array.",
            ),
            "sheets.*.properties.dataSourceSheetProperties.dataSourceId.required_with" => __(
                "The data source ID is required when data source sheet properties are provided for sheet :index.",
            ),
            "sheets.*.properties.dataSourceSheetProperties.dataSourceId.string" => __(
                "The data source ID for sheet :index must be a string.",
            ),
            "sheets.*.properties.dataSourceSheetProperties.dataSourceId.max" => __(
                "The data source ID for sheet :index must not exceed :max characters.",
            ),
            "sheets.*.properties.dataSourceSheetProperties.columns.array" => __(
                "The data source columns for sheet :index must be an array.",
            ),
            "sheets.*.properties.dataSourceSheetProperties.columns.*.reference.required_with" => __(
                "The column reference for a data source column in sheet :index is required when column details are provided.",
            ),
            "sheets.*.properties.dataSourceSheetProperties.columns.*.reference.array" => __(
                "The column reference for a data source column in sheet :index must be an array.",
            ),
            "sheets.*.properties.dataSourceSheetProperties.columns.*.reference.name.required_with" => __(
                "The column reference name for a data source column in sheet :index is required when column reference details are provided.",
            ),
            "sheets.*.properties.dataSourceSheetProperties.columns.*.reference.name.string" => __(
                "The column reference name for a data source column in sheet :index must be a string.",
            ),
            "sheets.*.properties.dataSourceSheetProperties.columns.*.reference.name.max" => __(
                "The column reference name for a data source column in sheet :index must not exceed :max characters.",
            ),
            "sheets.*.properties.dataSourceSheetProperties.columns.*.formula.string" => __(
                "The column formula for a data source column in sheet :index must be a string.",
            ),
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json(
                [
                    "status" => "error",
                    "message" => "Validation failed",
                    "errors" => $validator->errors(),
                ],
                422,
            ),
        );
    }
}
