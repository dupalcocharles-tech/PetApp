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
        cursor: pointer;
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

    /* Modal Styling */
    .modal-content {
        border-radius: 24px;
        border: none;
        overflow: hidden;
        backdrop-filter: blur(20px);
        background: rgba(255, 255, 255, 0.95);
    }
    
    .modal-header {
        background: linear-gradient(135deg, var(--primary-green), var(--primary-dark));
        border-bottom: none;
        padding: 1.5rem;
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

        /* Table as Cards for Mobile */
        .table thead { display: none; }
        .table tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            background: #fff;
            padding: 1rem;
        }
        .table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
            text-align: right;
        }
        .table tbody td:last-child { border-bottom: none; }
        .table tbody td::before {
            content: attr(data-label);
            font-weight: bold;
            color: #6c757d;
            margin-right: 1rem;
            text-align: left;
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

    .today-next-appointment {
        background-color: #fef3c7 !important;
        box-shadow: 0 0 0 1px rgba(245, 158, 11, 0.35);
        transform: scale(1.005);
        position: relative;
    }
    .today-next-appointment:hover {
        background-color: #fde68a !important;
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
            @php
                $isNextView = request()->routeIs('clinic.nextAppointments');
            @endphp
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4 pt-5 pt-md-0">
                <div>
                    <h2 class="fw-bold text-dark mb-1">{{ $isNextView ? 'Next Appointments' : 'Completed Appointments' }}</h2>
                    <p class="text-secondary mb-0 fw-medium">
                        {{ $isNextView ? 'View appointments with a scheduled next visit and send reminders.' : 'Review past appointments and send reminders.' }}
                    </p>
                </div>
                <div class="d-none d-md-block">
                    <span class="badge bg-success bg-opacity-10 text-success fw-bold fs-6 px-4 py-2 rounded-pill shadow-sm border border-success border-opacity-25">
                        <i class="bi bi-check2-all me-1"></i> {{ $appointments->count() }} Records
                    </span>
                </div>
            </div>

            <div class="card border-0 rounded-4 overflow-hidden mb-4">
                <div class="card-body p-0">
                    @if($appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 text-center">
                                <thead class="bg-light text-secondary fw-bold small text-uppercase">
                                    <tr>
                                        <th class="py-3 ps-4 text-start">Pet</th>
                                        <th class="py-3">Owner</th>
                                        <th class="py-3">Service</th>
                                        <th class="py-3">Date</th>
                                        <th class="py-3">Status</th>
                                        <th class="py-3">Next Visit</th>
                                        <th class="py-3 pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="small fw-medium">
                                   @if($isNextView)
                                       @php
                                           $groupedAppointments = $appointments
                                               ->sortBy(function($appointment) {
                                                   return $appointment->next_appointment ?? $appointment->appointment_date;
                                               })
                                               ->groupBy(function($appointment) {
                                                   $referenceDate = $appointment->next_appointment ?? $appointment->appointment_date;
                                                   return $referenceDate
                                                       ? \Carbon\Carbon::parse($referenceDate)->startOfWeek()->format('Y-m-d')
                                                       : 'unscheduled';
                                               });
                                       @endphp

                                       @foreach($groupedAppointments as $weekKey => $weekAppointments)
                                           @php
                                               $weekStart = $weekKey !== 'unscheduled' ? \Carbon\Carbon::parse($weekKey) : null;
                                               $weekEnd = $weekStart ? (clone $weekStart)->endOfWeek() : null;
                                           @endphp

                                           @if($weekStart)
                                               <tr class="table-light">
                                                   <td colspan="7" class="text-start fw-bold ps-4 text-secondary">
                                                       Week of {{ $weekStart->format('M d') }} – {{ $weekEnd->format('M d, Y') }}
                                                   </td>
                                               </tr>
                                           @endif

                                           @foreach($weekAppointments as $appointment)
                                             @foreach($appointment->pets as $pet)
                                                @php
                                                    $medical = $appointment->medicalRecords->where('pet_id', $pet->id)->first() ?? null;
                                                    $medications = $appointment->medications->where('pet_id', $pet->id);
                                                    $ownerName = $appointment->owner->full_name ?? '-';
                                                    $nextDate = $appointment->next_appointment;
                                                    $isToday = $nextDate && strtotime($nextDate) !== false && \Carbon\Carbon::parse($nextDate)->isToday();
                                                @endphp

                                                <tr 
                                                    class="appointment-row {{ $isToday ? 'today-next-appointment' : '' }}"
                                                    data-id="{{ $appointment->id }}"
                                                    data-pet-image="{{ $pet->image ? asset('storage/' . $pet->image) : asset('images/pets/default.png') }}"
                                                    data-owner-name="{{ $ownerName }}"
                                                    data-weight="{{ $medical->weight ?? '-' }}"
                                                    data-vaccine-status="{{ $medical->vaccine_status ?? '-' }}"
                                                    data-vaccination-dates="{{ $medical->vaccination_dates ?? '-' }}"
                                                    data-health-condition="{{ $medical->health_condition ?? '-' }}"
                                                    data-vet-notes="{{ $medical->vet_notes ?? '-' }}"
                                                    data-medicine='@json($medications->pluck('medicine_name'))'
                                                    data-administration='@json($medications->pluck('dosage'))'
                                                    data-frequency='@json($medications->pluck('schedule'))'
                                                    data-duration='@json($medications->pluck('treatment_start'))'
                                                    data-next-date="{{ $appointment->next_appointment ?? '' }}"
                                                >
                                                    <td data-label="Pet" class="fw-semibold text-start ps-4">
                                                        <div class="d-flex align-items-center gap-3">
                                                            <img src="{{ $pet->image ? asset('storage/' . $pet->image) : asset('images/pets/default.png') }}" 
                                                                 alt="Pet" class="rounded-circle border border-secondary border-opacity-25" style="width:40px; height:40px; object-fit:cover;">
                                                            <span class="text-dark">{{ $pet->name ?? 'N/A' }}</span>
                                                        </div>
                                                    </td>
                                                    <td data-label="Owner">{{ $ownerName }}</td>
                                                    <td data-label="Service">
                                                        <span class="badge bg-light text-dark border border-secondary border-opacity-25 px-3 py-2 fw-medium">{{ optional($appointment->service)->name ?? '-' }}</span>
                                                    </td>
                                                    <td data-label="Date" class="text-secondary">
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</span>
                                                            <span class="small">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}</span>
                                                        </div>
                                                    </td>
                                                    <td data-label="Status">
                                                        <span class="badge badge-soft-success px-3 py-2 rounded-pill">
                                                            {{ ucfirst($appointment->status) }}
                                                        </span>
                                                    </td>
                                                    <td data-label="Next Visit" class="fw-bold {{ $isToday ? 'text-danger' : 'text-dark' }}">
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-bold">{{ \Carbon\Carbon::parse($appointment->next_appointment)->format('M d, Y') }}</span>
                                                            <span class="small opacity-75">{{ \Carbon\Carbon::parse($appointment->next_appointment)->format('h:i A') }}</span>
                                                        </div>
                                                    </td>
                                                    <td data-label="Action" class="pe-4">
                                                        @if($nextDate)
                                                            <button 
                                                                class="btn btn-sm remind-btn {{ $isToday ? 'btn-success' : 'btn-outline-success' }} rounded-pill px-3 shadow-sm fw-bold"
                                                                data-id="{{ $appointment->id }}"
                                                                data-next-date="{{ $nextDate }}">
                                                                <i class="bi bi-bell{{ $isToday ? '-fill' : '' }} me-1"></i> Remind
                                                            </button>
                                                        @else
                                                            <button class="btn btn-sm btn-light text-muted border rounded-pill px-3 fw-medium" disabled>No Schedule</button>
                                                        @endif
                                                    </td>
                                                </tr>
                                             @endforeach
                                           @endforeach
                                       @endforeach
                                   @else
                                       @foreach($appointments as $appointment)
                                         @foreach($appointment->pets as $pet)
                                            @php
                                                $medical = $appointment->medicalRecords->where('pet_id', $pet->id)->first() ?? null;
                                                $medications = $appointment->medications->where('pet_id', $pet->id);
                                                $ownerName = $appointment->owner->full_name ?? '-';
                                                $nextDate = $appointment->next_appointment;
                                                $isToday = $nextDate && strtotime($nextDate) !== false && \Carbon\Carbon::parse($nextDate)->isToday();
                                            @endphp

                                            <tr 
                                                class="appointment-row"
                                                data-id="{{ $appointment->id }}"
                                                data-pet-image="{{ $pet->image ? asset('storage/' . $pet->image) : asset('images/pets/default.png') }}"
                                                data-owner-name="{{ $ownerName }}"
                                                data-weight="{{ $medical->weight ?? '-' }}"
                                                data-vaccine-status="{{ $medical->vaccine_status ?? '-' }}"
                                                data-vaccination-dates="{{ $medical->vaccination_dates ?? '-' }}"
                                                data-health-condition="{{ $medical->health_condition ?? '-' }}"
                                                data-vet-notes="{{ $medical->vet_notes ?? '-' }}"
                                                data-medicine='@json($medications->pluck('medicine_name'))'
                                                data-administration='@json($medications->pluck('dosage'))'
                                                data-frequency='@json($medications->pluck('schedule'))'
                                                data-duration='@json($medications->pluck('treatment_start'))'
                                                data-next-date="{{ $appointment->next_appointment ?? '' }}"
                                            >
                                                <td data-label="Pet" class="fw-semibold text-start ps-4">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <img src="{{ $pet->image ? asset('storage/' . $pet->image) : asset('images/pets/default.png') }}" 
                                                             alt="Pet" class="rounded-circle border border-secondary border-opacity-25" style="width:40px; height:40px; object-fit:cover;">
                                                        <span class="text-dark">{{ $pet->name ?? 'N/A' }}</span>
                                                    </div>
                                                </td>
                                                <td data-label="Owner">{{ $ownerName }}</td>
                                                <td data-label="Service">
                                                    <span class="badge bg-light text-dark border border-secondary border-opacity-25 px-3 py-2 fw-medium">{{ optional($appointment->service)->name ?? '-' }}</span>
                                                </td>
                                                <td data-label="Date" class="text-secondary">
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</span>
                                                        <span class="small">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}</span>
                                                    </div>
                                                </td>
                                                <td data-label="Status">
                                                    <span class="badge badge-soft-success px-3 py-2 rounded-pill">
                                                        {{ ucfirst($appointment->status) }}
                                                    </span>
                                                </td>
                                                <td data-label="Next Visit" class="fw-bold {{ $isToday ? 'text-danger' : 'text-dark' }}">
                                                    @if($appointment->next_appointment)
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-bold">{{ \Carbon\Carbon::parse($appointment->next_appointment)->format('M d, Y') }}</span>
                                                            <span class="small opacity-75">{{ \Carbon\Carbon::parse($appointment->next_appointment)->format('h:i A') }}</span>
                                                        </div>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td data-label="Action" class="pe-4">
                                                    <span class="text-muted small fst-italic">Completed</span>
                                                </td>
                                            </tr>
                                         @endforeach
                                       @endforeach
                                   @endif
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-5 text-center text-muted">
                            <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                                <i class="bi bi-clipboard-check display-4 text-secondary opacity-50"></i>
                            </div>
                            <h5 class="fw-bold text-dark">
                                {{ $isNextView ? 'No Appointments With Next Visit Yet' : 'No Completed Appointments Yet' }}
                            </h5>
                            <p class="text-secondary small">
                                {{ $isNextView ? 'Appointments will appear here once a next visit date is recorded.' : 'Appointments will appear here once they are marked as completed.' }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>

{{-- Appointment Details Modal --}}
<div class="modal fade" id="completedAppointmentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content rounded-4 shadow-lg">
      <div class="modal-header text-white">
        <h5 class="modal-title fw-bold"><i class="bi bi-file-medical me-2"></i> Appointment Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
          <div class="d-flex align-items-center mb-4 bg-light p-3 rounded-4 border border-secondary border-opacity-10">
              <img id="completedPetImage" src="{{ asset('images/pets/default.png') }}" 
                   alt="Pet Image" class="shadow rounded-circle border border-3 border-white" style="width:100px; height:100px; object-fit:cover;">
              <div class="ms-3">
                  <h4 class="fw-bold text-dark mb-1" id="completedPetName">Pet Name</h4>
                  <p class="mb-0 text-muted"><i class="bi bi-person me-1"></i> <span id="completedOwnerName">Owner Name</span></p>
              </div>
          </div>

          <div class="row g-3 mb-4">
              <div class="col-md-6">
                  <div class="p-3 border rounded-4 h-100 bg-white shadow-sm">
                      <label class="small text-muted fw-bold text-uppercase mb-1">Weight</label>
                      <p class="mb-0 fw-bold fs-5 text-dark" id="completedWeight">-</p>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="p-3 border rounded-4 h-100 bg-white shadow-sm">
                      <label class="small text-muted fw-bold text-uppercase mb-1">Vaccine Status</label>
                      <p class="mb-0 fw-bold fs-5 text-dark" id="completedVaccineStatus">-</p>
                  </div>
              </div>
          </div>

          <div class="mb-3">
              <label class="fw-bold text-secondary mb-1">Vaccination History</label>
              <div class="p-3 bg-light rounded-4 border border-secondary border-opacity-10 text-dark" id="completedVaccinationDates">-</div>
          </div>
          <div class="mb-3">
              <label class="fw-bold text-secondary mb-1">Health Condition</label>
              <div class="p-3 bg-light rounded-4 border border-secondary border-opacity-10 text-dark" id="completedHealthCondition">-</div>
          </div>
          <div class="mb-3">
              <label class="fw-bold text-secondary mb-1">Vet Notes</label>
              <div class="p-3 bg-light rounded-4 border border-secondary border-opacity-10 text-dark" id="completedVetNotes">-</div>
          </div>

          <div class="mb-3">
            <label class="fw-bold text-success mb-1">Next Appointment Date</label>
            <input type="date" class="form-control border-success rounded-3 bg-success bg-opacity-10 fw-bold text-success" id="nextAppointmentDate" readonly>
            <small class="text-muted ms-1">Recorded next visit date.</small>
          </div>

          <hr class="my-4 opacity-10">
          <h6 class="fw-bold text-primary mb-3"><i class="bi bi-prescription2 me-2"></i> Digital Prescription</h6>
          <div id="completedPrescriptionContainer"></div>
      </div>
      <div class="modal-footer border-0 bg-light rounded-bottom-4">
          <button type="button" class="btn btn-secondary px-4 rounded-pill fw-medium" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


{{-- ✅ SMS Success Modal --}}
<div class="modal fade" id="smsSuccessModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
      <div class="modal-header text-white" style="background: linear-gradient(135deg, #10b981, #059669);">
        <h5 class="modal-title fw-bold"><i class="bi bi-check-circle me-2"></i> SMS Sent</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center py-4">
        <div class="mb-3">
            <i class="bi bi-chat-left-check-fill text-success display-1"></i>
        </div>
        <p class="mb-0 fw-semibold text-dark fs-5" id="smsSuccessMessage">SMS reminder sent successfully!</p>
      </div>
      <div class="modal-footer border-0 justify-content-center pb-4">
        <button type="button" class="btn btn-success rounded-pill px-5 shadow-sm fw-bold" data-bs-dismiss="modal">Great!</button>
      </div>
    </div>
  </div>
</div>

{{-- ✅ SMS Confirmation Modal --}}
<div class="modal fade" id="remindConfirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
      <div class="modal-header bg-primary text-white border-0 py-3">
        <h5 class="modal-title fw-bold"><i class="bi bi-question-circle me-2"></i> Confirm Reminder</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 text-center">
        <div class="mb-3">
            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3">
                <i class="bi bi-chat-dots-fill text-primary fs-1"></i>
            </div>
        </div>
        <h5 class="fw-bold text-dark">Send SMS Reminder?</h5>
        <p class="text-secondary small px-3">
            Are you sure you want to send an appointment reminder to <strong id="confirmOwnerName" class="text-dark"></strong> for their pet <strong id="confirmPetName" class="text-dark"></strong>?
        </p>
        <div class="bg-light rounded-3 p-3 text-start mb-3">
            <div class="d-flex justify-content-between small mb-1">
                <span class="text-muted">Next Visit:</span>
                <span id="confirmNextDate" class="fw-bold text-dark"></span>
            </div>
        </div>
      </div>
      <div class="modal-footer border-0 justify-content-center pb-4 gap-2">
        <button type="button" class="btn btn-light rounded-pill px-4 fw-bold text-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmRemindBtn" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
            <i class="bi bi-send-fill me-2"></i> Yes, Send Now
        </button>
      </div>
    </div>
  </div>
</div>


@section('scripts')
<script>
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

document.addEventListener("DOMContentLoaded", function(){
    // Modal & Row Click Logic
    var modalEl = document.getElementById('completedAppointmentModal');
    var modal = new bootstrap.Modal(modalEl);

    document.querySelectorAll('.appointment-row').forEach(row => {
        row.addEventListener('click', function(e){
            // Prevent opening modal if clicking specific elements
            if(e.target.closest('button') || e.target.closest('a')) return;

            document.getElementById('completedPetImage').src = row.dataset.petImage;
            document.getElementById('completedPetName').textContent = row.querySelector('td[data-label="Pet"] span').textContent.trim();
            document.getElementById('completedOwnerName').textContent = row.dataset.ownerName;

            document.getElementById('completedWeight').textContent = (row.dataset.weight && row.dataset.weight !== '-') ? row.dataset.weight + ' kg' : 'Not recorded';
            document.getElementById('completedVaccineStatus').textContent = row.dataset.vaccineStatus || 'Unknown';
            document.getElementById('completedVaccinationDates').textContent = row.dataset.vaccinationDates || 'No history';
            document.getElementById('completedHealthCondition').textContent = row.dataset.healthCondition || 'No notes';
            document.getElementById('completedVetNotes').textContent = row.dataset.vetNotes || 'No notes';

            let medicines = JSON.parse(row.dataset.medicine || '[]');
            let administration = JSON.parse(row.dataset.administration || '[]');
            let frequency = JSON.parse(row.dataset.frequency || '[]');
            let duration = JSON.parse(row.dataset.duration || '[]');

            let container = document.getElementById('completedPrescriptionContainer');
            container.innerHTML = '';
            
            if(medicines.length === 0) {
                 container.innerHTML = '<p class="text-muted fst-italic">No prescription details available.</p>';
            } else {
                for(let i=0; i<medicines.length; i++){
                    container.innerHTML += `
                        <div class="mb-3 border border-success border-opacity-25 bg-success bg-opacity-10 p-3 rounded-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold text-success">${medicines[i] || 'Medicine'}</span>
                                <span class="badge bg-success">${duration[i] || ''}</span>
                            </div>
                            <div class="small text-dark">
                                <p class="mb-1"><i class="bi bi-capsule me-1"></i> <strong>Dosage:</strong> ${administration[i] || '-'}</p>
                                <p class="mb-0"><i class="bi bi-clock me-1"></i> <strong>Frequency:</strong> ${frequency[i] || '-'}</p>
                            </div>
                        </div>
                    `;
                }
            }

            const nextDate = row.dataset.nextDate;
            const dateInput = document.getElementById('nextAppointmentDate');

            if (nextDate && !isNaN(Date.parse(nextDate))) {
                dateInput.value = new Date(nextDate).toISOString().split('T')[0];
            } else {
                dateInput.value = '';
            }

            modal.show();
        });
    });

    // Remind button functionality
    // Helper to start countdown
    function startCountdown(btn, remainingSeconds, originalHtml) {
        const appointmentId = btn.dataset.id;
        btn.disabled = true;
        
        const updateTimer = () => {
            const minutes = Math.floor(remainingSeconds / 60);
            const seconds = remainingSeconds % 60;
            btn.innerHTML = `<i class="bi bi-hourglass-split me-1"></i> ${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            if (remainingSeconds <= 0) {
                clearInterval(interval);
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                localStorage.removeItem('remind_cooldown_' + appointmentId);
            }
            remainingSeconds--;
        };

        updateTimer(); // Run immediately
        const interval = setInterval(updateTimer, 1000);
    }

    const remindConfirmModal = new bootstrap.Modal(document.getElementById('remindConfirmModal'));
    let currentRemindBtn = null;

    document.querySelectorAll('.remind-btn').forEach(btn => {
        // Check for existing cooldowns on load
        const appointmentId = btn.dataset.id;
        const cooldownEnd = localStorage.getItem('remind_cooldown_' + appointmentId);
        
        if (cooldownEnd) {
            const now = Date.now();
            const remaining = Math.ceil((parseInt(cooldownEnd) - now) / 1000);
            
            if (remaining > 0) {
                startCountdown(btn, remaining, btn.innerHTML);
            } else {
                localStorage.removeItem('remind_cooldown_' + appointmentId);
            }
        }

        btn.addEventListener('click', function(e){
            e.stopPropagation(); // Stop row click
            
            const row = btn.closest('tr');
            const ownerName = row.dataset.ownerName;
            const petName = row.querySelector('td[data-label="Pet"] span').textContent.trim();
            const nextDate = btn.dataset.nextDate;

            // Populate confirmation modal
            document.getElementById('confirmOwnerName').textContent = ownerName;
            document.getElementById('confirmPetName').textContent = petName;
            document.getElementById('confirmNextDate').textContent = new Date(nextDate).toLocaleDateString('en-US', { 
                month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' 
            });

            currentRemindBtn = btn;
            remindConfirmModal.show();
        });
    });

    document.getElementById('confirmRemindBtn').addEventListener('click', function() {
        if (!currentRemindBtn) return;

        const btn = currentRemindBtn;
        const originalText = btn.innerHTML;
        const appointmentId = btn.dataset.id;
        
        // Hide confirmation modal
        remindConfirmModal.hide();

        // Loading State
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Sending...';

        fetch("{{ route('clinic.sendReminder') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ appointment_id: appointmentId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Show custom success modal
                document.getElementById('smsSuccessMessage').textContent = data.message || 'SMS reminder sent successfully!';
                new bootstrap.Modal(document.getElementById('smsSuccessModal')).show();

                // Start 2-minute countdown (120 seconds)
                const cooldownSeconds = 120;
                const cooldownEnd = Date.now() + (cooldownSeconds * 1000);
                localStorage.setItem('remind_cooldown_' + appointmentId, cooldownEnd);
                startCountdown(btn, cooldownSeconds, originalText);
            } else {
                alert(data.message); 
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred. Please try again.');
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    });
});
</script>
@include('staff.partials.scripts')
@endsection
