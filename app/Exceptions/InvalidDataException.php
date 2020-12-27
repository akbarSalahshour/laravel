<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InvalidDataException extends Exception
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
                'message'=>'اطلاعات وارد شده صحیح نمی باشد.',
                'status'=>'error'
            ],Response::HTTP_UNAUTHORIZED);
        }
//    }
}
