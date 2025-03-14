<!-- filepath: g:\projects\restaurant-system\resources\views\welcome.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col">
                <h2 class="fw-bold mb-0">Dashboard</h2>
                <p class="text-muted">Welcome to your restaurant management system</p>
            </div>
            <div class="col-auto">
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="timeRangeDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-calendar-alt me-2"></i> <span id="selected-range">Today</span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="timeRangeDropdown">
                        <li><a class="dropdown-item active" href="#" data-range="today">Today</a></li>
                        <li><a class="dropdown-item" href="#" data-range="yesterday">Yesterday</a></li>
                        <li><a class="dropdown-item" href="#" data-range="last7">Last 7 days</a></li>
                        <li><a class="dropdown-item" href="#" data-range="thismonth">This month</a></li>
                        <li><a class="dropdown-item" href="#" data-range="lastmonth">Last month</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-primary bg-gradient rounded-circle p-3 text-white">
                                    <i class="fas fa-receipt fa-fw"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-0 text-muted">Total Orders</h6>
                                <h3 class="mb-0">{{ App\Models\Order::count() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 py-2">
                        <div class="d-flex align-items-center text-success">
                            <i class="fas fa-arrow-up me-1"></i>
                            <span class="small">
                                {{ number_format((App\Models\Order::whereDate('created_at', \Carbon\Carbon::today())->count() / max(1, App\Models\Order::count())) * 100, 1) }}%
                                today
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-success bg-gradient rounded-circle p-3 text-white">
                                    <i class="fas fa-money-bill-wave fa-fw"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-0 text-muted">Revenue</h6>
                                <h3 class="mb-0">${{ number_format(App\Models\Order::sum('total_price'), 2) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 py-2">
                        <div class="d-flex align-items-center text-success">
                            <i class="fas fa-arrow-up me-1"></i>
                            <span class="small">
                                ${{ number_format(App\Models\Order::whereDate('created_at', \Carbon\Carbon::today())->sum('total_price'), 2) }}
                                today
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-info bg-gradient rounded-circle p-3 text-white">
                                    <i class="fas fa-utensils fa-fw"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-0 text-muted">Menu Items</h6>
                                <h3 class="mb-0">{{ App\Models\Item::count() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 py-2">
                        <div class="d-flex align-items-center">
                            <span class="small text-muted">
                                In {{ App\Models\Category::count() }} categories
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-warning bg-gradient rounded-circle p-3 text-white">
                                    <i class="fas fa-user-friends fa-fw"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-0 text-muted">Users</h6>
                                <h3 class="mb-0">{{ App\Models\User::count() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 py-2">
                        <div class="d-flex align-items-center">
                            <span class="small text-muted">
                                {{ App\Models\User::where('created_at', '>=', now()->subDays(30))->count() }} new in 30 days
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <!-- Orders Chart -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Orders Overview</h5>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-secondary active" id="weekly-view">Weekly</button>
                            <button type="button" class="btn btn-outline-secondary" id="monthly-view">Monthly</button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <canvas id="ordersChart" style="height: 300px" class="w-100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Popular Items -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0">Popular Items</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @php
                                $popularItems = \DB::table('order_items')
                                    ->select('item_id', \DB::raw('SUM(quantity) as total_quantity'))
                                    ->groupBy('item_id')
                                    ->orderByDesc('total_quantity')
                                    ->limit(5)
                                    ->get();

                                $itemDetails = App\Models\Item::whereIn('id', $popularItems->pluck('item_id'))
                                    ->get()
                                    ->keyBy('id');
                            @endphp

                            @foreach ($popularItems as $item)
                                @if (isset($itemDetails[$item->item_id]))
                                    @php $itemDetail = $itemDetails[$item->item_id]; @endphp
                                    <div class="list-group-item d-flex align-items-center">
                                        <div class="me-3">
                                            @if ($itemDetail->image)
                                                <div style="width:48px; height:48px; overflow:hidden; border-radius:8px;">
                                                    <img src="{{ asset('storage/' . $itemDetail->image) }}"
                                                        class="img-fluid" alt="{{ $itemDetail->name }}">
                                                </div>
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center"
                                                    style="width:48px; height:48px; border-radius:8px;">
                                                    <i class="fas fa-utensils text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">{{ $itemDetail->name }}</h6>
                                            <small
                                                class="text-muted">{{ Str::limit($itemDetail->description, 60) }}</small>
                                        </div>
                                        <div class="ms-2">
                                            <span class="badge bg-primary">{{ $item->total_quantity }} sold</span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 text-center">
                        <a href="{{ route('items.index') }}" class="btn btn-sm btn-outline-primary">View All Items</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <!-- Recent Orders -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0">Recent Orders</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (App\Models\Order::latest()->take(5)->get() as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->orderItems->sum('quantity') }} items</td>
                                            <td>${{ number_format($order->total_price, 2) }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $order->created_at->format('M d, g:i A') }}</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 text-center">
                        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary">View All Orders</a>
                    </div>
                </div>
            </div>

            <!-- Category Distribution -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0">Category Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="categoryChart" style="height: 250px" class="mb-3"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            @php
                // Weekly data (last 7 days)
                $dates = [];
                $orderCounts = [];
                $revenues = [];

                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $dates[] = $date->format('D'); // Short day name

                    // Get order count for this day
                    $orderCount = App\Models\Order::whereDate('created_at', $date->format('Y-m-d'))->count();
                    $orderCounts[] = $orderCount;

                    // Get revenue for this day
                    $revenue = App\Models\Order::whereDate('created_at', $date->format('Y-m-d'))->sum('total_price');
                    $revenues[] = $revenue;
                }

                // Monthly data (last 30 days)
                $monthlyDates = [];
                $monthlyOrderCounts = [];
                $monthlyRevenues = [];

                for ($i = 29; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $monthlyDates[] = $date->format('M d'); // Month and day

                    // Get order count for this day
                    $orderCount = App\Models\Order::whereDate('created_at', $date->format('Y-m-d'))->count();
                    $monthlyOrderCounts[] = $orderCount;

                    // Get revenue for this day
                    $revenue = App\Models\Order::whereDate('created_at', $date->format('Y-m-d'))->sum('total_price');
                    $monthlyRevenues[] = $revenue;
                }

                // Category data
                $categories = App\Models\Category::withCount('items')->get();
                $categoryNames = $categories->pluck('name')->toArray();
                $categoryItemCounts = $categories->pluck('items_count')->toArray();
            @endphp

            window.dashboardData = {
                // Weekly data
                dates: @json($dates),
                orderCounts: @json($orderCounts),
                revenues: @json($revenues),

                // Monthly data
                monthlyDates: @json($monthlyDates),
                monthlyOrderCounts: @json($monthlyOrderCounts),
                monthlyRevenues: @json($monthlyRevenues),

                // Category data
                categoryNames: @json($categoryNames),
                categoryItemCounts: @json($categoryItemCounts)
            };
        </script>
        <script src="{{ asset('js/dashboard.js') }}"></script>
    @endpush
@endsection
