<?php

namespace App\Http\Controllers;

use App\Traits\filterProductsAndStores;
use App\Traits\sortProductsAndStores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{
    use sortProductsAndStores , filterProductsAndStores;
    public function addFavorite(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'product_id' => ['required', 'exists:products,id'],
        ]);

        if ($validator->fails()){
            return response()->json([
                'message' => "Failed to add to favorites",
                'data' =>$validator->errors()
            ],400);
        }

        $user = Auth::user();

        if ($user->favorites()->where('product_id', $request->product_id)->exists()) {
            return response()->json([
                'message' => 'This product is already in your favorites.',
            ], 409);
        }

        $user->favorites()->attach($request->product_id);

        return response()->json([
            'message' => 'Product added to favorites successfully.',
        ], 201);
    }

    public function getFavorites(Request $request){
        $user = Auth::user();

        $productsQuery = $user->favorites()
            ->select('products.id as product_id', 'name', 'price', 'store_id')
            ->with(['mainImage:product_id,path', 'store:id,name,location']);

        $this->filterProductsAndStores($request, $productsQuery, null);

        $sortBy = $request->get('sort');
        $this->sortProductsAndStores($sortBy, $productsQuery, null);

        $products = $productsQuery->paginate(10);
        $products->appends($request->query());

        if ($products->isEmpty()) {
            return response()->json([
                'message' => 'No favorite products found.',
            ], 404);
        }

        $data = $products->map(function ($product) {
            return [
                'product_id' => $product->product_id,
                'name' => $product->name,
                'price' => $product->price,
                'main_image' => $product->mainImage->path ?? null,
                'store' => [
                    'id' => $product->store->id ?? null,
                    'name' => $product->store->name ?? null,
                    'location' => $product->store->location ?? null,
                ],
            ];
        });

        return response()->json([
            'current_page' => $products->currentPage(),
            'data' => $data,
            'first_page_url' => $products->url(1),
            'last_page' => $products->lastPage(),
            'last_page_url' => $products->url($products->lastPage()),
            'links' => [
                'previous' => $products->previousPageUrl(),
                'next' => $products->nextPageUrl(),
            ],
            'per_page' => $products->perPage(),
            'to' => $products->lastItem(),
            'total' => $products->total(),
        ]);
    }
}
