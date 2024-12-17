<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use App\Traits\filterProductsAndStores;
use App\Traits\sortProductsAndStores;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    use filterProductsAndStores , sortProductsAndStores;
    public function search(Request $request)
    {
        if (!$request->has('key') || $request->get('key') == null) {
            return response()->json([
                'message' => 'The "key" query parameter is required.'
            ], 400);
        }

        $storesQuery = Store::query();
        $productsQuery = Product::query();

        $filteredData = $this->filterProductsAndStores($request, $productsQuery, $storesQuery);
        $productsQuery = $filteredData['productsQuery'];
        $storesQuery = $filteredData['storesQuery'];


        $sortBy = $request->get('sort');
        if($sortBy){
            $sortedData = $this->sortProductsAndStores($sortBy, $productsQuery, $storesQuery);
            $productsQuery = $sortedData['productsQuery'];
            $storesQuery = $sortedData['storesQuery'];
        }

        $products = $productsQuery->get();
        $stores = $storesQuery->get();

        if ($products->isEmpty() && $stores->isEmpty()) {
            return response()->json([
                'message' => 'No products or stores found.',
            ], 404);
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
