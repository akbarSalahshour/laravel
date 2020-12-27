<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NotActiveProfileException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function render(Request $request): JsonResponse
    {
//        if($request->expectsJson()){
        return \response()->json([
            'data'=>[],
            'message'=>'حساب کاربری شما فعال نشده است.',
            'status'=>'error'
        ],Response::HTTP_NOT_ACCEPTABLE);
    }
//    }
}
