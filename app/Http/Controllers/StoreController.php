<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessLocation;
use App\Models\Store;
use App\Models\User;
use App\Traits\filterProductsAndStores;
use App\Traits\sortProductsAndStores;
use Illuminate\Http\Request;
use Validator;

class StoreController extends Controller
{
    use sortProductsAndStores, filterProductsAndStores;
    public function getStores(Request $request)
    {
        $storesQuery = Store::select('id', 'name', 'location', 'image', 'discount');

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

    public function store(Request $request)
    {
        $store = Store::find($request->id);

        if (!$store) {
            return response()->json(['message' => 'Store not found.'], 404);
        }

        $favorites = auth()->user()->favorites->pluck('id')->toArray();

        $store->products->each(function ($product) use ($favorites) {
            $product->is_favorite = in_array($product->id, $favorites);
        });

        $products = $store->products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $product->quantity,
                'image' => $product->images->isNotEmpty() ? $product->images->first()->path : null,
                'is_favorite' =>  $product->is_favorite
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
    public function addStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'image' => 'image|mimes:jpg,jpeg,png|max:2048',
            'location' => 'required|json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Adding failed.',
                'data' => $validator->errors()
            ], 401);
        }
        $admin = User::find($request->get('user_id'));
        if($admin->role->name != 'admin'||$admin->store){
            return response()->json(['message' => 'Incorrect admin id.'], 422);
        }
        $store = new Store();
        $store->name = $request->get('name');
        $store->user_id = $request->get('user_id');
        $store->location = $request->input('location');
        $store->image = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images/stores', 'public');
            $store->image = 'storage/' . str_replace('public/', '', $path);
        }

        $store->save();

        ProcessLocation::dispatch($store, json_decode($request->input('location'), true));

        return response()->json(['message' => 'Store added successfully!', 'store' => $store], 201);
    }
}
