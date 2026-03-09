<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block position-fixed start-0 top-0 bottom-0 shadow-lg">
    <div class="d-flex flex-column h-100 text-white">
        {{-- Logo & Clinic --}}
        <div class="text-center p-4 border-bottom border-light-subtle">
            <div class="mb-3 d-flex align-items-center justify-content-start">
                <img src="{{ asset('images/offlogo.png') }}" alt="Official Logo" 
                     style="height: 44px; width: auto;" class="me-2">
                <span class="fw-bold text-white" style="font-size: 1.2rem;">PetApp</span>
                <span class="text-light opacity-75 ms-1" style="font-size: 1.05rem;">| Clinic</span>
            </div>
            <div class="mb-3 position-relative d-inline-block">
                @php
                    $clinicUser = Auth::guard('clinic')->user();
                @endphp
                @if($clinicUser && $clinicUser->profile_image)
                    <img src="{{ asset('storage/clinics/' . $clinicUser->profile_image) }}" alt="{{ $clinicUser->clinic_name ?? 'Clinic' }}"
                         class="rounded-circle border border-3 border-success shadow" width="80" height="80" style="object-fit: cover;">
                @else
                    <img src="{{ asset('images/clinics/default.png') }}" 
                         class="rounded-circle border border-3 border-success shadow" width="80" height="80" style="object-fit: cover;">
                @endif
                <span class="position-absolute bottom-0 end-0 bg-success border border-light rounded-circle p-1"></span>
            </div>
            <h6 class="fw-bold text-white mb-0">{{ $clinicUser->clinic_name ?? 'Clinic' }}</h6>
            <small class="text-light opacity-75">Veterinary Partner</small>
            @if($clinicUser)
                @php
                    $isOpenNow = $clinicUser->is_open ?? false;
                @endphp
                <div class="mt-2">
                    @if($clinicUser->is_24_hours)
                        <span class="badge bg-primary bg-opacity-75 text-white px-3 py-1 rounded-pill">
                            <i class="bi bi-clock-history me-1"></i>24 Hours
                        </span>
                    @elseif($isOpenNow)
                        <span class="badge bg-success bg-opacity-75 text-white px-3 py-1 rounded-pill">
                            <i class="bi bi-clock-fill me-1"></i>Open Now
                        </span>
                    @else
                        <span class="badge bg-danger bg-opacity-75 text-white px-3 py-1 rounded-pill">
                            <i class="bi bi-x-circle-fill me-1"></i>Closed
                        </span>
                    @endif
                    @if($clinicUser->opening_time && $clinicUser->closing_time && !$clinicUser->is_24_hours)
                        <div class="small text-light opacity-75 mt-1">
                            <i class="bi bi-clock me-1"></i>
                            {{ \Carbon\Carbon::parse($clinicUser->opening_time)->format('g:i A') }}
                            -
                            {{ \Carbon\Carbon::parse($clinicUser->closing_time)->format('g:i A') }}
                        </div>
                    @endif
                </div>
            @endif
            <div class="mt-3">
                <a href="{{ route('clinic.edit') }}" 
                   class="btn btn-sm btn-outline-light fw-semibold px-3 rounded-pill shadow-sm hover-scale">
                    <i class="bi bi-pencil-square me-1"></i> Edit Profile
                </a>
            </div>
        </div>

        <div class="flex-grow-1 p-3 overflow-auto">
            <ul class="nav flex-column gap-2">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-3 fw-semibold w-100 {{ request()->routeIs('clinic.dashboard') ? 'active bg-success shadow-sm' : 'bg-dark bg-opacity-25' }}" 
                       href="{{ route('clinic.dashboard') }}">
                        <i class="bi bi-speedometer2 me-2 fs-5"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-3 fw-semibold w-100 bg-dark bg-opacity-25" 
                       href="#" data-bs-toggle="modal" data-bs-target="#clinicNotificationsModal">
                        <i class="bi bi-bell-fill me-2 fs-5"></i> Notifications
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-3 fw-semibold w-100 {{ request()->routeIs('clinic.completedAppointments') ? 'active bg-success shadow-sm' : 'bg-dark bg-opacity-25' }}" 
                       href="{{ route('clinic.completedAppointments') }}">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i> Completed
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-3 fw-semibold w-100 {{ request()->routeIs('clinic.nextAppointments') ? 'active bg-success shadow-sm' : 'bg-dark bg-opacity-25' }}" 
                       href="{{ route('clinic.nextAppointments') }}">
                        <i class="bi bi-calendar-event me-2 fs-5"></i> Next Appointments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-3 fw-semibold w-100 {{ request()->routeIs('clinic.records') ? 'active bg-success shadow-sm' : 'bg-dark bg-opacity-25' }}" 
                       href="{{ route('clinic.records') }}">
                        <i class="bi bi-folder2-open me-2 fs-5"></i> Clinic Records
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-3 fw-semibold w-100 {{ request()->routeIs('services.index') ? 'active bg-success shadow-sm' : 'bg-dark bg-opacity-25' }}" 
                       href="{{ route('services.index', Auth::guard('clinic')->id()) }}">
                        <i class="bi bi-clipboard-heart me-2 fs-5"></i> Services
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-3 fw-semibold w-100 {{ request()->routeIs('clinic.reviews') ? 'active bg-success shadow-sm' : 'bg-dark bg-opacity-25' }}" 
                       href="{{ route('clinic.reviews') }}">
                        <i class="bi bi-star-fill me-2 fs-5"></i> Reviews
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-3 fw-semibold w-100 bg-dark bg-opacity-25" 
                       href="#" data-bs-toggle="modal" data-bs-target="#availabilityModal">
                        <i class="bi bi-clock-fill me-2 fs-5"></i> Availability
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-3 fw-semibold w-100 {{ request()->routeIs('clinic.profile') ? 'active bg-success shadow-sm' : 'bg-dark bg-opacity-25' }}" 
                       href="{{ route('clinic.profile') }}">
                        <i class="bi bi-person-badge me-2 fs-5"></i> Profile
                    </a>
                </li>
            </ul>
        </div>

        {{-- Logout --}}
        <div class="mt-auto p-3 border-top border-light-subtle">
            <a class="nav-link text-danger fw-bold d-flex align-items-center justify-content-center bg-dark bg-opacity-25 rounded py-2 hover-danger" href="#"
               data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="bi bi-box-arrow-right me-2"></i> Sign Out
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</nav>

