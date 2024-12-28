<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function (){
    Route::post('/sendCode', 'sendCode');
    Route::post('/verify', 'verify');
    Route::post('/register', 'register');
    Route::post('/resetPassword', 'resetPassword');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
    Route::post('/changePassword', 'changePassword')->middleware('auth:sanctum');
    Route::post('/updateUserInfo', 'updateUserInfo')->middleware('auth:sanctum');
});

Route::controller(ProductController::class)->group(function (){
    Route::get('/getProducts', 'getProducts')
        ->middleware('auth:sanctum');
    Route::get('/latestProducts', 'latestProducts');
    Route::get('/product/{id}', 'product');
    Route::post('/addProduct', 'addProduct')
        ->middleware('auth:sanctum')
        ->middleware(\App\Http\Middleware\RoleMiddleware::class);
});

Route::controller(StoreController::class)->group(function (){
    Route::get('/getStores', 'getStores')->middleware('auth:sanctum');
    Route::get('/latestStores', 'latestStores');
    Route::get('/store/{id}', 'store');
    Route::post('/addStore', 'addStore')
        ->middleware('auth:sanctum')
        ->middleware(\App\Http\Middleware\RoleMiddleware::class);
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


Route::controller(SearchController::class)->group(function (){
    Route::get('/search', 'search');
    Route::get('/autoComplete', 'autoComplete');
});
Route::middleware('auth:sanctum')->prefix('user')->group(function () {
    Route::get('/cart', [CartController::class, 'getUserCart']);
    Route::post('/cart', [CartController::class, 'addToCart']);
    Route::post('/cart/make-order', [OrderController::class, 'placeOrder']);
    Route::get('/orders', [OrderController::class, 'getUserOrders']);
    Route::post('/edit-order', [OrderController::class, 'editOrder']);
    Route::post('/cancel-order', [OrderController::class, 'cancelOrder'])->name('cancel-order');

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

});

Route::get('/pending-orders', [OrderController::class, 'getPendingOrders'])
    ->middleware('auth:sanctum')
    ->middleware(\App\Http\Middleware\RoleMiddleware::class);

Route::post('/change-order-status', [OrderController::class, 'changeOrderStatus'])
    ->middleware('auth:sanctum')
    ->middleware(\App\Http\Middleware\RoleMiddleware::class);

