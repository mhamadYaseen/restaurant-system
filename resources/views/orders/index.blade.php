<!-- filepath: g:\projects\restaurant-system\resources\views\orders\index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="fw-bold">Orders</h2>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('orders.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> New Order
                </a>
            </div>
        </div>

        @php
            // Group orders by date
            $groupedOrders = $orders->groupBy(function($order) {
                return $order->created_at->format('Y-m-d');
            });
        @endphp

        @forelse($groupedOrders as $date => $ordersForDay)
            <div class="date-section mb-4">
                @php
                    $carbonDate = \Carbon\Carbon::parse($date);
                    $isToday = $carbonDate->isToday();
                    $isYesterday = $carbonDate->isYesterday();
                @endphp
                
                <div class="date-header d-flex align-items-center mb-3">
                    <div class="date-badge rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3">
                        <div class="text-center">
                            <div class="small">{{ $carbonDate->format('M') }}</div>
                            <div class="fw-bold">{{ $carbonDate->format('d') }}</div>
                        </div>
                    </div>
                    <h4 class="m-0">
                        @if($isToday)
                            Today
                        @elseif($isYesterday)
                            Yesterday
                        @else
                            {{ $carbonDate->format('l, F j, Y') }}
                        @endif
                        <span class="badge bg-light text-dark ms-2">{{ $ordersForDay->count() }} orders</span>
                    </h4>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order #</th>
                                        <th>Time</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ordersForDay as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->created_at->format('g:i A') }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    {{ $order->orderItems->sum('quantity') }} items
                                                    <div class="order-item-preview ms-2">
                                                        @foreach($order->orderItems->take(3) as $item)
                                                            @if($item->image)
                                                                <div class="order-item-thumbnail" data-bs-toggle="tooltip" title="{{ $item->item->name }} × {{ $item->quantity }}">
                                                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->item->name }}">
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                        @if($order->orderItems->count() > 3)
                                                            <div class="order-item-more" data-bs-toggle="tooltip" title="{{ $order->orderItems->count() - 3 }} more items">
                                                                +{{ $order->orderItems->count() - 3 }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="fw-bold">${{ number_format($order->total_price, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-info me-1" data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-warning me-1">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('orders.destroy', $order) }}" method="POST" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this order?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                    <h5>No Orders Found</h5>
                    <p class="text-muted">There are no orders in the system yet.</p>
                    <a href="{{ route('orders.create') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus-circle"></i> Create New Order
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Order Detail Modals -->
    @foreach($orders as $order)
        <!-- Order Detail Modal -->
        <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1" aria-labelledby="orderModalLabel{{ $order->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title" id="orderModalLabel{{ $order->id }}">
                            Order #{{ $order->id }} Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold">Order Information</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created:</th>
                                        <td>{{ $order->created_at->format('M d, Y g:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated:</th>
                                        <td>{{ $order->updated_at->format('M d, Y g:i A') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold">Order Summary</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <th>Total Items:</th>
                                        <td>{{ $order->orderItems->sum('quantity') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Price:</th>
                                        <td class="fw-bold text-primary">${{ number_format($order->total_price, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <h6 class="fw-bold border-bottom pb-2 mb-3">Order Items</h6>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->image)
                                                        <div class="me-3" style="width: 50px; height: 50px;">
                                                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->item->name }}" class="img-thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                                                        </div>
                                                    @else
                                                        <div class="me-3 bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                            <i class="fas fa-utensils text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $item->item->name }}</strong>
                                                        @if($item->item->description)
                                                            <br>
                                                            <small class="text-muted">{{ Str::limit($item->item->description, 50) }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>${{ number_format($item->price, 2) }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td class="text-end">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th class="text-end">${{ number_format($order->total_price, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Order
                        </a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @push('styles')
    <style>
        .date-badge {
            width: 60px;
            height: 60px;
            min-width: 60px;
        }
        
        .order-item-preview {
            display: flex;
            align-items: center;
        }
        
        .order-item-thumbnail {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: -10px;
            border: 2px solid #fff;
            position: relative;
        }
        
        .order-item-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .order-item-more {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
            font-size: 0.75rem;
            color: #666;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    @endpush
@endsection