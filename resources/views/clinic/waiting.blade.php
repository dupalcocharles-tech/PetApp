@extends('layouts.app')

@section('content')
<style>
    /* Animated Background */
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

    /* Floating Decorative Elements */
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
        transition: transform 0.3s ease;
    }

    .pulse-warning {
        animation: pulse-yellow 2s infinite;
        border-radius: 50%;
    }

    @keyframes pulse-yellow {
        0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.4); }
        70% { box-shadow: 0 0 0 15px rgba(255, 193, 7, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
    }
</style>

<div class="vet-bg-animation">
    <i class="fas fa-paw floating-icon" style="top: 10%; left: 10%; font-size: 8rem; transform: rotate(-20deg);"></i>
    <i class="fas fa-heartbeat floating-icon" style="bottom: 10%; right: 10%; font-size: 10rem; opacity: 0.02;"></i>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card glass-card p-4 p-md-5 text-center">
                    
                    @if(Auth::user()->is_verified)
                        <div class="verification-icon mb-4">
                            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="fas fa-check-circle fa-3x"></i>
                            </div>
                        </div>
                        
                        <h2 class="fw-bold text-dark mb-2">Clinic Verified</h2>
                        <p class="text-muted mb-4">Welcome to the network! Your clinic is now active and ready to manage patients, schedules, and medical records.</p>
                        
                        <div class="d-grid">
                            <a href="{{ route('clinic.dashboard') }}" class="btn btn-success btn-lg py-3 fw-bold shadow-sm" style="border-radius: 15px;">
                                <i class="fas fa-columns me-2"></i> Enter Dashboard
                            </a>
                        </div>
                    @else
                        <div class="verification-icon mb-4">
                            <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center pulse-warning" style="width: 80px; height: 80px;">
                                <i class="fas fa-hourglass-half fa-2x"></i>
                            </div>
                        </div>
                        
                        <h2 class="fw-bold text-dark mb-2">Application Under Review</h2>
                        <p class="text-muted mb-4">Our medical board is currently reviewing your clinic's documentation. We prioritize safety and credentialing for all our pet partners.</p>
                        
                        <div class="alert bg-light border-0 py-3 mb-4" style="border-radius: 12px;">
                            <div class="d-flex align-items-center justify-content-center text-warning">
                                <span class="spinner-grow spinner-grow-sm me-2" role="status"></span>
                                <small class="fw-bold text-uppercase tracking-wider">Status: Pending Verification</small>
                            </div>
                        </div>

                        
                    @endif

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