@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-5 align-items-center">
        <div class="col-md-8">
            <h1 class="fw-bold text-success mb-2 display-6">My Pets</h1>
            <p class="text-muted lead mb-0">Manage your registered pets profiles and details</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('pets.create') }}" class="btn btn-success rounded-pill px-5 py-3 shadow fw-bold hover-scale">
                <i class="bi bi-plus-lg me-2"></i>Add New Pet
            </a>
        </div>
    </div>

    @if($pets->isEmpty())
        <div class="text-center py-5 rounded-4 bg-light-subtle border border-dashed">
            <div class="mb-4">
                <div class="bg-white p-4 rounded-circle shadow-sm d-inline-block">
                    <i class="bi bi-paw-fill display-1 text-success opacity-50"></i>
                </div>
            </div>
            <h3 class="text-dark fw-bold mb-2">No pets found</h3>
            <p class="text-muted mb-4">You haven't added any pets yet. Start by adding your first companion!</p>
            <a href="{{ route('pets.create') }}" class="btn btn-success rounded-pill px-4 py-2 fw-bold shadow-sm">
                Add Your First Pet
            </a>
        </div>
    @else
        <div class="row g-4">
            @foreach($pets as $pet)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden pet-card group">
                    <div class="position-relative overflow-hidden">
                        <img src="{{ $pet->image ? asset('storage/'.$pet->image) : asset('images/default_pet.jpg') }}" 
                             class="card-img-top object-fit-cover transition-transform duration-500" 
                             alt="{{ $pet->name }}"
                             style="height: 240px; width: 100%;">
                        
                        <div class="position-absolute top-0 end-0 p-3">
                            <span class="badge bg-white/90 backdrop-blur text-success shadow-sm rounded-pill px-3 py-2 fw-bold border border-success-subtle">
                                {{ $pet->species }}
                            </span>
                        </div>

                        <!-- Hover Overlay -->
                        <div class="pet-overlay position-absolute inset-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-dark bg-opacity-75 opacity-0 transition-opacity duration-300">
                            <div class="d-flex gap-3 transform-translate-y-4 transition-transform duration-300 delay-100 overlay-buttons">
                                <a href="{{ route('pets.edit', $pet->id) }}" class="btn btn-white text-success rounded-circle shadow p-3 hover-scale" title="Edit" style="width: 50px; height: 50px; display: grid; place-items: center;">
                                    <i class="bi bi-pencil-fill fs-5"></i>
                                </a>
                                <form action="{{ route('pets.destroy', $pet->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to remove this pet?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-white text-danger rounded-circle shadow p-3 hover-scale" title="Delete" style="width: 50px; height: 50px; display: grid; place-items: center;">
                                        <i class="bi bi-trash-fill fs-5"></i>
                                    </button>
                                </form>
                            </div>
                            <span class="text-white mt-3 fw-bold small text-uppercase tracking-wider opacity-0 transition-opacity duration-300 delay-200 overlay-text">Manage Pet</span>
                        </div>
                    </div>
                    
                    <div class="card-body text-center p-4">
                        <h4 class="card-title fw-bold text-dark mb-1">{{ $pet->name }}</h4>
                        <p class="text-muted small mb-4">{{ $pet->breed ?? 'Unknown Breed' }}</p>
                        
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="p-2 bg-light-subtle rounded-3 border border-light">
                                    <small class="d-block text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Age</small>
                                    <span class="fw-bold text-dark">{{ $pet->age ?? '-' }} <small class="text-muted fw-normal">yrs</small></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 bg-light-subtle rounded-3 border border-light">
                                    <small class="d-block text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Gender</small>
                                    <span class="fw-bold text-dark">{{ $pet->gender ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Actions (Visible only on mobile) -->
                        <div class="d-flex d-md-none justify-content-center gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('pets.edit', $pet->id) }}" class="btn btn-outline-success btn-sm rounded-pill px-3 flex-grow-1">
                                <i class="bi bi-pencil-square me-1"></i>Edit
                            </a>
                            <form action="{{ route('pets.destroy', $pet->id) }}" method="POST" class="d-inline flex-grow-1" onsubmit="return confirm('Are you sure you want to remove this pet?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 w-100">
                                    <i class="bi bi-trash me-1"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
    
    <div class="mt-5 text-center">
        <a href="{{ route('pet_owner.dashboard') }}" class="btn btn-link text-decoration-none text-muted hover-opacity">
            <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
</div>

<style>
    .bg-white\/90 { background-color: rgba(255, 255, 255, 0.9); }
    .backdrop-blur { backdrop-filter: blur(4px); }
    .tracking-wider { letter-spacing: 0.1em; }
    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: scale(1.05); }
    
    .pet-card {
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
    }
    .pet-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    
    /* Image Zoom Effect */
    .pet-card:hover .card-img-top {
        transform: scale(1.05);
    }
    .transition-transform { transition: transform 0.5s ease; }
    
    /* Overlay Animation */
    .pet-card:hover .pet-overlay {
        opacity: 1 !important;
    }
    .pet-card:hover .overlay-buttons {
        transform: translateY(0);
    }
    .pet-card:hover .overlay-text {
        opacity: 0.8;
    }
    
    .overlay-buttons {
        transform: translateY(20px);
    }
    .btn-white {
        background-color: white;
        border: none;
    }
    .btn-white:hover {
        background-color: #f8f9fa;
    }

    /* Dark Theme Support */
    body.dark-theme .pet-card {
        background-color: #2d2d2d;
    }
    body.dark-theme .pet-card .fw-bold.text-dark,
    body.dark-theme .pet-card .card-title,
    body.dark-theme h3.text-dark {
        color: #fff !important;
    }
    body.dark-theme .bg-light-subtle {
        background-color: #3d3d3d !important;
        border-color: #4d4d4d !important;
    }
    body.dark-theme .btn-white {
        background-color: #3d3d3d;
        color: #fff;
    }
    body.dark-theme .btn-white.text-success { color: #75b798 !important; }
    body.dark-theme .btn-white.text-danger { color: #ea868f !important; }
    body.dark-theme .btn-white:hover {
        background-color: #454545;
    }
</style>
@endsection
