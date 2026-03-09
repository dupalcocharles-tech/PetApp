@extends('layouts.app')

@section('styles')
<style>
    .fade-in-up {
        animation: fadeInUp 1s ease-out forwards;
        opacity: 0;
    }
    
    .fade-in-right {
        animation: fadeInRight 1s ease-out forwards;
        opacity: 0;
        animation-delay: 0.3s;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeInRight {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .hover-scale {
        transition: transform 0.2s ease;
    }
    
    .hover-scale:hover {
        transform: translateY(-3px);
    }

    .hero-badge {
        font-size: 0.8rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        border-radius: 999px;
        padding: 0.25rem 0.9rem;
    }

    .hero-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        box-shadow: 0 14px 35px rgba(15, 118, 110, 0.12);
        backdrop-filter: blur(6px);
        padding: 1.5rem 1.75rem;
    }

    .stat-pill {
        border-radius: 999px;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }

    .feature-card {
        border-radius: 20px;
        border: 1px solid rgba(148, 163, 184, 0.25);
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 14px 35px rgba(15, 23, 42, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 18px 50px rgba(15, 23, 42, 0.12);
    }

    .admin-access-link {
        font-size: 0.7rem;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        padding: 0.25rem 0.9rem;
        border-radius: 999px;
        border-width: 1px;
        opacity: 0.7;
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(6px);
        transition: opacity 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
    }

    .admin-access-link:hover {
        opacity: 1;
        transform: translateY(-1px);
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.18);
    }

    @media (max-width: 991px) {
        .hero-card {
            text-align: center;
            padding: 2rem 1.5rem;
        }
        .hero-card p {
            margin-left: auto;
            margin-right: auto;
        }
        .stat-pill {
            justify-content: center;
            width: 100%;
        }
        .hero-card .d-flex.flex-wrap {
            justify-content: center;
        }
        #slideImage {
            max-height: 350px !important;
            margin-top: 1rem;
        }
    }

    @media (max-width: 576px) {
        .hero-card {
            padding: 1.5rem 1.25rem;
            border-radius: 24px;
        }
        .hero-card h1 {
            font-size: 2.1rem;
            line-height: 1.2;
        }
        .hero-card p {
            font-size: 1rem;
        }
        .navbar-brand span {
            font-size: 1.4rem !important;
        }
        .btn-lg {
            padding-top: 0.8rem;
            padding-bottom: 0.8rem;
            font-size: 1rem;
        }
    }

    /* Floating Bubbles */
    .bubble-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        pointer-events: none;
        z-index: 0;
    }

    .floating-bubble {
        position: absolute;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0.15;
        box-shadow: 0 8px 32px rgba(15, 118, 110, 0.1);
        animation: float-around 20s infinite ease-in-out;
    }

    .floating-bubble img {
        width: 65%;
        height: 65%;
        object-fit: contain;
        filter: grayscale(20%);
    }

    @keyframes float-around {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        25% { transform: translate(20px, -30px) rotate(5deg); }
        50% { transform: translate(-15px, 20px) rotate(-5deg); }
        75% { transform: translate(25px, 15px) rotate(3deg); }
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

    $bubbleIcons = [
        'cats.png', 'chickens.png', 'cows.png', 'dogs.png', 'ducks.png', 
        'geese.png', 'goats.png', 'guinea_pigs.png', 'hamsters.png', 
        'horses.png', 'mice.png', 'parrots.png', 'pigs.png', 
        'rabbits.png', 'rats.png', 'sheep.png', 'turkeys.png'
    ];
@endphp

@section('content')
<div class="min-vh-100 d-flex flex-column position-relative" style="background: radial-gradient(circle at top left, #bbf7d0 0, rgba(187, 247, 208, 0) 55%), radial-gradient(circle at bottom right, #a5f3fc 0, rgba(165, 243, 252, 0) 55%), linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 100%);">
    <!-- Floating Bubbles Background -->
    <div class="bubble-container d-none d-md-block">
        @foreach(array_rand(array_flip($bubbleIcons), 15) as $index => $icon)
            @php
                $size = rand(100, 180);
                $top = rand(0, 95);
                $left = rand(0, 95);
                $delay = rand(0, 15);
                $duration = rand(20, 35);
            @endphp
            <div class="floating-bubble" 
                 style="width: {{ $size }}px; height: {{ $size }}px; top: {{ $top }}%; left: {{ $left }}%; 
                        animation-delay: -{{ $delay }}s; animation-duration: {{ $duration }}s;">
                <img src="{{ asset('images/' . $icon) }}" alt="Animal Icon">
            </div>
        @endforeach
    </div>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light py-3 px-3 px-lg-5 position-relative" style="z-index: 1;">
        <div class="container-fluid px-0">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <img src="{{ asset('images/offlogo.png') }}" alt="PetApp Logo" width="50" height="50" class="d-inline-block align-text-top rounded-circle shadow-sm">
                <span class="fw-bold fs-3 text-success">PetApp</span>
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler border-0 shadow-none p-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <div class="bg-success-subtle p-2 rounded-3">
                    <span class="navbar-toggler-icon"></span>
                </div>
            </button>

            <!-- Nav Links -->
            <div class="collapse navbar-collapse justify-content-end mt-3 mt-lg-0" id="navbarNav">
                <div class="d-flex flex-column flex-lg-row gap-2">
                    <a href="{{ route('auth.signup', ['role' => 'pet_owner']) }}" class="btn btn-outline-success rounded-pill px-4 fw-semibold">
                        Pet Owner Sign Up
                    </a>
                    <a href="{{ route('auth.signup', ['role' => 'clinic_staff']) }}" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold">
                        Clinic Staff Sign Up
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container flex-grow-1 d-flex align-items-center justify-content-center py-4 position-relative" style="z-index: 1;">
        <div class="row w-100 align-items-center g-4">
            <div class="col-12 col-lg-6 fade-in-up">
                <div class="hero-card">
                    <div class="d-flex justify-content-center d-lg-block mb-3">
                        <span class="hero-badge bg-success-subtle text-success border border-success-subtle">
                            Digital Pet Care Hub
                        </span>
                    </div>
                    <h1 class="display-5 fw-bold text-dark mb-3 lh-sm">
                        Your Pet Deserves <br>
                        <span class="text-success">Better Care</span>
                    </h1>
                    <p class="lead text-secondary mb-4" style="max-width: 520px;">
                        Book appointments, connect with trusted veterinary clinics, and keep all your pet’s records in one friendly dashboard.
                    </p>

                    <div class="d-flex flex-column flex-sm-row gap-3 mb-4">
                        <a href="{{ route('auth.login', ['role' => 'pet_owner']) }}" class="btn btn-success btn-lg rounded-pill px-5 shadow-sm fw-bold hover-scale flex-fill">
                            Pet Owner Login
                        </a>
                        <a href="{{ route('auth.login', ['role' => 'clinic_staff']) }}" class="btn btn-outline-dark btn-lg rounded-pill px-5 shadow-sm fw-bold hover-scale flex-fill">
                            Clinic Staff Login
                        </a>
                    </div>

                    <div class="d-flex flex-wrap gap-2 gap-sm-3 text-start justify-content-center justify-content-lg-start">
                        <div class="stat-pill bg-success-subtle text-success d-flex align-items-center gap-2">
                            <span class="fw-bold">24/7</span>
                            <span class="text-secondary small">Access to your records</span>
                        </div>
                        <div class="stat-pill bg-info-subtle text-info d-flex align-items-center gap-2">
                            <span class="fw-bold">Clinic-ready</span>
                            <span class="text-secondary small">Built for vets</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 text-center position-relative fade-in-right">
                <div class="position-relative d-inline-block">
                    <img id="slideImage" src="{{ $initialImage }}" alt="Pet App" 
                        class="img-fluid rounded-4" 
                        style="max-height: 500px; object-fit: cover; width: 100%; transition: opacity 0.8s ease-in-out;">
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-4 d-none d-md-block position-relative" style="z-index: 1;">
        <div class="row g-4">
            <div class="col-12 col-md-4">
                <div class="feature-card h-100 p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-heart-pulse"></i>
                        </div>
                        <h6 class="fw-bold mb-0 ms-3 text-dark">For Pet Owners</h6>
                    </div>
                    <p class="text-secondary small mb-0">
                        Track appointments, view vet notes, and keep your pet’s history organized in one place.
                    </p>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="feature-card h-100 p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-hospital"></i>
                        </div>
                        <h6 class="fw-bold mb-0 ms-3 text-dark">For Clinics</h6>
                    </div>
                    <p class="text-secondary small mb-0">
                        Manage schedules, patients, and medical records with tools built for veterinary teams.
                    </p>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="feature-card h-100 p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h6 class="fw-bold mb-0 ms-3 text-dark">Secure & Friendly</h6>
                    </div>
                    <p class="text-secondary small mb-0">
                        Built with secure sign-in for admins, clinics, and pet owners on any device.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="w-100 py-2 text-center mt-auto mb-2 position-relative" style="z-index: 1;">
        <a href="{{ route('admin.login') }}" class="btn btn-outline-secondary border-0 admin-access-link d-inline-flex align-items-center gap-1">
            <i class="bi bi-shield-lock"></i>
            <span>Admin Access</span>
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const images = @json($slideImages);

    let index = images.indexOf("{{ $initialImage }}");
    const slideImage = document.getElementById('slideImage');

    // Preload images
    images.forEach(src => {
        const img = new Image();
        img.src = src;
    });

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
        }, 800);
    }, 5000);
</script>
@endsection
