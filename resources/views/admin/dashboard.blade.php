@extends('layout')

@section('content')
<div class="header">
    <h1>Admin Dashboard</h1>
    <div>
        <a href="{{ route('admin.create') }}" class="btn btn-primary">+ Add New Product</a>
        <a href="{{ route('admin.sales') }}" class="btn" style="background: #4b5563; color: white;">Sales Report</a>
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf <button class="btn" style="background: #ef4444; color: white;">Logout</button>
        </form>
    </div>
</div>

@if(session('success'))
    <div style="background: #d1fae5; padding: 10px; margin-bottom: 20px;">{{ session('success') }}</div>
@endif

<div style="background: white; padding: 15px; margin-bottom: 20px; border-radius: 8px; display: flex; gap: 10px; align-items: center;">
    <form action="{{ route('admin.dashboard') }}" method="GET" style="display: flex; gap: 10px; width: 100%;">
        
        <input type="text" name="search" placeholder="Search product name..." value="{{ request('search') }}" style="padding: 8px; flex: 1;">
        
        <select name="category" style="padding: 8px;">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->CategoryID }}" {{ request('category') == $cat->CategoryID ? 'selected' : '' }}>
                    {{ $cat->CategoryName }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary">Filter & Search</button>
        <a href="{{ route('admin.dashboard') }}" class="btn" style="background: #ccc;">Reset</a>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>
                <a href="{{ route('admin.dashboard', array_merge(request()->all(), ['sort_by' => 'Name', 'order' => request('order') === 'asc' ? 'desc' : 'asc'])) }}">
                    Product Name {{ request('sort_by') == 'Name' ? (request('order') == 'asc' ? '↑' : '↓') : '' }}
                </a>
            </th>
            <th>
                <a href="{{ route('admin.dashboard', array_merge(request()->all(), ['sort_by' => 'StockQuantity', 'order' => request('order') === 'asc' ? 'desc' : 'asc'])) }}">
                    Stock {{ request('sort_by') == 'StockQuantity' ? (request('order') == 'asc' ? '↑' : '↓') : '' }}
                </a>
            </th>
            <th>
                <a href="{{ route('admin.dashboard', array_merge(request()->all(), ['sort_by' => 'Price', 'order' => request('order') === 'asc' ? 'desc' : 'asc'])) }}">
                    Price {{ request('sort_by') == 'Price' ? (request('order') == 'asc' ? '↑' : '↓') : '' }}
                </a>
            </th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td>
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" width="30" style="vertical-align: middle; margin-right: 5px;"> 
                @endif
                {{ $product->Name }}
            </td>
            <td style="{{ $product->StockQuantity < 10 ? 'color:red; font-weight:bold;' : '' }}">
                {{ $product->StockQuantity }}
            </td>
            <td>${{ $product->Price }}</td>
            <td>
                <div style="display: flex; gap: 5px;">
                    <a href="{{ route('admin.edit', $product->ProductID) }}" class="btn" style="background: #2563eb; color: white; padding: 5px 10px; font-size: 0.8rem;">Edit</a>
                    
                    <form action="{{ route('admin.delete', $product->ProductID) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn" style="background: #ef4444; color: white; padding: 5px 10px; font-size: 0.8rem;">Delete</button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection