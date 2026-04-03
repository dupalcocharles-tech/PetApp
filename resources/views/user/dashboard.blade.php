@extends('layouts.app')

@section('content')
@php
    /**
     * Get the actual image path for each animal.
     */
    function animalImagePath($imageFilename) {
        return asset("images/{$imageFilename}");
    }

    $mammals = [
        ['name' => 'Dogs', 'image' => 'dogs.png'],
        ['name' => 'Cats', 'image' => 'cats.png'],
        ['name' => 'Rabbits', 'image' => 'rabbits.png'],
        ['name' => 'Cows', 'image' => 'cows.png'],
        ['name' => 'Sheep', 'image' => 'sheep.png'],
        ['name' => 'Goats', 'image' => 'goats.png'],
        ['name' => 'Pigs', 'image' => 'pigs.png'],
        ['name' => 'Horses', 'image' => 'horses.png'],
    ];
    $birds = [
        ['name' => 'Chickens', 'image' => 'chickens.png'],
        ['name' => 'Ducks', 'image' => 'ducks.png'],
        ['name' => 'Turkeys', 'image' => 'turkeys.png'],
        ['name' => 'Geese', 'image' => 'geese.png'],
        ['name' => 'Parrots', 'image' => 'parrots.png'],
    ];
    $rodents = [
        ['name' => 'Hamsters', 'image' => 'hamsters.png'],
        ['name' => 'Guinea pigs', 'image' => 'guinea_pigs.png'],
        ['name' => 'Mice', 'image' => 'mice.png'],
        ['name' => 'Rats', 'image' => 'rats.png'],
    ];

    $categories = [
        ['id' => 'mammals', 'name' => 'Mammals', 'image' => 'images/paw1.webp', 'animals' => $mammals],
        ['id' => 'birds', 'name' => 'Birds', 'image' => 'images/bird.png', 'animals' => $birds],
        ['id' => 'rodents', 'name' => 'Rodents', 'image' => 'images/rodent.png', 'animals' => $rodents],
    ];
@endphp

<div class="container-fluid">
    <div class="row">
       {{-- Sidebar --}}
<nav id="sidebarMenu"
     class="col-md-3 col-lg-2 d-md-block sidebar position-fixed shadow bg-dark"
     style="top:0; left:0; height:100vh; z-index:1050; transition: transform 0.3s ease-in-out;" 
     role="navigation" aria-label="Main sidebar">
    
    <div class="d-flex flex-column h-100 p-3">

        {{-- Brand --}}
        <div class="d-flex align-items-center justify-content-between mb-4 px-2 pt-2 sidebar-header">
            <div class="d-flex align-items-center brand-container">
                <img src="{{ asset('images/offlogo.png') }}" alt="PetApp" class="me-2 rounded-3" style="width: 32px; height: 32px;">
                <span class="fs-4 fw-bold text-white tracking-tight brand-text">PetApp</span>
            </div>
            <button id="desktopSidebarToggle" class="btn btn-link text-white-50 p-0 d-none d-md-block text-decoration-none">
                <i class="bi bi-chevron-left fs-5 transition-transform"></i>
            </button>
        </div>

        {{-- Profile section --}}
        <div class="card bg-white bg-opacity-10 border-0 mb-4 rounded-4 overflow-hidden profile-section">
            <div class="card-body p-3 d-flex align-items-center transition-all">
                <img src="{{ Auth::user()->profile_image ? asset('storage/'.Auth::user()->profile_image) : asset('images/owner.png') }}"
                     class="rounded-circle me-3 border border-2 border-white shadow-sm object-fit-cover" 
                     alt="Profile" style="width:45px;height:45px;">
                <div class="overflow-hidden profile-info">
                    <h6 class="fw-bold mb-0 text-white text-truncate" style="font-size: 0.95rem;">
                        {{ Auth::user()->username ?? Auth::user()->name ?? 'User' }}
                    </h6>
                    <small class="text-white-50" style="font-size: 0.75rem;">Pet Owner</small>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <div class="flex-grow-1">
            <ul class="nav flex-column gap-2">
                
                {{-- Dashboard (Active) --}}
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-2 rounded-3 text-white {{ request()->routeIs('pet_owner.dashboard') ? 'bg-success shadow-sm' : 'text-opacity-75 hover-bg-white-10' }}" 
                       href="{{ route('pet_owner.dashboard') }}"
                       data-bs-toggle="tooltip" data-bs-placement="right" title="Dashboard">
                        <i class="bi bi-grid-fill me-3 fs-5"></i>
                        <span class="fw-medium">Dashboard</span>
                    </a>
                </li>

                {{-- Profile Settings --}}
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-2 rounded-3 text-white {{ request()->routeIs('pet_owner.edit') ? 'bg-success shadow-sm' : 'text-opacity-75 hover-bg-white-10' }}" 
                       href="{{ route('pet_owner.edit') }}"
                       data-bs-toggle="tooltip" data-bs-placement="right" title="Profile Settings">
                        <i class="bi bi-person-gear me-3 fs-5"></i>
                        <span class="fw-medium">Profile Settings</span>
                    </a>
                </li>

                {{-- Add New Pet --}}
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-2 rounded-3 text-white {{ request()->routeIs('pets.create') ? 'bg-success shadow-sm' : 'text-opacity-75 hover-bg-white-10' }}" 
                       href="{{ route('pets.create') }}"
                       data-bs-toggle="tooltip" data-bs-placement="right" title="Add New Pet">
                        <i class="bi bi-plus-circle-fill me-3 fs-5"></i>
                        <span class="fw-medium">Add New Pet</span>
                    </a>
                </li>

                {{-- Vet Notes --}}
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-2 rounded-3 text-white text-opacity-75 hover-bg-white-10" 
                       href="#" data-bs-toggle="modal" data-bs-target="#vetNotesModal"
                       data-bs-toggle="tooltip" data-bs-placement="right" title="Vet Notes">
                        <i class="bi bi-journal-medical me-3 fs-5"></i>
                        <span class="fw-medium">Vet Notes</span>
                    </a>
                </li>

                {{-- Theme Toggler --}}
                <li class="nav-item mt-2 theme-section">
                    <div class="d-flex align-items-center px-3 py-2 text-white text-opacity-75 theme-label-container">
                        <i class="bi bi-palette-fill me-3 fs-5"></i>
                        <span class="fw-medium theme-text">Appearance</span>
                    </div>
                    <div class="d-flex gap-2 px-3 mt-1 theme-buttons-container">
                         <button class="btn btn-sm btn-outline-light w-50 theme-option d-flex align-items-center justify-content-center" data-theme="light" title="Light Mode">
                            <i class="bi bi-sun-fill me-1"></i> <span class="theme-btn-text">Light</span>
                         </button>
                         <button class="btn btn-sm btn-outline-light w-50 theme-option d-flex align-items-center justify-content-center" data-theme="dark" title="Dark Mode">
                            <i class="bi bi-moon-stars-fill me-1"></i> <span class="theme-btn-text">Dark</span>
                         </button>
                    </div>
                </li>
            </ul>
        </div>

        {{-- Bottom Section (Logout) --}}
        <div class="mt-auto pt-3 border-top border-white border-opacity-10">
            <a class="nav-link d-flex align-items-center px-3 py-2 rounded-3 text-danger hover-bg-danger-10" 
               href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"
               data-bs-toggle="tooltip" data-bs-placement="right" title="Log out">
                <i class="bi bi-box-arrow-right me-3 fs-5"></i>
                <span class="fw-bold">Log out</span>
            </a>
        </div>
    </div>
</nav>
               

        {{-- Main Content --}}
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" id="mainContent" tabindex="-1">
            {{-- Welcome Container --}}
            {{-- Desktop Header & Hero --}}
            <div class="py-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4 rounded-4 shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-4 shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Hero Card --}}
                <div class="card border-0 rounded-4 shadow-sm overflow-hidden mb-4 position-relative hero-card">
                    <div class="card-body p-4 p-lg-5 position-relative z-1">
                        <div class="row align-items-center">
                            <div class="col-lg-7">
                                <h1 class="display-5 fw-bold text-dark mb-2">
                                    Hello, <span class="text-success">{{ Auth::user()->username ?? Auth::user()->name ?? 'Pet Owner' }}</span>!
                                </h1>
                                <p class="lead text-muted mb-4">
                                    Manage your pets, find clinics, and book appointments with ease.
                                </p>
                                
                                {{-- Unified Search Bar --}}
                                <div class="bg-white p-2 rounded-pill shadow-sm d-flex align-items-center border search-bar-container" style="max-width: 600px;">
                                    <div>
                                        <button class="btn btn-light rounded-pill px-3 py-2 fw-medium d-flex align-items-center border-0 bg-transparent" type="button" data-bs-toggle="modal" data-bs-target="#locationModal">
                                            <i class="bi bi-geo-alt-fill text-danger me-2"></i>
                                            <span class="text-truncate" style="max-width: 100px;">{{ $selectedLocation ?: 'Location' }}</span>
                                            <i class="bi bi-chevron-down ms-2 small text-muted"></i>
                                        </button>
                                    </div>
                                    <input type="hidden" name="location" value="{{ $selectedLocation }}">
                                    <div class="vr mx-2 opacity-25"></div>
                                    <input type="text" class="form-control border-0 shadow-none bg-transparent" 
                                           placeholder="Search for clinics or services..." 
                                           readonly onclick="openSearchModal()" style="cursor: pointer;">
                                    <button class="btn btn-success rounded-circle p-2 shadow-sm d-flex align-items-center justify-content-center" 
                                            onclick="openSearchModal()" style="width: 40px; height: 40px;">
                                        <i class="bi bi-search text-white"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-5 d-none d-lg-block text-end position-relative" style="height: 260px;">
                                <div class="welcome-slideshow ms-auto">
                                    <img src="{{ asset('images/offlogo.png') }}" class="slide-item logo-slide" alt="PetApp Logo">
                                    <img src="{{ asset('images/doggo1 (2).gif') }}" class="slide-item doggo-slide" alt="Dog">
                                    <img src="{{ asset('images/gatto (2).gif') }}" class="slide-item gatto-slide" alt="Cat">
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Decorative Background --}}
                    <div class="position-absolute top-0 end-0 h-100 w-50 bg-success bg-opacity-10 rounded-start-pill d-none d-lg-block" style="transform: skewX(-20deg) translateX(20%);"></div>
                </div>
            </div>




            {{-- Quick Stats & Shortcuts --}}
            <div class="row g-3 mb-4">
                {{-- My Pets --}}
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift transition-all cursor-pointer" data-bs-toggle="modal" data-bs-target="#myPetsModal">
                        <div class="card-body p-3 d-flex flex-column align-items-center justify-content-center text-center">
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle mb-3">
                                <img src="{{ asset('images/mypet.png') }}" alt="My Pets" style="width: 40px; height: 40px; object-fit: contain;">
                            </div>
                            <h6 class="fw-bold text-dark mb-1">My Pets</h6>
                            <span class="badge bg-success rounded-pill px-3">{{ isset($pets) ? $pets->count() : 0 }} Pets</span>
                        </div>
                    </div>
                </div>

                {{-- Appointments --}}
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift transition-all cursor-pointer" data-bs-toggle="modal" data-bs-target="#recentAppointmentsModal">
                        <div class="card-body p-3 d-flex flex-column align-items-center justify-content-center text-center">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle mb-3">
                                <img src="{{ asset('images/myapp.png') }}" alt="Appointments" style="width: 40px; height: 40px; object-fit: contain;">
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Appointments</h6>
                            @php
                                $appointmentsTotal = isset($recentAppointments) ? $recentAppointments->count() : 0;
                                $appointmentsPending = isset($recentAppointments)
                                    ? $recentAppointments->filter(fn ($a) => ($a->status ?? '') !== 'completed')->count()
                                    : 0;
                                $appointmentsCompleted = max(0, $appointmentsTotal - $appointmentsPending);
                                $appointmentsBadgeText = $appointmentsPending > 0
                                    ? $appointmentsPending . ' Pending'
                                    : $appointmentsCompleted . ' Completed';
                                $appointmentsBadgeClass = $appointmentsPending > 0 ? 'bg-primary' : 'bg-success';
                            @endphp
                            <span class="badge {{ $appointmentsBadgeClass }} rounded-pill px-3">{{ $appointmentsBadgeText }}</span>
                        </div>
                    </div>
                </div>

                {{-- Vet Notes --}}
                <div class="col-6 col-md-3">
                     <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift transition-all cursor-pointer" data-bs-toggle="modal" data-bs-target="#vetNotesModal">
                        <div class="card-body p-3 d-flex flex-column align-items-center justify-content-center text-center">
                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle mb-3">
                                <img src="{{ asset('images/notes.gif') }}" alt="Vet Notes" class="dark-invert-gif" style="width: 40px; height: 40px; object-fit: contain;">
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Vet Notes</h6>
                            <small class="text-muted">Health Records</small>
                        </div>
                    </div>
                </div>

                 {{-- Add Pet --}}
                <div class="col-6 col-md-3">
                     <a href="{{ route('pets.create') }}" class="card border-0 shadow-sm rounded-4 h-100 hover-lift transition-all text-decoration-none">
                        <div class="card-body p-3 d-flex flex-column align-items-center justify-content-center text-center">
                            <div class="bg-danger bg-opacity-10 p-3 rounded-circle mb-3">
                                <img src="{{ asset('images/cat-playing.gif') }}" alt="Add Pet" class="dark-invert-gif" style="width: 40px; height: 40px; object-fit: contain;">
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Add Pet</h6>
                            <small class="text-muted">New Profile</small>
                        </div>
                    </a>
                </div>
            </div>
            
            <h5 class="fw-bold text-dark mb-3 px-1">Browse by Category</h5>

            {{-- Animals Sections --}}
            {{-- =======================
   ANIMALS SECTIONS (MOBILE-CLEAN LAYOUT)
======================= --}}

{{-- Animal Categories (Side-by-Side Buttons with Slideshow) --}}
<style>
    .category-btn {
        height: 200px;
        position: relative;
        overflow: hidden;
        border: none;
        border-radius: 12px;
        transition: transform 0.2s, box-shadow 0.2s;
        background: transparent;
    }
    .category-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .category-btn:active {
        transform: scale(0.98);
    }
    .category-btn .slideshow-container {
        position: absolute;
        top: 15%;
        left: 5%; /* Wider to accommodate 3 items */
        width: 90%; /* Wider to accommodate 3 items */
        height: 70%;
        z-index: 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .category-btn .slide-slot {
        flex: 1;
        height: 100%;
        position: relative;
        overflow: hidden;
        margin: 0 2px;
    }
    .category-btn .slide-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
        opacity: 0;
        transition: opacity 1s ease-in-out;
    }
    .category-btn .slide-img.active {
        opacity: 1;
    }
    .category-btn .btn-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.1); 
        z-index: 1;
        transition: background 0.3s;
    }
    .category-btn.active .btn-overlay {
        background: rgba(0, 0, 0, 0.3);
    }
    .category-btn .btn-content {
        position: relative;
        z-index: 2;
        color: #333;
        text-shadow: 0 2px 4px rgba(255,255,255,0.8);
    }
    
    /* Dark Theme Support */
    body.dark-theme .category-btn {
        background: #2c2c2c;
        border-color: #444;
    }
    body.dark-theme .category-btn .btn-content {
        color: #f8f9fa;
        text-shadow: 0 2px 4px rgba(0,0,0,0.8);
    }

    .fade-in-item {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.4s ease-out, transform 0.4s ease-out;
    }

    .category-section-container {
        background: #fff;
        transition: background-color 0.3s, border-color 0.3s;
    }
    /* Dark Theme for Clinics Modal & Booking Modal */
    body.dark-theme #clinicsModal .modal-content,
    body.dark-theme #bookModal .modal-content {
        background-color: #1e1e1e;
        color: #fff;
    }
    body.dark-theme #clinicsModal .modal-header,
    body.dark-theme #bookModal .modal-header {
        background-color: #198754 !important;
        border-bottom-color: #333;
    }
    body.dark-theme #clinicsModal .modal-title,
    body.dark-theme #bookModal .modal-title {
        color: #fff !important;
    }
    body.dark-theme #clinicsModal .btn-close,
    body.dark-theme #bookModal .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }
    body.dark-theme #clinicsModal .modal-body,
    body.dark-theme #bookModal .modal-body {
        color: #e0e0e0;
    }
    body.dark-theme #clinicsModal .card {
        background-color: #2a2a2a;
        color: #e0e0e0;
        border-color: #444;
    }
    body.dark-theme #clinicsModal .text-dark,
    body.dark-theme #bookModal .text-dark {
        color: #fff !important;
    }
    
    /* Invert GIF on dark mode (preserves colors, flips black/white) */
    body.dark-theme .invert-on-dark {
        filter: invert(1) hue-rotate(180deg);
    }
    body.dark-theme #clinicsModal .text-muted,
    body.dark-theme #bookModal .text-muted {
        color: #adb5bd !important;
    }
    
    /* Specific Dark Mode Overrides for Elements inside Modals */
    body.dark-theme #clinicsModal .bg-light,
    body.dark-theme #bookModal .bg-light {
        background-color: #333 !important;
        color: #e0e0e0 !important;
        border-color: #444 !important;
    }
    
    /* Service Item Dark Mode */
    body.dark-theme .service-item {
        border-color: #444;
        background-color: transparent;
        color: #e0e0e0;
    }
    body.dark-theme .service-item:hover {
        background-color: #333;
        border-color: #555;
    }
    body.dark-theme .service-item.active {
        background-color: rgba(25, 135, 84, 0.2); /* Translucent Green */
        border-color: #198754 !important;
    }
    
    /* Form Inputs in Dark Mode (for Booking Modal) */
    body.dark-theme #bookModal .form-control,
    body.dark-theme #bookModal .form-select {
        background-color: #2a2a2a;
        border-color: #444;
        color: #fff;
    }
    body.dark-theme #bookModal .form-label {
        color: #e0e0e0;
    }

    /* Dark Theme for Vet Notes Modal */
    body.dark-theme #vetNotesModal .modal-content {
        background-color: #1e1e1e;
        color: #fff;
    }
    body.dark-theme #vetNotesModal .modal-header {
        background-color: #198754 !important;
        border-bottom-color: #333;
    }
    body.dark-theme #vetNotesModal .modal-title {
        color: #fff !important;
    }
    body.dark-theme #vetNotesModal .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }
    body.dark-theme #vetNotesModal .card {
        background-color: #2a2a2a !important;
        color: #e0e0e0;
        border-color: #444;
    }
    body.dark-theme #vetNotesModal .text-dark {
        color: #fff !important;
    }
    body.dark-theme #vetNotesModal .text-muted {
        color: #adb5bd !important;
    }
    body.dark-theme #vetNotesModal .bg-light,
    body.dark-theme #vetNotesModal .bg-light-subtle {
        background-color: #333 !important;
        color: #e0e0e0 !important;
        border-color: #444 !important;
    }
    body.dark-theme #vetNotesModal .border {
        border-color: #444 !important;
    }
    body.dark-theme #vetNotesModal .card {
        background-color: #2a2a2a;
        color: #e0e0e0;
        border-color: #444;
    }
    body.dark-theme #vetNotesModal .text-dark {
        color: #fff !important;
    }
    body.dark-theme #vetNotesModal .text-muted {
        color: #adb5bd !important;
    }
    body.dark-theme #vetNotesModal .bg-light {
        background-color: #333 !important;
    }

    /* =========================================
       PC LAYOUT REDESIGN (MD+ SCREENS ONLY)
       ========================================= */
    @media (min-width: 768px) {
        /* Sidebar Enhancement */
        #sidebarMenu {
            background: linear-gradient(180deg, #008080 0%, #004d4d 100%) !important;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1) !important;
            border-right: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-btn-container, .logout-btn-container {
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            overflow: hidden;
            border: 1px solid transparent;
        }

        .sidebar-btn-container:hover, .logout-btn-container:hover {
            background-color: rgba(255, 255, 255, 0.95);
            transform: translateX(8px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }

        .sidebar-btn-container:hover span, 
        .logout-btn-container:hover span,
        .sidebar-btn-container:hover i, 
        .logout-btn-container:hover i {
            color: #008080 !important;
        }

        /* Welcome Container Enhancement */
        .welcome-container {
            background: linear-gradient(135deg, #ffffff 0%, #e8f5e9 100%);
            border-radius: 24px !important;
            border: 1px solid rgba(25, 135, 84, 0.15);
            box-shadow: 0 10px 30px rgba(25, 135, 84, 0.1) !important;
            transition: transform 0.3s ease;
        }
        
        .welcome-container:hover {
            transform: translateY(-2px);
        }

        /* Category Buttons Enhancement */
        .category-btn {
            border-radius: 24px;
            border: none;
            background: #ffffff;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }

        .category-btn:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        }

        /* Category Content Container */
        .category-section-container {
            border-radius: 24px !important;
            border: none !important;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08) !important;
            margin-top: 20px !important; /* More spacing */
        }
        
        /* Action Buttons (My Pets, Appointments) */
        .my-buttons-container .btn {
            border-radius: 24px !important;
            border: none;
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .my-buttons-container .btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        /* Dark Theme Overrides for PC Layout */
        body.dark-theme #sidebarMenu {
            background: linear-gradient(180deg, #1e1e1e 0%, #121212 100%) !important;
            border-right: 1px solid #333;
            box-shadow: 4px 0 15px rgba(0,0,0,0.5) !important;
        }

        body.dark-theme .sidebar-btn-container:hover, 
        body.dark-theme .logout-btn-container:hover {
            background-color: rgba(255, 255, 255, 0.1); 
        }

        body.dark-theme .sidebar-btn-container:hover span, 
        body.dark-theme .logout-btn-container:hover span,
        body.dark-theme .sidebar-btn-container:hover i, 
        body.dark-theme .logout-btn-container:hover i {
            color: #4db6ac !important; /* Lighter teal for dark mode */
        }

        body.dark-theme .welcome-container {
            background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);
            border: 1px solid #444;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4) !important;
        }
        
        body.dark-theme .category-btn {
            background: #2c2c2c;
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }

        body.dark-theme .category-section-container {
            background: #2c2c2c;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4) !important;
        }
    }

    /* Hero Card */
    .hero-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        transition: transform 0.3s ease;
    }
    body.dark-theme .hero-card {
        background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);
        border: 1px solid #444 !important;
    }
    body.dark-theme .hero-card h1, 
    body.dark-theme .hero-card .lead {
        color: #fff !important;
    }

    /* Search Bar */
    .search-bar-container {
        transition: box-shadow 0.3s ease;
    }
    .search-bar-container:focus-within {
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    body.dark-theme .search-bar-container {
        background-color: #2c2c2c !important;
        border-color: #444 !important;
    }
    body.dark-theme .search-bar-container input {
        color: #fff;
    }
    body.dark-theme .search-bar-container .btn-light {
        background-color: transparent;
        color: #fff;
    }
    body.dark-theme .search-bar-container .dropdown-menu {
        background-color: #2c2c2c;
        border-color: #444;
    }
    body.dark-theme .search-bar-container .dropdown-item {
        color: #e0e0e0;
    }
    body.dark-theme .search-bar-container .dropdown-item:hover {
        background-color: #333;
    }
    
    /* Quick Stats Cards */
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    body.dark-theme .hover-lift {
        background-color: #2c2c2c !important;
        border-color: #444 !important;
    }
    body.dark-theme .hover-lift h6 {
        color: #fff !important;
    }
    body.dark-theme .hover-lift .text-muted {
        color: #adb5bd !important;
    }
    
    /* Tracking Tight */
    .tracking-tight {
        letter-spacing: -0.5px;
    }
</style>

<div class="row g-3 mb-4">
    @foreach($categories as $cat)
    <div class="col-12 col-md-4">
        <button class="btn w-100 p-0 category-btn category-toggle-btn" 
                type="button" 
                data-category="{{ $cat['id'] }}">
            
            {{-- Slideshow --}}
            <div class="slideshow-container">
                <div class="slide-slot"></div>
                <div class="slide-slot"></div>
                <div class="slide-slot"></div>
                <div class="d-none slide-source-data">
                    @foreach($cat['animals'] as $animal)
                        <span data-src="{{ animalImagePath($animal['image']) }}"></span>
                    @endforeach
                </div>
            </div>

            {{-- Overlay --}}
            <div class="btn-overlay"></div>

            {{-- Content --}}
            <div class="btn-content d-flex flex-column align-items-center justify-content-center h-100">
                <img src="{{ asset($cat['image']) }}" alt="{{ $cat['name'] }}" style="width: 50px; height: 50px; object-fit: contain; margin-bottom: 8px;">
                <span class="fs-3 fw-bold">{{ $cat['name'] }}</span>
            </div>
        </button>

        {{-- Mobile Content (Accordion Style) --}}
        <div id="{{ $cat['id'] }}-mobile" class="category-content d-md-none overflow-hidden" style="max-height: 0; transition: max-height 0.4s ease-in-out;">
            <div class="p-3 border rounded shadow-sm category-section-container mt-2">
                <h5 class="fw-bold text-success mb-3"><img src="{{ asset($cat['image']) }}" style="width: 30px; height: 30px; object-fit: contain;" class="me-2">{{ $cat['name'] }}</h5>
                <div class="row g-3">
                    @foreach($cat['animals'] as $animal)
                    <div class="col-4 col-md-3 col-lg-2 fade-in-item">
                        <button class="btn animal-btn w-100 shadow-sm" data-animal="{{ $animal['name'] }}">
                            <img src="{{ animalImagePath($animal['image']) }}" alt="{{ $animal['name'] }}">
                            <div class="overlay">{{ $animal['name'] }}</div>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Desktop Content Sections --}}
