<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - NIT Medical Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @include('partials.footer-styles')
    <style>
        :root {
            --bg: #f4fafe;
            --panel: rgba(255, 255, 255, 0.94);
            --border: rgba(143, 211, 255, 0.4);
            --text: #0f172a;
            --muted: #64748b;
            --brand: #8fd3ff;
            --brand-2: #60bdf5;
        }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(143, 211, 255, 0.25), transparent 32%),
                radial-gradient(circle at bottom right, rgba(143, 211, 255, 0.14), transparent 28%),
                linear-gradient(160deg, #ffffff 0%, #eef8ff 55%, #f8fcff 100%);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .register-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 24px;
            gap: 24px;
        }

        .stage {
            flex: 1;
            display: grid;
            place-items: center;
        }

        .card-register {
            width: min(1120px, 100%);
            display: grid;
            grid-template-columns: 1fr 1fr;
            border-radius: 28px;
            overflow: hidden;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.88);
            box-shadow: 0 18px 48px rgba(15, 23, 42, 0.08);
            backdrop-filter: blur(20px);
        }

        .info-panel {
            padding: 40px;
            background:
                linear-gradient(180deg, rgba(37, 99, 235, 0.10), rgba(255, 255, 255, 0.12)),
                url('https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=1200&q=80') center/cover;
            position: relative;
            min-height: 620px;
        }

        .info-panel::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.16) 0%, rgba(15, 23, 42, 0.58) 100%);
        }

        .info-content {
            position: relative;
            z-index: 1;
            color: #16233a;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.90);
            border: 1px solid var(--border);
            width: fit-content;
        }

        .brand-logo {
            width: 34px;
            height: 34px;
            object-fit: contain;
        }

        .info-content h1 {
            font-size: clamp(2.2rem, 4vw, 4rem);
            line-height: 0.98;
            font-weight: 900;
            margin: 18px 0 12px;
            color: #ffffff;
            text-shadow: 0 10px 24px rgba(15, 23, 42, 0.35);
        }

        .info-content p {
            max-width: 52ch;
            color: rgba(255, 255, 255, 0.95);
            line-height: 1.8;
            text-shadow: 0 6px 18px rgba(15, 23, 42, 0.28);
        }

        .points {
            display: grid;
            gap: 14px;
            margin-top: 28px;
        }

        .point {
            padding: 16px;
            border-radius: 18px;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.92);
            color: #0f172a;
        }

        .form-panel {
            padding: 36px;
            background: rgba(255, 255, 255, 0.96);
        }

        .form-panel h2 {
            font-weight: 800;
            margin-bottom: 10px;
        }

        .form-panel p {
            color: var(--muted);
        }

        .form-control,
        .form-select {
            background: #ffffff;
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 14px;
            padding: 0.9rem 1rem;
        }

        .form-control:focus,
        .form-select:focus {
            background: #ffffff;
            color: var(--text);
            border-color: rgba(37, 99, 235, 0.45);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.10);
        }

        .password-field {
            position: relative;
        }

        .password-field .form-control {
            padding-right: 3.2rem;
        }

        .phone-group {
            display: flex;
            align-items: center;
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
            background: #ffffff;
        }

        .phone-prefix {
            padding: 0.9rem 1rem;
            background: #eef8ff;
            border-right: 1px solid var(--border);
            color: #0f172a;
            font-weight: 700;
            white-space: nowrap;
        }

        .phone-group .form-control {
            border: 0;
            border-radius: 0;
            box-shadow: none !important;
        }

        .phone-group:focus-within {
            border-color: rgba(37, 99, 235, 0.45);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.10);
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            color: var(--muted);
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-submit,
        .btn-linkish {
            border-radius: 14px;
            padding: 0.95rem 1rem;
            font-weight: 800;
            text-decoration: none;
        }

        .btn-submit {
            border: 0;
            color: white;
            background: linear-gradient(135deg, #8fd3ff, #4aaef0);
        }

        .btn-linkish {
            color: var(--text);
            background: rgba(37, 99, 235, 0.04);
            border: 1px solid var(--border);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .alert {
            border-radius: 16px;
        }

        .rule-list {
            list-style: none;
            margin: 12px 0 0;
            padding: 0;
            display: grid;
            gap: 8px;
        }

        .rule-list li {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.92rem;
            color: #dc2626;
            font-weight: 600;
        }

        .rule-list li.valid {
            color: #16a34a;
        }

        .rule-list li.invalid {
            color: #dc2626;
        }

        @media (max-width: 991.98px) {
            .card-register {
                grid-template-columns: 1fr;
            }

            .info-panel {
                min-height: 380px;
            }
        }
    </style>
</head>
<body>
    <div class="register-shell">
        <div class="stage">
            <div class="card-register">
                <section class="info-panel">
                    <div class="info-content">
                        <div class="brand">
                            <img src="{{ asset('images/NIT_logoBg.png') }}" alt="NIT Logo" class="brand-logo">
                            <span>NIT Medical Inventory</span>
                        </div>
                        <h1>Create one account, then log in by role.</h1>
                        <p>Register once with your real details. Your name inside the system will now come directly from the secure account you create here.</p>

                        <div class="points">
                            <div class="point">Use your full name, phone number, and work email during registration.</div>
                            <div class="point">Choose your role during registration: Pharmacist or Procurement Officer.</div>
                            <div class="point">Your password must be strong before the system accepts your account.</div>
                        </div>
                    </div>
                </section>

                <section class="form-panel">
                    <h2>Create account</h2>
                    <p class="mb-4">Fill your details once, then use the correct login page for your role.</p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.store') }}" autocomplete="off">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Full name</label>
                            <input type="text" id="name" name="name" class="form-control" required autocomplete="off">
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone number</label>
                            <div class="phone-group">
                                <span class="phone-prefix">+255</span>
                                <input type="tel" id="phone" name="phone" class="form-control" placeholder="7XXXXXXXX" required autocomplete="off" inputmode="numeric" maxlength="9" pattern="[0-9]{9}">
                            </div>
                            <div class="form-text mt-2">Enter 9 digits only after +255.</div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Work email address</label>
                            <input type="email" id="email" name="email" class="form-control" required autocomplete="off" autocapitalize="none" spellcheck="false">
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Account type</label>
                            <select id="role" name="role" class="form-select" required>
                                <option value="">Choose role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role }}">{{ $role === 'procurement' ? 'Procurement Officer' : 'Pharmacist' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="password-field">
                                <input type="password" id="password" name="password" class="form-control" required autocomplete="new-password">
                                <button type="button" class="password-toggle" data-toggle-password="password" aria-label="Show password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                            <ul class="rule-list" id="password-rules">
                                <li class="invalid" data-rule="length"><i class="fa-solid fa-circle"></i><span>Use 8-12 characters</span></li>
                                <li class="invalid" data-rule="uppercase"><i class="fa-solid fa-circle"></i><span>Include an uppercase letter</span></li>
                                <li class="invalid" data-rule="lowercase"><i class="fa-solid fa-circle"></i><span>Include a lowercase letter</span></li>
                                <li class="invalid" data-rule="number"><i class="fa-solid fa-circle"></i><span>Include a number</span></li>
                                <li class="invalid" data-rule="special"><i class="fa-solid fa-circle"></i><span>Include a special character</span></li>
                            </ul>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirm password</label>
                            <div class="password-field">
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required autocomplete="new-password">
                                <button type="button" class="password-toggle" data-toggle-password="password_confirmation" aria-label="Show password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-submit w-100">Register</button>
                    </form>

                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('login') }}" class="btn-linkish">
                            <i class="fa-solid fa-right-to-bracket"></i>
                            Already registered? Go to login
                        </a>
                    </div>
                </section>
            </div>
        </div>

        @include('partials.footer', [
            'footerClass' => 'standalone-footer',
        ])
    </div>

    <script>
        window.addEventListener('pageshow', function () {
            const form = document.querySelector('form');
            if (form) {
                form.reset();
            }
        });

        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function () {
                phoneInput.value = phoneInput.value.replace(/\D/g, '').slice(0, 9);
            });
        }

        const passwordInput = document.getElementById('password');
        const passwordRules = {
            length: function (value) { return value.length >= 8 && value.length <= 12; },
            uppercase: function (value) { return /[A-Z]/.test(value); },
            lowercase: function (value) { return /[a-z]/.test(value); },
            number: function (value) { return /[0-9]/.test(value); },
            special: function (value) { return /[^A-Za-z0-9]/.test(value); },
        };

        function updatePasswordRules() {
            const value = passwordInput ? passwordInput.value : '';

            document.querySelectorAll('#password-rules [data-rule]').forEach(function (item) {
                const passed = passwordRules[item.dataset.rule](value);
                item.classList.toggle('valid', passed);
                item.classList.toggle('invalid', !passed);
            });
        }

        if (passwordInput) {
            passwordInput.addEventListener('input', updatePasswordRules);
            updatePasswordRules();
        }

        document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
            button.addEventListener('click', function () {
                const input = document.getElementById(button.getAttribute('data-toggle-password'));
                const icon = button.querySelector('i');
                const show = input.type === 'password';

                input.type = show ? 'text' : 'password';
                icon.className = show ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
                button.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
            });
        });
    </script>
</body>
</html>
