<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Traits\filterProductsAndStores;
use App\Traits\sortProductsAndStores;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use filterProductsAndStores, sortProductsAndStores;
    public function getProducts(Request $request)
    {

        $productsQuery = Product::select('id', 'name', 'price', 'store_id')
            ->with(['mainImage:product_id,path', 'store:id,name,location']);

        $this->filterProductsAndStores($request, $productsQuery, null);

        $sortBy = $request->get('sort');
        $this->sortProductsAndStores($sortBy, $productsQuery, null);

        $products = $productsQuery->paginate(10);

        $products->appends($request->query());

        if ($products->isEmpty()) {
            return response()->json([
                'message' => 'No products available.',
            ], 404);
        }

        return response()->json([
            'current_page' => $products->currentPage(),
            'data' => $products,
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
    public function latestProducts()
    {
        $products = Product::select('id', 'name', 'price', 'store_id')->with(['mainImage:product_id,path',
            'store:id,name,location'])->latest()->take(10)->get();

        if ($products->isEmpty()) {
            return response()->json([
                'message' => 'No products available.',
            ], 404);
        }

        return response()->json([
            'data' => $products,
            'total' => count($products),
        ]);
    }
    public function product(Request $request)
    {
        $product = Product::find($request->id);

        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'description' => $product->description,
            'quantity' => $product->quantity,
            'details' => json_decode($product->details),
            'store_id' => $product->store_id,
            'store_name' => $product->store ? $product->store->name : null,
            'store_location' => $product->store ? json_decode($product->store->location) : null,
            'images' => $product->images->pluck('path'),
            'main_image' => $product->images->firstWhere('is_main', true)->path ?? null,
        ]);
    }
}
