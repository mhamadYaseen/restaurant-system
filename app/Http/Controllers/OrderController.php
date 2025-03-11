<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    // Display all orders
    public function index()
    {
        $orders = Order::with(['orderItems.item'])->latest()->get();
        return view('orders.index', compact('orders'));
    }

    // Show form to create a new order
    public function create()
    {
        $items = Item::all();
        return view('orders.create', compact('items'));
    }

    // Store new order
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
        ]);

        // Normalize the items array based on its format
        // Format from menu: items[0][id], items[0][quantity]
        // Format from create: items[item_id][id], items[item_id][quantity]
        $normalizedItems = [];

        foreach ($request->items as $key => $item) {
            // Skip items without an ID (unchecked checkboxes in create form)
            if (!isset($item['id']) || empty($item['id'])) {
                continue;
            }

            // Only include items with quantity
            if (isset($item['quantity']) && $item['quantity'] > 0) {
                $normalizedItems[] = [
                    'id' => $item['id'],
                    'quantity' => $item['quantity']
                ];
            }
        }

        // Check if any items remain after filtering
        if (empty($normalizedItems)) {
            return redirect()->back()
                ->with('error', 'Please select at least one item for the order.')
                ->withInput();
        }

        $total_price = 0;

        // Calculate total price
        foreach ($normalizedItems as $item) {
            $product = Item::findOrFail($item['id']);
            $total_price += $product->price * $item['quantity'];
        }

        // Create order
        $order = Order::create([
            'total_price' => $total_price,
            'status' => 'pending',
        ]);

        // Create order items
        foreach ($normalizedItems as $item) {
            $product = Item::findOrFail($item['id']);

            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'image' => $product->image,
            ]);
        }

        // Determine where to redirect based on referer
        if (str_contains(url()->previous(), 'menu')) {
            return redirect()->route('orders.index')
                ->with('success', 'Your order has been placed successfully!');
        } else {
            return redirect()->route('orders.index')
                ->with('success', 'Order created successfully!');
        }
    }

    // Display a single order
    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    // Show edit form
    public function edit(Order $order)
    {
        $items = Item::all();
        return view('orders.edit', compact('order', 'items'));
    }

    // Update order
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        // Filter out any items that don't have an ID
        $validItems = collect($request->items)
            ->filter(function ($item) {
                return isset($item['id']);
            });

        if ($validItems->isEmpty()) {
            return redirect()->back()->with('error', 'Please select at least one item for the order.');
        }

        $total_price = 0;
        $order->orderItems()->delete(); // Remove old order items

        foreach ($validItems as $item) {
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
    public function destroy(Order $order)
    {
        $order->orderItems()->delete();
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully!');
    }
}
