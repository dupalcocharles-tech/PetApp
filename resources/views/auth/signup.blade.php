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
        color: #198754 !important;
    }
    
    /* Custom Input Focus */
    .form-control:focus {
        box-shadow: none;
        background-color: #fff !important;
        border: 1px solid #198754 !important;
    }
    .form-control:focus + .input-group-text, 
    .input-group:focus-within .input-group-text {
        background-color: #fff !important;
        border-top: 1px solid #198754 !important;
        border-bottom: 1px solid #198754 !important;
        border-left: 1px solid #198754 !important;
    }
    .input-group:focus-within .form-control {
        border-left: 0 !important;
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
                        <div class="col-lg-5 d-none d-lg-flex align-items-center justify-content-center bg-light">
                            <img id="slideImage" src="{{ $initialImage }}" alt="Signup Image" 
                                 class="img-fluid rounded-4" 
                                 style="transition: opacity 0.5s ease-in-out; max-width: 90%;">
                        </div>

                        <!-- Right Side - Form -->
                        <div class="col-lg-7 p-4 p-md-5 bg-white">
                             <div class="d-flex align-items-center justify-content-between mb-4">
                                <a href="/" class="text-decoration-none text-muted small fw-semibold hover-text-success">
                                    <i class="bi bi-arrow-left me-1"></i> Home
                                </a>
                                <span class="badge bg-success-subtle text-success rounded-pill px-3">
                                    {{ ucwords(str_replace('_', ' ', $role)) }}
                                </span>
                             </div>
                             
                             <div class="text-center text-lg-start mb-4">
                                <h2 class="fw-bold text-dark">Create Account</h2>
                                <p class="text-muted">Join us today! Enter your details below.</p>
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

                             <form method="POST" action="{{ route('auth.signup.post', $role) }}" enctype="multipart/form-data">
                                @csrf

                                <div class="row g-3">
                                    {{-- Username --}}
                                    <div class="col-md-6">
                                        <label class="form-label text-secondary small fw-bold text-uppercase">Username</label>
                                        <input type="text" name="username" class="form-control bg-light border-0 rounded-4 px-3 py-2" value="{{ old('username') }}" required>
                                    </div>

                                    {{-- Email --}}
                                    <div class="col-md-6">
                                        <label class="form-label text-secondary small fw-bold text-uppercase">Email</label>
                                        <input type="email" name="email" class="form-control bg-light border-0 rounded-4 px-3 py-2" value="{{ old('email') }}" required>
                                    </div>

                                    {{-- Password --}}
                                    <div class="col-md-6">
                                        <label class="form-label text-secondary small fw-bold text-uppercase">Password</label>
                                        <input type="password" name="password" class="form-control bg-light border-0 rounded-4 px-3 py-2" required>
                                        <div class="form-text small text-muted mt-1" style="font-size: 0.7rem;">
                                            At least 8 chars, 1 uppercase, 1 number & 1 symbol.
                                        </div>
                                    </div>

                                    {{-- Confirm Password --}}
                                    <div class="col-md-6">
                                        <label class="form-label text-secondary small fw-bold text-uppercase">Confirm Password</label>
                                        <input type="password" name="password_confirmation" class="form-control bg-light border-0 rounded-4 px-3 py-2" required>
                                    </div>
                                </div>
                                
                                <hr class="my-4 border-light">

                                {{-- CLINIC STAFF FIELDS --}}
                                @if($role === 'clinic_staff')
                                    <div class="mb-3">
                                        <label class="form-label text-secondary small fw-bold text-uppercase">Clinic Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0 rounded-start-4 ps-3"><i class="bi bi-hospital text-muted"></i></span>
                                            <input type="text" name="clinic_name" class="form-control bg-light border-0 rounded-end-4" required>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label text-secondary small fw-bold text-uppercase">Address</label>
                                            <input type="text" name="address" class="form-control bg-light border-0 rounded-4 px-3 py-2" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-secondary small fw-bold text-uppercase">Phone <small class="text-muted fw-normal text-lowercase"></small></label>
                                            <input type="text" name="phone" class="form-control bg-light border-0 rounded-4 px-3 py-2">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label text-secondary small fw-bold text-uppercase">Upload Verification Documents</label>
                                        <input type="file" name="documents[]" class="form-control bg-light border-0 rounded-4" multiple>
                                        <div class="form-text small"><i class="bi bi-info-circle me-1"></i> Images or PDFs (max 5MB)</div>
                                    </div>
                                @endif

                                {{-- PET OWNER FIELDS --}}
                                @if($role === 'pet_owner')
                                    <div class="mb-3">
                                        <label class="form-label text-secondary small fw-bold text-uppercase">Full Name <small class="text-muted fw-normal text-lowercase"></small></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0 rounded-start-4 ps-3"><i class="bi bi-person text-muted"></i></span>
                                            <input type="text" name="full_name" class="form-control bg-light border-0 rounded-end-4">
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label text-secondary small fw-bold text-uppercase">Phone</label>
                                            <input type="text" name="phone" class="form-control bg-light border-0 rounded-4 px-3 py-2" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-secondary small fw-bold text-uppercase">Address</label>
                                            <input type="text" name="address" class="form-control bg-light border-0 rounded-4 px-3 py-2" required>
                                        </div>
                                    </div>
                                @endif

                                <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill fw-bold shadow-sm hover-scale transition-all">
                                    Sign Up
                                </button>
                                
                                <div class="text-center mt-4">
                                    <p class="small text-muted mb-0">Already have an account? 
                                        <a href="{{ route('auth.login', ['role' => $role]) }}" class="text-success fw-bold text-decoration-none">Log In</a>
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
