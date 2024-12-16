<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function search(Request $request)
    {
        $key = $request->get('key', '');
        $sort = $request->get('sort', '');

        $storesQuery = Store::query();
        $productsQuery = Product::query();

        if (!empty($key)) {
            $storesQuery->where('name', 'LIKE', '%' . $key . '%');
            $productsQuery->where('name', 'LIKE', '%' . $key . '%')
                ->with(['mainImage:product_id,path', 'store:id,name'])
                ->select('id', 'name', 'price', 'store_id', 'created_at');

            if ($country = request('country')) {
                $storesQuery->where('location->country', $country);
                $productsQuery->whereHas('store', function ($query) use ($country) {
                    $query->where('location->country', $country);
                });
            }

            if ($city = request('city')) {
                $storesQuery->where('location->city', $city);
                $productsQuery->whereHas('store', function ($query) use ($city) {
                    $query->where('location->city', $city);
                });
            }

            if ($minPrice = request('min_price')) {
                $productsQuery->where('price', '>=', $minPrice);
            }

            if ($maxPrice = request('max_price')) {
                $productsQuery->where('price', '<=', $maxPrice);
            }

            if ($sort === 'price') {
                $products = $productsQuery->orderBy('price', 'asc')->get();
                $stores = $storesQuery->get();
            } elseif ($sort === 'name') {
                $products = $productsQuery->orderBy('name', 'asc')->get();
                $stores = $storesQuery->orderBy('name', 'asc')->get();
            } elseif ($sort === 'created_at') {
                $products = $productsQuery->orderBy('created_at', 'desc')->get();
                $stores = $storesQuery->orderBy('created_at', 'desc')->get();
            } else {
                $products = $productsQuery->get();
                $stores = $storesQuery->get();
            }
        }
        else {
            $products = [];
            $stores = [];
        }

        return response()->json([
            'products' => $products,
            'stores' => $stores,
        ], 200);
    }
    public function autoComplete(Request $request)
    {
        $key = $request->get('key', '');
        $suggestions = [];
        if (!empty($key)) {
            $stores = Store::where('name', 'LIKE', $key . '%')
                ->limit(5)
                ->pluck('name');
            $products = Product::where('name', 'LIKE', $key . '%')
                ->limit(5)
                ->pluck('name');
            $suggestions = $stores->merge($products)->unique()->values()->all();
        }

        return response()->json($suggestions);
    }
}
