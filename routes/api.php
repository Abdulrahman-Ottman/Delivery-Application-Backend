<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;

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

Route::controller(CategoryController::class)->prefix('categories')->group(function () {
    Route::get('/', 'getAllCategories');
    Route::get('{category}/subcategories', 'getSubcategoriesByCategory');
});

Route::controller(AdController::class)->group(function (){
    Route::get('/ads' , 'index');
    Route::post('/ads' , 'store');
    Route::delete('/ads/{id}',  'destroy');

});

Route::get('/search' , SearchController::class);


//Route::controller(UserController::class)->group(function (){
//    Route::get('/getUserInfo/{phone}', 'getUserInfo');
//    Route::get('/getUserImage/{phone}', 'getUserImage');
//});
Route::controller(ProductController::class)->group(function (){
    Route::get('/allProducts', [ProductController::class, 'allProducts']);
    Route::get('/latestProducts', [ProductController::class, 'latestProducts']);
    Route::get('/product/{id}', [ProductController::class, 'product']);
});

Route::controller(StoreController::class)->group(function (){
    Route::get('/allStores', [StoreController::class, 'allStores']);
    Route::get('/latestStores', [StoreController::class, 'latestStores']);
    Route::get('/store/{id}', [StoreController::class, 'store']);
});

