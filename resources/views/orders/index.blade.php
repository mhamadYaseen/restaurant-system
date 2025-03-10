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
                     <th>Status</th>
                     <th>Actions</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($orders as $order)
                     <tr>
                        <td>{{ $order->id }}</td>
                        <td>${{ $order->total_price }}</td>
                        <td>
                           <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'secondary') }}">
                              {{ ucfirst($order->status) }}
                           </span>
                        </td>
                        <td>
                           <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info me-1">
                              <i class="fas fa-eye"></i> View
                           </a>
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
@endsection
