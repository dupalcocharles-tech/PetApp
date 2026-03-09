@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5" style="background: linear-gradient(135deg, #f0fdf4 0%, #e6fffa 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-lg rounded-5 p-4 p-md-5 bg-white">
                    <div class="mb-4">
                        <a href="{{ route('auth.login', ['role' => $role]) }}" class="text-decoration-none text-muted small fw-semibold">
                            <i class="bi bi-arrow-left me-1"></i> Back to Login
                        </a>
                    </div>
                    
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-dark">Find Account</h2>
                        <p class="text-muted small">Enter your email, username, or registered phone number to recover your account.</p>
                    </div>

                    @if (session('message'))
                        <div class="alert alert-info rounded-4 border-0 bg-info-subtle text-info mb-4">
                            {{ session('message') }}
                        </div>
                    @endif

                    <form id="findAccountForm" method="POST" action="{{ route('password.find') }}">
                        @csrf
                        <input type="hidden" name="role" value="{{ $role }}">

                        <div class="mb-4">
                            <label class="form-label text-secondary small fw-bold text-uppercase">Account Identifier</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 rounded-start-4 ps-3">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" name="identifier" id="identifier" class="form-control form-control-lg bg-light border-0 rounded-end-4" required autofocus placeholder="Email, Username, or Phone">
                            </div>
                            <div id="identifierError" class="text-danger small mt-1 d-none"></div>
                        </div>

                        <button type="submit" id="findAccountBtn" class="btn btn-success btn-lg w-100 rounded-pill fw-bold shadow-sm transition-all hover-scale">
                            <span id="btnText">Find Account</span>
                            <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Account Confirmation Modal -->
<div class="modal fade" id="accountConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-5 overflow-hidden">
            <div class="modal-header bg-success text-white border-0 py-3">
                <h5 class="modal-title fw-bold">Is this your account?</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 p-md-5 text-center">
                <div class="mb-4">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                        <i class="bi bi-person-check-fill display-4 text-success"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">Account Found!</h4>
                    <p class="text-muted small">Please confirm if these details match your account.</p>
                </div>

                <div class="bg-light rounded-4 p-4 mb-4 text-start shadow-sm border border-light">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white rounded-3 p-2 me-3 shadow-sm">
                            <i class="bi bi-envelope-fill text-success"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Masked Email</small>
                            <span id="modalMaskedEmail" class="text-dark fw-bold"></span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="bg-white rounded-3 p-2 me-3 shadow-sm">
                            <i class="bi bi-phone-fill text-success"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Masked Phone</small>
                            <span id="modalMaskedPhone" class="text-dark fw-bold"></span>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('password.sendOtp') }}">
                    @csrf
                    <input type="hidden" name="user_id" id="modalUserId">
                    <input type="hidden" name="role" id="modalRole">
                    <input type="hidden" name="method" value="sms">

                    <p class="small text-muted mb-4 px-3">
                        A 6-digit verification code will be sent via SMS to your registered phone number to verify your identity.
                    </p>

                    <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill fw-bold shadow-sm transition-all hover-scale py-3">
                        <i class="bi bi-send-fill me-2"></i> Send Code via SMS
                    </button>
                </form>
                
                <div class="mt-3">
                    <button type="button" class="btn btn-link text-muted text-decoration-none small fw-semibold" data-bs-dismiss="modal">
                        Not my account
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const findAccountForm = document.getElementById('findAccountForm');
    const findAccountBtn = document.getElementById('findAccountBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');
    const identifierError = document.getElementById('identifierError');
    const accountConfirmModal = new bootstrap.Modal(document.getElementById('accountConfirmModal'));

    if (findAccountForm) {
        findAccountForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // UI States
            findAccountBtn.disabled = true;
            btnText.classList.add('d-none');
            btnSpinner.classList.remove('d-none');
            identifierError.classList.add('d-none');

            const formData = new FormData(findAccountForm);

            fetch("{{ route('password.find') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Populate Modal
                    document.getElementById('modalMaskedEmail').textContent = data.masked_email;
                    document.getElementById('modalMaskedPhone').textContent = data.masked_phone;
                    document.getElementById('modalUserId').value = data.user_id;
                    document.getElementById('modalRole').value = data.role;
                    
                    // Show Modal
                    accountConfirmModal.show();
                } else {
                    identifierError.textContent = data.message || 'Account not found.';
                    identifierError.classList.remove('d-none');
                }
            })
            .catch(err => {
                console.error(err);
                identifierError.textContent = 'An error occurred. Please try again.';
                identifierError.classList.remove('d-none');
            })
            .finally(() => {
                findAccountBtn.disabled = false;
                btnText.classList.remove('d-none');
                btnSpinner.classList.add('d-none');
            });
        });
    }
});
</script>
@endsection
@endsection
