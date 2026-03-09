@extends('layouts.app')

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
                        <h2 class="fw-bold text-success mb-1"><i class="bi bi-calendar-event me-2"></i>Appointment Details</h2>
                        <p class="text-muted mb-0">View detailed information about this appointment.</p>
                    </div>
                    <div>
                         <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold shadow-sm">
                            <i class="bi bi-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        {{-- Appointment Info Card --}}
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-white border-bottom border-light p-3">
                                <h5 class="fw-bold text-success mb-0"><i class="bi bi-info-circle me-2"></i>General Information</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="small text-muted text-uppercase fw-bold">Pet Name</label>
                                        <p class="fs-5 fw-bold text-dark mb-0">{{ $appointment->pet->name ?? 'Unknown Pet' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-muted text-uppercase fw-bold">Service</label>
                                        <p class="fs-5 fw-bold text-dark mb-0">{{ $appointment->service->name ?? 'General Checkup' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-muted text-uppercase fw-bold">Appointment Date</label>
                                        <p class="fs-5 fw-bold text-dark mb-0">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y h:i A') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-muted text-uppercase fw-bold">Status</label>
                                        <div>
                                            @if($appointment->status == 'pending')
                                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Pending</span>
                                            @elseif($appointment->status == 'approved')
                                                <span class="badge bg-success px-3 py-2 rounded-pill">Approved</span>
                                            @elseif($appointment->status == 'completed')
                                                <span class="badge bg-primary px-3 py-2 rounded-pill">Completed</span>
                                            @elseif($appointment->status == 'cancelled')
                                                <span class="badge bg-danger px-3 py-2 rounded-pill">Cancelled</span>
                                            @else
                                                <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ ucfirst($appointment->status) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                         {{-- Owner Info Card --}}
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                             <div class="card-header bg-white border-bottom border-light p-3">
                                <h5 class="fw-bold text-success mb-0"><i class="bi bi-person me-2"></i>Owner Details</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                     <div class="col-md-6">
                                        <label class="small text-muted text-uppercase fw-bold">Owner Name</label>
                                        <p class="fs-5 fw-bold text-dark mb-0">{{ $appointment->owner->full_name ?? 'N/A' }}</p>
                                    </div>
                                     <div class="col-md-6">
                                        <label class="small text-muted text-uppercase fw-bold">Phone Number</label>
                                        <p class="fs-5 fw-bold text-dark mb-0">{{ $appointment->owner->phone ?? 'N/A' }}</p>
                                    </div>
                                     <div class="col-12">
                                        <label class="small text-muted text-uppercase fw-bold">Address</label>
                                        <p class="fs-5 text-dark mb-0">{{ $appointment->owner->address ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

@include('staff.partials.scripts')
@endsection
