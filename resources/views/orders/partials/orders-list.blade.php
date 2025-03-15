<!-- filepath: g:\projects\restaurant-system\resources\views\orders\partials\orders-list.blade.php -->

@forelse($groupedOrders as $date => $ordersForDay)
    <?php
        $carbonDate = \Carbon\Carbon::parse($date);
        $isToday = $carbonDate->isToday();
        $isYesterday = $carbonDate->isYesterday();
    ?>

    <div class="date-section mb-4">
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
            </h4>
        </div>
        
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Amount</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Items</th>
                                <th>Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ordersForDay as $order)
                                <tr>
                                    <td class="fw-bold">${{ number_format($order->total_price, 2) }}</td>
                                    <td>{{ $order->user->name ?? 'N/A' }} ({{ $order->user->email ?? 'N/A' }})</td>
                                    <td>
                                        @if($order->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @else
                                            <span class="badge bg-danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>{{ $order->orderItems->count() }} items</td>
                                    <td>{{ $order->created_at->format('g:i A') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('orders.show', $order) }}" 
                                              class="btn btn-sm btn-outline-primary" 
                                              data-bs-toggle="tooltip" 
                                              title="View Order">
                                              <i class="fas fa-eye"></i>
                                            </a>
                                            @if(Auth::user()->role == 'admin')
                                                <a href="{{ route('orders.edit', $order) }}" 
                                                  class="btn btn-sm btn-outline-secondary" 
                                                  data-bs-toggle="tooltip" 
                                                  title="Edit Order">
                                                  <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                        </div>
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
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i> No orders found for this time period.
    </div>
@endforelse