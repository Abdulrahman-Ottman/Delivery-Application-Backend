<?php

use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('/sms/{phone}', [MessageController::class, 'show'])->name('sms');


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
