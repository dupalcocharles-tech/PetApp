@extends('layouts.app')

@section('content')
@include('staff.partials.styles')

<style>
    /* Dark Theme Support */
    body.dark-theme {
        background-color: #121212;
        color: #e0e0e0;
    }
    body.dark-theme .bg-light {
        background-color: #1e1e1e !important;
    }
    body.dark-theme .bg-white {
        background-color: #2a2a2a !important;
        color: #e0e0e0 !important;
    }
    body.dark-theme .text-dark {
        color: #e0e0e0 !important;
    }
    body.dark-theme .text-muted {
        color: #adb5bd !important;
    }
    body.dark-theme .text-secondary {
        color: #adb5bd !important;
    }
    body.dark-theme .card {
        background-color: #2a2a2a;
        border-color: #444;
    }
    body.dark-theme .card-header {
        background-color: #333 !important;
        border-bottom-color: #444 !important;
    }
    body.dark-theme .border-light {
        border-color: #444 !important;
    }
    body.dark-theme .shadow-sm {
        box-shadow: 0 .125rem .25rem rgba(0,0,0,.5) !important;
    }
    
    /* Gallery Styles */
    .gallery-img {
        transition: transform 0.3s ease;
        cursor: pointer;
    }
    .gallery-img:hover {
        transform: scale(1.05);
    }
    
    /* Mobile adjustments */
    @media (max-width: 767.98px) {
        #sidebarMenu {
            position: fixed;
            top: 0;
            left: 0;
            width: 75%; /* Drawer width */
            max-width: 300px;
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
            z-index: 1050;
        }

        #sidebarMenu.show {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0 !important;
            width: 100% !important;
            padding-top: 80px;
        }
    }

    /* Sidebar Backdrop */
    #sidebarBackdrop {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1040;
    }
    #sidebarBackdrop.show { display: block; }
</style>

