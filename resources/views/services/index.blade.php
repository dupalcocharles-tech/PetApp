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
    }

    .table td {
        vertical-align: middle;
        padding: 1rem 0.5rem;
        border-bottom: 1px solid #f3f4f6;
    }

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
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 main-content bg-light min-vh-100">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4 pt-5 pt-md-0">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Services List</h2>
                    <p class="text-muted mb-0">Manage the services offered by your clinic.</p>
                </div>
                <div>
                    <a href="{{ route('services.create') }}" class="btn btn-success fw-semibold px-4 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-plus-lg me-1"></i> Add Service
                    </a>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-secondary fw-bold small text-uppercase">
                                <tr>
                                    <th class="py-3 ps-4">Name</th>
                                    <th class="py-3">Description</th>
                                    <th class="py-3">Price</th>
                                    <th class="py-3">For Animal</th>
                                    <th class="py-3">Location</th>
                                    <th class="py-3">Availability</th>
                                    <th class="py-3">Home Slots</th>
                                    <th class="py-3 text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                @forelse($services as $service)
                                    <tr>
                                        <td data-label="Name" class="fw-bold text-dark ps-4">{{ $service->name }}</td>
                                        <td data-label="Description" class="text-secondary">{{ Str::limit($service->description, 50) }}</td>
                                        <td data-label="Price" class="fw-semibold text-success">{{ number_format($service->price, 2) }}</td>
                                        <td data-label="For Animal">
                                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1 rounded-pill">
                                                {{ $service->animal_type }}
                                            </span>
                                        </td>
                                        <td data-label="Location">
                                            @php
                                                $location = $service->location_type ?? 'clinic';
                                                $label = match ($location) {
                                                    'home' => 'Home Service',
                                                    'both' => 'Clinic & Home',
                                                    default => 'Clinic',
                                                };
                                                $badgeClass = match ($location) {
                                                    'home' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                                                    'both' => 'bg-success-subtle text-success-emphasis border border-success-subtle',
                                                    default => 'bg-primary-subtle text-primary-emphasis border border-primary-subtle',
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }} px-2 py-1 rounded-pill">
                                                {{ $label }}
                                            </span>
                                        </td>
                                        <td data-label="Availability">
                                            <form action="{{ route('services.toggleAvailability', $service->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm rounded-pill px-3 {{ $service->is_available ? 'btn-success' : 'btn-outline-secondary' }}">
                                                    {{ $service->is_available ? 'Available' : 'Unavailable' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td data-label="Home Slots">
                                            @if(in_array($service->location_type, ['home','both']))
                                                @php
                                                    $rawSlots = is_array($service->home_slots) ? $service->home_slots : [];
                                                    $slotsInfo = [];
                                                    foreach ($rawSlots as $slot) {
                                                        if (!$slot) continue;
                                                        $taken = \App\Models\Appointment::where('service_id', $service->id)
                                                            ->where('appointment_date', $slot)
                                                            ->exists();
                                                        $slotsInfo[] = [
                                                            'time' => $slot,
                                                            'taken' => $taken,
                                                        ];
                                                    }
                                                @endphp
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#homeSlotsModal"
                                                        data-service-id="{{ $service->id }}"
                                                        data-service-name="{{ $service->name }}"
                                                        data-service-slots='@json($slotsInfo)'>
                                                    Manage Slots
                                                </button>
                                            @else
                                                <span class="small text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td data-label="Actions" class="text-end pe-4">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('services.show', $service->id) }}" class="btn btn-sm btn-outline-primary rounded-start-pill px-3" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('services.edit', $service->id) }}" class="btn btn-sm btn-outline-warning rounded-end-pill px-3" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="text-muted opacity-50 mb-2">
                                                <i class="bi bi-box-seam display-4"></i>
                                            </div>
                                            <h6 class="text-muted fw-bold">No Services Found</h6>
                                            <p class="text-secondary small mb-3">Get started by adding your first service.</p>
                                            <a href="{{ route('services.create') }}" class="btn btn-sm btn-success rounded-pill px-3">
                                                Add Service
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<div class="modal fade" id="homeSlotsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow-lg">
      <div class="modal-header border-0 bg-success text-white rounded-top-4">
        <h5 class="modal-title fw-bold" id="homeSlotsModalLabel">Manage Home Slots</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="mb-3">
            <div class="small text-muted text-uppercase fw-bold mb-1">Service</div>
            <div class="fw-bold text-dark" id="homeSlotsServiceName"></div>
        </div>
        <div class="mb-3">
            <div class="small text-muted text-uppercase fw-bold mb-1">Existing Slots</div>
            <div id="homeSlotsList"></div>
        </div>
        <form id="homeSlotsForm" method="POST">
            @csrf
            <div class="small text-muted text-uppercase fw-bold mb-1">Add New Slot</div>
            <div class="input-group">
                <input type="datetime-local" name="slot" class="form-control" required>
                <button type="submit" class="btn btn-success">Add</button>
            </div>
        </form>
        <form id="homeSlotDeleteForm" method="POST" class="d-none">
            @csrf
            @method('DELETE')
            <input type="hidden" name="slot" value="">
        </form>
        <form id="homeSlotsClearForm" method="POST" class="mt-3 text-end">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm" disabled>Delete All Slots</button>
        </form>
      </div>
    </div>
  </div>
