@extends('layouts.app')

@section('content')
<div class="container mt-4">
   <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Categories</h2>
      <a href="{{ route('categories.create') }}" class="btn btn-primary">
         <i class="fas fa-plus"></i> Add New Category
      </a>
   </div>

   <div class="card shadow-sm">
      <div class="card-body">
         <table class="table table-striped table-hover">
            <thead class="thead-dark">
               <tr>
                  <th scope="col">ID</th>
                  <th scope="col">Name</th>
                  <th scope="col" class="text-center">Actions</th>
               </tr>
            </thead>
            <tbody>
               @foreach($categories as $category)
               <tr>
                  <td>{{ $category->id }}</td>
                  <td>{{ $category->name }}</td>
                  <td class="text-center">
                     <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-edit"></i> Edit
                     </a>
                     <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">
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
@endsection