<div class="mb-4 d-none d-md-block">
    @foreach($categories as $cat)
    <div id="{{ $cat['id'] }}-desktop" class="category-content overflow-hidden" style="max-height: 0; transition: max-height 0.4s ease-in-out;">
        <div class="p-3 border rounded shadow-sm category-section-container mt-2">
            <h5 class="fw-bold text-success mb-3"><img src="{{ asset($cat['image']) }}" style="width: 30px; height: 30px; object-fit: contain;" class="me-2">{{ $cat['name'] }}</h5>
            <div class="row g-3">
                @foreach($cat['animals'] as $animal)
                <div class="col-4 col-md-3 col-lg-2 fade-in-item">
                    <button class="btn animal-btn w-100 shadow-sm" data-animal="{{ $animal['name'] }}">
                        <img src="{{ animalImagePath($animal['image']) }}" alt="{{ $animal['name'] }}">
                        <div class="overlay">{{ $animal['name'] }}</div>
                    </button>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach
</div>







{{-- Mobile Bottom Navigation --}}
<div class="mobile-bottom-nav d-md-none">
    <a href="#" id="mobileProfileBtn" class="{{ request()->routeIs('pet_owner.edit') ? 'active' : '' }}">
        <img src="{{ asset('images/profile.gif') }}" alt="Profile" style="width: 40px; height: 40px; margin-bottom: 2px;">
        <span>Profile</span>
    </a>

    <a href="#" data-bs-toggle="modal" data-bs-target="#vetNotesModal">
        <img src="{{ asset('images/notes.gif') }}" alt="Vet Notes" class="dark-invert-gif" style="width: 40px; height: 40px; margin-bottom: 2px;">
        <span>Vet Notes</span>
    </a>

    <a href="{{ route('pets.create') }}" class="{{ request()->routeIs('pets.create') ? 'active' : '' }}">
        <img src="{{ asset('images/cat-playing.gif') }}" alt="Add Pet" class="dark-invert-gif" style="width: 40px; height: 40px; margin-bottom: 2px;">
        <span>Add Pet</span>
    </a>

    <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
        <img src="{{ asset('images/logout.gif') }}" alt="Logout" style="width: 40px; height: 40px; margin-bottom: 2px;">
        <span>Logout</span>
    </a>
</div>

        </main>



<div class="modal fade" id="myPetsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
      <div class="modal-header bg-success text-white border-0">
        <h5 class="modal-title fw-bold"><i class="bi bi-paw-fill me-2"></i>My Pets</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 bg-light-subtle">
       @if(isset($pets) && $pets->count() > 0)
        <div class="d-flex flex-column gap-3">
            @foreach($pets as $pet)
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-1 my-pet-card">
                    <div class="card-body p-0 d-flex flex-wrap flex-sm-nowrap align-items-center">
                        <!-- Image Left -->
                        <img src="{{ $pet->image ? $pet->image_url : asset('images/default_pet.jpg') }}" 
                             class="object-fit-cover d-none d-sm-block" 
                             alt="{{ $pet->name }}" 
                             style="height: 100px; width: 100px;">
                        
                        <!-- Details Middle -->
                        <div class="ms-3 flex-grow-1 p-3">
                            <h5 class="fw-bold mb-1 pet-name">{{ $pet->name ?? 'Unnamed' }}</h5>
                            <p class="text-muted small mb-0">{{ $pet->age ?? 'N/A' }} years old</p>
                            <p class="text-muted small mb-0">{{ $pet->breed ?? 'Unknown Breed' }}</p>
                        </div>

                        <!-- Edit Button Right (Big Icon) -->
                        <div class="pe-3 pe-md-4 flex-shrink-0 d-flex align-items-center">
                             <a href="{{ route('pets.edit', $pet->id) }}" class="text-success p-2 text-decoration-none" title="Edit Pet">
                                <i class="bi bi-pencil-square" style="font-size: 2rem;"></i>
                            </a>
                            <button type="button" class="btn p-2 text-danger border-0 bg-transparent delete-pet-btn" 
                                    data-pet-id="{{ $pet->id }}" title="Delete Pet">
                                <i class="bi bi-trash" style="font-size: 2rem;"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @else
            <div class="text-center py-5">
                <div class="bg-success-subtle text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="bi bi-emoji-frown fs-1"></i>
                </div>
                <h5 class="text-muted fw-bold">You haven’t added any pets yet.</h5>
                <p class="text-muted small mb-4">Add your beloved pets to manage their appointments easily.</p>
                <a href="{{ route('pets.create') }}" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
                    <i class="bi bi-plus-circle me-2"></i>Add Your First Pet
                </a>
            </div>
        @endif
      </div>
      <div class="modal-footer border-0 bg-light-subtle">
        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Pet Detail Modal -->
<div class="modal fade" id="petDetailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
      <div class="modal-header border-0 bg-light-subtle">
        <h5 class="modal-title fw-bold text-success" id="petDetailModalTitle">Pet Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-4 py-4" id="petDetailModalBody">
        <!-- Filled via JS -->
      </div>
      <div class="modal-footer border-0 justify-content-center bg-light-subtle pb-4 gap-3">
        <button type="button" id="petDeleteBtn" class="btn btn-danger rounded-pill px-5 fw-bold shadow-sm">
            <i class="bi bi-trash me-2"></i>Delete
        </button>
        <a href="#" id="petEditBtn" class="btn btn-success rounded-pill px-5 fw-bold shadow-sm">
            <i class="bi bi-pencil-square me-2"></i>Edit Pet
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Delete Pet Confirmation Modal -->
<div class="modal fade" id="deletePetModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
      <div class="modal-header bg-danger text-white border-0">
        <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Confirm Deletion</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 text-center">
        <div class="mb-3">
            <i class="bi bi-trash3 text-danger display-1"></i>
        </div>
        <h5 class="fw-bold mb-3">Are you sure you want to delete this pet?</h5>
        <p class="text-muted mb-0">This action cannot be undone. All records associated with this pet will be permanently removed.</p>
      </div>
      <div class="modal-footer border-0 bg-light-subtle justify-content-center pb-4">
        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmDeletePetBtn" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">
            Delete Pet
        </button>
      </div>
    </div>
  </div>
</div>

<style>
    .pet-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    /* Dark Theme Adjustments for Pet Cards */
    body.dark-theme .pet-card {
        background-color: #2a2a2a; /* Slightly lighter than modal bg */
        color: #e0e0e0;
    }
    body.dark-theme .pet-card .card-title {
        color: #fff;
    }
    body.dark-theme .pet-card .text-muted {
        color: #adb5bd !important;
    }
    /* Dark Theme for My Pets Modal List */
    body.dark-theme .my-pet-card {
        background-color: #2a2a2a;
    }
    body.dark-theme .my-pet-card .pet-name {
        color: #fff !important;
    }
    body.dark-theme .my-pet-card .text-muted {
        color: #adb5bd !important;
    }
    body.dark-theme .bg-light-subtle {
        background-color: #1e1e1e !important;
    }

    /* Dark Theme for Recent Appointments Modal */
    body.dark-theme #recentAppointmentsModal .modal-content {
        background-color: #1e1e1e;
        color: #fff;
    }
    body.dark-theme #recentAppointmentsModal .modal-header {
        background-color: #1e1e1e !important;
        border-bottom-color: #333;
    }
    body.dark-theme #recentAppointmentsModal .modal-title {
        color: #fff !important;
    }
    body.dark-theme #recentAppointmentsModal .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }
    
    /* Appointment Cards in Dark Mode */
    body.dark-theme .appointment-card {
        background-color: #2a2a2a !important;
        color: #e0e0e0;
        transition: transform 0.2s, box-shadow 0.2s, background-color 0.2s;
    }
    body.dark-theme .appointment-card:hover {
        background-color: #333 !important;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3) !important;
        transform: translateY(-2px);
    }
    /* Light mode hover for consistency */
    .appointment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
    }

    body.dark-theme .appointment-card .text-dark {
        color: #fff !important;
    }
    body.dark-theme .appointment-card .bg-light {
        background-color: #333 !important;
        border-color: #444 !important;
        color: #e0e0e0 !important;
    }
    body.dark-theme .appointment-card .text-muted {
        color: #adb5bd !important;
    }
    body.dark-theme .appointment-card .bg-primary-subtle {
        background-color: rgba(13, 110, 253, 0.2) !important;
        color: #6ea8fe !important;
    }
    body.dark-theme .appointment-card .text-primary {
        color: #6ea8fe !important;
    }

    /* Dark Theme for Recent Appointments Modal Body & Text */
    body.dark-theme #recentAppointmentsModal .bg-light-subtle {
        background-color: #1e1e1e !important;
        color: #e0e0e0;
    }
    body.dark-theme #recentAppointmentsModal .text-muted {
        color: #adb5bd !important;
    }
    body.dark-theme #recentAppointmentsModal .text-dark {
        color: #fff !important;
    }

    /* Dark Theme for Appointment Details Modal */
    body.dark-theme #appointmentDetailsModal .modal-content {
        background-color: #1e1e1e;
        color: #fff;
    }
    body.dark-theme #appointmentDetailsModal .modal-header {
        background-color: #198754 !important;
        border-bottom-color: #333;
    }
    body.dark-theme #appointmentDetailsModal .modal-title {
        color: #fff !important;
    }
    body.dark-theme #appointmentDetailsModal .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }
    body.dark-theme #appointmentDetailsModal .bg-light-subtle {
        background-color: #1e1e1e !important;
        color: #e0e0e0;
    }
    body.dark-theme #appointmentDetailsModal .text-dark {
        color: #fff !important;
    }
    body.dark-theme #appointmentDetailsModal .text-muted {
        color: #adb5bd !important;
    }
    body.dark-theme #appointmentDetailsModal .card {
        background-color: #2a2a2a;
        color: #e0e0e0;
        border-color: #444;
    }
    
    /* Dark Theme for Pet Items in Appointment Details */
    body.dark-theme #appointmentDetailsModal .bg-light {
        background-color: #383838 !important;
        border-color: #444 !important;
        color: #e0e0e0 !important;
    }

    /* Dark Theme for Stacked Pet Images in List View */
    body.dark-theme .appointment-card img.border-white {
        border-color: #2a2a2a !important;
    }
    body.dark-theme .appointment-card:hover img.border-white {
        border-color: #333 !important;
    }

    /* Dark Mode Badges */
    body.dark-theme .badge.bg-success-subtle {
        background-color: rgba(25, 135, 84, 0.2) !important;
        color: #75b798 !important;
        border-color: #198754 !important;
    }
    body.dark-theme .badge.bg-warning-subtle {
        background-color: rgba(255, 193, 7, 0.2) !important;
        color: #ffda6a !important;
        border-color: #ffc107 !important;
    }
    body.dark-theme .badge.bg-danger-subtle {
        background-color: rgba(220, 53, 69, 0.2) !important;
        color: #ea868f !important;
        border-color: #dc3545 !important;
    }
    body.dark-theme .badge.bg-primary-subtle {
        background-color: rgba(13, 110, 253, 0.2) !important;
        color: #6ea8fe !important;
        border-color: #0d6efd !important;
    }
    
    /* Dark Theme for Logout Modal */
    body.dark-theme #logoutModal .modal-content {
        background-color: #1e1e1e;
        color: #fff;
    }
    body.dark-theme #logoutModal .bg-light-subtle {
        background-color: #1e1e1e !important;
        color: #e0e0e0;
    }
    body.dark-theme #logoutModal .text-dark {
        color: #fff !important;
    }
    body.dark-theme #logoutModal .text-muted {
        color: #adb5bd !important;
    }
    body.dark-theme #logoutModal .bg-danger-subtle {
        background-color: rgba(220, 53, 69, 0.2) !important;
        color: #ea868f !important;
    }
    body.dark-theme #logoutModal .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    /* Mobile Bottom Nav Dark Mode - Invert Black to White, preserve other colors */
    body.dark-theme .mobile-bottom-nav img {
        filter: invert(1) hue-rotate(180deg);
    }

    body.dark-theme img.dark-invert-gif {
        filter: invert(1) brightness(2) saturate(0) !important;
    }
</style>





        {{-- Hidden Clinics Data --}}
        <div id="clinicsData" data-clinics='@json($clinicsJson ?? [])' style="display:none;"></div>
    </div>
</div>


