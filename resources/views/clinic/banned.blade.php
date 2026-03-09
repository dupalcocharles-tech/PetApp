@extends('layouts.app')

@section('content')
<style>
    .banned-bg {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #fef2f2 0%, #fef9c3 100%);
    }
    .banned-card {
        border-radius: 24px;
        box-shadow: 0 18px 45px rgba(248, 113, 113, 0.18);
    }
    .banned-icon {
        width: 80px;
        height: 80px;
        border-radius: 999px;
    }
</style>

<div class="banned-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card banned-card border-0 bg-white">
                    <div class="card-body px-4 px-md-5 py-4">
                        <div class="text-center mb-4">
                            <div class="banned-icon bg-danger-subtle text-danger d-inline-flex align-items-center justify-content-center">
                                <i class="fas fa-ban fa-2x"></i>
                            </div>
                            <h3 class="mt-3 mb-1 fw-bold text-dark">
                                Clinic Access Restricted
                            </h3>
                            <p class="text-muted small mb-0">
                                Your clinic account is currently banned from accessing the dashboard.
                            </p>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
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

                        <div class="mb-3">
                            <h6 class="fw-semibold text-dark mb-1">Ban reason</h6>
                            <p class="small mb-0 text-muted">
                                {{ $ban->reason ?? 'No reason provided.' }}
                            </p>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-semibold text-dark mb-1">Ban duration</h6>
                            @if($liftDate)
                                <p class="small mb-1 text-muted">
                                    Ban is active until {{ $liftDate->format('M d, Y') }}.
                                </p>
                                @if(!is_null($daysRemaining))
                                    <p class="small mb-0 text-muted">
                                        {{ $daysRemaining > 0 ? $daysRemaining.' days remaining.' : 'Ban end date is near or has passed.' }}
                                    </p>
                                @endif
                            @else
                                <p class="small mb-0 text-muted">
                                    This ban is currently set without an end date.
                                </p>
                            @endif
                        </div>

                        <hr>

                        <h6 class="fw-semibold text-dark mb-2">Submit a ban appeal</h6>
                        <p class="small text-muted">
                            If you believe this ban was made in error or there are important details
                            the admin team should review, you can submit an appeal below. The admin
                            will review your message and respond through this system.
                        </p>

                        <form method="POST" action="{{ route('clinic.banAppeal.store') }}" class="mt-3">
                            @csrf
                            <input type="hidden" name="ban_id" value="{{ $ban->id }}">

                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-uppercase text-muted">
                                    Appeal message
                                </label>
                                <textarea
                                    name="message"
                                    rows="4"
                                    class="form-control"
                                    placeholder="Explain your situation, provide context, and any supporting details."
                                >{{ old('message') }}</textarea>
                                <div class="form-text small">
                                    Maximum 2000 characters.
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-danger fw-bold rounded-pill">
                                    Submit Appeal
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer bg-white border-0 text-center pb-4">
                        <p class="small text-muted mb-0">
                            If you have urgent concerns, please contact the platform administrator.
                        </p>
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
@endsection

