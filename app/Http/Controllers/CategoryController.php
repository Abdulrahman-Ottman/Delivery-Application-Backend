<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Validator;

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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'color' => 'required',
            'parent_id' => 'exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()){
            return response()->json([
                'message' => "Failed to add category.",
                'data' =>$validator->errors()
            ],401);
        }

        $path = $request->file('image')->store('images/categories', 'public');
        $path = 'storage/' . str_replace("public/", "", $path);

        Category::create([
            'name' => $request->get('name'),
            'color' => $request->get('color'),
            'parent_id' => $request->get('parent_id'),
            'image' => $path
        ]);

        return response()->json([
            'message' => 'Category created successfully.',
        ], 201);
    }
}
