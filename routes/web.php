<?php

use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::get('/sms/{phone}', [MessageController::class, 'show'])->name('sms');
Route::get('/', function () { return view('login'); });
Route::get('/super_admin/dashboard', function () { return view('super-admin');});
Route::get('/admin/dashboard/{id}', function ($id) { return view('admin', ['id' => $id]);});


Route::get('/pr', function () {
    $sourcePath = 'C:/Users/Pro/Desktop/bb';

    // Define the destination path in the public disk
    $destinationPath = 'images/categories';

    // Check if the source folder exists
    if (File::exists($sourcePath) && File::isDirectory($sourcePath)) {
        // Create the destination folder if it doesn't exist
        if (!Storage::disk('public')->exists($destinationPath)) {
            Storage::disk('public')->makeDirectory($destinationPath);
        }

        // Copy files from the source folder to the destination folder
        $files = File::files($sourcePath);
        foreach ($files as $file) {
            $fileName = $file->getFilename();
            Storage::disk('public')->putFileAs($destinationPath, new \Illuminate\Http\File($file), $fileName);
        }

        return response()->json(['message' => 'Folder copied successfully.']);
    }

    return response()->json(['message' => 'Source folder does not exist.'], 404);
});

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