<div class="modal fade" id="clinicNotificationsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
      <div class="modal-header bg-white border-bottom-0 pb-0">
        <h5 class="modal-title fw-bold text-dark">
            <i class="bi bi-bell-fill me-2 text-success"></i>Notifications
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 bg-light-subtle">
        @if(isset($appointments) && $appointments->count() > 0)
            <div class="list-group list-group-flush rounded-3 overflow-hidden shadow-sm">
                @foreach($appointments as $appointment)
                    <div class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-light text-dark border">
                                    #{{ $appointment->id }}
                                </span>
                                <span class="fw-semibold text-dark">
                                    {{ $appointment->owner->full_name ?? 'Unknown Owner' }}
                                </span>
                            </div>
                            <div class="small text-muted mb-1">
                                <i class="bi bi-calendar-event me-1"></i>
                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y h:i A') }}
                                @if($appointment->service)
                                    <span class="mx-1">•</span>
                                    <i class="bi bi-heart-pulse me-1"></i>{{ $appointment->service->name }}
                                @endif
                            </div>
                            <div class="small">
                                <span class="text-secondary">Payment:</span>
                                @if($appointment->payment_status === 'downpayment_paid')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">
                                        Verified
                                    </span>
                                @elseif($appointment->payment_status === 'paid')
                                    <span class="badge bg-success text-white rounded-pill px-2 py-1">
                                        Paid
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-1">
                                        Unpaid
                                    </span>
                                @endif
                                <span class="ms-2 text-secondary">Status:</span>
                                <span class="badge rounded-pill px-2 py-1 border
                                    @if($appointment->status == 'approved') bg-success-subtle text-success border-success-subtle
                                    @elseif($appointment->status == 'pending') bg-warning-subtle text-warning border-warning-subtle
                                    @elseif($appointment->status == 'completed') bg-primary-subtle text-primary border-primary-subtle
                                    @else bg-danger-subtle text-danger border-danger-subtle @endif">
                                    {{ ucfirst($appointment->status ?? 'N/A') }}
                                </span>
                            </div>
                        </div>
                        @if($appointment->payment_receipt)
                            <a href="{{ asset('storage/' . $appointment->payment_receipt) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill fw-semibold">
                                <i class="bi bi-receipt me-1"></i> View Receipt
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-bell-slash text-muted display-5"></i>
                </div>
                <h6 class="fw-bold text-muted mb-1">No notifications yet</h6>
                <p class="text-muted small mb-0">New bookings and payment updates will appear here.</p>
            </div>
        @endif
      </div>
    </div>
  </div>
</div>

{{-- Logout Confirmation Modal --}}
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger" id="logoutModalLabel">Sign Out</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="bi bi-box-arrow-right text-danger display-4"></i>
                </div>
                <h5 class="fw-semibold text-dark mb-2">Are you sure you want to leave?</h5>
                <p class="text-muted mb-0">You will need to log in again to access your dashboard.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-light px-4 rounded-pill fw-semibold" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger px-4 rounded-pill fw-semibold" onclick="document.getElementById('logout-form').submit();">
                    Yes, Sign Out
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Availability Modal --}}
<div class="modal fade" id="availabilityModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header text-white" style="background: linear-gradient(135deg, var(--primary-green, #0d9488), var(--primary-dark, #0f766e));">
        <h5 class="modal-title"><i class="bi bi-clock-fill me-2"></i> Availability Settings</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form action="{{ route('staff.updateAvailability') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-6">
                    <label class="form-label fw-bold text-secondary small text-uppercase">Opening Time</label>
                    <input type="time" name="opening_time" class="form-control bg-light border-0 py-2 shadow-sm" 
                           value="{{ old('opening_time', $clinicUser->opening_time ? \Carbon\Carbon::parse($clinicUser->opening_time)->format('H:i') : '') }}">
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold text-secondary small text-uppercase">Closing Time</label>
                    <input type="time" name="closing_time" class="form-control bg-light border-0 py-2 shadow-sm" 
                           value="{{ old('closing_time', $clinicUser->closing_time ? \Carbon\Carbon::parse($clinicUser->closing_time)->format('H:i') : '') }}">
                </div>
                <div class="col-12">
                    <div class="form-check form-switch p-3 bg-light rounded-3 d-flex justify-content-between align-items-center border border-secondary border-opacity-10 shadow-sm">
                        <label class="form-check-label fw-bold text-dark mb-0" for="is24Hours">
                            <i class="bi bi-moon-stars-fill text-primary me-2"></i> Open 24 Hours
                        </label>
                        <input class="form-check-input m-0" type="checkbox" id="is24Hours" name="is_24_hours" value="1" 
                               {{ $clinicUser->is_24_hours ? 'checked' : '' }} style="width: 3em; height: 1.5em; cursor: pointer;">
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-2 border-top border-light d-flex justify-content-end">
                <button type="button" class="btn btn-light rounded-pill px-4 me-2 fw-bold text-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Update Settings</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
