@extends('layouts.app')

@section('content')
<div class="container mt-4">
   <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Menu Items</h2>
      <a href="{{ route('items.create') }}" class="btn btn-primary">Add New Item</a>
   </div>

   <div class="card shadow-sm">
      <div class="card-body">
         <div class="table-responsive">
            <table class="table table-hover">
               <thead class="thead-light">
                  <tr>
                     <th>Name</th>
                     <th>Price</th>
                     <th>Category</th>
                     <th>Image</th>
                     <th>Actions</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($items as $item)
                     <tr>
                        <td>{{ $item->name }}</td>
                        <td>${{ $item->price }}</td>
                        <td>{{ $item->category->name }}</td>
                        <td>
                           @if($item->image)
                              <img src="{{ asset('storage/' . $item->image) }}" class="img-thumbnail" width="50">
                           @endif
                        </td>
                        <td>
                           <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                           <form action="{{ route('items.destroy', $item) }}" method="POST" class="d-inline">
                              @csrf @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button>
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
