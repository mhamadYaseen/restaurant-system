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
            <i class="fas fa-plus-circle"></i> Create New Order
         </a>
      </div>
   </div>

   <div class="card shadow-sm">
      <div class="card-body">
         <div class="table-responsive">
            <table class="table table-hover">
               <thead class="table-light">
                  <tr>
                     <th>ID</th>
                     <th>Total Price</th>
                     <th>created at</th>
                     <th>Actions</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($orders as $order)
                     <tr>
                        <td>{{ $order->id }}</td>
                        <td>${{ $order->total_price }}</td>
                        <td>
                           {{ $order->created_at->format('M d, Y g:i A') }}
                        </td>
                        <td>
                           <button type="button" class="btn btn-sm btn-info me-1" data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}">
                              <i class="fas fa-eye"></i> View
                           </button>
                           <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-warning me-1">
                              <i class="fas fa-edit"></i> Edit
                           </a>
                           <form action="{{ route('orders.destroy', $order) }}" method="POST" style="display:inline;">
                              @csrf @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this order?')">
                                 <i class="fas fa-trash"></i> Delete
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
                        <td class="fw-bold text-primary">${{ $order->total_price }}</td>
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
                        <td>${{ $item->price }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td class="text-end">${{ $item->price * $item->quantity }}</td>
                     </tr>
                     @endforeach
                  </tbody>
                  <tfoot class="table-light">
                     <tr>
                        <th colspan="3" class="text-end">Total:</th>
                        <th class="text-end">${{ $order->total_price }}</th>
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
@endsection