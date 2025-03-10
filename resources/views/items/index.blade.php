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
                  <tr class="text-center">
                     <th>Name</th>
                     <th>Price</th>
                     <th>Category</th>
                     <th>Image</th>
                     <th>Actions</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($items as $item)
                     <tr class="text-center">
                        <td class="align-middle">{{ $item->name }}</td>
                        <td class="align-middle">${{ $item->price }}</td>
                        <td class="align-middle">{{ $item->category->name }}</td>
                        <td class="align-middle">
                           @if($item->image)
                              <div style="width: 50px; height: 50px; overflow: hidden; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                 <img src="{{ asset('storage/' . $item->image) }}" class="img-thumbnail" style="object-fit: cover; width: 100%; height: 100%;">
                              </div>
                           @endif
                        </td>
                        <td class="align-middle">
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
