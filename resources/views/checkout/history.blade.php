@extends('layout')
@section('content')
<div class="header">
    <h1>My Purchase History</h1>
    <a href="{{ route('checkout.index') }}" class="btn cart-btn">Back to Shop</a>
</div>

@forelse($orders as $order)
    <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 10px;">
            <strong>Order #{{ $order->OrderID }}</strong>
            <span style="color: #666;">{{ $order->OrderDate }}</span>
            <span style="font-weight: bold; color: var(--primary);">${{ $order->TotalAmount }}</span>
            <span style="background: #d1fae5; color: #065f46; padding: 2px 8px; border-radius: 10px; font-size: 0.8rem;">
                {{ $order->Status }}
            </span>
        </div>
        <ul>
            @foreach($order->items as $item)
                <li>
                    {{ $item->product->Name ?? 'Unknown Item' }} 
                    (x{{ $item->Quantity }}) - ${{ $item->UnitPrice }}
                </li>
            @endforeach
        </ul>
    </div>
@empty
    <p>You haven't placed any orders yet.</p>
@endforelse
@endsection