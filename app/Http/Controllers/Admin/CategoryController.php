<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index()
    {
        $categories = Category::with('products')->get();
        return $this->apiResponse($categories);
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_uz' => 'required|string|max:255',
            'name_ru' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
        ]);

        $category = Category::create($request->only(['name_uz', 'name_ru', 'name_en']));
        return $this->apiResponse($category);
    }

    /**
     * Display the specified category
     */
    public function show(Category $category)
    {
        $category->load('products');
        return $this->apiResponse($category);
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name_uz' => 'required|string|max:255',
            'name_ru' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
        ]);

        $category->update($request->only(['name_uz', 'name_ru', 'name_en']));
        return $this->apiResponse($category);
    }

    /**
     * Remove the specified category
     */
    public function destroy(Category $category)
    {
        // Detach all products before deleting
        $category->products()->detach();
        $category->delete();
        
        return $this->apiResponse(['message' => 'Category deleted successfully']);
    }
}
