<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function (){
    Route::post('/sendCode', 'sendCode');
    Route::post('/verify', 'verify');
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});
//Route::controller(UserController::class)->group(function (){
//    Route::get('/getUserInfo/{phone}', 'getUserInfo');
//    Route::get('/getUserImage/{phone}', 'getUserImage');
//});
