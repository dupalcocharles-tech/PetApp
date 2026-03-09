@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5" style="background: linear-gradient(135deg, #f0fdf4 0%, #e6fffa 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-lg rounded-5 p-4 p-md-5 bg-white">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-dark">Verify OTP</h2>
                        <p class="text-muted small">Enter the 6-digit code sent to your phone.</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger rounded-4 border-0 bg-danger-subtle text-danger mb-4">
                            <ul class="mb-0 small ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.verify') }}">
                        @csrf
                        <input type="hidden" name="otp_id" value="{{ $otpEntry->id }}">

                        <div class="mb-4 text-center">
                            <label class="form-label text-secondary small fw-bold text-uppercase d-block mb-3">6-Digit Code</label>
                            <input type="text" name="otp_code" class="form-control form-control-lg bg-light border-0 rounded-4 text-center fw-bold fs-2" 
                                   required maxlength="6" autofocus placeholder="000000" style="letter-spacing: 10px;">
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill fw-bold shadow-sm">
                            Verify OTP
                        </button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="small text-muted mb-0">Didn't receive the code? 
                            <a href="{{ route('password.request', ['role' => $otpEntry->user_type]) }}" class="text-success fw-bold text-decoration-none">Resend</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
