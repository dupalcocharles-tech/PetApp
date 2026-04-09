<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>PawPoint</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Page-specific styles --}}
    @yield('styles')

    <style>
        html, body {
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        html.dark-theme,
        body.dark-theme {
            background-color: #000;
        }
        body {
            border-top: none !important;
        }
        main {
            margin: 0 !important;
            padding: 0 !important;
        }
        body > *:first-child {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }

        .password-toggle-btn {
            position: absolute;
            top: 50%;
            right: 0.75rem;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            padding: 0.25rem;
            line-height: 1;
            color: #6c757d;
        }

        .password-toggle-btn:focus {
            outline: none;
            box-shadow: none;
        }
    </style>
</head>
<body>
    <main>
        @yield('content')
    </main>

    {{-- Bootstrap Bundle JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Chart.js (must be loaded before your chart code) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- AngularJS only if needed --}}
    @yield('angular-scripts')

    {{-- Page-specific scripts --}}
    @yield('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const passwordInputs = Array.from(document.querySelectorAll('input[type="password"]'))
                .filter(input => !input.hasAttribute('data-no-password-toggle'));

            passwordInputs.forEach(input => {
                if (!input || input.dataset.passwordToggleReady === '1') return;
                input.dataset.passwordToggleReady = '1';

                let container = input.closest('.input-group') || input.parentElement;
                if (!container) return;

                if (container.querySelector(':scope > .password-toggle-btn')) return;

                if (!container.classList.contains('position-relative')) {
                    container.classList.add('position-relative');
                }

                const currentPaddingEnd = parseFloat(getComputedStyle(input).paddingRight || '0') || 0;
                if (currentPaddingEnd < 44) {
                    input.style.paddingRight = '2.75rem';
                }

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'password-toggle-btn';
                btn.setAttribute('aria-label', 'Show password');
                btn.innerHTML = '<i class="bi bi-eye"></i>';

                btn.addEventListener('click', () => {
                    const isHidden = input.type === 'password';
                    input.type = isHidden ? 'text' : 'password';
                    btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
                    btn.innerHTML = isHidden ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
                });

                container.appendChild(btn);
            });
        });
    </script>
</body>
</html>
