<!-- filepath: g:\projects\restaurant-system\resources\views\items\menu.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Add mobile navigation tabs -->
        <div class="d-lg-none mobile-nav-tabs">
            <ul class="nav nav-pills nav-fill">
                <li class="nav-item">
                    <a class="nav-link active" id="menu-tab" data-bs-toggle="pill" href="#menu-content" role="tab">
                        <i class="fas fa-utensils me-2"></i> Menu
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="order-tab" data-bs-toggle="pill" href="#order-content" role="tab">
                        <i class="fas fa-shopping-cart me-2"></i> Your Order
                        <span class="badge bg-primary ms-1 order-count d-none">0</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Mobile content wrapper -->
        <div class="tab-content d-lg-none">
            <div class="tab-pane fade show active" id="menu-content" role="tabpanel">
                <!-- Menu content will be dynamically cloned here by JS -->
            </div>
            <div class="tab-pane fade" id="order-content" role="tabpanel">
                <!-- Order panel will be dynamically cloned here by JS -->
            </div>
        </div>

        <!-- Original desktop layout -->
        <div class="row original-layout">
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
                                                        <input type="text" class="form-control text-center item-quantity"
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
                <div class="card order-panel sticky-top ">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-cart me-2"></i> Your Order
                        </h5>
                    </div>
                    @if (Auth::check() && Auth::user()->role != 'pending')
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
                    @endif

                    @guest
                        <div class="card-body">
                            <div class="text-center py-4">
                                <i class="fas fa-user fa-3x text-muted mb-3"></i>
                                <p>You must be logged in to place an order</p>
                                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                            </div>
                        </div>
                    @endguest

                    @if (Auth::check() && Auth::user()->role == 'pending')
                        <div class="card-body">
                            <div class="text-center py-4">
                                <i class="fas fa-user-clock fa-3x text-warning mb-3"></i>
                                <p>Your account is pending approval</p>
                                <p class="text-muted small">You'll be able to place orders once your account is approved
                                </p>

                                <a href="{{ route('menu') }}" class="btn btn-outline-primary mt-3">
                                    <i class="fas fa-utensils me-2"></i> Browse Menu
                                </a>

                            </div>

                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @include('orders.receipt')
@endsection
