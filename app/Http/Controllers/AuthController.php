<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;

class AuthController extends Controller
{
    // Show Login Form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle Login Logic
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'Email' => 'required|email',
            'password' => 'required'
        ]);

        // Custom Auth attempt because our table is 'customers', not 'users'
        // We use the 'web' guard but need to configure it (see Step 6) 
        // OR we can manually check for simplicity in this project scope:
        
        $customer = Customer::where('Email', $request->Email)->first();

        if ($customer && Hash::check($request->password, $customer->password)) {
            Auth::login($customer);
            $request->session()->regenerate();

            if ($customer->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('checkout.index');
        }

        return back()->withErrors([
            'Email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}