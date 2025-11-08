<?php

namespace App\Traits;

use Illuminate\Support\Arr;

trait BaseEnumTrait
{
    public static function valueFromName($text): ?int
    {
        $cleanedText = mb_strtoupper($text);
        $enumValue = Arr::first(self::cases(), fn ($value) => $value->name === $cleanedText);

        return $enumValue->value ?? null;
    }

    public static function nameFromValue(int $value): ?string
    {
        $enumValue = Arr::first(self::cases(), fn ($val) => $val->value == $value);

        return strtolower($enumValue->name) ?? null;
    }

    public static function getNamesInArray(): array
    {
        return self::transformCaseNames()->toArray();
    }

    private static function transformCaseNames($pointer = 'name', bool $lowercase = true): \Illuminate\Support\Collection
    {
        return collect(self::cases())->map(fn ($value) => $lowercase ? strtolower($value->$pointer) : $value->$pointer);
    }

    public static function getNamesInString(): string
    {
        return self::transformCaseNames()->implode(',');
    }

    public static function getValuesInArray(): array
    {
        return self::transformCaseNames('value')->toArray();
    }

    public static function getValuesInString(): string
    {
        return self::transformCaseNames('value')->implode(',');
    }

    public static function getOriginalValuesInArray(): array
    {
        return self::transformCaseNames('value', false)->toArray();
    }

    public static function getOriginalValuesInString(): string
    {
        return self::transformCaseNames('value', false)->implode(',');
    }

    public static function valuesFromPartialNames(string $search): array
    {
        $searchTerms = array_map('trim', explode(' ', string: $search));
        $searchTerms = array_map('mb_strtoupper', $searchTerms);

        $matchingValues = [];

        foreach (self::cases() as $case) {
            foreach ($searchTerms as $term) {
                if (stripos($case->name, $term) !== false) {
                    $matchingValues[] = $case->value;
                    break;
                }
            }
        }

        return array_unique($matchingValues);
    }

    public static function getValuesFromCommaSeparatedNames(string $names): array
    {
        $inputNames = array_map('trim', explode(',', $names));
        $inputNames = array_map('strtolower', $inputNames);

        return array_filter(self::cases(), function ($case) use ($inputNames) {
            return in_array(strtolower($case->name), $inputNames);
        });
    }
}
