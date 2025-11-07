<?php

namespace App\Http\Controllers;

use App\Enums\LogTypeEnum;
use App\Models\Log;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * Insert log into the database and return a response.
     *
     * @param  mixed  $data
     * @param  LogTypeEnum  $typeEnum
     * @return JsonResponse
     */
    public function logAndResponse(mixed $data, LogTypeEnum $typeEnum = LogTypeEnum::SERVICE_TYPE): JsonResponse
    {
        $inputs = [
            'type' => $typeEnum->value,
            'activity' => $data,
        ];
        $user = request()->user();
        if ($user)
            $inputs = array_merge($inputs, [
                'email' => $user->email,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
        Log::query()->create($inputs);

        return response()->json($data);
    }
}
