@extends('layout')

@section('content')
<div class="header">
    <div style="display: flex; align-items: center; gap: 15px;">
        <img src="{{ asset('images/logo2.png') }}" alt="QuickMart Logo" style="height: 100px; width: auto;">
        
        <h1 style="margin: 0;">QuickMart</h1>
        
        @auth
            <span style="font-size: 0.9rem; color: #666; margin-left: 10px; border-left: 1px solid #ccc; padding-left: 15px;">
                Welcome, {{ Auth::user()->FirstName }}
            </span>
        @endauth
    </div>

    <div style="display: flex; gap: 10px;">
        @if(!Auth::check() || Auth::user()->role !== 'admin')
        <button class="btn cart-btn" onclick="openCart()">
            🛒 Cart (<span id="cart-count">{{ count((array) session('cart')) }}</span>)
        </button>
        @endif

        @auth
            <a href="{{ route('checkout.history') }}" class="btn" style="background: #4b5563; color: white; text-decoration: none;">My Orders</a>
        
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn" style="background: #ef4444; color: white;">
                    Logout
                </button>
            </form>
        @endauth
    </div>
</div>

@if(session('success'))
    <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
        {{ session('error') }}
    </div>
@endif
<div style="margin-bottom: 20px; display: flex; gap: 10px; overflow-x: auto; padding-bottom: 10px;">
    <a href="{{ route('checkout.index') }}" 
       class="btn" style="background: {{ !request('category') ? '#2563eb' : 'white' }}; color: {{ !request('category') ? 'white' : '#2563eb' }}; border: 1px solid #2563eb;">
       All
    </a>

    @foreach($categories as $cat)
        <a href="{{ route('checkout.index', ['category' => $cat->CategoryID]) }}" 
           class="btn" 
           style="background: {{ request('category') == $cat->CategoryID ? '#2563eb' : 'white' }}; 
                  color: {{ request('category') == $cat->CategoryID ? 'white' : '#2563eb' }}; 
                  border: 1px solid #2563eb; white-space: nowrap;">
           {{ $cat->CategoryName }}
        </a>
    @endforeach
</div>
<div class="product-grid">
    @foreach($products as $product)
    <div class="product-card">
        <img src="{{ asset('images/' . $product->ProductID . '.jpg') }}" 
     alt="{{ $product->Name }}" 
     class="product-img"
     onerror="this.onerror=null; this.src='{{ asset('images/' . $product->ProductID . '.png') }}'; this.onerror=function(){this.src='https://placehold.co/300x200?text=No+Image';};">
        <h3>{{ $product->Name }}</h3>
        <p style="color: #666; font-size: 0.9rem;">{{ $product->category->CategoryName }}</p>
        <div class="price">${{ $product->Price }}</div>
        <p style="font-size: 0.8rem; color: #555;">Stock: {{ $product->StockQuantity }}</p>
        
        <div style="display: flex; gap: 5px; margin-top: auto;">
            <input type="number" id="qty-{{ $product->ProductID }}" value="1" min="1" max="{{ $product->StockQuantity }}" style="width: 50px; padding: 5px; border: 1px solid #ddd; border-radius: 4px;">
            <button class="btn btn-primary" onclick="addToCart({{ $product->ProductID }})">Add</button>
        </div>
    </div>
    @endforeach
</div>

<div id="cartModal" class="modal">
    <div class="modal-content" style="max-width: 700px;"> <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h2>Your Order</h2>
            <button onclick="closeCart()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>

        @if(session('cart'))
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f3f4f6;">
                    <th style="padding: 10px;">Item</th>
                    <th style="padding: 10px;">Qty</th>
                    <th style="padding: 10px;">Price</th>
                    <th style="padding: 10px;">Total</th>
                    <th style="padding: 10px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach(session('cart') as $id => $details)
                    @php $total += $details['price'] * $details['quantity']; @endphp
                    <tr>
                        <td style="padding: 10px;">{{ $details['name'] }}</td>
                        <td style="padding: 10px;">
                            <form action="{{ route('checkout.update') }}" method="POST" style="display: flex; gap: 5px;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="id" value="{{ $id }}">
                                <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" style="width: 50px; padding: 5px;">
                                <button type="submit" class="btn" style="padding: 5px; font-size: 0.8rem; background: #e5e7eb;">↻</button>
                            </form>
                        </td>
                        <td style="padding: 10px;">${{ $details['price'] }}</td>
                        <td style="padding: 10px;">${{ $details['price'] * $details['quantity'] }}</td>
                        <td style="padding: 10px;">
                            <form action="{{ route('checkout.remove') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $id }}">
                                <button type="submit" class="btn" style="background: #ef4444; color: white; padding: 5px 10px; font-size: 0.8rem;">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right; padding: 10px; font-weight: bold;">Grand Total:</td>
                    <td colspan="2" style="padding: 10px; font-weight: bold; font-size: 1.1rem; color: var(--primary);">${{ $total }}</td>
                </tr>
            </tfoot>
        </table>

        <form action="{{ route('checkout.placeOrder') }}" method="POST" style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px;">
            @csrf
            <h3>Payment Method</h3>
            <div style="margin-bottom: 1rem;">
                <label><input type="radio" name="payment_method" value="Cash" checked> Cash</label>
                <label><input type="radio" name="payment_method" value="Card" style="margin-left: 15px;"> Credit/Debit Card</label>
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" class="btn" style="background: #ccc;" onclick="closeCart()">Continue Shopping</button>
                <button type="submit" class="btn btn-primary">Pay & Checkout</button>
            </div>
        </form>
        @else
            <p style="text-align: center; padding: 20px; color: #666;">Your cart is empty.</p>
            <button type="button" class="btn" style="background: #ccc; width: 100%;" onclick="closeCart()">Start Shopping</button>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openCart() {
        document.getElementById('cartModal').classList.add('active');
    }

    function closeCart() {
        document.getElementById('cartModal').classList.remove('active');
    }

    function addToCart(productId) {
        // Get the value from the specific input for this product
        let qtyInput = document.getElementById('qty-' + productId);
        let quantity = qtyInput ? qtyInput.value : 1;

        fetch("{{ route('checkout.add') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            // Pass the quantity in the body
            body: JSON.stringify({ ProductID: productId, quantity: quantity }) 
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                document.getElementById('cart-count').innerText = data.cartCount;
                // Optional: Show a toast/notification instead of alert
                // alert('Item added!'); 
                // Reload isn't strictly necessary if you don't have the cart open, 
                // but ensures sync if the user opens the modal next.
                location.reload(); 
            } else {
                alert(data.error || 'Error adding item.');
            }
        });
    }
</script>
@endsection