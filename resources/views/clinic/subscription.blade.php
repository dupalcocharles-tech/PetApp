@extends('layouts.app')

@section('content')
<style>
    .subscription-bg {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #ecfeff 0%, #f0fdf4 100%);
    }
    .subscription-card {
        border-radius: 24px;
        box-shadow: 0 18px 45px rgba(15, 118, 110, 0.08);
    }
    .subscription-badge {
        border-radius: 999px;
        font-size: 0.75rem;
        padding-inline: 0.9rem;
        padding-block: 0.25rem;
    }
    .receipt-preview {
        width: 220px;
        height: 220px;
        border-radius: 20px;
        border: 1px dashed rgba(148, 163, 184, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #ffffff;
    }
    .receipt-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 16px;
    }
</style>

<div class="subscription-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card subscription-card border-0 bg-white">
                    <div class="card-header bg-white border-0 px-4 px-md-5 pt-4 pb-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1 fw-bold text-dark">Subscription Required</h3>
                            <p class="mb-0 text-muted small">Complete the subscription payment and upload proof so the admin can verify your clinic.</p>
                        </div>
                        <span class="badge bg-warning-subtle text-warning subscription-badge">Awaiting Payment</span>
                    </div>
                            <div class="card-body px-4 px-md-5 pb-4">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('message'))
                            <div class="alert alert-info">
                                {{ session('message') }}
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

                        @php
                            $expiresAt = $clinic->subscription_expires_at;
                            $now = \Carbon\Carbon::now();
                            $isExpired = $expiresAt && $expiresAt->isPast();
                            $daysLeft = $expiresAt ? $now->diffInDays($expiresAt, false) : null;
                        @endphp

                        @if ($clinic->is_subscribed && !$isExpired)
                            <div class="alert alert-success">
                                Subscription approved.
                                @if($expiresAt)
                                    @if($daysLeft === 0)
                                        <span class="d-block small mb-0">Expires today ({{ $expiresAt->format('M d, Y') }}).</span>
                                    @elseif($daysLeft > 0)
                                        <span class="d-block small mb-0">Expires in {{ $daysLeft }} day{{ $daysLeft === 1 ? '' : 's' }} (until {{ $expiresAt->format('M d, Y') }}).</span>
                                    @endif
                                @endif
                                <span class="d-block small">You can now access your dashboard.</span>
                            </div>
                            <div class="d-grid mt-3">
                                <a href="{{ route('clinic.dashboard') }}" class="btn btn-success fw-bold rounded-pill">
                                    Go to Dashboard
                                </a>
                            </div>
                        @else
                            <p class="text-muted small mb-4">
                                Please pay the clinic subscription fee using the admin-provided payment details, then upload a clear photo or screenshot of your payment receipt. The admin will review and approve your subscription.
                            </p>

                            @if(isset($admin) && $admin && $admin->qr_code)
                                <div class="mb-4 text-center">
                                    <h6 class="text-muted fw-semibold mb-2">Scan this QR code to pay your subscription</h6>
                                    <div class="receipt-preview mx-auto" data-bs-toggle="modal" data-bs-target="#qrFullscreenModal" style="cursor: pointer;">
                                        <img src="{{ asset('storage/admins/qr_codes/' . $admin->qr_code) }}" alt="Payment QR Code">
                                    </div>
                                    <div class="small text-muted mt-2">
                                        Tap the QR to view full size
                                    </div>
                                </div>
                            @endif

                            <div class="row g-4 align-items-center">
                                <div class="col-md-6">
                                    <form method="POST" action="{{ route('clinic.subscription.submit') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small text-uppercase text-muted mb-1">Receipt Image</label>
                                            <input type="file" name="receipt" class="form-control" accept="image/*" onchange="previewSubscriptionReceipt(this)">
                                            <div class="form-text small">
                                                JPG, PNG, GIF or WebP, max 5MB.
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success w-100 fw-bold rounded-pill">
                                            Submit Receipt
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-6 text-center">
                                    <div class="receipt-preview mx-auto" id="subscriptionReceiptPreview">
                                        @if($clinic->subscription_receipt)
                                            <img src="{{ asset('storage/clinics/subscription_receipts/' . $clinic->subscription_receipt) }}" alt="Subscription Receipt">
                                        @else
                                            <span class="text-muted small">No receipt uploaded yet</span>
                                        @endif
                                    </div>
                                    @if($clinic->subscription_receipt && !$clinic->is_subscribed)
                                        <div class="small text-warning mt-2">
                                            Receipt submitted. Waiting for admin approval.
                                        </div>
                                    @elseif($clinic->subscription_receipt && $isExpired)
                                        <div class="small text-danger mt-2">
                                            Previous subscription expired. Please upload a new receipt to renew.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($admin) && $admin && $admin->qr_code)
<div class="modal fade" id="qrFullscreenModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 bg-transparent">
            <div class="d-flex justify-content-end p-2">
                <button type="button" class="btn btn-light btn-sm rounded-pill px-3" data-bs-dismiss="modal">Close</button>
            </div>
            <div class="text-center pb-4">
                <img src="{{ asset('storage/admins/qr_codes/' . $admin->qr_code) }}" class="img-fluid rounded-3" alt="Payment QR Code Full">
            </div>
        </div>
    </div>
</div>
@endif

<script>
    function previewSubscriptionReceipt(input) {
        if (!input.files || !input.files[0]) {
            return;
        }
        var reader = new FileReader();
        reader.onload = function(e) {
            var container = document.getElementById('subscriptionReceiptPreview');
            if (!container) return;
            container.innerHTML = '';
            var img = document.createElement('img');
            img.src = e.target.result;
            container.appendChild(img);
        };
        reader.readAsDataURL(input.files[0]);
    }

    document.addEventListener('DOMContentLoaded', function () {
        // No subscription expired modal on this page anymore
    });
</script>
@endsection
