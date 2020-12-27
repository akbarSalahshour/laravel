<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::namespace('api\v1')->group(function(){
    Route::post('register','UserController@register');
    Route::post('login','UserController@login');
    Route::post('verify','UserController@verify');
    Route::post('complete','UserController@complete_profile');
    Route::post('forget_password','UserController@forget_password');
    Route::put('insert_password','UserController@verify_forget_password');
    Route::get('articles/index','ArticleController@index');
    Route::get('articles/{article}','ArticleController@show');
    Route::middleware('auth')->group(function (){
        Route::prefix('articles')->resource('article','ArticleController')->except(['index','show']);
        Route::prefix('categories')->resource('category','CategoryController')->except(['index','show']);
    });
});
