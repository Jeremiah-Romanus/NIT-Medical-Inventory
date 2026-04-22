<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NIT Medical Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --bg: #07111f;
            --panel: rgba(10, 18, 33, 0.88);
            --border: rgba(255, 255, 255, 0.10);
            --text: #eaf1ff;
            --muted: #9db2d4;
            --brand: #60a5fa;
            --brand-2: #6ee7b7;
        }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(96, 165, 250, 0.22), transparent 30%),
                radial-gradient(circle at bottom right, rgba(110, 231, 183, 0.16), transparent 30%),
                linear-gradient(160deg, #050816 0%, #0b1730 55%, #09111e 100%);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-shell {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .login-card {
            width: min(1120px, 100%);
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            border-radius: 28px;
            overflow: hidden;
            border: 1px solid var(--border);
            background: rgba(10, 18, 33, 0.76);
            box-shadow: 0 26px 80px rgba(0, 0, 0, 0.42);
            backdrop-filter: blur(20px);
        }

        .hero-panel {
            padding: 40px;
            background:
                linear-gradient(180deg, rgba(7, 17, 31, 0.40), rgba(7, 17, 31, 0.78)),
                url('https://images.unsplash.com/photo-1581056771107-24ca5f033842?auto=format&fit=crop&w=1200&q=80') center/cover;
            position: relative;
            min-height: 620px;
        }

        .hero-panel::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 35%, rgba(7, 17, 31, 0.88) 100%);
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
            background: rgba(96, 165, 250, 0.12);
            border: 1px solid rgba(96, 165, 250, 0.14);
            width: fit-content;
        }

        .brand i {
            color: var(--brand-2);
        }

        .hero-title {
            font-size: clamp(2.4rem, 5vw, 4.8rem);
            line-height: 0.95;
            font-weight: 900;
            margin: 18px 0;
        }

        .hero-text {
            max-width: 56ch;
            color: #bfd0ea;
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
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .hero-stat strong {
            display: block;
            font-size: 1.4rem;
        }

        .hero-stat span {
            color: #b8c8e0;
            font-size: 0.85rem;
        }

        .form-panel {
            padding: 36px;
            background: rgba(12, 20, 36, 0.95);
        }

        .form-panel h2 {
            font-weight: 800;
            margin-bottom: 10px;
        }

        .form-panel p {
            color: var(--muted);
        }

        .form-control {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: var(--text);
            border-radius: 14px;
            padding: 0.9rem 1rem;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.06);
            color: var(--text);
            border-color: rgba(96, 165, 250, 0.5);
            box-shadow: 0 0 0 0.2rem rgba(96, 165, 250, 0.16);
        }

        .btn-login {
            border: 0;
            border-radius: 14px;
            padding: 0.95rem 1rem;
            font-weight: 800;
            color: #07111f;
            background: linear-gradient(135deg, var(--brand), var(--brand-2));
        }

        .demo-box {
            margin-top: 24px;
            padding: 18px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .demo-box h6 {
            margin-bottom: 12px;
            font-weight: 800;
        }

        .demo-box p {
            margin: 6px 0;
            color: #d7e4f6;
        }

        .alert {
            border-radius: 16px;
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
        <div class="login-card">
            <section class="hero-panel">
                <div class="hero-content">
                    <div>
                        <div class="brand">
                            <i class="fa-solid fa-hospital"></i>
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
                <h2>Sign in</h2>
                <p class="mb-4">Use your system account to continue.</p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-login w-100">Login</button>
                </form>

                <div class="demo-box">
                    <h6><i class="fa-solid fa-circle-info me-2"></i>Demo credentials</h6>
                    <p><strong>Pharmacist:</strong> pharmacist@nit.com</p>
                    <p><strong>Procurement:</strong> procurement@nit.com</p>
                    <p><strong>Password:</strong> password123</p>
                </div>
            </section>
        </div>
    </div>
</body>
</html>
