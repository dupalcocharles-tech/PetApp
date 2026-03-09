@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5" style="background: linear-gradient(135deg, #f0fdf4 0%, #e6fffa 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-lg rounded-5 p-4 p-md-5 bg-white">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-dark">Change Password</h2>
                        <p class="text-muted small">Create a new secure password for your account.</p>
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

                    <form method="POST" action="{{ route('password.change') }}">
                        @csrf
                        <input type="hidden" name="otp_id" value="{{ $otpEntry->id }}">

                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold text-uppercase">New Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 rounded-start-4 ps-3">
                                    <i class="bi bi-lock text-muted"></i>
                                </span>
                                <input type="password" name="password" class="form-control form-control-lg bg-light border-0 rounded-end-4" required placeholder="••••••••">
                            </div>
                            <div class="form-text small text-muted mt-1">
                                Must be at least 8 characters, include an uppercase letter, a number, and a special character.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-secondary small fw-bold text-uppercase">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 rounded-start-4 ps-3">
                                    <i class="bi bi-lock-check text-muted"></i>
                                </span>
                                <input type="password" name="password_confirmation" class="form-control form-control-lg bg-light border-0 rounded-end-4" required placeholder="••••••••">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill fw-bold shadow-sm">
                            Reset Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
