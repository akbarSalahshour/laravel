<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NotCompleteProfileException extends Exception
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
            'message'=>'ابتدا باید اطلاعات کاربری خود را تکمیل کنید.',
            'status'=>'error'
        ],Response::HTTP_NOT_ACCEPTABLE);
    }
//    }
}
