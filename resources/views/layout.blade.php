<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>QuickMart Self-Checkout</title>
    <style>
        :root { --primary: #2563eb; --bg: #f3f4f6; --text: #1f2937; }
        body { font-family: sans-serif; background: var(--bg); color: var(--text); margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        
        /* Grid Layout */
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .product-card { background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); display: flex; flex-direction: column; }
        .product-img { width: 100%; height: 150px; background: #e5e7eb; object-fit: cover; border-radius: 4px; }
        .price { font-size: 1.25rem; font-weight: bold; color: var(--primary); margin: 10px 0; }
        
        /* Buttons */
        .btn { padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; transition: 0.2s; }
        .btn-primary { background: var(--primary); color: white; width: 100%; }
        .btn-primary:hover { background: #1d4ed8; }
        .cart-btn { background: white; border: 1px solid var(--primary); color: var(--primary); }
        
        /* Modal */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
        .modal-content { background: white; padding: 2rem; border-radius: 8px; width: 90%; max-width: 500px; }
        .modal.active { display: flex; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; }
        th, td { text-align: left; padding: 8px; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
    @yield('scripts')
</body>
</html>