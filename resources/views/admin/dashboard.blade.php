@extends('layouts.app')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f8f9fa;
        overflow-x: hidden;
    }

    .fade-in-up {
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }
    
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Sidebar Styles */
    .sidebar {
        background: #ffffff;
        border-right: 1px solid rgba(0,0,0,0.05);
        box-shadow: 4px 0 24px rgba(0,0,0,0.02);
    }

    .sidebar .nav-link {
        color: #6c757d;
        border-radius: 12px;
        padding: 14px 20px;
        margin-bottom: 8px;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .sidebar .nav-link:hover {
        background-color: #f8f9fa;
        color: #198754;
        transform: translateX(4px);
    }

    .sidebar .nav-link.active {
        background-color: #e8f5e9;
        color: #198754 !important;
        font-weight: 600;
    }

    .sidebar .nav-link i {
        font-size: 1.2rem;
        transition: transform 0.3s ease;
    }

    .sidebar .nav-link:hover i {
        transform: scale(1.1);
    }

    /* Card Styles */
    .dashboard-card {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.04);
        border-radius: 24px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.03);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .dashboard-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 32px rgba(0,0,0,0.06);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Table Styles */
    .table-custom thead th {
        background-color: #f8f9fa;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 16px 24px;
        border-bottom: 1px solid #edf2f7;
    }

    .table-custom tbody td {
        padding: 20px 24px;
        vertical-align: middle;
        color: #2d3748;
        border-bottom: 1px solid #edf2f7;
    }

    .table-custom tr:last-child td {
        border-bottom: none;
    }

    /* Badge Styles */
    .badge-soft {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.75rem;
    }
    
    .badge-soft-success { background: #e8f5e9; color: #1b5e20; }
    .badge-soft-warning { background: #fff8e1; color: #f57f17; }
    .badge-soft-danger { background: #ffebee; color: #c62828; }
    .badge-soft-info { background: #e3f2fd; color: #0d47a1; }

</style>
@endsection

@section('content')
<div class="container-fluid min-vh-100 bg-light p-0">
    <div class="row g-0">
        <!-- Desktop Sidebar -->
        <div class="col-lg-2 p-0 sidebar position-fixed h-100 d-none d-lg-flex flex-column top-0 start-0" style="z-index: 1040;">
             <div class="p-4">
                <div class="d-flex align-items-center gap-3 mb-5 px-2">
                    <div class="p-2">
                        <img src="{{ asset('images/offlogo_admin.png') }}" alt="Logo" width="48" class="d-block">
                    </div>
                    <div>
                        <h5 class="fw-bold m-0 text-dark">PetApp</h5>
                        <small class="text-muted" style="font-size: 0.75rem;">Admin Panel</small>
                    </div>
                </div>
                
                <div class="mb-4">
                    <p class="text-uppercase text-muted fw-bold px-3 mb-2" style="font-size: 0.7rem; letter-spacing: 1px;">Menu</p>
                    <ul class="nav flex-column gap-1">
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link active d-flex align-items-center gap-3">
                                <i class="bi bi-grid-1x2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.edit') }}" class="nav-link d-flex align-items-center gap-3">
                                <i class="bi bi-gear"></i> Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.add') }}" class="nav-link d-flex align-items-center gap-3">
                                <i class="bi bi-person-plus"></i> Add Admin
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.reports.index') }}" class="nav-link d-flex align-items-center gap-3">
                                <i class="bi bi-flag"></i> Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.appeals.index') }}" class="nav-link d-flex align-items-center gap-3">
                                <i class="bi bi-inbox"></i> Appeals
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="mb-4">
                    <p class="text-uppercase text-muted fw-bold px-3 mb-2" style="font-size: 0.7rem; letter-spacing: 1px;">Analytics</p>
                    <div class="px-3">
                        <button class="btn btn-light w-100 text-start mb-2 d-flex align-items-center justify-content-between p-2 rounded-3 border-0" id="showVerified">
                            <span class="small text-muted"><i class="bi bi-check-circle text-success me-2"></i>Verified</span>
                            <span class="badge bg-white text-dark shadow-sm border">{{ $verifiedClinics }}</span>
                        </button>
                        <button class="btn btn-light w-100 text-start mb-2 d-flex align-items-center justify-content-between p-2 rounded-3 border-0" id="showUnverified">
                            <span class="small text-muted"><i class="bi bi-exclamation-circle text-warning me-2"></i>Pending</span>
                            <span class="badge bg-white text-dark shadow-sm border">{{ $unverifiedClinics }}</span>
                        </button>
                        <button class="btn btn-light w-100 text-start d-flex align-items-center justify-content-between p-2 rounded-3 border-0" id="showAll">
                            <span class="small text-muted"><i class="bi bi-list-ul text-secondary me-2"></i>Total</span>
                            <span class="badge bg-white text-dark shadow-sm border">{{ $verifiedClinics + $unverifiedClinics }}</span>
                        </button>
                    </div>
                </div>
             </div>

             <div class="mt-auto p-4 border-top">
                <div class="d-flex align-items-center gap-3 mb-3 px-2">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 text-primary">
                        <i class="bi bi-person-circle fs-5"></i>
                    </div>
                    <div class="overflow-hidden">
                        <p class="mb-0 fw-bold text-dark text-truncate">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</p>
                        <p class="mb-0 small text-muted text-truncate">Administrator</p>
                    </div>
                </div>
                <button class="btn btn-outline-danger btn-sm w-100 rounded-pill fw-semibold" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                </button>
             </div>
        </div>

        <!-- Mobile Offcanvas -->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title fw-bold">PetApp Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
                <!-- Mobile Menu Content (simplified) -->
                <ul class="nav flex-column gap-2">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link active"><i class="bi bi-grid-1x2 me-2"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.edit') }}" class="nav-link"><i class="bi bi-gear me-2"></i> Settings</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.add') }}" class="nav-link"><i class="bi bi-person-plus me-2"></i> Add Admin</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.reports.index') }}" class="nav-link"><i class="bi bi-flag me-2"></i> Reports</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.appeals.index') }}" class="nav-link"><i class="bi bi-inbox me-2"></i> Appeals</a>
                    </li>
                </ul>
                <hr>
                <button class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-10 offset-lg-2 p-0">
            <!-- Mobile Header -->
            <nav class="navbar navbar-light bg-white shadow-sm d-lg-none px-4 py-3 sticky-top">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('images/offlogo_admin.png') }}" width="40">
                    <span class="fw-bold h6 m-0">PetApp Admin</span>
                </div>
                <button class="btn btn-light rounded-circle p-2" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                    <i class="bi bi-list fs-5"></i>
                </button>
            </nav>

            <div class="p-4 p-lg-5 fade-in-up">
                <!-- Header Section -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
                    <div>
                        <h2 class="fw-bold text-dark mb-1">Dashboard Overview</h2>
                        <p class="text-muted mb-0">Welcome back! Here's what's happening today.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-white bg-white border shadow-sm rounded-pill px-4 fw-semibold text-muted">
                            <i class="bi bi-calendar4 me-2"></i> {{ now()->format('M d, Y') }}
                        </button>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row g-4 mb-5">
                    @php
                        $stats = [
                            ['label' => 'Total Clinics', 'value' => $totalClinics, 'icon' => 'bi-hospital', 'color' => 'primary', 'bg' => 'bg-primary-subtle'],
                            ['label' => 'Pending Verification', 'value' => $unverifiedClinics, 'icon' => 'bi-hourglass-split', 'color' => 'warning', 'bg' => 'bg-warning-subtle'],
                            ['label' => 'Pet Owners', 'value' => $totalPetOwners, 'icon' => 'bi-people', 'color' => 'info', 'bg' => 'bg-info-subtle'],
                            ['label' => 'Upcoming Appts', 'value' => $upcomingAppointments, 'icon' => 'bi-calendar-check', 'color' => 'success', 'bg' => 'bg-success-subtle'],
                        ];
                    @endphp

                    @foreach($stats as $stat)
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="dashboard-card h-100 p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="stat-icon {{ $stat['bg'] }} text-{{ $stat['color'] }}">
                                    <i class="bi {{ $stat['icon'] }}"></i>
                                </div>
                                <span class="badge {{ $stat['bg'] }} text-{{ $stat['color'] }} rounded-pill">+2.5%</span>
                            </div>
                            <h2 class="fw-bold text-dark mb-1">{{ $stat['value'] }}</h2>
                            <p class="text-muted small mb-0">{{ $stat['label'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Content Grid -->
                <div class="row g-4 mb-5">
                    <!-- Pending Verifications -->
                    <div class="col-lg-6 col-xl-6">
                        <div class="dashboard-card h-100">
                            <div class="d-flex justify-content-between align-items-center p-4 border-bottom border-light">
                                <h5 class="fw-bold m-0 text-dark">Pending Verifications</h5>
                                <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">{{ $unverifiedClinics }} Pending</span>
                            </div>
                            
                            @if(session('success'))
                                <div class="alert alert-success m-4 rounded-3 border-0 bg-success-subtle text-success">
                                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                                </div>
                            @endif

                            <div class="table-responsive">
                                @if(empty($clinics) || $clinics->isEmpty())
                                    <div class="text-center py-5">
                                        <div class="bg-light rounded-circle p-4 d-inline-block mb-3">
                                            <i class="bi bi-check2-circle fs-1 text-muted"></i>
                                        </div>
                                        <p class="text-muted fw-medium">All clinics verified!</p>
                                    </div>
                                @else
                                    <table class="table table-custom mb-0">
                                        <thead>
                                            <tr>
                                                <th>Clinic Details</th>
                                                <th>Submitted</th>
                                                <th>Status</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($clinics as $clinic)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div class="bg-light rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                                <i class="bi bi-building text-muted"></i>
                                                            </div>
                                                            <div>
                                                                <p class="mb-0 fw-bold text-dark">{{ $clinic->clinic_name }}</p>
                                                                <small class="text-muted">{{ $clinic->email }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted small">{{ $clinic->created_at->format('M d, Y') }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-soft-warning">Pending Review</span>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-light btn-sm rounded-pill px-3 fw-semibold viewClinicBtn"
                data-id="{{ $clinic->id }}"
                data-name="{{ $clinic->clinic_name }}"
                data-owner="{{ $clinic->username }}"
                data-email="{{ $clinic->email }}"
                data-documents='@json($clinic->documents)'
                data-receipt="{{ $clinic->subscription_receipt }}"
                data-status="Pending">
                Review
            </button>
                                                            <form action="{{ route('admin.clinics.verify', $clinic->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 fw-semibold">
                                                                    Approve
                                                                </button>
                                                            </form>
                                                            <button type="button"
                                                                    class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-semibold denyClinicBtn"
                                                                    data-clinic-id="{{ $clinic->id }}"
                                                                    data-clinic-name="{{ $clinic->clinic_name }}">
                                                                Deny
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Verified Clinics List -->
                    <div class="col-lg-6 col-xl-6">
                        <div class="dashboard-card h-100">
                            <div class="p-4 border-bottom border-light">
                                <h5 class="fw-bold m-0 text-dark">Verified Clinics</h5>
                            </div>
                            <div class="p-0 overflow-auto" style="max-height: 500px;">
                                @if(empty($verifiedClinicList) || $verifiedClinicList->isEmpty())
                                    <div class="text-center py-5">
                                        <p class="text-muted">No verified clinics yet.</p>
                                    </div>
                                @else
                                    <div class="list-group list-group-flush">
                                        @foreach($verifiedClinicList as $clinic)
                                            @php
                                                $expiresAt = $clinic->subscription_expires_at;
                                                $now = \Carbon\Carbon::now();
                                                $statusLabel = 'Subscription Not Paid';
                                                $badgeClass = 'bg-secondary-subtle text-secondary';
                                                $countdownText = null;
                                                $rangeText = null;

                                                if ($clinic->subscription_receipt && !$clinic->is_subscribed) {
                                                    $statusLabel = 'Receipt Pending Approval';
                                                    $badgeClass = 'bg-warning-subtle text-warning';
                                                }

                                                if ($clinic->is_subscribed) {
                                                    if ($expiresAt) {
                                                        if ($expiresAt->isPast()) {
                                                            $statusLabel = 'Subscription Expired';
                                                            $badgeClass = 'bg-danger-subtle text-danger';
                                                            $countdownText = 'Expired';
                                                            $rangeText = 'Ended on ' . $expiresAt->format('M d, Y');
                                                        } else {
                                                            $daysLeft = $now->diffInDays($expiresAt, false);
                                                            $statusLabel = 'Subscription Active';
                                                            $badgeClass = 'bg-success-subtle text-success';
                                                            if ($daysLeft === 0) {
                                                                $countdownText = 'Expires today';
                                                                $rangeText = 'until ' . $expiresAt->format('M d, Y');
                                                            } elseif ($daysLeft > 0) {
                                                                $countdownText = $daysLeft . ' day' . ($daysLeft === 1 ? '' : 's') . ' left';
                                                                $rangeText = 'until ' . $expiresAt->format('M d, Y');
                                                            }
                                                        }
                                                    } else {
                                                        $statusLabel = 'Subscription Active';
                                                        $badgeClass = 'bg-success-subtle text-success';
                                                    }
                                                }
                                            @endphp

                                            <div class="list-group-item p-3 border-light d-flex justify-content-between align-items-center viewClinicBtn" 
                                                style="cursor: pointer; transition: background-color 0.2s;"
                                                onmouseover="this.classList.add('bg-light')"
                                                onmouseout="this.classList.remove('bg-light')"
                                                data-id="{{ $clinic->id }}"
                                                data-name="{{ $clinic->clinic_name }}"
                                                data-owner="{{ $clinic->username }}"
                                                data-email="{{ $clinic->email }}"
                                                data-documents='@json($clinic->documents)'
                                                data-receipt="{{ $clinic->subscription_receipt }}"
                                                data-status="Verified">
                                                
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="bg-success-subtle rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="bi bi-hospital fs-5 text-success"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <p class="mb-0 fw-bold text-dark small">{{ $clinic->clinic_name }}</p>
                                                                <small class="text-muted d-block" style="font-size: 0.75rem;">{{ $clinic->email }}</small>
                                                            </div>
                                                            <span class="badge {{ $badgeClass }} rounded-pill ms-2">{{ $statusLabel }}</span>
                                                        </div>
                                                        @if($expiresAt)
                                                            <div class="mt-1 d-flex flex-wrap align-items-center gap-2">
                                                                @if($countdownText)
                                                                    <span class="badge bg-light text-secondary border small px-2 py-1">
                                                                        <i class="bi bi-clock-history me-1"></i>{{ $countdownText }}
                                                                    </span>
                                                                @endif
                                                                @if($rangeText)
                                                                    <span class="small text-muted">
                                                                        {{ $rangeText }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="z-2 position-relative d-flex align-items-center gap-2" onclick="event.stopPropagation()">
                                                    @if($clinic->subscription_receipt && !$clinic->is_subscribed)
                                                        <a href="{{ asset('storage/clinics/subscription_receipts/' . $clinic->subscription_receipt) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                            View Receipt
                                                        </a>
                                                        <form action="{{ route('admin.clinics.approveSubscription', $clinic->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success rounded-pill px-3">
                                                                Approve Subscription
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if($clinic->is_subscribed)
                                                        <form action="{{ route('admin.clinics.testExpiry', $clinic->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-warning rounded-pill px-3">
                                                                Test Expiry
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('admin.clinics.delete', $clinic->id) }}" method="POST" onsubmit="return confirm('Delete this clinic?');" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link text-danger p-0" title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="dashboard-card p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-bold m-0">Verification Status</h5>
                                <button class="btn btn-sm btn-light rounded-pill px-3">Last 30 Days</button>
                            </div>
                            <div class="position-relative" style="height: 250px;">
                                <canvas id="clinicChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dashboard-card p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-bold m-0">Appointments Overview</h5>
                                <button class="btn btn-sm btn-light rounded-pill px-3">This Week</button>
                            </div>
                            <div class="position-relative" style="height: 250px;">
                                <canvas id="appointmentChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- Hidden chart data --}}
<div id="chartData"
    data-verified="{{ $verifiedClinics ?? 0 }}"
    data-unverified="{{ $unverifiedClinics ?? 0 }}"
    data-appt-labels='@json($appointmentsPerClinicLabels ?? [])'
    data-appt-data='@json($appointmentsPerClinicData ?? [])'
    style="display:none;"></div>

{{-- Logout Modal --}}
<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow-lg">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">Confirm Logout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body py-4">
          <div class="text-center mb-3">
              <i class="bi bi-box-arrow-right text-warning display-1"></i>
          </div>
          <p class="text-center text-muted mb-0">Are you sure you want to end your session?</p>
      </div>
      <div class="modal-footer border-0 pt-0 justify-content-center pb-4">
        <button type="button" class="btn btn-light fw-bold rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
        <form action="{{ route('logout') }}" method="POST">@csrf
            <button type="submit" class="btn btn-danger fw-bold rounded-pill px-4">Logout</button>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Clinic View Modal --}}
<div class="modal fade" id="clinicViewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
      <!-- Modern Header -->
      <div class="modal-header border-0 bg-white p-4 pb-0">
        <div>
            <h4 class="modal-title fw-bold text-dark mb-1" id="modalClinicName">Clinic Name</h4>
            <span class="badge rounded-pill px-3 py-2" id="modalClinicStatus">Status</span>
        </div>
        <button type="button" class="btn-close bg-light rounded-circle p-2" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body p-0">
        <!-- Modern Tabs -->
        <div class="px-4 mt-3 border-bottom">
            <ul class="nav nav-tabs nav-justified border-0" id="clinicTabs" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active py-3 border-0 fw-semibold text-secondary position-relative" 
                    id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview-pane" type="button" role="tab">
                    Overview
                    <span class="active-indicator position-absolute bottom-0 start-0 w-100 bg-success" style="height: 3px; display: none;"></span>
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link py-3 border-0 fw-semibold text-secondary position-relative" 
                    id="services-tab" data-bs-toggle="tab" data-bs-target="#services-pane" type="button" role="tab">
                    Services
                    <span class="active-indicator position-absolute bottom-0 start-0 w-100 bg-success" style="height: 3px; display: none;"></span>
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link py-3 border-0 fw-semibold text-secondary position-relative" 
                    id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews-pane" type="button" role="tab">
                    Reviews
                    <span class="active-indicator position-absolute bottom-0 start-0 w-100 bg-success" style="height: 3px; display: none;"></span>
                </button>
              </li>
            </ul>
        </div>

        <div class="tab-content bg-light p-4" id="clinicTabsContent" style="min-height: 300px;">
            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="overview-pane" role="tabpanel">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="bg-white p-3 rounded-4 shadow-sm h-100 d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle">
                                <i class="bi bi-person-fill fs-4"></i>
                            </div>
                            <div>
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Owner</label>
                                <span class="fs-6 fw-bold text-dark" id="modalClinicOwner"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-white p-3 rounded-4 shadow-sm h-100 d-flex align-items-center gap-3">
                            <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-circle">
                                <i class="bi bi-envelope-fill fs-4"></i>
                            </div>
                            <div>
                                <label class="small text-muted fw-bold text-uppercase d-block mb-1">Email</label>
                                <span class="fs-6 fw-bold text-dark text-break" id="modalClinicEmail"></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-4 shadow-sm p-4">
                    <h6 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-text text-secondary"></i> Submitted Documents
                    </h6>
                    <div id="modalClinicDocuments" class="d-flex flex-wrap gap-3"></div>
                </div>
            </div>

            <!-- Services Tab -->
            <div class="tab-pane fade" id="services-pane" role="tabpanel">
                <div id="modalServicesLoader" class="text-center py-5">
                    <div class="spinner-border text-success" role="status"></div>
                    <p class="mt-2 text-muted small">Loading services...</p>
                </div>
                <div id="modalServicesContent"></div>
            </div>

            <!-- Reviews Tab -->
            <div class="tab-pane fade" id="reviews-pane" role="tabpanel">
                <div id="modalReviewsLoader" class="text-center py-5">
                    <div class="spinner-border text-success" role="status"></div>
                    <p class="mt-2 text-muted small">Loading reviews...</p>
                </div>
                <div id="modalReviewsContent"></div>
            </div>
        </div>

      </div>
    </div>
  </div>
</div>

<style>
/* Custom Tab Styles */
.nav-link.active {
    color: #198754 !important;
    background: transparent !important;
}
.nav-link.active .active-indicator {
    display: block !important;
}
.nav-link:hover {
    color: #198754;
}
</style>

{{-- Full Document Preview Modal --}}
<div class="modal fade" id="documentPreviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content bg-dark text-white text-center">
      <div class="modal-header border-0">
        <h5 class="modal-title">Document Preview</h5>
        <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3" id="backToClinicView">
            <i class="bi bi-arrow-left me-2"></i>Back to Clinic Info
        </button>
      </div>
      <div class="modal-body d-flex justify-content-center align-items-center">
        <div id="documentPreviewContainer" class="w-100 h-100 d-flex justify-content-center align-items-center"></div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="denyClinicModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
      <div class="modal-header bg-danger text-white border-0">
        <h5 class="modal-title fw-bold"><i class="bi bi-x-octagon-fill me-2"></i>Deny Verification</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" id="denyClinicForm">
        @csrf
        <div class="modal-body p-4 bg-light-subtle">
          <div class="fw-bold text-dark mb-1" id="denyClinicName">Clinic</div>
          <div class="text-muted small mb-3">State the reason for denial. This request will be removed.</div>
          <label class="form-label fw-bold text-secondary small text-uppercase">Reason</label>
          <textarea name="reason" class="form-control shadow-sm" rows="4" required placeholder="Write the reason here..."></textarea>
        </div>
        <div class="modal-footer border-0 bg-light-subtle pb-4 px-4">
          <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">Deny & Remove</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart setup
const chartDiv = document.getElementById('chartData');
const verifiedClinics = Number(chartDiv.dataset.verified);
const unverifiedClinics = Number(chartDiv.dataset.unverified);
const appointmentLabels = JSON.parse(chartDiv.dataset.apptLabels);
const appointmentData = JSON.parse(chartDiv.dataset.apptData);

const ctxClinic = document.getElementById('clinicChart').getContext('2d');
const clinicChart = new Chart(ctxClinic, {
    type: 'doughnut',
    data: {
        labels: ['Verified', 'Unverified'],
        datasets: [{ 
            data: [verifiedClinics, unverifiedClinics], 
            backgroundColor: ['#198754', '#ffc107'],
            borderWidth: 0,
            hoverOffset: 4
        }]
    },
    options: { 
        responsive: true, 
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom' }
        },
        cutout: '70%'
    }
});

