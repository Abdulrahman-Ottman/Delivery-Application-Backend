<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function allStores()
    {
        $stores = Store::paginate(10);

        if ($stores->isEmpty()) {
            return response()->json([
                'message' => 'No Stores available.',
            ], 404);
        }

        $stores->getCollection()->transform(function ($store) {
            return [
                'id' => $store->id,
                'name' => $store->name,
//                'location' => json_decode($store->location),
                'image' => $store->image,
            ];
        });

        return response()->json([
            'current_page' => $stores->currentPage(),
            'data' => $stores->getCollection(),
            'first_page_url' => $stores->url(1),
            'last_page' => $stores->lastPage(),
            'last_page_url' => $stores->url($stores->lastPage()),
            'links' => [
                'previous' => $stores->previousPageUrl(),
                'next' => $stores->nextPageUrl(),
            ],
            'per_page' => $stores->perPage(),
            'to' => $stores->lastItem(),
            'total' => $stores->total(),
        ]);
    }
    public function latestStores()
    {
        $stores = Store::latest()->take(10)->get();

        if ($stores->isEmpty()) {
            return response()->json([
                'message' => 'No stores available.',
            ], 404);
        }

        $transformedStores = $stores->transform(function ($store) {
            return [
                'id' => $store->id,
                'name' => $store->name,
//                'location' => json_decode($store->location),
                'image' => $store->image,
            ];
        });

        return response()->json([
            'data' => $transformedStores,
            'total' => $transformedStores->count(),
        ]);
    }
    public function store(Request $request)
    {
        $store = Store::find($request->id);

        if (!$store) {
            return response()->json(['message' => 'Store not found.'], 404);
        }

        $products = $store->products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'formatted_price' => '$' . number_format($product->price, 2),
                'image' => $product->images->isNotEmpty() ? $product->images->first()->path : null,
            ];
        });
        return response()->json([
            'id' => $store->id,
            'name' => $store->name,
            'location' => json_decode($store->location),
            'image' => $store->image,
            'products' => $products,
        ]);
    }
}
