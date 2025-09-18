<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // Show category list & form
    public function index()
    {
        $categories = Category::orderBy('created_at', 'desc')->get();
        return view('categories.index', compact('categories'));
    }

    // Store new category
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name',
            'active'        => 'required|boolean',
        ]);

        Category::create([
            'category_key'  => Str::slug($validated['category_name']),
            'category_name' => $validated['category_name'],
            'active'        => $validated['active'],
            'created_by'    => auth()->id() ?? 1, // fallback if not logged in
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category added successfully!');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name,' . $category->category_id . ',category_id',
            'active'        => 'required|boolean',
        ]);

        $category->update([
            'category_key'  => Str::slug($validated['category_name']),
            'category_name' => $validated['category_name'],
            'active'        => $validated['active'],
            'updated_by'    => auth()->id(),
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        // Optional: Check if the category is being used by any QR codes
        if ($category->qrData()->exists()) {
            return redirect()->route('categories.index')
                ->with('error', 'Cannot delete this category because it is associated with one or more QR codes.');
        }
        
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}
