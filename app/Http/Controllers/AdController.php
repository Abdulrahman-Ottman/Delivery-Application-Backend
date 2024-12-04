<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdController extends Controller
{
    public function index()
    {
        $ads = Ad::latest()->paginate(1);

        if ($ads->isEmpty()) {
            return response()->json([
                'message' => 'No Ad available for id : ' . \request('page'),
                'links' => [
                    'next' => $ads->url(1),
                    'prev' => null,
                ],
            ], 200);
        }

        $currentAd = $ads->first();

        return response()->json([
            'ad' => [
                'image' => $currentAd['image'],
            ],
            'pagination' => [
                'current_page' => $ads->currentPage(),
                'total' => $ads->total(),
                'links' => [
                    'next' => $ads->nextPageUrl(),
                    'prev' => $ads->previousPageUrl(),
                ],
            ],
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()){
            return response()->json([
                'message' => "failed to create the Ad",
                'data' =>$validator->errors()
            ],401);
        }

        $path = $request->file('image')->store('ads', 'public');
        $ad = Ad::create([
            'image' => $path,
        ]);

        return response()->json([
            'message' => 'Ad created successfully.',
            'ad' => $ad,
        ], 201);
    }
}
