@extends('layouts.app')

@section('content')
<style>
    .denied-bg {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 40%, #f8fafc 100%);
    }
    .denied-card {
        border-radius: 24px;
        box-shadow: 0 18px 45px rgba(239, 68, 68, 0.18);
    }
    .denied-icon {
        width: 82px;
        height: 82px;
        border-radius: 999px;
    }
</style>

<div class="denied-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card denied-card border-0 bg-white">
                    <div class="card-body px-4 px-md-5 py-4">
                        <div class="text-center mb-4">
                            <div class="denied-icon bg-danger-subtle text-danger d-inline-flex align-items-center justify-content-center">
                                <i class="bi bi-x-octagon-fill fs-2"></i>
                            </div>
                            <h3 class="mt-3 mb-1 fw-bold text-dark">Verification Denied</h3>
                            <p class="text-muted small mb-0">
                                Your clinic verification request was denied by the administrator.
                            </p>
                        </div>

                        <div class="mb-3">
                            <h6 class="fw-semibold text-dark mb-1">Clinic</h6>
                            <p class="small mb-0 text-muted">{{ $clinicName ?? 'Clinic' }}</p>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-semibold text-dark mb-1">Reason</h6>
                            <p class="small mb-0 text-muted">{{ $reason ?? 'No reason provided.' }}</p>
                        </div>

                        <div class="alert alert-warning border-0 bg-warning-subtle text-warning small rounded-4 mb-0">
                            This page will close in <span class="fw-bold" id="denyCountdown">15</span>s and redirect you to the landing page.
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 text-center pb-4">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold">
                            Back to Home
                        </a>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <p class="text-muted small mb-0">
                        © {{ date('Y') }} PetApp - Pet Clinic Appointment Booking System
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const counter = document.getElementById('denyCountdown');
        let secs = 15;
        const tick = () => {
            secs -= 1;
            if (counter) counter.textContent = String(Math.max(0, secs));
            if (secs <= 0) {
                try { window.close(); } catch (e) {}
                window.location.href = "{{ route('home') }}";
                return;
            }
            setTimeout(tick, 1000);
        };
        setTimeout(tick, 1000);
        setTimeout(() => {
            try { window.close(); } catch (e) {}
            window.location.href = "{{ route('home') }}";
        }, 15000);
    })();
</script>
@endsection

