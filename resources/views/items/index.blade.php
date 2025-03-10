@extends('layouts.app')

@section('content')
    <h2>Menu Items</h2>
    <a href="{{ route('items.create') }}">Add New Item</a>
    <table>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Category</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        @foreach($items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>${{ $item->price }}</td>
                <td>{{ $item->category->name }}</td>
                <td>
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" width="50">
                    @endif
                </td>
                <td>
                    <a href="{{ route('items.edit', $item) }}">Edit</a>
                    <form action="{{ route('items.destroy', $item) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
@endsection
