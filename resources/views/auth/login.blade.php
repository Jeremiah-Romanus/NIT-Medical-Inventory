<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ucfirst($role) }} Login - NIT Medical Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .login-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 24px;
            gap: 24px;
        }

        .login-stage {
            flex: 1;
            display: grid;
            place-items: center;
        }

        .login-card {
            width: min(1120px, 100%);
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            border-radius: 28px;
            overflow: hidden;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.88);
            box-shadow: 0 18px 48px rgba(15, 23, 42, 0.08);
            backdrop-filter: blur(20px);
        }

        .hero-panel {
            padding: 40px;
            background:
                linear-gradient(180deg, rgba(37, 99, 235, 0.10), rgba(255, 255, 255, 0.12)),
                url('https://images.unsplash.com/photo-1581056771107-24ca5f033842?auto=format&fit=crop&w=1200&q=80') center/cover;
            position: relative;
            min-height: 620px;
        }

        .hero-panel::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 35%, rgba(15, 23, 42, 0.40) 100%);
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 540px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 100%;
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

        .hero-title {
            font-size: clamp(2.4rem, 5vw, 4.8rem);
            line-height: 0.95;
            font-weight: 900;
            margin: 18px 0;
        }

        .hero-text {
            max-width: 56ch;
            color: #334155;
            line-height: 1.8;
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-top: 28px;
        }

        .hero-stat {
            padding: 16px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.90);
            border: 1px solid var(--border);
        }

        .hero-stat strong {
            display: block;
            font-size: 1.4rem;
        }

        .hero-stat span {
            color: var(--muted);
            font-size: 0.85rem;
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

        .form-control {
            background: #ffffff;
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 14px;
            padding: 0.9rem 1rem;
        }

        .form-control:focus {
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

        .btn-login {
            border: 0;
            border-radius: 14px;
            padding: 0.95rem 1rem;
            font-weight: 800;
            color: white;
            background: linear-gradient(135deg, #8fd3ff, #4aaef0);
        }

        .btn-alt {
            border-radius: 14px;
            padding: 0.9rem 1rem;
            font-weight: 700;
            color: var(--text);
            background: rgba(37, 99, 235, 0.04);
            border: 1px solid var(--border);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .help-box {
            margin-top: 24px;
            padding: 18px;
            border-radius: 18px;
            background: rgba(37, 99, 235, 0.04);
            border: 1px solid var(--border);
        }

        .help-box h6 {
            margin-bottom: 12px;
            font-weight: 800;
        }

        .help-box p {
            margin: 6px 0;
            color: #334155;
        }

        .alert {
            border-radius: 16px;
        }

        .lockout-box {
            margin-bottom: 18px;
            padding: 16px 18px;
            border-radius: 18px;
            background: rgba(220, 38, 38, 0.08);
            border: 1px solid rgba(220, 38, 38, 0.18);
            color: #991b1b;
        }

        .lockout-box strong {
            display: block;
            margin-bottom: 6px;
        }

        .form-locked {
            opacity: 0.65;
            pointer-events: none;
            user-select: none;
        }

        @media (max-width: 991.98px) {
            .login-card {
                grid-template-columns: 1fr;
            }

            .hero-panel {
                min-height: 420px;
            }
        }
    </style>
</head>
<body>
    <div class="login-shell">
        <div class="login-stage">
            <div class="login-card">
                <section class="hero-panel">
                    <div class="hero-content">
                        <div>
                            <div class="brand">
                                <img src="{{ asset('images/NIT_logoBg.png') }}" alt="NIT Logo" class="brand-logo">
                                <span>NIT Medical Inventory</span>
                            </div>
                            <h1 class="hero-title">Inventory control for clinical teams.</h1>
                            <p class="hero-text">
                                Keep medicines visible, requests organized, and expiry risks under control with a workflow built for
                                pharmacists and procurement officers.
                            </p>
                        </div>

                        <div class="hero-stats">
                            <div class="hero-stat">
                                <strong>2 roles</strong>
                                <span>Role-based access</span>
                            </div>
                            <div class="hero-stat">
                                <strong>Live stock</strong>
                                <span>Inventory visibility</span>
                            </div>
                            <div class="hero-stat">
                                <strong>Fast flow</strong>
                                <span>Requests to distribution</span>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="form-panel">
                    <h2>{{ ucfirst($role) }} sign in</h2>
                    <p class="mb-4">If you already registered as {{ $role }}, use your account to continue. If not, register first.</p>

                    @if (!empty($isLocked))
                        <div class="lockout-box">
                            <strong>Login temporarily locked</strong>
                            <div>
                                Too many failed attempts. Please wait
                                <span id="lockout-timer" data-seconds="{{ $lockoutSecondsRemaining ?? 0 }}"></span>
                                before trying again.
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.attempt', $role) }}" autocomplete="off" class="{{ !empty($isLocked) ? 'form-locked' : '' }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" id="email" name="email" class="form-control" required autofocus autocomplete="off" autocapitalize="none" spellcheck="false" @disabled(!empty($isLocked))>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="password-field">
                                <input type="password" id="password" name="password" class="form-control" required autocomplete="new-password" @disabled(!empty($isLocked))>
                                <button type="button" class="password-toggle" data-toggle-password="password" aria-label="Show password" @disabled(!empty($isLocked))>
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-login w-100" @disabled(!empty($isLocked))>
                            {{ !empty($isLocked) ? 'Login Locked' : 'Login' }}
                        </button>
                    </form>

                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('register') }}" class="btn-alt">
                            <i class="fa-solid fa-user-plus"></i>
                            Not registered yet? Register first
                        </a>
                        <a href="{{ route('login') }}" class="btn-alt">
                            <i class="fa-solid fa-arrow-left"></i>
                            Choose another login type
                        </a>
                    </div>

                    <div class="help-box">
                        <h6><i class="fa-solid fa-circle-info me-2"></i>Before you log in</h6>
                        <p>Use only the account details you created during registration.</p>
                        <p>If you do not have an account yet, register first and then return to this login page.</p>
                    </div>
                </section>
            </div>
        </div>

        @include('partials.footer', [
            'footerClass' => 'standalone-footer',
        ])
    </div>

    @include('partials.sweetalert')
    <script>
        window.addEventListener('pageshow', function () {
            const form = document.querySelector('form');
            if (form) {
                form.reset();
            }
        });

        const lockoutTimer = document.getElementById('lockout-timer');
        if (lockoutTimer) {
            let seconds = Number(lockoutTimer.dataset.seconds || 0);

            const renderTime = function () {
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = seconds % 60;
                lockoutTimer.textContent = minutes + ' minute(s) ' + remainingSeconds + ' second(s)';
            };

            renderTime();

            const interval = setInterval(function () {
                seconds -= 1;

                if (seconds <= 0) {
                    clearInterval(interval);
                    window.location.reload();
                    return;
                }

                renderTime();
            }, 1000);
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
