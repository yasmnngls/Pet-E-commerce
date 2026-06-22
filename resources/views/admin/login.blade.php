@extends('common.main2')
@section('title', 'Admin Portal')
@section('content')

<div class="container-fluid p-0">
    <div class="row g-0 min-vh-100"> 
        
        <div class="col-12 col-md-4 d-flex align-items-center justify-content-center" style="background-color: #2f3542;">
            <div class="card p-5 border-0 shadow-lg rounded-4" style="width: 85%; max-width: 450px; background-color: #ffffff;">
                
                <h5 class="fw-bold mb-0" style="color: #a52a2a;">
                    <i class="bi bi-shield-lock-fill"></i> PowerPuff Pets
                </h5>
                <p class="text-muted small mb-4">Staff Backoffice Management</p>
                
                <h2 class="fw-bold mb-4 text-dark">Admin Sign In</h2>
                
                @if ($errors->any())
                    <div class="alert alert-danger py-2 small mb-4">
                        @foreach ($errors->all() as $error)
                            <div class="fw-bold">{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                
                <form action="{{ route('admin.login.submit') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small text-muted fw-bold">Admin Email</label>
                        <input type="email" name="email" class="form-control" placeholder="admin@powerpuffpets.com" required value="{{ old('email') }}">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label small text-muted fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    
                    <button type="submit" class="btn w-100 text-white fw-bold rounded-pill py-2" style="background-color: #a52a2a;">
                        ACCESS PORTAL
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-8 d-flex align-items-center justify-content-center" style="background-color: #f1f2f6;">
            <div class="text-center">
                <i class="bi bi-database-fill-gear" style="font-size: 6rem; color: #a52a2a; opacity: 0.8;"></i>
                <h3 class="fw-bold text-dark mt-3">Backoffice Control Environment</h3>
                <p class="text-muted small">Manage promotional banners, monitor live transactions, and process vendor applications.</p>
            </div>
        </div>
        
    </div>
</div>

@endsection