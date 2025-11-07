<?php

use Illuminate\Http\Exceptions\HttpResponseException;

if (!function_exists('th')) {
    function th(mixed ...$data): void
    {
        throw new HttpResponseException(response()->json($data));
    }
}
