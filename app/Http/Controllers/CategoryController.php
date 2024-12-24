<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getAllCategories(){

        $categories = Category::where('parent_id' , null)->get();
        if ($categories->isEmpty()) {
            return response()->json([
                'message' => 'No categories found.',
                'data' => []
            ], 404);
        }
        $data = $this->buildCategoryHierarchy($categories);

        return response()->json([
            'message' => 'Categories retrieved successfully.',
            'data' => $data
        ]);
    }

    private function buildCategoryHierarchy($categories)
    {
        $result = [];

        foreach ($categories as $category) {
            $result[] = [
                'id' => $category->id,
                'name' => $category->name,
                'color' => $category->color,
                'parent_id' => $category->parent_id,
                'image' => $category->image,
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at,
                'subcategories' => $this->buildCategoryHierarchy($category->subcategories)
            ];
        }

        return $result;
    }

}
