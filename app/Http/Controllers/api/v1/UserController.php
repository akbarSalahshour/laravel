<?php

namespace App\Http\Controllers\api\v1;

use App\Exceptions\InvalidDataException;
use App\Exceptions\NotActiveProfileException;
use App\Exceptions\NotCompleteProfile;
use App\Exceptions\NotCompleteProfileException;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\UserResource;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Http\{
    Request,
};

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
        ]);
        $verify_code = rand(1000, 9999);
        User::create([
            'email' => $validated['email'],
            'code' => $verify_code
        ]);
        return \response()->json([
            'data' => [],
            'status' => 'success',
            'message' => 'کد به ایمیل شما ارسال شد.'
        ]);
    }

    public function verify(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|exists:users|email',
            'code' => 'required'
        ]);
        $user = User::where([
            ['email', $validated['email']],
            ['code', $validated['code']]
        ])->first();
        //ایمیل و کد تایید مطابقت ندارد؟
        if (!$user)
            throw new InvalidDataException($request);
        $user->active=1;
        $user->save();
        return \response()->json([
            'data' => [],
            'status' => 'success',
            'message' => 'حساب کاربری شما فعال شد.'
        ]);
    }

    public function complete_profile(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|exists:users|email',
            'name' => 'required|string',
            'password' => 'required|min:6',
        ]);
        $user = User::where('email',$validated['email'])
            ->first();
        //کاربری با این ایمیل وجود ندارد؟
        if(!$user)
            throw new InvalidDataException($request);
        $user->name=$validated['name'];
        $user->password=bcrypt($validated['password']);
        $user->active=2;
        $user->save();
        return \response()->json([
            'data' => [],
            'status' => 'success',
            'message' => 'اطلاعات حساب شما با موفقیت تکمیل شد.'
        ]);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|exists:users|email',
            'password' => 'required|min:6'
        ]);
        //اطلاعات کاربر صحیح نیست؟
        if (!Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])){
            $user = User::where('email',$request['email'])
                ->first();
            //ایمیل کاربر در سیستم وجود دارد؟
            if($user){
                //کاربر کد تایید را وارد کرده است؟
                switch ($user->active){
                    case 0:
                        //باید کد تایید را وارد کند.
                        throw new NotActiveProfileException($request);
                        break;
                    case 1:
                        //باید اطلاعات خود را تکمیل کند.
                        throw new NotCompleteProfileException($request);
                        break;
                    default:
                        //باید اطلاعات را صحیح وارد کند.
                        throw new UnauthorizedException($request);
                }
            }else
                //باید ایمیل خود را در سیستم ثبت نمایید.
                throw new InvalidDataException($request);
        }
        \auth()->user()->tokens()->delete();
        $token=\auth()->user()->createToken('Api Token on Laravel')->accessToken;
        return new UserResource($token);
    }

    public function forget_password(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users',
        ]);
        $verify_code = rand(1000, 9999);
        User::where([
            'email' => $validated['email'],
        ])->update([
            'active'=>0,
            'password'=>null,
            'code' => $verify_code,
        ]);
        return \response()->json([
            'data' => [],
            'status' => 'success',
            'message' => 'کد به ایمیل شما ارسال شد.'
        ]);
    }
    public function verify_forget_password(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|exists:users|email',
            'code' => 'required',
            'password' => 'required|min:6',
        ]);
        $user = User::where([
            ['email', $validated['email']],
            ['code', $validated['code']]
        ])->first();
        //ایمیل و کد تایید مطابقت ندارد؟
        if (!$user)
            throw new InvalidDataException($request);
        $user->password=$validated['password'];
        $user->active=2;
        $user->save();
        return \response()->json([
            'data' => [],
            'status' => 'success',
            'message' => 'رمز شما با موفقیت تغییر کرد.'
        ]);
    }
}
