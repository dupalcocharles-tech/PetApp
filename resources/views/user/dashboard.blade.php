@extends('layouts.app')

@section('content')
@php
    /**
     * Dynamically resolve the image path for each animal.
     */
    function animalImagePath($animal) {
        $filename = strtolower(str_replace(' ', '_', $animal));
        $extensions = ['jpg', 'jpeg', 'png', 'webp'];
        foreach ($extensions as $ext) {
            $path = public_path("images/{$filename}.{$ext}");
            if (file_exists($path)) {
                return asset("images/{$filename}.{$ext}");
            }
        }
        return asset('images/default.jpg');
    }

    $mammals = ['Dogs','Cats','Rabbits','Cows','Sheep','Goats','Pigs','Horses'];
    $birds = ['Chickens','Ducks','Turkeys','Geese','Parrots'];
    $rodents = ['Hamsters','Guinea pigs','Mice','Rats'];

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

                {{-- Notifications --}}
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-2 rounded-3 text-white text-opacity-75 hover-bg-white-10" 
                       href="#" data-bs-toggle="modal" data-bs-target="#recentAppointmentsModal" 
                       title="Notifications">
                        <i class="bi bi-bell-fill me-3 fs-5"></i>
                        <span class="fw-medium">Notifications</span>
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
            {{-- Mobile Header --}}
            <div class="d-flex d-md-none align-items-center justify-content-between p-3 mb-3 border-bottom bg-white sticky-top shadow-sm mobile-header-bar">
                <div class="d-flex align-items-center">
                    <button class="btn btn-light rounded-circle shadow-sm me-3 border" id="sidebarToggle" style="width: 40px; height: 40px;">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <span class="fw-bold fs-5 text-dark tracking-tight mobile-app-title">PetApp</span>
                </div>
                <img src="{{ Auth::user()->profile_image ? asset('storage/'.Auth::user()->profile_image) : asset('images/owner.png') }}" 
                     class="rounded-circle border shadow-sm object-fit-cover" 
                     style="width: 35px; height: 35px;" alt="User">
            </div>

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
                                    <button class="btn btn-light rounded-pill px-3 py-2 fw-medium d-flex align-items-center border-0 bg-transparent" type="button" data-bs-toggle="modal" data-bs-target="#locationModal">
                                        <i class="bi bi-geo-alt-fill text-danger me-2"></i>
                                        <span class="text-truncate" style="max-width: 100px;">{{ $selectedLocation ?: 'Location' }}</span>
                                        <i class="bi bi-chevron-down ms-2 small text-muted"></i>
                                    </button>
                                    <input type="hidden" name="location" value="{{ $selectedLocation }}">
                                    <div class="vr mx-2 opacity-25"></div>
                                    <input type="text" class="form-control border-0 shadow-none bg-transparent" 
                                           placeholder="Search for clinics or services..." 
                                           readonly onclick="openSearchModal()" style="cursor: pointer;">
                                    <img src="{{ asset('images/searchicon.gif') }}" 
                                         class="search-button-img p-1" 
                                         onclick="openSearchModal()" 
                                         alt="Search"
                                         style="width: 40px; height: 40px; cursor: pointer; object-fit: contain;">
                                </div>
                            </div>
                            <div class="col-lg-5 d-none d-lg-block text-end position-relative" style="height: 210px;">
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
                                $statusLabel = 'No Appointments';
                                $badgeClass = 'bg-secondary';

                                if (isset($recentAppointments) && $recentAppointments->count() > 0) {
                                    $statusCounts = $recentAppointments->groupBy('status')->map->count();

                                    $pendingTotal = ($statusCounts['pending'] ?? 0) + ($statusCounts['requested'] ?? 0);

                                    if ($pendingTotal > 0) {
                                        $statusLabel = $pendingTotal . ' Pending';
                                        $badgeClass = 'bg-warning';
                                    } elseif (!empty($statusCounts['approved'] ?? 0)) {
                                        $statusLabel = $statusCounts['approved'] . ' Approved';
                                        $badgeClass = 'bg-success';
                                    } elseif (!empty($statusCounts['completed'] ?? 0)) {
                                        $statusLabel = $statusCounts['completed'] . ' Completed';
                                        $badgeClass = 'bg-success';
                                    } elseif (!empty($statusCounts['cancelled'] ?? 0)) {
                                        $statusLabel = $statusCounts['cancelled'] . ' Cancelled';
                                        $badgeClass = 'bg-danger';
                                    } else {
                                        $total = $recentAppointments->count();
                                        $statusLabel = $total . ' Booked';
                                        $badgeClass = 'bg-primary';
                                    }
                                }
                            @endphp
                            <span class="badge {{ $badgeClass }} rounded-pill px-3">{{ $statusLabel }}</span>
                        </div>
                    </div>
                </div>

                {{-- Vet Notes --}}
                <div class="col-6 col-md-3">
                     <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift transition-all cursor-pointer" data-bs-toggle="modal" data-bs-target="#vetNotesModal">
                        <div class="card-body p-3 d-flex flex-column align-items-center justify-content-center text-center">
                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle mb-3">
                                <img src="{{ asset('images/notes.gif') }}" alt="Vet Notes" style="width: 40px; height: 40px; object-fit: contain;">
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
                                <img src="{{ asset('images/cat-playing.gif') }}" alt="Add Pet" style="width: 40px; height: 40px; object-fit: contain;">
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
        opacity: 0.3;
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
    /* =========================================
       MODERN MODAL DESIGN & DARK THEME SYSTEM
       ========================================= */
    
    /* 1. Enhanced Base Design (Light Mode Default) */
    .modal-content {
        border: none;
        border-radius: 1.5rem; /* Modern rounded corners */
        box-shadow: 0 20px 50px rgba(0,0,0,0.2); /* Deep, soft shadow */
        overflow: hidden; /* Clip content to corners */
        background-color: rgba(255, 255, 255, 0.98); /* Slight transparency */
    }

    /* Glassmorphism Effect for Modals */
    @supports (backdrop-filter: blur(10px)) {
        .modal-content {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            background-color: rgba(255, 255, 255, 0.9);
        }
    }

    .modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        align-items: center;
    }

    .modal-footer {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid rgba(0,0,0,0.05);
        background-color: rgba(0,0,0,0.02); /* Very subtle contrast */
    }

    /* Smooth Pop Animation */
    .modal.fade .modal-dialog {
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        transform: scale(0.92) translateY(20px);
    }
    .modal.show .modal-dialog {
        transform: scale(1) translateY(0);
    }

    /* 2. Global Dark Theme for Modals */
    body.dark-theme .modal-content {
        background-color: #1e1e1e; /* Fallback */
        color: #fff;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    }
    
    @supports (backdrop-filter: blur(10px)) {
        body.dark-theme .modal-content {
            background-color: rgba(30, 30, 30, 0.9);
            border: 1px solid rgba(255,255,255,0.05);
        }
    }

    /* Header Backgrounds & Borders in Dark Mode */
    body.dark-theme .modal-header {
        border-bottom-color: rgba(255,255,255,0.05);
        background-color: rgba(255,255,255,0.02);
    }
    /* Preserve specific header colors with increased opacity for legibility */
    body.dark-theme .modal-header.bg-success { background-color: #198754 !important; }
    body.dark-theme .modal-header.bg-primary { background-color: #0d6efd !important; }
    body.dark-theme .modal-header.bg-danger  { background-color: #dc3545 !important; }
    body.dark-theme .modal-header.bg-info    { background-color: #0dcaf0 !important; }
    body.dark-theme .modal-header.bg-warning { background-color: #ffc107 !important; }
    body.dark-theme .modal-header.bg-white   { background-color: transparent !important; }

    /* Close Button Inversion */
    body.dark-theme .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
        opacity: 0.8;
    }
    body.dark-theme .btn-close:hover { opacity: 1; }

    /* Modal Body & Footer */
    body.dark-theme .modal-body {
        color: #e0e0e0;
    }
    body.dark-theme .modal-footer {
        border-top-color: rgba(255,255,255,0.05);
        background-color: rgba(0,0,0,0.2);
    }

    /* Text Colors & Typography */
    body.dark-theme .modal-title,
    body.dark-theme .modal-body h1, body.dark-theme .modal-body h2, 
    body.dark-theme .modal-body h3, body.dark-theme .modal-body h4, 
    body.dark-theme .modal-body h5, body.dark-theme .modal-body h6,
    body.dark-theme .modal-content .text-dark {
        color: #fff !important;
    }
    body.dark-theme .modal-content .text-danger { color: #ff8b94 !important; }
    body.dark-theme .modal-content .text-primary { color: #6ea8fe !important; }
    body.dark-theme .modal-content .text-success { color: #75b798 !important; }
    body.dark-theme .modal-content .text-muted { color: #adb5bd !important; }

    /* Form Elements Modernization */
    body.dark-theme .modal-content .form-control,
    body.dark-theme .modal-content .form-select {
        background-color: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: #fff;
        border-radius: 0.8rem;
    }
    body.dark-theme .modal-content .form-control:focus,
    body.dark-theme .modal-content .form-select:focus {
        background-color: rgba(255,255,255,0.1);
        border-color: #198754;
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
    }
    
    /* Input Groups */
    body.dark-theme .modal-content .input-group-text {
        background-color: rgba(255,255,255,0.1);
        border-color: rgba(255,255,255,0.1);
        color: #e0e0e0;
    }

    /* Cards inside Modals */
    body.dark-theme .modal-content .card {
        background-color: rgba(255,255,255,0.05);
        color: #e0e0e0;
        border: 1px solid rgba(255,255,255,0.05);
    }
    
    /* Service Items */
    body.dark-theme .service-item {
        border-color: rgba(255,255,255,0.1);
        background-color: transparent;
        color: #e0e0e0;
    }
    body.dark-theme .service-item:hover {
        background-color: rgba(255,255,255,0.05);
    }
    body.dark-theme .service-item.active {
        background-color: rgba(25, 135, 84, 0.2);
        border-color: #198754 !important;
    }
    
    /* Background Utilities in Modals */
    body.dark-theme .modal-content .bg-light,
    body.dark-theme .modal-content .bg-light-subtle {
        background-color: rgba(255,255,255,0.05) !important;
        color: #e0e0e0 !important;
        border-color: rgba(255,255,255,0.1) !important;
    }

    /* Restored Utilities */
    body.dark-theme .invert-on-dark {
        filter: invert(1) hue-rotate(180deg);
    }
    body.dark-theme .modal-content .form-label {
        color: #e0e0e0;
    }

    /* 3. Component Refinements */
    .modal-footer .btn {
        border-radius: 50rem; /* Pill shape */
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        letter-spacing: 0.3px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        transition: all 0.2s ease;
    }
    .modal-footer .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.1);
    }
    
    /* Stylish Close Button */
    .modal-header .btn-close {
        background-color: rgba(0,0,0,0.05);
        border-radius: 50%;
        padding: 0.5rem;
        background-size: 0.8rem;
        transition: transform 0.2s, background-color 0.2s;
        opacity: 0.7;
    }
    .modal-header .btn-close:hover {
        background-color: rgba(220, 53, 69, 0.1);
        transform: rotate(90deg);
        opacity: 1;
    }
    body.dark-theme .modal-header .btn-close {
        background-color: rgba(255,255,255,0.1);
    }
    
    /* Better Scrollbars */
    .modal-body::-webkit-scrollbar {
        width: 6px;
    }
    .modal-body::-webkit-scrollbar-track {
        background: transparent;
    }
    .modal-body::-webkit-scrollbar-thumb {
        background-color: rgba(0,0,0,0.1);
        border-radius: 10px;
    }
    body.dark-theme .modal-body::-webkit-scrollbar-thumb {
        background-color: rgba(255,255,255,0.2);
    }

    /* Force Glass Effect on Headers */
    .modal-header.bg-white {
        background-color: transparent !important; 
    }
    
    /* Input Fields Polish */
    .modal-content .form-control:focus, 
    .modal-content .form-select:focus {
        box-shadow: 0 0 0 4px rgba(25, 135, 84, 0.15); 
        border-color: #198754;
    }

    /* 4. Location Modal Dark Mode Support */
    body.dark-theme #locationModal .btn-light {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
        color: #e0e0e0 !important;
    }
    body.dark-theme #locationModal .btn-light:hover {
        background-color: rgba(255, 255, 255, 0.1) !important;
        transform: translateY(-2px);
    }
    body.dark-theme #locationModal .border-top {
        border-color: rgba(255, 255, 255, 0.1) !important;
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
    body.dark-theme .search-button-img {
        filter: invert(1);
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
                        <span data-src="{{ animalImagePath($animal) }}"></span>
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
                        <button class="btn animal-btn w-100 shadow-sm" data-animal="{{ $animal }}">
                            <img src="{{ animalImagePath($animal) }}" alt="{{ $animal }}">
                            <div class="overlay">{{ $animal }}</div>
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
                    <button class="btn animal-btn w-100 shadow-sm" data-animal="{{ $animal }}">
                        <img src="{{ animalImagePath($animal) }}" alt="{{ $animal }}">
                        <div class="overlay">{{ $animal }}</div>
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
        <img src="{{ asset('images/notes.gif') }}" alt="Vet Notes" style="width: 40px; height: 40px; margin-bottom: 2px;">
        <span>Vet Notes</span>
    </a>

    <a href="{{ route('pets.create') }}" class="{{ request()->routeIs('pets.create') ? 'active' : '' }}">
        <img src="{{ asset('images/cat-playing.gif') }}" alt="Add Pet" style="width: 40px; height: 40px; margin-bottom: 2px;">
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
      <div class="modal-header bg-white border-0 pb-0">
        <h5 class="modal-title fw-bold text-dark"><i class="bi bi-paw-fill me-2 text-success"></i>My Pets</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 bg-light-subtle">
       @if(isset($pets) && $pets->count() > 0)
        <div class="row row-cols-1 row-cols-sm-2 g-3">
            @foreach($pets as $pet)
                <div class="col">
                    <div class="card border-0 shadow-sm rounded-4 h-100 my-pet-card hover-lift transition-all">
                        <div class="card-body p-3 text-center">
                            <!-- Circular Image -->
                            <div class="position-relative d-inline-block mb-3">
                                <img src="{{ $pet->image ? $pet->image_url : asset('images/default_pet.jpg') }}" 
                                     class="rounded-circle border border-3 border-success border-opacity-25 object-fit-cover shadow-sm" 
                                     alt="{{ $pet->name }}" 
                                     style="height: 100px; width: 100px;">
                                <div class="position-absolute bottom-0 end-0 bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 28px; height: 28px; border: 2px solid white;">
                                    <i class="bi bi-heart-fill" style="font-size: 0.8rem;"></i>
                                </div>
                            </div>
                            
                            <!-- Pet Details -->
                            <h5 class="fw-bold mb-1 pet-name text-dark">{{ $pet->name ?? 'Unnamed' }}</h5>
                            <div class="d-flex justify-content-center gap-2 mb-3">
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 small">
                                    {{ $pet->age ?? 'N/A' }} yrs
                                </span>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1 small text-truncate" style="max-width: 100px;">
                                    {{ $pet->breed ?? 'Unknown' }}
                                </span>
                            </div>

                            <!-- Actions -->
                            <div class="d-flex justify-content-center gap-2 pt-2 border-top">
                                <a href="{{ route('pets.edit', $pet->id) }}" class="btn btn-sm btn-light rounded-pill px-3 fw-bold text-success hover-bg-success-subtle transition-all">
                                    <i class="bi bi-pencil-square me-1"></i>Edit
                                </a>
                                <button type="button" class="btn btn-sm btn-light rounded-pill px-3 fw-bold text-danger hover-bg-danger-subtle transition-all delete-pet-btn" 
                                        data-pet-id="{{ $pet->id }}">
                                    <i class="bi bi-trash me-1"></i>Delete
                                </button>
                            </div>
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
      <div class="modal-footer border-0 bg-light-subtle pb-4 justify-content-center">
        <button type="button" class="btn btn-secondary rounded-pill px-5 fw-bold shadow-sm" data-bs-dismiss="modal">Close</button>
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
        border: 1px solid rgba(255,255,255,0.05) !important;
    }
    body.dark-theme .my-pet-card .pet-name {
        color: #fff !important;
    }
    body.dark-theme .my-pet-card .text-muted {
        color: #adb5bd !important;
    }
    body.dark-theme .my-pet-card .btn-light {
        background-color: rgba(255,255,255,0.05);
        color: #e0e0e0 !important;
        border: none;
    }
    body.dark-theme .my-pet-card .btn-light:hover {
        background-color: rgba(255,255,255,0.1);
    }
    
    .my-pet-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .my-pet-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1) !important;
    }
    .hover-bg-success-subtle:hover {
        background-color: rgba(25, 135, 84, 0.1) !important;
    }
    .hover-bg-danger-subtle:hover {
        background-color: rgba(220, 53, 69, 0.1) !important;
    }
    body.dark-theme .bg-light-subtle {
        background-color: #1e1e1e !important;
    }

    /* Recent Appointments Modal styles removed */
    
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

    /* Recent Appointments Body styles removed */

    /* Appointment Details Modal styles removed */

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
    
    /* Dark Theme for Logout Modal - Specific Override */
    body.dark-theme #logoutModal .bg-danger-subtle {
        background-color: rgba(220, 53, 69, 0.2) !important;
        color: #ea868f !important;
    }

    /* Mobile Bottom Nav Dark Mode - Invert Black to White, preserve other colors */
    body.dark-theme .mobile-bottom-nav img {
        filter: invert(1) hue-rotate(180deg);
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
        @if(isset($reportNotifications) && $reportNotifications->count() > 0)
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-2">
                    <i class="bi bi-bell-fill me-1 text-warning"></i>
                    Report Updates
                </h6>
                <div class="list-group small">
                    @foreach($reportNotifications as $note)
                        <div class="list-group-item border-0 px-0 d-flex justify-content-between align-items-start bg-transparent">
                            <div>
                                <div class="fw-semibold text-dark">
                                    {{ $note['clinic_name'] }}
                                </div>
                                <div class="text-muted">
                                    {{ $note['message'] }}
                                </div>
                            </div>
                            <span class="text-muted ms-3" style="font-size: 0.75rem;">
                                {{ $note['created_at']->diffForHumans() }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

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
                            qr_code: '{{ $appointment->clinic?->qr_code ? asset('storage/clinics/qr_codes/' . $appointment->clinic->qr_code) : '' }}',
                            payment_receipt: '{{ $appointment->payment_receipt ? asset('storage/payment_receipts/' . $appointment->payment_receipt) : '' }}',
                            uploadReceiptUrl: '{{ route('payment.uploadReceipt', $appointment->id) }}',
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

                                    @if(in_array($appointment->status, ['pending', 'requested']))
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" 
                                                onclick="event.stopPropagation(); showCancelModal('{{ $appointment->id }}')"
                                                title="Cancel Appointment">
                                            <i class="bi bi-x-circle"></i> Cancel
                                        </button>
                                    @endif

                                    @if($appointment->payment_status === 'unpaid' && ($appointment->status == 'approved' || $appointment->payment_method == 'online'))
                                        @if($appointment->payment_method == 'online')
                                            <button type="button" class="btn btn-sm btn-success rounded-pill fw-bold px-3">
                                                Pay / Upload Receipt
                                            </button>
                                        @else
                                            <a href="{{ route('payment.checkout', $appointment->id) }}" class="btn btn-sm btn-success rounded-pill fw-bold px-3" onclick="event.stopPropagation()">
                                                Pay Downpayment
                                            </a>
                                        @endif
                                    @elseif($appointment->payment_status === 'downpayment_paid')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">
                                            <i class="bi bi-shield-fill-check me-1"></i> Verified
                                        </span>
                                    @endif

                                    <div class="d-flex align-items-center gap-2">
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
                                            <div class="text-warning small me-2" title="Rated {{ $appointment->review->rating }} stars">
                                                @for($i = 0; $i < 5; $i++)
                                                    <i class="bi bi-star{{ $i < $appointment->review->rating ? '-fill' : '' }}"></i>
                                                @endfor
                                            </div>
                                        @endif

                                        @if($appointment->status === 'completed')
                                            <button class="btn btn-sm btn-outline-danger rounded-pill fw-bold px-3"
                                                    onclick="event.stopPropagation()"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#reportClinicModal"
                                                    data-appointment="{{ $appointment->id }}">
                                                Report
                                            </button>
                                        @endif
                                    </div>
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

        <div id="apptDetailsQrContainer" class="text-center mb-3 mt-3 d-none p-3 border rounded bg-white shadow-sm">
            <p class="small text-muted fw-bold text-uppercase mb-2">Scan to Pay (GCash/Maya)</p>
            <img id="apptDetailsQrImage" src="" class="img-fluid rounded" style="max-height: 200px; object-fit: contain;">
            <div class="small text-muted mt-2">After scanning, please upload your receipt below for verification.</div>
        </div>

        <div id="apptDetailsReceiptContainer" class="mb-3 d-none">
            <div class="card border-0 bg-light p-3">
                <h6 class="fw-bold text-dark small text-uppercase mb-2">Upload Payment Receipt</h6>
                
                {{-- Form for uploading receipt --}}
                <form id="uploadReceiptForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group mb-2">
                        <input type="file" class="form-control" name="receipt" accept="image/*" required>
                        <button class="btn btn-primary" type="submit">Upload</button>
                    </div>
                    <div class="form-text small">Please upload a screenshot of your payment confirmation.</div>
                </form>

                {{-- Status if already uploaded --}}
                <div id="receiptPreview" class="mt-2 d-none">
                     <div class="alert alert-success d-flex align-items-center gap-2 mb-0 py-2">
                        <i class="bi bi-check-circle-fill"></i>
                        <div class="small fw-bold">Receipt Uploaded</div>
                        <a href="#" target="_blank" id="receiptLink" class="btn btn-sm btn-outline-success ms-auto py-0">View</a>
                     </div>
                     <div class="text-muted small mt-1 text-center fst-italic">Waiting for clinic verification...</div>
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

<div class="modal fade" id="serviceImageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
      <div class="modal-header bg-white border-0 py-3">
        <h5 class="modal-title fw-bold text-dark" id="serviceImageModalTitle">Service Gallery</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0 bg-light">
        <div class="position-relative">
            <img id="serviceImageModalImg" src="" alt="Service Image" class="img-fluid w-100" style="max-height: 70vh; object-fit: contain;">
            
            <button type="button" class="btn btn-dark btn-sm rounded-circle position-absolute top-50 start-0 translate-middle-y ms-3 shadow-sm d-none" id="prevServiceImg" style="width: 32px; height: 32px; z-index: 10;">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button type="button" class="btn btn-dark btn-sm rounded-circle position-absolute top-50 end-0 translate-middle-y me-3 shadow-sm d-none" id="nextServiceImg" style="width: 32px; height: 32px; z-index: 10;">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
        <div id="serviceModalGallery" class="d-flex gap-2 overflow-x-auto p-3 bg-white border-top">
            <!-- Thumbnails injected via JS -->
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Booking Modal --}}
<div class="modal fade" id="bookModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false"
     data-owner-address="{{ $owner->address }}" data-owner-phone="{{ $owner->phone }}">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
      <form method="POST" action="{{ route('appointments.store') }}" enctype="multipart/form-data" onsubmit="localStorage.removeItem('openBookingModal')">
        @csrf
        
        <input type="hidden" name="clinic_id" id="modalClinicId">
        <input type="hidden" name="service_id" id="modalServiceId">
        <input type="hidden" name="service_location" id="bookingServiceLocation">

        <div class="modal-header bg-success text-white border-0">
          <h5 class="modal-title fw-bold"><i class="bi bi-calendar-check-fill me-2"></i>Book Appointment</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="localStorage.removeItem('openBookingModal')"></button>
        </div>
        <div class="modal-body p-4 bg-light-subtle">
          {{-- Service Header/Images --}}
          <div id="bookingServiceHeader" class="mb-4">
              <div class="d-flex align-items-center mb-2">
                  <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 me-3">
                      <i class="bi bi-briefcase-fill fs-4"></i>
                  </div>
                  <div>
                      <h4 class="fw-bold text-dark mb-0" id="bookingServiceName">Service Name</h4>
                      <div class="text-muted small" id="bookingServiceDesc"></div>
                  </div>
              </div>
              <div id="bookingServiceImages" class="d-flex gap-2 overflow-x-auto pb-2 mt-3">
                  <!-- Service images injected via JS -->
              </div>
          </div>

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
            <label class="form-label fw-bold text-secondary small text-uppercase">Choose Date & Time</label>
            <div class="input-group shadow-sm" data-booking-manual-datetime>
                <span class="input-group-text bg-white border-end-0 text-success"><i class="bi bi-clock-fill"></i></span>
                <input type="datetime-local" name="appointment_date" class="form-control border-start-0 ps-0" required style="cursor: pointer;">
            </div>
            <div class="mt-2 d-none" data-booking-slot-select-group>
                <div class="d-flex flex-wrap gap-2" data-booking-slot-buttons></div>
                <div class="small text-muted mt-1">Tap a time slot to select.</div>
            </div>
          </div>

          {{-- Clinic Location Map --}}
          <div class="mb-3">
              <label class="form-label fw-bold text-secondary small text-uppercase">Clinic Location</label>
              <div id="bookingClinicMap" class="rounded-3 border border-2 border-secondary shadow-sm" style="height: 300px; width: 100%; z-index: 1; min-height: 300px;"></div>
          </div>

          {{-- Service Location Preference --}}
          <div class="mb-3" id="serviceLocationSection" style="display: none;">
              <label class="form-label fw-bold text-secondary small text-uppercase">Service Location Preference</label>

              <div id="serviceLocationClinicOnly" class="alert alert-light border border-success-subtle d-none small mb-2">
                  <i class="bi bi-info-circle me-1 text-success"></i>
                  This service is performed at the clinic only.
              </div>

              <div id="serviceLocationHomeOnly" class="alert alert-light border border-success-subtle d-none small mb-2">
                  <i class="bi bi-house-door-fill me-1 text-success"></i>
                  This service is provided as a home service.
              </div>

              <div id="serviceLocationBothOptions" class="d-none mb-2">
                  <div class="d-flex gap-3">
                      <div class="form-check flex-fill p-0">
                          <input type="radio" class="btn-check" name="service_location_choice" id="bookingLocationClinic" value="clinic" checked>
                          <label class="btn btn-outline-success w-100 p-2 rounded-3 d-flex flex-column align-items-center gap-1 shadow-sm" for="bookingLocationClinic">
                              <i class="bi bi-hospital fs-5"></i>
                              <span class="small fw-bold">At Clinic</span>
                          </label>
                      </div>
                      <div class="form-check flex-fill p-0">
                          <input type="radio" class="btn-check" name="service_location_choice" id="bookingLocationHome" value="home">
                          <label class="btn btn-outline-primary w-100 p-2 rounded-3 d-flex flex-column align-items-center gap-1 shadow-sm" for="bookingLocationHome">
                              <i class="bi bi-house-door fs-5"></i>
                              <span class="small fw-bold">Home Service</span>
                          </label>
                      </div>
                  </div>
              </div>

              <div id="serviceHomeInfo" class="mt-2 d-none">
                  <div class="card border-0 shadow-sm rounded-3">
                      <div class="card-body p-3">
                          <div class="small text-muted text-uppercase fw-bold mb-1">Home Service Details</div>
                          <div class="small mb-2">
                              <label class="fw-semibold mb-1">Service Address</label>
                              <input type="text" name="service_address" class="form-control form-control-sm" data-home-address-input>
                          </div>
                          <div class="small mb-1">
                              <label class="fw-semibold mb-1">Contact Number</label>
                              <input type="text" name="service_contact" class="form-control form-control-sm" data-home-phone-input>
                          </div>
                          <div class="small text-muted mt-1">
                              Please ensure someone is available at this address during the scheduled time.
                              Additional travel fees, if any, will be discussed by the clinic.
                          </div>
                      </div>
                  </div>
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
             </div>
          </div>
          
          {{-- Clinic Reviews --}}
          <div class="mb-3">
              <label class="form-label fw-bold text-secondary small text-uppercase">Recent Reviews</label>
              <div id="bookingClinicReviews" class="list-group list-group-flush border rounded-3 overflow-hidden shadow-sm bg-white">
                  <div class="p-3 text-center text-muted small">No reviews yet.</div>
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

              {{-- QR Code and Receipt Upload (Added for Immediate Online Payment) --}}
              <div class="mt-4 pt-4 border-top">
                  <div class="card border shadow-sm rounded-3 overflow-hidden payment-scan-card">
                       <div class="card-header bg-primary text-white py-2 px-3 fw-bold small text-uppercase">
                            <i class="bi bi-qr-code-scan me-2"></i>Scan & Pay
                       </div>
                       <div class="card-body p-4 text-center">
                            <div class="bg-white p-3 rounded-3 d-inline-block shadow-sm mb-3 border qr-wrapper" style="cursor: pointer;" onclick="expandQrImage(this.querySelector('img').src)">
                                <img src="" id="clinicQrCode" alt="Clinic QR Code" class="img-fluid rounded" style="max-height: 250px; display: none;" onerror="this.style.display='none'; document.getElementById('noQrMessage').style.display='block';">
                                <p id="noQrMessage" class="text-muted small fst-italic m-0" style="display: none;">No QR Code available for this clinic.</p>
                                <div class="small text-muted mt-1 fst-italic"><i class="bi bi-arrows-fullscreen me-1"></i>Tap to expand</div>
                            </div>
                            <p class="small text-muted mb-0">Scan this QR code using your payment app (GCash/Maya).</p>
                       </div>
                  </div>

                  <div class="mt-3">
                      <label for="paymentReceipt" class="form-label small fw-bold text-body text-uppercase">Upload Payment Receipt <span class="text-danger">*</span></label>
                      <div class="input-group">
                           <input type="file" class="form-control" name="receipt" id="paymentReceipt" accept="image/*">
                           <label class="input-group-text bg-secondary-subtle text-secondary" for="paymentReceipt"><i class="bi bi-upload"></i></label>
                      </div>
                      <div class="form-text small"><i class="bi bi-info-circle me-1"></i>Please upload a clear screenshot of your successful transaction.</div>
                  </div>
              </div>
              
              <style>
                  body.dark-theme .qr-wrapper {
                      background-color: #fff !important; /* QR always on white for scanning */
                  }
                  body.dark-theme .payment-scan-card {
                      background-color: rgba(255,255,255,0.05);
                      border-color: rgba(255,255,255,0.1) !important;
                  }
                  body.dark-theme .payment-scan-card .text-muted {
                      color: #adb5bd !important;
                  }
                  body.dark-theme #paymentReceipt {
                      background-color: rgba(255,255,255,0.05);
                      border-color: rgba(255,255,255,0.1);
                      color: #e0e0e0;
                  }
                  body.dark-theme .input-group-text {
                      background-color: rgba(255,255,255,0.1) !important;
                      border-color: rgba(255,255,255,0.1) !important;
                      color: #e0e0e0 !important;
                  }
              </style>
          </div>
        </div>
        <div class="modal-footer border-0 bg-light-subtle pb-4 px-4">
          <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal" onclick="localStorage.removeItem('openBookingModal')">Cancel</button>
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
        <button type="button" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm" id="confirmLogoutBtn">Yes, Logout</button>
      </div>
    </div>
  </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>

<!-- ✅ Paste REVIEW MODAL here -->
<div class="modal fade" id="reviewModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('reviews.store') }}" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="appointment_id" id="reviewAppointment">
      <input type="hidden" name="clinic_id" id="reviewClinic">

      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Rate Clinic</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3 text-center">
            <div id="starRating" class="fs-3 text-warning">
              @for($i=1;$i<=5;$i++)
                <i class="bi bi-star star" data-value="{{ $i }}"></i>
              @endfor
            </div>
            <input type="hidden" name="rating" id="ratingValue" required>
          </div>

          <div class="mb-3">
              <label class="form-label small text-muted">Add Photos (Optional)</label>
              <input type="file" name="images[]" class="form-control" multiple accept="image/*">
          </div>

          <textarea name="review" class="form-control"
                    placeholder="Write your experience (optional)"></textarea>
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-primary">Submit Review</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="reportClinicModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('clinicReports.store') }}" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="appointment_id" id="reportAppointment">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">Report Clinic</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label small text-muted">Describe the issue</label>
            <textarea name="report_message" class="form-control" rows="4" required></textarea>
          </div>
          <div class="mb-3">
              <label class="form-label small text-muted">Attach proof (optional)</label>
              <input type="file" name="proof_image" class="form-control" accept="image/*">
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-danger">Submit Report</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- View Reviews Modal --}}
<div class="modal fade" id="viewReviewsModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="viewReviewsTitle">Clinic Reviews</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="viewReviewsBody" style="max-height: 70vh; overflow-y: auto;">
        <!-- Reviews populated via JS -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
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

    /* Vet Notes Dark Theme */
    body.dark-theme #vetNotesModal .modal-content {
        background: #020617;
        color: #e5e7eb;
    }
    body.dark-theme #vetNotesModal .modal-header,
    body.dark-theme #vetNotesModal .modal-footer {
        background: #020617;
        border-color: #1f2937;
    }
    body.dark-theme #vetNotesModal .modal-title,
    body.dark-theme #vetNotesModal .text-dark {
        color: #e5e7eb !important;
    }
    body.dark-theme #vetNotesModal .card {
        background: #020617;
        border-color: #1f2937;
    }
    body.dark-theme #vetNotesModal .bg-white,
    body.dark-theme #vetNotesModal .bg-light,
    body.dark-theme #vetNotesModal .bg-light-subtle,
    body.dark-theme #vetNotesModal .bg-primary-subtle,
    body.dark-theme #vetNotesModal .bg-secondary-subtle {
        background: #0f172a !important;
        color: #e5e7eb;
    }
    body.dark-theme #vetNotesModal .text-muted {
        color: #9ca3af !important;
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

                                    // Get medications
                                    $petMedications = $appointment->medications->where('pet_id', $pet->id);
                                    $medicationData = $petMedications->map(function($m) {
                                        return [
                                            'name' => $m->medicine_name,
                                            'dosage' => $m->dosage,
                                            'schedule' => $m->schedule,
                                            'period' => ($m->treatment_start ?? '') . ' - ' . ($m->treatment_end ?? '')
                                        ];
                                    })->values();
                                    $medicationsJson = addslashes($medicationData->toJson());

                                    // If only 1 pet, show it. If multiple, show none by default (waiting for selection)
                                    $isHidden = $appointment->pets->count() > 1 ? 'd-none' : '';
                                @endphp
                                <div id="petNote-{{ $appointment->id }}-{{ $pet->id }}" class="pet-note-container {{ $isHidden }}">
                                    @if($hasNotes)
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="fw-bold text-primary fs-5">
                                                <i class="bi bi-paw me-1"></i>{{ $pet->name }}
                                            </div>
                                            <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" 
                                                    onclick="downloadVetNote('{{ $clinicImage }}', '{{ addslashes($clinicName) }}', '{{ $date }}', '{{ addslashes($pet->name) }}', '{{ addslashes(str_replace(["\r", "\n"], ' ', $noteContent)) }}', '{{ $weight }}', '{{ $vaccineStatus }}', '{{ $vaccinationDates }}', '{{ addslashes($healthCondition) }}', '{{ $medicationsJson }}')">
                                                <i class="bi bi-download me-2"></i>DOWNLOAD
                                            </button>
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


