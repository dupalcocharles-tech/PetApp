@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    :root {
        --primary-green: #0d9488;
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
    
    .pet-row {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .pet-row:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px -10px rgba(13, 148, 136, 0.15) !important;
        border-color: var(--primary-green) !important;
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

    /* Dark Theme Support */
    body.dark-theme .card {
        background-color: #1e1e1e;
        color: #e0e0e0;
        border-color: #333;
    }
    body.dark-theme .text-dark { color: #e0e0e0 !important; }
    body.dark-theme .text-muted { color: #adb5bd !important; }
    body.dark-theme .bg-light { background-color: #2a2a2a !important; }
    body.dark-theme .table { color: #e0e0e0; border-color: #333; }
    body.dark-theme .table-light { background-color: #2a2a2a; color: #e0e0e0; }
    body.dark-theme .modal-content { background-color: #1e1e1e; color: #e0e0e0; border-color: #444; }
    body.dark-theme .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }
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
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 main-content">
             <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 mt-5 mt-md-0 gap-3">
                <h4 class="fw-bold mb-0" style="color: var(--primary-dark);">Medical Records</h4>
                
                <div class="d-flex align-items-center gap-3 w-100 w-md-auto">
                    {{-- Search Bar --}}
                    <form action="{{ route('clinic.records') }}" method="GET" class="d-flex flex-grow-1">
                        <div class="input-group shadow-sm rounded-pill overflow-hidden bg-white border">
                            <span class="input-group-text bg-white border-0 ps-3">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-0 py-2" 
                                   placeholder="Search by pet name..." 
                                   value="{{ request('search') }}">
                            <button class="btn btn-primary px-4" type="submit">Search</button>
                        </div>
                    </form>
                    
                    <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill shadow-sm fs-6 text-nowrap">
                        <i class="bi bi-folder2-open me-1"></i> {{ $pets->total() }} Pets
                    </div>
                </div>
            </div>

            <div class="row g-4">
                @forelse($pets as $pet)
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden pet-row" onclick="openRecordsModal({{ $pet->id }})">
                            <div class="card-body p-4">
                                <div class="d-flex flex-column flex-md-row align-items-center gap-4">
                                    {{-- Pet Image --}}
                                    <div class="position-relative">
                                        <img src="{{ $pet->image_url }}" 
                                             class="rounded-circle border border-3 border-success shadow-sm" 
                                             width="80" height="80" style="object-fit: cover;">
                                        <span class="position-absolute bottom-0 end-0 bg-white border rounded-circle p-1 small" title="{{ $pet->species }}">
                                            @if(strtolower($pet->species) == 'dog') <i class="bi bi-emoji-smile"></i>
                                            @elseif(strtolower($pet->species) == 'cat') <i class="bi bi-emoji-heart-eyes"></i>
                                            @else <i class="bi bi-egg-fill"></i> @endif
                                        </span>
                                    </div>

                                    {{-- Pet Info --}}
                                    <div class="flex-grow-1 text-center text-md-start">
                                        <h5 class="fw-bold mb-1">{{ $pet->name }}</h5>
                                        <p class="text-muted mb-0 small">
                                            <span class="badge bg-light text-dark border">{{ $pet->breed ?? $pet->species }}</span>
                                            <span class="mx-1">•</span>
                                            {{ $pet->age }} years old
                                            <span class="mx-1">•</span>
                                            Owner: <span class="fw-medium text-dark">{{ $pet->owner->full_name ?? 'Unknown' }}</span>
                                        </p>
                                    </div>

                                    {{-- Action --}}
                                    <div>
                                        <button class="btn btn-light text-success rounded-pill px-4 fw-medium border" type="button">
                                            View History <i class="bi bi-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-3 opacity-50">
                            <i class="bi bi-search display-1 text-muted"></i>
                        </div>
                        <h5 class="text-muted fw-medium">No medical records found</h5>
                        <p class="text-secondary small">Try adjusting your search or create new records.</p>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-4">
                {{ $pets->withQueryString()->links() }}
            </div>
        </main>
    </div>
</div>

{{-- Records Modal --}}
<div class="modal fade" id="recordsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Medical History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                {{-- Pet Header in Modal --}}
                <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-4 border">
                    <img id="modalPetImage" src="" class="rounded-circle border border-2 border-white shadow-sm" width="60" height="60" style="object-fit: cover;">
                    <div>
                        <h5 id="modalPetName" class="fw-bold mb-0"></h5>
                        <p class="text-muted small mb-0">Owner: <span id="modalOwnerName" class="fw-medium text-dark"></span></p>
                    </div>
                </div>

                {{-- Timeline --}}
                <div id="modalRecordsList" class="d-flex flex-column gap-3">
                    <!-- Records will be injected here -->
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Pass PHP data to JS
    const petsData = @json($pets->items());

    function openRecordsModal(petId) {
        const pet = petsData.find(p => p.id == petId);
        if (!pet) return;

        // Populate Header
        document.getElementById('modalPetImage').src = pet.image_url || '/images/default-pet.png'; // Fallback handled in accessor usually
        document.getElementById('modalPetName').textContent = pet.name;
        document.getElementById('modalOwnerName').textContent = pet.owner ? pet.owner.full_name : 'Unknown';

        // Populate Records
        const listContainer = document.getElementById('modalRecordsList');
        listContainer.innerHTML = ''; // Clear previous

        if (pet.appointments && pet.appointments.length > 0) {
            pet.appointments.forEach(app => {
                if (app.medical_record) { // Check relation existence
                    const date = new Date(app.appointment_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                    const serviceName = app.service ? app.service.name : 'General Service';
                    const weight = app.medical_record.weight || 'N/A';
                    const condition = app.medical_record.health_condition || 'N/A';
                    const notes = app.medical_record.vet_notes || 'No notes.';
                    // Prescriptions: check app.prescriptions (direct column) or fallback
                    const prescriptions = app.prescriptions || 'No prescriptions.';

                    const itemHtml = `
                        <div class="card border border-light shadow-sm rounded-4 overflow-hidden">
                            <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <span class="badge bg-primary bg-opacity-10 text-primary mb-1">${serviceName}</span>
                                    <h6 class="mb-0 fw-bold text-dark">${date}</h6>
                                </div>
                                <span class="badge bg-light text-dark border">Weight: ${weight} kg</span>
                            </div>
                            <div class="card-body pt-0">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <p class="small text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.7rem;">Diagnosis</p>
                                        <p class="mb-0 fw-medium text-dark">${condition}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="small text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.7rem;">Prescriptions</p>
                                        <p class="mb-0 text-danger fw-medium bg-danger bg-opacity-10 p-2 rounded-3 small">
                                            <i class="bi bi-capsule me-1"></i> ${prescriptions}
                                        </p>
                                    </div>
                                    <div class="col-12">
                                        <p class="small text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.7rem;">Vet Notes</p>
                                        <p class="mb-0 text-secondary small bg-light p-2 rounded-3">${notes}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    listContainer.innerHTML += itemHtml;
                }
            });
        } else {
             listContainer.innerHTML = '<div class="text-center text-muted py-4">No records found.</div>';
        }

        // Show Modal
        const modal = new bootstrap.Modal(document.getElementById('recordsModal'));
        modal.show();
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
@endsection

@endsection
