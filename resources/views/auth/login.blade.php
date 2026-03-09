@extends('layouts.app')

@section('styles')
<style>
    .hover-scale:hover {
        transform: translateY(-2px);
    }
    .transition-all {
        transition: all 0.3s ease;
    }
    .hover-text-success:hover {
        color: #198754 !important; /* Bootstrap success color */
    }
    
    /* Custom Input Focus */
    .form-control:focus {
        box-shadow: none;
        background-color: #fff !important;
        border: 1px solid #198754 !important; /* Green border on focus */
    }
    .form-control:focus + .input-group-text, 
    .input-group:focus-within .input-group-text {
        background-color: #fff !important;
        border-top: 1px solid #198754 !important;
        border-bottom: 1px solid #198754 !important;
        border-left: 1px solid #198754 !important;
    }
    .input-group:focus-within .form-control {
        border-left: 0 !important; /* Remove left border so it merges with icon */
    }
</style>
@endsection

@php
    $slideImages = [
        asset('images/b10.png'),
        asset('images/bb1.png'),
        asset('images/bb2.png'),
        asset('images/bb3.png'),
        asset('images/bb4.png'),
        asset('images/bb5.png'),
        asset('images/bb6.png'),
        asset('images/bb7.png'),
        asset('images/bb8.png'),
        asset('images/bb10.png')
    ];
    $initialImage = $slideImages[array_rand($slideImages)];
@endphp

@section('content')

<div class="min-vh-100 d-flex align-items-center justify-content-center py-5" style="background: linear-gradient(135deg, #f0fdf4 0%, #e6fffa 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-10 col-xl-9">
                <div class="card border-0 shadow-lg rounded-5 overflow-hidden">
                    <div class="row g-0">
                        <!-- Left Side - Image/Slider (Hidden on mobile) -->
                        <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center bg-light">
                            <img id="slideImage" src="{{ $initialImage }}" alt="Login Image" 
                                 class="img-fluid rounded-4" 
                                 style="transition: opacity 0.5s ease-in-out; max-width: 80%;">
                        </div>

                        <!-- Right Side - Form -->
                        <div class="col-lg-6 p-4 p-md-5 bg-white">
                             <div class="d-flex align-items-center justify-content-between mb-4">
                                <a href="/" class="text-decoration-none text-muted small fw-semibold hover-text-success">
                                    <i class="bi bi-arrow-left me-1"></i> Home
                                </a>
                                <span class="badge bg-success-subtle text-success rounded-pill px-3">
                                    {{ ucwords(str_replace('_', ' ', $role)) }}
                                </span>
                             </div>
                             
                             <div class="text-center text-lg-start mb-4">
                                <h2 class="fw-bold text-dark">Welcome Back!</h2>
                                <p class="text-muted">Please enter your details to sign in.</p>
                             </div>

                             @if ($errors->any())
                                <div class="alert alert-danger rounded-4 mb-4 border-0 bg-danger-subtle text-danger">
                                    <ul class="mb-0 small ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                             @endif

                             <form method="POST" action="{{ route('auth.login.post', ['role' => $role]) }}">
                                @csrf
                                <input type="hidden" name="role" value="{{ $role === 'clinic_staff' ? 'clinic' : $role }}">

                                <div class="mb-3">
                                    <label class="form-label text-secondary small fw-bold text-uppercase">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0 rounded-start-4 ps-3">
                                            <i class="bi bi-envelope text-muted"></i>
                                        </span>
                                        <input type="email" name="email" 
                                               class="form-control form-control-lg bg-light border-0 rounded-end-4" 
                                               value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-secondary small fw-bold text-uppercase">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0 rounded-start-4 ps-3">
                                            <i class="bi bi-lock text-muted"></i>
                                        </span>
                                        <input type="password" name="password" 
                                               class="form-control form-control-lg bg-light border-0 rounded-end-4" 
                                               required placeholder="••••••••">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill fw-bold shadow-sm hover-scale transition-all">
                                    Log In
                                </button>
                                
                                <div class="text-center mt-3">
                                    <a href="{{ route('password.request', ['role' => $role]) }}" class="text-secondary small fw-bold text-decoration-none hover-text-success">
                                        <i class="bi bi-shield-lock me-1"></i> Forgot Password?
                                    </a>
                                </div>
                                
                                <div class="text-center mt-4">
                                    <p class="small text-muted mb-0">Don't have an account? 
                                        <a href="{{ route('auth.signup', ['role' => $role]) }}" class="text-success fw-bold text-decoration-none">Sign Up</a>
                                    </p>
                                </div>
                             </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const images = @json($slideImages);

    let index = images.indexOf("{{ $initialImage }}");
    const slideImage = document.getElementById('slideImage');

    if(slideImage) {
        // Preload
        images.forEach(src => new Image().src = src);

        setInterval(() => {
            slideImage.style.opacity = 0;
            setTimeout(() => {
                let nextIndex;
                do {
                    nextIndex = Math.floor(Math.random() * images.length);
                } while (nextIndex === index);
                index = nextIndex;
                slideImage.src = images[index];
                slideImage.style.opacity = 1;
            }, 500);
        }, 5000);
    }
</script>
@endsection
