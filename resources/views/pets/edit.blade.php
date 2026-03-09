@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-12 bg-success bg-gradient p-4 text-white text-center position-relative overflow-hidden">
                            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('{{ asset('images/pattern.svg') }}') repeat; opacity: 0.1;"></div>
                            <h2 class="fw-bold mb-1 position-relative z-1">Edit Pet Profile</h2>
                            <p class="mb-0 opacity-75 position-relative z-1">Update your pet's information</p>
                        </div>
                        <div class="col-12 p-5">
                            <form method="POST" action="{{ route('pets.update', $pet->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- Keep pet_owner_id hidden -->
                                <input type="hidden" name="pet_owner_id" value="{{ $pet->pet_owner_id }}">

                                <div class="text-center mb-5 mt-n5 position-relative z-2">
                                    <div class="position-relative d-inline-block">
                                        <div class="rounded-circle p-1 bg-white shadow-sm">
                                            <img src="{{ $pet->image ? asset('storage/'.$pet->image) : asset('images/default_pet.jpg') }}" 
                                                 id="imagePreview"
                                                 alt="Pet Image" 
                                                 class="rounded-circle object-fit-cover" 
                                                 style="width: 160px; height: 160px;">
                                        </div>
                                        <label for="pet_image" class="position-absolute bottom-0 end-0 bg-success text-white rounded-circle p-2 shadow border border-2 border-white cursor-pointer hover-scale mb-2 me-2">
                                            <i class="bi bi-camera-fill fs-5"></i>
                                        </label>
                                    </div>
                                    <input type="file" class="d-none" id="pet_image" name="pet_image" accept="image/*" onchange="previewImage(this)">
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control bg-light-subtle border-0 rounded-3" id="name" name="name" value="{{ old('name', $pet->name) }}" placeholder="Pet Name" required>
                                            <label for="name" class="text-muted"><i class="bi bi-tag-fill me-2 text-success opacity-50"></i>Pet Name</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control bg-light-subtle border-0 rounded-3" id="species" name="species" value="{{ old('species', $pet->species) }}" placeholder="Species" required>
                                            <label for="species" class="text-muted"><i class="bi bi-paw-fill me-2 text-success opacity-50"></i>Species</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control bg-light-subtle border-0 rounded-3" id="breed" name="breed" value="{{ old('breed', $pet->breed) }}" placeholder="Breed">
                                            <label for="breed" class="text-muted"><i class="bi bi-flower1 me-2 text-success opacity-50"></i>Breed</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="number" class="form-control bg-light-subtle border-0 rounded-3" id="age" name="age" value="{{ old('age', $pet->age) }}" placeholder="Age">
                                            <label for="age" class="text-muted"><i class="bi bi-calendar-event-fill me-2 text-success opacity-50"></i>Age (years)</label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-floating">
                                            <select name="gender" id="gender" class="form-select bg-light-subtle border-0 rounded-3">
                                                <option value="">Select Gender</option>
                                                <option value="Male" {{ old('gender', $pet->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ old('gender', $pet->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                            </select>
                                            <label for="gender" class="text-muted"><i class="bi bi-gender-ambiguous me-2 text-success opacity-50"></i>Gender</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid gap-2 mt-5">
                                    <button type="submit" class="btn btn-success rounded-pill py-3 fw-bold shadow-sm hover-scale">
                                        <i class="bi bi-check-lg me-2"></i>Save Changes
                                    </button>
                                    <a href="{{ route('pet_owner.dashboard') }}" class="btn btn-light-subtle rounded-pill py-3 fw-bold text-muted">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
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
    body.dark-theme .bg-light {
        background-color: #3d3d3d !important;
    }
    body.dark-theme .text-muted {
        color: #aaa !important;
    }
</style>
@endsection
