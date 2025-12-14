<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    // 1. List products by category for the catalog view
  public function index(Request $request)
{
    $query = \App\Models\Product::with('category')->where('StockQuantity', '>', 0);

    // Filter if category_id is present in URL
    if ($request->has('category')) {
        $query->where('CategoryID', $request->category);
    }

    $products = $query->get();
    $categories = \App\Models\Category::all(); // Pass categories to view for the filter buttons

    return view('checkout.index', compact('products', 'categories'));
}

    // 2. Add items to a temporary cart (using Session)
    public function addToCart(Request $request)
    {
        $product = Product::find($request->ProductID);
        $qty = $request->input('quantity', 1); // Default to 1 if not provided
        
        if(!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Check stock availability
        if($product->StockQuantity < $qty) {
             return response()->json(['error' => 'Not enough stock available'], 400);
        }

        $cart = session()->get('cart', []);

        // If item exists, add to existing quantity
        if(isset($cart[$request->ProductID])) {
            $newQty = $cart[$request->ProductID]['quantity'] + $qty;
            // Double check stock for the total new quantity
            if($product->StockQuantity < $newQty) {
                return response()->json(['error' => 'Cannot add more. Stock limit reached.'], 400);
            }
            $cart[$request->ProductID]['quantity'] = $newQty;
        } else {
            // Add new item
            $cart[$request->ProductID] = [
                "name" => $product->Name,
                "quantity" => $qty,
                "price" => $product->Price,
                "image" => "https://placehold.co/100"
            ];
        }

        session()->put('cart', $cart);
        return response()->json(['success' => 'Product added to cart!', 'cartCount' => count($cart)]);
    }
    public function updateCart(Request $request)
    {
        if($request->id && $request->quantity) {
            $cart = session()->get('cart', []);
            
            if (!isset($cart[$request->id])) {
                session()->flash('error', 'Item not found in cart');
                return redirect()->back();
            }
            
            // Validate Stock before updating
            $product = Product::find($request->id);
            if($product && $product->StockQuantity >= $request->quantity) {
                 $cart[$request->id]["quantity"] = $request->quantity;
                 session()->put('cart', $cart);
                 session()->flash('success', 'Cart updated successfully');
            } else {
                 session()->flash('error', 'Insufficient stock for this quantity');
            }
        }
        // Return a simple redirect so the page reloads with new session data
        return redirect()->back(); 
    }
    public function history()
{
    $orders = \App\Models\Order::where('CustomerID', auth()->id())
                ->with('items.product')
                ->orderBy('OrderDate', 'desc')
                ->get();

    return view('checkout.history', compact('orders'));
}
    // NEW: Remove Item from Cart
    public function removeFromCart(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart', []);
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
                session()->flash('success', 'Item removed successfully');
            } else {
                session()->flash('error', 'Item not found in cart');
            }
        }
        return redirect()->back();
    }
    // 3. Store the final Order and OrderItems in the database
    public function placeOrder(Request $request)
    {
        // 1. CHECK: Ensure user is logged in
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to place an order.');
        }

        $cart = session()->get('cart');
        if(!$cart) {
            return back()->with('error', 'Cart is empty!');
        }

        $totalAmount = 0;
        foreach($cart as $id => $details) {
            $totalAmount += $details['price'] * $details['quantity'];
        }

        DB::beginTransaction();

        try {
            // 2. CHANGE: Use Auth::id() to link order to the actual user
            $order = Order::create([
                'CustomerID' => auth()->id(), // <--- THIS WAS THE ISSUE
                'OrderDate' => now(),
                'Status' => 'Completed',
                'TotalAmount' => $totalAmount
            ]);

            foreach($cart as $id => $details) {
                OrderItem::create([
                    'OrderID' => $order->OrderID,
                    'ProductID' => $id,
                    'Quantity' => $details['quantity'],
                    'UnitPrice' => $details['price']
                ]);

                // Stock Deduction
                $product = Product::where('ProductID', $id)->lockForUpdate()->first();
                
                if($product->StockQuantity < $details['quantity']) {
                    throw new \Exception("Insufficient stock for " . $product->Name);
                }

                $product->StockQuantity -= $details['quantity'];
                $product->save();
            }

            Payment::create([
                'OrderID' => $order->OrderID,
                'PaymentDate' => now(),
                'Amount' => $totalAmount,
                'PaymentMethod' => $request->payment_method,
                'PaymentStatus' => 'Success'
            ]);

            DB::commit();
            session()->forget('cart');
            
            // Redirect to the new "My Orders" page so they see it immediately
            return redirect()->route('checkout.history')->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Order failed: ' . $e->getMessage());
        }
    }
}