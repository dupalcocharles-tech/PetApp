@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">Clinic Reports</h2>
        <form method="GET" class="d-flex align-items-center gap-2">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="pending" {{ ($status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="reviewed" {{ ($status ?? '') === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                <option value="dismissed" {{ ($status ?? '') === 'dismissed' ? 'selected' : '' }}>Dismissed</option>
            </select>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Clinic</th>
                            <th>Owner</th>
                            <th>Appointment</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr class="report-row" style="cursor: pointer;"
                                data-clinic="{{ $report->clinic?->clinic_name ?? 'Unknown Clinic' }}"
                                data-owner="{{ $report->owner?->full_name ?? 'Unknown Owner' }}"
                                data-appointment="{{ $report->appointment_id }}"
                                data-status="{{ $report->status }}"
                                data-proof="{{ $report->proof_image ? asset('storage/clinic_reports/'.$report->proof_image) : '' }}"
                                data-ban-action="{{ $report->clinic_id ? route('admin.clinics.ban', $report->clinic_id) : '' }}">
                                <td>{{ $report->clinic?->clinic_name ?? 'Unknown Clinic' }}</td>
                                <td>{{ $report->owner?->full_name ?? 'Unknown Owner' }}</td>
                                <td>#{{ $report->appointment_id }}</td>
                                <td style="max-width: 260px;">
                                    <div class="small text-muted">
                                        {{ \Illuminate\Support\Str::limit($report->report_message, 120) }}
                                    </div>
                                    <span class="d-none full-report-message">{{ $report->report_message }}</span>
                                    @if($report->proof_image)
                                        <a href="{{ asset('storage/clinic_reports/'.$report->proof_image) }}" target="_blank" class="small">
                                            View proof
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $report->status === 'pending' ? 'warning' : ($report->status === 'reviewed' ? 'primary' : 'secondary') }}">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        @if($report->status === 'pending')
                                            <form method="POST" action="{{ route('admin.reports.review', $report->id) }}">
                                                @csrf
                                                <button class="btn btn-sm btn-success">Mark Reviewed</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.reports.dismiss', $report->id) }}">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-secondary">Dismiss</button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.clinics.ban', $report->clinic_id) }}" onsubmit="return confirm('Ban this clinic based on this report?');">
                                            @csrf
                                            <input type="hidden" name="reason" value="{{ $report->report_message }}">
                                            <button class="btn btn-sm btn-outline-danger">Ban Clinic</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No reports found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($reports, 'links'))
            <div class="card-footer border-0">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modalEl = document.getElementById('reportDetailModal');
    if (!modalEl || typeof bootstrap === 'undefined' || !bootstrap.Modal) {
        return;
    }

    var detailModal = new bootstrap.Modal(modalEl);
    var clinicEl = document.getElementById('reportDetailClinic');
    var ownerEl = document.getElementById('reportDetailOwner');
    var appointmentEl = document.getElementById('reportDetailAppointment');
    var statusEl = document.getElementById('reportDetailStatus');
    var messageEl = document.getElementById('reportDetailMessage');
    var proofWrapperEl = document.getElementById('reportDetailProofWrapper');
    var proofImgEl = document.getElementById('reportDetailProofImage');
    var noProofEl = document.getElementById('reportDetailNoProof');
    var banForm = document.getElementById('banFromReportForm');
    var banReasonInput = document.getElementById('banReasonInput');

    document.querySelectorAll('.report-row').forEach(function (row) {
        row.addEventListener('click', function (e) {
            if (e.target.closest('form') || e.target.closest('button') || e.target.closest('a')) {
                return;
            }

            var clinic = row.dataset.clinic || '';
            var owner = row.dataset.owner || '';
            var appointment = row.dataset.appointment || '';
            var status = row.dataset.status || '';
            var proof = row.dataset.proof || '';
            var fullMessageEl = row.querySelector('.full-report-message');
            var fullMessage = fullMessageEl ? fullMessageEl.textContent.trim() : '';
            var banAction = row.dataset.banAction || '';

            if (clinicEl) clinicEl.textContent = clinic;
            if (ownerEl) ownerEl.textContent = owner;
            if (appointmentEl) appointmentEl.textContent = '#' + appointment;
            if (statusEl) statusEl.textContent = status ? status.charAt(0).toUpperCase() + status.slice(1) : '';
            if (messageEl) messageEl.textContent = fullMessage;

            if (proof && proofWrapperEl && proofImgEl) {
                proofWrapperEl.classList.remove('d-none');
                proofImgEl.src = proof;
                if (noProofEl) {
                    noProofEl.classList.add('d-none');
                }
            } else {
                if (proofWrapperEl && proofImgEl) {
                    proofWrapperEl.classList.add('d-none');
                    proofImgEl.src = '';
                }
                if (noProofEl) {
                    noProofEl.classList.remove('d-none');
                }
            }

            if (banForm && banAction) {
                banForm.action = banAction;
            }
            if (banReasonInput) {
                banReasonInput.value = fullMessage;
            }

            detailModal.show();
        });
    });
});
</script>

<div class="modal fade" id="reportDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header bg-white border-0">
                <div>
                    <h5 class="modal-title fw-bold mb-1">Clinic Report Details</h5>
                    <div class="small text-muted">
                        <span id="reportDetailClinic"></span> ·
                        <span id="reportDetailOwner"></span> ·
                        <span id="reportDetailAppointment"></span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light-subtle">
                <div class="mb-3">
                    <span class="badge bg-secondary" id="reportDetailStatus"></span>
                </div>
                <div class="mb-3">
                    <h6 class="fw-semibold mb-1">Report message</h6>
                    <p class="small mb-0" id="reportDetailMessage"></p>
                </div>
                <div class="mb-4">
                    <h6 class="fw-semibold mb-2">Proof image</h6>
                    <div id="reportDetailProofWrapper" class="d-none">
                        <img id="reportDetailProofImage" src="" alt="Proof image" class="img-fluid rounded shadow-sm">
                    </div>
                    <p id="reportDetailNoProof" class="small text-muted mb-0 d-none">No proof image provided.</p>
                </div>
                <div class="border-top pt-3">
                    <h6 class="fw-semibold mb-2">Ban this clinic</h6>
                    <p class="small text-muted">
                        You can impose a custom number of days for this ban. Leave the field
                        empty to set the ban without an end date.
                    </p>
                    <form id="banFromReportForm" method="POST">
                        @csrf
                        <input type="hidden" name="reason" id="banReasonInput">
                        <div class="mb-3">
                            <label for="banDaysInput" class="form-label small">Ban duration (days)</label>
                            <input type="number" name="days" id="banDaysInput" class="form-control" min="1" max="365" placeholder="e.g. 7">
                            <div class="form-text small">Optional. Maximum 365 days.</div>
                        </div>
                        <button type="submit" class="btn btn-danger rounded-pill px-4">Ban Clinic</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
