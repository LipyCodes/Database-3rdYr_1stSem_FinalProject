@extends('layout')

@section('content')
<div style="max-width: 400px; margin: 50px auto; background: rgb(255, 255, 255); padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(233, 14, 14, 0.1);">
    
    <div style="text-align: center; margin-bottom: 20px;">
        <img src="{{ asset('images/logo2.png') }}" alt="Logo" style="max-width: 400px; height: 200px;">
    </div>

    <h2 style="text-align: center;">Login</h2>
    
    <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div style="margin-bottom: 15px;">
            <label>Email</label>
            <input type="email" name="Email" required style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>
        <div style="margin-bottom: 15px;">
            <label>Password</label>
            <input type="password" name="password" required style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        
        <p style="text-align: center; margin-top: 20px;">
            Don't have an account yet? <a href="{{ route('register') }}">Sign up</a>
        </p>
    </form>
    
    @if($errors->any())
        <p style="color: red; margin-top: 10px; text-align: center;">{{ $errors->first() }}</p>
    @endif
</div>
@endsection