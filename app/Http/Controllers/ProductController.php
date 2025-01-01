<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Traits\filterProductsAndStores;
use App\Traits\sortProductsAndStores;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    use filterProductsAndStores, sortProductsAndStores;
    public function getProducts(Request $request)
    {

        $productsQuery = Product::select('id', 'name', 'price', 'store_id','quantity')
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
    public function addProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|exists:stores,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'description' => 'required|string',
            'details' => 'array',
            'main_image' => 'image|mimes:jpg,jpeg,png|max:2048',
            'additional_images.*' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Adding failed',
                'data' => $validator->errors()
            ], 401);
        }
        $store_id = $request->get('store_id');

        if(auth()->user()->store->id != $store_id)
            return response()->json(['message' => 'Access Denied.'], 440);

        $product = new Product();
        $product->name = $request->get('name');
        $product->price = $request->get('price');
        $product->quantity = $request->get('quantity');
        $product->description = $request->get('description');
        $details = $request->input('details');
        $product->details = str_replace(['[', ']'], '', json_encode($details));
        $product->store_id = $request->get('store_id');
        $product->save();
        if ($request->hasFile('main_image')) {
            $mainImagePath = $request->file('main_image')->store('images/products', 'public');
            ProductImage::create([
                'path' => 'storage/' . str_replace('public/', '', $mainImagePath),
                'product_id' => $product->id,
                'is_main' => true,
            ]);

        }

        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $image) {
                $additionalImagePath = $image->store('images/products', 'public');
                ProductImage::create([
                    'path' => 'storage/' . str_replace('public/', '', $additionalImagePath),
                    'product_id' => $product->id,
                    'is_main' => false,
                ]);
            }
        }

        return response()->json(['message' => 'Product added successfully!', 'product' => $product], 201);
    }
}
