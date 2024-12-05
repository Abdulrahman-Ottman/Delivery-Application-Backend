<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getAllCategories(){
        $categories = null;
        $categories = Category::where('parent_id' , null)->get();
        if ($categories == null) {
            return response()->json([
                'message' => 'No categories found.',
                'data' => []
            ], 404);
        }

        return response()->json([
            'message' => 'Categories retrieved successfully.',
            'data' => $categories
        ]);
    }
    public function getSubcategoriesByCategory($categoryID){
        $category = Category::where('id',$categoryID)->where('parent_id', null)->first();

        if (!$category) {
            return response()->json([
                'message' => 'Category not found.'
            ], 404);
        }

        $subcategories = $category->subcategories;

        if ($subcategories->isEmpty()) {
            return response()->json([
                'message' => 'No subcategories found for this category.',
                'data' => []
            ],404);
        }

        return response()->json([
            'message' => 'Subcategories retrieved successfully.',
            'data' => $subcategories
        ],200);
    }
}
