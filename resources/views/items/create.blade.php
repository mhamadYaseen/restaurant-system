@extends('layouts.app')

@section('content')
<div class="container mt-4">
   <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Add New Item</h2>
      <a href="{{ route('items.index') }}" class="btn btn-secondary">Back to Items</a>
   </div>

   <div class="card shadow-sm">
      <div class="card-body">
         <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
               <label for="name" class="form-label">Name:</label>
               <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="mb-3">
               <label for="price" class="form-label">Price:</label>
               <div class="input-group">
                  <span class="input-group-text">$</span>
                  <input type="number" step="0.01" class="form-control" id="price" name="price" required>
               </div>
            </div>

            <div class="mb-3">
               <label for="description" class="form-label">Description:</label>
               <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>

            <div class="mb-3">
               <label for="category_id" class="form-label">Category:</label>
               <select class="form-select" id="category_id" name="category_id" required>
                  @foreach($categories as $category)
                     <option value="{{ $category->id }}">{{ $category->name }}</option>
                  @endforeach
               </select>
            </div>

            <div class="mb-3">
               <label for="image" class="form-label">Image:</label>
               <input type="file" class="form-control" id="image" name="image">
            </div>

            <div class="mb-3 form-check">
               <input type="checkbox" class="form-check-input" id="available" name="available" checked>
               <label class="form-check-label" for="available">Available</label>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
               <button type="submit" class="btn btn-primary">Add Item</button>
            </div>
         </form>
      </div>
   </div>
</div>
@endsection
