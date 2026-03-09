<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .admin-bg {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, #bbf7d0 0, rgba(187, 247, 208, 0) 55%),
                radial-gradient(circle at bottom right, #a5f3fc 0, rgba(165, 243, 252, 0) 55%),
                linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 100%);
        }
        .admin-card {
            max-width: 420px;
            border-radius: 20px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.15);
        }
        .admin-logo {
            width: 52px;
            height: 52px;
            border-radius: 999px;
            background: #ecfdf3;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
    </style>
</head>
<body class="admin-bg d-flex align-items-center justify-content-center">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 d-flex justify-content-center">
            <div class="card admin-card border-0 bg-white p-4 p-md-5">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="admin-logo">
                            <img src="{{ asset('images/offlogo_admin.png') }}" alt="Admin Logo" class="img-fluid" style="width: 40px; height: 40px; object-fit: contain;">
                        </div>
                        <div>
                            <div class="fw-bold text-dark">PetCare Admin</div>
                            <div class="small text-muted">Secure access for administrators</div>
                        </div>
                    </div>
                    <span class="badge bg-success-subtle text-success rounded-pill px-3">Admin</span>
                </div>

                <div class="mb-4">
                    <h4 class="fw-bold text-dark mb-1">Sign in to dashboard</h4>
                    <p class="text-muted small mb-0">Enter the admin password to continue.</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger rounded-4 mb-4 border-0 bg-danger-subtle text-danger small">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.post') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold text-uppercase">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg bg-light border-0 rounded-4" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill fw-bold mt-2">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
