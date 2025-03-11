<!-- filepath: g:\projects\restaurant-system\resources\views\categories\index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mt-4">
   <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Categories</h2>
      <div>
         <a href="{{ route('items.index') }}" class="btn btn-outline-primary me-2">
            <i class="fas fa-utensils"></i> View Menu Items
         </a>
         <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Category
         </a>
      </div>
   </div>

   @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
         {{ session('success') }}
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
   @endif

   <div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
      <div class="col">
         <div class="card bg-light border-0 shadow-sm h-100">
            <div class="card-body text-center">
               <div class="py-3">
                  <i class="fas fa-layer-group fa-3x text-primary mb-3"></i>
                  <h3>{{ $categories->count() }}</h3>
                  <p class="text-muted mb-0">Total Categories</p>
               </div>
            </div>
         </div>
      </div>
      
      <div class="col">
         <div class="card bg-light border-0 shadow-sm h-100">
            <div class="card-body text-center">
               <div class="py-3">
                  <i class="fas fa-utensils fa-3x text-success mb-3"></i>
                  <h3>{{ App\Models\Item::count() }}</h3>
                  <p class="text-muted mb-0">Total Menu Items</p>
               </div>
            </div>
         </div>
      </div>
      
      <div class="col">
         <div class="card bg-light border-0 shadow-sm h-100">
            <div class="card-body text-center">
               <div class="py-3">
                  <i class="fas fa-plus-circle fa-3x text-primary mb-3"></i>
                  <div class="mt-4">
                     <a href="{{ route('categories.create') }}" class="btn btn-outline-primary">
                        Add New Category
                     </a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <div class="card shadow-sm">
      <div class="card-header bg-white">
         <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
               <i class="fas fa-tags me-2"></i> Category List
            </h5>
            <div class="input-group" style="width: 300px;">
               <span class="input-group-text bg-white border-end-0">
                  <i class="fas fa-search text-muted"></i>
               </span>
               <input type="text" id="categorySearch" class="form-control border-start-0" placeholder="Search categories...">
            </div>
         </div>
      </div>
      <div class="card-body">
         <div class="table-responsive">
            <table class="table table-hover" id="categoryTable">
               <thead class="table-light">
                  <tr>
                     <th scope="col" width="80">ID</th>
                     <th scope="col">Name</th>
                     <th scope="col" width="180">Items</th>
                     <th scope="col" width="180">Created At</th>
                     <th scope="col" class="text-center" width="200">Actions</th>
                  </tr>
               </thead>
               <tbody>
                  @forelse($categories as $category)
                     <tr>
                        <td>{{ $category->id }}</td>
                        <td>
                           <div class="d-flex align-items-center">
                              <span class="category-color me-2" style="background-color: {{ '#' . substr(md5($category->name), 0, 6) }};"></span>
                              <span class="fw-medium">{{ $category->name }}</span>
                           </div>
                        </td>
                        <td>
                           @php $itemCount = $category->items->count(); @endphp
                           <span class="badge bg-{{ $itemCount > 0 ? 'primary' : 'secondary' }}">
                              {{ $itemCount }} {{ Str::plural('item', $itemCount) }}
                           </span>
                        </td>
                        <td>{{ $category->created_at->format('M d, Y') }}</td>
                        <td class="text-center">
                           <a href="{{ route('items.index') }}?category={{ $category->id }}" class="btn btn-sm btn-outline-primary me-1" title="View Items">
                              <i class="fas fa-eye"></i>
                           </a>
                           <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit Category">
                              <i class="fas fa-edit"></i>
                           </a>
                           <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                              @csrf @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-outline-danger" 
                                 onclick="return confirm('Are you sure you want to delete the category \'{{ $category->name }}\'? This may affect menu items in this category.')" 
                                 title="Delete Category">
                                 <i class="fas fa-trash"></i>
                              </button>
                           </form>
                        </td>
                     </tr>
                  @empty
                     <tr>
                        <td colspan="5" class="text-center py-5">
                           <i class="fas fa-tag fa-3x text-muted mb-3"></i>
                           <h5>No Categories Found</h5>
                           <p class="text-muted">Start by creating your first category.</p>
                           <a href="{{ route('categories.create') }}" class="btn btn-primary mt-3">
                              <i class="fas fa-plus"></i> Add New Category
                           </a>
                        </td>
                     </tr>
                  @endforelse
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>

@push('styles')
<style>
   .category-color {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      display: inline-block;
   }
   
   .table th {
      font-weight: 600;
   }
</style>
@endpush

@push('scripts')
<script>
   document.addEventListener('DOMContentLoaded', function() {
      // Category search functionality
      const searchInput = document.getElementById('categorySearch');
      searchInput.addEventListener('keyup', function() {
         const searchTerm = this.value.toLowerCase();
         const table = document.getElementById('categoryTable');
         const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
         
         for (let i = 0; i < rows.length; i++) {
            const categoryName = rows[i].getElementsByTagName('td')[1].textContent.toLowerCase();
            if (categoryName.includes(searchTerm)) {
               rows[i].style.display = '';
            } else {
               rows[i].style.display = 'none';
            }
         }
      });
      
      // Initialize tooltips
      const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
      tooltipTriggerList.map(function (tooltipTriggerEl) {
         return new bootstrap.Tooltip(tooltipTriggerEl, {
            placement: 'top'
         });
      });
   });
</script>
@endpush
@endsection