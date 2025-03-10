@extends('layouts.app')

@section('content')
<div class="container mt-4">
   <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Edit Category</h2>
      <a href="{{ route('categories.index') }}" class="btn btn-secondary">
         <i class="fas fa-arrow-left"></i> Back to Categories
      </a>
   </div>

   <div class="card shadow-sm">
      <div class="card-body">
         <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf 
            @method('PUT')
            
            <div class="mb-3">
               <label for="name" class="form-label">Name:</label>
               <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}" required>
            </div>

            <div class="mt-4">
               <button type="submit" class="btn btn-primary">
                  <i class="fas fa-save"></i> Update Category
               </button>
            </div>
         </form>
      </div>
   </div>
</div>
@endsection
