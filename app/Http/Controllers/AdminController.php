<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;

class AdminController extends Controller
{
    // READ + SEARCH + SORT + FILTER + PAGINATION
    public function dashboard(Request $request)
    {
        $query = Product::query();

        // 1. SEARCH
        if ($request->filled('search')) {
            $query->where('Name', 'like', '%' . $request->search . '%');
        }

        // 2. FILTER
        if ($request->filled('category')) {
            $query->where('CategoryID', $request->category);
        }

        // 3. SORT
        $sortBy = $request->get('sort_by', 'ProductID');
        $sortOrder = $request->get('order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // 4. PAGINATION (New!) - Shows 10 items per page
        $products = $query->paginate(10); 
        $products->appends($request->all()); // Keeps search params in URL
        $categories = Category::all();

        // Pointing to 'admin.admin' view (admin/admin.blade.php)
        return view('admin.edit', compact('products', 'categories'));
    }

    // CREATE (Show Form)
    public function create()
    {
        $categories = Category::all();
        return view('admin.create', compact('categories'));
    }

    // CREATE (Save Data) - Includes VALIDATION
    public function store(Request $request)
    {
        $request->validate([
            'Name' => 'required|string|max:255',
            'CategoryID' => 'required|exists:categories,CategoryID',
            'Price' => 'required|numeric|min:0',
            'StockQuantity' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $product = new Product();
        $product->Name = $request->Name;
        $product->save();
        $product->CategoryID = $request->CategoryID;
        $product->Price = $request->Price;
        $product->StockQuantity = $request->StockQuantity;
        $product->Description = $request->input('Description', '');

        if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->save();

        return redirect()->route('admin.dashboard')->with('success', 'Product Created Successfully');
    }

    // UPDATE (Show Form)
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.edit', compact('product', 'categories'));
    }

    // UPDATE (Save Data) - Includes VALIDATION
    public function update(Request $request, $id)
    {
        $request->validate([
            'Name' => 'required|string|max:255',
            'CategoryID' => 'required|exists:categories,CategoryID',
            'Price' => 'required|numeric|min:0',
            'StockQuantity' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $product = Product::findOrFail($id);
        $product->Name = $request->Name;
        $product->save();
        $product->CategoryID = $request->CategoryID;
        $product->Price = $request->Price;
        $product->StockQuantity = $request->StockQuantity;
        $product->Description = $request->input('Description', '');

        if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->save();

        return redirect()->route('admin.dashboard')->with('success', 'Product Updated Successfully');
    }

    // DELETE
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Product Deleted Successfully');
    }

    // RESTOCK
    public function restock(Request $request, $id)
    {
        $request->validate([
            'stock_added' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($id);
        $product->StockQuantity += $request->stock_added;
        $product->save();

        return redirect()->route('admin.dashboard')->with('success', 'Stock updated successfully');
    }

    // Existing Sales Report Logic
    public function salesReport()
    {
        $customers = Customer::whereHas('orders')->with(['orders.items.product', 'orders' => function($query) {
            $query->where('Status', 'Completed');
        }])->get();

        return view('admin.sales', compact('customers'));
    }
}