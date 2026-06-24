@extends('common.main1')
@section('title', 'Login')
@section('content')

<div class="container-fluid p-0">
    
    <div class="row g-0 min-vh-100"> 
        
        <div class="col-12 col-md-4 d-flex align-items-center justify-content-center" style="background-color: brown;">
            
            <div class="card p-5 border-0 shadow-lg rounded-4" style="width: 85%; max-width: 450px;">
                <h5 class="fw-bold text-danger mb-0">
                    <i class="bi bi-shop"></i> PowerPuff Pets
                </h5>
                
                @if (request()->is('login/register') || old('username'))
                    
                    <h2 class="fw-bold mb-4">Create an Account</h2>

                    @if ($errors->any())
                        <div class="alert alert-danger py-2 small mb-3">
                            @foreach ($errors->all() as $error)
                                <div class="fw-bold">{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                    
                    <form action="{{ route('register.submit') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Username</label>
                            <input type="text" name="username" class="form-control px-3 py-2" required placeholder="e.g. Juan Dela Cruz" value="{{ old('username') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Email address</label>
                            <input type="email" name="email" class="form-control px-3 py-2" required placeholder="name@example.com" value="{{ old('email') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Password</label>
                            <input type="password" name="password" class="form-control px-3 py-2" required placeholder="••••••••">
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control px-3 py-2" required placeholder="••••••••">
                        </div>

                        <button type="submit" class="btn btn-danger w-100 rounded-pill py-2">REGISTER</button>
                    </form>

                    <div class="text-center mt-4 small">
                        <span class="text-muted">Already have an account?</span> 
                        <a href="{{ route('login') }}" style="color: #a52a2a; text-decoration: none; font-weight: bold;">Sign in</a>
                    </div>

                @else

                    <h2 class="fw-bold mb-4">Sign in</h2>
                    
                    @if ($errors->any())
                        <div class="alert alert-danger py-2 small mb-4">
                            @foreach ($errors->all() as $error)
                                <div class="fw-bold">{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                    
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small text-muted">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="example@gmail.com" value="{{ old('email') }}" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label small text-muted">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                        
                        <button type="submit" class="btn btn-danger w-100 rounded-pill py-2">SIGN IN</button>
                    </form>
                    
                    <div class="text-center mt-4 small">
                        <span class="text-muted">Don't have an account?</span> 
                        <a href="{{ route('register') }}" style="color: #a52a2a; text-decoration: none; font-weight: bold;">Sign up</a>
                    </div>

                @endif

            </div>
        </div>

        <div class="col-md-7 col-lg-8 d-none d-md-block position-relative vh-100 overflow-hidden" style="background-color: #F8EFE4;">
            <img src="{{ asset('storage/banners/login.png') }}" 
                 alt="Shopping Illustration" 
                 class="position-absolute top-0 start-0 w-100 h-100" 
                 style="object-fit: cover; object-position: center;">
        </div>
        
    </div>

</div>

@endsection