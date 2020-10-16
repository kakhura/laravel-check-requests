<?php

namespace Kakhura\LaravelCheckRequest\Helpers;

use Illuminate\Http\JsonResponse;

class Helper
{
    /**
     * @param boolean $result
     * @param array $data
     * @param integer $code
     * @param string $errors
     * @return JsonResponse
     */
    public static function response(bool $result = true, array $data = [], int $code = 200, array $errors = []): JsonResponse
    {
        return response()->json(array_merge(
            ['result' => $result],
            ['data' => $data],
            ['errors' => $errors]
        ), $code);
    }
}
