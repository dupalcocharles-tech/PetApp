﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿﻿@extends('layouts.app')

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

    .welcome-box {
        background: linear-gradient(135deg, #ffffff 0%, #f0fdfa 100%);
        border: 1px solid rgba(13, 148, 136, 0.1);
    }

    /* Table Styling */
    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 0.75rem;
        color: var(--text-light);
        background: transparent;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 1rem;
    }

    .table tbody tr {
        transition: all 0.2s ease;
        background: transparent;
    }

    .table tbody tr:hover {
        background-color: rgba(243, 244, 246, 0.6);
        transform: scale(1.01);
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        z-index: 10;
        position: relative;
    }

    .table td {
        vertical-align: middle;
        padding: 1rem 0.5rem;
        border-bottom: 1px solid #f3f4f6;
    }

    /* Badges */
    .badge {
        font-weight: 600;
        letter-spacing: 0.02em;
    }
    
    .badge-soft-success { background: #d1fae5; color: #065f46; }
    .badge-soft-warning { background: #fef3c7; color: #92400e; }
    .badge-soft-danger { background: #fee2e2; color: #991b1b; }
    .badge-soft-secondary { background: #f3f4f6; color: #4b5563; }

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

    .modal-content {
        border-radius: 24px;
        border: none;
        overflow: hidden;
    }
    
    .modal-header {
        background: linear-gradient(135deg, var(--primary-green), var(--primary-dark));
        border-bottom: none;
        padding: 1.5rem;
    }

    /* Dark Theme Support for Clinic Profile Modal */
    body.dark-theme .modal-content {
        background-color: #1e1e1e;
        color: #e0e0e0;
    }
    body.dark-theme .bg-light {
        background-color: #2a2a2a !important;
        border-color: #333 !important;
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
    body.dark-theme .border-white {
        border-color: #1e1e1e !important;
    }
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
            <div id="dashboard-view">
            {{-- Welcome Container --}}
            <div class="welcome-box card border-0 rounded-4 mb-3 p-3 d-flex flex-column flex-md-row align-items-center gap-3 mt-5 mt-md-0">
                <div class="cat-anim text-center">
                    <img src="{{ asset('images/wavecat.gif') }}" alt="Waving Cat" class="cat-waving" width="70" style="filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));">
                </div>
                <div>
                    <h4 class="fw-bold mb-1" style="color: var(--primary-dark);">Hello There, {{ $clinic->clinic_name }}! 🐾</h4>
                    <p class="text-secondary mb-0 fw-medium small">We’re glad to have you here. Let’s take care of some furry friends today!</p>
                </div>
            </div>

            {{-- Appointments --}}
            <div class="card border-0 mb-4 rounded-4 overflow-hidden">
                <div class="card-header bg-white d-flex justify-content-between align-items-center fw-semibold border-0 py-4 px-4">
                    <div class="d-flex align-items-center text-dark">
                        <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3">
                            <i class="bi bi-calendar-check fs-5 text-success"></i>
                        </div>
                        <span class="fs-5 fw-bold">All Appointments</span>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-2 rounded-pill">
                        {{ $appointments->count() }} Total
                    </span>
                </div>
                <div class="card-body p-0">
                    @if($appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 text-center">
                                <thead class="bg-light text-secondary fw-bold small text-uppercase">
                                    <tr>
                                        <th class="py-3 ps-4 text-start">Pet Owner</th>
                                        <th class="py-3">Service</th>
                                        <th class="py-3">Date</th>
                                        <th class="py-3">Payment</th>
                                        <th class="py-3">Status</th>
                                        <th class="py-3 pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="small fw-medium">
                                    @foreach($appointments as $appointment)
                                    @php
                                        $petsList = $appointment->pets->count() > 0 
                                            ? $appointment->pets 
                                            : ($appointment->pet ? collect([$appointment->pet]) : collect([]));

                                        $petsData = $petsList->map(fn($p) => [
                                            "id" => $p->id, 
                                            "name" => $p->name, 
                                            "image" => $p->image ? asset("storage/" . $p->image) : asset("images/pets/default.png"),
                                            "species" => $p->species ?? "Unknown",
                                            "completed" => $appointment->medicalRecords->contains("pet_id", $p->id)
                                        ]);
                                    @endphp
                                    <tr style="cursor: pointer;" data-id="{{ $appointment->id }}" 
                                        data-status="{{ $appointment->status }}" 
                                        data-service="{{ optional($appointment->service)->name ?? '' }}"
                                        data-owner="{{ $appointment->owner->full_name ?? 'N/A' }}"
                                        data-appointment-date="{{ $appointment->appointment_date }}"
                                        data-payment-method="{{ $appointment->payment_method ?? '' }}"
                                        data-payment-option="{{ $appointment->payment_option ?? '' }}"
                                        data-payment-status="{{ $appointment->payment_status ?? '' }}"
                                        data-receipt-url="{{ $appointment->payment_receipt ? asset('storage/' . $appointment->payment_receipt) : '' }}"
                                        data-service-location="{{ $appointment->service_location ?? '' }}"
                                        data-service-address="{{ $appointment->service_address ?? '' }}"
                                        data-service-contact="{{ $appointment->service_contact ?? '' }}"
                                        data-pets='@json($petsData, JSON_HEX_APOS)'>

                                        <td data-label="Pet Owner" class="fw-semibold text-start ps-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <span class="fw-bold">{{ substr($appointment->owner->full_name ?? 'U', 0, 1) }}</span>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="text-dark">{{ $appointment->owner->full_name ?? 'N/A' }}</span>
                                                    <a href="#" class="text-muted small text-decoration-none"
                                                       onclick="viewPetOwnerHistory({{ $appointment->owner->id }}, '{{ addslashes($appointment->owner->full_name ?? 'N/A') }}')">
                                                        <i class="bi bi-clock-history me-1"></i> History
                                                    </a>
                                                </div>
                                            </div>
                                        </td>

                                        <td data-label="Service">
                                            <span class="badge bg-light text-dark border border-secondary border-opacity-25 px-3 py-2 fw-medium">{{ optional($appointment->service)->name ?? 'No Service' }}</span>
                                        </td>

                                        <td data-label="Date" class="text-secondary">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</span>
                                                <span class="small">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}</span>
                                            </div>
                                        </td>

                                        <td data-label="Payment">
                                            @if($appointment->payment_status === 'downpayment_paid')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Verified
                                                </span>
                                            @elseif($appointment->payment_status === 'paid')
                                                <span class="badge bg-success text-white rounded-pill px-3 py-2">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Paid
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-2">
                                                    Unpaid
                                                </span>
                                            @endif
                                        </td>

                                        <td data-label="Status">
                                            <span class="badge px-3 py-2 rounded-pill
                                                @if($appointment->status == 'approved') badge-soft-success
                                                @elseif($appointment->status == 'pending') badge-soft-warning
                                                @elseif($appointment->status == 'cancelled') badge-soft-danger
                                                @else badge-soft-secondary
                                                @endif">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>

                                        <td data-label="Action" class="pe-4">
                                            @if($appointment->status == 'requested')
                                                <div class="d-flex gap-2 justify-content-end justify-content-md-center">
                                                    {{-- Accept --}}
                                                    <form action="{{ route('clinic.appointments.accept', $appointment->id) }}" method="POST" class="accept-btn-form">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm fw-bold">
                                                            <i class="bi bi-check-lg me-1"></i> Accept
                                                        </button>
                                                    </form>

                                                    {{-- Cancel --}}
                                                    <form action="{{ route('appointments.update', $appointment->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold">
                                                            <i class="bi bi-x-lg me-1"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @elseif(in_array($appointment->status, ['approved', 'pending']))
                                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-4 w-100 shadow-sm fw-bold" onclick="openPetsModal(this)">
                                                    View Pets
                                                </button>
                                            @elseif(in_array($appointment->status, ['cancelled', 'completed']))
                                                <span class="text-muted small fst-italic">
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-5 text-center text-muted">
                            <i class="bi bi-clipboard-x display-1 opacity-25"></i>
                            <p class="mt-3 fw-bold">No appointments found.</p>
                        </div>
                    @endif
                </div>
            </div>
            </div> {{-- End dashboard-view --}}

            {{-- Records View --}}
            <div id="records-view" class="d-none">
                <div class="card border-0 mb-4 rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-0 py-4 px-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="d-flex align-items-center text-dark">
                                <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                                    <i class="bi bi-folder2-open fs-5 text-primary"></i>
                                </div>
                                <span class="fs-5 fw-bold">Medical Records</span>
                            </div>
                            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-2 rounded-pill">
                                {{ $records->count() }} Records
                            </span>
                        </div>
                        <div class="position-relative">
                            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary opacity-50"></i>
                            <input type="text" id="recordSearchInput" class="form-control form-control-lg bg-light border-0 rounded-pill ps-5 shadow-sm" placeholder="Search by Pet Name..." onkeyup="filterRecords()" onkeypress="checkEnter(event)" style="font-size: 0.95rem;">
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($records->count() > 0)
                            <div class="table-responsive">
                                <table class="table align-middle mb-0 text-center" id="recordsTable">
                                    <thead class="bg-light text-secondary fw-bold small text-uppercase">
                                        <tr>
                                            <th class="py-3 ps-4 text-start">Pet</th>
                                            <th class="py-3">Owner</th>
                                            <th class="py-3">Date</th>
                                            <th class="py-3">Service</th>
                                            <th class="py-3 pe-4">Condition</th>
                                        </tr>
                                    </thead>
                                    <tbody class="small fw-medium">
                                        @foreach($records as $record)
                                        <tr style="cursor: pointer;" onclick="viewRecord(this)"
                                            data-pet-name="{{ $record->pet->name }}"
                                            data-pet-image="{{ $record->pet->image ? asset('storage/' . $record->pet->image) : asset('images/pets/default.png') }}"
                                            data-owner="{{ $record->appointment->owner->full_name ?? 'Unknown' }}"
                                            data-date="{{ \Carbon\Carbon::parse($record->appointment->appointment_date)->format('M d, Y') }}"
                                            data-service="{{ optional($record->appointment->service)->name ?? 'N/A' }}"
                                            data-condition="{{ $record->health_condition ?? 'N/A' }}"
                                            data-weight="{{ $record->weight ?? '' }}"
                                            data-vaccine="{{ $record->vaccine_status ?? 'N/A' }}"
                                            data-vaccine-dates="{{ $record->vaccination_dates ?? '' }}"
                                            data-notes="{{ $record->vet_notes ?? '' }}">
                                            
                                            <td class="fw-semibold text-start ps-4">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $record->pet->image ? asset('storage/' . $record->pet->image) : asset('images/pets/default.png') }}" 
                                                         class="rounded-circle me-3 border border-2 border-white shadow-sm" width="45" height="45" style="object-fit:cover;">
                                                    <span class="pet-name text-dark fw-bold">{{ $record->pet->name }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $record->appointment->owner->full_name ?? 'N/A' }}</td>
                                            <td class="text-secondary">{{ \Carbon\Carbon::parse($record->appointment->appointment_date)->format('M d, Y') }}</td>
                                            <td><span class="badge bg-light text-dark border border-secondary border-opacity-25 px-3 py-2 fw-medium rounded-pill">{{ optional($record->appointment->service)->name ?? 'N/A' }}</span></td>
                                            <td class="text-muted pe-4">{{ Str::limit($record->health_condition ?? 'N/A', 50) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-5 text-center text-muted">
                                <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                                    <i class="bi bi-folder-x display-4 text-secondary opacity-50"></i>
                                </div>
                                <p class="fw-bold text-dark">No medical records found.</p>
                                <p class="small text-secondary">Records will appear here once appointments are completed.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <footer class="text-center mt-5 mb-3">
                <p class="text-muted small mb-0">© {{ date('Y') }} {{ config('app.name', 'PetApp') }}. All Rights Reserved.</p>
            </footer>
        </main>
    </div>
</div>

{{-- ✅ Success Modal --}}
@if(session('success'))
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header text-white">
        <h5 class="modal-title" id="successModalLabel"><i class="bi bi-check-circle me-2"></i> Success</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center py-4">
        <div class="mb-3">
            <i class="bi bi-check-circle-fill text-success display-1"></i>
        </div>
        <p class="mb-0 fw-semibold text-dark fs-5">{{ session('success') }}</p>
      </div>
      <div class="modal-footer border-0 justify-content-center pb-4">
        <button type="button" class="btn btn-success rounded-pill px-5 shadow-sm fw-bold" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>
@endif

{{-- ✅ Appointment Modal --}}
<div class="modal fade" id="appointmentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <form id="appointmentForm" class="modal-content">
      <div class="modal-header text-white">
        <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i> Appointment Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <div class="modal-body p-4">
           <div class="d-flex align-items-center mb-4 bg-light p-3 rounded-4 border border-secondary border-opacity-10">
              <!-- Pet Image -->
              <img id="modalPetImage" src="{{ asset('images/pets/default.png') }}" 
                   alt="Pet Image" class="shadow-sm rounded-circle border border-2 border-white" style="width:80px; height:80px; object-fit:cover;">

              <!-- Pet Name -->
              <div class="ms-3">
                  <h4 class="fw-bold text-dark mb-1" id="modalPetName">Pet Name</h4>
                  <!-- Hidden input for appointment ID -->
                  <input type="hidden" id="modalAppointmentId">
                  <input type="hidden" id="modalPetId" name="pet_id">
              </div>
           </div>

          {{-- Pet Info --}}
          <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label fw-bold text-secondary small text-uppercase">Weight (kg)</label>
                <input type="number" step="0.01" class="form-control bg-light" name="weight" required>
              </div>
              
              {{-- Vaccination Button (Conditional) --}}
              <div class="col-md-6 d-flex align-items-end" id="vaccinationButtonContainer" style="display: none;">
                 <button type="button" class="btn btn-warning w-100 fw-bold text-white shadow-sm" id="manageVaccineBtn">
                    <i class="bi bi-shield-plus me-2"></i> Manage Vaccination
                 </button>
              </div>
          </div>
          
          {{-- Hidden Vaccine Fields --}}
          <div id="vaccineFieldsContainer" class="p-3 mb-3 bg-warning-subtle rounded-3 border border-warning-subtle" style="display: none;">
              <h6 class="fw-bold text-warning-emphasis mb-3"><i class="bi bi-capsule me-2"></i>Vaccination Details</h6>
              
              <div class="mb-3">
                <label class="form-label fw-bold text-secondary small text-uppercase">Vaccine Information</label>
                <input type="text" class="form-control" name="vaccine_status" placeholder="e.g. Vaccine Name 1st Dose, Booster">
              </div>
              
              <div class="mb-0">
                <label class="form-label fw-bold text-secondary small text-uppercase">Vaccination Dates</label>
                <input type="text" class="form-control" name="vaccination_dates" placeholder="e.g. 2023-10-01, 2023-11-01">
              </div>
          </div>

          <hr class="my-4 opacity-10">
          <div class="d-flex justify-content-between align-items-center mb-3">
              <h6 class="fw-bold text-primary mb-0"><i class="bi bi-calendar-event me-2"></i>Schedule Next Appointment</h6>
              <button type="button" class="btn btn-sm btn-outline-primary rounded-pill fw-bold" id="toggleNextAppointmentBtn">
                  <i class="bi bi-plus-lg me-1"></i> Add Schedule
              </button>
          </div>
          
          <div id="nextAppointmentContainer" style="display: none;">
              <div class="mb-3">
                <label class="form-label fw-bold text-secondary small text-uppercase">Next Appointment Date & Time</label>
                <input type="datetime-local" class="form-control bg-light border-0" name="next_appointment" id="nextAppointment">
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold text-secondary small text-uppercase">Reason / Notes</label>
                <textarea class="form-control bg-light border-0" name="next_notes" rows="2" placeholder="Optional"></textarea>
              </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold text-secondary small text-uppercase">Health Condition</label>
            <textarea class="form-control bg-light border-0" name="health_condition" rows="2"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold text-secondary small text-uppercase">Vet Notes</label>
            <textarea class="form-control bg-light border-0" name="vet_notes" rows="2"></textarea>
          </div>

          {{-- Digital Prescription --}}
          <hr class="my-4 opacity-10">
          <div class="d-flex justify-content-between align-items-center mb-3">
              <h6 class="fw-bold text-primary mb-0"><i class="bi bi-prescription2 me-2"></i>Digital Prescription</h6>
              <button type="button" class="btn btn-sm btn-outline-primary rounded-pill fw-bold" id="togglePrescriptionBtn">
                  <i class="bi bi-plus-lg me-1"></i> Add Prescription
              </button>
          </div>

          <div id="prescriptionWrapper" style="display: none;">
              <div id="prescriptionContainer">
                  <div class="prescription-item mb-3 border border-secondary border-opacity-25 bg-light p-3 rounded-3">
                      <div class="mb-2">
                          <label class="form-label fw-bold text-primary">Medicine</label>
                          <input type="text" class="form-control border-0 bg-white shadow-sm" name="medicine[]" placeholder="Enter medicine name" required disabled>
                      </div>

                      <div class="mb-2">
                          <label class="form-label fw-bold text-dark small">How to Administer</label>
                          <textarea class="form-control border-0 bg-white shadow-sm" name="administration[]" rows="2" placeholder="Ex: Give orally using syringe after meals" required disabled></textarea>
                      </div>

                      <div class="mb-2">
                          <label class="form-label fw-bold text-dark small">How Often Should It Be Taken</label>
                          <textarea class="form-control border-0 bg-white shadow-sm" name="frequency[]" rows="2" placeholder="Ex: 2 times a day (morning and evening)" required disabled></textarea>
                      </div>

                      <div class="mb-2">
                          <label class="form-label fw-bold text-dark small">Duration</label>
                          <textarea class="form-control border-0 bg-white shadow-sm" name="duration[]" rows="2" placeholder="Ex: 7 days or until symptoms improve" required disabled></textarea>
                      </div>
                  </div>
              </div>

              <button type="button" id="addMedicationBtn" class="btn btn-sm btn-outline-success mt-2 rounded-pill fw-bold">
                <i class="bi bi-plus-circle me-1"></i> Add Another Prescription
              </button>
          </div>

        </div>

        <div class="modal-footer border-0 bg-white rounded-bottom-4 pb-4 pe-4">
            <button type="button" class="btn btn-light rounded-pill px-4 me-2 fw-bold text-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">Complete Appointment</button>
        </div>
    </form>
  </div>
</div>

<div class="modal fade" id="clinicAppointmentDetailsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content rounded-4 shadow-lg">
      <div class="modal-header text-white">
        <h5 class="modal-title fw-bold"><i class="bi bi-receipt-cutoff me-2"></i>Appointment Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="d-flex flex-column gap-3">
          <div class="bg-light p-3 rounded-4 border border-secondary border-opacity-10">
            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
              <div>
                <div class="text-muted small fw-bold text-uppercase">Pet Owner</div>
                <div class="fw-bold text-dark" id="clinicApptOwner">N/A</div>
              </div>
              <div class="text-end">
                <div class="text-muted small fw-bold text-uppercase">Status</div>
                <div class="fw-bold text-dark" id="clinicApptStatus">N/A</div>
              </div>
            </div>
            <hr class="my-3 opacity-10">
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <div class="text-muted small fw-bold text-uppercase">Service</div>
                <div class="fw-bold text-dark" id="clinicApptService">N/A</div>
              </div>
              <div class="col-12 col-md-6">
                <div class="text-muted small fw-bold text-uppercase">Date & Time</div>
                <div class="fw-bold text-dark" id="clinicApptDate">N/A</div>
              </div>
              <div class="col-12 col-md-6">
                <div class="text-muted small fw-bold text-uppercase">Payment</div>
                <div class="fw-bold text-dark" id="clinicApptPayment">N/A</div>
              </div>
              <div class="col-12 col-md-6">
                <div class="text-muted small fw-bold text-uppercase">Service Location</div>
                <div class="fw-bold text-dark" id="clinicApptLocation">N/A</div>
              </div>
            </div>
            <div class="mt-3 d-none" id="clinicApptHomeDetails">
              <div class="text-muted small fw-bold text-uppercase mb-2">Home Service Details</div>
              <div class="d-flex flex-column gap-2">
                <div class="d-flex justify-content-between gap-3 flex-wrap">
                  <div class="text-muted">Address</div>
                  <div class="fw-bold text-dark text-end" id="clinicApptHomeAddress">N/A</div>
                </div>
                <div class="d-flex justify-content-between gap-3 flex-wrap">
                  <div class="text-muted">Contact</div>
                  <div class="fw-bold text-dark text-end" id="clinicApptHomeContact">N/A</div>
                </div>
              </div>
            </div>
          </div>

          <div>
            <div class="text-muted small fw-bold text-uppercase mb-2">Pets</div>
            <div class="d-flex flex-wrap gap-2" id="clinicApptPets"></div>
          </div>

          <div class="d-none" id="clinicApptReceiptSection">
            <div class="text-muted small fw-bold text-uppercase mb-2">Online Payment Receipt</div>
            <div class="bg-light p-3 rounded-4 border border-secondary border-opacity-10 d-flex flex-column flex-md-row gap-3 align-items-start">
              <img id="clinicApptReceiptImg" src="" alt="Receipt" class="rounded-3 border shadow-sm d-none" style="width: 200px; height: 200px; object-fit: cover;">
              <div class="flex-grow-1">
                <div class="fw-bold text-dark mb-2" id="clinicApptReceiptTitle">Receipt</div>
                <a id="clinicApptReceiptLink" href="#" target="_blank" class="btn btn-outline-primary rounded-pill px-4 d-none">
                  View Full Receipt
                </a>
                <div class="text-muted small d-none" id="clinicApptReceiptMissing">No receipt uploaded yet.</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer border-0 bg-white rounded-bottom-4 pb-4 pe-4">
        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

{{-- ✅ View Pets Modal --}}
<div class="modal fade" id="viewPetsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header text-white">
        <h5 class="modal-title"><i class="bi bi-people-fill me-2"></i> Manage Pets</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <h6 class="fw-bold mb-3 text-dark">Pets in this Appointment:</h6>
        <div id="petsList" class="d-flex flex-column gap-3">
            <!-- Pets will be injected here via JS -->
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ✅ Clinic Profile Modal --}}
<div class="modal fade" id="clinicProfileModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
      <div class="modal-header border-0 p-0 position-relative" style="height: 150px; background: linear-gradient(135deg, var(--primary-green), var(--primary-dark));">
         <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-4 pb-5 pt-0 text-center mt-n5">
         <div class="position-relative d-inline-block mb-3">
            <img src="{{ $clinic->profile_image ? asset('storage/clinics/' . $clinic->profile_image) : asset('images/clinics/default.png') }}" 
                 class="rounded-circle border border-4 border-white shadow-lg bg-white" 
                 width="140" height="140" style="object-fit: cover;">
            <span class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle p-2" title="Verified Clinic">
                <i class="bi bi-check-lg text-white"></i>
            </span>
         </div>
         
         <h3 class="fw-bold text-dark mb-1">{{ $clinic->clinic_name }}</h3>
         <p class="text-muted fw-medium mb-3"><i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $clinic->address }}</p>

         <div class="text-start bg-light p-4 rounded-4 mb-4 border border-light shadow-sm">
            <h5 class="fw-bold text-dark mb-3 border-bottom pb-2 border-secondary border-opacity-10">About Us</h5>
            <p class="text-secondary mb-0" style="line-height: 1.6;">
                {{ $clinic->description ?? 'Welcome to ' . $clinic->clinic_name . '! We are dedicated to providing the best care for your pets. Our team of experienced veterinarians is here to help keep your furry friends happy and healthy.' }}
            </p>
            <div class="mt-3 pt-3 border-top border-secondary border-opacity-10 d-flex flex-wrap gap-3 text-secondary small">
                <span><i class="bi bi-telephone-fill me-1"></i> {{ $clinic->phone }}</span>
                <span><i class="bi bi-envelope-fill me-1"></i> {{ $clinic->email }}</span>
            </div>
         </div>

         <div class="text-start mb-4">
            <h5 class="fw-bold text-dark mb-3 border-bottom pb-2 border-secondary border-opacity-10">Gallery</h5>
            <div class="row g-2">
                <div class="col-4">
                    <div class="ratio ratio-1x1 rounded-3 overflow-hidden bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center text-muted">
                        <i class="bi bi-image fs-1 opacity-25"></i>
                    </div>
                </div>
                <div class="col-4">
                    <div class="ratio ratio-1x1 rounded-3 overflow-hidden bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center text-muted">
                        <i class="bi bi-image fs-1 opacity-25"></i>
                    </div>
                </div>
                <div class="col-4">
                    <div class="ratio ratio-1x1 rounded-3 overflow-hidden bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center text-muted">
                        <i class="bi bi-image fs-1 opacity-25"></i>
                    </div>
                </div>
            </div>
         </div>

         <div class="d-grid">
            <a href="{{ route('clinic.edit') }}" class="btn btn-primary rounded-pill py-2 fw-bold shadow-sm">
                <i class="bi bi-pencil-square me-2"></i> Edit Profile
            </a>
         </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
