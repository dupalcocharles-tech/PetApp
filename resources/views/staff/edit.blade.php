@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endsection

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
                        <h2 class="fw-bold text-success mb-1"><i class="bi bi-person-gear me-2"></i>Edit Clinic Profile</h2>
                        <p class="text-muted mb-0">Update your clinic information, password, and specializations.</p>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <form action="{{ route('clinic.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- Basic Information Card --}}
                            <div class="card border-0 shadow-sm rounded-4 mb-4">
                                <div class="card-header bg-white border-bottom border-light p-3">
                                    <h5 class="fw-bold text-success mb-0"><i class="bi bi-info-circle me-2"></i>Basic Information</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold text-secondary">Username</label>
                                            <input type="text" name="username" class="form-control bg-light" 
                                                   value="{{ old('username', $clinic->username) }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold text-secondary">Email Address</label>
                                            <input type="email" name="email" class="form-control bg-light" 
                                                   value="{{ old('email', $clinic->email) }}" required>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            {{-- Security Card --}}
                            <div class="card border-0 shadow-sm rounded-4 mb-4">
                                <div class="card-header bg-white border-bottom border-light p-3">
                                    <h5 class="fw-bold text-success mb-0"><i class="bi bi-shield-lock me-2"></i>Security</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="alert alert-light border-start border-4 border-warning" role="alert">
                                        <small class="text-muted"><i class="bi bi-exclamation-circle me-1"></i> Leave these fields blank if you do not want to change your password.</small>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold text-secondary">New Password</label>
                                            <input type="password" name="password" class="form-control bg-light" placeholder="Enter new password">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold text-secondary">Confirm Password</label>
                                            <input type="password" name="password_confirmation" class="form-control bg-light" placeholder="Confirm new password">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Clinic Details Card --}}
                            <div class="card border-0 shadow-sm rounded-4 mb-4">
                                <div class="card-header bg-white border-bottom border-light p-3">
                                    <h5 class="fw-bold text-success mb-0"><i class="bi bi-hospital me-2"></i>Clinic Details</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold text-secondary">Clinic Name</label>
                                        <input type="text" name="clinic_name" class="form-control bg-light" 
                                               value="{{ old('clinic_name', $clinic->clinic_name) }}" required>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-7 position-relative">
                                            <label class="form-label fw-semibold text-secondary">Address</label>
                                            <input type="text" name="address" id="clinicAddressInput" class="form-control bg-light" 
                                                   value="{{ old('address', $clinic->address) }}" autocomplete="off" required>
                                            <ul id="addressSuggestions" class="list-group position-absolute w-100 shadow start-0" 
                                                style="z-index: 1050; display: none; max-height: 250px; overflow-y: auto; top: 100%;"></ul>
                                            
                                            {{-- Map Container --}}
                                            <div id="map" class="mt-3 rounded-3 border shadow-sm" style="height: 300px; z-index: 1;"></div>
                                            <div class="form-text small text-muted mt-1"><i class="bi bi-geo-alt"></i> Drag the pin to set your precise location.</div>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label fw-semibold text-secondary">Phone Number</label>
                                            <input type="text" name="phone" class="form-control bg-light" 
                                                   value="{{ old('phone', $clinic->phone) }}" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 mt-3">
                                        <label class="form-label fw-semibold text-secondary">About the Clinic (Bio)</label>
                                        <textarea name="description" class="form-control bg-light" rows="4">{{ old('description', $clinic->description) }}</textarea>
                                        <div class="form-text">Tell pet owners about your clinic's history, mission, and team.</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Payment Settings Card --}}
                            <div class="card border-0 shadow-sm rounded-4 mb-4">
                                <div class="card-header bg-white border-bottom border-light p-3">
                                    <h5 class="fw-bold text-success mb-0"><i class="bi bi-wallet2 me-2"></i>Payment Settings</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <label class="form-label fw-semibold text-secondary">E-Wallet QR Code</label>
                                            <input type="file" name="qr_code" id="clinicQrInput" class="form-control bg-light mb-2" accept="image/*">
                                            <div class="form-text">Upload your GCash/Maya QR code for direct payments. Max size: 2MB.</div>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            @if($clinic->qr_code)
                                                <div class="position-relative d-inline-block">
                                                    <img id="clinicQrPreview" src="{{ asset('storage/clinics/qr_codes/' . $clinic->qr_code) }}" 
                                                         class="img-thumbnail shadow-sm border-success" 
                                                         style="width:150px; height:150px; object-fit:contain;">
                                                    <span class="position-absolute bottom-0 end-0 bg-success text-white rounded-circle p-1 border border-white">
                                                        <i class="bi bi-check small"></i>
                                                    </span>
                                                </div>
                                            @else
                                                <div class="position-relative d-inline-block">
                                                    <img id="clinicQrPreview" src="" 
                                                         class="img-thumbnail shadow-sm border-success" 
                                                         style="width:150px; height:150px; object-fit:contain; display:none;">
                                                    <div id="clinicQrPlaceholder" class="bg-light rounded d-flex align-items-center justify-content-center border border-dashed mx-auto" style="width:150px; height:150px;">
                                                        <span class="text-muted small">No QR Code</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Branding Card --}}
                            <div class="card border-0 shadow-sm rounded-4 mb-4">
                                <div class="card-header bg-white border-bottom border-light p-3">
                                    <h5 class="fw-bold text-success mb-0"><i class="bi bi-image me-2"></i>Clinic Branding</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <label class="form-label fw-semibold text-secondary">Profile Image</label>
                                            <input type="file" name="profile_image" id="clinicProfileInput" class="form-control bg-light mb-2" accept="image/*">
                                            <div class="form-text">Supported formats: JPG, PNG, JPEG. Max size: 2MB.</div>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            @if($clinic->profile_image)
                                                <div class="position-relative d-inline-block">
                                                    <img id="clinicProfilePreview" src="{{ asset('storage/clinics/' . $clinic->profile_image) }}" 
                                                         class="img-thumbnail rounded-circle shadow-sm border-success" 
                                                         style="width:120px; height:120px; object-fit:cover;">
                                                    <span class="position-absolute bottom-0 end-0 bg-success text-white rounded-circle p-1 border border-white">
                                                        <i class="bi bi-check small"></i>
                                                    </span>
                                                </div>
                                            @else
                                                <div class="position-relative d-inline-block">
                                                    <img id="clinicProfilePreview" src="" 
                                                         class="img-thumbnail rounded-circle shadow-sm border-success" 
                                                         style="width:120px; height:120px; object-fit:cover; display:none;">
                                                    <div id="clinicProfilePlaceholder" class="bg-light rounded-circle d-flex align-items-center justify-content-center border border-dashed mx-auto" style="width:120px; height:120px;">
                                                        <span class="text-muted small">No Image</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row align-items-center mt-4 border-top pt-4 border-light">
                                        <div class="col-12">
                                            <label class="form-label fw-semibold text-secondary">Clinic Gallery</label>
                                            <input type="file" name="gallery[]" class="form-control bg-light mb-2" multiple accept="image/*">
                                            <div class="form-text">Upload photos of your clinic facilities, team, or happy patients. Max 5 images.</div>
                                        </div>
                                        @if($clinic->gallery && is_array($clinic->gallery))
                                            <div class="col-12 mt-3">
                                                <div class="d-flex gap-2 flex-wrap">
                                                    @foreach($clinic->gallery as $image)
                                                        <div class="position-relative">
                                                            <img src="{{ asset('storage/clinics/gallery/' . $image) }}" 
                                                                 class="rounded shadow-sm" 
                                                                 style="width: 80px; height: 80px; object-fit: cover;">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Services Card --}}
                            <div class="card border-0 shadow-sm rounded-4 mb-4">
                                <div class="card-header bg-white border-bottom border-light p-3">
                                    <h5 class="fw-bold text-success mb-0"><i class="bi bi-paw me-2"></i>Animals You Serve</h5>
                                </div>
                                <div class="card-body p-4">
                                    <p class="text-muted small mb-3">Select all the animals your clinic is equipped to handle.</p>
                                    
                                    <div class="row g-2">
                                        @php
                                            $animalList = [
                                                'Dogs','Cats','Cows','Sheep','Goats','Pigs','Horses','Rabbits',
                                                'Chickens','Ducks','Turkeys','Geese','Parrots','Hamsters',
                                                'Guinea Pigs','Mice','Rats'
                                            ];

                                            $clinicAnimals = is_array($clinic->specializations) 
                                                             ? $clinic->specializations 
                                                             : json_decode($clinic->specializations, true);

                                            if (!is_array($clinicAnimals)) {
                                                $clinicAnimals = [];
                                            }
                                        @endphp

                                        @foreach($animalList as $animal)
                                            <div class="col-6 col-sm-4 col-md-3">
                                                <input type="checkbox" class="btn-check" id="animal_{{ $loop->index }}" 
                                                       name="specializations[]" value="{{ $animal }}"
                                                       {{ in_array($animal, $clinicAnimals) ? 'checked' : '' }}>
                                                <label class="btn btn-outline-success rounded-pill w-100 d-flex align-items-center justify-content-center gap-2 py-2 shadow-sm" 
                                                       for="animal_{{ $loop->index }}">
                                                    @if($animal == 'Dogs') <i class="bi bi-emoji-smile"></i>
                                                    @elseif($animal == 'Cats') <i class="bi bi-emoji-wink"></i>
                                                    @else <i class="bi bi-circle-fill" style="font-size: 0.5rem;"></i>
                                                    @endif
                                                    {{ $animal }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-5">
                                <button type="submit" class="btn btn-success rounded-pill px-5 py-3 shadow hover-scale">
                                    <i class="bi bi-save me-2"></i> Save Changes
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@include('staff.partials.scripts')

<style>
    .cursor-pointer { cursor: pointer; }
    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: scale(1.02); }
