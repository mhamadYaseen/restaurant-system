<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller {
    
    // Display all categories
    public function index() {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    // Show form to create a new category
    public function create() {
        return view('categories.create');
    }

    // Store new category
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category added successfully!');
    }

    // Display a single category
    public function show(Category $category) {
        return view('categories.show', compact('category'));
    }

    // Show edit form
    public function edit(Category $category) {
        return view('categories.edit', compact('category'));
    }

    // Update category
    public function update(Request $request, Category $category) {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }

    // Delete category
    public function destroy(Category $category) {
        if ($category->items()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'Category cannot be deleted because it has associated items.');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }
}
