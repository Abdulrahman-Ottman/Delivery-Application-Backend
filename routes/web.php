<?php

use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::get('/sms/{phone}', [MessageController::class, 'show'])->name('sms');
Route::get('/', function () { return view('login'); });
Route::get('/super_admin/dashboard', function () { return view('super-admin');});
Route::get('/admin/dashboard/{id}', function ($id) { return view('admin', ['id' => $id]);});

//just for testing
Route::get('/des/{location1}/{location2}' , function ($location1 , $location2){
    class DistanceTester {
        use \App\Traits\CalculatesDistance;
    }

    $tester = new DistanceTester();

    $distance = $tester->CalculateDistance($location1, $location2);

    if ($distance == null){
        return "wrong locations";
    }
    return response()->json([
        'distance' => $distance,
        'from' => $location1,
        'to' => $location2,
    ]);
});
