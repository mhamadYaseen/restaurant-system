@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row mb-4">
      <div class="col-md-12">
         <h2 class="fw-bold">Edit Order</h2>
      </div>
   </div>

   <div class="card shadow-sm">
      <div class="card-body">
         <form action="{{ route('orders.update', $order) }}" method="POST">
            @csrf @method('PUT')
            
            <div class="mb-4">
               <h5 class="fw-bold">Select Items:</h5>
               <div class="row g-3">
                  @foreach($items as $item)
                     <div class="col-md-6 col-lg-4">
                        <div class="card h-100">
                           <div class="card-body d-flex flex-column">
                              <div class="form-check mb-2">
                                 <input class="form-check-input" type="checkbox" 
                                    name="items[{{ $item->id }}][id]" value="{{ $item->id }}" 
                                    id="item{{ $item->id }}"
                                    {{ in_array($item->id, $order->orderItems->pluck('item_id')->toArray()) ? 'checked' : '' }}>
                                 <label class="form-check-label fw-bold" for="item{{ $item->id }}">
                                    {{ $item->name }} - ${{ $item->price }}
                                 </label>
                              </div>
                              <div class="mt-auto">
                                 <label for="quantity{{ $item->id }}" class="form-label">Quantity:</label>
                                 <input type="number" class="form-control" id="quantity{{ $item->id }}" 
                                    name="items[{{ $item->id }}][quantity]" 
                                    value="{{ $order->orderItems->where('item_id', $item->id)->first()->quantity ?? 1 }}" 
                                    min="1">
                              </div>
                           </div>
                        </div>
                     </div>
                  @endforeach
               </div>
            </div>
            
            <div class="mb-4">
               <label for="status" class="form-label fw-bold">Status:</label>
               <select class="form-select" id="status" name="status">
                  <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                  <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                  <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
               </select>
            </div>
            
            <div class="d-flex justify-content-end">
               <a href="{{ route('orders.index') }}" class="btn btn-secondary me-2">
                  <i class="fas fa-arrow-left"></i> Cancel
               </a>
               <button type="submit" class="btn btn-primary">
                  <i class="fas fa-save"></i> Update Order
               </button>
            </div>
         </form>
      </div>
   </div>
</div>
@endsection
