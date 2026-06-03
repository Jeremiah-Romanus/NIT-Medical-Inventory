<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.login') }} - {{ __('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    @include('partials.footer-styles')
    @include('partials.site-header-styles')
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
            background: #f7fbff;
            font-family: "Inter", sans-serif;
        }

        .login-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 0;
        }

        .login-stage {
            flex: 1;
            display: grid;
            place-items: center;
            padding: 24px 18px;
        }

        .login-card {
            width: min(1120px, 100%);
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            border-radius: 0;
            overflow: visible;
            border: 0;
            background: transparent;
            box-shadow: none;
            backdrop-filter: none;
        }

        .hero-panel {
            padding: 40px;
            background:
                url('https://images.unsplash.com/photo-1581056771107-24ca5f033842?auto=format&fit=crop&w=1200&q=80') center/cover;
            position: relative;
            min-height: 620px;
        }

        .hero-panel::after {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, 0.30);
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
            border-radius: 0;
            background: transparent;
            border: 0;
            border-bottom: 1px solid var(--border);
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
            background: #4aaef0;
        }

        .locale-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.8);
            color: var(--text);
            font-size: 0.82rem;
            font-weight: 600;
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none;
        }

        .locale-btn:hover {
            background: rgba(143, 211, 255, 0.18);
            border-color: rgba(143, 211, 255, 0.42);
        }

        .locale-btn.active {
            background: rgba(37, 99, 235, 0.12);
            border-color: rgba(37, 99, 235, 0.25);
        }

        .locale-switcher {
            display: flex;
            gap: 4px;
            position: absolute;
            top: 24px;
            right: 24px;
            z-index: 2;
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
            border-radius: 0;
            background: transparent;
            border: 0;
            border-top: 1px solid var(--border);
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
        @include('partials.site-header')
        <div class="login-stage">
            <div class="login-card">
                <section class="hero-panel">
                    <div class="hero-content">
                        <div>
                            <div class="brand">
                                <img src="{{ asset('images/NIT_logoBg.png') }}" alt="{{ __('app.name') }}" class="brand-logo">
                                <span>{{ __('app.name') }}</span>
                            </div>
                            <h1 class="hero-title">{{ __('app.subtitle') }}</h1>
                            <p class="hero-text">{{ __('hero.description') }}</p>
                        </div>

                        <div class="hero-stats">
                            <div class="hero-stat">
                                <strong>{{ __('hero.stat1_title') }}</strong>
                                <span>{{ __('hero.stat1_sub') }}</span>
                            </div>
                            <div class="hero-stat">
                                <strong>{{ __('hero.stat2_title') }}</strong>
                                <span>{{ __('hero.stat2_sub') }}</span>
                            </div>
                            <div class="hero-stat">
                                <strong>{{ __('hero.stat3_title') }}</strong>
                                <span>{{ __('hero.stat3_sub') }}</span>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="form-panel" x-data="{ passwordVisible: false }">
                    <div class="locale-switcher">
                        <a href="{{ route('locale.switch', 'en') }}" class="locale-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                            <span>🇬🇧</span> EN
                        </a>
                        <a href="{{ route('locale.switch', 'sw') }}" class="locale-btn {{ app()->getLocale() === 'sw' ? 'active' : '' }}">
                            <span>🇹🇿</span> SW
                        </a>
                    </div>
                    <h2>{{ __('auth.login') }}</h2>
                    <p class="mb-4">{{ __('auth.login_title') }}</p>

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

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.attempt') }}" autocomplete="off" class="{{ !empty($isLocked) ? 'form-locked' : '' }}">
                        @csrf
                        <div class="mb-3">
                            <label for="login" class="form-label">{{ __('auth.email') }}</label>
                            <input type="text" id="login" name="login" class="form-control" required autofocus autocomplete="off" autocapitalize="none" spellcheck="false" @disabled(!empty($isLocked))>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('auth.password') }}</label>
                            <div class="password-field">
                                <input type="password" id="password" name="password" class="form-control" required autocomplete="new-password" @disabled(!empty($isLocked))>
                                <button type="button" class="password-toggle" @@click="passwordVisible = !passwordVisible" aria-label="Show password" @disabled(!empty($isLocked))>
                                    <i :class="passwordVisible ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-login w-100" @disabled(!empty($isLocked))>
                            {{ !empty($isLocked) ? __('common.loading') : __('auth.login') }}
                        </button>
                    </form>

                    <div class="mt-3 text-end">
                        <a href="{{ route('password.request') }}" class="text-decoration-none">{{ __('auth.forgot_password') }}</a>
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('home') }}" class="btn-alt">
                            <i class="fa-solid fa-arrow-left"></i>
                            {{ __('common.back') }}
                        </a>
                    </div>

                    <div class="help-box">
                        <h6><i class="fa-solid fa-circle-info me-2"></i>{{ __('auth.login_title') }}</h6>
                        <p>{{ __('auth.logged_in_as') }}</p>
                        <p>{{ __('user.subtitle') }}</p>
                    </div>
                </section>
            </div>
        </div>

        @include('partials.footer', [
            'footerClass' => 'standalone-footer',
        ])
    </div>

    @include('partials.site-header-script')
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