function downloadVetNote(clinicImage, clinicName, date, petName, notes, weight, vaccineStatus, vaccinationDates, healthCondition, medicationsJson) {
    // Parse medications
    let medications = [];
    try {
        medications = JSON.parse(medicationsJson || '[]');
    } catch(e) {
        console.error("Error parsing medications", e);
    }

    // Build Prescriptions HTML
    let prescriptionsHtml = '';
    if (medications.length > 0) {
        let rows = medications.map(m => `
            <tr>
                <td style="padding: 8px; border: 1px solid #eee; color: #333;">${m.name}</td>
                <td style="padding: 8px; border: 1px solid #eee; color: #333;">${m.dosage}</td>
                <td style="padding: 8px; border: 1px solid #eee; color: #333;">${m.schedule}</td>
                <td style="padding: 8px; border: 1px solid #eee; color: #333;">${m.period}</td>
            </tr>
        `).join('');

        prescriptionsHtml = `
            <div style="margin-bottom: 30px;">
                <h3 style="color: #333; border-left: 4px solid #008080; padding-left: 10px;">Prescriptions</h3>
                <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                    <thead>
                        <tr style="background: #f4f4f4;">
                            <th style="padding: 8px; border: 1px solid #eee; text-align: left; color: #555;">Medicine</th>
                            <th style="padding: 8px; border: 1px solid #eee; text-align: left; color: #555;">Dosage</th>
                            <th style="padding: 8px; border: 1px solid #eee; text-align: left; color: #555;">Schedule</th>
                            <th style="padding: 8px; border: 1px solid #eee; text-align: left; color: #555;">Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${rows}
                    </tbody>
                </table>
            </div>
        `;
    }

    const content = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Vet Note - ${petName}</title>
            <meta charset="utf-8">
            <style>
                body { font-family: Arial, sans-serif; padding: 40px; background-color: #f4f4f4; }
                .container { max-width: 800px; margin: 0 auto; background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
            </style>
        </head>
        <body>
            <div class="container">
                <div style="text-align: center; margin-bottom: 30px; border-bottom: 2px solid #008080; padding-bottom: 20px;">
                    <h1 style="color: #008080; margin: 0;">Vet Note & Medical Record</h1>
                    <p style="color: #666; margin-top: 5px;">Generated from PetApp Medical Records</p>
                </div>
                
                <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 8px 0; font-weight: bold; color: #555; width: 150px;">Clinic:</td>
                            <td style="padding: 8px 0; color: #333;">
                                <div style="display: flex; align-items: center;">
                                    <img src="${clinicImage}" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; object-fit: cover; border: 1px solid #ddd;">
                                    <span>${clinicName}</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; font-weight: bold; color: #555;">Date:</td>
                            <td style="padding: 8px 0; color: #333;">${date}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; font-weight: bold; color: #555;">Pet Name:</td>
                            <td style="padding: 8px 0; color: #333;">${petName}</td>
                        </tr>
                    </table>
                </div>

                <div style="margin-bottom: 30px;">
                    <h3 style="color: #333; border-left: 4px solid #008080; padding-left: 10px;">Medical Details</h3>
                    <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                        <tr>
                            <td style="padding: 8px; border: 1px solid #eee; font-weight: bold; color: #555; background: #f4f4f4; width: 30%;">Weight</td>
                            <td style="padding: 8px; border: 1px solid #eee; color: #333;">${weight} kg</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #eee; font-weight: bold; color: #555; background: #f4f4f4;">Health Condition</td>
                            <td style="padding: 8px; border: 1px solid #eee; color: #333;">${healthCondition}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #eee; font-weight: bold; color: #555; background: #f4f4f4;">Vaccine Status</td>
                            <td style="padding: 8px; border: 1px solid #eee; color: #333;">${vaccineStatus}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #eee; font-weight: bold; color: #555; background: #f4f4f4;">Vaccination Dates</td>
                            <td style="padding: 8px; border: 1px solid #eee; color: #333;">${vaccinationDates}</td>
                        </tr>
                    </table>
                </div>

                ${prescriptionsHtml}

                <div>
                    <h3 style="color: #333; border-left: 4px solid #008080; padding-left: 10px;">Veterinarian's Notes</h3>
                    <div style="margin-top: 15px; line-height: 1.6; color: #444; white-space: pre-wrap; text-align: justify; background: #fff; border: 1px solid #eee; padding: 15px; border-radius: 4px;">
                        ${notes}
                    </div>
                </div>

                <div style="margin-top: 50px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #eee; padding-top: 20px;">
                    &copy; ${new Date().getFullYear()} PetApp. All rights reserved.
                </div>
            </div>
        </body>
        </html>
    `;
    
    // Create a Blob from the content
    const blob = new Blob([content], { type: 'text/html' });
    
    // Create a download link
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    
    // Sanitize filename
    const safePetName = petName.replace(/[^a-z0-9]/gi, '_').toLowerCase();
    const safeDate = date.replace(/[^a-z0-9]/gi, '_').toLowerCase();
    a.download = `vet_note_${safePetName}_${safeDate}.html`;
    
    // Trigger download
    document.body.appendChild(a);
    a.click();
    
    // Cleanup
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
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
    </div>
</div>

<div class="modal fade" id="clinicUnavailableModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-danger fw-bold">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Clinic Unavailable
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">This clinic is unavailable for booking right now.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-success rounded-pill px-4" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Appointment Modal -->
<div class="modal fade" id="cancelAppointmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Cancel Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this appointment? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form id="cancelAppointmentForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-danger">Yes, Cancel Appointment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Location Modal -->
<div class="modal fade" id="locationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header border-bottom-0 pb-0 bg-transparent">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-geo-alt-fill me-2 text-danger"></i>Select Location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-3">Choose a city to find clinics near you.</p>
                <div class="row g-2">
                    @foreach(['Bogo City', 'Carcar City', 'Cebu City', 'Danao City', 'Lapu-Lapu City', 'Mandaue City', 'Naga City', 'Talisay City', 'Toledo City'] as $city)
                        <div class="col-6">
                            <a href="{{ route('pet_owner.dashboard', ['location' => $city]) }}" 
                               class="btn w-100 p-3 rounded-4 d-flex align-items-center justify-content-center text-decoration-none border transition-all {{ $selectedLocation == $city ? 'btn-success text-white shadow' : 'btn-light text-dark bg-white' }}"
                               style="height: 60px;">
                                <span class="fw-bold">{{ $city }}</span>
                                @if($selectedLocation == $city)
                                    <i class="bi bi-check-circle-fill ms-2"></i>
                                @endif
                            </a>
                        </div>
                    @endforeach
                </div>
                
                @if($selectedLocation)
                    <div class="mt-4 pt-3 border-top">
                        <a href="{{ route('pet_owner.dashboard') }}" class="btn btn-outline-danger w-100 rounded-pill py-2">
                            <i class="bi bi-x-circle me-2"></i>Clear Location Filter
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Clinic Profile Modal -->
<div class="modal fade" id="clinicProfileModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
      <div class="modal-header bg-success text-white border-0 py-3">
        <h5 class="modal-title fw-bold d-flex align-items-center">
            <i class="bi bi-hospital-fill me-2 fs-4"></i>
            <span>Clinic Profile</span>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0 bg-light-subtle">
        <!-- Hero Section -->
        <div class="clinic-hero position-relative" style="height: 220px;">
            <div class="w-100 h-100 bg-success bg-opacity-10 d-flex align-items-center justify-content-center overflow-hidden">
                <i class="bi bi-hospital text-success opacity-10" style="font-size: 10rem; transform: rotate(-15deg);"></i>
            </div>
            <div class="position-absolute bottom-0 start-0 w-100 p-4 d-flex align-items-end" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                <div class="position-relative">
                    <img id="cpProfileImage" src="" class="rounded-4 border border-4 border-white shadow-lg object-fit-cover" style="width: 140px; height: 140px; margin-bottom: -60px; z-index: 2;">
                </div>
                <div class="text-white ms-3 mb-2 flex-grow-1">
                    <h2 id="cpClinicName" class="fw-bold mb-1 tracking-tight">Clinic Name</h2>
                    <div id="cpRatingStars" class="text-warning small d-flex align-items-center gap-1 fs-6"></div>
                </div>
            </div>
        </div>

        <div class="container-fluid p-4 pt-5 mt-4">
            <div class="row g-4">
                <!-- Left Column: Info & Hours -->
                <div class="col-md-7">
                    <!-- Bio Section -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-success text-uppercase small mb-3 letter-spacing-1">
                            <i class="bi bi-info-circle-fill me-2"></i>About the Clinic
                        </h6>
                        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                            <p id="cpDescription" class="text-dark mb-0 lh-base fst-italic opacity-75">No description available.</p>
                        </div>
                    </div>

                    <!-- Contact Details -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-success text-uppercase small mb-3 letter-spacing-1">
                            <i class="bi bi-person-lines-fill me-2"></i>Contact Information
                        </h6>
                        <div class="card border-0 shadow-sm rounded-4 p-1 bg-white overflow-hidden">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item border-0 py-3 bg-transparent">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-danger bg-opacity-10 text-danger rounded-3 p-2 me-3">
                                            <i class="bi bi-geo-alt-fill fs-5"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Location</small>
                                            <span id="cpAddress" class="text-dark fw-medium small">Address</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item border-0 py-3 bg-transparent border-top">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 me-3">
                                            <i class="bi bi-telephone-fill fs-5"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Phone Number</small>
                                            <span id="cpPhone" class="text-dark fw-medium small">Phone</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item border-0 py-3 bg-transparent border-top">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-info bg-opacity-10 text-info rounded-3 p-2 me-3">
                                            <i class="bi bi-envelope-fill fs-5"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Email Address</small>
                                            <span id="cpEmail" class="text-dark fw-medium small">Email</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Business Hours -->
                    <div>
                        <h6 class="fw-bold text-success text-uppercase small mb-3 letter-spacing-1">
                            <i class="bi bi-clock-fill me-2"></i>Business Hours
                        </h6>
                        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                            <div id="cpHours" class="text-dark fw-medium small"></div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Gallery -->
                <div class="col-md-5">
                    <h6 class="fw-bold text-success text-uppercase small mb-3 letter-spacing-1">
                        <i class="bi bi-images me-2"></i>Gallery
                    </h6>
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                        <div id="cpGallery" class="row g-2 align-content-start">
                            <!-- Images injected via JS -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer border-0 bg-light-subtle pb-4 justify-content-center">
        <button type="button" class="btn btn-light rounded-pill px-5 fw-bold text-secondary shadow-sm" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<style>
    .letter-spacing-1 { letter-spacing: 1px; }
    .tracking-tight { letter-spacing: -0.5px; }
    .hover-scale:hover {
        transform: scale(1.05);
    }
    .clinic-gallery-img {
        height: 100px; 
        width: 100%; 
        object-fit: cover; 
        cursor: pointer; 
        transition: all 0.3s ease;
    }
    .clinic-gallery-img:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2) !important;
        z-index: 5;
    }
    
    /* Dark Theme Support for Modal */
    body.dark-theme #clinicProfileModal .bg-white {
        background-color: #2a2a2a !important;
        border: 1px solid rgba(255,255,255,0.05) !important;
    }
    body.dark-theme #clinicProfileModal .text-dark {
        color: #e0e0e0 !important;
    }
    body.dark-theme #clinicProfileModal .list-group-item {
        border-color: rgba(255,255,255,0.05) !important;
    }
    body.dark-theme #clinicProfileModal .bg-light-subtle {
        background-color: #1e1e1e !important;
    }
    body.dark-theme #clinicProfileModal .btn-light {
        background-color: #333 !important;
        color: #e0e0e0 !important;
        border-color: #444 !important;
    }
</style>

@endsection

@section('styles')
@parent
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css">
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
    background: transparent; /* Transparent to remove white block */
    display: flex;
    justify-content: space-around;
    align-items: center;
    padding: 10px 0;
    z-index: 1050;
    pointer-events: none; /* Allow clicks to pass through empty space */
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
    pointer-events: auto; /* Enable clicks on buttons */
    
    /* Floating button style */
    background: transparent !important;
    padding: 6px 12px;
    border-radius: 12px;
    box-shadow: none !important;
    border: none !important;
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
    background: transparent;
    border-top: none;
}
body.dark-theme .mobile-bottom-nav a {
    color: #adb5bd;
    background: transparent !important;
    box-shadow: none !important;
    border: none !important;
}
body.dark-theme .mobile-bottom-nav a.active {
    color: #198754;
    background: transparent !important;
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
    width: 200px;
    height: 200px;
    position: relative;
    flex-shrink: 0;
}

/* All slide items stacked */
.slide-item {
    position: absolute;
    top: 0;
    left: 0;
    width: 200px;
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
body.dark-theme .mobile-app-title {
    color: #e0e0e0 !important;
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

/* Theme Toggler Desktop Styling */
@media (min-width: 768px) {
    .theme-section {
        margin-top: 1rem;
    }
    .theme-label-container {
        opacity: 0.85;
        font-size: 0.8rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }
    .theme-buttons-container {
        margin-top: 0.35rem;
        padding: 4px;
        background: rgba(0, 0, 0, 0.18);
        border-radius: 999px;
    }
    .theme-option {
        border-radius: 999px !important;
        border-width: 0 !important;
        font-size: 0.7rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding-top: 0.4rem;
        padding-bottom: 0.4rem;
        background-color: transparent;
        color: rgba(255, 255, 255, 0.85);
        opacity: 0.8;
        transition:
            background-color 0.2s ease,
            color 0.2s ease,
            box-shadow 0.2s ease,
            transform 0.2s ease,
            opacity 0.2s ease;
    }
    .theme-option:hover {
        opacity: 1;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
    }

    body:not(.dark-theme) .theme-option[data-theme="light"],
    body.dark-theme .theme-option[data-theme="dark"] {
        background-color: #ffffff !important;
        color: #198754 !important;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.35);
        opacity: 1;
    }

    body.dark-theme .theme-buttons-container {
        background: rgba(255, 255, 255, 0.06);
    }
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

/* Leaflet Routing Machine Dark Mode */
body.dark-theme .leaflet-routing-container {
    background-color: #2a2a2a !important;
    color: #e0e0e0 !important;
    border: 1px solid #444 !important;
    box-shadow: 0 4px 6px rgba(0,0,0,0.3) !important;
}

body.dark-theme .leaflet-routing-container h2,
body.dark-theme .leaflet-routing-container h3 {
    color: #e0e0e0 !important;
}

body.dark-theme .leaflet-routing-alt table tbody tr:hover {
    background-color: #3a3a3a !important;
    cursor: pointer;
}

body.dark-theme .leaflet-routing-alt tr {
    border-bottom: 1px solid #444;
}

body.dark-theme .leaflet-routing-icon {
    filter: invert(1) brightness(2);
}

/* Scrollbar for routing container */
body.dark-theme .leaflet-routing-container::-webkit-scrollbar {
    width: 8px;
}
body.dark-theme .leaflet-routing-container::-webkit-scrollbar-track {
    background: #1e1e1e;
}
body.dark-theme .leaflet-routing-container::-webkit-scrollbar-thumb {
    background: #555;
    border-radius: 4px;
}
</style>
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />

@endsection

@section('scripts')
@parent
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
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
                    const formattedPrice = service.price 
                        ? '₱' + parseFloat(service.price).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) 
                        : 'Ask for Price';

                    let availabilityBadge = '';
                    if (clinic.is_24_hours) {
                        availabilityBadge = '<span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1 rounded-pill me-2"><i class="bi bi-clock-history me-1"></i>24 Hours</span>';
                    } else if (clinic.is_active) {
                        availabilityBadge = '<span class="badge bg-success-subtle text-success border border-success px-2 py-1 rounded-pill me-2"><i class="bi bi-clock-fill me-1"></i>Open Now</span>';
                    } else {
                        availabilityBadge = '<span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1 rounded-pill me-2"><i class="bi bi-x-circle-fill me-1"></i>Closed</span>';
                    }

                    const hoursText = (!clinic.is_24_hours && clinic.formatted_opening_time && clinic.formatted_closing_time)
                        ? `<span class="text-muted small"><i class="bi bi-clock me-1"></i>${clinic.formatted_opening_time} - ${clinic.formatted_closing_time}</span>`
                        : '';

                    resultsHTML += `
                        <button type="button" class="list-group-item list-group-item-action border-0 border-bottom p-3" onclick="openBookingModalWithAvailability(${clinic.id}, ${service.id})" style="transition: background-color 0.2s;">
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

                                    <div class="d-flex align-items-center gap-1 mb-1">
                                        ${availabilityBadge}
                                        ${hoursText}
                                    </div>

                                    ${animals.length > 0 ? `
                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                        ${animals.map(a => `<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle" style="font-size: 0.65rem; font-weight: 500;">${a}</span>`).join('')}
                                    </div>` : ''}

                                    ${service.images && Array.isArray(service.images) && service.images.length > 0 ? `
                                        <button type="button" class="btn p-0 border-0 bg-transparent mt-2 service-image-preview" 
                                                data-images='${JSON.stringify(service.images)}'
                                                data-name="${service.name || service.service_name || 'Service'}">
                                            <div class="position-relative">
                                                <img src="${service.images[0]}" class="rounded border shadow-sm hover-scale" style="width: 50px; height: 50px; object-fit: cover; transition: transform 0.2s;">
                                                ${service.images.length > 1 ? `
                                                    <div class="position-absolute bottom-0 end-0 bg-dark bg-opacity-75 text-white px-1 rounded-start small" style="font-size: 0.6rem;">
                                                        <i class="bi bi-images me-1"></i>+${service.images.length - 1}
                                                    </div>
                                                ` : ''}
                                            </div>
                                        </button>` : ''}
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

window.openBookingModalWithAvailability = function(clinicId, serviceId) {
    const clinicsDataEl = document.getElementById('clinicsData');
    if (!clinicsDataEl) {
        return;
    }

    let clinics = [];
    try {
        clinics = JSON.parse(clinicsDataEl.dataset.clinics || '[]');
    } catch (e) {
        return;
    }

    const clinic = clinics.find(c => c.id == clinicId);
    if (!clinic) {
        return;
    }

    const isAvailable = (clinic.is_24_hours === true) || !!clinic.is_active;
    if (!isAvailable) {
        const unavailableModalEl = document.getElementById('clinicUnavailableModal');
        if (unavailableModalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const unavailableModal = new bootstrap.Modal(unavailableModalEl);
            unavailableModal.show();
        }
        return;
    }

    openBookingModal(clinicId, serviceId);
}

window.togglePaymentOptions = function() {
    const payOnline = document.getElementById('payOnline');
    const optionsDiv = document.getElementById('onlinePaymentOptions');
    const receiptInput = document.getElementById('paymentReceipt');

    if (payOnline && optionsDiv) {
        const isOnline = payOnline.checked;
        optionsDiv.style.display = isOnline ? 'block' : 'none';
        
        if (receiptInput) {
            if (isOnline) {
                receiptInput.setAttribute('required', 'required');
            } else {
                receiptInput.removeAttribute('required');
                receiptInput.value = ''; // Clear file
            }
        }
    }
}

window.openBookingModal = function(clinicId, serviceId) {
    // Inject Leaflet Routing Machine dependencies dynamically
    (function() {
        if (!document.querySelector('link[href*="leaflet-routing-machine"]')) {
            var link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = 'https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css';
            document.head.appendChild(link);
        }
        if (!document.querySelector('script[src*="leaflet-routing-machine"]')) {
            var script = document.createElement('script');
            script.src = 'https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js';
            document.head.appendChild(script);
        }
    })();
    
    // Save state for persistence
    localStorage.setItem('openBookingModal', JSON.stringify({clinicId, serviceId}));

    const userAddress = "{{ $owner->address ?? '' }}";
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
            const s = c.services.find(srv => srv.id == serviceId);
            if(s) {
                targetService = s;
                targetClinic = c;
            }
        }
    });

    if (!targetService) {
        console.error('Target service not found for ID:', serviceId);
        alert('Service details could not be loaded. Please try again.');
        return;
    }
    
    const bookModalEl = document.getElementById('bookModal');
    if(bookModalEl) {
        // Populate Service Info
        const nameEl = document.getElementById('bookingServiceName');
        const descEl = document.getElementById('bookingServiceDesc');
        const imagesEl = document.getElementById('bookingServiceImages');
        
        if(nameEl) nameEl.textContent = targetService.name || targetService.service_name || 'Service';
        if(descEl) descEl.textContent = targetService.description || '';
        
        if(imagesEl) {
            imagesEl.innerHTML = '';
            if(targetService.images && Array.isArray(targetService.images) && targetService.images.length > 0) {
                targetService.images.forEach(img => {
                    const imgTag = document.createElement('img');
                    imgTag.src = img;
                    imgTag.className = 'rounded shadow-sm border object-fit-cover flex-shrink-0 cursor-pointer hover-scale';
                    imgTag.style.width = '100px';
                    imgTag.style.height = '100px';
                    imgTag.style.transition = 'transform 0.2s';
                    imgTag.onclick = () => {
                        const previewModal = document.getElementById('serviceImageModal');
                        const previewImg = document.getElementById('serviceImageModalImg');
                        if(previewModal && previewImg) {
                            previewImg.src = img;
                            new bootstrap.Modal(previewModal).show();
                        }
                    };
                    imagesEl.appendChild(imgTag);
                });
            } else {
                imagesEl.classList.add('d-none');
            }
        }

        // Close search modal if open
        const searchModalEl = document.getElementById('searchModal');
        if (searchModalEl) {
            const searchModal = bootstrap.Modal.getInstance(searchModalEl);
            if (searchModal) searchModal.hide();
        }

        // Populate Reviews
        const reviewsContainer = document.getElementById('bookingClinicReviews');
        if(reviewsContainer) {
            if(targetClinic.reviews && targetClinic.reviews.length > 0) {
                reviewsContainer.innerHTML = targetClinic.reviews.map(r => {
                    const imagesHtml = (r.images && Array.isArray(r.images) && r.images.length > 0) ? 
                        `<div class="d-flex gap-2 mt-2 overflow-x-auto pb-1">
                            ${r.images.map(img => `<a href="/storage/reviews/${img}" target="_blank"><img src="/storage/reviews/${img}" class="rounded border shadow-sm" style="width: 60px; height: 60px; object-fit: cover;"></a>`).join('')}
                         </div>` 
                        : '';
                        
                    return `
                    <div class="list-group-item p-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold text-dark small">${r.user_name || 'Anonymous'}</span>
                            <div class="text-warning small">
                                ${'<i class="bi bi-star-fill"></i>'.repeat(r.rating)}${'<i class="bi bi-star"></i>'.repeat(5-r.rating)}
                            </div>
                        </div>
                        <p class="mb-1 text-muted small fst-italic">"${r.review || ''}"</p>
                        ${imagesHtml}
                        <small class="text-secondary opacity-75" style="font-size: 0.75rem;">${r.created_at}</small>
                    </div>
                    `;
                }).join('');
            } else {
                reviewsContainer.innerHTML = '<div class="p-3 text-center text-muted small">No reviews available for this clinic.</div>';
            }
        }

        // QR Code Setup
        const qrCodeImg = document.getElementById('clinicQrCode');
        const noQrMsg = document.getElementById('noQrMessage');
        if (qrCodeImg && noQrMsg) {
            if (targetClinic.qr_code) {
                qrCodeImg.src = "/storage/clinics/qr_codes/" + targetClinic.qr_code;
                qrCodeImg.style.display = 'block';
                noQrMsg.style.display = 'none';
            } else {
                qrCodeImg.style.display = 'none';
                noQrMsg.style.display = 'block';
            }
        }

        // Service Location Preference Setup
        const serviceLocationSection = document.getElementById('serviceLocationSection');
        const clinicOnlyNotice = document.getElementById('serviceLocationClinicOnly');
        const homeOnlyNotice = document.getElementById('serviceLocationHomeOnly');
        const bothOptions = document.getElementById('serviceLocationBothOptions');
        const homeInfo = document.getElementById('serviceHomeInfo');
        const locationHiddenInput = document.getElementById('bookingServiceLocation');
        const clinicRadio = document.getElementById('bookingLocationClinic');
        const homeRadio = document.getElementById('bookingLocationHome');
        const payClinicLabel = document.getElementById('payClinicLabel');
        const defaultPayClinicText = 'Pay at Clinic';
        const homePayClinicText = 'Pay After Service';

        const manualDateTimeGroup = bookModalEl.querySelector('[data-booking-manual-datetime]');
        const slotSelectGroup = bookModalEl.querySelector('[data-booking-slot-select-group]');
        const slotButtonsContainer = bookModalEl.querySelector('[data-booking-slot-buttons]');
        const appointmentInput = bookModalEl.querySelector('input[name="appointment_date"]');

        const applySlotUi = (effectiveLocation) => {
            if (!appointmentInput || !manualDateTimeGroup || !slotSelectGroup || !slotButtonsContainer) return;

            slotButtonsContainer.innerHTML = '';
            slotSelectGroup.classList.add('d-none');
            manualDateTimeGroup.style.display = '';

            const isHomeLocation = effectiveLocation === 'home';
            const slots = (targetService && Array.isArray(targetService.slots)) ? targetService.slots : [];

            if (isHomeLocation && slots.length) {
                const normalizeLabel = (slot) => {
                    try {
                        const d = new Date(slot);
                        if (!isNaN(d.getTime())) {
                            return d.toLocaleString();
                        }
                    } catch (e) {}
                    return slot;
                };

                slots.forEach(slot => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'btn btn-outline-success btn-sm';
                    btn.textContent = normalizeLabel(slot);
                    btn.dataset.slotValue = slot;

                    btn.addEventListener('click', () => {
                        const allButtons = slotButtonsContainer.querySelectorAll('button');
                        allButtons.forEach(b => b.classList.remove('active'));
                        btn.classList.add('active');
                        appointmentInput.value = slot;
                    });

                    slotButtonsContainer.appendChild(btn);
                });

                slotSelectGroup.classList.remove('d-none');
                manualDateTimeGroup.style.display = 'none';
            } else {
                manualDateTimeGroup.style.display = '';
                slotSelectGroup.classList.add('d-none');
            }
        };

        if (serviceLocationSection && locationHiddenInput) {
            // Reset visibility
            serviceLocationSection.style.display = 'none';
            if (clinicOnlyNotice) clinicOnlyNotice.classList.add('d-none');
            if (homeOnlyNotice) homeOnlyNotice.classList.add('d-none');
            if (bothOptions) bothOptions.classList.add('d-none');
            if (homeInfo) homeInfo.classList.add('d-none');

            const locationType = (targetService.location_type || 'clinic').toLowerCase();

            // Prepare editable home info inputs with owner's defaults
            let ownerAddress = '';
            let ownerPhone = '';
            if (bookModalEl) {
                ownerAddress = (bookModalEl.dataset.ownerAddress || '').trim();
                ownerPhone = (bookModalEl.dataset.ownerPhone || '').trim();
            }
            if (homeInfo) {
                const addrInput = homeInfo.querySelector('[data-home-address-input]');
                const phoneInput = homeInfo.querySelector('[data-home-phone-input]');
                if (addrInput && !addrInput.value) addrInput.value = ownerAddress;
                if (phoneInput && !phoneInput.value) phoneInput.value = ownerPhone;
            }

            if (locationType === 'clinic') {
                serviceLocationSection.style.display = '';
                if (clinicOnlyNotice) clinicOnlyNotice.classList.remove('d-none');
                locationHiddenInput.value = 'clinic';
                if (homeInfo) homeInfo.classList.add('d-none');
                if (payClinicLabel) payClinicLabel.textContent = defaultPayClinicText;
                applySlotUi('clinic');
            } else if (locationType === 'home') {
                serviceLocationSection.style.display = '';
                if (homeOnlyNotice) homeOnlyNotice.classList.remove('d-none');
                if (homeInfo) homeInfo.classList.remove('d-none');
                locationHiddenInput.value = 'home';
                if (payClinicLabel) payClinicLabel.textContent = homePayClinicText;
                applySlotUi('home');
            } else if (locationType === 'both') {
                serviceLocationSection.style.display = '';
                if (bothOptions) bothOptions.classList.remove('d-none');
                // Default to clinic
                if (clinicRadio) clinicRadio.checked = true;
                if (homeRadio) homeRadio.checked = false;
                locationHiddenInput.value = 'clinic';
                if (payClinicLabel) payClinicLabel.textContent = defaultPayClinicText;
                applySlotUi('clinic');

                const updateHomeVisibility = () => {
                    if (!homeInfo) return;
                    if (homeRadio && homeRadio.checked) {
                        homeInfo.classList.remove('d-none');
                        locationHiddenInput.value = 'home';
                        if (payClinicLabel) payClinicLabel.textContent = homePayClinicText;
                        applySlotUi('home');
                    } else {
                        homeInfo.classList.add('d-none');
                        locationHiddenInput.value = 'clinic';
                        if (payClinicLabel) payClinicLabel.textContent = defaultPayClinicText;
                        applySlotUi('clinic');
                    }
                };

                if (clinicRadio) clinicRadio.addEventListener('change', updateHomeVisibility);
                if (homeRadio) homeRadio.addEventListener('change', updateHomeVisibility);
                updateHomeVisibility();
            }
        }

        const petCheckboxes = bookModalEl.querySelectorAll('.pet-checkbox');
        
        // Get active category filter if any
        const activeAnimalBtn = document.querySelector('.animal-btn.active');
        const activeCategory = activeAnimalBtn ? (activeAnimalBtn.dataset.animal || '').trim().toLowerCase() : null;

        petCheckboxes.forEach(chk => {
            const wrapper = chk.closest('.form-check');
            // Reset state first
            if(wrapper) wrapper.style.display = '';
            chk.disabled = false;

            const petSpecies = (chk.dataset.species || '').trim().toLowerCase();
            let isAllowed = true;

            // 1. Service Constraint
            if (targetService && targetService.animals && targetService.animals.length > 0) {
                const allowedAnimals = targetService.animals
                    .map(a => (a || '').toLowerCase().trim())
                    .filter(a => a !== '');

                // Recognized standard animals (used for main categories)
                const standardAnimals = [
                    'dogs','cats','cows','sheep','goats','pigs','horses','rabbits',
                    'chickens','ducks','turkeys','geese','parrots',
                    'hamsters','guinea pigs','mice','rats'
                ];

                const hasOtherCategory = allowedAnimals.includes('other');
                const isOtherTypeService = allowedAnimals.length > 0 &&
                    allowedAnimals.every(a => !standardAnimals.includes(a));

                // If the service is truly "other" (no standard animals) or explicitly has "Other",
                // don't block pets by species – allow other species pets to be selected.
                if (!isOtherTypeService && !hasOtherCategory) {
                    const match = allowedAnimals.some(allowed =>
                        petSpecies.includes(allowed) || allowed.includes(petSpecies)
                    );
                    if (!match) isAllowed = false;
                }
            }

            // 2. Category Constraint (from user selection)
            //    Apply only when the service itself is animal-specific and not an "other-type" service.
            if (activeCategory && isAllowed && targetService && targetService.animals && targetService.animals.length > 0) {
                const allowedAnimals = targetService.animals
                    .map(a => (a || '').toLowerCase().trim())
                    .filter(a => a !== '');
                const standardAnimals = [
                    'dogs','cats','cows','sheep','goats','pigs','horses','rabbits',
                    'chickens','ducks','turkeys','geese','parrots',
                    'hamsters','guinea pigs','mice','rats'
                ];
                const hasOtherCategory = allowedAnimals.includes('other');
                const isOtherTypeService = allowedAnimals.length > 0 &&
                    allowedAnimals.every(a => !standardAnimals.includes(a));

                if (!isOtherTypeService && !hasOtherCategory) {
                    if (!petSpecies.includes(activeCategory) && !activeCategory.includes(petSpecies)) {
                        isAllowed = false;
                    }
                }
            }
            
            // Apply visibility/disabled state
            if (!isAllowed) {
                if(wrapper) wrapper.style.display = 'none';
                chk.disabled = true;
                chk.checked = false;
            }
        });
        
        // Price Calculation Logic
        const basePrice = parseFloat(targetService.price || 0);
        console.log('Base Price:', basePrice);

        const updatePrice = () => {
            const checkedPets = Array.from(bookModalEl.querySelectorAll('.pet-checkbox:checked'));
            const countForCalc = checkedPets.length > 0 ? checkedPets.length : 1;
            
            const totalPrice = basePrice * countForCalc;
            let downpayment = totalPrice * 0.5;
            
            if (downpayment < 50) downpayment = 50;
            if (totalPrice > 0 && downpayment > totalPrice) downpayment = totalPrice;
            
            const optionHalfPrice = document.getElementById('optionHalfPrice');
            const optionFullPrice = document.getElementById('optionFullPrice');
            if(optionHalfPrice) optionHalfPrice.textContent = '₱' + downpayment.toLocaleString('en-US', {minimumFractionDigits: 2});
            if(optionFullPrice) optionFullPrice.textContent = '₱' + totalPrice.toLocaleString('en-US', {minimumFractionDigits: 2});

            const servicePriceEl = document.getElementById('bookingServicePrice');
            const petCountEl = document.getElementById('bookingPetCount');
            const totalPriceEl = document.getElementById('bookingTotalPrice');

            if(servicePriceEl) servicePriceEl.textContent = '₱' + basePrice.toLocaleString('en-US', {minimumFractionDigits: 2});
            if(petCountEl) petCountEl.textContent = checkedPets.length;
            if(totalPriceEl) totalPriceEl.textContent = '₱' + totalPrice.toLocaleString('en-US', {minimumFractionDigits: 2});
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
        
        // Map Integration
        const mapContainer = document.getElementById('bookingClinicMap');
        if(mapContainer) {
            mapContainer.innerHTML = '<div class="d-flex justify-content-center align-items-center h-100 bg-light text-secondary fw-bold"><div class="spinner-border text-success me-2" role="status"></div> Loading Map...</div>';
        } else {
            console.error("Map container 'bookingClinicMap' not found in DOM.");
        }
        
        if (targetClinic && targetClinic.address) {
                const initMap = () => {
                 console.log("Initializing map for:", targetClinic.address);
                 if (typeof L === 'undefined') {
                     console.error("Leaflet (L) is undefined.");
                     if(mapContainer) mapContainer.innerHTML = '<div class="d-flex justify-content-center align-items-center h-100 bg-light text-danger">Map resources failed to load. Please refresh.</div>';
                     return;
                 }
                 
                 if (window.clinicMap) {
                     window.clinicMap.remove();
                     window.clinicMap = null;
                 }
                 
                // Initialize map
                try {
                    var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap contributors'
                    });

                    window.clinicMap = L.map('bookingClinicMap', {
                        center: [14.5995, 120.9842],
                        zoom: 13,
                        layers: [osmLayer]
                    });
                     
                    // 1. Geocode Clinic Address First (Priority)
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=1&countrycodes=ph&q=${encodeURIComponent(targetClinic.address)}`)
                         .then(res => res.json())
                         .then(data => {
                             if (data.length > 0) {
                                 var destLatLng = L.latLng(data[0].lat, data[0].lon);
                                 
                                 // Center map on clinic
                                 window.clinicMap.setView(destLatLng, 15);
                                 L.marker(destLatLng).addTo(window.clinicMap).bindPopup(targetClinic.clinic_name).openPopup();
                                 
                                 // Define Routing Function
                                 const drawRoute = (startLatLng, label) => {
                                      // Custom Icon for User (Green Marker)
                                      var userIcon = L.icon({
                                          iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                                          shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                                          iconSize: [25, 41],
                                          iconAnchor: [12, 41],
                                          popupAnchor: [1, -34],
                                          shadowSize: [41, 41]
                                      });

                                      L.marker(startLatLng, {icon: userIcon}).addTo(window.clinicMap).bindPopup(label).openPopup();
                                      
                                      if (window.clinicRouteControl) window.clinicMap.removeControl(window.clinicRouteControl);
                                      
                                      if (typeof L.Routing !== 'undefined') {
                                          window.clinicRouteControl = L.Routing.control({
                                              waypoints: [startLatLng, destLatLng],
                                              routeWhileDragging: false,
                                              lineOptions: { styles: [{color: '#0d6efd', opacity: 0.7, weight: 5}] },
                                              addWaypoints: false,
                                              draggableWaypoints: false,
                                              fitSelectedRoutes: true,
                                              showAlternatives: false,
                                              collapsible: true,
                                              show: true,
                                              createMarker: function() { return null; }
                                          }).addTo(window.clinicMap);
                                      } else {
                                          console.warn("L.Routing undefined. Retrying in 1s...");
                                          setTimeout(() => drawRoute(startLatLng, label), 1000);
                                      }
                                 };

                                 // 2. Determine Start Point
                                 if (userAddress) {
                                     // Geocode User Address
                                     console.log("Geocoding User Address:", userAddress);
                                     fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(userAddress)}&countrycodes=ph`)
                                        .then(r => r.json())
                                        .then(udata => {
                                            if (udata.length > 0) {
                                                const userLatLng = L.latLng(udata[0].lat, udata[0].lon);
                                                drawRoute(userLatLng, "Your Location (Saved)");
                                            } else {
                                                console.warn("User address not found, trying GPS.");
                                                window.clinicMap.locate({ setView: false, maxZoom: 16 });
                                            }
                                        })
                                        .catch(e => {
                                            console.error("User geocode error:", e);
                                            window.clinicMap.locate({ setView: false, maxZoom: 16 });
                                        });
                                 } else {
                                     // Fallback to GPS
                                     window.clinicMap.locate({ setView: false, maxZoom: 16 });
                                 }
                                 
                                 // GPS Fallback Handlers
                                 window.clinicMap.on('locationfound', function (e) {
                                    // Only use GPS if we haven't already routed via address (or if address failed)
                                    drawRoute(e.latlng, "Your Location (GPS)");
                                 });
                                 
                                 window.clinicMap.on('locationerror', function (e) {
                                      console.warn("User location not found:", e.message);
                                 });
                                 
                             } else {
                                 console.warn("Clinic address not found:", targetClinic.address);
                                 L.popup()
                                    .setLatLng(window.clinicMap.getCenter())
                                    .setContent("Clinic location could not be found.")
                                    .openOn(window.clinicMap);
                             }
                         })
                        .catch(err => {
                            console.error("Geocoding error:", err);
                            if (window.clinicMap && typeof L !== 'undefined') {
                                L.popup()
                                    .setLatLng(window.clinicMap.getCenter())
                                    .setContent("Clinic location could not be found.")
                                    .openOn(window.clinicMap);
                            } else if (mapContainer) {
                                mapContainer.innerHTML = '<div class="d-flex justify-content-center align-items-center h-100 bg-light text-muted">Clinic location could not be found.</div>';
                            }
                        });
                 } catch (err) {
                     console.error("Map initialization error:", err);
                     if(mapContainer) mapContainer.innerHTML = '<div class="d-flex justify-content-center align-items-center h-100 bg-light text-danger">Error initializing map.</div>';
                 }

                 setTimeout(() => {
                     window.clinicMap.invalidateSize();
                 }, 300);
                };

                // Remove existing listeners to avoid duplicates if any
                // Note: {once:true} handles self-removal, but if we close and reopen, we add a new one.
                bookModalEl.addEventListener('shown.bs.modal', initMap, {once:true});
                
                // Fallback: If modal is already shown (edge case) or event missed
                if (bookModalEl.classList.contains('show')) {
                    initMap();
                }
                
        } else {
            console.warn('Clinic address missing or targetClinic not found.');
            if(mapContainer) {
                mapContainer.innerHTML = '<div class="d-flex justify-content-center align-items-center h-100 bg-light text-muted">Clinic address not available for map.</div>';
            }
        }
        
        // Use existing instance if available to avoid conflicts
        let bookModal = bootstrap.Modal.getInstance(bookModalEl);
        if (!bookModal) {
            bookModal = new bootstrap.Modal(bookModalEl);
        }
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

    // Reset UI Elements
    payBtn.classList.add('d-none');
    const receiptContainer = document.getElementById('apptDetailsReceiptContainer');
    const uploadForm = document.getElementById('uploadReceiptForm');
    const receiptPreview = document.getElementById('receiptPreview');
    const receiptLink = document.getElementById('receiptLink');
    const qrContainer = document.getElementById('apptDetailsQrContainer');
    const qrImage = document.getElementById('apptDetailsQrImage');

    if(qrContainer) qrContainer.classList.add('d-none');
    if(receiptContainer) receiptContainer.classList.add('d-none');

    // Payment Status Badge Logic
    if(data.payment_status === 'downpayment_paid' || data.payment_status === 'paid') {
        paymentStatusContainer.innerHTML = '<span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2"><i class="bi bi-shield-fill-check me-1"></i> Paid</span>';
    } else {
         paymentStatusContainer.innerHTML = '<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-2">Unpaid</span>';
    }

    // Online Payment Flow (Manual QR + Receipt Upload)
    if (data.payment_method === 'online' && data.status === 'approved') {
        
        // 1. Show QR Code if available
        if (qrContainer && qrImage && data.qr_code) {
             qrImage.src = data.qr_code;
             qrContainer.classList.remove('d-none');
        }

        // 2. Show Receipt Upload UI
        if (receiptContainer) {
            receiptContainer.classList.remove('d-none');
            uploadForm.action = data.uploadReceiptUrl;

            if (data.payment_receipt) {
                // Receipt already uploaded
                uploadForm.classList.add('d-none');
                receiptPreview.classList.remove('d-none');
                receiptLink.href = data.payment_receipt;
                
                // Update status badge to Verification Pending
                paymentStatusContainer.innerHTML = '<span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3 py-2"><i class="bi bi-hourglass-split me-1"></i> Verification Pending</span>';
            } else {
                // Need to upload receipt
                uploadForm.classList.remove('d-none');
                receiptPreview.classList.add('d-none');
            }
        }

    } else if (data.payment_status === 'unpaid' && data.status === 'approved' && data.payment_method !== 'online') {
         // Legacy/Other payment methods logic if needed (e.g. Pay at Clinic doesn't need a button)
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
    const bookModalEl = document.getElementById('bookModal');
    const bookModal = bookModalEl ? new bootstrap.Modal(bookModalEl) : null;
    const modalClinicId = document.getElementById('modalClinicId');

    // REOPEN MODAL ON REFRESH IF STATE EXISTS
    const savedModalState = localStorage.getItem('openBookingModal');
    if (savedModalState) {
        try {
            const { clinicId, serviceId } = JSON.parse(savedModalState);
            if (clinicId && serviceId) {
                // Short delay to ensure DOM and libraries are ready
                setTimeout(() => {
                    window.openBookingModal(clinicId, serviceId);
                    if(bookModal) bookModal.show();
                }, 500);
            }
        } catch(e) {
            console.error('Failed to restore modal state:', e);
            localStorage.removeItem('openBookingModal');
        }
    }

    animalButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            animalButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const animalName = (btn.dataset.animal || '').trim().toLowerCase();

            const locationInput = document.querySelector('input[name="location"]');
            const selectedLocation = locationInput ? locationInput.value.trim() : '';

            const filtered = (clinicsData || []).filter(c => {
                const matchesAnimal = Array.isArray(c.specializations) &&
                    c.specializations.some(a => (a || '').trim().toLowerCase() === animalName);
                
                const matchesLocation = !selectedLocation || (() => {
                    // Remove "City" from the selected location to match broader address formats
                    // e.g., "Lapu-Lapu City" matches "Lapu-Lapu"
                    const searchTerm = selectedLocation.toLowerCase().replace(/\s*city$/i, '').trim();
                    const address = (c.address || '').toLowerCase();
                    return address.includes(searchTerm);
                })();
                
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
                    card.style.cursor = 'pointer';
                    
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

                    const selectedAnimal = animalName;
                    const filteredServices = (clinic.services && Array.isArray(clinic.services))
                        ? clinic.services.filter(s => {
                            if (s.is_available === false) {
                                return false;
                            }
                            const animals = (s.animals || []).map(a => (a || '').toString().toLowerCase().trim());
                            if (!animals.length) {
                                return true;
                            }
                            if (!selectedAnimal) {
                                return true;
                            }
                            const selected = selectedAnimal.toLowerCase();
                            return animals.some(an => an.includes(selected) || selected.includes(an));
                        })
                        : [];

                    card.innerHTML = `
                        <div class="card-body p-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="clinic-profile-trigger" data-id="${clinic.id}" style="cursor: pointer;">
                                            <img src="${clinic.image_url ?? '/images/default_clinic.png'}"
                                                 alt="${clinic.clinic_name}"
                                                 class="me-3 rounded-4 shadow-sm object-fit-cover hover-scale"
                                                 style="width:90px;height:90px; transition: transform 0.2s;">
                                        </div>
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
                                            <button type="button" class="btn btn-link btn-sm text-decoration-none p-0 view-reviews-btn mt-1" data-id="${clinic.id}" style="font-size: 0.8rem;">
                                                View Reviews
                                            </button>
                                        </div>
                                    </div>

                                    ${!clinic.is_24_hours && clinic.formatted_opening_time ? 
                                      `<div class="d-inline-block bg-light px-2 py-1 rounded small text-secondary border mt-1">
                                         <i class="bi bi-clock me-1"></i> ${clinic.formatted_opening_time} - ${clinic.formatted_closing_time}
                                       </div>` : ''}
                                </div>
                            </div>
                            
                            <hr class="my-3 text-muted opacity-25">

                             <div class="text-center small fw-bold ${bookingDisabled ? 'text-muted' : 'text-muted'}">
                                 <i class="bi bi-chevron-down me-1"></i> ${bookingDisabled ? 'This clinic is unavailable. You can still view services.' : 'Tap to View Services'}
                             </div>

                            <div class="services-container d-none mt-3">
                                <p class="small text-muted fw-bold text-uppercase mb-2 ms-1">Select a Service:</p>
                                ${
                                    filteredServices && filteredServices.length
                                    ? `
                                        <div class="d-grid gap-2 mb-3">
                                            ${filteredServices.map(s => `
                                                <div class="service-item p-3 rounded-3 mb-1 ${(bookingDisabled || s.is_available === false) ? 'service-disabled' : ''}" data-id="${s.id}">
                                                    <div class="d-flex align-items-start">
                                                        <div class="flex-grow-1">
                                                            <div class="fw-bold text-dark">${s.name ?? s.service_name ?? 'Unnamed Service'}</div>
                                                            ${s.description ? `<div class="small text-muted mt-1">${s.description}</div>` : ''}
                                                            ${s.animals && s.animals.length ? `
                                                                <div class="mt-2">
                                                                    ${s.animals.map(animal => `<span class="badge bg-secondary-subtle text-secondary border border-secondary me-1" style="font-size: 0.75rem;">${animal.trim()}</span>`).join('')}
                                                                </div>
                                                            ` : ''}
                                                        </div>
                                                        <div class="d-flex flex-column align-items-end ms-3" style="min-width: 90px;">
                                                            <div class="fw-bold fs-5 ${bookingDisabled ? 'text-muted' : 'text-success'}">
                                                                ${s.price ? `₱${parseFloat(s.price).toFixed(2)}` : ''}
                                                            </div>
                                                            ${s.images && Array.isArray(s.images) && s.images.length ? `
                                                                <button type="button" class="btn p-0 border-0 bg-transparent mt-2 service-image-preview" 
                                                                        data-images='${JSON.stringify(s.images)}'
                                                                        data-name="${s.name ?? s.service_name ?? 'Service'}">
                                                                    <div class="position-relative">
                                                                        <img src="${s.images[0]}" class="rounded border shadow-sm hover-scale" style="width: 72px; height: 72px; object-fit: cover; transition: transform 0.2s;">
                                                                        ${s.images.length > 1 ? `
                                                                            <div class="position-absolute bottom-0 end-0 bg-dark bg-opacity-75 text-white px-1 rounded-start small" style="font-size: 0.7rem;">
                                                                                <i class="bi bi-images me-1"></i>+${s.images.length - 1}
                                                                            </div>
                                                                        ` : ''}
                                                                    </div>
                                                                </button>
                                                            ` : ''}
                                                        </div>
                                                    </div>
                                                </div>
                                            `).join('')}
                                        </div>
                                        ${bookingDisabled 
                                            ? '<div class="alert alert-danger border-0 bg-danger-subtle text-danger small text-center fw-bold"><i class="bi bi-exclamation-circle me-2"></i>This clinic is unavailable for booking right now.</div>' 
                                            : `<button type="button" class="btn btn-success w-100 py-2 rounded-3 fw-bold shadow-sm book-btn d-none" data-id="${clinic.id}"><i class="bi bi-calendar-check me-2"></i>Book Selected Service</button>`
                                        }
                                    `
                                    : `<div class="alert alert-secondary small text-center">No services listed for <span class="text-capitalize">${selectedAnimal}</span> at this clinic.</div>`
                                }
                            </div>
                        </div>
                    `;
                    clinicsModalBody.appendChild(card);

                    // Add listener for Clinic Profile Modal
                    const profileTrigger = card.querySelector('.clinic-profile-trigger');
                    if(profileTrigger) {
                        profileTrigger.addEventListener('click', (e) => {
                            e.stopPropagation();
                            const cId = profileTrigger.dataset.id;
                            const clinic = clinicsData.find(c => c.id == cId);
                            if(clinic) {
                                document.getElementById('cpClinicName').textContent = clinic.clinic_name;
                                document.getElementById('cpProfileImage').src = clinic.image_url || '/images/default_clinic.png';
                                document.getElementById('cpAddress').innerHTML = `<i class="bi bi-geo-alt-fill text-danger me-2"></i>${clinic.address || 'N/A'}`;
                                document.getElementById('cpPhone').innerHTML = `<i class="bi bi-telephone-fill text-primary me-2"></i>${clinic.phone || 'N/A'}`;
                                document.getElementById('cpEmail').innerHTML = `<i class="bi bi-envelope-fill text-info me-2"></i>${clinic.email || 'N/A'}`;
                                
                                // Description (Bio)
                                const bioContainer = document.getElementById('cpDescription');
                                if(bioContainer) {
                                    bioContainer.textContent = clinic.description || 'No description available.';
                                }

                                // Rating
                                const rating = Math.round(clinic.avg_rating || 0);
                                document.getElementById('cpRatingStars').innerHTML = 
                                    '<i class="bi bi-star-fill"></i>'.repeat(rating) + 
                                    '<i class="bi bi-star"></i>'.repeat(5-rating) + 
                                    ` <span class="ms-1">${rating}.0</span>`;

                                // Hours
                                const hoursContainer = document.getElementById('cpHours');
                                if(clinic.is_24_hours) {
                                    hoursContainer.innerHTML = '<div class="d-flex align-items-center text-primary fw-bold"><i class="bi bi-clock-history me-2"></i>24 Hours / 7 Days</div>';
                                } else {
                                    hoursContainer.innerHTML = `
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Opening Time:</span>
                                            <span class="fw-bold">${clinic.formatted_opening_time || 'N/A'}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Closing Time:</span>
                                            <span class="fw-bold">${clinic.formatted_closing_time || 'N/A'}</span>
                                        </div>
                                    `;
                                }

                                // Gallery (using clinic gallery)
                                const gallery = document.getElementById('cpGallery');
                                gallery.innerHTML = '';
                                if(clinic.gallery && Array.isArray(clinic.gallery) && clinic.gallery.length > 0) {
                                    clinic.gallery.forEach(img => {
                                        const col = document.createElement('div');
                                        col.className = 'col-4 col-sm-4';
                                        col.innerHTML = `<img src="${img}" class="img-fluid rounded-3 shadow-sm border clinic-gallery-img" onclick="window.open('${img}', '_blank')">`;
                                        gallery.appendChild(col);
                                    });
                                } else {
                                    gallery.innerHTML = `
                                        <div class="col-12 text-center py-4">
                                            <i class="bi bi-images text-muted opacity-25 fs-1 d-block mb-2"></i>
                                            <span class="text-muted small fst-italic">No gallery images available.</span>
                                        </div>
                                    `;
                                }

                                const profileModal = new bootstrap.Modal(document.getElementById('clinicProfileModal'));
                                profileModal.show();
                            }
                        });
                    }

                    // Add Review Button Listener
                    const reviewBtn = card.querySelector('.view-reviews-btn');
                    if(reviewBtn) {
                        reviewBtn.addEventListener('click', (e) => {
                            e.stopPropagation();
                            
                            const cId = reviewBtn.dataset.id;
                            const cData = clinicsData.find(c => c.id == cId);
                            
                            if(cData) {
                                const modalEl = document.getElementById('viewReviewsModal');
                                const modalBody = document.getElementById('viewReviewsBody');
                                const modalTitle = document.getElementById('viewReviewsTitle');
                                
                                if(modalEl && modalBody) {
                                    modalTitle.textContent = `Reviews for ${cData.clinic_name}`;
                                    
                                    if(cData.reviews && cData.reviews.length > 0) {
                                        modalBody.innerHTML = cData.reviews.map(r => {
                                            const imagesHtml = (r.images && Array.isArray(r.images) && r.images.length > 0) ? 
                                                 `<div class="d-flex gap-2 mt-2 overflow-x-auto pb-1">
                                                     ${r.images.map(img => `<a href="/storage/reviews/${img}" target="_blank"><img src="/storage/reviews/${img}" class="rounded border shadow-sm" style="width: 80px; height: 80px; object-fit: cover;"></a>`).join('')}
                                                  </div>` 
                                                 : '';
                                                 
                                            return `
                                                <div class="card mb-3 border-0 shadow-sm bg-light-subtle">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <div class="d-flex align-items-center">
                                                                <div class="bg-secondary-subtle rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                                    <i class="bi bi-person-fill text-secondary"></i>
                                                                </div>
                                                                <div>
                                                                    <h6 class="mb-0 fw-bold small">${r.user_name || 'Anonymous'}</h6>
                                                                    <small class="text-muted" style="font-size: 0.7rem;">${r.created_at}</small>
                                                                </div>
                                                            </div>
                                                            <div class="text-warning small">
                                                                ${'<i class="bi bi-star-fill"></i>'.repeat(r.rating)}${'<i class="bi bi-star"></i>'.repeat(5-r.rating)}
                                                            </div>
                                                        </div>
                                                        <p class="mb-2 text-dark small">"${r.review || ''}"</p>
                                                        ${imagesHtml}
                                                    </div>
                                                </div>
                                            `;
                                        }).join('');
                                    } else {
                                        modalBody.innerHTML = `
                                            <div class="text-center py-5 text-muted">
                                                <i class="bi bi-chat-square-text fs-1 mb-3 d-block opacity-25"></i>
                                                <p>No reviews yet for this clinic.</p>
                                            </div>
                                        `;
                                    }
                                    
                                    const bsModal = new bootstrap.Modal(modalEl);
                                    bsModal.show();
                                }
                            }
                        });
                    }

                    const previewButtons = card.querySelectorAll('.service-image-preview');
                    previewButtons.forEach(btn => {
                        btn.addEventListener('click', (e) => {
                            e.stopPropagation();
                            const images = JSON.parse(btn.dataset.images || '[]');
                            const serviceName = btn.dataset.name || 'Service';
                            
                            if (!images.length) return;
                            
                            const modalEl = document.getElementById('serviceImageModal');
                            const imgEl = document.getElementById('serviceImageModalImg');
                            const titleEl = document.getElementById('serviceImageModalTitle');
                            const galleryEl = document.getElementById('serviceModalGallery');
                            const prevBtn = document.getElementById('prevServiceImg');
                            const nextBtn = document.getElementById('nextServiceImg');
                            
                            let currentIndex = 0;

                            const updateModalImage = (index) => {
                                currentIndex = index;
                                imgEl.src = images[currentIndex];
                                
                                // Update Thumbnails
                                const thumbnails = galleryEl.querySelectorAll('img');
                                thumbnails.forEach((thumb, idx) => {
                                    if(idx === currentIndex) {
                                        thumb.classList.add('border-success', 'border-2', 'opacity-100');
                                        thumb.classList.remove('opacity-50');
                                        thumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                                    } else {
                                        thumb.classList.remove('border-success', 'border-2', 'opacity-100');
                                        thumb.classList.add('opacity-50');
                                    }
                                });

                                // Update Navigation Buttons
                                if(images.length > 1) {
                                    prevBtn.classList.remove('d-none');
                                    nextBtn.classList.remove('d-none');
                                } else {
                                    prevBtn.classList.add('d-none');
                                    nextBtn.classList.add('d-none');
                                }
                            };

                            if (modalEl && imgEl) {
                                updateModalImage(0);
                                if (titleEl) titleEl.textContent = `${serviceName} Gallery`;
                                
                                if (galleryEl) {
                                    galleryEl.innerHTML = '';
                                    if (images.length > 1) {
                                        galleryEl.classList.remove('d-none');
                                        images.forEach((imgUrl, idx) => {
                                            const thumb = document.createElement('img');
                                            thumb.src = imgUrl;
                                            thumb.className = 'rounded border shadow-sm cursor-pointer hover-scale flex-shrink-0 transition-all ' + (idx === 0 ? 'border-success border-2' : 'opacity-50');
                                            thumb.style.width = '60px';
                                            thumb.style.height = '60px';
                                            thumb.style.objectFit = 'cover';
                                            thumb.onclick = () => updateModalImage(idx);
                                            galleryEl.appendChild(thumb);
                                        });
                                    } else {
                                        galleryEl.classList.add('d-none');
                                    }
                                }

                                // Setup Navigation Listeners
                                prevBtn.onclick = () => {
                                    let newIndex = currentIndex - 1;
                                    if(newIndex < 0) newIndex = images.length - 1;
                                    updateModalImage(newIndex);
                                };

                                nextBtn.onclick = () => {
                                    let newIndex = currentIndex + 1;
                                    if(newIndex >= images.length) newIndex = 0;
                                    updateModalImage(newIndex);
                                };
                                
                                const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
                                bsModal.show();
                            }
                        });
                    });

                    // Toggle show/hide services by clicking the card itself
                    card.addEventListener('click', function(e) {
                        // Prevent toggling if clicking on a button or service item inside the card
                        if (e.target.closest('.book-btn') || e.target.closest('.service-item') || e.target.closest('.view-reviews-btn')) {
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

                    // Show "Book" only after selecting an available service
                    card.querySelectorAll('.service-item').forEach(service => {
                        service.addEventListener('click', function() {
                            if (this.classList.contains('service-disabled')) {
                                return;
                            }
                            const bookBtn = card.querySelector('.book-btn');
                            if (bookBtn) bookBtn.classList.remove('d-none');
                            card.querySelectorAll('.service-item').forEach(si => si.classList.remove('active'));
                            this.classList.add('active');
                            const serviceIdInput = document.getElementById('modalServiceId');
                            if (serviceIdInput) {
                                serviceIdInput.value = this.dataset.id;
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
});

const reportModal = document.getElementById('reportClinicModal');
reportModal?.addEventListener('show.bs.modal', e => {
    const btn = e.relatedTarget;
    document.getElementById('reportAppointment').value = btn.dataset.appointment;
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
    // LOGOUT BUTTON
    // -------------------------------
    const logoutBtn = document.getElementById('confirmLogoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            const logoutForm = document.getElementById('logout-form');
            if (logoutForm) logoutForm.submit();
        });
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
    // Cancel Appointment Modal Trigger
    window.showCancelModal = function(appointmentId) {
        var form = document.getElementById('cancelAppointmentForm');
        form.action = '/appointments/' + appointmentId + '/cancel';
        var modal = new bootstrap.Modal(document.getElementById('cancelAppointmentModal'));
        modal.show();
    };
});

function expandQrImage(src) {
    const modalEl = document.getElementById('qrExpandModal');
    const imgEl = document.getElementById('expandedQrImage');
    if (modalEl && imgEl && src) {
        imgEl.src = src;
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
}
</script>

{{-- QR Expansion Modal --}}
<div class="modal fade" id="qrExpandModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-transparent border-0 shadow-none">
            <div class="modal-body p-0 text-center position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 z-3 bg-dark p-2 rounded-circle" data-bs-dismiss="modal" aria-label="Close"></button>
                <img src="" id="expandedQrImage" class="img-fluid rounded-3 shadow-lg" style="max-height: 80vh; background-color: white; padding: 10px;">
            </div>
        </div>
    </div>
</div>
@endsection