@include('staff.partials.scripts')
<script>
    // ✅ Records View Toggle (Global Scope)
    window.showSection = function(sectionId, element) {
        if(sectionId === 'records') {
            document.getElementById('dashboard-view').classList.add('d-none');
            document.getElementById('records-view').classList.remove('d-none');
            
            // Update Sidebar Active State
            document.querySelectorAll('.nav-link').forEach(el => el.classList.remove('active', 'bg-success', 'shadow-sm'));
            if(element) element.classList.add('active', 'bg-success', 'shadow-sm');
        }
    }

    // ✅ Records Search Filter (Global Scope)
    window.filterRecords = function() {
        let input = document.getElementById('recordSearchInput');
        let filter = input.value.toUpperCase();
        let table = document.getElementById('recordsTable');
        let tr = table.getElementsByTagName('tr');

        for (let i = 0; i < tr.length; i++) {
            let td = tr[i].getElementsByTagName('td')[0]; // First column is Pet Name
            if (td) {
                let txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }       
        }
    }

    // ✅ Open Pets Modal
    window.openPetsModal = function(btn) {
        let row = btn.closest('tr');
        let pets = JSON.parse(row.dataset.pets);
        let appointmentId = row.dataset.id;
        let serviceName = row.dataset.service || '';
        
        let listContainer = document.getElementById('petsList');
        listContainer.innerHTML = '';
        
        pets.forEach(pet => {
            let item = document.createElement('div');
            item.className = `d-flex align-items-center justify-content-between p-3 bg-white rounded-3 border border-secondary border-opacity-10 shadow-sm ${pet.completed ? 'opacity-75' : ''}`;
            item.innerHTML = `
                <div class="d-flex align-items-center">
                    <img src="${pet.image}" class="rounded-circle me-3 border border-2 border-white shadow-sm" width="50" height="50" style="object-fit:cover; ${pet.completed ? 'filter: grayscale(100%);' : ''}">
                    <div>
                        <h6 class="fw-bold mb-0 ${pet.completed ? 'text-muted text-decoration-line-through' : 'text-dark'}">${pet.name}</h6>
                        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.75rem;">${pet.species}</small>
                    </div>
                </div>
                <div>
                    ${pet.completed 
                        ? '<span class="badge bg-secondary bg-opacity-25 text-secondary border border-secondary border-opacity-25 rounded-pill px-3 py-2"><i class="bi bi-check-circle me-1"></i> Done</span>' 
                        : `<button class="btn btn-sm btn-primary rounded-pill px-4 shadow-sm fw-bold" onclick="fillPetForm('${pet.id}', '${pet.name.replace(/'/g, "\\'")}', '${pet.image}', '${appointmentId}', '${serviceName.replace(/'/g, "\\'")}')">Fill Form</button>`
                    }
                </div>
            `;
            listContainer.appendChild(item);
        });
        
        new bootstrap.Modal(document.getElementById('viewPetsModal')).show();
    }

    // ✅ Fill Pet Form
    window.fillPetForm = function(petId, petName, petImage, appointmentId, serviceName) {
        // Close view pets modal
        bootstrap.Modal.getInstance(document.getElementById('viewPetsModal')).hide();
        
        // Populate Appointment Modal
        document.getElementById('modalAppointmentId').value = appointmentId;
        document.getElementById('modalPetId').value = petId;
        document.getElementById('modalPetName').textContent = petName;
        document.getElementById('modalPetImage').src = petImage;

        // Handle Vaccination Button Visibility
        let btnContainer = document.getElementById('vaccinationButtonContainer');
        let fieldsContainer = document.getElementById('vaccineFieldsContainer');
        
        // Reset state
        if(btnContainer) btnContainer.style.display = 'none';
        if(fieldsContainer) fieldsContainer.style.display = 'none';
        
        // Check if service is vaccination (case insensitive)
        const keywords = ['vaccin', 'rabies', 'immuniz', 'shot', 'booster', 'injection'];
        const isVaccination = serviceName && keywords.some(keyword => serviceName.toLowerCase().includes(keyword));
        
        console.log('Service Check:', { name: serviceName, isVaccination: isVaccination });

        // Show Service Name for Confirmation
        let serviceLabel = document.getElementById('modalServiceLabel');
        if (!serviceLabel) {
             let petInfoRow = btnContainer ? btnContainer.parentElement : document.querySelector('#appointmentForm .row.g-3.mb-3');
             if(petInfoRow) {
                 serviceLabel = document.createElement('div');
                 serviceLabel.id = 'modalServiceLabel';
                 serviceLabel.className = 'col-12 text-muted small mt-1';
                 petInfoRow.appendChild(serviceLabel);
             }
        }
        if(serviceLabel) serviceLabel.textContent = 'Service Detected: ' + (serviceName || 'None');

        if (isVaccination && btnContainer) {
            btnContainer.style.display = 'flex';
            btnContainer.classList.remove('d-none');
        }
        
        // Reset form fields
        document.getElementById('appointmentForm').reset();
        
        // Reset Schedule Next Appointment
        const nextApptContainer = document.getElementById('nextAppointmentContainer');
        const toggleNextApptBtn = document.getElementById('toggleNextAppointmentBtn');
        if(nextApptContainer) {
            nextApptContainer.style.display = 'none';
        }
        if(toggleNextApptBtn) {
            toggleNextApptBtn.innerHTML = '<i class="bi bi-plus-lg me-1"></i> Add Schedule';
            toggleNextApptBtn.classList.replace('btn-outline-danger', 'btn-outline-primary');
        }

        // Reset Digital Prescription
        const prescriptionWrapper = document.getElementById('prescriptionWrapper');
        const togglePrescriptionBtn = document.getElementById('togglePrescriptionBtn');
        if(prescriptionWrapper) {
            prescriptionWrapper.style.display = 'none';
        }
        if(togglePrescriptionBtn) {
            togglePrescriptionBtn.innerHTML = '<i class="bi bi-plus-lg me-1"></i> Add Prescription';
            togglePrescriptionBtn.classList.replace('btn-outline-danger', 'btn-outline-primary');
        }

        document.getElementById('prescriptionContainer').innerHTML = `
              <div class="prescription-item mb-3 border border-secondary border-opacity-25 bg-light p-3 rounded-3">
                  <div class="mb-2">
                      <label class="form-label fw-bold text-primary">Medicine</label>
                      <input type="text" class="form-control border-0 bg-white shadow-sm" name="medicine[]" placeholder="Enter medicine name" required disabled>
                  </div>
                  <div class="mb-2">
                      <label class="form-label fw-bold text-dark small">How to Administer</label>
                      <textarea class="form-control border-0 bg-white shadow-sm" name="administration[]" rows="2" placeholder="Ex: Give orally using syringe after meals" required disabled></textarea>
                  </div>
                  <div class="mb-2">
                      <label class="form-label fw-bold text-dark small">How Often Should It Be Taken</label>
                      <textarea class="form-control border-0 bg-white shadow-sm" name="frequency[]" rows="2" placeholder="Ex: 2 times a day (morning and evening)" required disabled></textarea>
                  </div>
                  <div class="mb-2">
                      <label class="form-label fw-bold text-dark small">Duration</label>
                      <textarea class="form-control border-0 bg-white shadow-sm" name="duration[]" rows="2" placeholder="Ex: 7 days or until symptoms improve" required disabled></textarea>
                  </div>
              </div>`;

        // Open Appointment Modal
        new bootstrap.Modal(document.getElementById('appointmentModal')).show();
    }

document.addEventListener("DOMContentLoaded", function(){

    // Show success modal if it exists
    var successModalEl = document.getElementById('successModal');
    if(successModalEl){
        var successModal = new bootstrap.Modal(successModalEl);
        successModal.show();
    }

    const clinicApptDetailsModalEl = document.getElementById('clinicAppointmentDetailsModal');
    const clinicApptDetailsModal = clinicApptDetailsModalEl ? new bootstrap.Modal(clinicApptDetailsModalEl) : null;

    const formatApptDate = (raw) => {
        const value = (raw || '').toString().trim();
        if (!value) return 'N/A';
        const normalized = value.includes('T') ? value : value.replace(' ', 'T');
        const date = new Date(normalized);
        if (Number.isNaN(date.getTime())) return value;
        const d = new Intl.DateTimeFormat('en-US', { month: 'short', day: '2-digit', year: 'numeric' }).format(date);
        const t = new Intl.DateTimeFormat('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }).format(date);
        return `${d} ${t}`;
    };

    document.querySelectorAll('tr[data-id][data-owner][data-service]').forEach(row => {
        row.addEventListener('click', (e) => {
            if (e.target.closest('button, a, form, input, label')) return;
            if (!clinicApptDetailsModal) return;

            const ownerEl = document.getElementById('clinicApptOwner');
            const serviceEl = document.getElementById('clinicApptService');
            const dateEl = document.getElementById('clinicApptDate');
            const statusEl = document.getElementById('clinicApptStatus');
            const paymentEl = document.getElementById('clinicApptPayment');
            const locationEl = document.getElementById('clinicApptLocation');
            const petsEl = document.getElementById('clinicApptPets');
            const receiptSection = document.getElementById('clinicApptReceiptSection');
            const receiptImg = document.getElementById('clinicApptReceiptImg');
            const receiptLink = document.getElementById('clinicApptReceiptLink');
            const receiptMissing = document.getElementById('clinicApptReceiptMissing');
            const homeDetails = document.getElementById('clinicApptHomeDetails');
            const homeAddress = document.getElementById('clinicApptHomeAddress');
            const homeContact = document.getElementById('clinicApptHomeContact');

            if (ownerEl) ownerEl.textContent = row.dataset.owner || 'N/A';
            if (serviceEl) serviceEl.textContent = row.dataset.service || 'N/A';
            if (dateEl) dateEl.textContent = formatApptDate(row.dataset.appointmentDate);
            if (statusEl) statusEl.textContent = (row.dataset.status || 'N/A').toString();

            const pm = (row.dataset.paymentMethod || '').toLowerCase();
            const ps = row.dataset.paymentStatus || '';
            const po = row.dataset.paymentOption || '';
            if (paymentEl) {
                const pieces = [];
                if (pm) pieces.push(pm === 'online' ? 'Online' : 'Clinic');
                if (po) pieces.push(po);
                if (ps) pieces.push(ps);
                paymentEl.textContent = pieces.length ? pieces.join(' • ') : 'N/A';
            }

            const serviceLoc = (row.dataset.serviceLocation || '').toLowerCase();
            if (locationEl) locationEl.textContent = serviceLoc ? serviceLoc : 'clinic';
            if (homeDetails) {
                const isHome = serviceLoc === 'home';
                homeDetails.classList.toggle('d-none', !isHome);
                if (homeAddress) homeAddress.textContent = row.dataset.serviceAddress || 'N/A';
                if (homeContact) homeContact.textContent = row.dataset.serviceContact || 'N/A';
            }

            if (petsEl) {
                petsEl.innerHTML = '';
                let pets = [];
                try {
                    pets = JSON.parse(row.dataset.pets || '[]');
                } catch (_) {}
                pets.forEach(p => {
                    const chip = document.createElement('div');
                    chip.className = 'd-flex align-items-center bg-light rounded-pill pe-3 ps-1 py-1 border border-secondary border-opacity-10';
                    chip.innerHTML = `
                        <img src="${p.image || '{{ asset('images/pets/default.png') }}'}" class="rounded-circle me-2 object-fit-cover border border-2 border-white shadow-sm" style="width: 28px; height: 28px;">
                        <div class="small fw-bold text-dark lh-1">${p.name || 'Pet'}</div>
                    `;
                    petsEl.appendChild(chip);
                });
            }

            if (receiptSection && receiptImg && receiptLink && receiptMissing) {
                const receiptUrl = row.dataset.receiptUrl || '';
                const isOnline = pm === 'online';
                receiptSection.classList.toggle('d-none', !isOnline);
                if (isOnline) {
                    if (receiptUrl) {
                        receiptImg.src = receiptUrl;
                        receiptImg.classList.remove('d-none');
                        receiptLink.href = receiptUrl;
                        receiptLink.classList.remove('d-none');
                        receiptMissing.classList.add('d-none');
                    } else {
                        receiptImg.src = '';
                        receiptImg.classList.add('d-none');
                        receiptLink.href = '#';
                        receiptLink.classList.add('d-none');
                        receiptMissing.classList.remove('d-none');
                    }
                }
            }

            clinicApptDetailsModal.show();
        });
    });

    // Toggle Next Appointment
    const toggleNextApptBtn = document.getElementById('toggleNextAppointmentBtn');
    const nextApptContainer = document.getElementById('nextAppointmentContainer');

    if(toggleNextApptBtn && nextApptContainer){
        toggleNextApptBtn.addEventListener('click', function(){
            const isHidden = nextApptContainer.style.display === 'none';
            if(isHidden){
                nextApptContainer.style.display = 'block';
                toggleNextApptBtn.innerHTML = '<i class="bi bi-dash-lg me-1"></i> Remove Schedule';
                toggleNextApptBtn.classList.replace('btn-outline-primary', 'btn-outline-danger');
            } else {
                nextApptContainer.style.display = 'none';
                toggleNextApptBtn.innerHTML = '<i class="bi bi-plus-lg me-1"></i> Add Schedule';
                toggleNextApptBtn.classList.replace('btn-outline-danger', 'btn-outline-primary');
                // Clear inputs
                nextApptContainer.querySelectorAll('input, textarea').forEach(el => el.value = '');
            }
        });
    }

    // Toggle Prescription
    const togglePrescriptionBtn = document.getElementById('togglePrescriptionBtn');
    const prescriptionWrapper = document.getElementById('prescriptionWrapper');

    if(togglePrescriptionBtn && prescriptionWrapper){
        togglePrescriptionBtn.addEventListener('click', function(){
            const isHidden = prescriptionWrapper.style.display === 'none';
            if(isHidden){
                prescriptionWrapper.style.display = 'block';
                togglePrescriptionBtn.innerHTML = '<i class="bi bi-dash-lg me-1"></i> Remove Prescription';
                togglePrescriptionBtn.classList.replace('btn-outline-primary', 'btn-outline-danger');
                
                // Enable required inputs
                prescriptionWrapper.querySelectorAll('input, textarea').forEach(el => el.disabled = false);
            } else {
                prescriptionWrapper.style.display = 'none';
                togglePrescriptionBtn.innerHTML = '<i class="bi bi-plus-lg me-1"></i> Add Prescription';
                togglePrescriptionBtn.classList.replace('btn-outline-danger', 'btn-outline-primary');
                
                // Disable inputs so they don't block submission
                prescriptionWrapper.querySelectorAll('input, textarea').forEach(el => el.disabled = true);
            }
        });
    }

    // Toggle Vaccination Fields
    let manageVaccineBtn = document.getElementById('manageVaccineBtn');
    if(manageVaccineBtn) {
        manageVaccineBtn.addEventListener('click', function(){
            let fieldsContainer = document.getElementById('vaccineFieldsContainer');
            if (fieldsContainer.style.display === 'none') {
                fieldsContainer.style.display = 'block';
                fieldsContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                fieldsContainer.style.display = 'none';
            }
        });
    }

    // Add new medication row
    document.getElementById('addMedicationBtn').addEventListener('click', function(){
        let container = document.getElementById('prescriptionContainer');
        let item = container.querySelector('.prescription-item').cloneNode(true);
        item.querySelectorAll('input, textarea').forEach(input => input.value = '');
        container.appendChild(item);
    });

    // Reusable appointment modal instance
    var appointmentModalEl = document.getElementById('appointmentModal');
    var appointmentModal = new bootstrap.Modal(appointmentModalEl);

    // Populate modal when opened via button
    appointmentModalEl.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        if (!button || !button.closest('tr')) return;

        var row = button.closest('tr');

        let petImage = row.dataset.petImage || '{{ asset("images/pets/default.png") }}';
        document.getElementById('modalPetImage').src = petImage;

        let petName = row.querySelector('td[data-label="Pet"] span').textContent.trim() || 'N/A';
        document.getElementById('modalPetName').textContent = petName;

        document.getElementById('modalAppointmentId').value = row.dataset.id;

        // Enable all inputs in modal
        appointmentModalEl.querySelectorAll('input, textarea, select, button').forEach(el => el.disabled = false);

        // Reset and Hide Optional Sections
        const nextApptContainer = document.getElementById('nextAppointmentContainer');
        const toggleNextApptBtn = document.getElementById('toggleNextAppointmentBtn');
        if(nextApptContainer) {
             nextApptContainer.style.display = 'none';
             nextApptContainer.querySelectorAll('input, textarea').forEach(el => el.value = '');
        }
        if(toggleNextApptBtn) {
             toggleNextApptBtn.innerHTML = '<i class="bi bi-plus-lg me-1"></i> Add Schedule';
             toggleNextApptBtn.classList.replace('btn-outline-danger', 'btn-outline-primary');
        }

        const prescriptionWrapper = document.getElementById('prescriptionWrapper');
        const togglePrescriptionBtn = document.getElementById('togglePrescriptionBtn');
        if(prescriptionWrapper) {
             prescriptionWrapper.style.display = 'none';
             // Disable inputs since they are hidden
             prescriptionWrapper.querySelectorAll('input, textarea').forEach(el => el.disabled = true);
        }
        if(togglePrescriptionBtn) {
             togglePrescriptionBtn.innerHTML = '<i class="bi bi-plus-lg me-1"></i> Add Prescription';
             togglePrescriptionBtn.classList.replace('btn-outline-danger', 'btn-outline-primary');
        }
    });

    // Restore statuses from localStorage
    document.querySelectorAll('table tbody tr').forEach(row => {
        // Skip records table rows
        if(row.closest('#recordsTable')) return;

        let id = row.dataset.id;
        let savedStatus = localStorage.getItem('appointment_' + id);
        if(savedStatus){
            row.dataset.status = savedStatus;
            let td = row.querySelector('td[data-label="Status"]'); // Use data-label selector
            if(savedStatus === 'approved'){
                td.innerHTML = `<span class="badge bg-success w-100 rounded-pill">Approved</span>`;
            } else if(savedStatus === 'cancelled'){
                td.innerHTML = `<span class="badge bg-danger w-100 rounded-pill">Cancelled</span>`;
            }
        }
        
        // Ensure rows are not clickable (only specific buttons)
        row.style.cursor = 'default';
        row.onclick = null;
    });

    // Accept button functionality
    document.querySelectorAll('.accept-btn-form').forEach(form => {
        form.addEventListener('submit', function(e){
            e.preventDefault();
            let row = form.closest('tr');
            let id = row.dataset.id;

            // Update Status Column
            row.querySelector('td[data-label="Status"]').innerHTML = `<span class="badge bg-success w-100 rounded-pill">Approved</span>`;
            
            // Update Action Column to show "View Pets" button logic (simulate approval)
            row.querySelector('td[data-label="Action"]').innerHTML = `
                <button type="button" class="btn btn-sm btn-success rounded-pill px-3 w-100" onclick="openPetsModal(this)">
                    View Pets
                </button>`;

            row.dataset.status = 'approved';
            localStorage.setItem('appointment_' + id, 'approved');

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => console.log('Accepted:', data))
            .catch(err => console.error(err));
        });
    });

    // Cancel button functionality
    document.querySelectorAll('form input[name="status"][value="cancelled"]').forEach(input => {
        let form = input.closest('form');
        form.addEventListener('submit', function(e){
            e.preventDefault();
            let row = form.closest('tr');
            let id = row.dataset.id;

            row.querySelector('td[data-label="Status"]').innerHTML = `<span class="badge bg-danger w-100 rounded-pill">Cancelled</span>`;
            row.querySelector('td[data-label="Action"]').innerHTML = `<span class="badge bg-danger w-100 rounded-pill">Cancelled</span>`;
            
            row.dataset.status = 'cancelled';
            localStorage.setItem('appointment_' + id, 'cancelled');

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                }
            });
        });
    });

    // Complete Appointment
    document.getElementById('appointmentForm').addEventListener('submit', function(e){
        e.preventDefault();

        let id = document.getElementById('modalAppointmentId').value;
        let petId = document.getElementById('modalPetId').value;

        if(!id){
            alert('Appointment ID missing!');
            return;
        }

        let row = document.querySelector(`tr[data-id='${id}']`);
        if(!row || row.dataset.status !== 'approved'){
            alert('Appointment not found or not approved.');
            return;
        }

        let medicines = Array.from(document.querySelectorAll('input[name="medicine[]"]')).map(i => i.value || '');
        let administration = Array.from(document.querySelectorAll('textarea[name="administration[]"]')).map(i => i.value || '');
        let frequency = Array.from(document.querySelectorAll('textarea[name="frequency[]"]')).map(i => i.value || '');
        let duration = Array.from(document.querySelectorAll('textarea[name="duration[]"]')).map(i => i.value || '');

        let len = medicines.length;
        while(administration.length < len) administration.push('');
        while(frequency.length < len) frequency.push('');
        while(duration.length < len) duration.push('');

        let nextAppointmentEl = document.getElementById('nextAppointment');
        let nextNotesEl = document.querySelector('textarea[name="next_notes"]');

        let payload = {
            appointment_id: id,
            pet_id: petId,
            weight: this.weight.value || null,
            vaccine_status: this.vaccine_status.value || '',
            vaccination_dates: this.vaccination_dates.value || '',
            health_condition: this.health_condition.value || '',
            vet_notes: this.vet_notes.value || '',
            medicine: medicines,
            administration: administration,
            frequency: frequency,
            duration: duration,
            next_appointment: nextAppointmentEl.value || null,
            next_notes: nextNotesEl.value || null
        };


        fetch("{{ route('clinic.completeAppointment') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(payload)
        })
        .then(async res => {
            if(!res.ok){
                let err = await res.json().catch(() => { return {message: 'Unknown error'} });
                throw new Error(err.message || 'Server error');
            }
            return res.json();
        })
        .then(data => {
            if(data.success){
                // Update row data-pets
                let pets = JSON.parse(row.dataset.pets);
                pets = pets.map(p => {
                    if(p.id == petId) p.completed = true;
                    return p;
                });
                row.dataset.pets = JSON.stringify(pets);

                bootstrap.Modal.getInstance(document.getElementById('appointmentModal')).hide();

                if(data.status === 'completed'){
                    row.querySelector('td[data-label="Status"]').innerHTML = `<span class="badge bg-secondary w-100 rounded-pill">Completed</span>`;
                    row.querySelector('td[data-label="Action"]').innerHTML = `<span class="badge bg-secondary w-100 rounded-pill">Completed</span>`;
                    
                    row.dataset.status = 'completed';
                    window.location.href = "{{ route('clinic.completedAppointments') }}";
                } else {
                     // Re-open pets modal
                     let btn = row.querySelector('button[onclick="openPetsModal(this)"]');
                     if(btn) openPetsModal(btn);
                     // alert('Pet record saved!');
                }
            } else {
                alert(data.message || 'Error completing appointment.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error completing appointment: ' + err.message);
        });
    });

    document.addEventListener('hidden.bs.modal', function (event) {
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
    });

});

// Record Search & Modal Logic
window.checkEnter = function(event) {
    if (event.key === 'Enter') {
        let visibleRows = [];
        let table = document.getElementById('recordsTable');
        if (!table) return;
        
        let rows = table.querySelector('tbody').getElementsByTagName('tr');
        
        for (let i = 0; i < rows.length; i++) {
            if (rows[i].style.display !== 'none') {
                visibleRows.push(rows[i]);
            }
        }
        
        if (visibleRows.length === 1) {
            viewRecord(visibleRows[0]);
        }
    }
}

window.viewRecord = function(row) {
    // Populate Modal
    document.getElementById('recordModalPetImage').src = row.dataset.petImage;
    document.getElementById('recordModalPetName').textContent = row.dataset.petName;
    document.getElementById('recordModalOwnerName').textContent = 'Owner: ' + row.dataset.owner;
    
    document.getElementById('recordModalDate').textContent = row.dataset.date;
    document.getElementById('recordModalService').textContent = row.dataset.service;
    document.getElementById('recordModalWeight').textContent = row.dataset.weight ? row.dataset.weight + ' kg' : 'N/A';
    document.getElementById('recordModalCondition').textContent = row.dataset.condition || 'N/A';
    
    document.getElementById('recordModalVaccineStatus').textContent = row.dataset.vaccine || 'N/A';
    document.getElementById('recordModalVaccineDates').textContent = row.dataset.vaccineDates || 'N/A';
    
    document.getElementById('recordModalVetNotes').textContent = row.dataset.notes || 'No notes available.';
    
    // Show Modal
    let modal = new bootstrap.Modal(document.getElementById('recordDetailsModal'));
    modal.show();
}
</script>

<!-- Record Details Modal -->
<div class="modal fade" id="recordDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg overflow-hidden">
            <div class="modal-header border-0 bg-success text-white">
                <div class="d-flex align-items-center gap-3">
                    <img id="recordModalPetImage" src="" class="rounded-circle border border-2 border-white" width="60" height="60" style="object-fit:cover;">
                    <div>
                        <h5 class="modal-title fw-bold" id="recordModalPetName">Pet Name</h5>
                        <p class="mb-0 small opacity-75" id="recordModalOwnerName">Owner: </p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="row g-4">
                    <!-- Medical Info -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="fw-bold text-success mb-3"><i class="bi bi-activity me-2"></i>Medical Info</h6>
                                <ul class="list-unstyled mb-0 d-flex flex-column gap-2">
                                    <li class="d-flex justify-content-between">
                                        <span class="text-muted">Date:</span>
                                        <span class="fw-semibold" id="recordModalDate"></span>
                                    </li>
                                    <li class="d-flex justify-content-between">
                                        <span class="text-muted">Service:</span>
                                        <span class="fw-semibold" id="recordModalService"></span>
                                    </li>
                                    <li class="d-flex justify-content-between">
                                        <span class="text-muted">Weight:</span>
                                        <span class="fw-semibold" id="recordModalWeight"></span>
                                    </li>
                                    <li class="d-flex justify-content-between">
                                        <span class="text-muted">Condition:</span>
                                        <span class="fw-semibold" id="recordModalCondition"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Vaccination -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="fw-bold text-success mb-3"><i class="bi bi-shield-check me-2"></i>Vaccination</h6>
                                <ul class="list-unstyled mb-0 d-flex flex-column gap-2">
                                    <li class="d-flex justify-content-between">
                                        <span class="text-muted">Status:</span>
                                        <span class="fw-semibold" id="recordModalVaccineStatus"></span>
                                    </li>
                                    <li>
                                        <span class="text-muted d-block mb-1">Dates:</span>
                                        <span class="fw-semibold small" id="recordModalVaccineDates"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Vet Notes -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="fw-bold text-success mb-3"><i class="bi bi-journal-medical me-2"></i>Veterinarian Notes</h6>
                                <div class="p-3 bg-light rounded border" id="recordModalVetNotes" style="min-height: 100px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-white">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Pet Owner History Modal --}}
<div class="modal fade" id="petOwnerHistoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 shadow border-0">
      <div class="modal-header bg-success text-white rounded-top-4">
        <h5 class="modal-title"><i class="bi bi-clock-history me-2"></i> Appointment History: <span id="historyOwnerName" class="fw-bold"></span></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <div id="historyList" class="d-flex flex-column gap-2 p-3" style="max-height: 500px; overflow-y: auto;">
            {{-- Populated via JS --}}
        </div>
        <div id="historyLoading" class="text-center p-4">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div id="historyEmpty" class="text-center p-4 d-none">
            <p class="text-muted fw-bold">No history found for this owner.</p>
        </div>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
window.viewPetOwnerHistory = function(ownerId, ownerName) {
    document.getElementById('historyOwnerName').textContent = ownerName;
    document.getElementById('historyList').innerHTML = '';
    document.getElementById('historyLoading').classList.remove('d-none');
    document.getElementById('historyEmpty').classList.add('d-none');
    
    new bootstrap.Modal(document.getElementById('petOwnerHistoryModal')).show();

    fetch("{{ url('/clinic/pet-owner-history') }}/" + ownerId)
        .then(response => response.json())
        .then(data => {
            document.getElementById('historyLoading').classList.add('d-none');
            let container = document.getElementById('historyList');
            
            if(data.length === 0) {
                document.getElementById('historyEmpty').classList.remove('d-none');
                return;
            }

            data.forEach((item, index) => {
                let statusColor = 'secondary';
                if(item.status.toLowerCase() === 'approved' || item.status.toLowerCase() === 'completed') statusColor = 'success';
                else if(item.status.toLowerCase() === 'pending') statusColor = 'warning';
                else if(item.status.toLowerCase() === 'cancelled') statusColor = 'danger';

                let collapseId = `historyCollapse${index}`;
                
                let itemHtml = `
                    <div class="history-item">
                        <button class="btn btn-outline-secondary w-100 text-start p-3 fw-bold d-flex justify-content-between align-items-center bg-white shadow-sm border rounded-3" 
                                type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-paw-fill text-success me-3 fs-5"></i>
                                <div>
                                    <div class="text-dark">${item.pet_name}</div>
                                    <small class="text-muted fw-normal">${new Date(item.date).toLocaleDateString()}</small>
                                </div>
                            </div>
                            <i class="bi bi-chevron-down text-muted"></i>
                        </button>
                        <div class="collapse mt-2" id="${collapseId}">
                             <div class="card card-body bg-light border-0 rounded-3 p-3">
                                 <div class="row g-2">
                                     <div class="col-6">
                                         <small class="text-muted d-block">Service</small>
                                         <span class="fw-semibold text-dark">${item.service}</span>
                                     </div>
                                     <div class="col-6">
                                         <small class="text-muted d-block">Status</small>
                                         <span class="badge bg-${statusColor} rounded-pill">${item.status}</span>
                                     </div>
                                     <div class="col-12 mt-2">
                                         <small class="text-muted d-block">Vet Notes</small>
                                         <div class="p-2 bg-white rounded border text-secondary small">
                                             ${item.notes || 'No notes available.'}
                                         </div>
                                     </div>
                                 </div>
                             </div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', itemHtml);
            });
        })
        .catch(err => {
            console.error(err);
            document.getElementById('historyLoading').classList.add('d-none');
            alert('Error loading history.');
        });
}
</script>
@endsection
