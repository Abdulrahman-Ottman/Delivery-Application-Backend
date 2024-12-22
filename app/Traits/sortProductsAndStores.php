<?php

namespace App\Traits;

trait sortProductsAndStores
{
    public function sortProductsAndStores($sortBy, $productsQuery, $storesQuery)
    {

        if($productsQuery) {
            if ($sortBy === 'price') {
                //add desc
                $productsQuery->orderBy('price', 'asc');
                //
            } elseif ($sortBy === 'name') {
                $productsQuery->orderBy('name', 'asc');
            } elseif ($sortBy === 'created_at') {
                $productsQuery->orderBy('created_at', 'desc');
            }
        }
        if($storesQuery){
            if ($sortBy === 'name') {
                $storesQuery->orderBy('name', 'asc');
            } elseif ($sortBy === 'created_at') {
                $storesQuery->orderBy('created_at', 'desc');
            }
        }

        return compact('productsQuery', 'storesQuery');
    }
}
