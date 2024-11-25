@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4" style="width: 400px; box-shadow: 0px 4px 8px rgba(0,0,0,0.1); border-radius: 8px;">
        <div class="text-center mb-4">
            <!-- Logo -->
            <img src="{{ asset('uz.png') }}" alt="Logo" style="width: 75px;">
        </div>
        <h4 class="text-center mb-3">{{ __('Sign in to your account') }}</h4>
        
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group mb-3">
                <label for="username" class="form-label">Email</label>
                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" required autofocus >
                @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
            </div>

            

            <button type="submit" class="btn btn-dark w-100">Login</button>
        </form>
    </div>
</div>
@endsection
