@extends('layouts.app')

@section('content')
@include('staff.partials.styles')

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
                        <h2 class="fw-bold text-success mb-1"><i class="bi bi-eye me-2"></i>Service Details</h2>
                        <p class="text-muted mb-0">View detailed information about this service.</p>
                    </div>
                    <a href="{{ route('services.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-arrow-left me-2"></i> Back to Services
                    </a>
                </div>

                {{-- Service Details Card --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-5">
                        <div class="row">
                            <div class="col-md-8">
                                <h3 class="fw-bold text-dark mb-3">{{ $service->name }}</h3>
                                
                                <div class="mb-4">
                                    <h6 class="text-uppercase text-muted fw-bold small ls-1 mb-2">For Animal</h6>
                                    <span class="badge bg-light text-dark px-3 py-2 rounded-pill fw-semibold border border-success-subtle" style="color:#111 !important;">
                                        <i class="bi bi-tag-fill me-1 text-success"></i>
                                        {{ ($service->animal_type && trim($service->animal_type) !== '') ? $service->animal_type : 'General' }}
                                    </span>
                                </div>

                                <div class="mb-4">
                                    <h6 class="text-uppercase text-muted fw-bold small ls-1">Service Location</h6>
                                    @php
                                        $location = $service->location_type ?? 'clinic';
                                        $label = match ($location) {
                                            'home' => 'Home Service',
                                            'both' => 'Clinic & Home',
                                            default => 'Clinic',
                                        };
                                        $badgeClass = match ($location) {
                                            'home' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                                            'both' => 'bg-success-subtle text-success-emphasis border border-success-subtle',
                                            default => 'bg-primary-subtle text-primary-emphasis border border-primary-subtle',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill fw-semibold">
                                        {{ $label }}
                                    </span>
                                </div>

                                <div class="mb-4">
                                    <h6 class="text-uppercase text-muted fw-bold small ls-1">Description</h6>
                                    <p class="text-secondary lead fs-6">{{ $service->description }}</p>
                                </div>

                                <div class="mb-4">
                                    <h6 class="text-uppercase text-muted fw-bold small ls-1">Price</h6>
                                    <h2 class="text-success fw-bold">₱{{ number_format($service->price, 2) }}</h2>
                                </div>
                            </div>
                            
                            <div class="col-md-4 border-start border-light ps-md-5 mt-4 mt-md-0">
                                <h6 class="text-uppercase text-muted fw-bold small ls-1 mb-3">Actions</h6>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('services.edit', $service->id) }}" class="btn btn-primary rounded-pill py-2 shadow-sm">
                                        <i class="bi bi-pencil-square me-2"></i> Edit Service
                                    </a>
                                    
                                    <form action="{{ route('services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger rounded-pill py-2 w-100">
                                            <i class="bi bi-trash me-2"></i> Delete Service
                                        </button>
                                    </form>
                                </div>

                                <div class="mt-4 p-3 bg-light rounded-4 border border-light-subtle text-center">
                                    <small class="text-muted d-block mb-1">Created on</small>
                                    <span class="fw-semibold text-dark">{{ $service->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

@include('staff.partials.scripts')
@endsection
