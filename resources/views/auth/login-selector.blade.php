<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NIT Medical Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @include('partials.footer-styles')
    <style>
        :root {
            --bg: #f4f7fb;
            --panel: rgba(255, 255, 255, 0.94);
            --border: rgba(15, 23, 42, 0.08);
            --text: #16233a;
            --muted: #64748b;
            --brand: #2563eb;
            --brand-2: #0ea5a8;
        }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, 0.08), transparent 30%),
                radial-gradient(circle at bottom right, rgba(14, 165, 168, 0.08), transparent 30%),
                linear-gradient(160deg, #f8fbff 0%, #eef4fb 55%, #f7fafc 100%);
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

        .stage {
            flex: 1;
            display: grid;
            place-items: center;
        }

        .panel {
            width: min(1120px, 100%);
            border-radius: 28px;
            overflow: hidden;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.88);
            box-shadow: 0 18px 48px rgba(15, 23, 42, 0.08);
            backdrop-filter: blur(20px);
        }

        .hero {
            padding: 40px;
            background:
                linear-gradient(180deg, rgba(37, 99, 235, 0.10), rgba(255, 255, 255, 0.12)),
                url('https://images.unsplash.com/photo-1581056771107-24ca5f033842?auto=format&fit=crop&w=1200&q=80') center/cover;
            position: relative;
        }

        .hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 25%, rgba(15, 23, 42, 0.40) 100%);
        }

        .hero-content {
            position: relative;
            z-index: 1;
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

        .brand i {
            color: var(--brand-2);
        }

        .hero h1 {
            font-size: clamp(2.3rem, 5vw, 4.5rem);
            line-height: 0.95;
            font-weight: 900;
            margin: 18px 0 12px;
        }

        .hero p {
            max-width: 58ch;
            color: #334155;
            line-height: 1.8;
            margin: 0;
        }

        .content {
            padding: 32px;
        }

        .choice-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            margin-top: 22px;
        }

        .choice-card {
            padding: 24px;
            border-radius: 20px;
            border: 1px solid var(--border);
            background: #ffffff;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.05);
        }

        .choice-card h3 {
            font-size: 1.2rem;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .choice-card p {
            color: var(--muted);
            margin-bottom: 18px;
        }

        .action-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 24px;
        }

        .btn-main,
        .btn-alt {
            border-radius: 14px;
            padding: 0.9rem 1rem;
            font-weight: 800;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-main {
            border: 0;
            color: white;
            background: linear-gradient(135deg, var(--brand), var(--brand-2));
        }

        .btn-alt {
            color: var(--text);
            background: rgba(37, 99, 235, 0.04);
            border: 1px solid var(--border);
        }

        @media (max-width: 767.98px) {
            .choice-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="login-shell">
        <div class="stage">
            <div class="panel">
                <section class="hero">
                    <div class="hero-content">
                        <div class="brand">
                            <i class="fa-solid fa-hospital"></i>
                            <span>NIT Medical Inventory</span>
                        </div>
                        <h1>Start with your account type.</h1>
                        <p>If you already registered, pick your workspace and sign in. If not, create one account first and then log in.</p>
                    </div>
                </section>

                <section class="content">
                    <h2 class="fw-bold">Choose where to log in</h2>
                    <div class="choice-grid">
                        <div class="choice-card">
                            <h3>Pharmacist Login</h3>
                            <p>Use this if your account was registered as a pharmacist.</p>
                            <a href="{{ route('login.role', 'pharmacist') }}" class="btn-main w-100">
                                <i class="fa-solid fa-user-nurse"></i>
                                Continue as Pharmacist
                            </a>
                        </div>
                        <div class="choice-card">
                            <h3>Procurement Login</h3>
                            <p>Use this if your account was registered as procurement staff.</p>
                            <a href="{{ route('login.role', 'procurement') }}" class="btn-main w-100">
                                <i class="fa-solid fa-briefcase-medical"></i>
                                Continue as Procurement
                            </a>
                        </div>
                    </div>

                    <div class="action-row">
                        <a href="{{ route('register') }}" class="btn-alt">
                            <i class="fa-solid fa-user-plus"></i>
                            Register First
                        </a>
                        <a href="{{ url('/') }}" class="btn-alt">
                            <i class="fa-solid fa-arrow-left"></i>
                            Back Home
                        </a>
                    </div>
                </section>
            </div>
        </div>

        @include('partials.footer', [
            'footerClass' => 'standalone-footer',
        ])
    </div>
</body>
</html>