</div>

@include('staff.partials.scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('homeSlotsModal');
    if (!modalEl) return;

    const serviceNameEl = document.getElementById('homeSlotsServiceName');
    const slotsListEl = document.getElementById('homeSlotsList');
    const formEl = document.getElementById('homeSlotsForm');
    const deleteFormEl = document.getElementById('homeSlotDeleteForm');
    const clearFormEl = document.getElementById('homeSlotsClearForm');

    modalEl.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        if (!button) return;

        const serviceId = button.getAttribute('data-service-id');
        const serviceName = button.getAttribute('data-service-name') || '';
        const slotsRaw = button.getAttribute('data-service-slots') || '[]';

        let slots = [];
        try {
            slots = JSON.parse(slotsRaw) || [];
        } catch (e) {
            slots = [];
        }

        if (serviceNameEl) {
            serviceNameEl.textContent = serviceName;
        }

        if (slotsListEl) {
            slotsListEl.innerHTML = '';
            if (Array.isArray(slots) && slots.length) {
                const list = document.createElement('div');
                list.className = 'list-group';

                slots.forEach(function (info) {
                    const time = info && info.time ? info.time : '';
                    const taken = !!(info && info.taken);

                    const item = document.createElement('div');
                    item.className = 'list-group-item d-flex justify-content-between align-items-center py-3';

                    const timeEl = document.createElement('div');
                    timeEl.className = 'fw-semibold';
                    timeEl.textContent = time;

                    const badge = document.createElement('span');
                    badge.className = 'badge rounded-pill ' +
                        (taken ? 'bg-danger-subtle text-danger border border-danger-subtle'
                               : 'bg-success-subtle text-success border border-success-subtle');
                    badge.textContent = taken ? 'Taken' : 'Available';

                    item.appendChild(timeEl);
                    const rightGroup = document.createElement('div');
                    rightGroup.className = 'd-flex align-items-center gap-2';
                    rightGroup.appendChild(badge);

                    if (!taken && deleteFormEl && serviceId) {
                        const deleteBtn = document.createElement('button');
                        deleteBtn.type = 'button';
                        deleteBtn.className = 'btn btn-sm btn-outline-danger';
                        deleteBtn.textContent = 'Delete';
                        deleteBtn.addEventListener('click', function () {
                            if (!confirm('Delete this slot?')) {
                                return;
                            }
                            deleteFormEl.action = '/services/' + serviceId + '/home-slots';
                            const input = deleteFormEl.querySelector('input[name="slot"]');
                            if (input) {
                                input.value = time;
                            }
                            deleteFormEl.submit();
                        });
                        rightGroup.appendChild(deleteBtn);
                    }

                    item.appendChild(rightGroup);
                    list.appendChild(item);
                });

                slotsListEl.appendChild(list);
            } else {
                const empty = document.createElement('div');
                empty.className = 'small text-muted';
                empty.textContent = 'No slots yet';
                slotsListEl.appendChild(empty);
            }
        }

        if (formEl && serviceId) {
            formEl.action = '/services/' + serviceId + '/home-slots';
        }

        if (clearFormEl && serviceId) {
            clearFormEl.action = '/services/' + serviceId + '/home-slots/clear';
            const clearBtn = clearFormEl.querySelector('button[type="submit"]');
            if (clearBtn) {
                clearBtn.disabled = !(Array.isArray(slots) && slots.length);
            }
        }
    });
});
</script>
@endsection
