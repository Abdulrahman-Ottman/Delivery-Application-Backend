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

        if (!empty($key)) {
            $stores = Store::where('name', 'LIKE', '%' . $key . '%')->get();
            $products = Product::select('id', 'name', 'price','store_id')->with(['mainImage:product_id,path','store:id,name'])->where('name', 'LIKE', '%' . $key . '%')->get();
        } else {
            $stores = [];
            $products = [];
        }

        return response()->json([
            'products' => $products,
            'stores' => $stores,
        ],200);
    }
}
