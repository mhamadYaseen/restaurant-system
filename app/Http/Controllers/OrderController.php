<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;
use Illuminate\Http\Request;

class OrderController extends Controller {
    
    // Display all orders
    public function index() {
        $orders = Order::with('orderItems.item')->get();
        return view('orders.index', compact('orders'));
    }

    // Show form to create a new order
    public function create() {
        $items = Item::all();
        return view('orders.create', compact('items'));
    }

    // Store new order
    public function store(Request $request) {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $total_price = 0;
        foreach ($request->items as $item) {
            $product = Item::findOrFail($item['id']);
            $total_price += $product->price * $item['quantity'];
        }

        $order = Order::create([
            'total_price' => $total_price,
            'status' => 'pending',
        ]);

        foreach ($request->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => Item::findOrFail($item['id'])->price,
                'image' => Item::findOrFail($item['id'])->image, // Same image as item
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Order created successfully!');
    }

    // Display a single order
    public function show(Order $order) {
        return view('orders.show', compact('order'));
    }

    // Show edit form
    public function edit(Order $order) {
        $items = Item::all();
        return view('orders.edit', compact('order', 'items'));
    }

    // Update order
    public function update(Request $request, Order $order) {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $total_price = 0;
        $order->orderItems()->delete(); // Remove old order items

        foreach ($request->items as $item) {
            $product = Item::findOrFail($item['id']);
            $total_price += $product->price * $item['quantity'];

            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'image' => $product->image,
            ]);
        }

        $order->update([
            'total_price' => $total_price,
            'status' => $request->status,
        ]);

        return redirect()->route('orders.index')->with('success', 'Order updated successfully!');
    }

    // Delete order
    public function destroy(Order $order) {
        $order->orderItems()->delete();
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully!');
    }
}
