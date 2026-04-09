@extends('layouts.app')

@section('content')
<style>
    .vet-bg-animation {
        background: linear-gradient(-45deg, #f0f9ff, #e0f2fe, #fdf2f8, #f0fdf4);
        background-size: 400% 400%;
        animation: gradient 15s ease infinite;
        min-height: 90vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    @keyframes gradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .floating-icon {
        position: absolute;
        color: rgba(0, 0, 0, 0.03);
        z-index: 0;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        z-index: 1;
    }
</style>

<div class="vet-bg-animation">
    <i class="fas fa-paw floating-icon" style="top: 10%; left: 10%; font-size: 8rem; transform: rotate(-20deg);"></i>
    <i class="fas fa-heartbeat floating-icon" style="bottom: 10%; right: 10%; font-size: 10rem; opacity: 0.02;"></i>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card glass-card p-4 p-md-5 text-center">
                    <div class="mb-4">
                        <div class="bg-danger text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 86px; height: 86px;">
                            <i class="fas fa-times-circle fa-3x"></i>
                        </div>
                    </div>

                    <h2 class="fw-bold text-dark mb-2">Clinic Verification Denied</h2>
                    <p class="text-muted mb-4">
                        Your clinic verification request was denied by the administrator.
                    </p>

                    <div class="text-start bg-light border-0 p-4 rounded-4">
                        <div class="small text-uppercase fw-bold text-danger mb-2" style="letter-spacing: 0.12em;">Reason</div>
                        <div class="text-dark" style="white-space: pre-wrap;">
                            {{ $clinic->verification_denied_reason ?? 'No reason provided.' }}
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-lg py-3 fw-bold shadow-sm w-100" style="border-radius: 15px;">
                                <i class="fas fa-home me-2"></i> Return Home
                            </button>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <p class="text-muted small">© {{ date('Y') }} PetApp - Pet Clinic Appointment Booking System</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://kit.fontawesome.com/your-code-here.js" crossorigin="anonymous"></script>
@endsection
