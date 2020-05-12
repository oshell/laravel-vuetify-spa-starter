<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::get('verify/{uid}/{hash}', 'AuthController@verifyEmail');
Route::post('reset/password/init', 'AuthController@initPasswordReset');
Route::post('reset/password/{uid}/{hash}', 'AuthController@resetPassword');

Route::middleware('auth:api')->group(function () {
    Route::get('user', 'AuthController@getAuthUser');
    Route::get('logout', 'AuthController@logout');
});

Route::get('preview/mail', function () {
    return new App\Mail\CtaMail([
        'title' => 'title',
        'preview' => 'preview text',
        'paragraph' => 'this is a test mail',
        'ctaLink' => 'https://www.google.com',
        'ctaText' => 'Click Me',
        'info' => 'this is just a test mail'
    ]);
});