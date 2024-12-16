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
    public function __invoke()
    {
        $key = request('key', '');

        $storesQuery = Store::query();
        $productsQuery = Product::query();

        if (!empty($key)) {
            $storesQuery->where('name', 'LIKE', '%' . $key . '%');
            $productsQuery->where('name', 'LIKE', '%' . $key . '%');
        }

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

        // excute queries
        $stores = $storesQuery->get();
        $products = $productsQuery->select('id', 'name', 'price', 'store_id')
            ->with(['mainImage:product_id,path', 'store:id,name'])
            ->get();

        return response()->json([
            'products' => $products,
            'stores' => $stores,
        ], 200);
    }
}
