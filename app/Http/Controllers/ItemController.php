<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller {
    
    // Display all items
    public function index() {
        $items = Item::all();
        return view('items.index', compact('items'));
    }

    // Show form to create a new item
    public function create() {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    // Store new item
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('items', 'public');
        }

        Item::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'image' => $imagePath,
            'available' => $request->has('available') ? true : false,
        ]);

        return redirect()->route('items.index')->with('success', 'Item added successfully!');
    }

    // Display a single item
    public function show(Item $item) {
        return view('items.show', compact('item'));
    }

    // Show edit form
    public function edit(Item $item) {
        $categories = Category::all();
        return view('items.edit', compact('item', 'categories'));
    }

    // Update item
    public function update(Request $request, Item $item) {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload if new file is provided
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $item->image = $request->file('image')->store('items', 'public');
        }

        // Update item details
        $item->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'available' => $request->has('available') ? true : false,
        ]);

        return redirect()->route('items.index')->with('success', 'Item updated successfully!');
    }

    // Delete item
    public function destroy(Item $item) {
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted successfully!');
    }


    // Display menu
    public function menu() {
        $categories = Category::with('items')->get();
        return view('items.menu', compact('categories'));
    }

    public function createOrder() {
        $items = Item::all();
        return view('orders.create', compact('items'));
    }
}
