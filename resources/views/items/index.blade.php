<!-- filepath: g:\projects\restaurant-system\resources\views\items\index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Menu Items</h2>
        <div>
            <a href="{{ route('categories.create') }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-folder-plus"></i> New Category
            </a>
            <a href="{{ route('items.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Add New Item
            </a>
        </div>
    </div>

    @php
        // Group items by category
        $itemsByCategory = $items->groupBy('category_id');
        $categories = App\Models\Category::whereIn('id', $itemsByCategory->keys())->get()->keyBy('id');
    @endphp

    <div class="category-filters mb-4">
        <div class="d-flex align-items-center mb-2">
            <h5 class="mb-0 me-3">Filter by Category:</h5>
            <button class="btn btn-sm btn-primary me-2 category-filter active" data-category="all">All</button>
            @foreach($categories as $category)
                <button class="btn btn-sm btn-outline-primary me-2 category-filter" data-category="{{ $category->id }}">
                    {{ $category->name }} ({{ $itemsByCategory[$category->id]->count() }})
                </button>
            @endforeach
        </div>
    </div>
    
    <div id="all-categories-view">
        @foreach($categories as $category)
            <div class="card shadow-sm mb-4 category-section" id="category-{{ $category->id }}">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-tag me-2"></i> {{ $category->name }}
                        </h5>
                        <span class="badge bg-primary">{{ $itemsByCategory[$category->id]->count() }} items</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($itemsByCategory[$category->id] as $item)
                                    <tr>
                                        <td class="align-middle">{{ $item->name }}</td>
                                        <td class="align-middle">${{ number_format($item->price, 2) }}</td>
                                        <td class="align-middle">
                                            @if($item->available)
                                                <span class="badge bg-success">Available</span>
                                            @else
                                                <span class="badge bg-danger">Out of Stock</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            @if($item->image)
                                                <div style="width: 50px; height: 50px; overflow: hidden; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                                    <img src="{{ asset('storage/' . $item->image) }}" class="img-thumbnail" style="object-fit: cover; width: 100%; height: 100%;">
                                                </div>
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; margin: 0 auto;">
                                                    <i class="fas fa-utensils text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('items.destroy', $item) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete {{ $item->name }}?')">
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
        @endforeach
    </div>
    
    @if($items->isEmpty())
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                <h5>No Menu Items Found</h5>
                <p class="text-muted">Get started by adding your first menu item.</p>
                <a href="{{ route('items.create') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus-circle"></i> Add New Item
                </a>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.category-filter');
        const categorySections = document.querySelectorAll('.category-section');
        
        // Add click event to filter buttons
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const categoryId = this.getAttribute('data-category');
                
                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active', 'btn-primary'));
                filterButtons.forEach(btn => btn.classList.add('btn-outline-primary'));
                this.classList.add('active', 'btn-primary');
                this.classList.remove('btn-outline-primary');
                
                // Show/hide categories based on selection
                if (categoryId === 'all') {
                    categorySections.forEach(section => section.style.display = 'block');
                } else {
                    categorySections.forEach(section => {
                        if (section.id === `category-${categoryId}`) {
                            section.style.display = 'block';
                        } else {
                            section.style.display = 'none';
                        }
                    });
                }
            });
        });
    });
</script>
@endpush

@push('styles')
<style>
    .category-section {
        transition: all 0.3s ease;
    }
    
    .card-header {
        border-left: 5px solid var(--bs-primary);
    }
    
    .category-filters {
        overflow-x: auto;
        white-space: nowrap;
        padding-bottom: 5px;
    }
</style>
@endpush
@endsection