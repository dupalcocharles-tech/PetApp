@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-success text-white p-4 border-0">
                    <h2 class="fw-bold mb-0 text-center"><i class="bi bi-plus-circle me-2"></i>Add New Pet</h2>
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('pets.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- Link pet to logged-in owner automatically --}}
                        <input type="hidden" name="pet_owner_id" value="{{ Auth::id() }}">

                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                <img src="{{ asset('images/default_pet.jpg') }}" 
                                     id="imagePreview"
                                     alt="Pet Image" 
                                     class="rounded-circle shadow-sm border border-4 border-light object-fit-cover" 
                                     style="width: 150px; height: 150px;">
                                <label for="pet_image" id="addPetImageLabel" class="position-absolute bottom-0 end-0 bg-white rounded-circle p-2 shadow-sm border cursor-pointer hover-scale">
                                    <i class="bi bi-camera-fill text-success fs-5"></i>
                                </label>
                            </div>
                            <input type="file" class="d-none" id="pet_image" name="pet_image" accept="image/*" onchange="previewImage(this)">
                            <div class="form-text mt-2">Upload a photo of your pet</div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control rounded-3" id="name" name="name" value="{{ old('name') }}" placeholder="Pet Name" required>
                                    <label for="name">Pet Name</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control rounded-3" id="species" name="species" value="{{ old('species') }}" placeholder="Species" required>
                                    <label for="species">Species (e.g., Dog, Cat, Turtle or other)</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control rounded-3" id="breed" name="breed" value="{{ old('breed') }}" placeholder="Breed">
                                    <label for="breed">Breed (Optional)</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" class="form-control rounded-3" id="age" name="age" value="{{ old('age') }}" placeholder="Age" min="0">
                                    <label for="age">Age (years)</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <select name="gender" id="gender" class="form-select rounded-3">
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender')=='Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender')=='Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    <label for="gender">Gender</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-success rounded-pill py-3 fw-bold shadow-sm hover-scale">
                                <i class="bi bi-check-lg me-2"></i>Save Pet
                            </button>
                            <a href="{{ route('pet_owner.dashboard') }}" class="btn btn-light rounded-pill py-3 fw-bold text-muted">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        var savedTheme = localStorage.getItem('theme') || 'light';
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-theme');
            document.documentElement.classList.add('dark-theme');
        }
    });
</script>

<style>
    .cursor-pointer { cursor: pointer; }
    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: scale(1.05); }
    
    /* Dark Theme Support */
    body.dark-theme .card {
        background-color: #2d2d2d;
        color: #fff;
    }
    body.dark-theme .form-control,
    body.dark-theme .form-select {
        background-color: #3d3d3d;
        border-color: #4d4d4d;
        color: #fff;
    }
    body.dark-theme .form-floating label {
        color: #aaa;
    }
    body.dark-theme .form-control:focus,
    body.dark-theme .form-select:focus {
        background-color: #454545;
        color: #fff;
    }
    body.dark-theme #addPetImageLabel {
        background-color: #1f2937 !important;
        border-color: #4b5563 !important;
    }
    body.dark-theme .btn-light {
        background-color: #3b3b3b;
        border-color: #4b5563;
        color: #e5e7eb;
    }
    body.dark-theme .bg-light {
        background-color: #3d3d3d !important;
    }
    body.dark-theme .text-muted {
        color: #aaa !important;
    }
</style>
@endsection