</style>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addressInput = document.getElementById('clinicAddressInput');
        const suggestionsList = document.getElementById('addressSuggestions');
        let debounceTimer;

        // --- Map Initialization ---
        // Default to Cebu City (approx center)
        const defaultLat = 10.3157; 
        const defaultLon = 123.8854;
        
        const map = L.map('map').setView([defaultLat, defaultLon], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const marker = L.marker([defaultLat, defaultLon], {draggable: true}).addTo(map);

        // Helper: Update map from address string
        function geocodeAndSetMap(address) {
            if(!address) return;
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&countrycodes=ph&limit=1`)
                .then(response => response.json())
                .then(data => {
                    if(data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lon = parseFloat(data[0].lon);
                        const newLatLng = new L.LatLng(lat, lon);
                        marker.setLatLng(newLatLng);
                        map.setView(newLatLng, 15);
                    }
                })
                .catch(err => console.error('Geocoding error:', err));
        }

        // Helper: Update address input from lat/lon
        function reverseGeocodeAndSetInput(lat, lon) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
                .then(response => response.json())
                .then(data => {
                    if(data.display_name) {
                        addressInput.value = data.display_name;
                    }
                })
                .catch(err => console.error('Reverse geocoding error:', err));
        }

        // Initialize map with current address if available
        if (addressInput && addressInput.value.trim() !== '') {
            geocodeAndSetMap(addressInput.value);
        }

        // Map Event: Marker Drag
        marker.on('dragend', function(e) {
            const lat = e.target.getLatLng().lat;
            const lon = e.target.getLatLng().lng;
            reverseGeocodeAndSetInput(lat, lon);
        });

        // Map Event: Map Click
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            reverseGeocodeAndSetInput(e.latlng.lat, e.latlng.lng);
        });

        if (!addressInput || !suggestionsList) return;

        addressInput.addEventListener('input', function() {
            const query = this.value.trim();
            clearTimeout(debounceTimer);

            if (query.length < 3) {
                suggestionsList.style.display = 'none';
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=ph&addressdetails=1&limit=5`)
                    .then(response => response.json())
                    .then(data => {
                        suggestionsList.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(item => {
                                const li = document.createElement('li');
                                li.className = 'list-group-item list-group-item-action cursor-pointer';
                                li.textContent = item.display_name;
                                li.onclick = function() {
                                    addressInput.value = item.display_name;
                                    suggestionsList.style.display = 'none';
                                    
                                    // Update Map
                                    const lat = parseFloat(item.lat);
                                    const lon = parseFloat(item.lon);
                                    const newLatLng = new L.LatLng(lat, lon);
                                    marker.setLatLng(newLatLng);
                                    map.setView(newLatLng, 15);
                                };
                                suggestionsList.appendChild(li);
                            });
                            suggestionsList.style.display = 'block';
                        } else {
                            suggestionsList.style.display = 'none';
                        }
                    })
                    .catch(err => console.error('Error fetching address suggestions:', err));
            }, 300);
        });

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!addressInput.contains(e.target) && !suggestionsList.contains(e.target)) {
                suggestionsList.style.display = 'none';
            }
        });

        const qrInput = document.getElementById('clinicQrInput');
        const qrPreview = document.getElementById('clinicQrPreview');
        const qrPlaceholder = document.getElementById('clinicQrPlaceholder');

        if (qrInput && qrPreview) {
            qrInput.addEventListener('change', function () {
                const file = this.files && this.files[0];
                if (!file) return;
                const url = URL.createObjectURL(file);
                qrPreview.src = url;
                qrPreview.style.display = 'block';
                if (qrPlaceholder) {
                    qrPlaceholder.remove();
                }
            });
        }

        const profileInput = document.getElementById('clinicProfileInput');
        const profilePreview = document.getElementById('clinicProfilePreview');
        const profilePlaceholder = document.getElementById('clinicProfilePlaceholder');

        if (profileInput && profilePreview) {
            profileInput.addEventListener('change', function () {
                const file = this.files && this.files[0];
                if (!file) return;
                const url = URL.createObjectURL(file);
                profilePreview.src = url;
                profilePreview.style.display = 'block';
                if (profilePlaceholder) {
                    profilePlaceholder.remove();
                }
            });
        }
    });
</script>
@endsection
