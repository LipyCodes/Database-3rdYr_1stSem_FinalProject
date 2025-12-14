<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    // Show Register Form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle Registration Logic
    public function register(Request $request)
    {
        $request->validate([
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'Email' => 'required|string|email|max:255|unique:customers',
            'Phone' => 'required|numeric',
            'Address' => 'required|string',
            'password' => 'required|string|min:6|confirmed', // expects password_confirmation field
        ]);

        $customer = Customer::create([
            'FirstName' => $request->FirstName,
            'LastName' => $request->LastName,
            'Email' => $request->Email,
            'Phone' => $request->Phone,
            'Address' => $request->Address,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'CreatedAt' => now(),
        ]);

        // Auto-login after registration
        Auth::login($customer);

        return redirect()->route('checkout.index')->with('success', 'Account created successfully!');
    }
}