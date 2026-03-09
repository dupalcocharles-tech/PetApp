@extends('layouts.app')

@section('content')
<style>
    body {
        background: radial-gradient(circle at top, #ecfdf5 0, #f9fafb 45%, #eef2ff 100%);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", system-ui, sans-serif;
        min-height: 100vh;
    }

    .edit-profile-card {
        border: none;
        border-radius: 24px;
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.12);
        overflow: hidden;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(12px);
    }

    .card-header-custom {
        background: linear-gradient(135deg, var(--primary-green, #0d9488), var(--primary-dark, #0f766e));
        padding: 32px 24px 80px;
        text-align: center;
        color: white;
        position: relative;
    }

    .card-header-custom h3 {
        letter-spacing: 0.04em;
    }

    .card-header-custom p {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .profile-img-container {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto -75px;
        border-radius: 28px;
        border: 4px solid rgba(255, 255, 255, 0.9);
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.35);
        overflow: hidden;
        background: #020617;
    }

    .profile-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.25s ease-out;
    }

    .profile-img-container:hover .profile-img {
        transform: scale(1.04);
    }

    .camera-icon-overlay {
        position: absolute;
        inset-inline: 0;
        bottom: 0;
        background: linear-gradient(to top, rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0));
        color: white;
        padding: 6px 0 10px;
        text-align: center;
        font-size: 0.8rem;
        opacity: 0;
        transition: opacity 0.2s ease-out;
        cursor: pointer;
    }

    .profile-img-container:hover .camera-icon-overlay {
        opacity: 1;
    }

    .form-container {
        padding: 96px 32px 32px;
    }

    @media (max-width: 991.98px) {
        .form-container {
            padding-inline: 24px;
        }
    }

    .form-floating > label {
        color: #6b7280;
        font-size: 0.9rem;
    }

    .form-control {
        border-radius: 0.9rem;
        border-color: #e5e7eb;
        background-color: #f9fafb;
    }

    .form-control:focus {
        border-color: var(--primary-green, #0d9488);
        box-shadow: 0 0 0 0.18rem rgba(13, 148, 136, 0.25);
        background-color: #ffffff;
    }

    .btn-save {
        background: linear-gradient(to right, var(--primary-green, #0d9488), var(--primary-dark, #0f766e));
        border: none;
        padding: 0.85rem 1.25rem;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        font-size: 0.85rem;
        transition: transform 0.15s ease-out, box-shadow 0.15s ease-out, filter 0.15s ease-out;
    }

    .btn-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 30px rgba(13, 148, 136, 0.3);
        filter: brightness(1.03);
    }

    #profile_image_input {
        display: none;
    }

    .mobile-header {
        background: rgba(255, 255, 255, 0.96);
        padding: 14px 18px;
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
        backdrop-filter: blur(14px);
    }
    
    .mobile-profile-container {
        position: relative;
        width: 132px;
        height: 132px;
        margin: 0 auto;
        border-radius: 28px;
        overflow: hidden;
        border: 4px solid rgba(255, 255, 255, 0.9);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.3);
        background: #020617;
    }
    
    .mobile-form-card {
        background: rgba(255, 255, 255, 0.98);
        border-radius: 20px;
        padding: 22px 18px;
        margin-bottom: 18px;
        box-shadow: 0 16px 35px rgba(15, 23, 42, 0.06);
        border: 1px solid rgba(148, 163, 184, 0.18);
    }

    .form-control-mobile {
        background-color: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 0.9rem;
        padding: 11px 14px;
        font-size: 0.98rem;
    }
    
    .form-control-mobile:focus {
        background-color: #ffffff;
        border-color: var(--primary-green, #0d9488);
        box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.16);
    }

    body.dark-theme {
        background: #020617 !important;
        color: #e0e0e0;
    }

    .dark-theme .edit-profile-card,
    .dark-theme .mobile-form-card,
    .dark-theme .mobile-header {
        background: #020617 !important;
        color: #e0e0e0;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.7);
        border-color: rgba(51, 65, 85, 0.8);
    }

    .dark-theme .profile-img-container,
    .dark-theme .mobile-profile-container {
        border-color: rgba(30, 64, 175, 0.8);
    }

    .dark-theme .form-control,
    .dark-theme .form-control-mobile {
        background-color: #0f172a;
        border-color: #1f2937;
        color: #e5e7eb;
    }

    .dark-theme .form-control:focus,
    .dark-theme .form-control-mobile:focus {
        background-color: #020617;
        border-color: var(--primary-green, #22c55e);
        color: #f9fafb;
    }

    .dark-theme .text-muted {
        color: #9ca3af !important;
    }
    
    .dark-theme .mobile-header .text-dark,
    .dark-theme .fw-bold.text-dark {
        color: #e5e7eb !important;
    }

    .dark-theme .btn-light {
        background-color: #0f172a;
        border-color: #1f2937;
        color: #e5e7eb;
    }

    .dark-theme .bg-light {
        background-color: #020617 !important;
    }
</style>

{{-- Desktop View --}}
<div class="container py-5 d-none d-md-block">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="edit-profile-card">
                <div class="card-header-custom">
                    <h3 class="fw-bold mb-0">Edit Profile</h3>
                    <p class="mb-0 opacity-75">Update your personal information</p>
                </div>

                <form method="POST" action="{{ route('pet_owner.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="profile-img-container" onclick="document.getElementById('profile_image_input').click()">
                        @if($petOwner->profile_image)
                            <img src="{{ asset('storage/' . $petOwner->profile_image) }}" 
                                 id="preview_image"
                                 class="profile-img" 
                                 alt="Profile Image">
                        @else
                            <img src="{{ asset('images/owner.png') }}" 
                                 id="preview_image"
                                 class="profile-img" 
                                 alt="Default User">
                        @endif
                        
                        <div class="camera-icon-overlay">
                            <i class="bi bi-camera-fill me-1"></i> Change
                        </div>
                    </div>

                    <input type="file" name="profile_image" id="profile_image_input" accept="image/*" onchange="previewImage(this)">

                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" onclick="document.getElementById('profile_image_input').click()">
                            <i class="bi bi-image me-1"></i> Select New Image
                        </button>
                    </div>

                    <div class="form-container">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="full_name" name="full_name" 
                                           placeholder="Full Name" value="{{ old('full_name', $petOwner->full_name) }}" required>
                                    <label for="full_name"><i class="bi bi-person me-2"></i>Full Name</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" name="email" 
                                           placeholder="name@example.com" value="{{ old('email', $petOwner->email) }}" required>
                                    <label for="email"><i class="bi bi-envelope me-2"></i>Email Address</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           placeholder="Phone Number" value="{{ old('phone', $petOwner->phone) }}">
                                    <label for="phone"><i class="bi bi-telephone me-2"></i>Phone Number</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating position-relative">
                                    <input type="text" class="form-control" id="ownerAddressInputDesktop" name="address" 
                                           placeholder="Address" value="{{ old('address', $petOwner->address) }}" autocomplete="off">
                                    <label for="ownerAddressInputDesktop"><i class="bi bi-geo-alt me-2"></i>Address</label>
                                    <ul id="addressSuggestionsDesktop" class="list-group position-absolute w-100 shadow start-0" 
                                        style="z-index: 1050; display: none; max-height: 250px; overflow-y: auto; top: 100%;"></ul>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <h5 class="text-muted border-bottom pb-2 mb-3">Change Password <small class="text-muted fw-normal fs-6">(Leave blank to keep current)</small></h5>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="New Password">
                                    <label for="password"><i class="bi bi-lock me-2"></i>New Password</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
                                    <label for="password_confirmation"><i class="bi bi-lock-fill me-2"></i>Confirm Password</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-primary btn-save rounded-pill shadow-sm text-white">
                                Save Changes
                            </button>
                            <a href="{{ route('pet_owner.dashboard') }}" class="btn btn-link text-muted text-decoration-none mt-2">
                                <i class="bi bi-arrow-left me-1"></i> Cancel and go back
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Mobile View --}}
<div class="d-block d-md-none bg-light min-vh-100 pb-5">
    {{-- Mobile Header --}}
    <div class="mobile-header d-flex align-items-center justify-content-between">
        <a href="{{ route('pet_owner.dashboard') }}" class="btn btn-light rounded-circle shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-arrow-left text-dark"></i>
        </a>
        <h5 class="mb-0 fw-bold text-dark">Edit Profile</h5>
        <div style="width: 40px;"></div> {{-- Spacer for alignment --}}
    </div>

    <form method="POST" action="{{ route('pet_owner.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="container mt-4">
            {{-- Mobile Profile Image --}}
            <div class="text-center mb-4">
                <div class="mobile-profile-container position-relative mb-3" onclick="document.getElementById('mobile_profile_image_input').click()">
                    @if($petOwner->profile_image)
                        <img src="{{ asset('storage/' . $petOwner->profile_image) }}" 
                             id="mobile_preview_image"
                             class="w-100 h-100 object-fit-cover" 
                             alt="Profile Image">
                    @else
                        <img src="{{ asset('images/owner.png') }}" 
                             id="mobile_preview_image"
                             class="w-100 h-100 object-fit-cover" 
                             alt="Default User">
                    @endif
                    
                    <div class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-50 text-white py-1 small">
                        <i class="bi bi-camera-fill"></i>
                    </div>
                </div>

                <input type="file" name="profile_image" id="mobile_profile_image_input" class="d-none" accept="image/*" onchange="previewMobileImage(this)">
                
                <button type="button" class="btn btn-outline-success rounded-pill px-4 btn-sm fw-bold" onclick="document.getElementById('mobile_profile_image_input').click()">
                    <i class="bi bi-pencil-square me-1"></i> Change Photo
                </button>
            </div>

            {{-- Alerts --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-3 shadow-sm border-0" role="alert">
                    <ul class="mb-0 ps-3 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4 rounded-3 shadow-sm border-0" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Personal Info Card --}}
            <div class="mobile-form-card">
                <h6 class="fw-bold text-success mb-4 text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">
                    <i class="bi bi-person-lines-fill me-2"></i>Personal Info
                </h6>

                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Full Name</label>
                    <input type="text" class="form-control form-control-mobile" name="full_name" 
                           value="{{ old('full_name', $petOwner->full_name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Email Address</label>
                    <input type="email" class="form-control form-control-mobile" name="email" 
                           value="{{ old('email', $petOwner->email) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Phone Number</label>
                    <input type="tel" class="form-control form-control-mobile" name="phone" 
                           value="{{ old('phone', $petOwner->phone) }}">
                </div>

                <div class="mb-0 position-relative">
                    <label class="form-label text-muted small fw-bold">Address</label>
                    <input type="text" class="form-control form-control-mobile" id="ownerAddressInputMobile" name="address" 
                           value="{{ old('address', $petOwner->address) }}" autocomplete="off">
                    <ul id="addressSuggestionsMobile" class="list-group position-absolute w-100 shadow start-0" 
                        style="z-index: 1050; display: none; max-height: 250px; overflow-y: auto; top: 100%;"></ul>
                </div>
            </div>

            {{-- Security Card --}}
            <div class="mobile-form-card">
                <h6 class="fw-bold text-success mb-4 text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">
                    <i class="bi bi-shield-lock-fill me-2"></i>Security
                </h6>

                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">New Password</label>
                    <input type="password" class="form-control form-control-mobile" name="password" placeholder="Leave blank to keep current">
                </div>

                <div class="mb-0">
                    <label class="form-label text-muted small fw-bold">Confirm Password</label>
                    <input type="password" class="form-control form-control-mobile" name="password_confirmation" placeholder="Confirm new password">
                </div>
            </div>

            {{-- Save Button --}}
            <button type="submit" class="btn btn-success w-100 rounded-pill py-3 fw-bold shadow mb-4 text-uppercase" style="letter-spacing: 1px;">
                Save Changes
            </button>
        </div>
    </form>