<div class="container-fluid p-0 m-0">
    {{-- Mobile Sidebar Toggle --}}
    <button class="btn btn-success d-md-none position-fixed top-0 start-0 m-3 z-3 shadow rounded-circle p-2" 
            id="sidebarToggle" style="width: 45px; height: 45px;">
        <i class="bi bi-list fs-4 text-white"></i>
    </button>

    {{-- Sidebar Backdrop --}}
    <div id="sidebarBackdrop"></div>

    <div class="row g-0">
        {{-- Sidebar --}}
        @include('staff.partials.sidebar')

        {{-- Main Content --}}
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 main-content bg-light min-vh-100">
            <div class="container-xl">
                
                {{-- Header --}}
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 bg-white p-4 rounded-4 shadow-sm border-start border-5 border-success">
                    <div class="mb-3 mb-md-0">
                        <h2 class="fw-bold text-success mb-1"><i class="bi bi-person-circle me-2"></i>Clinic Profile</h2>
                        <p class="text-muted mb-0">View your clinic's public profile information.</p>
                    </div>
                </div>

                @php
                    $clinic = Auth::guard('clinic')->user();
                @endphp

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        
                         {{-- Profile Overview Card --}}
                        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                            <div class="card-body p-0">
                                <div class="bg-success bg-opacity-10 p-4 text-center">
                                    <div class="position-relative d-inline-block mb-3">
                                        @if($clinic && $clinic->profile_image)
                                            <img src="{{ asset('storage/clinics/' . $clinic->profile_image) }}" 
                                                 class="rounded-circle border border-4 border-white shadow" width="120" height="120" style="object-fit: cover;">
                                        @else
                                            <img src="{{ asset('images/clinics/default.png') }}" 
                                                 class="rounded-circle border border-4 border-white shadow" width="120" height="120" style="object-fit: cover;">
                                        @endif
                                    </div>
                                    <h3 class="fw-bold text-dark mb-1">{{ $clinic->clinic_name }}</h3>
                                    <p class="text-muted mb-0">{{ $clinic->email }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Bio Section --}}
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-white border-bottom border-light p-3">
                                <h5 class="fw-bold text-success mb-0"><i class="bi bi-file-text me-2"></i>About the Clinic</h5>
                            </div>
                            <div class="card-body p-4">
                                @if($clinic->description)
                                    <p class="text-secondary mb-0" style="white-space: pre-line;">{{ $clinic->description }}</p>
                                @else
                                    <p class="text-muted fst-italic mb-0">No description provided yet.</p>
                                @endif
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-header bg-white border-bottom border-light p-3">
                                        <h5 class="fw-bold text-success mb-0"><i class="bi bi-info-circle me-2"></i>Contact Information</h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="mb-3">
                                            <label class="small text-muted text-uppercase fw-bold">Address</label>
                                            <p class="fs-5 text-dark">{{ $clinic->address }}</p>
                                        </div>
                                        <div class="mb-0">
                                            <label class="small text-muted text-uppercase fw-bold">Phone Number</label>
                                            <p class="fs-5 text-dark mb-0">{{ $clinic->phone }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-header bg-white border-bottom border-light p-3">
                                        <h5 class="fw-bold text-success mb-0"><i class="bi bi-clock me-2"></i>Operating Hours</h5>
                                    </div>
                                    <div class="card-body p-4">
                                        @if($clinic->is_24_hours)
                                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                                                <div>
                                                    <strong>Open 24 Hours</strong>
                                                    <div class="small">This clinic operates 24/7.</div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="row">
                                                <div class="col-6">
                                                    <label class="small text-muted text-uppercase fw-bold">Opening Time</label>
                                                    <p class="fs-5 text-dark">{{ $clinic->opening_time ? \Carbon\Carbon::parse($clinic->opening_time)->format('g:i A') : 'N/A' }}</p>
                                                </div>
                                                <div class="col-6">
                                                     <label class="small text-muted text-uppercase fw-bold">Closing Time</label>
                                                    <p class="fs-5 text-dark">{{ $clinic->closing_time ? \Carbon\Carbon::parse($clinic->closing_time)->format('g:i A') : 'N/A' }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="mt-3">
                                            <label class="small text-muted text-uppercase fw-bold">Status</label>
                                            <div class="d-flex align-items-center">
                                                <span class="badge {{ $clinic->is_open ? 'bg-success' : 'bg-danger' }} rounded-pill px-3 py-2">
                                                    {{ $clinic->is_open ? 'Open Now' : 'Closed' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Gallery Section --}}
                        <div class="card border-0 shadow-sm rounded-4 mb-5">
                            <div class="card-header bg-white border-bottom border-light p-3">
                                <h5 class="fw-bold text-success mb-0"><i class="bi bi-images me-2"></i>Gallery</h5>
                            </div>
                            <div class="card-body p-4">
                                @if($clinic->gallery && is_array($clinic->gallery) && count($clinic->gallery) > 0)
                                    <div class="row g-3">
                                        @foreach($clinic->gallery as $image)
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <div class="position-relative ratio ratio-1x1 overflow-hidden rounded-3 shadow-sm">
                                                    <img src="{{ asset('storage/clinics/gallery/' . $image) }}" 
                                                         class="gallery-img w-100 h-100 object-fit-cover" 
                                                         alt="Gallery Image">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-5 text-muted">
                                        <i class="bi bi-image fs-1 mb-2 d-block opacity-50"></i>
                                        <p class="mb-0">No images in gallery.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Edit Button (Bottom) --}}
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center mb-5">
                            <a href="{{ route('staff.edit') }}" class="btn btn-success rounded-pill px-5 py-3 fw-bold shadow hover-scale">
                                <i class="bi bi-pencil-square me-2"></i>Edit Profile
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function(){
        // Sidebar Toggle Logic
        const sidebar = document.getElementById('sidebarMenu');
        const toggleBtn = document.getElementById('sidebarToggle');
        const backdrop = document.getElementById('sidebarBackdrop');
    
        if(toggleBtn){
            toggleBtn.addEventListener('click', function(){
                sidebar.classList.toggle('show');
                backdrop.classList.toggle('show');
            });
        }
    
        if(backdrop){
            backdrop.addEventListener('click', function(){
                sidebar.classList.remove('show');
                backdrop.classList.remove('show');
            });
        }
    });
</script>
@include('staff.partials.scripts')
@endsection
