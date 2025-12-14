@extends('layout')

@section('content')
<div class="header">
    <h1>Admin Panel</h1>
    <div>
        <a href="{{ route('admin.create') }}" class="btn btn-primary">+ Add Product</a>
        <a href="{{ route('admin.sales') }}" class="btn" style="background: #4b5563; color: white;">Sales Report</a>
        
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf 
            <button class="btn" style="background: #ef4444; color: white;">Logout</button>
        </form>
    </div>
</div>

@if(session('success'))
    <div style="background: #d1fae5; padding: 10px; margin-bottom: 20px; border-radius: 5px; color: #065f46;">
        {{ session('success') }}
    </div>
@endif

<div style="background: white; padding: 15px; margin-bottom: 20px; border-radius: 8px;">
    <form action="{{ route('admin.dashboard') }}" method="GET" style="display: flex; gap: 10px;">
        <input type="text" name="search" placeholder="Search product..." value="{{ request('search') }}" style="padding: 10px; border: 1px solid #ddd; border-radius: 4px; flex: 1;">
        
        <select name="category" style="padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->CategoryID }}" {{ request('category') == $cat->CategoryID ? 'selected' : '' }}>
                    {{ $cat->CategoryName }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('admin.dashboard') }}" class="btn" style="background: #e5e7eb; color: #374151; text-decoration: none;">Reset</a>
    </form>
</div>

<div style="background: white; border-radius: 8px; overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                <th style="padding: 12px; text-align: left;">Image</th>
                <th style="padding: 12px; text-align: left;">Product Name</th>
                <th style="padding: 12px; text-align: left;">Stock</th>
                <th style="padding: 12px; text-align: left;">Price</th>
                <th style="padding: 12px; text-align: left;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 12px;">
                    <img src="{{ asset('images/' . $product->ProductID . '.jpg') }}" 
                         width="40" height="40" style="object-fit: cover; border-radius: 4px;"
                         onerror="this.onerror=null; this.src='https://placehold.co/40?text=x';">
                </td>
                <td style="padding: 12px;">{{ $product->Name }}</td>
                <td style="padding: 12px;">{{ $product->StockQuantity }}</td>
                <td style="padding: 12px;">${{ number_format($product->Price, 2) }}</td>
                <td style="padding: 12px;">
                    <a href="{{ route('admin.edit', $product->ProductID) }}" class="btn" style="background: #2563eb; color: white; padding: 5px 10px; font-size: 0.8rem; text-decoration: none;">Edit</a>
                    <form action="{{ route('admin.delete', $product->ProductID) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this product?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn" style="background: #ef4444; color: white; padding: 5px 10px; font-size: 0.8rem;">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="padding: 20px; text-align: center;">No products found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding: 15px;">{{ $products->links() }}</div>
</div>
@endsection