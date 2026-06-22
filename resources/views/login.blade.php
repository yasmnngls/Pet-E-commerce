@extends('common.main1')
@section('title', 'Login')
@section('content')

<div class="container-fluid p-0">
    
    <div class="row g-0 min-vh-100"> 
        
        <div class="col-12 col-md-4 d-flex align-items-center justify-content-center" style="background-color: brown;">
            
            <div class="card p-5 border-0 shadow-lg rounded-4" style="width: 85%; max-width: 450px;">
                <h5 class="fw-bold text-danger mb-0">Logo Here</h5>
                <p class="text-muted small mb-4">Welcome back !!!</p>
                
                <h2 class="fw-bold mb-4">Sign in</h2>
                
                <form>
                    <div class="mb-3">
                        <label class="form-label small text-muted">Email</label>
                        <input type="email" class="form-control" placeholder="example@gmail.com">
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between">
                            <label class="form-label small text-muted">Password</label>
                        </div>
                        <input type="password" class="form-control" placeholder="••••••••">
                    </div>
                    
                    <button type="submit" class="btn btn-danger w-100 rounded-pill py-2">SIGN IN</button>
                </form>
                
                <div class="text-center mt-4 small">
                    <span class="text-muted">Don't have an account ?</span> 
                    <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal" style="color: #a52a2a; text-decoration: none;">Sign up</a>
                </div>
            </div>

        </div>

        <div class="col-lg-8 d-flex align-items-center justify-content-center" style="background-color: antiquewhite;">
            
            <img src="{{ asset('images/snoopy3.jpg') }}" alt="Shopping Illustration" class="img-fluid" style="max-width: 75%;">
            
        </div>
        
    </div>

    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            
            <div class="modal-header text-white" style="background-color: #a52a2a;">
                <h5 class="modal-title fw-bold" id="registerModalLabel">Create an Account</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                
                <form onsubmit="event.preventDefault(); alert('Frontend Test: Form Submitted!');">

                    <div class="mb-3">
                        <label for="register-email" class="form-label text-muted small fw-bold">Email address</label>
                        <input type="email" class="form-control px-3 py-2" id="register-email" required placeholder="name@example.com">
                    </div>

                    <div class="mb-3">
                        <label for="register-password" class="form-label text-muted small fw-bold">Password</label>
                        <input type="password" class="form-control px-3 py-2" id="register-password" required placeholder="••••••••">
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label text-muted small fw-bold">Confirm Password</label>
                        <input type="password" class="form-control px-3 py-2" id="password_confirmation" required placeholder="••••••••">
                    </div>

                    <button type="submit" class="btn w-100 text-white fw-bold py-2 mt-2" style="background-color: #a52a2a; border-radius: 8px;">
                        REGISTER
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
</div>



@endsection