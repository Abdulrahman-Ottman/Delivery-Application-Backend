<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdResource;
use App\Models\Ad;
use Illuminate\Http\Request;

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
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

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
