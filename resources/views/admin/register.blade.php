<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>Register</title>
</head>

<body>
    @include("components.header")

    @if(session('error'))
        <div id="register-error" data-message="{{ session('error') }}"></div>
    @endif

    <section class="login-section">
        <h1>WELCOME</h1>

        <form id="register-form" action="{{ route('register') }}" method="POST">
            @csrf

            <fieldset>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </fieldset>

            <fieldset>
                <input type="text" id="name" name="name" placeholder="Username" required>
            </fieldset>

            <fieldset class="password-wrapper">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                    <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </fieldset>

            <fieldset class="password-wrapper">
                <input type="password" id="c-password" name="password_confirmation" placeholder="Confirm Password" required>
                <button type="button" class="password-toggle" onclick="togglePassword('c-password', this)">
                    <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </fieldset>

            <div id="password-warning" class="warning-text" style="display: none;">
                Passwords do not match.
            </div>

            <button type="submit">Register</button>
        </form>

        @if (session('error'))
            <script>
                alert(@json(session('error')));
            </script>
        @endif
    </section>

    <script>
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';

            const svg = btn.querySelector('svg');
            svg.innerHTML = isHidden
                ? `<path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.98 8.223A10.94 10.94 0 001.458 12c1.274 4.057 5.064 7 9.542 7 1.878 0 3.63-.52 5.114-1.416M19.74 15.54A10.947 10.947 0 0022.542 12c-1.274-4.057-5.064-7-9.542-7-1.538 0-2.998.347-4.308.97M3 3l18 18" />
                   <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9.88 9.88a3 3 0 104.24 4.24" />`
                : `<path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                   <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />`;
        }

        document.addEventListener("DOMContentLoaded", () => {
            const form = document.getElementById('register-form');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('c-password');
            const warning = document.getElementById('password-warning');

            form.addEventListener('submit', function (e) {
                if (password.value !== confirmPassword.value) {
                    warning.style.display = 'block';
                    e.preventDefault();
                } else {
                    warning.style.display = 'none';
                }
            });

            confirmPassword.addEventListener('input', () => {
                warning.style.display = (confirmPassword.value !== password.value) ? 'block' : 'none';
            });
        });
    </script>
</body>

</html>
