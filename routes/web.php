<?php

use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('/sms/{phone}', [MessageController::class, 'show'])->name('sms');
