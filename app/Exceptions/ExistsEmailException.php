<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\{
    Request,
    JsonResponse
};
use Symfony\Component\HttpFoundation\Response;

class ExistsEmailException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render(Request $request): JsonResponse
    {
        return \response()->json([
            'data' => [],
            'message' => 'ایمیل وارد شده قبلا در سیستم ثبت شده است.',
            'status' => 'error'
        ], Response::HTTP_FOUND);
    }
}