<div class="modal fade" id="recentAppointmentsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
      <div class="modal-header bg-white border-bottom-0 pb-0">
        <h5 class="modal-title fw-bold text-dark"><i class="bi bi-clock-history me-2 text-primary"></i>My Recent Appointments</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 bg-light-subtle">
        @if(isset($recentAppointments) && $recentAppointments->count() > 0)
            <div class="d-flex flex-column gap-3">
                @foreach($recentAppointments as $appointment)
                    @php
                        $apptPets = $appointment->pets->map(function($p) {
                            return [
                                'name' => $p->name,
                                'image' => $p->image_url,
                                'breed' => $p->breed ?? 'Unknown',
                                'species' => $p->animal->name ?? 'Pet'
                            ];
                        });
                        // Fallback for legacy single pet
                        if($apptPets->isEmpty() && $appointment->pet) {
                             $apptPets->push([
                                'name' => $appointment->pet->name,
                                'image' => $appointment->pet->image_url,
                                'breed' => $appointment->pet->breed ?? 'Unknown',
                                'species' => $appointment->pet->animal->name ?? 'Pet'
                            ]);
                        }
                    @endphp
                    <div class="card border-0 shadow-sm rounded-4 appointment-card bg-white" 
                         style="cursor: pointer;"
                         onclick="showAppointmentDetails({
                            clinic: '{{ addslashes($appointment->clinic?->clinic_name ?? 'Unknown Clinic') }}',
                            service: '{{ addslashes($appointment->service?->name ?? 'Service') }}',
                            status: '{{ $appointment->status }}',
                            date: '{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}',
                            time: '{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('g:i A') }}',
                            price: '{{ $appointment->service?->price ?? 0 }}',
                            pet_count: {{ $appointment->pets->count() > 0 ? $appointment->pets->count() : 1 }},
                            pets: {{ json_encode($apptPets) }},
                            payment_status: '{{ $appointment->payment_status }}',
                            payment_method: '{{ $appointment->payment_method }}',
                            checkoutUrl: '{{ route('payment.checkout', $appointment->id) }}'
                         })">
                        <div class="card-body p-3">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                                <!-- Left: Clinic & Date -->
                                <div class="d-flex align-items-center gap-3 flex-grow-1">
                                    <div class="bg-primary-subtle text-primary rounded-circle p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
                                        <i class="bi bi-hospital-fill fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark">{{ $appointment->clinic?->clinic_name ?? 'Unknown Clinic' }}</h6>
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                            <span class="badge bg-light text-muted border fw-normal">
                                                <i class="bi bi-calendar-event me-1"></i>{{ $appointment->appointment_date ?? 'N/A' }}
                                            </span>
                                            <div class="d-flex align-items-center ms-2">
                                                @foreach($appointment->pets as $index => $pet)
                                                    <img src="{{ $pet->image_url }}" 
                                                         alt="{{ $pet->name }}" 
                                                         class="rounded-circle border border-white shadow-sm object-fit-cover" 
                                                         style="width: 30px; height: 30px; margin-left: {{ $index > 0 ? '-10px' : '0' }}; z-index: {{ 10 - $index }};"
                                                         title="{{ $pet->name }}">
                                                @endforeach
                                                @if($appointment->pets->count() == 0 && $appointment->pet)
                                                    {{-- Fallback for legacy single pet --}}
                                                    <img src="{{ $appointment->pet->image_url }}" 
                                                         alt="{{ $appointment->pet->name }}" 
                                                         class="rounded-circle border border-white shadow-sm object-fit-cover" 
                                                         style="width: 30px; height: 30px;"
                                                         title="{{ $appointment->pet->name }}">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right: Status & Action -->
                                <div class="d-flex align-items-center gap-3 ms-md-auto align-self-stretch align-self-md-center justify-content-between w-100 w-md-auto">
                                    <span class="badge rounded-pill px-3 py-2 border
                                        @if($appointment->status == 'approved') bg-success-subtle text-success border-success-subtle
                                        @elseif($appointment->status == 'pending') bg-warning-subtle text-warning border-warning-subtle
                                        @elseif($appointment->status == 'completed') bg-primary-subtle text-primary border-primary-subtle
                                        @else bg-danger-subtle text-danger border-danger-subtle @endif">
                                        {{ ucfirst($appointment->status ?? 'N/A') }}
                                    </span>

                                    @if($appointment->payment_status === 'unpaid' && ($appointment->status == 'approved' || $appointment->payment_method == 'online'))
                                        <a href="{{ route('payment.checkout', $appointment->id) }}" class="btn btn-sm btn-success rounded-pill fw-bold px-3" onclick="event.stopPropagation()">
                                            Pay Downpayment
                                        </a>
                                    @elseif($appointment->payment_status === 'downpayment_paid')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">
                                            <i class="bi bi-shield-fill-check me-1"></i> Verified
                                        </span>
                                    @endif

                                    @if($appointment->status === 'completed' && !$appointment->review)
                                        <button class="btn btn-sm btn-outline-primary rounded-pill fw-bold px-3"
                                                onclick="event.stopPropagation()"
                                                data-bs-toggle="modal"
                                                data-bs-target="#reviewModal"
                                                data-appointment="{{ $appointment->id }}"
                                                data-clinic="{{ $appointment->clinic_id }}">
                                            Review
                                        </button>
                                    @elseif($appointment->review)
                                        <div class="text-warning small" title="Rated {{ $appointment->review->rating }} stars">
                                            @for($i = 0; $i < 5; $i++)
                                                <i class="bi bi-star{{ $i < $appointment->review->rating ? '-fill' : '' }}"></i>
                                            @endfor
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="bi bi-calendar-x fs-1"></i>
                </div>
                <h5 class="text-muted fw-bold">No recent appointments found.</h5>
                <p class="text-muted small mb-0">Book an appointment with a clinic to see it here.</p>
            </div>
        @endif
      </div>
      <div class="modal-footer border-0 bg-light-subtle">
        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



      
      </div>
    </div>
  </div>
</div>
{{-- Clinics Modal --}}
<div class="modal fade" id="appointmentDetailsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
      <div class="modal-header bg-success text-white border-0">
        <h5 class="modal-title fw-bold" id="apptDetailsClinicName">Clinic Name</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 bg-light-subtle">
        
        <div class="text-center mb-4">
            <h4 class="fw-bold text-dark mb-1" id="apptDetailsService">Service Name</h4>
            <span class="badge rounded-pill px-3 py-2 border" id="apptDetailsStatus">Status</span>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2 border-bottom pb-2">
                    <span class="text-muted">Date</span>
                    <span class="fw-bold text-dark" id="apptDetailsDate">Date</span>
                </div>
                <div class="d-flex justify-content-between mb-2 border-bottom pb-2">
                    <span class="text-muted">Time</span>
                    <span class="fw-bold text-dark" id="apptDetailsTime">Time</span>
                </div>
                <div class="d-flex justify-content-between mb-2 border-bottom pb-2">
                    <span class="text-muted">Total Price</span>
                    <span class="fw-bold text-dark" id="apptDetailsPrice">₱0.00</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Downpayment (50%)</span>
                    <span class="fw-bold text-success fs-5" id="apptDetailsDownpayment">₱0.00</span>
                </div>
                
                <div class="mt-3 pt-3 border-top" id="apptDetailsPetsContainer" style="display: none;">
                    <span class="text-muted small fw-bold text-uppercase mb-2 d-block">Pets</span>
                    <div id="apptDetailsPets" class="d-flex flex-wrap gap-2"></div>
                </div>
            </div>
        </div>

        <div id="apptDetailsPaymentStatusContainer" class="text-center mb-3">
             <!-- Dynamic Payment Status Badge -->
        </div>

      </div>
      <div class="modal-footer border-0 bg-light-subtle justify-content-center pb-4">
        <a href="#" id="apptDetailsPayBtn" class="btn btn-success rounded-pill px-5 fw-bold shadow-sm d-none">
            Pay Downpayment
        </a>
        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="clinicsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title"><i class="bi bi-hospital me-2"></i> Clinics</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="clinicsModalBody"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="clinicDetailsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
      <div class="modal-header bg-success text-white border-0">
        <h5 class="modal-title fw-bold" id="clinicDetailsModalTitle">Clinic</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 bg-light-subtle" id="clinicDetailsModalBody"></div>
      <div class="modal-footer border-0 bg-light-subtle">
        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

{{-- Booking Modal --}}
<div class="modal fade" id="bookModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
      <form method="POST" action="{{ route('appointments.store') }}" enctype="multipart/form-data">
        @csrf
        
        <input type="hidden" name="clinic_id" id="modalClinicId"><input type="hidden" name="service_id" id="modalServiceId">

        <div class="modal-header bg-success text-white border-0">
          <h5 class="modal-title fw-bold"><i class="bi bi-calendar-check-fill me-2"></i>Book Appointment</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4 bg-light-subtle">
          <div class="mb-4">
            <label class="form-label fw-bold text-secondary small text-uppercase">Select Your Pet(s)</label>
            <div class="pet-selection-container shadow-sm p-2 rounded border bg-body">
                <div class="d-flex flex-column w-100 gap-2" style="max-height: 250px; overflow-y: auto;">
                    @foreach($pets as $pet)
                    <div class="form-check pet-selection-card p-2 border rounded mb-1 d-flex align-items-center gap-3" style="cursor: pointer; transition: all 0.2s ease;">
                        <input class="form-check-input pet-checkbox mt-0 flex-shrink-0 d-none" type="checkbox" name="pet_ids[]" value="{{ $pet->id }}" id="pet_{{ $pet->id }}" data-species="{{ $pet->species ?? '' }}" style="width: 1.25rem; height: 1.25rem;">
                        
                        <label class="form-check-label d-flex align-items-center gap-3 w-100 m-0" for="pet_{{ $pet->id }}" style="cursor: pointer;">
                            <img src="{{ $pet->image ? asset('storage/'.$pet->image) : asset('images/default_pet.jpg') }}" 
                                 alt="{{ $pet->name }}" 
                                 class="rounded-circle object-fit-cover border shadow-sm flex-shrink-0" 
                                 style="width: 45px; height: 45px;">
                                 
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-body pet-name" style="font-size: 1rem;">{{ $pet->name }}</span>
                                <span class="small text-muted pet-species text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                    {{ $pet->species ?? 'Unknown' }}
                                </span>
                            </div>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <style>
                /* Pet Selection Custom Styles */
                .pet-selection-card:hover {
                    background-color: var(--bs-tertiary-bg);
                    border-color: var(--bs-success) !important;
                }
                .pet-selection-card:has(.pet-checkbox:checked) {
                    background-color: rgba(25, 135, 84, 0.1);
                    border-color: var(--bs-success) !important;
                }
                
                /* Dark Mode Adjustments */
                body.dark-theme .pet-selection-container {
                    background-color: #212529 !important;
                    border-color: #495057 !important;
                }
                body.dark-theme .pet-selection-card {
                    background-color: #2c3034;
                    border-color: #495057 !important;
                }
                body.dark-theme .pet-selection-card:hover {
                    background-color: #343a40;
                    border-color: var(--bs-success) !important;
                }
                body.dark-theme .pet-selection-card:has(.pet-checkbox:checked) {
                    background-color: rgba(25, 135, 84, 0.2);
                    border-color: var(--bs-success) !important;
                }
                body.dark-theme .pet-name {
                    color: #f8f9fa !important;
                }
            </style>
          </div>
          <div class="mb-3">
            <div id="serviceLocationSection" class="mb-3 d-none">
                <label class="form-label fw-bold text-secondary small text-uppercase">Service Location</label>
                <div class="d-flex gap-3">
                    <div class="form-check flex-fill p-0">
                        <input type="radio" class="btn-check" name="service_location" id="serviceLocationClinic" value="clinic" checked>
                        <label class="btn btn-outline-success w-100 p-3 rounded-3 d-flex align-items-center justify-content-between shadow-sm" for="serviceLocationClinic">
                            <span class="fw-bold"><i class="bi bi-hospital me-2"></i>Clinic Service</span>
                            <i class="bi bi-check-circle-fill d-none" data-selected-icon></i>
                        </label>
                    </div>
                    <div class="form-check flex-fill p-0">
                        <input type="radio" class="btn-check" name="service_location" id="serviceLocationHome" value="home">
                        <label class="btn btn-outline-primary w-100 p-3 rounded-3 d-flex align-items-center justify-content-between shadow-sm" for="serviceLocationHome">
                            <span class="fw-bold"><i class="bi bi-house-door-fill me-2"></i>Home Service</span>
                            <i class="bi bi-check-circle-fill d-none" data-selected-icon></i>
                        </label>
                    </div>
                </div>
            </div>

            <div id="homeServiceFields" class="mb-3 d-none">
                <label class="form-label fw-bold text-secondary small text-uppercase">Home Service Details</label>
                <div class="row g-2">
                    <div class="col-12">
                        <input type="text" class="form-control shadow-sm" name="service_address" id="serviceAddress" placeholder="Home address">
                    </div>
                    <div class="col-12 col-md-6">
                        <input type="text" class="form-control shadow-sm" name="service_contact" id="serviceContact" placeholder="Contact number">
                    </div>
                    <div class="col-12 col-md-6">
                        <select class="form-select shadow-sm" id="homeSlotSelect">
                            <option value="">Select a slot</option>
                        </select>
                    </div>
                </div>
                <div class="small text-muted mt-2 d-none" id="homeSlotEmpty">No available slots.</div>
            </div>

            <label class="form-label fw-bold text-secondary small text-uppercase">Choose Date & Time</label>
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white border-end-0 text-success"><i class="bi bi-clock-fill"></i></span>
                <input type="datetime-local" name="appointment_date" id="bookingAppointmentDate" class="form-control border-start-0 ps-0" required style="cursor: pointer;">
            </div>
          </div>

          {{-- Price Summary --}}
          <div class="card border-0 bg-success-subtle mb-3 rounded-3 overflow-hidden">
             <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small text-muted">Service Price</span>
                    <span class="fw-bold text-dark" id="bookingServicePrice">₱0.00</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small text-muted">Number of Pets</span>
                    <span class="fw-bold text-dark" id="bookingPetCount">0</span>
                </div>
                <hr class="my-2 border-secondary opacity-25">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small fw-bold text-dark">Total Price</span>
                    <span class="fw-bold text-success fs-5" id="bookingTotalPrice">₱0.00</span>
                </div>
                <div class="d-flex justify-content-between align-items-center bg-white rounded-3 p-2 mt-2 shadow-sm border border-success-subtle">
                    <div class="d-flex align-items-center text-success">
                        <i class="bi bi-wallet2 me-2"></i>
                        <span class="small fw-bold text-uppercase">Downpayment Required (50%)</span>
                    </div>
                    <span class="fw-bold text-success" id="bookingDownpayment">₱0.00</span>
                </div>
             </div>
          </div>
          
          <div class="mb-3">
            <label class="form-label fw-bold text-secondary small text-uppercase">Payment Method</label>
            <div class="d-flex gap-3">
                <div class="form-check flex-fill p-0">
                    <input type="radio" class="btn-check" name="payment_method" id="payClinic" value="clinic" checked onchange="togglePaymentOptions()">
                    <label class="btn btn-outline-success w-100 p-3 rounded-3 d-flex flex-column align-items-center gap-2 h-100 shadow-sm" for="payClinic">
                        <i class="bi bi-shop fs-3"></i>
                        <span class="fw-bold" id="payClinicLabel">Pay at Clinic</span>
                    </label>
                </div>
                <div class="form-check flex-fill p-0">
                    <input type="radio" class="btn-check" name="payment_method" id="payOnline" value="online" onchange="togglePaymentOptions()">
                    <label class="btn btn-outline-primary w-100 p-3 rounded-3 d-flex flex-column align-items-center gap-2 h-100 shadow-sm" for="payOnline">
                        <i class="bi bi-credit-card fs-3"></i>
                        <span class="fw-bold">Pay Online</span>
                    </label>
                </div>
            </div>
          </div>

          {{-- Online Payment Options (Hidden by default) --}}
          <div id="onlinePaymentOptions" class="mb-3 p-3 rounded-3 border payment-options-container" style="display: none;">
              <label class="form-label fw-bold small text-uppercase mb-2 payment-options-label">Payment Option</label>
              <div class="d-flex flex-column gap-2">
                  <div class="position-relative">
                      <input type="radio" class="btn-check" name="payment_option" id="payHalf" value="half" checked>
                      <label class="payment-option-card d-flex align-items-center p-3 rounded-3 border w-100" for="payHalf">
                          <div class="flex-shrink-0 me-3">
                              <div class="payment-icon-wrapper rounded-circle d-flex align-items-center justify-content-center">
                                   <i class="bi bi-pie-chart-fill fs-4"></i>
                              </div>
                          </div>
                          <div class="flex-grow-1">
                              <div class="d-flex justify-content-between align-items-center mb-1">
                                  <span class="fw-bold payment-option-title">Downpayment (50%)</span>
                                  <span class="badge rounded-pill payment-badge-half" id="optionHalfPrice">₱0.00</span>
                              </div>
                              <small class="payment-option-desc">Pay the rest at the clinic.</small>
                          </div>
                      </label>
                  </div>

                  <div class="position-relative">
                      <input type="radio" class="btn-check" name="payment_option" id="payFull" value="full">
                      <label class="payment-option-card d-flex align-items-center p-3 rounded-3 border w-100" for="payFull">
                          <div class="flex-shrink-0 me-3">
                               <div class="payment-icon-wrapper rounded-circle d-flex align-items-center justify-content-center">
                                   <i class="bi bi-check-circle-fill fs-4"></i>
                              </div>
                          </div>
                          <div class="flex-grow-1">
                              <div class="d-flex justify-content-between align-items-center mb-1">
                                  <span class="fw-bold payment-option-title">Full Amount</span>
                                  <span class="badge rounded-pill payment-badge-full" id="optionFullPrice">₱0.00</span>
                              </div>
                              <small class="payment-option-desc">Settle everything now.</small>
                          </div>
                      </label>
                  </div>
              </div>

              <div class="mt-3 border-top pt-3" id="onlinePaymentProof">
                  <div class="fw-bold text-secondary small text-uppercase mb-2">Pay via Clinic QR</div>
                  <div class="d-flex flex-column flex-md-row gap-3 align-items-start">
                      <div class="bg-white rounded-3 border p-3 shadow-sm w-100" style="max-width: 280px;" id="clinicQrWrapper">
                          <img id="clinicQrImage" src="" alt="Clinic QR Code" class="img-fluid rounded-3 d-none">
                          <div class="text-muted small d-none" id="clinicQrMissing">This clinic has no QR code uploaded yet.</div>
                      </div>
                      <div class="flex-grow-1">
                          <label class="form-label fw-bold text-secondary small text-uppercase">Upload Receipt (Proof)</label>
                          <input type="file" name="receipt" id="receiptInput" class="form-control shadow-sm" accept="image/*">
                          <div class="small text-muted mt-2">Upload a clear screenshot/photo of your payment receipt.</div>
                      </div>
                  </div>
              </div>
          </div>
        </div>
        <div class="modal-footer border-0 bg-light-subtle pb-4 px-4">
          <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">Confirm Booking</button>
        </div>
      </form>
    </div>
  </div>
</div>
{{-- Logout Confirmation Modal --}}
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
      <div class="modal-header border-0 bg-danger text-white">
        <h5 class="modal-title fw-bold" id="logoutModalLabel">
            <i class="bi bi-box-arrow-right me-2"></i>Sign Out
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 text-center bg-light-subtle">
        <div class="mb-3">
             <div class="bg-danger-subtle text-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                <i class="bi bi-door-open-fill display-4"></i>
             </div>
        </div>
        <h5 class="fw-bold text-dark mb-2">Leaving so soon?</h5>
        <p class="text-muted small mb-0">Are you sure you want to end your session?</p>
      </div>
      <div class="modal-footer border-0 bg-light-subtle justify-content-center pb-4">
        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Stay</button>
        <form action="{{ route('logout') }}" method="POST" class="m-0">
          @csrf
          <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">Yes, Logout</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- ✅ Paste REVIEW MODAL here -->
<div class="modal fade" id="reviewModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" action="{{ route('reviews.store') }}" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="appointment_id" id="reviewAppointment">
      <input type="hidden" name="clinic_id" id="reviewClinic">

      <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
        <div class="modal-header bg-primary text-white border-0">
          <div class="d-flex align-items-center gap-2">
            <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
              <i class="bi bi-star-fill"></i>
            </div>
            <div>
              <h5 class="modal-title fw-bold mb-0">Rate Clinic</h5>
              <div class="small opacity-75">Share your experience</div>
            </div>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body p-4 bg-light-subtle">
          <div class="text-center mb-4">
            <div id="starRating" class="text-warning" style="font-size: 2rem;">
              @for($i=1;$i<=5;$i++)
                <i class="bi bi-star star" data-value="{{ $i }}" style="cursor:pointer;"></i>
              @endfor
            </div>
            <input type="hidden" name="rating" id="ratingValue" required>
            <div class="small text-muted mt-2">Tap a star to set your rating.</div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold text-secondary small text-uppercase">Review (Optional)</label>
            <textarea name="review" class="form-control shadow-sm" rows="4" placeholder="Write your experience..."></textarea>
          </div>

          <div class="mb-0">
            <label class="form-label fw-bold text-secondary small text-uppercase">Add Photo (Optional)</label>
            <input type="file" name="images[]" id="reviewImages" class="form-control shadow-sm" accept="image/*" multiple>
            <div class="d-flex flex-wrap gap-2 mt-3" id="reviewImagesPreview"></div>
            <div class="small text-muted mt-2">You can upload up to 5 images.</div>
          </div>
        </div>

        <div class="modal-footer border-0 bg-light-subtle pb-4 px-4">
          <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Submit Review</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- Mobile Profile Bottom Sheet -->
