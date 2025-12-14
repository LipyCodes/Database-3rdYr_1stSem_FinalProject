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

<div style="background: white; padding: 15px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
    <form action="{{ route('admin.dashboard') }}" method="GET" style="display: flex; gap: 10px; flex-wrap: wrap;">
        
        <input type="text" name="search" placeholder="Search product..." value="{{ request('search') }}" 
               style="padding: 10px; border: 1px solid #ddd; border-radius: 4px; flex: 1;">
        
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

<div style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                <th style="padding: 12px; text-align: left;">Image</th>
                
                <th style="padding: 12px; text-align: left;">
                    <a href="{{ route('admin.dashboard', array_merge(request()->all(), ['sort_by' => 'Name', 'order' => request('order') === 'asc' ? 'desc' : 'asc'])) }}" style="text-decoration: none; color: #374151;">
                        Product Name {{ request('sort_by') == 'Name' ? (request('order') == 'asc' ? '↑' : '↓') : '' }}
                    </a>
                </th>
                
                <th style="padding: 12px; text-align: left;">Category</th>
                
                <th style="padding: 12px; text-align: left;">
                    <a href="{{ route('admin.dashboard', array_merge(request()->all(), ['sort_by' => 'StockQuantity', 'order' => request('order') === 'asc' ? 'desc' : 'asc'])) }}" style="text-decoration: none; color: #374151;">
                        Stock {{ request('sort_by') == 'StockQuantity' ? (request('order') == 'asc' ? '↑' : '↓') : '' }}
                    </a>
                </th>
                
                <th style="padding: 12px; text-align: left;">
                    <a href="{{ route('admin.dashboard', array_merge(request()->all(), ['sort_by' => 'Price', 'order' => request('order') === 'asc' ? 'desc' : 'asc'])) }}" style="text-decoration: none; color: #374151;">
                        Price {{ request('sort_by') == 'Price' ? (request('order') == 'asc' ? '↑' : '↓') : '' }}
                    </a>
                </th>
                
                <th style="padding: 12px; text-align: left;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 12px;">
                    <img src="{{ asset('images/' . $product->ProductID . '.jpg') }}" 
                         alt="Img" 
                         width="40" 
                         height="40"
                         style="object-fit: cover; border-radius: 4px;"
                         onerror="this.onerror=null; this.src='https://placehold.co/40?text=x';">
                </td>
                <td style="padding: 12px;">
                    <strong>{{ $product->Name }}</strong><br>
                    <span style="font-size: 0.8rem; color: #666;">ID: {{ $product->ProductID }}</span>
                </td>
                <td style="padding: 12px;">
                    <span style="background: #eff6ff; color: #1e40af; padding: 2px 8px; border-radius: 10px; font-size: 0.8rem;">
                        {{ $product->category->CategoryName ?? 'Uncategorized' }}
                    </span>
                </td>
                <td style="padding: 12px;">
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <span style="{{ $product->StockQuantity < 10 ? 'color:red; font-weight:bold;' : '' }}">
                            {{ $product->StockQuantity }}
                        </span>
                        <form action="{{ route('admin.restock', $product->ProductID) }}" method="POST" style="display: flex;">
                            @csrf
                            <input type="number" name="stock_added" placeholder="+" min="1" style="width: 40px; padding: 2px; font-size: 0.8rem; border: 1px solid #ccc;">
                            <button type="submit" style="background: #10b981; color: white; border: none; cursor: pointer; padding: 2px 6px;">✓</button>
                        </form>
                    </div>
                </td>
                <td style="padding: 12px;">${{ number_format($product->Price, 2) }}</td>
                <td style="padding: 12px;">
                    <div style="display: flex; gap: 5px;">
                        <a href="{{ route('admin.edit', $product->ProductID) }}" class="btn" style="background: #2563eb; color: white; padding: 5px 10px; font-size: 0.8rem; text-decoration: none;">Edit</a>
                        
                        <form action="{{ route('admin.delete', $product->ProductID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete {{ $product->Name }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn" style="background: #ef4444; color: white; padding: 5px 10px; font-size: 0.8rem;">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding: 20px; text-align: center; color: #666;">No products found matching your criteria.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="padding: 15px;">
        {{ $products->links() }} 
    </div>
</div>
@endsection