<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // Display all orders - FIX THE ADMIN CHECK
    public function index(Request $request)
    {
        $query = Order::with(['orderItems.item', 'user'])->latest()->get();;

        // Filter by user if specified (admin only)
        if ($request->filled('user_id') && Auth::user()->role === 'admin') {
            $query->where('user_id', $request->user_id);
        }

        // Non-admin users can only see their own orders
        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        $orders = $query;

        // Only load users for dropdown if admin
        $users = Auth::user()->role === 'admin' ? User::where('role', '!=', 'pending')->get() : null;

        return view('orders.index', compact('orders', 'users'));
    }

    // Show form to create a new order
    public function create()
    {
        // Only users with roles can create orders from admin panel
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'user') {
            return redirect()->route('home')->with('error', 'You do not have permission to access this page.');
        }

        $items = Item::where('available', true)->get();
        return view('orders.create', compact('items'));
    }

    // Store new order
    public function store(Request $request)
    {
        // Basic validation
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'exists:items,id',
            'items.*.quantity' => 'integer|min:1|max:50', // Prevent unreasonable quantities
        ]);

        // Security: Normalize the items array based on its format
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
                // Cast to integers to prevent injection
                $normalizedItems[] = [
                    'id' => (int) $item['id'],
                    'quantity' => (int) $item['quantity']
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
        $itemsWithDetails = [];

        try {
            // Calculate total price and gather item details
            foreach ($normalizedItems as $item) {
                // Security: Verify item exists and is available
                $product = Item::where('id', $item['id'])->where('available', true)->firstOrFail();
                $itemTotal = $product->price * $item['quantity'];
                $total_price += $itemTotal;

                $itemsWithDetails[] = [
                    'id' => $item['id'],
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'total' => $itemTotal
                ];
            }

            // Begin transaction to ensure data integrity
            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_price' => $total_price,
                'status' => 'completed',
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

            DB::commit();

            // For AJAX requests (from the menu page)
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Your order has been placed successfully!',
                    'receipt' => [
                        'order_id' => $order->id,
                        'date' => $order->created_at->format('M d, Y'),
                        'time' => $order->created_at->format('g:i A'),
                        'items' => $itemsWithDetails,
                        'subtotal' => $total_price,
                        'tax' => $total_price * 0.1,
                        'total' => $total_price + ($total_price * 0.1)
                    ]
                ]);
            }

            // For regular requests
            return redirect()->route('orders.index')
                ->with('success', 'Your order has been placed successfully!')
                ->with('order_id', $order->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while processing your order. Please try again.')
                ->withInput();
        }
    }

    // Show specific order - ADD SECURITY CHECK
    public function show(Order $order)
    {
        // Authorize access - only admin or the order owner can view
        if (Auth::user()->role !== 'admin' && Auth::id() !== $order->user_id) {
            abort(403, 'Unauthorized access');
        }

        $order->load(['orderItems.item', 'user']);
        return view('orders.show', compact('order'));
    }

    // User's order history
    public function orders()
    {
        $orders = Auth::user()->orders()->with('orderItems.item')->latest()->paginate(10);
        return view('profile.orders', compact('orders'));
    }

    // Show edit form - ADD SECURITY CHECK
    public function edit(Order $order)
    {
        // Only admin or the order owner can edit
        if (Auth::user()->role !== 'admin' && Auth::id() !== $order->user_id) {
            abort(403, 'Unauthorized access');
        }

        // Users can only edit pending orders
        if (Auth::user()->role !== 'admin' && $order->status !== 'pending') {
            return redirect()->route('orders.index')
                ->with('error', 'Only pending orders can be modified.');
        }

        $items = Item::where('available', true)->get();
        return view('orders.edit', compact('order', 'items'));
    }

    // Update order - ADD SECURITY CHECKS
    public function update(Request $request, Order $order)
    {
        // Only admin or the order owner can update
        if (Auth::user()->role !== 'admin' && Auth::id() !== $order->user_id) {
            abort(403, 'Unauthorized access');
        }

        // Users can only update pending orders
        if (Auth::user()->role !== 'admin' && $order->status !== 'pending') {
            return redirect()->route('orders.index')
                ->with('error', 'Only pending orders can be modified.');
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'exists:items,id',
            'items.*.quantity' => 'required|integer|min:1|max:50',
            'status' => [
                Rule::requiredIf(Auth::user()->role === 'admin'),
                Rule::in(['pending', 'completed', 'cancelled']),
            ],
        ]);

        // Filter out any items that don't have an ID
        $validItems = collect($request->items)
            ->filter(function ($item) {
                return isset($item['id']);
            });

        if ($validItems->isEmpty()) {
            return redirect()->back()
                ->with('error', 'Please select at least one item for the order.');
        }

        try {
            DB::beginTransaction();

            $total_price = 0;
            $order->orderItems()->delete(); // Remove old order items

            foreach ($validItems as $item) {
                // Security: Verify item exists and is available
                $product = Item::where('id', $item['id'])->where('available', true)->firstOrFail();
                $total_price += $product->price * $item['quantity'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'image' => $product->image,
                ]);
            }

            // Only update status if admin or if user is changing to 'cancelled'
            $status = $order->status;
            if (Auth::user()->role === 'admin') {
                $status = $request->status;
            } elseif ($request->has('status') && $request->status === 'cancelled') {
                $status = 'cancelled';
            }

            $order->update([
                'total_price' => $total_price,
                'status' => $status,
            ]);

            DB::commit();

            return redirect()->route('orders.index')
                ->with('success', 'Order updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order update failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while updating your order. Please try again.');
        }
    }

    // Delete order - ADD SECURITY CHECKS
    public function destroy(Order $order)
    {
        // Only admin or the owner of a pending order can delete
        if (
            Auth::user()->role !== 'admin' &&
            (Auth::id() !== $order->user_id || $order->status !== 'pending')
        ) {
            abort(403, 'Unauthorized access');
        }

        try {
            DB::beginTransaction();
            $order->orderItems()->delete();
            $order->delete();
            DB::commit();

            return redirect()->route('orders.index')
                ->with('success', 'Order deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order deletion failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while deleting the order.');
        }
    }
}
