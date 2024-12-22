<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Traits\filterProductsAndStores;
use App\Traits\sortProductsAndStores;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    use sortProductsAndStores, filterProductsAndStores;
    public function getStores(Request $request)
    {
        $storesQuery = Store::select('id', 'name', 'location', 'image');

        $this->filterProductsAndStores($request, null, $storesQuery);

        $sortBy = $request->get('sort');
        $this->sortProductsAndStores($sortBy, null, $storesQuery);

        $stores = $storesQuery->paginate(10);

        $stores->appends($request->query());

        if ($stores->isEmpty()) {
            return response()->json([
                'message' => 'No Stores available.',
            ], 404);
        }

        return response()->json([
            'current_page' => $stores->currentPage(),
            'data' => $stores,
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
        $stores = Store::select('id', 'name', 'image' ,'location')->latest()->take(10)->get();

        if ($stores->isEmpty()) {
            return response()->json([
                'message' => 'No stores available.',
            ], 404);
        }

        return response()->json([
            'data' => $stores,
            'total' => count($stores),
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
