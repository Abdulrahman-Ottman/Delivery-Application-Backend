<?php

namespace App\Traits;

trait sortProductsAndStores
{
    public function sortProductsAndStores($sortBy, $productsQuery, $storesQuery)
    {

        if ($sortBy === 'price') {
            $productsQuery->orderBy('price', 'asc');
            $storesQuery->orderBy('price', 'asc');
        } elseif ($sortBy === 'name') {
            $productsQuery->orderBy('name', 'asc');
            $storesQuery->orderBy('name', 'asc');
        } elseif ($sortBy === 'created_at') {
            $productsQuery->orderBy('created_at', 'desc');
            $storesQuery->orderBy('created_at', 'desc');
        }

        return compact('productsQuery', 'storesQuery');
    }
}
