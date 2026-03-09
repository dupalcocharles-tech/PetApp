@extends('layouts.app')

@section('content')
{{-- Internal Styles for Self-Contained Design --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    :root {
        --primary-green: #0d9488; /* Teal-ish green */
        --primary-dark: #0f766e;
        --secondary-green: #ccfbf1;
        --sidebar-bg: #111827;
        --card-bg: rgba(255, 255, 255, 0.9);
        --text-dark: #1f2937;
        --text-light: #6b7280;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f3f4f6;
        background-image: radial-gradient(#e5e7eb 1px, transparent 1px);
        background-size: 20px 20px;
        color: var(--text-dark);
        min-height: 100vh;
    }

    /* Sidebar Styling */
    #sidebarMenu {
        background: linear-gradient(180deg, #111827 0%, #1f2937 100%);
        box-shadow: 4px 0 24px rgba(0,0,0,0.1);
        z-index: 1040;
        height: 100vh;
        border-right: 1px solid rgba(255,255,255,0.05);
    }

    .nav-link {
        color: #9ca3af;
        border-radius: 12px;
        margin-bottom: 4px;
        padding: 12px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .nav-link:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.05);
        transform: translateX(4px);
    }

    .nav-link.active {
        background: linear-gradient(90deg, var(--primary-green) 0%, var(--primary-dark) 100%);
        color: #fff;
        box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3);
    }

    /* Cards */
    .card {
        background: var(--card-bg);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 24px;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.1);
    }

    /* Form Styling */
    .form-control, .form-select {
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.1);
    }

    .form-label {
        font-weight: 500;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    /* Buttons */
    .btn-primary {
        background: var(--primary-green);
        border-color: var(--primary-green);
    }
    
    .btn-primary:hover {
        background: var(--primary-dark);
        border-color: var(--primary-dark);
    }

    /* Mobile adjustments */
    @media (max-width: 767.98px) {
        .main-content {
            padding-top: 80px;
        }
        
        #sidebarMenu {
            width: 280px;
            background: #1f2937;
        }
    }
    
    /* Scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }
    ::-webkit-scrollbar-track {
        background: transparent;
    }
    ::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
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
        z-index: 1030;
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
        {{-- Sidebar --}}
        @include('staff.partials.sidebar')

        {{-- Main Content --}}
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 main-content bg-light min-vh-100">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4 pt-5 pt-md-0">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Add New Service</h2>
                    <p class="text-muted mb-0">Create a new service offering for your clinic.</p>
                </div>
                <div>
                    <a href="{{ route('services.index') }}" class="btn btn-outline-secondary fw-semibold px-4 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                        <div class="card-body p-4 p-md-5">
                            <form method="POST" action="{{ route('services.store') }}" enctype="multipart/form-data">
                                @csrf

                                {{-- Service Name --}}
                                <div class="mb-4">
                                    <label for="name" class="form-label">Service Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 rounded-start-4"><i class="bi bi-tag text-muted"></i></span>
                                        <input type="text" class="form-control border-start-0 rounded-end-4 ps-0" id="name" name="name" placeholder="e.g. Annual Vaccination" required>
                                    </div>
                                </div>

                                {{-- Description --}}
                                <div class="mb-4">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control rounded-4" id="description" name="description" rows="4" placeholder="Describe the service details..."></textarea>
                                </div>

                                {{-- Service Images --}}
                                <div class="mb-4">
                                    <label for="images" class="form-label">Service Images (optional)</label>
                                    <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple>
                                    <small class="text-muted">You can upload one or more images to showcase this service.</small>
                                </div>

                                <div class="row">
                                    {{-- Price --}}
                                    <div class="col-md-6 mb-4">
                                        <label for="price" class="form-label">Price (₱)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 rounded-start-4">₱</span>
                                            <input type="number" class="form-control border-start-0 rounded-end-4 ps-0" id="price" name="price" step="0.01" placeholder="0.00" required>
                                        </div>
                                    </div>

                                    {{-- Animal Type Checkboxes --}}
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label">For Animal</label>
                                        @php
                                            $animalGroups = [
                                                'Mammals' => ['Dogs','Cats','Cows','Sheep','Goats','Pigs','Horses','Rabbits'],
                                                'Birds'   => ['Chickens','Ducks','Turkeys','Geese','Parrots'],
                                                'Rodents' => ['Hamsters','Guinea Pigs','Mice','Rats'],
                                            ];
                                            $selectedAnimals = old('animal_type', []);
                                            if (!is_array($selectedAnimals)) {
                                                $selectedAnimals = array_filter(explode(',', (string) $selectedAnimals));
                                            }

                                             // Filter available animals by clinic specializations, keeping groups structure
                                             $clinicSpecializations = $specializations ?? [];
                                             if (!is_array($clinicSpecializations)) {
                                                 $clinicSpecializations = [];
                                             }
                                             $normalizedSpecs = array_map(function ($name) {
                                                 return strtolower(trim($name));
                                             }, $clinicSpecializations);

                                             if (!empty($normalizedSpecs)) {
                                                 $filteredGroups = [];
                                                 foreach ($animalGroups as $groupLabel => $animals) {
                                                     $filtered = [];
                                                     foreach ($animals as $animal) {
                                                         if (in_array(strtolower($animal), $normalizedSpecs, true)) {
                                                             $filtered[] = $animal;
                                                         }
                                                     }
                                                     if (!empty($filtered)) {
                                                         $filteredGroups[$groupLabel] = $filtered;
                                                     }
                                                 }
                                                 $animalGroups = $filteredGroups;
                                             }
                                        @endphp
                                        <div class="card p-3 rounded-4 bg-light border-0" style="max-height: 300px; overflow-y: auto;">
                                            @foreach($animalGroups as $groupLabel => $animals)
                                                <h6 class="fw-bold text-secondary small text-uppercase mb-2">{{ $groupLabel }}</h6>
                                                <div class="row g-2 mb-3">
                                                    @foreach($animals as $animal)
                                                        <div class="col-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                       name="animal_type[]" value="{{ $animal }}"
                                                                       id="animal_{{ str_replace(' ', '_', $animal) }}"
                                                                       {{ in_array($animal, $selectedAnimals) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="animal_{{ str_replace(' ', '_', $animal) }}">
                                                                    {{ $animal }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach

                                            <h6 class="fw-bold text-secondary small text-uppercase mb-2 mt-3">Others</h6>
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                               name="animal_type[]" value="Other"
                                                               id="animal_Other"
                                                               {{ in_array('Other', $selectedAnimals) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="animal_Other">Other</label>
                                                    </div>
                                                    <div class="mt-2">
                                                        <input type="text" class="form-control form-control-sm"
                                                               name="other_animal_type"
                                                               value="{{ old('other_animal_type') }}"
                                                               placeholder="Specify other animal (e.g. Turtles)">
                                                        <small class="text-muted">Tick "Other" and type the specific animal.</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Service Location</label>
                                    @php
                                        $location = old('location_type', 'clinic');
                                    @endphp
                                    <div class="d-flex flex-wrap gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="location_type" id="location_clinic" value="clinic" {{ $location === 'clinic' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="location_clinic">
                                                At Clinic
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="location_type" id="location_home" value="home" {{ $location === 'home' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="location_home">
                                                Home Service
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="location_type" id="location_both" value="both" {{ $location === 'both' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="location_both">
                                                Both
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Buttons --}}
                                <div class="d-flex justify-content-end mt-4 pt-2 border-top">
                                    <a href="{{ route('services.index') }}" class="btn btn-light text-secondary me-3 rounded-pill px-4">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">
                                        <i class="bi bi-check-lg me-1"></i> Save Service
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@include('staff.partials.scripts')
@endsection
