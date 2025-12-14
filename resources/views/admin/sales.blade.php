@extends('layout')

@section('content')
<div class="header">
    <h1>Sales Report</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn cart-btn">Back to Dashboard</a>
</div>

@foreach($customers as $customer)
    <div style="background: white; padding: 15px; margin-bottom: 20px; border-radius: 8px;">
        <h3 style="border-bottom: 1px solid #eee; padding-bottom: 10px;">
            Customer: {{ $customer->FirstName }} {{ $customer->LastName }} 
            <span style="font-size: 0.9rem; color: gray;">({{ $customer->Email }})</span>
        </h3>
        
        <table>
            <thead>
                <tr style="background: #f9fafb;">
                    <th>Order Date</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customer->orders as $order)
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $order->OrderDate }}</td>
                        <td>{{ $item->product->Name ?? 'Unknown Product' }}</td> <td>{{ $item->Quantity }}</td>
                        <td>${{ $item->UnitPrice }}</td>
                        <td>${{ $item->Quantity * $item->UnitPrice }}</td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
@endforeach
@endsection