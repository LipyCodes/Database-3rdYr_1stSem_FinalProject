@extends('layout')

@section('content')
<div style="max-width: 600px; margin: 20px auto; background: white; padding: 2rem; border-radius: 8px;">
    <h2>Add New Product</h2>
    <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div style="margin-bottom: 15px;">
            <label>Name</label>
            <input type="text" name="Name" required style="width: 100%; padding: 8px;">
        </div>
        <div style="margin-bottom: 15px;">
            <label>Category</label>
            <select name="CategoryID" style="width: 100%; padding: 8px;">
                @foreach($categories as $cat)
                    <option value="{{ $cat->CategoryID }}">{{ $cat->CategoryName }}</option>
                @endforeach
            </select>
        </div>
        <div style="display: flex; gap: 10px; margin-bottom: 15px;">
            <div style="flex: 1;">
                <label>Price</label>
                <input type="number" step="0.01" name="Price" required style="width: 100%; padding: 8px;">
            </div>
            <div style="flex: 1;">
                <label>Stock</label>
                <input type="number" name="StockQuantity" required style="width: 100%; padding: 8px;">
            </div>
        </div>
        <div style="margin-bottom: 15px;">
            <label>Description</label>
            <textarea name="Description" style="width: 100%; padding: 8px;"></textarea>
        </div>
        <div style="margin-bottom: 15px;">
            <label>Image</label>
            <input type="file" name="image">
        </div>
        <button type="submit" class="btn btn-primary">Create Product</button>
        <a href="{{ route('admin.dashboard') }}" class="btn" style="background: #ccc;">Cancel</a>
    </form>
</div>
@endsection