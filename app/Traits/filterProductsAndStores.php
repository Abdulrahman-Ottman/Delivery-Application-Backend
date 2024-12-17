<?php

namespace App\Traits;

use App\Models\Product;
use App\Models\Store;

trait filterProductsAndStores
{
    public function filterProductsAndStores($request, $productsQuery = null, $storesQuery = null)
    {
        $key = $request->get('key', '');

        if ($productsQuery && !empty($key)) {
            $productsQuery->where('name', 'LIKE', '%' . $key . '%')
                ->with(['mainImage:product_id,path', 'store:id,name'])
                ->select('id', 'name', 'price', 'store_id', 'created_at');
        }

        if ($storesQuery && !empty($key)) {
            $storesQuery->where('name', 'LIKE', '%' . $key . '%');
        }

        if ($country = $request->get('country')) {
            if ($storesQuery) {
                $storesQuery->where('location->country', $country);
            }

            if ($productsQuery) {
                $productsQuery->whereHas('store', function ($query) use ($country) {
                    $query->where('location->country', $country);
                });
            }
        }

        if ($city = $request->get('city')) {
            if ($storesQuery) {
                $storesQuery->where('location->city', $city);
            }

            if ($productsQuery) {
                $productsQuery->whereHas('store', function ($query) use ($city) {
                    $query->where('location->city', $city);
                });
            }
        }

        if ($minPrice = $request->get('min_price')) {
            if ($productsQuery) {
                $productsQuery->where('price', '>=', $minPrice);
            }
        }

        if ($maxPrice = $request->get('max_price')) {
            if ($productsQuery) {
                $productsQuery->where('price', '<=', $maxPrice);
            }
        }

        return compact('productsQuery', 'storesQuery');
    }}