function updateChart(data) {
    clinicChart.data.datasets[0].data = data;
    clinicChart.update();
}

// Bind both desktop and mobile buttons
['showVerified', 'showVerifiedMobile'].forEach(id => {
    const btn = document.getElementById(id);
    if(btn) btn.addEventListener('click', () => updateChart([verifiedClinics, 0]));
});

['showUnverified', 'showUnverifiedMobile'].forEach(id => {
    const btn = document.getElementById(id);
    if(btn) btn.addEventListener('click', () => updateChart([0, unverifiedClinics]));
});

['showAll', 'showAllMobile'].forEach(id => {
    const btn = document.getElementById(id);
    if(btn) btn.addEventListener('click', () => updateChart([verifiedClinics, unverifiedClinics]));
});

const ctxAppt = document.getElementById('appointmentChart').getContext('2d');
new Chart(ctxAppt, {
    type: 'bar',
    data: { 
        labels: appointmentLabels, 
        datasets: [{ 
            label: 'Appointments', 
            data: appointmentData, 
            backgroundColor: '#198754',
            borderRadius: 5
        }] 
    },
    options: { 
        responsive: true, 
        maintainAspectRatio: false,
        scales: {
            y: { beginAtZero: true, grid: { display: false } },
            x: { grid: { display: false } }
        },
        plugins: { legend: { display: false } }
    }
});

// Clinic view modal logic
document.querySelectorAll('.viewClinicBtn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        const owner = this.dataset.owner;
        const email = this.dataset.email;
        let docs = [];
        const receipt = this.dataset.receipt;
        try {
            docs = JSON.parse(this.dataset.documents) || [];
        } catch (e) {
            docs = [];
        }
        const status = this.dataset.status;

        // Populate Overview
        document.getElementById('modalClinicName').textContent = name;
        document.getElementById('modalClinicOwner').textContent = owner;
        document.getElementById('modalClinicEmail').textContent = email;

        const statusBadge = document.getElementById('modalClinicStatus');
        if (status === 'Verified') {
            statusBadge.className = 'badge bg-success-subtle text-success rounded-pill';
            statusBadge.textContent = 'Verified';
        } else {
            statusBadge.className = 'badge bg-warning-subtle text-warning rounded-pill';
            statusBadge.textContent = 'Pending Verification';
        }

        const docContainer = document.getElementById('modalClinicDocuments');
        docContainer.innerHTML = '';

        if (docs && docs.length > 0) {
            docs.forEach(doc => {
                const ext = doc.split('.').pop().toLowerCase();
                const path = `{{ asset('storage') }}/${doc}`;
                if (['jpg','jpeg','png','gif','webp'].includes(ext)) {
                    docContainer.innerHTML += `
                        <div class="position-relative hover-scale">
                            <img src="${path}" alt="Document" class="rounded-3 shadow-sm border doc-image" 
                                 style="width:100px; height:100px; object-fit: cover; cursor:pointer; transition: transform 0.2s;">
                        </div>`;
                } else {
                    docContainer.innerHTML += `
                        <a href="${path}" target="_blank" class="btn btn-outline-dark btn-sm rounded-pill">
                            <i class="bi bi-file-earmark-text me-1"></i> View ${ext.toUpperCase()}
                        </a>`;
                }
            });
        } else {
            docContainer.innerHTML = `<p class="text-muted fst-italic">No documents uploaded.</p>`;
        }

        if (receipt) {
            const receiptPath = `{{ asset('storage/clinics/subscription_receipts') }}/${receipt}`;
            const receiptBlock = `
                <div class="mt-3">
                    <h6 class="fw-semibold mb-2">Payment Proof</h6>
                    <div class="d-flex align-items-center gap-3">
                        <div class="position-relative hover-scale">
                            <img src="${receiptPath}" alt="Subscription Receipt" class="rounded-3 shadow-sm border"
                                 style="width:120px; height:120px; object-fit: cover; cursor:pointer; transition: transform 0.2s;">
                        </div>
                        <a href="${receiptPath}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill">
                            View Full Receipt
                        </a>
                    </div>
                </div>
            `;
            docContainer.insertAdjacentHTML('beforeend', receiptBlock);
        }

        // Reset Tabs
        const triggerFirstTab = new bootstrap.Tab(document.querySelector('#clinicTabs button[data-bs-target="#overview-pane"]'));
        triggerFirstTab.show();

        // Clear Services and Reviews
        document.getElementById('modalServicesContent').innerHTML = '';
        document.getElementById('modalReviewsContent').innerHTML = '';
        document.getElementById('modalServicesLoader').style.display = 'block';
        document.getElementById('modalReviewsLoader').style.display = 'block';

        // Show Modal
        new bootstrap.Modal(document.getElementById('clinicViewModal')).show();

        // Fetch Details (Services & Reviews)
        fetch(`{{ url('admin/clinics') }}/${id}/details`)
            .then(response => response.json())
            .then(data => {
                // Populate Services
                const servicesContainer = document.getElementById('modalServicesContent');
                document.getElementById('modalServicesLoader').style.display = 'none';
                
                if (data.services.length > 0) {
                    let servicesHtml = '<div class="list-group list-group-flush rounded-3">';
                    data.services.forEach(service => {
                        servicesHtml += `
                            <div class="list-group-item p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold">${service.name}</h6>
                                    <span class="badge bg-success-subtle text-success rounded-pill">${service.price ? '₱' + service.price : 'Price Varies'}</span>
                                </div>
                                <p class="text-muted small mb-0 mt-1">${service.description || 'No description provided.'}</p>
                            </div>`;
                    });
                    servicesHtml += '</div>';
                    servicesContainer.innerHTML = servicesHtml;
                } else {
                    servicesContainer.innerHTML = '<p class="text-center text-muted fst-italic py-3">No services listed.</p>';
                }

                // Populate Reviews
                const reviewsContainer = document.getElementById('modalReviewsContent');
                document.getElementById('modalReviewsLoader').style.display = 'none';

                if (data.reviews.length > 0) {
                    let reviewsHtml = '<div class="d-flex flex-column gap-3">';
                    data.reviews.forEach(review => {
                        let stars = '';
                        for(let i=1; i<=5; i++) {
                            stars += `<i class="bi bi-star${i <= review.rating ? '-fill text-warning' : ' text-muted'}"></i>`;
                        }

                        reviewsHtml += `
                            <div class="card border-0 shadow-sm bg-light">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h6 class="fw-bold mb-0">${review.reviewer_name}</h6>
                                        <small class="text-muted">${review.date}</small>
                                    </div>
                                    <div class="mb-2 text-warning small">${stars}</div>
                                    <p class="mb-0 text-muted small">${review.review || 'No written review.'}</p>
                                </div>
                            </div>`;
                    });
                    reviewsHtml += '</div>';
                    reviewsContainer.innerHTML = reviewsHtml;
                } else {
                    reviewsContainer.innerHTML = '<p class="text-center text-muted fst-italic py-3">No reviews yet.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching clinic details:', error);
                document.getElementById('modalServicesLoader').style.display = 'none';
                document.getElementById('modalReviewsLoader').style.display = 'none';
                document.getElementById('modalServicesContent').innerHTML = '<p class="text-danger text-center">Failed to load services.</p>';
                document.getElementById('modalReviewsContent').innerHTML = '<p class="text-danger text-center">Failed to load reviews.</p>';
            });
    });
});