</div>

<script>
    // Apply theme immediately
    const savedTheme = localStorage.getItem('theme') || 'light';
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-theme');
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById('preview_image').src = e.target.result;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewMobileImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById('mobile_preview_image').src = e.target.result;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Address Autocomplete Logic
    function setupAddressAutocomplete(inputId, listId) {
        const input = document.getElementById(inputId);
        const list = document.getElementById(listId);
        let timeout = null;

        if (!input || !list) return;

        input.addEventListener('input', function() {
            clearTimeout(timeout);
            const query = this.value;
            
            if (query.length < 3) {
                list.style.display = 'none';
                return;
            }

            timeout = setTimeout(() => {
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=ph`)
                    .then(response => response.json())
                    .then(data => {
                        list.innerHTML = '';
                        if (data.length > 0) {
                            list.style.display = 'block';
                            data.forEach(item => {
                                const li = document.createElement('li');
                                li.className = 'list-group-item list-group-item-action cursor-pointer';
                                li.textContent = item.display_name;
                                li.style.cursor = 'pointer';
                                li.onclick = function() {
                                    input.value = item.display_name;
                                    list.style.display = 'none';
                                    
                                    // Sync values between desktop and mobile inputs if needed
                                    const otherInputId = inputId === 'ownerAddressInputDesktop' ? 'ownerAddressInputMobile' : 'ownerAddressInputDesktop';
                                    const otherInput = document.getElementById(otherInputId);
                                    if(otherInput) otherInput.value = item.display_name;
                                };
                                list.appendChild(li);
                            });
                        } else {
                            list.style.display = 'none';
                        }
                    })
                    .catch(error => console.error('Error fetching address:', error));
            }, 300);
        });

        // Hide list when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target !== input && e.target !== list) {
                list.style.display = 'none';
            }
        });
    }

    // Initialize for both Desktop and Mobile
    document.addEventListener('DOMContentLoaded', function() {
        setupAddressAutocomplete('ownerAddressInputDesktop', 'addressSuggestionsDesktop');
        setupAddressAutocomplete('ownerAddressInputMobile', 'addressSuggestionsMobile');
    });
</script>
@endsection
