<!-- filepath: g:\projects\restaurant-system\resources\views\items\menu.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Menu Section (75% width) -->
            <div class="col-lg-9 menu-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold">Our Menu</h2>
                    <div class="category-pills">
                        <button class="btn btn-outline-primary active me-2" onclick="filterCategory('all')">All</button>
                        @foreach ($categories as $category)
                            <button class="btn btn-outline-primary me-2" onclick="filterCategory('{{ $category->id }}')">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                @foreach ($categories as $category)
                    <div class="category-section mb-5" id="category-{{ $category->id }}">
                        <h3 class="category-title mb-4">{{ $category->name }}</h3>
                        <div class="row g-4">
                            @foreach ($category->items as $item)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card menu-item-card h-100">
                                        <div class="menu-item-image">
                                            @if ($item->image)
                                                <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top"
                                                    alt="{{ $item->name }}">
                                            @else
                                                <div class="no-image-placeholder">
                                                    <i class="fas fa-utensils"></i>
                                                </div>
                                            @endif
                                            @if (!$item->available)
                                                <div class="unavailable-badge">
                                                    <span>Out of Stock</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h5 class="card-title mb-0">{{ $item->name }}</h5>
                                                <span class="menu-item-price">${{ number_format($item->price, 2) }}</span>
                                            </div>
                                            <p class="card-text text-muted small">{{ Str::limit($item->description, 60) }}
                                            </p>
                                            @if ($item->available)
                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                    <div class="input-group input-group-sm quantity-control"
                                                        style="width: 120px">
                                                        <button type="button"
                                                            class="btn btn-outline-secondary btn-sm quantity-btn"
                                                            onclick="decreaseQuantity('{{ $item->id }}')">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                        <input type="number" class="form-control text-center item-quantity"
                                                            id="quantity-{{ $item->id }}" value="1" min="1"
                                                            readonly>
                                                        <button type="button"
                                                            class="btn btn-outline-secondary btn-sm quantity-btn"
                                                            onclick="increaseQuantity('{{ $item->id }}')">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                    <button class="btn btn-sm btn-primary add-to-order"
                                                        onclick="addToOrder('{{ $item->id }}', '{{ $item->name }}', {{ $item->price }})">
                                                        <i class="fas fa-plus"></i> Add
                                                    </button>
                                                </div>
                                            @else
                                                <button class="btn btn-sm btn-secondary w-100 mt-3" disabled>
                                                    Unavailable
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Order Panel (25% width) -->
            <div class="col-lg-3">
                <div class="card order-panel sticky-top">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-cart me-2"></i> Your Order
                        </h5>
                    </div>
                    <form id="orderForm" action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div id="order-items">
                                <div class="text-center py-4 empty-cart-message">
                                    <i class="fas fa-shopping-basket fa-3x text-muted mb-3"></i>
                                    <p>Your order is empty</p>
                                    <p class="text-muted small">Select items from the menu to add them to your order</p>
                                </div>
                                <!-- Order items will be dynamically added here -->
                                <!-- Hidden inputs for the order data will be inserted here -->
                                <div id="order-inputs"></div>
                            </div>

                            <div class="order-summary mt-3 pt-3 border-top d-none">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span id="subtotal-amount">$0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 text-muted small">
                                    <span>Tax (10%):</span>
                                    <span id="tax-amount">$0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-0 fw-bold">
                                    <span>Total:</span>
                                    <span id="total-amount">$0.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" id="place-order-btn" class="btn btn-primary w-100" disabled>
                                Place Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>


            @push('scripts')
                <script>
                    let orderItems = [];
                    let subtotal = 0;
                    const taxRate = 0.1; // 10%

                    // Add item to order
                    function addToOrder(id, name, price) {
                        const quantityInput = document.getElementById(`quantity-${id}`);
                        const quantity = parseInt(quantityInput.value);

                        // Check if item already exists in the order
                        const existingItem = orderItems.find(item => item.id === id);

                        if (existingItem) {
                            existingItem.quantity += quantity;
                        } else {
                            orderItems.push({
                                id: id,
                                name: name,
                                price: price,
                                quantity: quantity
                            });
                        }

                        // Reset quantity input
                        quantityInput.value = 1;

                        // Update order display
                        updateOrderDisplay();
                    }

                    // Remove item from order
                    function removeFromOrder(id) {
                        orderItems = orderItems.filter(item => item.id !== id);
                        updateOrderDisplay();
                    }

                    // Update quantity of an item in order
                    function updateOrderQuantity(id, newQuantity) {
                        const item = orderItems.find(item => item.id === id);
                        if (item) {
                            if (newQuantity <= 0) {
                                removeFromOrder(id);
                            } else {
                                item.quantity = newQuantity;
                                updateOrderDisplay();
                            }
                        }
                    }

                    // Update the order display
                    function updateOrderDisplay() {
                        const orderContainer = document.getElementById('order-items');
                        const emptyMessage = document.querySelector('.empty-cart-message');
                        const orderSummary = document.querySelector('.order-summary');
                        const placeOrderBtn = document.getElementById('place-order-btn');
                        const orderInputsContainer = document.getElementById('order-inputs');

                        // Clear current content except the empty message and inputs container
                        Array.from(orderContainer.children).forEach(child => {
                            if (!child.classList.contains('empty-cart-message') && child.id !== 'order-inputs') {
                                child.remove();
                            }
                        });

                        // Clear hidden inputs
                        orderInputsContainer.innerHTML = '';

                        if (orderItems.length === 0) {
                            // Show empty message
                            emptyMessage.classList.remove('d-none');
                            orderSummary.classList.add('d-none');
                            placeOrderBtn.disabled = true;
                            return;
                        }

                        // Hide empty message and show order summary
                        emptyMessage.classList.add('d-none');
                        orderSummary.classList.remove('d-none');
                        placeOrderBtn.disabled = false;

                        // Calculate subtotal
                        subtotal = 0;

                        // Add each order item
                        orderItems.forEach((item, index) => {
                            const itemTotal = item.price * item.quantity;
                            subtotal += itemTotal;

                            // Create hidden inputs for form submission
                            const idInput = document.createElement('input');
                            idInput.type = 'hidden';
                            idInput.name = `items[${index}][id]`;
                            idInput.value = item.id;
                            orderInputsContainer.appendChild(idInput);

                            const quantityInput = document.createElement('input');
                            quantityInput.type = 'hidden';
                            quantityInput.name = `items[${index}][quantity]`;
                            quantityInput.value = item.quantity;
                            orderInputsContainer.appendChild(quantityInput);

                            const orderItemElement = document.createElement('div');
                            orderItemElement.className = 'order-item';
                            orderItemElement.innerHTML = `
            <div class="d-flex justify-content-between">
                <h6 class="mb-0">${item.name}</h6>
                <button type="button" class="btn btn-sm text-danger p-0" onclick="removeFromOrder('${item.id}')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-2">
                <div class="input-group input-group-sm" style="width: 100px">
                    <button type="button" class="btn btn-outline-secondary btn-sm" 
                        onclick="updateOrderQuantity('${item.id}', ${item.quantity - 1})">
                        <i class="fas fa-minus"></i>
                    </button>
                    <input type="number" class="form-control text-center" value="${item.quantity}" min="1" readonly>
                    <button type="button" class="btn btn-outline-secondary btn-sm" 
                        onclick="updateOrderQuantity('${item.id}', ${item.quantity + 1})">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <span>$${itemTotal.toFixed(2)}</span>
            </div>
        `;

                            orderContainer.insertBefore(orderItemElement, orderInputsContainer);
                        });

                        // Update summary amounts
                        const tax = subtotal * taxRate;
                        const total = subtotal + tax;

                        document.getElementById('subtotal-amount').textContent = `$${subtotal.toFixed(2)}`;
                        document.getElementById('tax-amount').textContent = `$${tax.toFixed(2)}`;
                        document.getElementById('total-amount').textContent = `$${total.toFixed(2)}`;
                    }

                    // Replace the placeOrder function with a form submission handler
                    document.addEventListener('DOMContentLoaded', function() {
                        // Add form submission handler
                        const orderForm = document.getElementById('orderForm');
                        orderForm.addEventListener('submit', function(event) {
                            if (orderItems.length === 0) {
                                event.preventDefault();
                                return;
                            }

                            // Show loading state
                            const placeOrderBtn = document.getElementById('place-order-btn');
                            placeOrderBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                            placeOrderBtn.disabled = true;
                        });
                    });
                    // Increase quantity on menu item
                    function increaseQuantity(id) {
                        const quantityInput = document.getElementById(`quantity-${id}`);
                        quantityInput.value = parseInt(quantityInput.value) + 1;
                    }

                    // Decrease quantity on menu item
                    function decreaseQuantity(id) {
                        const quantityInput = document.getElementById(`quantity-${id}`);
                        if (parseInt(quantityInput.value) > 1) {
                            quantityInput.value = parseInt(quantityInput.value) - 1;
                        }
                    }

                    // Filter menu by category
                    function filterCategory(categoryId) {
                        // Update active button
                        const buttons = document.querySelectorAll('.category-pills .btn');
                        buttons.forEach(btn => btn.classList.remove('active'));

                        const clickedButton = event.currentTarget;
                        clickedButton.classList.add('active');

                        // Show/hide categories
                        const categories = document.querySelectorAll('.category-section');

                        if (categoryId === 'all') {
                            categories.forEach(cat => cat.style.display = 'block');
                        } else {
                            categories.forEach(cat => {
                                if (cat.id === `category-${categoryId}`) {
                                    cat.style.display = 'block';
                                } else {
                                    cat.style.display = 'none';
                                }
                            });
                        }
                    }

                    // Place order function
                    function placeOrder() {
                        if (orderItems.length === 0) return;

                        // Example: Send order data to server
                        const orderData = {
                            items: orderItems.map(item => ({
                                id: item.id,
                                quantity: item.quantity
                            })),
                            subtotal: subtotal,
                            tax: subtotal * taxRate,
                            total: subtotal + (subtotal * taxRate)
                        };

                        console.log('Placing order:', orderData);

                        // Show loading state
                        const placeOrderBtn = document.getElementById('place-order-btn');
                        const originalText = placeOrderBtn.innerHTML;
                        placeOrderBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                        placeOrderBtn.disabled = true;

                        // Simulate API call (replace with actual Ajax request)
                        setTimeout(() => {
                            // Reset order
                            orderItems = [];
                            updateOrderDisplay();

                            // Show success message
                            alert('Your order has been placed successfully!');

                            // Reset button
                            placeOrderBtn.innerHTML = originalText;
                            placeOrderBtn.disabled = false;
                        }, 1500);
                    }
                </script>
            @endpush
            @push('styles')
                <style>
                    .menu-section {
                        transition: all 0.3s ease;
                    }

                    .category-pills {
                        overflow-x: auto;
                        white-space: nowrap;
                        padding-bottom: 5px;
                    }

                    .category-title {
                        position: relative;
                        display: inline-block;
                        margin-left: 15px;
                        color: var(--bs-primary);
                        font-family: 'Playfair Display', serif;
                    }

                    .category-title:before {
                        content: "";
                        position: absolute;
                        left: -15px;
                        top: 0;
                        bottom: 0;
                        width: 5px;
                        background-color: var(--bs-primary);
                        border-radius: 3px;
                    }

                    .menu-item-card {
                        border-radius: 0.75rem;
                        overflow: hidden;
                        transition: transform 0.2s ease, box-shadow 0.2s ease;
                        border: none;
                        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
                    }

                    .menu-item-card:hover {
                        transform: translateY(-5px);
                        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
                    }

                    .menu-item-image {
                        height: 180px;
                        position: relative;
                        overflow: hidden;
                        background-color: #f8f8f8;
                    }

                    .menu-item-image img {
                        object-fit: cover;
                        height: 100%;
                        width: 100%;
                    }

                    .no-image-placeholder {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        height: 100%;
                        background-color: #f0f0f0;
                        color: #aaa;
                        font-size: 2rem;
                    }

                    .unavailable-badge {
                        position: absolute;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        background-color: rgba(0, 0, 0, 0.6);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }

                    .unavailable-badge span {
                        background-color: var(--bs-danger);
                        color: white;
                        padding: 5px 15px;
                        border-radius: 20px;
                        transform: rotate(-15deg);
                        font-weight: bold;
                    }

                    .menu-item-price {
                        font-weight: 600;
                        color: var(--bs-accent);
                        font-size: 1.1rem;
                    }

                    .quantity-control {
                        max-width: 100px;
                    }

                    .order-panel {
                        max-height: 90vh;
                        top: 1rem;
                    }

                    .order-panel .card-body {
                        max-height: calc(90vh - 120px);
                        overflow-y: auto;
                    }

                    .order-item {
                        border-bottom: 1px dashed rgba(0, 0, 0, 0.1);
                        padding-bottom: 10px;
                        margin-bottom: 10px;
                    }

                    .order-item:last-child {
                        border-bottom: none;
                    }
                </style>
            @endpush
        @endsection
