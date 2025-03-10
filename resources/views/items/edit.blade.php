@extends('layouts.app')

@section('content')
    <h2>Edit Item</h2>
    <form action="{{ route('items.update', $item) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <label>Name:</label>
        <input type="text" name="name" value="{{ $item->name }}" required>

        <label>Price:</label>
        <input type="number" step="0.01" name="price" value="{{ $item->price }}" required>

        <label>Description:</label>
        <textarea name="description">{{ $item->description }}</textarea>

        <label>Category:</label>
        <select name="category_id" required>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $category->id == $item->category_id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <label>Image:</label>
        <input type="file" name="image">
        @if($item->image)
            <img src="{{ asset('storage/' . $item->image) }}" width="50">
        @endif

        <label>Available:</label>
        <input type="checkbox" name="available" {{ $item->available ? 'checked' : '' }}>

        <button type="submit">Update Item</button>
    </form>
@endsection
