@extends('layouts.app')

@section('styles')
<style>
    body {
        background: linear-gradient(135deg, #f0fdf4 0%, #e6fffa 100%);
    }
    .settings-card {
        border-radius: 24px;
        box-shadow: 0 18px 45px rgba(15, 118, 110, 0.08);
    }
    .settings-header {
        border-bottom: 1px solid rgba(148, 163, 184, 0.2);
    }
    .settings-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 600;
        color: #6b7280;
    }
    .qr-preview {
        width: 160px;
        height: 160px;
        border-radius: 16px;
        border: 1px dashed rgba(148, 163, 184, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #ffffff;
    }
    .qr-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 12px;
    }
    .pill-badge {
        border-radius: 999px;
        font-size: 0.75rem;
        padding-inline: 0.75rem;
        padding-block: 0.25rem;
    }
</style>
@endsection

@section('content')
@php
    $admin = auth('admin')->user();
@endphp
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card settings-card border-0 bg-white">
                <div class="card-header settings-header bg-white px-4 px-md-5 py-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                    <div>
                        <h3 class="mb-1 fw-bold text-dark">Admin Settings</h3>
                        <p class="mb-0 text-muted">Manage your account security and payment details.</p>
                    </div>
                    <div class="mt-3 mt-md-0 text-md-end">
                        <div class="fw-semibold">{{ $admin->username ?? 'Admin' }}</div>
                        <div class="small text-muted">{{ $admin->email ?? '' }}</div>
                    </div>
                </div>
                <div class="card-body px-4 px-md-5 py-4">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.updatePassword') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <div class="col-md-6 border-end-md">
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="settings-label">Password</span>
                                        <span class="badge bg-success-subtle text-success pill-badge">Security</span>
                                    </div>
                                    <label class="form-label mb-1">New Password <span class="text-muted small">(optional)</span></label>
                                    <input type="password" name="password" class="form-control">
                                </div>

                                <div class="mb-4">
                                    <label class="form-label mb-1">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>

                                <p class="small text-muted mb-0">
                                    Use at least 8 characters. You will be asked to log in again after updating.
                                </p>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="settings-label">Online Payment QR</span>
                                    <span class="badge bg-primary-subtle text-primary pill-badge">GCash / Maya</span>
                                </div>

                                <div class="row g-3 align-items-center">
                                    <div class="col-sm-7">
                                        <label class="form-label mb-1">Upload QR Code Image</label>
                                        <input type="file" name="qr_code" class="form-control" accept="image/*" onchange="previewAdminQr(this)">
                                        <div class="form-text small">
                                            PNG or JPG, max 2MB.
                                        </div>
                                    </div>
                                    <div class="col-sm-5 text-center">
                                        <div class="qr-preview mx-auto" id="adminQrPreview">
                                            @if($admin && $admin->qr_code)
                                                <img src="{{ asset('storage/admins/qr_codes/' . $admin->qr_code) }}" alt="Admin QR Code">
                                            @else
                                                <span class="text-muted small">No QR Code</span>
                                            @endif
                                        </div>
                                        @if($admin && $admin->qr_code)
                                            <div class="small text-success mt-2">
                                                Current QR in use.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center border-top">
                            <p class="small text-muted mb-3 mb-md-0">
                                These settings affect how you access the admin panel and how users can pay online.
                            </p>
                            <button type="submit" class="btn btn-success px-4">
                                Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewAdminQr(input) {
        if (!input.files || !input.files[0]) {
            return;
        }
        var reader = new FileReader();
        reader.onload = function(e) {
            var container = document.getElementById('adminQrPreview');
            if (!container) return;
            container.innerHTML = '';
            var img = document.createElement('img');
            img.src = e.target.result;
            container.appendChild(img);
        };
        reader.readAsDataURL(input.files[0]);
    }
</script>
@endsection