// Full document preview
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('doc-image')) {
        const src = e.target.src;
        document.getElementById('documentPreviewContainer').innerHTML =
            `<img src="${src}" alt="Full Document" class="img-fluid rounded shadow" style="max-height:90vh;">`;

        const clinicModal = bootstrap.Modal.getInstance(document.getElementById('clinicViewModal'));
        clinicModal.hide();

        const docPreview = new bootstrap.Modal(document.getElementById('documentPreviewModal'));
        docPreview.show();
    }
});

document.getElementById('backToClinicView').addEventListener('click', () => {
    const docModalEl = document.getElementById('documentPreviewModal');
    const clinicModalEl = document.getElementById('clinicViewModal');
    const docModal = bootstrap.Modal.getInstance(docModalEl);

    docModal.hide();
    docModalEl.addEventListener('hidden.bs.modal', function reopenClinicModal() {
        new bootstrap.Modal(clinicModalEl).show();
        docModalEl.removeEventListener('hidden.bs.modal', reopenClinicModal);
    });
});

const denyModalEl = document.getElementById('denyClinicModal');
const denyForm = document.getElementById('denyClinicForm');
const denyName = document.getElementById('denyClinicName');
const denyModal = denyModalEl ? new bootstrap.Modal(denyModalEl) : null;

document.querySelectorAll('.denyClinicBtn').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.clinicId;
        const name = btn.dataset.clinicName;
        if (denyName) denyName.textContent = name || 'Clinic';
        if (denyForm && id) {
            denyForm.action = "{{ url('admin/clinics') }}/" + id + "/deny";
        }
        denyModal?.show();
    });
});

</script>
