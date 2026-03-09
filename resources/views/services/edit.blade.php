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
                    <h2 class="fw-bold text-dark mb-1">Edit Service</h2>
                    <p class="text-muted mb-0">Update service details for your clinic.</p>
                </div>
                <div>
                    <a href="{{ route('services.index') }}" class="btn btn-outline-secondary fw-semibold px-4 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    {{-- Show validation errors if any --}}
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-4 shadow-sm mb-4 border-0">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                        <div class="card-body p-4 p-md-5">
                            <form method="POST" action="{{ route('services.update', $service->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                {{-- Service Name --}}
                                <div class="mb-4">
                                    <label for="name" class="form-label">Service Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 rounded-start-4"><i class="bi bi-tag text-muted"></i></span>
                                        <input type="text" class="form-control border-start-0 rounded-end-4 ps-0" id="name" name="name" 
                                               value="{{ old('name', $service->name) }}" required>
                                    </div>
                                </div>

                                {{-- Description --}}
                                <div class="mb-4">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control rounded-4" id="description" name="description" rows="4">{{ old('description', $service->description) }}</textarea>
                                </div>

                                <div class="row">
                                    {{-- Price --}}
                                    <div class="col-md-6 mb-4">
                                        <label for="price" class="form-label">Price (₱)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 rounded-start-4">₱</span>
                                            <input type="number" class="form-control border-start-0 rounded-end-4 ps-0" id="price" name="price" step="0.01" 
                                                   value="{{ old('price', $service->price) }}" required>
                                        </div>
                                    </div>

                                    {{-- Animal Type Checkboxes --}}
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label">For Animal</label>
                                        @php
                                            $oldAnimalType = old('animal_type', $service->animal_type ?? '');
                                            $selectedAnimals = is_array($oldAnimalType) ? $oldAnimalType : array_filter(explode(',', $oldAnimalType));

                                            $animalGroups = [
                                                'Mammals' => ['Dogs','Cats','Cows','Sheep','Goats','Pigs','Horses','Rabbits'],
                                                'Birds'   => ['Chickens','Ducks','Turkeys','Geese','Parrots'],
                                                'Rodents' => ['Hamsters','Guinea Pigs','Mice','Rats'],
                                            ];
                                            $standardAnimals = array_merge(...array_values($animalGroups));
                                            $otherAnimal = null;
                                            foreach ($selectedAnimals as $sa) {
                                                if (!in_array($sa, $standardAnimals)) {
                                                    $otherAnimal = $sa;
                                                    break;
                                                }
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
                                                               {{ $otherAnimal ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="animal_Other">Other</label>
                                                    </div>
                                                    <div class="mt-2">
                                                        <input type="text" class="form-control form-control-sm"
                                                               name="other_animal_type"
                                                               value="{{ old('other_animal_type', $otherAnimal ?? '') }}"
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
                                        $location = old('location_type', $service->location_type ?? 'clinic');
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

                                <div class="mb-4">
                                    <label class="form-label">Service Images</label>
                                    <div class="card p-3 rounded-4 bg-light border-0">
                                        <div class="row g-2 mb-3" id="current-images">
                                            @if($service->images && count($service->images) > 0)
                                                @foreach($service->images as $index => $image)
                                                    <div class="col-4 col-md-3 position-relative image-container">
                                                        <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded-3 shadow-sm border object-fit-cover w-100" style="height: 80px;">
                                                        <button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 m-1 p-0 d-flex align-items-center justify-content-center" 
                                                                style="width: 20px; height: 20px;" onclick="removeImage(this, '{{ $image }}')">
                                                            <i class="bi bi-x small"></i>
                                                        </button>
                                                        <input type="hidden" name="existing_images[]" value="{{ $image }}">
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="input-group">
                                            <input type="file" class="form-control rounded-4" id="images" name="images[]" multiple accept="image/*">
                                        </div>
                                        <small class="text-muted mt-2">You can select multiple new images to add.</small>
                                    </div>
                                </div>

                                {{-- Buttons --}}
                                <div class="d-flex justify-content-end mt-4 pt-2 border-top">
                                    <a href="{{ route('services.index') }}" class="btn btn-light text-secondary me-3 rounded-pill px-4">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">
                                        <i class="bi bi-check-lg me-1"></i> Update Service
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

@section('scripts')
<script>
    function removeImage(btn, path) {
        if(confirm('Remove this image? This will be saved after you update the service.')) {
            btn.closest('.image-container').remove();
        }
    }

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