<div class="mobile-profile-overlay" id="mobileProfileOverlay"></div>
<div class="mobile-profile-sheet" id="mobileProfileSheet">
    <div class="sheet-handle-bar mb-3 d-flex justify-content-center">
        <div class="sheet-handle bg-secondary opacity-25 rounded-pill" style="width: 40px; height: 5px;"></div>
    </div>

    <div class="d-flex align-items-center mb-4">
        <img src="{{ Auth::user()->profile_image ? asset('storage/'.Auth::user()->profile_image) : asset('images/owner.png') }}"
             alt="Profile"
             class="rounded-circle me-3 object-fit-cover shadow-sm"
             style="width: 70px; height: 70px; border: 3px solid #198754;">
        <div>
            <h5 class="fw-bold mb-1 mobile-profile-name">{{ Auth::user()->username ?? Auth::user()->name ?? 'User' }}</h5>
            <small class="text-muted">Pet Owner</small>
        </div>
    </div>

    <a href="{{ route('pet_owner.edit') }}" class="btn btn-success w-100 rounded-pill py-2 fw-bold mb-3 shadow-sm">
        <i class="bi bi-pencil-square me-2"></i>Edit Profile
    </a>

    <button id="mobileThemeBtn" class="btn btn-light w-100 d-flex align-items-center justify-content-start p-2 mb-3 rounded-pill shadow-sm border">
        <img id="mobileThemeGif" 
             src="{{ asset('images/light (2).gif') }}" 
             alt="Toggle Theme" 
             class="rounded-circle me-3" 
             style="width: 40px; height: 40px; object-fit: cover;">
        <span class="fw-bold" style="font-size: 1.1rem; color: #333;">Switch Theme</span>
    </button>
</div>

<style>
    /* Mobile Profile Sheet */
    .mobile-profile-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1085; /* Below nav (1080? No nav is 1080, sheet should be above nav?) */
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s;
        backdrop-filter: blur(2px);
    }
    .mobile-profile-overlay.show {
        opacity: 1;
        visibility: visible;
        z-index: 1085;
    }

    .mobile-profile-sheet {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(255, 255, 255, 0.98);
        border-top-left-radius: 24px;
        border-top-right-radius: 24px;
        padding: 20px 24px 30px; /* Extra bottom padding */
        min-height: 350px; /* Ensure a taller slide */
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        z-index: 1090; /* Above nav (1080) */
        transform: translateY(100%);
        transition: transform 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 -10px 30px rgba(0,0,0,0.1);
    }
    .mobile-profile-sheet.show {
        transform: translateY(0);
    }

    /* Dark Theme Support */
    body.dark-theme .mobile-profile-sheet {
        background: #1e1e1e;
        color: #e0e0e0;
        box-shadow: 0 -10px 30px rgba(0,0,0,0.3);
    }
    body.dark-theme .mobile-profile-name {
        color: #e0e0e0;
    }
    body.dark-theme .mobile-profile-sheet .fw-bold {
        color: #e0e0e0 !important;
    }
    body.dark-theme #mobileThemeBtn {
        background-color: #333;
        border-color: #555;
    }
    body.dark-theme #mobileThemeBtn span {
        color: #e0e0e0 !important;
    }

    .last-no-border:last-child {
        border-bottom: 0 !important;
        padding-bottom: 0 !important;
        margin-bottom: 0 !important;
    }
</style>
<!-- Vet Notes Modal -->
<div class="modal fade" id="vetNotesModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
      <div class="modal-header bg-white border-bottom-0 pb-0">
        <h5 class="modal-title fw-bold text-dark"><i class="bi bi-journal-medical me-2 text-primary"></i>Vet Notes</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 bg-light-subtle">
        @php
            $hasVetNotes = false;
            // Filter appointments with vet notes (either in medicalRecords or directly on appointment)
            $notesAppointments = $recentAppointments->filter(function($app) {
                return $app->medicalRecords->contains(function($record) {
                    return !empty($record->vet_notes);
                }) || !empty($app->vet_notes);
            });
        @endphp

        @if($notesAppointments->count() > 0)
            <!-- List View -->
            <div id="vetNotesList" class="d-block">
                @foreach($notesAppointments as $appointment)
                    @php
                        $clinicName = $appointment->clinic->clinic_name ?? 'Unknown Clinic';
                        $date = \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y h:i A');
                        $clinicImage = $appointment->clinic->profile_image ? asset('storage/' . $appointment->clinic->profile_image) : asset('images/default_profile.png');
                        $petNames = $appointment->pets->pluck('name')->join(', ');
                    @endphp
                    <div class="card mb-3 border-0 shadow-sm rounded-4 bg-white clickable-card cursor-pointer" onclick="handleVetNoteClick('{{ $appointment->id }}', {{ $appointment->pets->count() }})" style="cursor: pointer; transition: transform 0.2s;">
                        <div class="card-body p-3 d-flex align-items-center">
                            <img src="{{ $clinicImage }}" class="rounded-circle me-3 border" width="50" height="50" style="object-fit:cover;">
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">{{ $clinicName }}</h6>
                                <small class="text-muted"><i class="bi bi-calendar-event me-1"></i>{{ $date }}</small>
                                <div class="small text-primary mt-1"><i class="bi bi-journal-text me-1"></i>Vet notes for {{ $petNames }}</div>
                            </div>
                            <i class="bi bi-chevron-right ms-auto text-muted"></i>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pet Selection Views (for multi-pet appointments) -->
            @foreach($notesAppointments as $appointment)
                @if($appointment->pets->count() > 1)
                    <div id="petSelection-{{ $appointment->id }}" class="d-none pet-selection-view">
                        <button class="btn btn-link text-decoration-none px-0 mb-3 fw-bold" onclick="showVetNotesList()">
                            <i class="bi bi-arrow-left me-2"></i>Back to List
                        </button>
                        <h5 class="fw-bold text-dark mb-4 ps-2 border-start border-4 border-primary">Select a Pet</h5>
                        <div class="row g-3">
                            @foreach($appointment->pets as $pet)
                                @php
                                    $record = $appointment->medicalRecords->where('pet_id', $pet->id)->first();
                                    $hasNotes = $record && !empty($record->vet_notes);
                                @endphp
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm rounded-4 bg-white h-100 clickable-card {{ $hasNotes ? 'cursor-pointer' : 'opacity-75' }}" 
                                         @if($hasNotes) onclick="showVetNoteForPet('{{ $appointment->id }}', '{{ $pet->id }}')" @endif
                                         style="{{ $hasNotes ? 'cursor: pointer; transition: transform 0.2s;' : 'cursor: default; background-color: #f8f9fa !important;' }}">
                                        <div class="card-body p-4 d-flex align-items-center">
                                            <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                                <i class="bi bi-paw fs-4"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-1 text-dark">{{ $pet->name }}</h6>
                                                <small class="{{ $hasNotes ? 'text-muted' : 'text-danger' }}">
                                                    {{ $hasNotes ? 'Click to view notes' : 'No notes available' }}
                                                </small>
                                            </div>
                                            @if($hasNotes)
                                                <i class="bi bi-chevron-right ms-auto text-muted"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            <!-- Detail Views -->
            @foreach($notesAppointments as $appointment)
                @php
                    $clinicName = $appointment->clinic->clinic_name ?? 'Unknown Clinic';
                    $date = \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y h:i A');
                    $clinicImage = $appointment->clinic->profile_image ? asset('storage/' . $appointment->clinic->profile_image) : asset('images/default_profile.png');
                    $validRecords = $appointment->medicalRecords->filter(function($r) { return !empty($r->vet_notes); });
                @endphp
                <div id="vetNoteDetail-{{ $appointment->id }}" class="d-none vet-note-detail">
                    <button class="btn btn-link text-decoration-none px-0 mb-3 fw-bold" onclick="goBackFromDetail('{{ $appointment->id }}')">
                        <i class="bi bi-arrow-left me-2"></i>Back
                    </button>
                    
                    <div class="card border-0 shadow-sm rounded-4 bg-white">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $clinicImage }}" class="rounded-circle me-3 border" width="60" height="60" style="object-fit:cover;">
                                    <div>
                                        <h5 class="fw-bold mb-1 text-dark">{{ $clinicName }}</h5>
                                        <div class="text-muted small">
                                            {{ $date }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @foreach($appointment->pets as $pet)
                                @php
                                    $record = $appointment->medicalRecords->where('pet_id', $pet->id)->first();
                                    $hasNotes = $record && !empty($record->vet_notes);
                                    
                                    $weight = $record->weight ?? 'N/A';
                                    $vaccineStatus = $record->vaccine_status ?? 'N/A';
                                    $vaccinationDates = $record->vaccination_dates ?? 'N/A';
                                    $healthCondition = $record->health_condition ?? 'N/A';
                                    $noteContent = $record->vet_notes ?? '';
                                    $petMedications = $appointment->medications->where('pet_id', $pet->id);

                                    // If only 1 pet, show it. If multiple, show none by default (waiting for selection)
                                    $isHidden = $appointment->pets->count() > 1 ? 'd-none' : '';
                                @endphp
                                <div id="petNote-{{ $appointment->id }}-{{ $pet->id }}" class="pet-note-container {{ $isHidden }}">
                                    @if($hasNotes)
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="fw-bold text-primary fs-5">
                                                <i class="bi bi-paw me-1"></i>{{ $pet->name }}
                                            </div>
                                            <a class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm"
                                               href="{{ route('pet_owner.medical_record.pdf', ['appointment' => $appointment->id, 'pet' => $pet->id]) }}">
                                                <i class="bi bi-filetype-pdf me-2"></i>Download PDF
                                            </a>
                                        </div>

                                        <!-- Medical Details Grid -->
                                        <div class="row g-3 mb-3 small">
                                            <div class="col-6 col-md-3">
                                                <div class="p-3 bg-light rounded-4 h-100 text-center border">
                                                    <span class="d-block text-muted text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Weight</span>
                                                    <span class="fw-bold text-dark fs-6">{{ $weight }} kg</span>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <div class="p-3 bg-light rounded-4 h-100 text-center border">
                                                    <span class="d-block text-muted text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Health</span>
                                                    <span class="fw-bold text-dark fs-6">{{ $healthCondition }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <div class="p-3 bg-light rounded-4 h-100 text-center border">
                                                    <span class="d-block text-muted text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Vaccine</span>
                                                    <span class="fw-bold text-dark fs-6">{{ $vaccineStatus }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <div class="p-3 bg-light rounded-4 h-100 text-center border">
                                                    <span class="d-block text-muted text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Dates</span>
                                                    <span class="fw-bold text-dark fs-6">{{ $vaccinationDates }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-light p-4 rounded-4 border">
                                            <span class="d-block text-primary fw-bold text-uppercase mb-2" style="font-size: 0.8rem; letter-spacing: 1px;">
                                                <i class="bi bi-chat-square-text-fill me-2"></i>Veterinarian's Notes
                                            </span>
                                            <p class="mb-0 text-dark" style="white-space: pre-wrap; line-height: 1.6;">{{ $noteContent }}</p>
                                        </div>

                                        <div class="bg-light p-4 rounded-4 border mt-3">
                                            <span class="d-block text-primary fw-bold text-uppercase mb-2" style="font-size: 0.8rem; letter-spacing: 1px;">
                                                <i class="bi bi-capsule-pill me-2"></i>Prescription
                                            </span>
                                            @if($petMedications->count() > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-sm align-middle mb-0">
                                                        <thead>
                                                            <tr class="text-muted small text-uppercase">
                                                                <th class="border-0">Medicine</th>
                                                                <th class="border-0">Administration</th>
                                                                <th class="border-0">Schedule</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($petMedications as $med)
                                                                <tr class="border-top">
                                                                    <td class="fw-bold text-dark">{{ $med->medicine_name ?? 'N/A' }}</td>
                                                                    <td class="text-dark">{{ $med->dosage ?? 'N/A' }}</td>
                                                                    <td class="text-dark">{{ $med->schedule ?? 'N/A' }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="text-muted">No prescription recorded for this visit.</div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <div class="bg-secondary-subtle text-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                                <i class="bi bi-clipboard-x fs-3"></i>
                                            </div>
                                            <h6 class="text-muted fw-bold">No notes available for {{ $pet->name }}</h6>
                                        </div>
                                    @endif
                                </div>
                            @endforeach

                            @if($appointment->vet_notes && $appointment->medicalRecords->isEmpty())
                                <!-- Legacy Support for appointments without medical records but with direct notes -->
                                <div class="bg-light p-4 rounded-4 border">
                                    <span class="d-block text-primary fw-bold text-uppercase mb-2" style="font-size: 0.8rem; letter-spacing: 1px;">
                                        <i class="bi bi-chat-square-text-fill me-2"></i>General Notes
                                    </span>
                                    <p class="mb-0 text-dark" style="white-space: pre-wrap; line-height: 1.6;">{{ $appointment->vet_notes }}</p>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-5">
                <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="bi bi-journal-x fs-1"></i>
                </div>
                <h5 class="text-muted fw-bold">No vet notes found.</h5>
                <p class="text-muted small mb-0">Vet notes will appear here after your appointments are completed.</p>
            </div>
        @endif
      </div>
      <div class="modal-footer border-0 bg-light-subtle">
        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
function handleVetNoteClick(id, count) {
    // Hide list
    document.getElementById('vetNotesList').classList.remove('d-block');
    document.getElementById('vetNotesList').classList.add('d-none');
    
    // Hide all details and selections first to be safe
    document.querySelectorAll('.vet-note-detail').forEach(el => el.classList.add('d-none'));
    document.querySelectorAll('.pet-selection-view').forEach(el => el.classList.add('d-none'));

    if (count > 1) {
        // Show pet selection
        const selection = document.getElementById('petSelection-' + id);
        if (selection) {
            selection.classList.remove('d-none');
            selection.classList.add('d-block');
        }
    } else {
        // Show detail directly
        const detail = document.getElementById('vetNoteDetail-' + id);
        if (detail) {
            detail.classList.remove('d-none');
            detail.classList.add('d-block');
            
            // Ensure the single note is visible
            const notes = detail.querySelectorAll('.pet-note-container');
            if (notes.length > 0) {
                notes[0].classList.remove('d-none');
            }
        }
    }
}

function showVetNoteForPet(apptId, recordId) {
    // Hide selection
    const selection = document.getElementById('petSelection-' + apptId);
    if (selection) {
        selection.classList.remove('d-block');
        selection.classList.add('d-none');
    }

    // Show detail
    const detail = document.getElementById('vetNoteDetail-' + apptId);
    if (detail) {
        detail.classList.remove('d-none');
        detail.classList.add('d-block');
    }

    // Hide all notes in this detail
    const notes = document.querySelectorAll(`[id^="petNote-${apptId}-"]`);
    notes.forEach(note => {
        note.classList.add('d-none');
    });

    // Show specific note
    const target = document.getElementById(`petNote-${apptId}-${recordId}`);
    if (target) {
        target.classList.remove('d-none');
    }
}

function goBackFromDetail(apptId) {
    // Hide detail
    const detail = document.getElementById('vetNoteDetail-' + apptId);
    if (detail) {
        detail.classList.remove('d-block');
        detail.classList.add('d-none');
    }

    // Check if we have a pet selection view (meaning multi-pet)
    const selection = document.getElementById('petSelection-' + apptId);
    if (selection) {
        // Show selection
        selection.classList.remove('d-none');
        selection.classList.add('d-block');
    } else {
        // Go back to list
        showVetNotesList();
    }
}

function showVetNotesList() {
    // Hide all details
    const details = document.querySelectorAll('.vet-note-detail');
    details.forEach(el => {
        el.classList.remove('d-block');
        el.classList.add('d-none');
    });
    
    // Hide all selections
    const selections = document.querySelectorAll('.pet-selection-view');
    selections.forEach(el => {
        el.classList.remove('d-block');
        el.classList.add('d-none');
    });
    
    // Show list
    const list = document.getElementById('vetNotesList');
    if (list) {
        list.classList.remove('d-none');
        list.classList.add('d-block');
    }
}

// Reset modal to list view when closed
document.addEventListener('DOMContentLoaded', function() {
    const vetNotesModal = document.getElementById('vetNotesModal');
    if (vetNotesModal) {
        vetNotesModal.addEventListener('hidden.bs.modal', function () {
            showVetNotesList();
        });
    }
});


function downloadVetNotePdf(btn) {
    const clinicImage = btn?.dataset?.clinicImage || '';
    const clinicName = btn?.dataset?.clinicName || 'Clinic';
    const date = btn?.dataset?.date || '';
    const petName = btn?.dataset?.petName || 'Pet';
    const notes = btn?.dataset?.notes || '';
    const weight = btn?.dataset?.weight || 'N/A';
    const vaccineStatus = btn?.dataset?.vaccineStatus || 'N/A';
    const vaccinationDates = btn?.dataset?.vaccinationDates || 'N/A';
    const healthCondition = btn?.dataset?.healthCondition || 'N/A';
    let medications = [];
    try {
        medications = JSON.parse(btn?.dataset?.medications || '[]');
    } catch (_) {
        medications = [];
    }

    const medsTable = medications.length
        ? `
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 35%;">Medicine</th>
                        <th style="width: 35%;">Administration</th>
                        <th style="width: 30%;">Schedule</th>
                    </tr>
                </thead>
                <tbody>
                    ${medications.map(m => `
                        <tr>
                            <td><div class="strong">${(m.medicine_name || 'N/A')}</div></td>
                            <td>${(m.dosage || 'N/A')}</td>
                            <td>${(m.schedule || 'N/A')}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `
        : `<div class="muted">No prescription recorded for this visit.</div>`;

    const html = `
        <!doctype html>
        <html>
        <head>
            <meta charset="utf-8" />
            <title>Prescription - ${petName}</title>
            <style>
                @page { size: A4; margin: 14mm; }
                * { box-sizing: border-box; }
                body { font-family: Arial, Helvetica, sans-serif; color: #111827; }
                .sheet { width: 100%; }
                .header { display: flex; gap: 16px; align-items: center; border-bottom: 2px solid #0ea5a4; padding-bottom: 12px; margin-bottom: 14px; }
                .logo { width: 56px; height: 56px; border-radius: 999px; object-fit: cover; border: 1px solid #e5e7eb; }
                .h1 { font-size: 18px; font-weight: 800; margin: 0; }
                .sub { margin: 4px 0 0; color: #6b7280; font-size: 12px; }
                .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px; }
                .card { border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px 12px; }
                .label { font-size: 10px; text-transform: uppercase; letter-spacing: 0.08em; color: #6b7280; margin-bottom: 4px; }
                .value { font-size: 12px; font-weight: 700; color: #111827; }
                .section { margin-top: 14px; }
                .title { font-size: 12px; font-weight: 800; margin: 0 0 8px; }
                .note { border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px 12px; white-space: pre-wrap; line-height: 1.5; font-size: 12px; }
                .table { width: 100%; border-collapse: collapse; font-size: 12px; }
                .table th { text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.08em; color: #6b7280; padding: 8px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
                .table td { padding: 8px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
                .strong { font-weight: 800; }
                .muted { color: #6b7280; font-size: 12px; }
                .footer { margin-top: 18px; padding-top: 10px; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; gap: 12px; font-size: 10px; color: #6b7280; }
                .sig { margin-top: 22px; display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
                .sigline { border-top: 1px solid #111827; padding-top: 6px; font-size: 11px; }
            </style>
        </head>
        <body>
            <div class="sheet">
                <div class="header">
                    <img class="logo" src="${clinicImage}" alt="Clinic" />
                    <div>
                        <p class="h1">Medical Record & Prescription</p>
                        <p class="sub">PetApp • Generated document • Save as PDF from the print dialog</p>
                    </div>
                </div>

                <div class="grid">
                    <div class="card">
                        <div class="label">Clinic</div>
                        <div class="value">${clinicName}</div>
                    </div>
                    <div class="card">
                        <div class="label">Visit Date</div>
                        <div class="value">${date || 'N/A'}</div>
                    </div>
                    <div class="card">
                        <div class="label">Pet</div>
                        <div class="value">${petName}</div>
                    </div>
                    <div class="card">
                        <div class="label">Weight</div>
                        <div class="value">${weight === 'N/A' ? 'N/A' : `${weight} kg`}</div>
                    </div>
                    <div class="card">
                        <div class="label">Health Condition</div>
                        <div class="value">${healthCondition}</div>
                    </div>
                    <div class="card">
                        <div class="label">Vaccination</div>
                        <div class="value">${vaccineStatus}</div>
                        <div class="muted">${vaccinationDates}</div>
                    </div>
                </div>

                <div class="section">
                    <p class="title">Veterinarian's Notes</p>
                    <div class="note">${notes || 'N/A'}</div>
                </div>

                <div class="section">
                    <p class="title">Prescription</p>
                    ${medsTable}
                </div>

                <div class="sig">
                    <div class="sigline">Veterinarian Signature</div>
                    <div class="sigline">Pet Owner Signature</div>
                </div>

                <div class="footer">
                    <div>Generated: ${new Date().toLocaleString()}</div>
                    <div>&copy; ${new Date().getFullYear()} PetApp</div>
                </div>
            </div>
        </body>
        </html>
    `;

    const iframe = document.createElement('iframe');
    iframe.style.position = 'fixed';
    iframe.style.right = '0';
    iframe.style.bottom = '0';
    iframe.style.width = '0';
    iframe.style.height = '0';
    iframe.style.border = '0';
    iframe.setAttribute('aria-hidden', 'true');
    document.body.appendChild(iframe);

    const doc = iframe.contentDocument || iframe.contentWindow?.document;
    if (!doc || !iframe.contentWindow) {
        document.body.removeChild(iframe);
        return;
    }

    doc.open();
    doc.write(html);
    doc.close();

    const cleanup = () => {
        if (iframe.parentNode) {
            iframe.parentNode.removeChild(iframe);
        }
    };

    iframe.contentWindow.onafterprint = cleanup;
    setTimeout(() => {
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
    }, 250);
    setTimeout(cleanup, 15000);
}

function filterAnimals() {
    let input = document.getElementById('mobileSearchInput');
    let filter = input.value.toUpperCase();
    
    // Reset if empty
    if (filter === '') {
        document.querySelectorAll('.category-content').forEach(el => {
            el.style.maxHeight = '0px';
        });
        document.querySelectorAll('.category-toggle-btn').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.animal-btn').forEach(btn => {
            btn.parentElement.style.display = '';
        });
        return;
    }

    // Process all categories
    document.querySelectorAll('.category-content').forEach(catContent => {
        let matchFoundInCategory = false;
        let animals = catContent.querySelectorAll('.animal-btn');
        
        animals.forEach(btn => {
            let animalName = btn.getAttribute('data-animal').toUpperCase();
            let parentCol = btn.parentElement; // The column div
            
            if (animalName.indexOf(filter) > -1) {
                parentCol.style.display = "";
                matchFoundInCategory = true;
            } else {
                parentCol.style.display = "none";
            }
        });
        
        // Expand/Collapse category based on matches
        if (matchFoundInCategory) {
            catContent.style.maxHeight = catContent.scrollHeight + 'px';
            
            // Find corresponding toggle button to set active
            let catId = catContent.id.replace('-mobile', '').replace('-desktop', '');
            let toggleBtn = document.querySelector(`.category-toggle-btn[data-category="${catId}"]`);
            if(toggleBtn) toggleBtn.classList.add('active');
        } else {
            catContent.style.maxHeight = '0px';
            
            // Deactivate toggle button if no matches in this category (and check if other view might have it open? simpler to just remove if not match)
            // But one button controls both views? No, one button per category.
            // Actually, the structure is:
            // Loop categories
            //   Button
            //   Mobile Content
            // End Loop
            // Desktop Content (separate loop)
            
            // So for desktop content, the button is "far away" in DOM.
            // But we can find it by data-category.
        }
    });
}
</script>

<!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            <div class="modal-header border-bottom-0 p-3">
                <div class="input-group shadow-sm rounded-pill bg-light border w-100 p-1">
                    <span class="input-group-text border-0 bg-transparent ps-3"><i class="bi bi-search text-muted fs-5"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none fs-5" id="modalSearchInput" placeholder="Search services..." onkeyup="searchServices(this.value)">
                    <button type="button" class="btn btn-link text-muted text-decoration-none pe-3" data-bs-dismiss="modal"><i class="bi bi-x-lg fs-5"></i></button>
                </div>
            </div>
            <div class="modal-body px-0 pt-0">
                <div id="modalSearchResults" class="list-group list-group-flush">
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-search fs-1 mb-3 d-block opacity-25"></i>
                        <p class="fs-5">Type to search for services, clinics, or animals...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Location Selection Modal (Mobile) -->
<div class="modal fade" id="locationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0">
                <div class="w-100">
                    <div class="d-flex align-items-start justify-content-between gap-3">
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-1">Select City</h5>
                            @if($selectedLocation)
                                <div class="small text-muted">Selected: <span class="fw-semibold">{{ $selectedLocation }}</span></div>
                            @else
                                <div class="small text-muted">Choose a city to show nearby clinics.</div>
                            @endif
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="input-group mt-3">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0 shadow-none" id="locationModalSearch" placeholder="Search city">
                    </div>
                </div>
            </div>
            <div class="modal-body p-3 pt-0">
                <div class="row g-2" id="locationModalCityList">
                    @foreach(['Cebu City', 'Lapu-Lapu City', 'Mandaue City', 'Talisay City', 'Danao City', 'Toledo City', 'Carcar City', 'Naga City', 'Bogo City'] as $city)
                        <div class="col-12 col-md-6" data-city-wrap>
                            <a href="{{ route('pet_owner.dashboard', ['location' => $city]) }}"
                               data-city="{{ $city }}"
                               class="btn {{ $selectedLocation == $city ? 'btn-success' : 'btn-outline-success' }} w-100 rounded-3 py-3 d-flex align-items-center justify-content-between">
                                <span class="fw-semibold">{{ $city }}</span>
                                @if($selectedLocation == $city)
                                    <i class="bi bi-check-circle-fill"></i>
                                @endif
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="text-center text-muted small py-4 d-none" id="locationModalEmpty">No matching city found.</div>
            </div>
            <div class="modal-footer border-0 pt-0">
                @if($selectedLocation)
                    <a href="{{ route('pet_owner.dashboard') }}" class="btn btn-outline-danger rounded-pill fw-bold px-4 me-auto">
                        <i class="bi bi-x-circle me-2"></i>Clear Filter
                    </a>
                @endif
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>

<script>
(() => {
    const modalEl = document.getElementById('locationModal');
    if (!modalEl) return;

    const input = modalEl.querySelector('#locationModalSearch');
    const empty = modalEl.querySelector('#locationModalEmpty');
    const wraps = Array.from(modalEl.querySelectorAll('[data-city-wrap]'));

    const applyFilter = () => {
        const q = (input?.value || '').trim().toLowerCase();
        let visible = 0;
        wraps.forEach(wrap => {
            const btn = wrap.querySelector('[data-city]');
            const city = (btn?.dataset.city || '').toLowerCase();
            const show = !q || city.includes(q);
            wrap.style.display = show ? '' : 'none';
            if (show) visible += 1;
        });
        if (empty) empty.classList.toggle('d-none', visible !== 0);
    };

    modalEl.addEventListener('shown.bs.modal', () => {
        if (input) {
            input.value = '';
            input.focus();
        }
        applyFilter();
    });

    if (input) {
        input.addEventListener('input', applyFilter);
    }
})();
</script>

@endsection

@section('styles')
@parent
<style>
/* Body Background */
body {
    background-color: #f8f9fa;
}

/* Mobile Bottom Nav Styling */
.mobile-bottom-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background: #ffffff; /* Solid white background */
    display: flex;
    justify-content: space-around;
    align-items: center;
    padding: 12px 0;
    z-index: 1050;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.1); /* Add shadow for depth */
    border-top: 1px solid rgba(0,0,0,0.05);
}
.mobile-bottom-nav a {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-decoration: none;
    color: #666;
    font-size: 0.75rem;
    font-weight: 600;
    transition: all 0.3s;
    flex: 1; /* Distribute space evenly */
}
.mobile-bottom-nav a.active {
    color: #198754;
    background: transparent !important;
    transform: translateY(-2px);
}

/* Push content up on mobile to prevent nav overlap */
@media (max-width: 767px) {
    main {
        padding-bottom: 80px !important;
    }
}

/* Welcome */
.welcome-container{
    background: linear-gradient(rgba(248, 249, 250, 0.85), rgba(232, 245, 233, 0.85)), url('{{ asset('images/bg67.png') }}');
    background-size: cover;
    background-position: center;
    border-left: 6px solid #28a745;
}
.text-dark-theme{
    color: inherit;
}
.hover-shadow:hover{
    transform:translateY(-3px);
    box-shadow:0 8px 20px rgba(0,0,0,0.2)!important;
    transition:all .3s;
}
.animal-btn{
    border:none;border-radius:12px;overflow:hidden;
    transition:transform .3s,box-shadow .3s;
    position:relative;height:160px;background:#fff;
}
.animal-btn:hover{
    transform:translateY(-3px);
    box-shadow:0 8px 20px rgba(0,0,0,0.15);
}
.animal-btn img{
    width:80%;
    height:80%;
    object-fit:contain;
    display:block;
    margin:auto;
    margin-top:8px;
}
.animal-btn .overlay{
        position:absolute;
        left:0;
        bottom:0;
        width:100%;
        height:40px;
        display:flex;
        align-items: center;
        justify-content: center;
        background: transparent; /* Removed black strip */
        color: #212529; /* Text color black for readability */
        font-weight: 800; /* Extra bold for better visibility */
        text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.8); /* White shadow to separate text from image */
        font-size: 1.1rem;
    }
    
    /* Clinics Modal Custom Styles */
    .service-item {
        transition: all 0.2s ease-in-out;
        border: 2px solid #f8f9fa;
        cursor: pointer;
    }
    .service-item:hover {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }
    .service-item.active {
        background-color: #e8f5e9;
        border-color: #198754 !important;
        position: relative;
    }
    .service-item.active::after {
        content: '\F26B'; /* Bootstrap Icons check-circle-fill */
        font-family: 'bootstrap-icons';
        position: absolute;
        top: 10px;
        right: 10px;
        color: #198754;
        font-size: 1.2rem;
    }
    .toggle-services-btn {
        background-color: #f8f9fa;
        color: #198754;
        font-weight: 600;
        border: none;
        transition: all 0.2s;
    }
    .toggle-services-btn:hover {
        background-color: #e8f5e9;
        color: #157347;
    }
    align-items:center;
    justify-content:center;
    /* Removed dark mode container background for clarity */
    color:#050206;
    font-weight:600;
    font-size:.95rem;
}
.animal-btn.active{border:2px solid #28a745;}

/* Sidebar Mobile - no backdrop/dim */
@media(max-width:767px){
    #sidebarMenu{
        width:75%;
        max-width:300px;
        transform:translateX(-100%);
        transition:transform .28s cubic-bezier(.2,.8,.2,1);
        z-index:1060;
    }
    #sidebarMenu.show{ transform:translateX(0); }
    #sidebarClose{
        border-radius:8px;
        width:36px;
        height:36px;
        display:inline-flex;
        align-items:center;
        justify-content:center;
    }
}
@media(min-width:768px){
    #sidebarMenu{ transform:translateX(0) !important; }
}

/* ----------------------
   Dark Theme
---------------------- */
html.dark-theme {
    background-color: #121212;
}
body.dark-theme {
    background-color: #121212;
    color: #e0e0e0;
    min-height: 100vh; /* Ensure body covers full viewport height */
}

body.dark-theme .mobile-bottom-nav {
    background: #1e1e1e;
    border-top: 1px solid #333;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.3);
}
body.dark-theme .mobile-bottom-nav a {
    color: #adb5bd;
}
body.dark-theme .mobile-bottom-nav a.active {
    color: #198754;
}

body.dark-theme .card,
body.dark-theme .modal-content,
body.dark-theme .animal-btn,
body.dark-theme #sidebarMenu {
    background-color: #1e1e1e;
    color: #e0e0e0;
}
body.dark-theme .nav-link,
body.dark-theme .nav-link.active {
    color: #e0e0e0 !important;
}
body.dark-theme .table thead {
    background-color: #2a2a2a;
    color: #e0e0e0;
}
body.dark-theme .table tbody tr {
    background-color: #1e1e1e;
    color: #e0e0e0;
}
body.dark-theme .dropdown-menu {
    background-color: #2a2a2a;
    color: #e0e0e0;
}
body.dark-theme .dropdown-item {
    color: #e0e0e0;
}
body.dark-theme .dropdown-item:hover {
    background-color: #3a3a3a;
}
body.dark-theme .modal-header {
    background-color: #2a2a2a !important;
}
body.dark-theme .modal-footer {
    background-color: #1e1e1e !important;
}


/* Dark Theme Adjustments for Welcome Container and Animal Buttons */
body.dark-theme .welcome-container {
    background: linear-gradient(rgba(30, 30, 30, 0.9), rgba(42, 42, 42, 0.9)), url('{{ asset('images/bg67.png') }}');
    background-size: cover;
    background-position: center;
    border-left-color: #28a745;
    color: #e0e0e0; /* general text color */
}

body.dark-theme .welcome-container h2 {
    color: #28a745;
}
body.dark-theme .welcome-container p {
    color: #ffffff !important; /* force white for visibility */
}

body.dark-theme .animal-btn {
    background-color: #2a2a2a;
    color: #e0e0e0;
}
body.dark-theme .category-section-container {
    background-color: #1e1e1e;
    border-color: #444 !important;
}
body.dark-theme .animal-btn .overlay {
    background: transparent; /* removed dark background box */
    color: #ffffff;
}
/* Sidebar Teal Theme & Button Containers */
#sidebarMenu {
    background: #008080 !important; /* Teal color */
    color: white;
}

/* Container-style Buttons */
.sidebar-btn-container {
    background-color: #ffffff;
    border-radius: 12px;
    padding: 15px 18px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
}

/* Glassmorphism Button Containers */
.sidebar-btn-container {
    background: rgba(255, 255, 255, 0.15); /* Semi-transparent white */
    backdrop-filter: blur(10px);           /* The "Frosted" effect */
    -webkit-backdrop-filter: blur(10px);   /* Safari support */
    border: 1px solid rgba(255, 255, 255, 0.2); /* Thin light border */
    border-radius: 15px;
    padding: 16px 20px !important;
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    color: white !important;                /* Text becomes white for glass look */
}

/* Hover Effects */
.sidebar-btn-container:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-3px);
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.4);
}

/* Glass Icon Box */
.icon-box {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    box-shadow: inset 0 0 10px rgba(255, 255, 255, 0.1);
}

/* Glass Logout Button (Reddish Glass) */
.logout-btn-container {
    background: rgba(220, 53, 69, 0.2);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 15px;
    padding: 16px 20px !important;
    color: #ffbaba !important; /* Light red text */
    transition: all 0.3s ease;
}

.logout-btn-container:hover {
    background: rgba(220, 53, 69, 0.4);
    color: white !important;
}

/* Change text colors to white to match glass look */
.sidebar-btn-container span {
    color: white !important;
    letter-spacing: 0.5px;
}
/* =========================
   SIDEBAR DARK MODE (TEAL-FRIENDLY)
========================= */

body.dark-theme #sidebarMenu {
    background: linear-gradient(180deg, #0b1f2a, #102a3a) !important;
    color: #e6f4f1;
}

/* Profile divider */
body.dark-theme #sidebarMenu .border-bottom {
    border-color: rgba(43, 191, 167, 0.25) !important;
}

/* Sidebar buttons (cool glass) */
body.dark-theme .sidebar-btn-container {
    background: rgba(43, 191, 167, 0.08) !important;
    border: 1px solid rgba(43, 191, 167, 0.18);
    color: #e6f4f1 !important;
}

/* Hover effect */
body.dark-theme .sidebar-btn-container:hover {
    background: rgba(43, 191, 167, 0.16) !important;
    box-shadow: 0 10px 30px rgba(0,0,0,0.55);
}

/* Icon boxes */
body.dark-theme .icon-box {
    background: rgba(43, 191, 167, 0.22);
    color: #ffffff;
}

/* Active link highlight */
body.dark-theme .nav-link.active {
    background: rgba(43, 191, 167, 0.25) !important;
    border: 1px solid rgba(43, 191, 167, 0.45);
}

/* Logout (muted red that still fits teal) */
body.dark-theme .logout-btn-container {
    background: rgba(220, 53, 69, 0.25) !important;
    color: #ffdede !important;
}

body.dark-theme .logout-btn-container:hover {
    background: rgba(220, 53, 69, 0.45) !important;
    color: #ffffff !important;
}

/* Text clarity */
body.dark-theme .sidebar-btn-container span,
body.dark-theme #sidebarMenu h6,
body.dark-theme #sidebarMenu small {
    color: #e6f4f1 !important;
}
/* =========================
   SMOOTH THEME TRANSITIONS
========================= */

/* Global smooth color transition */
body,
#sidebarMenu,
.sidebar-btn-container,
.icon-box,
.logout-btn-container,
.card,
.modal-content,
.welcome-container,
.animal-btn,
.dropdown-menu,
.table,
.nav-link {
    transition:
        background-color 0.45s ease,
        color 0.45s ease,
        border-color 0.45s ease,
        box-shadow 0.45s ease,
        transform 0.45s ease;
}

/* Smooth gradient transition (sidebar) */
#sidebarMenu {
    transition:
        background 0.6s ease,
        box-shadow 0.6s ease;
}

/* Disable animation on page load (JS will remove) */
body.theme-transition * {
    transition: none !important;
}
/* Ensure sidebar buttons stay inside sidebar even when zoomed */
.sidebar-btn-container,
.logout-btn-container {
    width: 100%;            /* Full width of the sidebar */
    max-width: 100%;        /* Prevent overflow */
    box-sizing: border-box; /* Include padding & border in width */
    white-space: nowrap;    /* Keep text in a single line */
    overflow: hidden;       /* Hide any overflow */
    text-overflow: ellipsis;/* Show "..." if text is too long */
}

/* Icon + text spacing remains intact */
.sidebar-btn-container .icon-box,
.logout-btn-container .icon-box-logout {
    flex-shrink: 0; /* Prevent icon from shrinking */
}

/* Make the sidebar flex layout handle wrapping nicely */
#sidebarMenu .d-flex.flex-column {
    flex-wrap: nowrap; /* prevent buttons from leaving sidebar */
}
/* =========================
   MOBILE MODE (NO SIDEBAR)
========================= */
@media (max-width: 767px) {

    /* Kill sidebar completely */
    #sidebarMenu {
        display: none !important;
    }

    /* Remove hamburger bar */
    #sidebarToggle,
    .d-md-none.bg-success {
        display: none !important;
    }

    /* Full width content */
    #mainContent {
        margin-left: 0 !important;
        padding-bottom: 90px; /* space for bottom nav */
    }
}
/* =========================
   MOBILE TOP BAR
========================= */
.mobile-topbar {
    background-color: transparent;
    color: white;
    transition: background-color 0.3s, color 0.3s;
    /* Removed fixed/absolute positioning so it stays in flow, but without background */
}

body.dark-theme .mobile-topbar {
    background-color: transparent;
    color: #e0e0e0;
    border-bottom: none;
}

.mobile-app-name {
    font-family: 'Nativera', sans-serif;
    font-size: 1.8rem;
    font-weight: bold;
    color: #333;
    line-height: 1;
    transition: color 0.3s;
}

body.dark-theme .mobile-app-name {
    color: #e0e0e0;
}

.mobile-search-container {
    /* max-width removed to allow flex-grow to work */
}

.mobile-search-icon {
    background-color: #fff;
    border: 1px solid #ced4da;
    border-right: none;
    color: #6c757d;
}

.mobile-search-input {
    background-color: #fff;
    border: 1px solid #ced4da;
    border-left: none;
    color: #333;
    font-size: 0.85rem;
}
.mobile-search-input:focus {
    box-shadow: none;
    border-color: #ced4da;
}

body.dark-theme .mobile-search-icon {
    background-color: #2a2a2a;
    border-color: #444;
    color: #adb5bd;
}

body.dark-theme .mobile-search-input {
    background-color: #2a2a2a;
    border-color: #444;
    color: #e0e0e0;
}
body.dark-theme .mobile-search-input::placeholder {
    color: #6c757d;
}

/* =========================
   ANIMAL BUTTON – MOBILE FIX
========================= */
@media (max-width: 767px) {
    .animal-btn {
        height: 110px;          /* smaller */
        border-radius: 10px;
    }

    .animal-btn img {
        width: 70%;
        height: 65%;
        margin-top: 6px;
    }

    .animal-btn .overlay {
        height: 30px;
        font-size: 0.75rem;
    }
}
@media (max-width: 767px) {
    main#mainContent {
        padding-left: 12px !important;
        padding-right: 12px !important;
        padding-bottom: 150px !important; /* increased space for buttons + bottom nav */
    }
}
@media (max-width: 767px) {
    body.dark-theme .animal-btn {
        background-color: #1e1e1e !important;
    }

    body.dark-theme .animal-btn .overlay {
        color: #ffffff !important;
    }
}
.equal-btn {
    flex: 1;            /* Make both buttons take equal width */
    min-width: 0;       /* Fix flex shrinking issues */
}
/* =========================
   MOBILE FIX – BUTTONS CUT OFF
========================= */
@media (max-width: 767px) {
    .my-buttons-container {
        margin-bottom: 30px !important;
    }
}

/* Slideshow wrapper */
.welcome-slideshow {
    width: 250px;
    height: 250px;
    position: relative;
    flex-shrink: 0;
}

/* All slide items stacked */
.slide-item {
    position: absolute;
    top: 0;
    left: 0;
    width: 250px;
    height: auto;
    opacity: 0;
    animation-duration: 9s;
    animation-timing-function: ease-in-out;
    animation-iteration-count: infinite;
}

/* Animation classes */
.logo-slide {
    z-index: 3;
    animation-name: fadeLogo;
}
.doggo-slide {
    z-index: 2;
    animation-name: fadeDoggo;
}
.gatto-slide {
    z-index: 1;
    animation-name: fadeGatto;
}

/* Keyframes for 3-stage loop */
@keyframes fadeLogo {
    0%, 30% { opacity: 1; }
    33%, 100% { opacity: 0; }
}

@keyframes fadeDoggo {
    0%, 30% { opacity: 0; }
    33%, 63% { opacity: 1; }
    66%, 100% { opacity: 0; }
}

@keyframes fadeGatto {
    0%, 63% { opacity: 0; }
    66%, 96% { opacity: 1; }
    100% { opacity: 0; }
}

/* Dark Theme Adjustment for Slideshow */
body.dark-theme .slide-item {
    filter: invert(1) hue-rotate(180deg);
    -webkit-filter: invert(1) hue-rotate(180deg);
}

/* Search Modal Custom Styles & Dark Mode */
#searchModal .modal-content {
    backdrop-filter: blur(10px);
    background-color: rgba(255, 255, 255, 0.95);
}
body.dark-theme #searchModal .modal-content {
    background-color: rgba(33, 37, 41, 0.95);
    color: #f8f9fa;
}

#searchModal .input-group {
    transition: all 0.3s ease;
}
#searchModal .input-group:focus-within {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    transform: translateY(-1px);
}

body.dark-theme #searchModal .input-group.bg-light {
    background-color: rgba(255, 255, 255, 0.05) !important;
    border-color: rgba(255, 255, 255, 0.1) !important;
}

body.dark-theme #searchModal .form-control {
    color: #f8f9fa;
}
body.dark-theme #searchModal .form-control::placeholder {
    color: rgba(255, 255, 255, 0.5);
}
body.dark-theme #searchModal .input-group-text .bi,
body.dark-theme #searchModal .btn-link .bi {
    color: rgba(255, 255, 255, 0.5) !important;
}

/* List Group Dark Mode */
body.dark-theme #searchModal .list-group-item {
    background-color: transparent;
    color: #f8f9fa;
    border-bottom-color: rgba(255, 255, 255, 0.1) !important;
}
body.dark-theme #searchModal .list-group-item:hover {
    background-color: rgba(255, 255, 255, 0.05);
}
body.dark-theme #searchModal .text-muted {
    color: rgba(255, 255, 255, 0.5) !important;
}
body.dark-theme #searchModal .text-secondary {
    color: rgba(255, 255, 255, 0.6) !important;
}

/* Highlight color in dark mode */
body.dark-theme .bg-warning-subtle {
    background-color: rgba(255, 193, 7, 0.2) !important;
    color: #ffc107 !important;
}

/* Mobile Dark Mode Fixes */
body.dark-theme .mobile-header-bar {
    background-color: #1e1e1e !important;
    border-bottom-color: #333 !important;
}
body.dark-theme #sidebarToggle {
    background-color: #2a2a2a;
    border-color: #444;
    color: #e0e0e0;
}
body.dark-theme .search-bar-container {
    background-color: #1e1e1e !important;
    border-color: #333 !important;
}
body.dark-theme .search-bar-container .btn-light {
    color: #e0e0e0;
}
body.dark-theme .search-bar-container .text-muted {
    color: #aaa !important;
}
body.dark-theme .search-bar-container input {
    color: #e0e0e0;
}
body.dark-theme .search-bar-container input::placeholder {
    color: #aaa;
}

/* Location Modal Dark Mode */
body.dark-theme #locationModal .modal-content {
    background-color: #1e1e1e;
    color: #e0e0e0;
}
body.dark-theme #locationModal .modal-header .modal-title {
    color: #e0e0e0 !important;
}
body.dark-theme #locationModal .btn {
    background-color: #2a2a2a;
    color: #e0e0e0;
    border-color: #444;
}
body.dark-theme #locationModal .btn:hover {
    background-color: #3a3a3a;
}
body.dark-theme #locationModal .btn.btn-success {
    background-color: #198754;
    border-color: #198754;
    color: #ffffff;
}
body.dark-theme #locationModal .input-group-text {
    background-color: #2a2a2a !important;
    border-color: #444 !important;
    color: #e0e0e0 !important;
}
body.dark-theme #locationModal .form-control {
    background-color: #2a2a2a !important;
    border-color: #444 !important;
    color: #e0e0e0 !important;
}
body.dark-theme #locationModal .form-control::placeholder {
    color: #adb5bd !important;
}

body.dark-theme #clinicDetailsModal .modal-content {
    background-color: #1e1e1e;
    color: #e0e0e0;
}
body.dark-theme #clinicDetailsModal .text-dark {
    color: #e0e0e0 !important;
}
body.dark-theme #clinicDetailsModal .text-muted {
    color: #adb5bd !important;
}
body.dark-theme #clinicDetailsModal .bg-light {
    background-color: #2a2a2a !important;
    border-color: #444 !important;
}

body.dark-theme .card .text-dark {
    color: #e0e0e0 !important;
}
body.dark-theme .bg-success-subtle {
    background-color: rgba(25, 135, 84, 0.2) !important;
    color: #75b798 !important;
    border-color: rgba(25, 135, 84, 0.3) !important;
}
body.dark-theme .mobile-header-bar img.border {
    border-color: #444 !important;
}

/* Collapsed Sidebar Styles */
@media (min-width: 768px) {
    #sidebarMenu {
        transition: width 0.3s ease, padding 0.3s ease;
        white-space: nowrap;
        overflow: hidden;
    }
    #sidebarMenu.collapsed {
        width: 80px !important;
        padding-left: 10px !important;
        padding-right: 10px !important;
    }
    
    #sidebarMenu.collapsed .brand-text,
    #sidebarMenu.collapsed .profile-info,
    #sidebarMenu.collapsed .nav-link span,
    #sidebarMenu.collapsed .theme-text,
    #sidebarMenu.collapsed .theme-btn-text {
        display: none !important; 
    }
    
    /* Center icons */
    #sidebarMenu.collapsed .nav-link,
    #sidebarMenu.collapsed .theme-label-container {
        justify-content: center !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    #sidebarMenu.collapsed .nav-link i,
    #sidebarMenu.collapsed .theme-label-container i {
        margin-right: 0 !important;
        font-size: 1.5rem;
    }
    
    /* Header Adjustment */
    #sidebarMenu.collapsed .sidebar-header {
        flex-direction: column;
        gap: 1rem;
        padding-left: 0 !important;
        padding-right: 0 !important;
        margin-bottom: 1rem !important;
    }
    #sidebarMenu.collapsed .brand-container img {
        margin-right: 0 !important;
    }

    /* Adjust Profile Image */
    #sidebarMenu.collapsed .profile-section img {
        margin-right: 0 !important;
        width: 40px !important;
        height: 40px !important;
    }
    #sidebarMenu.collapsed .profile-section .card-body {
        justify-content: center !important;
        padding: 0 !important;
    }
    #sidebarMenu.collapsed .profile-section {
        padding: 10px !important;
    }
    
    /* Adjust Theme Buttons */
    #sidebarMenu.collapsed .theme-buttons-container {
        flex-direction: column;
        padding: 0 !important;
        gap: 0.5rem !important;
    }
    #sidebarMenu.collapsed .theme-buttons-container button {
        width: 100% !important;
        justify-content: center !important;
        padding: 6px !important;
    }
    #sidebarMenu.collapsed .theme-buttons-container i {
        margin-right: 0 !important;
        font-size: 1.2rem;
    }

    /* Main Content Adjustment */
    #mainContent {
        transition: margin-left 0.3s ease, width 0.3s ease;
    }
    #mainContent.expanded {
        width: calc(100% - 80px) !important;
        margin-left: 80px !important;
        flex: 0 0 auto !important;
        max-width: none !important; 
    }
    
    /* Toggle Button Rotate */
    .transition-transform {
        transition: transform 0.3s ease;
    }
    .transition-all {
        transition: all 0.3s ease;
    }
    #sidebarMenu.collapsed #desktopSidebarToggle i {
        transform: rotate(180deg);
    }
}

/* Payment Options Styling */
.payment-options-container {
    background-color: var(--bs-primary-bg-subtle);
    border-color: var(--bs-primary-border-subtle) !important;
}
.payment-options-label {
    color: var(--bs-primary);
}

.payment-option-card {
    background-color: var(--bs-body-bg);
    border-color: var(--bs-border-color) !important;
    cursor: pointer;
    transition: all 0.2s ease;
}
.payment-option-card:hover {
    border-color: var(--bs-primary) !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
}
.btn-check:checked + .payment-option-card {
    border-color: var(--bs-primary) !important;
    background-color: rgba(var(--bs-primary-rgb), 0.05);
    box-shadow: 0 0 0 1px var(--bs-primary);
}

.payment-icon-wrapper {
    width: 48px;
    height: 48px;
    background-color: var(--bs-gray-100);
    color: var(--bs-gray-600);
    transition: all 0.2s;
}
.btn-check:checked + .payment-option-card .payment-icon-wrapper {
    background-color: var(--bs-primary);
    color: white;
}

.payment-option-title {
    color: var(--bs-body-color);
}
.payment-option-desc {
    color: var(--bs-secondary-color);
}

.payment-badge-half {
    background-color: var(--bs-primary);
}
.payment-badge-full {
    background-color: var(--bs-success);
}

/* Dark Theme Overrides for Payment Options */
body.dark-theme .payment-options-container {
    background-color: rgba(13, 110, 253, 0.15); /* Primary subtle dark */
    border-color: rgba(13, 110, 253, 0.3) !important;
}
body.dark-theme .payment-options-label {
    color: #6ea8fe; /* primary-light */
}
body.dark-theme .payment-option-card {
    background-color: #2b3035; /* Dark card bg */
    border-color: #495057 !important;
}
body.dark-theme .payment-option-card:hover {
    background-color: #343a40;
    border-color: #6ea8fe !important;
}
body.dark-theme .btn-check:checked + .payment-option-card {
    background-color: rgba(13, 110, 253, 0.2);
    border-color: #6ea8fe !important;
    box-shadow: 0 0 0 1px #6ea8fe;
}
body.dark-theme .payment-icon-wrapper {
    background-color: #343a40;
    color: #adb5bd;
}
body.dark-theme .btn-check:checked + .payment-option-card .payment-icon-wrapper {
    background-color: #0d6efd;
    color: white;
}
body.dark-theme .payment-option-title {
    color: #e9ecef;
}
body.dark-theme .payment-option-desc {
    color: #adb5bd;
}
</style>


@endsection

@section('scripts')
@parent
<script>
window.openSearchModal = function() {
    const modalEl = document.getElementById('searchModal');
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
        modalEl.addEventListener('shown.bs.modal', function () {
            const input = document.getElementById('modalSearchInput');
            if(input) input.focus();
        }, {once:true});
    }
};

window.searchServices = function(query) {
    const clinicsDataEl = document.getElementById('clinicsData');
    const clinicsData = clinicsDataEl ? JSON.parse(clinicsDataEl.dataset.clinics || '[]') : [];
    const modalResults = document.getElementById('modalSearchResults');
    
    if (!modalResults) return;

    if (!query || query.trim() === '') {
        modalResults.innerHTML = `
            <div class="text-center text-muted py-5">
                <i class="bi bi-search fs-1 mb-3 d-block opacity-25"></i>
                <p>Type to search for services, clinics, or animals...</p>
            </div>
        `;
        return;
    }

    const lowerQuery = query.toLowerCase();
    // 2. Search Services
    let resultsHTML = '';
    let hasResults = false;
    
    // Helper to highlight text
    const highlightMatch = (text, query) => {
        if (!query) return text;
        const safeQuery = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); 
        const regex = new RegExp(`(${safeQuery})`, 'gi');
        return text.replace(regex, '<span class="bg-warning-subtle px-1 rounded fw-bold">$1</span>');
    };

    clinicsData.forEach(clinic => {
        if(clinic.services && Array.isArray(clinic.services)) {
            clinic.services.forEach(service => {
                const sName = (service.name || service.service_name || '').toLowerCase();
                const cName = (clinic.clinic_name || '').toLowerCase();
                const animals = (service.animals || []).map(a => (a || '').toString());
                const animalsStr = animals.join(' ').toLowerCase();
                const searchableText = `${sName} ${cName} ${animalsStr}`;
                
                const queryTokens = lowerQuery.split(/\s+/).filter(t => t.length > 0);
                const isMatch = queryTokens.every(token => searchableText.includes(token));

                if(isMatch) {
                    hasResults = true;
                    // Format price
                    const formattedPrice = service.price 
                        ? '₱' + parseFloat(service.price).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) 
                        : 'Ask for Price';

                    resultsHTML += `
                        <button type="button" class="list-group-item list-group-item-action border-0 border-bottom p-3" onclick="openBookingModal(${clinic.id}, ${service.id})" style="transition: background-color 0.2s;">
                             <div class="d-flex align-items-center">
                                <img src="${clinic.image_url || '/images/default_clinic.png'}" 
                                     alt="${clinic.clinic_name}" 
                                     class="rounded-3 object-fit-cover me-3 shadow-sm" 
                                     style="width: 55px; height: 55px; min-width: 55px;">
                                
                                <div class="flex-grow-1 overflow-hidden text-start">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h6 class="mb-0 fw-bold text-truncate pe-2" style="font-size: 1rem;">
                                            ${highlightMatch(service.name || service.service_name, query)}
                                        </h6>
                                        <span class="badge bg-success-subtle text-success border border-success px-2 py-1 rounded-pill flex-shrink-0">
                                            ${formattedPrice}
                                        </span>
                                    </div>
                                    
                                    <div class="d-flex align-items-center text-muted small mb-1 text-truncate">
                                        <i class="bi bi-hospital-fill me-1 text-danger"></i>
                                        <span class="text-truncate">${clinic.clinic_name}</span>
                                    </div>

                                    ${animals.length > 0 ? `
                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                        ${animals.map(a => `<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle" style="font-size: 0.65rem; font-weight: 500;">${a}</span>`).join('')}
                                    </div>` : ''}
                                </div>
                                
                                <div class="ms-3 text-secondary">
                                     <i class="bi bi-chevron-right"></i>
                                </div>
                            </div>
                        </button>
                    `;
                }
            });
        }
    });

    if(hasResults) {
        modalResults.innerHTML = resultsHTML;
    } else if (query.length > 0 && !document.querySelector('input[name="location"]')?.value.trim()) {
        modalResults.innerHTML = '<div class="p-4 text-center text-warning bg-light rounded m-3"><i class="bi bi-geo-alt-fill me-2 fs-5"></i><br>Please enter a city in the dashboard filter to search services.</div>';
    } else {
        modalResults.innerHTML = '<div class="p-5 text-center text-muted"><i class="bi bi-emoji-frown fs-1 mb-3 d-block"></i>No services found matching your search.</div>';
    }
}

window.togglePaymentOptions = function() {
    const payOnline = document.getElementById('payOnline');
    const optionsDiv = document.getElementById('onlinePaymentOptions');
    const receiptInput = document.getElementById('receiptInput');
    if (payOnline && optionsDiv) {
        optionsDiv.style.display = payOnline.checked ? 'block' : 'none';
    }
    if (receiptInput) {
        receiptInput.required = !!(payOnline && payOnline.checked);
        if (!receiptInput.required) {
            receiptInput.value = '';
        }
    }
}

window.openBookingModal = function(clinicId, serviceId) {
    console.log('Opening booking modal for Clinic:', clinicId, 'Service:', serviceId);
    const modalClinicId = document.getElementById('modalClinicId');
    const modalServiceId = document.getElementById('modalServiceId');
    
    if(modalClinicId) modalClinicId.value = clinicId;
    if(modalServiceId) modalServiceId.value = serviceId;
    
    const clinicsDataEl = document.getElementById('clinicsData');
    let clinicsData = [];
    try {
        clinicsData = clinicsDataEl ? JSON.parse(clinicsDataEl.dataset.clinics || '[]') : [];
    } catch(e) {
        console.error('Error parsing clinics data:', e);
    }

    let targetService = null;
    let targetClinic = null;
    clinicsData.forEach(c => {
        if(c.id == clinicId && c.services) {
            targetClinic = c;
            const s = c.services.find(srv => srv.id == serviceId);
            if(s) targetService = s;
        }
    });

    if (!targetService) {
        console.error('Target service not found for ID:', serviceId);
        alert('Service details could not be loaded. Please try again.');
        return;
    }
    
    const bookModalEl = document.getElementById('bookModal');
    if(bookModalEl) {
        // Close search modal if open
        const searchModalEl = document.getElementById('searchModal');
        if (searchModalEl) {
            const searchModal = bootstrap.Modal.getInstance(searchModalEl);
            if (searchModal) searchModal.hide();
        }

        const petCheckboxes = bookModalEl.querySelectorAll('.pet-checkbox');
        petCheckboxes.forEach(chk => {
            const wrapper = chk.closest('.form-check');
            if(wrapper) wrapper.style.display = '';
            chk.disabled = false;
        });
        
        if(targetService && targetService.animals && targetService.animals.length > 0) {
            const allowedAnimals = targetService.animals.map(a => a.toLowerCase().trim());
             petCheckboxes.forEach(chk => {
                const petSpecies = (chk.dataset.species || '').trim().toLowerCase();
                const match = allowedAnimals.some(allowed => 
                    petSpecies.includes(allowed) || allowed.includes(petSpecies)
                );
                
                const wrapper = chk.closest('.form-check');
                if (match) {
                    if(wrapper) wrapper.style.display = '';
                    chk.disabled = false;
                } else {
                    if(wrapper) wrapper.style.display = 'none';
                    chk.disabled = true;
                    chk.checked = false;
                }
            });
        }

        const serviceLocationSection = document.getElementById('serviceLocationSection');
        const homeServiceFields = document.getElementById('homeServiceFields');
        const serviceLocationClinic = document.getElementById('serviceLocationClinic');
        const serviceLocationHome = document.getElementById('serviceLocationHome');
        const serviceAddress = document.getElementById('serviceAddress');
        const serviceContact = document.getElementById('serviceContact');
        const homeSlotSelect = document.getElementById('homeSlotSelect');
        const homeSlotEmpty = document.getElementById('homeSlotEmpty');
        const bookingAppointmentDate = document.getElementById('bookingAppointmentDate');

        const normalizeLocationType = (val) => (val || '').toString().trim().toLowerCase();
        const locationType = normalizeLocationType(targetService.location_type);
        const supportsHome = locationType === 'home' || locationType === 'both';
        const supportsClinic = locationType === 'clinic' || locationType === 'both' || locationType === '';

        const updateHomeFieldsVisibility = () => {
            const isHome = !!(serviceLocationHome && serviceLocationHome.checked);
            if (homeServiceFields) homeServiceFields.classList.toggle('d-none', !isHome);
            if (serviceAddress) serviceAddress.required = isHome;
            if (serviceContact) serviceContact.required = isHome;
            if (homeSlotSelect) homeSlotSelect.required = isHome && homeSlotSelect.options.length > 1;
        };

        if (serviceLocationSection) {
            if (supportsHome && supportsClinic) {
                serviceLocationSection.classList.remove('d-none');
                if (serviceLocationClinic) serviceLocationClinic.checked = true;
                if (serviceLocationHome) serviceLocationHome.checked = false;
            } else {
                serviceLocationSection.classList.add('d-none');
                if (serviceLocationClinic) serviceLocationClinic.checked = supportsClinic;
                if (serviceLocationHome) serviceLocationHome.checked = supportsHome && !supportsClinic;
            }
        }

        if (homeSlotSelect) {
            homeSlotSelect.innerHTML = '<option value="">Select a slot</option>';
            const slots = Array.isArray(targetService.slots) ? targetService.slots : [];
            const formatSlotLabel = (slot) => {
                const raw = (slot || '').toString().trim();
                if (!raw) return raw;

                const normalized = raw.includes('T') ? raw : raw.replace(' ', 'T');
                const date = new Date(normalized);
                if (Number.isNaN(date.getTime())) return raw;

                const weekday = new Intl.DateTimeFormat('en-US', { weekday: 'long' }).format(date).toUpperCase();
                const time = new Intl.DateTimeFormat('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }).format(date);
                return `${weekday} ${time}`;
            };
            slots.forEach(slot => {
                const opt = document.createElement('option');
                opt.value = slot;
                opt.textContent = formatSlotLabel(slot);
                homeSlotSelect.appendChild(opt);
            });
            const hasSlots = homeSlotSelect.options.length > 1;
            if (homeSlotEmpty) homeSlotEmpty.classList.toggle('d-none', hasSlots);
            homeSlotSelect.disabled = !hasSlots;
            homeSlotSelect.value = '';
        }

        const onServiceLocationChange = () => {
            updateHomeFieldsVisibility();
            const payClinicLabel = document.getElementById('payClinicLabel');
            if (payClinicLabel) {
                payClinicLabel.textContent = (serviceLocationHome && serviceLocationHome.checked)
                    ? 'Pay after Service'
                    : 'Pay at Clinic';
            }
            if (serviceLocationHome && serviceLocationHome.checked) {
                if (bookingAppointmentDate && homeSlotSelect && homeSlotSelect.value) {
                    bookingAppointmentDate.value = homeSlotSelect.value;
                }
            }
        };

        if (serviceLocationClinic) serviceLocationClinic.onchange = onServiceLocationChange;
        if (serviceLocationHome) serviceLocationHome.onchange = onServiceLocationChange;
        if (homeSlotSelect) {
            homeSlotSelect.onchange = () => {
                if (bookingAppointmentDate && homeSlotSelect.value) {
                    bookingAppointmentDate.value = homeSlotSelect.value;
                }
            };
        }
        onServiceLocationChange();

        const clinicQrImage = document.getElementById('clinicQrImage');
        const clinicQrMissing = document.getElementById('clinicQrMissing');
        const qrBase = "{{ asset('storage/clinics/qr_codes') }}";
        if (clinicQrImage && clinicQrMissing) {
            const qrFilename = targetClinic ? targetClinic.qr_code : null;
            if (qrFilename) {
                clinicQrImage.src = qrBase + '/' + qrFilename;
                clinicQrImage.classList.remove('d-none');
                clinicQrMissing.classList.add('d-none');
            } else {
                clinicQrImage.src = '';
                clinicQrImage.classList.add('d-none');
                clinicQrMissing.classList.remove('d-none');
            }
        }
        
        // Price Calculation Logic
        const basePrice = parseFloat(targetService.price || 0);
        console.log('Base Price:', basePrice);

        const updatePrice = () => {
            const checkedPets = Array.from(bookModalEl.querySelectorAll('.pet-checkbox:checked'));
            // Backend Logic: if petCount < 1, defaults to 1.
            const countForCalc = checkedPets.length > 0 ? checkedPets.length : 1;
            
            const totalPrice = basePrice * countForCalc;
            let downpayment = totalPrice * 0.5;
            
            if (downpayment < 50) downpayment = 50;
            if (totalPrice > 0 && downpayment > totalPrice) downpayment = totalPrice;
            
            // Update Badges in Options
            const optionHalfPrice = document.getElementById('optionHalfPrice');
            const optionFullPrice = document.getElementById('optionFullPrice');
            if(optionHalfPrice) optionHalfPrice.textContent = '₱' + downpayment.toLocaleString('en-US', {minimumFractionDigits: 2});
            if(optionFullPrice) optionFullPrice.textContent = '₱' + totalPrice.toLocaleString('en-US', {minimumFractionDigits: 2});

            // Determine what to show in summary
            const payOnline = document.getElementById('payOnline').checked;
            const payFull = document.getElementById('payFull').checked;
            
            let amountToPay = 0;
            let labelText = "Downpayment Required (50%)";

            if (payOnline) {
                if (payFull) {
                    amountToPay = totalPrice;
                    labelText = "Total Payment Required";
                } else {
                    amountToPay = downpayment;
                    labelText = "Downpayment Required (50%)";
                }
            } else {
                // Default to showing downpayment info for clinic payment
                amountToPay = downpayment;
            }

            // Update DOM
            const servicePriceEl = document.getElementById('bookingServicePrice');
            const petCountEl = document.getElementById('bookingPetCount');
            const totalPriceEl = document.getElementById('bookingTotalPrice');
            const downpaymentEl = document.getElementById('bookingDownpayment');
            // Find label span: it is inside the div that is previous sibling of downpaymentEl's parent? 
            // Structure: 
            // <div ...> <div ...> <span>LABEL</span> </div> <span id="bookingDownpayment">...</span> </div>
            // Actually:
            // <div class="d-flex ... bg-white ...">
            //    <div class="d-flex ..."> <i ...></i> <span class="small fw-bold text-uppercase">LABEL</span> </div>
            //    <span id="bookingDownpayment">...</span>
            // </div>
            
            const labelSpan = downpaymentEl ? downpaymentEl.previousElementSibling.querySelector('span') : null;

            if(servicePriceEl) servicePriceEl.textContent = '₱' + basePrice.toLocaleString('en-US', {minimumFractionDigits: 2});
            if(petCountEl) petCountEl.textContent = checkedPets.length;
            if(totalPriceEl) totalPriceEl.textContent = '₱' + totalPrice.toLocaleString('en-US', {minimumFractionDigits: 2});
            if(downpaymentEl) downpaymentEl.textContent = '₱' + amountToPay.toLocaleString('en-US', {minimumFractionDigits: 2});
            if(labelSpan) labelSpan.textContent = labelText;
        };

        petCheckboxes.forEach(chk => {
            chk.onchange = updatePrice;
        });
        
        // Add listeners to payment inputs
        const paymentInputs = bookModalEl.querySelectorAll('input[name="payment_method"], input[name="payment_option"]');
        paymentInputs.forEach(input => {
            input.addEventListener('change', updatePrice);
        });

        // Initial call
        updatePrice();
        togglePaymentOptions(); // Ensure correct initial state
        
        const bookModal = new bootstrap.Modal(bookModalEl);
        bookModal.show();
    }
}

window.showAppointmentDetails = function(data) {
    document.getElementById('apptDetailsClinicName').textContent = data.clinic;
    document.getElementById('apptDetailsService').textContent = data.service;
    document.getElementById('apptDetailsStatus').textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
    
    // Status Badge Color
    const statusBadge = document.getElementById('apptDetailsStatus');
    statusBadge.className = 'badge rounded-pill px-3 py-2 border';
    if(data.status == 'approved') statusBadge.classList.add('bg-success-subtle', 'text-success', 'border-success-subtle');
    else if(data.status == 'pending') statusBadge.classList.add('bg-warning-subtle', 'text-warning', 'border-warning-subtle');
    else if(data.status == 'completed') statusBadge.classList.add('bg-primary-subtle', 'text-primary', 'border-primary-subtle');
    else statusBadge.classList.add('bg-danger-subtle', 'text-danger', 'border-danger-subtle');

    document.getElementById('apptDetailsDate').textContent = data.date;
    document.getElementById('apptDetailsTime').textContent = data.time || 'N/A';
    
    const price = parseFloat(data.price || 0);
    const petCount = parseInt(data.pet_count || 1);
    const totalPrice = price * petCount;

    let downpayment = totalPrice * 0.5;
    if (downpayment < 50) downpayment = 50;
    if (totalPrice > 0 && downpayment > totalPrice) downpayment = totalPrice;
    
    document.getElementById('apptDetailsPrice').innerHTML = '₱' + totalPrice.toLocaleString('en-US', {minimumFractionDigits: 2});
    if(petCount > 1) {
         document.getElementById('apptDetailsPrice').innerHTML += ` <small class="text-muted ms-1" style="font-size: 0.8rem;">(${petCount} pets)</small>`;
    }
    document.getElementById('apptDetailsDownpayment').textContent = '₱' + downpayment.toLocaleString('en-US', {minimumFractionDigits: 2});

    // Pets Logic
    const petsContainer = document.getElementById('apptDetailsPets');
    const petsWrapper = document.getElementById('apptDetailsPetsContainer');
    
    if(petsContainer && petsWrapper) {
        petsContainer.innerHTML = '';
        if(data.pets && Array.isArray(data.pets) && data.pets.length > 0) {
            petsWrapper.style.display = 'block';
            data.pets.forEach(pet => {
                const petEl = document.createElement('div');
                petEl.className = 'd-flex align-items-center bg-light rounded-pill pe-3 ps-1 py-1 border';
                petEl.innerHTML = `
                    <img src="${pet.image}" class="rounded-circle me-2 object-fit-cover" style="width: 30px; height: 30px;">
                    <div>
                        <div class="small fw-bold text-dark lh-1">${pet.name}</div>
                        <div class="text-muted" style="font-size: 0.7rem;">${pet.breed}</div>
                    </div>
                `;
                petsContainer.appendChild(petEl);
            });
        } else {
            petsWrapper.style.display = 'none';
        }
    }

    const payBtn = document.getElementById('apptDetailsPayBtn');
    const paymentStatusContainer = document.getElementById('apptDetailsPaymentStatusContainer');
    
    // Reset
    payBtn.classList.add('d-none');
    paymentStatusContainer.innerHTML = '';

    if(data.payment_status === 'unpaid' && (data.status === 'approved' || data.payment_method === 'online')) {
        payBtn.classList.remove('d-none');
        payBtn.href = data.checkoutUrl;
    } else if(data.payment_status === 'downpayment_paid') {
        paymentStatusContainer.innerHTML = '<span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2"><i class="bi bi-shield-fill-check me-1"></i> Verified</span>';
    } else {
         if(data.payment_status === 'unpaid') {
             paymentStatusContainer.innerHTML = '<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-2">Unpaid</span>';
         }
    }

    const modal = new bootstrap.Modal(document.getElementById('appointmentDetailsModal'));
    modal.show();
}
</script>
@parent
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebarMenu');
    const toggleBtn = document.getElementById('sidebarToggle');
    const closeBtn = document.getElementById('sidebarClose');

    function openSidebar() {
        sidebar.classList.add('show');
        sidebar.setAttribute('aria-hidden', 'false');
        if (window.innerWidth < 768) document.body.style.overflow = 'hidden';
        sidebar.focus?.();
    }

    function closeSidebar() {
        sidebar.classList.remove('show');
        sidebar.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        if (toggleBtn) toggleBtn.focus();
    }

    if (toggleBtn)
        toggleBtn.addEventListener('click', e => {
            e.stopPropagation();
            sidebar.classList.contains('show') ? closeSidebar() : openSidebar();
        });

    if (closeBtn)
        closeBtn.addEventListener('click', e => {
            e.stopPropagation();
            closeSidebar();
        });

    sidebar?.querySelectorAll('a.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 768) closeSidebar();
        });
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && sidebar.classList.contains('show')) closeSidebar();
    });

    // -------------------------------
    // CLINIC FILTER + MODALS
    // -------------------------------
    const clinicsDataEl = document.getElementById('clinicsData');
    const clinicsData = clinicsDataEl ? JSON.parse(clinicsDataEl.dataset.clinics || '[]') : [];
    const animalButtons = document.querySelectorAll('.animal-btn');
    const clinicsModalEl = document.getElementById('clinicsModal');
    const clinicsModal = clinicsModalEl ? new bootstrap.Modal(clinicsModalEl) : null;
    const clinicsModalBody = document.getElementById('clinicsModalBody');
    const clinicDetailsModalEl = document.getElementById('clinicDetailsModal');
    const clinicDetailsModal = clinicDetailsModalEl ? new bootstrap.Modal(clinicDetailsModalEl) : null;
    const clinicDetailsModalTitle = document.getElementById('clinicDetailsModalTitle');
    const clinicDetailsModalBody = document.getElementById('clinicDetailsModalBody');
    const bookModalEl = document.getElementById('bookModal');
    const bookModal = bookModalEl ? new bootstrap.Modal(bookModalEl) : null;
    const modalClinicId = document.getElementById('modalClinicId');
    let reopenClinicsModalAfterDetails = false;

    const openClinicDetailsModal = (clinic) => {
        if (!clinicDetailsModal || !clinicDetailsModalTitle || !clinicDetailsModalBody) return;

        reopenClinicsModalAfterDetails = !!clinicsModalEl && clinicsModalEl.classList.contains('show');
        if (clinicsModal) clinicsModal.hide();

        clinicDetailsModalTitle.textContent = clinic.clinic_name || 'Clinic';

        const imageUrl = clinic.image_url || '{{ asset('images/default_clinic.png') }}';
        const specializations = Array.isArray(clinic.specializations) ? clinic.specializations.filter(Boolean) : [];
        const services = Array.isArray(clinic.services) ? clinic.services : [];
        const gallery = Array.isArray(clinic.gallery) ? clinic.gallery : [];
        const reviews = Array.isArray(clinic.reviews) ? clinic.reviews : [];

        clinicDetailsModalBody.innerHTML = `
            <div class="d-flex gap-3 align-items-start mb-3">
                <img src="${imageUrl}" alt="${clinic.clinic_name || 'Clinic'}" class="rounded-4 shadow-sm object-fit-cover border" style="width:110px;height:110px;">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <div class="fw-bold text-dark" style="font-size: 1.25rem;">${clinic.clinic_name || ''}</div>
                        <span class="badge ${clinic.is_24_hours ? 'bg-primary-subtle text-primary border border-primary' : (clinic.is_active ? 'bg-success-subtle text-success border border-success' : 'bg-danger-subtle text-danger border border-danger')} px-3 py-2 rounded-pill">
                            ${clinic.is_24_hours ? '<i class="bi bi-clock-history me-1"></i>24 Hours' : (clinic.is_active ? '<i class="bi bi-clock-fill me-1"></i>Open' : '<i class="bi bi-x-circle-fill me-1"></i>Closed')}
                        </span>
                    </div>
                    <div class="text-muted small mt-1"><i class="bi bi-geo-alt-fill me-1 text-danger"></i>${clinic.address || ''}</div>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        <span class="badge bg-warning-subtle text-warning border border-warning px-3 py-2 rounded-pill"><i class="bi bi-star-fill me-1"></i>${(clinic.avg_rating ?? 0).toFixed ? clinic.avg_rating.toFixed(1) : clinic.avg_rating}</span>
                        ${clinic.phone ? `<span class="badge bg-light text-muted border px-3 py-2 rounded-pill"><i class="bi bi-telephone-fill me-1"></i>${clinic.phone}</span>` : ''}
                        ${clinic.email ? `<span class="badge bg-light text-muted border px-3 py-2 rounded-pill"><i class="bi bi-envelope-fill me-1"></i>${clinic.email}</span>` : ''}
                    </div>
                    ${!clinic.is_24_hours && clinic.formatted_opening_time ? `<div class="small text-muted mt-2"><i class="bi bi-clock me-1"></i>${clinic.formatted_opening_time} - ${clinic.formatted_closing_time || ''}</div>` : ''}
                </div>
            </div>

            ${clinic.description ? `<div class="mb-3"><div class="fw-bold text-dark mb-1">About</div><div class="text-muted">${clinic.description}</div></div>` : ''}

            ${specializations.length ? `
                <div class="mb-3">
                    <div class="fw-bold text-dark mb-2">Specializations</div>
                    <div class="d-flex flex-wrap gap-2">
                        ${specializations.map(s => `<span class="badge bg-secondary-subtle text-secondary border border-secondary px-3 py-2 rounded-pill">${String(s).trim()}</span>`).join('')}
                    </div>
                </div>
            ` : ''}

            ${gallery.length ? `
                <div class="mb-3">
                    <div class="fw-bold text-dark mb-2">Gallery</div>
                    <div class="d-flex flex-wrap gap-2">
                        ${gallery.map(url => `<a href="${url}" target="_blank" class="d-inline-block"><img src="${url}" class="rounded-3 border shadow-sm object-fit-cover" style="width:90px;height:90px;"></a>`).join('')}
                    </div>
                </div>
            ` : ''}

            <div class="mb-3">
                <div class="fw-bold text-dark mb-2">Services</div>
                ${services.length ? `
                    <div class="d-grid gap-2">
                        ${services.map(s => `
                            <button type="button" class="btn btn-outline-success rounded-3 py-3 text-start" data-clinic-id="${clinic.id}" data-service-id="${s.id}">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <div class="fw-bold">${s.name ?? s.service_name ?? 'Service'}</div>
                                        ${s.description ? `<div class="small text-muted mt-1">${s.description}</div>` : ''}
                                    </div>
                                    <div class="fw-bold">${s.price ? `₱${parseFloat(s.price).toFixed(2)}` : ''}</div>
                                </div>
                            </button>
                        `).join('')}
                    </div>
                ` : `<div class="alert alert-secondary small mb-0">No services listed for this clinic.</div>`}
            </div>

            ${reviews.length ? `
                <div class="mb-0">
                    <div class="fw-bold text-dark mb-2">Latest Reviews</div>
                    <div class="d-flex flex-column gap-2">
                        ${reviews.slice(0, 3).map(r => `
                            <div class="border rounded-3 p-3 bg-white">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="fw-bold text-dark">${r.user_name || 'Anonymous'}</div>
                                    <div class="small text-warning"><i class="bi bi-star-fill me-1"></i>${r.rating ?? ''}</div>
                                </div>
                                ${r.review ? `<div class="text-muted small">${r.review}</div>` : ''}
                                ${r.created_at ? `<div class="text-muted small mt-2">${r.created_at}</div>` : ''}
                            </div>
                        `).join('')}
                    </div>
                </div>
            ` : ''}
        `;

        clinicDetailsModalBody.querySelectorAll('[data-clinic-id][data-service-id]').forEach(btn => {
            btn.addEventListener('click', () => {
                clinicDetailsModal.hide();
                window.openBookingModal(btn.dataset.clinicId, btn.dataset.serviceId);
            });
        });

        clinicDetailsModal.show();
    };

    if (clinicDetailsModalEl) {
        clinicDetailsModalEl.addEventListener('hidden.bs.modal', () => {
            if (reopenClinicsModalAfterDetails && clinicsModal) {
                clinicsModal.show();
            }
            reopenClinicsModalAfterDetails = false;
        });
    }

    animalButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            animalButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const animalName = (btn.dataset.animal || '').trim().toLowerCase();

            const locationInput = document.querySelector('input[name="location"]');
            const selectedLocation = locationInput ? locationInput.value.trim() : '';
            const locationTerms = [];
            if (selectedLocation) {
                locationTerms.push(selectedLocation);
                if (selectedLocation.toLowerCase().includes('city')) {
                    locationTerms.push(selectedLocation.replace(/city/i, '').trim());
                }
                if (selectedLocation.trim().toLowerCase() === 'cebu city') {
                    locationTerms.push('Cebu');
                }
            }
            const normalizedLocationTerms = Array.from(new Set(locationTerms.filter(Boolean).map(t => t.toLowerCase())));

            const filtered = (clinicsData || []).filter(c => {
                const matchesAnimal = Array.isArray(c.specializations) &&
                    c.specializations.some(a => (a || '').trim().toLowerCase() === animalName);
                
                const address = (c.address || '').toLowerCase();
                const matchesLocation = !normalizedLocationTerms.length ||
                    normalizedLocationTerms.some(term => address.includes(term));
                
                return matchesAnimal && matchesLocation;
            });


            if (!clinicsModal || !clinicsModalBody) return;
            clinicsModalBody.innerHTML = '';

            if (!selectedLocation) {
                 clinicsModalBody.innerHTML = `
                    <div class="text-center py-5">
                        <div class="bg-warning-subtle text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-geo-alt-fill fs-1"></i>
                        </div>
                        <h5 class="text-muted fw-bold">Please enter your city.</h5>
                        <p class="text-muted small mb-0">Enter your city above to find clinics near you.</p>
                    </div>
                `;
            } else if (!filtered.length) {
                clinicsModalBody.innerHTML = `
                    <div class="text-center py-5">
                        <img src="{{ asset('images/notfoundcat.gif') }}" class="mb-3 invert-on-dark" style="width: 150px; height: auto;">
                        <h5 class="text-muted fw-bold">No clinics available for <span class="text-capitalize">${animalName}</span>.</h5>
                        <p class="text-muted small">Try selecting a different category.</p>
                    </div>
                `;
            } else {
                filtered.forEach(clinic => {
                    const card = document.createElement('div');
                    card.className = 'card mb-4 border-0 shadow-sm rounded-4 overflow-hidden clinic-card-toggle';
                     card.style.cursor = 'pointer'; // Make the whole card look clickable
                    
                    // Availability Logic
                    let statusBadge = '';
                    let bookingDisabled = false;
                    
                    if (clinic.is_active) {
                        statusBadge = '<span class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill"><i class="bi bi-clock-fill me-1"></i>Open Now</span>';
                    } else {
                        statusBadge = '<span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 rounded-pill"><i class="bi bi-x-circle-fill me-1"></i>Closed</span>';
                        bookingDisabled = true;
                    }

                    if (clinic.is_24_hours) {
                         statusBadge = '<span class="badge bg-primary-subtle text-primary border border-primary px-3 py-2 rounded-pill"><i class="bi bi-clock-history me-1"></i>24 Hours</span>';
                         bookingDisabled = false;
                    }

                    // Rating Stars
                    const rating = Math.round(clinic.avg_rating || 0);

                    card.innerHTML = `
                        <div class="card-body p-4">
                            <button type="button" class="btn p-0 text-start w-100 clinic-details-trigger border-0 bg-transparent">
                                <div class="d-flex align-items-start mb-3">
                                    <img src="${clinic.image_url ?? '/images/default_clinic.png'}"
                                         alt="${clinic.clinic_name}"
                                         class="me-3 rounded-4 shadow-sm object-fit-cover"
                                         style="width:90px;height:90px;">
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5 class="fw-bold mb-1 text-dark" style="font-size: 1.25rem;">${clinic.clinic_name}</h5>
                                                <p class="text-muted small mb-2"><i class="bi bi-geo-alt-fill me-1 text-danger"></i>${clinic.address}</p>
                                            </div>
                                            <div class="text-end">
                                                <div class="mb-2">${statusBadge}</div>
                                                <div class="text-warning small" title="${rating} Stars">
                                                    <i class="bi bi-star-fill"></i> ${rating}.0
                                                </div>
                                            </div>
                                        </div>

                                        ${!clinic.is_24_hours && clinic.formatted_opening_time ? 
                                          `<div class="d-inline-block bg-light px-2 py-1 rounded small text-secondary border mt-1">
                                             <i class="bi bi-clock me-1"></i> ${clinic.formatted_opening_time} - ${clinic.formatted_closing_time}
                                           </div>` : ''}
                                    </div>
                                </div>
                            </button>
                            
                            <hr class="my-3 text-muted opacity-25">

                             <div class="text-center text-muted small fw-bold">
                                 <i class="bi bi-chevron-down me-1"></i> Tap to View Services
                             </div>

                             <div class="services-container d-none mt-3">
                                 <p class="small text-muted fw-bold text-uppercase mb-2 ms-1">Select a Service:</p>
                                 ${
                                    clinic.services && Array.isArray(clinic.services) && clinic.services.length
                                    ? `
                                        <div class="d-grid gap-2 mb-3">
                                            ${clinic.services.map(s => `
                                                <div class="service-item p-3 rounded-3 mb-1" data-id="${s.id}">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <div class="fw-bold text-dark">${s.name ?? s.service_name ?? 'Unnamed Service'}</div>
                                                            ${s.description ? `<div class="small text-muted mt-1">${s.description}</div>` : ''}
                                                            ${s.animals && s.animals.length ? `
                                                                <div class="mt-2">
                                                                    ${s.animals.map(animal => `<span class="badge bg-secondary-subtle text-secondary border border-secondary me-1" style="font-size: 0.75rem;">${animal.trim()}</span>`).join('')}
                                                                </div>
                                                            ` : ''}
                                                        </div>
                                                        <div class="fw-bold text-success fs-5">
                                                            ${s.price ? `₱${parseFloat(s.price).toFixed(2)}` : ''}
                                                        </div>
                                                    </div>
                                                </div>
                                            `).join('')}
                                        </div>
                                        ${bookingDisabled 
                                            ? '<div class="alert alert-danger border-0 bg-danger-subtle text-danger small text-center fw-bold"><i class="bi bi-exclamation-circle me-2"></i>This clinic is currently closed.</div>' 
                                            : `<button type="button" class="btn btn-success w-100 py-2 rounded-3 fw-bold shadow-sm book-btn d-none" data-id="${clinic.id}"><i class="bi bi-calendar-check me-2"></i>Book Selected Service</button>`
                                        }
                                    `
                                    : '<div class="alert alert-secondary small text-center">No services listed for this clinic.</div>'
                                }
                            </div>
                        </div>
                    `;
                    clinicsModalBody.appendChild(card);
                    const detailsTrigger = card.querySelector('.clinic-details-trigger');
                    if (detailsTrigger) {
                        detailsTrigger.addEventListener('click', (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            openClinicDetailsModal(clinic);
                        });
                    }

                    // Toggle show/hide services by clicking the card itself
                    card.addEventListener('click', function(e) {
                        // Prevent toggling if clicking on a button or service item inside the card
                        if (e.target.closest('.book-btn') || e.target.closest('.service-item')) {
                            return;
                        }

                        const servicesContainer = card.querySelector('.services-container');
                        servicesContainer.classList.toggle('d-none');
                        
                        // Optional: Rotate chevron or change text if you added a specific indicator element
                         const indicator = card.querySelector('.bi-chevron-down');
                         if(indicator) {
                             if(servicesContainer.classList.contains('d-none')) {
                                 indicator.classList.remove('bi-chevron-up');
                                 indicator.classList.add('bi-chevron-down');
                             } else {
                                 indicator.classList.remove('bi-chevron-down');
                                 indicator.classList.add('bi-chevron-up');
                             }
                         }
                    });

                    // Show "Book" only after selecting a service
                    card.querySelectorAll('.service-item').forEach(service => {
                        service.addEventListener('click', function() {
                            const bookBtn = card.querySelector('.book-btn');
                            if (bookBtn) bookBtn.classList.remove('d-none');
                            card.querySelectorAll('.service-item').forEach(si => si.classList.remove('active'));
                            this.classList.add('active');
                            const serviceIdInput = document.getElementById('modalServiceId');
if (serviceIdInput) {
    serviceIdInput.value = this.dataset.id; // store the selected service ID
}

                        });
                    });

                    card.querySelectorAll('.book-btn').forEach(b => {
                        b.addEventListener('click', (e) => {
                            e.preventDefault();
                            const selectedService = card.querySelector('.service-item.active');
                            if (!selectedService) {
                                alert('Please select a service first.');
                                return; // stop opening the modal
                            }

                            // Use the global function to ensure price calculation and pet filtering logic is applied
                            if (clinicsModal) clinicsModal.hide();
                            window.openBookingModal(b.dataset.id, selectedService.dataset.id);
                        });
                    });


                });
            }

            clinicsModal.show();
        });
    });

    // -------------------------------
    // THEME TOGGLER
    // -------------------------------
    const themeOptions = document.querySelectorAll('.theme-option');
    const savedTheme = localStorage.getItem('theme') || 'light';
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-theme');
        document.documentElement.classList.add('dark-theme');
    }

    themeOptions.forEach(option => {
        option.addEventListener('click', e => {
            e.preventDefault();
            const theme = option.dataset.theme;
            if (theme === 'dark') {
                document.body.classList.add('dark-theme');
                document.documentElement.classList.add('dark-theme');
            } else {
                document.body.classList.remove('dark-theme');
                document.documentElement.classList.remove('dark-theme');
            }
            localStorage.setItem('theme', theme);
            
            // Sync with profile drawer button if it exists
            if (typeof updateThemeGif === 'function') {
                updateThemeGif(theme === 'dark');
            }
        });
    });
// -------------------------------
// PET DETAIL MODAL
// -------------------------------
const petCards = document.querySelectorAll('.pet-card');
const petModalEl = document.getElementById('petDetailModal');
const petModal = petModalEl ? new bootstrap.Modal(petModalEl) : null;
const petModalTitle = document.getElementById('petDetailModalTitle');
const petModalBody = document.getElementById('petDetailModalBody');
const petEditBtn = document.getElementById('petEditBtn');

petCards.forEach(card => {
    card.addEventListener('click', () => {
        const pet = JSON.parse(card.dataset.pet || '{}');
        if (!petModal || !petModalTitle || !petModalBody) return;

        petModalTitle.textContent = pet.name || 'Unnamed Pet';

        const imgSrc = pet.image_url || (pet.image ? '/storage/' + pet.image : '/images/default_pet.jpg');

        petModalBody.innerHTML = `
        <div class="text-center mb-4">
            <div class="position-relative d-inline-block">
                <img src="${imgSrc}" 
                     alt="${pet.name || 'Pet'}" 
                     class="rounded-circle shadow-sm border border-4 border-success object-fit-cover" 
                     style="width: 150px; height: 150px;">
                <span class="position-absolute bottom-0 end-0 bg-white rounded-circle p-2 shadow-sm border border-light">
                     <i class="bi bi-paw-fill text-success fs-5"></i>
                </span>
            </div>
            <h3 class="fw-bold mt-3 mb-1">${pet.name || 'Unnamed Pet'}</h3>
             <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2 fs-6 fw-bold">
                 ${pet.species ?? 'Pet'}
             </span>
         </div>
         
         <div class="row g-3 justify-content-center">
             <div class="col-6">
                 <div class="p-3 bg-light-subtle rounded-4 text-center h-100 border-0 shadow-sm">
                     <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Age</small>
                     <span class="fw-bold fs-4">${pet.age || 'N/A'}</span>
                     <small class="text-muted fw-normal d-block">years old</small>
                 </div>
             </div>
         </div>
        `;


        if (petEditBtn) petEditBtn.href = `/pets/${pet.id}/edit`;

        petModal.show();
    });
});

// DELETE PET LOGIC
const deletePetModalEl = document.getElementById('deletePetModal');
const deletePetModal = deletePetModalEl ? new bootstrap.Modal(deletePetModalEl) : null;
const confirmDeletePetBtn = document.getElementById('confirmDeletePetBtn');
let petIdToDelete = null;

// Event Delegation for Delete Buttons (since they are in a loop/modal)
document.body.addEventListener('click', function(e) {
    const btn = e.target.closest('.delete-pet-btn');
    if (btn) {
        petIdToDelete = btn.dataset.petId;
        // Optional: Hide My Pets modal if you want to avoid stacking
        // const myPetsModalEl = document.getElementById('myPetsModal');
        // const myPetsModal = bootstrap.Modal.getInstance(myPetsModalEl);
        // if (myPetsModal) myPetsModal.hide();

        if (deletePetModal) deletePetModal.show();
    }
});

if (confirmDeletePetBtn) {
    confirmDeletePetBtn.addEventListener('click', () => {
        if (!petIdToDelete) return;

        // Disable button to prevent double clicks
        confirmDeletePetBtn.disabled = true;
        confirmDeletePetBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Deleting...';

        fetch(`/pets/${petIdToDelete}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                location.reload(); 
            } else {
                alert('Failed to delete pet. Please try again.');
                confirmDeletePetBtn.disabled = false;
                confirmDeletePetBtn.textContent = 'Delete Pet';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred.');
            confirmDeletePetBtn.disabled = false;
            confirmDeletePetBtn.textContent = 'Delete Pet';
        });
    });
}
// REVIEW MODAL DATA
const reviewModal = document.getElementById('reviewModal');
reviewModal?.addEventListener('show.bs.modal', e => {
    const btn = e.relatedTarget;
    document.getElementById('reviewAppointment').value = btn.dataset.appointment;
    document.getElementById('reviewClinic').value = btn.dataset.clinic;
    const ratingValue = document.getElementById('ratingValue');
    if (ratingValue) ratingValue.value = '';
    document.querySelectorAll('#starRating .star').forEach(s => {
        s.classList.remove('bi-star-fill');
        s.classList.add('bi-star');
    });
    const imagesInput = document.getElementById('reviewImages');
    const preview = document.getElementById('reviewImagesPreview');
    if (imagesInput) imagesInput.value = '';
    if (preview) preview.innerHTML = '';
});

reviewModal?.addEventListener('hidden.bs.modal', () => {
    const preview = document.getElementById('reviewImagesPreview');
    if (preview) preview.innerHTML = '';
});

// STAR SELECT
document.querySelectorAll('#starRating .star').forEach(star => {
    star.addEventListener('click', function () {
        const val = this.dataset.value;
        document.getElementById('ratingValue').value = val;
        document.querySelectorAll('#starRating .star').forEach(s => {
            s.classList.toggle('bi-star-fill', s.dataset.value <= val);
            s.classList.toggle('bi-star', s.dataset.value > val);
        });
    });
});

const reviewImagesInput = document.getElementById('reviewImages');
reviewImagesInput?.addEventListener('change', () => {
    const preview = document.getElementById('reviewImagesPreview');
    if (!preview) return;
    preview.innerHTML = '';
    const files = Array.from(reviewImagesInput.files || []).slice(0, 5);
    files.forEach(file => {
        const url = URL.createObjectURL(file);
        const wrap = document.createElement('a');
        wrap.href = url;
        wrap.target = '_blank';
        wrap.className = 'd-inline-block';
        wrap.innerHTML = `<img src="${url}" class="rounded-3 border shadow-sm object-fit-cover" style="width: 70px; height: 70px;">`;
        preview.appendChild(wrap);
    });
});
    const mobileThemeGif = document.getElementById('mobileThemeGif');
    const mobileThemeBtn = document.getElementById('mobileThemeBtn');
    
    function updateThemeGif(isDark) {
        if (mobileThemeGif) {
            mobileThemeGif.src = isDark 
                ? "{{ asset('images/night.gif') }}" 
                : "{{ asset('images/light (2).gif') }}";
        }
    }

    if (mobileThemeBtn) {
        // Initialize based on current state
        updateThemeGif(document.body.classList.contains('dark-theme'));

        mobileThemeBtn.addEventListener('click', e => {
            e.preventDefault();
            const isDark = document.body.classList.contains('dark-theme');
            if (isDark) {
                document.body.classList.remove('dark-theme');
                document.documentElement.classList.remove('dark-theme');
                localStorage.setItem('theme', 'light');
                updateThemeGif(false);
            } else {
                document.body.classList.add('dark-theme');
                document.documentElement.classList.add('dark-theme');
                localStorage.setItem('theme', 'dark');
                updateThemeGif(true);
            }
        });
    }

    // Mobile Profile Bottom Sheet Logic
    const mobileProfileBtn = document.getElementById('mobileProfileBtn');
    const mobileProfileSheet = document.getElementById('mobileProfileSheet');
    const mobileProfileOverlay = document.getElementById('mobileProfileOverlay');

    if (mobileProfileBtn && mobileProfileSheet && mobileProfileOverlay) {
        mobileProfileBtn.addEventListener('click', e => {
            e.preventDefault();
            mobileProfileSheet.classList.add('show');
            mobileProfileOverlay.classList.add('show');
        });

        const closeSheet = () => {
            mobileProfileSheet.classList.remove('show');
            mobileProfileOverlay.classList.remove('show');
        };

        mobileProfileOverlay.addEventListener('click', closeSheet);
        
        // Close when clicking the handle bar (optional UX)
        const handle = mobileProfileSheet.querySelector('.sheet-handle-bar');
        if(handle) handle.addEventListener('click', closeSheet);
    }

    // -------------------------------
    // SLIDESHOW ANIMATION
    // -------------------------------
    const slideshows = document.querySelectorAll('.slideshow-container');
    slideshows.forEach(container => {
        const sourceSpans = container.querySelectorAll('.slide-source-data span');
        const images = Array.from(sourceSpans).map(span => span.dataset.src);
        const slots = container.querySelectorAll('.slide-slot');
        
        if (images.length === 0) return;
        
        let currentIndex = 0;

        function updateSlots() {
            slots.forEach((slot, index) => {
                slot.innerHTML = '';
                const imgIndex = (currentIndex + index) % images.length;
                const img = document.createElement('img');
                img.src = images[imgIndex];
                img.classList.add('slide-img');
                slot.appendChild(img);
                
                // Staggered fade in: Left(0), Middle(500ms), Right(1000ms)
                setTimeout(() => {
                    img.classList.add('active');
                }, index * 500 + 50);
            });
            currentIndex = (currentIndex + 3) % images.length;
        }

        // Initial run
        updateSlots();

        // Loop cycle
        setInterval(() => {
            // Fade out current images (Staggered: Left -> Middle -> Right)
            slots.forEach((slot, index) => {
                const img = slot.querySelector('img');
                if (img) {
                    setTimeout(() => {
                        img.classList.remove('active');
                    }, index * 500); // 0ms, 500ms, 1000ms
                }
            });
            
            // Wait for last fade out to finish (1000ms delay + 1000ms transition)
            setTimeout(updateSlots, 2100);
            
        }, 6000); // Total cycle time increased to account for staggered exit
    });

    // -------------------------------
    // ANIMAL CATEGORY FILTER (SLIDESHOW BUTTONS)
    // -------------------------------
    const categoryButtons = document.querySelectorAll('.category-toggle-btn');
    const categoryContents = document.querySelectorAll('.category-content');
    
    categoryButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const catId = this.dataset.category;
            const isMobile = window.innerWidth < 768; // Bootstrap md breakpoint
            const targetId = isMobile ? `#${catId}-mobile` : `#${catId}-desktop`;
            const content = document.querySelector(targetId);
            const isActive = this.classList.contains('active');

            // Close all first (reset state)
            categoryButtons.forEach(b => b.classList.remove('active'));
            categoryContents.forEach(c => c.style.maxHeight = '0px');

            // If it wasn't active, open it
            if (!isActive) {
                this.classList.add('active');
                if (content) {
                    content.style.maxHeight = content.scrollHeight + 'px';

                    // Animate items one by one
                    const items = content.querySelectorAll('.fade-in-item');
                    items.forEach((item, index) => {
                        item.style.opacity = '0';
                        item.style.transform = 'translateY(20px)';
                        // Force reflow
                        void item.offsetWidth;
                        
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'translateY(0)';
                        }, index * 100);
                    });
                }
            }
        });
    });
    // -------------------------------
    // DESKTOP SIDEBAR COLLAPSE
    // -------------------------------
    const desktopSidebarToggle = document.getElementById('desktopSidebarToggle');
    const mainContent = document.getElementById('mainContent');
    // sidebar is already defined at the top of this scope
    
    // Check localStorage
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (isCollapsed) {
        if(sidebar) sidebar.classList.add('collapsed');
        if(mainContent) mainContent.classList.add('expanded');
    }

    if (desktopSidebarToggle) {
        desktopSidebarToggle.addEventListener('click', () => {
            if(sidebar) sidebar.classList.toggle('collapsed');
            if(mainContent) mainContent.classList.toggle('expanded');
            
            // Save state
            if(sidebar) localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        });
    }

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection
