@extends('layout')

@section('content')
<div style="max-width: 500px; margin: 30px auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
    <h2 style="text-align: center;">Create Account</h2>
    
    <form action="{{ route('register.post') }}" method="POST">
        @csrf
        
        <div style="display: flex; gap: 10px; margin-bottom: 15px;">
            <div style="flex: 1;">
                <label>First Name</label>
                <input type="text" name="FirstName" value="{{ old('FirstName') }}" required style="width: 100%; padding: 8px; margin-top: 5px;">
            </div>
            <div style="flex: 1;">
                <label>Last Name</label>
                <input type="text" name="LastName" value="{{ old('LastName') }}" required style="width: 100%; padding: 8px; margin-top: 5px;">
            </div>
        </div>

        <div style="margin-bottom: 15px;">
            <label>Email</label>
            <input type="email" name="Email" value="{{ old('Email') }}" required style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label>Phone Number</label>
            <input type="text" name="Phone" value="{{ old('Phone') }}" required style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label>Address</label>
            <textarea name="Address" required style="width: 100%; padding: 8px; margin-top: 5px;">{{ old('Address') }}</textarea>
        </div>

        <div style="margin-bottom: 15px;">
            <label>Password</label>
            <input type="password" name="password" required style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" required style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
        
        <p style="text-align: center; margin-top: 15px;">
            Already have an account? <a href="{{ route('login') }}">Login here</a>
        </p>
    </form>

    @if($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 10px; margin-top: 15px; border-radius: 4px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection