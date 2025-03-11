<!-- filepath: g:\projects\restaurant-system\resources\views\items\edit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mt-4">
   <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Edit Menu Item: {{ $item->name }}</h2>
      <a href="{{ route('items.index') }}" class="btn btn-outline-secondary">
         <i class="fas fa-arrow-left me-2"></i> Back to Items
      </a>
   </div>

   @if($errors->any())
      <div class="alert alert-danger mb-4">
         <ul class="mb-0">
            @foreach($errors->all() as $error)
               <li>{{ $error }}</li>
            @endforeach
         </ul>
      </div>
   @endif

   <div class="card shadow-sm">
      <div class="card-header bg-white">
         <i class="fas fa-utensils me-2"></i> Item Details
      </div>
      <div class="card-body">
         <form action="{{ route('items.update', $item) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            
            <div class="row">
               <!-- Left Column - Basic Information -->
               <div class="col-md-8">
                  <div class="mb-4">
                     <label for="name" class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                     <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $item->name) }}" required placeholder="Enter item name">
                     @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                     <small class="text-muted">The name of the dish as it will appear on the menu.</small>
                  </div>
   
                  <div class="mb-4">
                     <label for="description" class="form-label fw-bold">Description</label>
                     <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Enter a detailed description of the menu item">{{ old('description', $item->description) }}</textarea>
                     @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                     <small class="text-muted">Describe the dish, including key ingredients and preparation method.</small>
                  </div>
   
                  <div class="row mb-4">
                     <div class="col-md-6">
                        <label for="price" class="form-label fw-bold">Price <span class="text-danger">*</span></label>
                        <div class="input-group">
                           <span class="input-group-text">$</span>
                           <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $item->price) }}" required placeholder="0.00">
                           @error('price')
                              <div class="invalid-feedback">{{ $message }}</div>
                           @enderror
                        </div>
                        <small class="text-muted">The price of the item in USD.</small>
                     </div>
                     
                     <div class="col-md-6">
                        <label for="category_id" class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                        <div class="input-group">
                           <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                              <option value="" disabled>Select a category</option>
                              @foreach($categories as $category)
                                 <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                              @endforeach
                           </select>
                           <a href="{{ route('categories.create') }}" class="btn btn-outline-secondary" title="Create new category">
                              <i class="fas fa-plus"></i>
                           </a>
                           @error('category_id')
                              <div class="invalid-feedback">{{ $message }}</div>
                           @enderror
                        </div>
                        <small class="text-muted">The category this item belongs to.</small>
                     </div>
                  </div>
               </div>
               
               <!-- Right Column - Image & Status -->
               <div class="col-md-4">
                  <div class="card mb-4">
                     <div class="card-body">
                        <div class="mb-4">
                           <label class="form-label fw-bold d-block">Item Preview</label>
                           <div class="image-preview-container text-center mb-3">
                              <img id="imagePreview" src="{{ $item->image ? asset('storage/' . $item->image) : asset('images/placeholder-food.png') }}" class="img-fluid rounded" style="max-height: 200px; width: auto;">
                           </div>
                           
                           <div class="mb-3">
                              <label for="image" class="form-label">Upload New Image</label>
                              <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" onchange="previewImage()">
                              @error('image')
                                 <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                              <small class="text-muted">Recommended size: 500x500 pixels. Leave blank to keep current image.</small>
                           </div>
                        </div>
                        
                        <div class="form-check form-switch">
                           <input type="checkbox" class="form-check-input" id="available" name="available" {{ old('available', $item->available) ? 'checked' : '' }}>
                           <label class="form-check-label fw-bold" for="available">Available for ordering</label>
                        </div>
                        <small class="text-muted">Turn off if the item is out of stock or unavailable.</small>
                     </div>
                  </div>

                  <div class="card border-info">
                     <div class="card-header bg-info bg-opacity-10 text-info">
                        <i class="fas fa-info-circle me-2"></i> Item Info
                     </div>
                     <div class="card-body">
                        <div class="mb-2">
                           <small class="text-muted d-block">Created:</small>
                           <span>{{ $item->created_at->format('M d, Y g:i A') }}</span>
                        </div>
                        <div>
                           <small class="text-muted d-block">Last Updated:</small>
                           <span>{{ $item->updated_at->format('M d, Y g:i A') }}</span>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            
            <hr>
            
            <div class="d-flex justify-content-between">
               <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">Cancel</button>
               <div>
                  <a href="{{ route('items.show', $item) }}" class="btn btn-outline-primary me-2">
                     <i class="fas fa-eye me-2"></i> View Item
                  </a>
                  <button type="submit" class="btn btn-primary px-4">
                     <i class="fas fa-save me-2"></i> Update Item
                  </button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>

@push('scripts')
<script>
   function previewImage() {
      const fileInput = document.getElementById('image');
      const preview = document.getElementById('imagePreview');
      
      if (fileInput.files && fileInput.files[0]) {
         const reader = new FileReader();
         
         reader.onload = function(e) {
            preview.src = e.target.result;
         }
         
         reader.readAsDataURL(fileInput.files[0]);
      }
   }
</script>
@endpush
@endsection