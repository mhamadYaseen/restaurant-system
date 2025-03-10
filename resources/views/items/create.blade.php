@extends('layouts.app')

@section('content')
    <h2>Add New Item</h2>
    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Price:</label>
        <input type="number" step="0.01" name="price" required>

        <label>Description:</label>
        <textarea name="description"></textarea>

        <label>Category:</label>
        <select name="category_id" required>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>

        <label>Image:</label>
        <input type="file" name="image">

        <label>Available:</label>
        <input type="checkbox" name="available" checked>

        <button type="submit">Add Item</button>
    </form>
@endsection
