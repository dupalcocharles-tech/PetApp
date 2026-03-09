@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5" style="background: linear-gradient(135deg, #f0fdf4 0%, #e6fffa 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-lg rounded-5 p-4 p-md-5 bg-white">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-dark">Confirm Account</h2>
                        <p class="text-muted small">Is this your account?</p>
                    </div>

                    <div class="bg-light rounded-4 p-3 mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-envelope text-muted me-2"></i>
                            <span class="text-dark fw-medium">{{ $maskedEmail }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-phone text-muted me-2"></i>
                            <span class="text-dark fw-medium">{{ $maskedPhone }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('password.sendOtp') }}">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <input type="hidden" name="role" value="{{ $role }}">
                        <input type="hidden" name="method" value="sms">

                        <p class="small text-muted mb-4">A 6-digit verification code will be sent via SMS to your registered phone number.</p>

                        <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill fw-bold shadow-sm">
                            Send Code via SMS
                        </button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('password.request', ['role' => $role]) }}" class="text-decoration-none text-muted small">Not my account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
