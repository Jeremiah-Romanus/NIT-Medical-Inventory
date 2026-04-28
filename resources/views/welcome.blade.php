<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NIT Medical Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @include('partials.footer-styles')
    <style>
        :root {
            --bg: #f4f7fb;
            --panel: rgba(255, 255, 255, 0.92);
            --panel-border: rgba(15, 23, 42, 0.08);
            --text: #16233a;
            --muted: #64748b;
            --accent: #0ea5a8;
            --accent-2: #2563eb;
            --accent-3: #d97706;
        }

        body {
            min-height: 100vh;
            margin: 0;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, 0.08), transparent 35%),
                radial-gradient(circle at bottom right, rgba(14, 165, 168, 0.08), transparent 30%),
                linear-gradient(160deg, #f8fbff 0%, #eef4fb 55%, #f7fafc 100%);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 32px 16px;
        }

        .hero {
            max-width: 1180px;
            margin: 0 auto;
            width: 100%;
        }

        .card-glass {
            background: var(--panel);
            backdrop-filter: blur(18px);
            border: 1px solid var(--panel-border);
            border-radius: 28px;
            box-shadow: 0 18px 48px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(37, 99, 235, 0.08);
            color: #1d4ed8;
            font-size: 0.9rem;
            letter-spacing: 0.02em;
        }

        h1 {
            font-size: clamp(2.4rem, 5vw, 4.8rem);
            line-height: 0.98;
            font-weight: 800;
            margin: 18px 0 18px;
        }

        .lead {
            color: var(--muted);
            font-size: 1.05rem;
            line-height: 1.8;
            max-width: 58ch;
        }

        .cta-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 28px;
        }

        .btn-hero {
            border: 0;
            border-radius: 14px;
            padding: 13px 18px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-hero.primary {
            background: linear-gradient(135deg, var(--accent-2), var(--accent));
            color: white;
        }

        .btn-hero.secondary {
            background: white;
            color: var(--text);
            border: 1px solid var(--panel-border);
        }

        .access-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-top: 24px;
        }

        .access-card {
            padding: 18px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid var(--panel-border);
        }

        .access-card h6 {
            margin: 12px 0 8px;
            font-weight: 700;
        }

        .access-card p {
            margin: 0;
            color: var(--muted);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .feature {
            padding: 18px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid var(--panel-border);
            height: 100%;
        }

        .feature i {
            color: var(--accent);
            font-size: 1.2rem;
        }

        .feature h6 {
            margin: 14px 0 8px;
            font-weight: 700;
        }

        .feature p {
            margin: 0;
            color: var(--muted);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-top: 26px;
        }

        .stat {
            padding: 16px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid var(--panel-border);
        }

        .stat strong {
            display: block;
            font-size: 1.5rem;
            line-height: 1.1;
        }

        .stat span {
            color: var(--muted);
            font-size: 0.9rem;
        }

        .tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 12px;
            background: rgba(217, 119, 6, 0.10);
            color: #92400e;
            font-size: 0.9rem;
            margin-top: 20px;
        }

        .visual {
            position: relative;
            min-height: 520px;
            display: flex;
            align-items: stretch;
        }

        .visual-panel {
            width: 100%;
            border-radius: 28px;
            background:
                linear-gradient(180deg, rgba(37, 99, 235, 0.12), rgba(255, 255, 255, 0.42)),
                url('https://images.unsplash.com/photo-1580281657527-47f249e8f1b0?auto=format&fit=crop&w=1200&q=80') center/cover;
            border: 1px solid var(--panel-border);
            position: relative;
            overflow: hidden;
        }

        .visual-panel::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 30%, rgba(15, 23, 42, 0.45) 100%);
        }

        .floating-card {
            position: absolute;
            left: 24px;
            right: 24px;
            bottom: 24px;
            z-index: 1;
            padding: 18px 18px 16px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid var(--panel-border);
            backdrop-filter: blur(16px);
        }

        .floating-card .label {
            color: var(--accent-3);
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .floating-card h5 {
            margin: 10px 0 8px;
            font-weight: 800;
        }

        .floating-card p {
            margin: 0;
            color: var(--muted);
            line-height: 1.6;
        }

        @media (max-width: 991.98px) {
            .visual {
                min-height: 340px;
                margin-top: 28px;
            }

            .access-grid,
            .stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="shell">
        <main class="hero">
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-7">
                    <div class="card-glass p-4 p-md-5 h-100">
                        <div class="eyebrow">
                            <i class="fa-solid fa-shield-heart"></i>
                            Medical inventory for faster, safer dispensing
                        </div>

                        <h1>NIT Medical Inventory System</h1>
                        <p class="lead">
                            Track stock, manage expiry dates, approve requests, and record distributions from one clean dashboard.
                            The system is built for pharmacists and procurement officers so the workflow stays simple and auditable.
                        </p>

                        <div class="cta-row">
                            <a href="{{ route('register') }}" class="btn-hero primary">
                                <i class="fa-solid fa-user-plus"></i>
                                Register Account
                            </a>
                            <a href="{{ route('login') }}" class="btn-hero secondary">
                                <i class="fa-solid fa-right-to-bracket"></i>
                                Already Registered? Login
                            </a>
                            <a href="#features" class="btn-hero secondary">
                                <i class="fa-solid fa-layer-group"></i>
                                Explore Features
                            </a>
                        </div>

                        <div class="tag">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            Expiry monitoring, stock visibility, and role-based access
                        </div>

                        <div class="stats">
                            <div class="stat">
                                <strong>2 Roles</strong>
                                <span>Pharmacist and procurement access control</span>
                            </div>
                            <div class="stat">
                                <strong>1 System</strong>
                                <span>Centralized medicine inventory workflow</span>
                            </div>
                            <div class="stat">
                                <strong>24/7</strong>
                                <span>Visibility for stock and expiry tracking</span>
                            </div>
                        </div>

                        <div class="access-grid">
                            <div class="access-card">
                                <i class="fa-solid fa-user-plus text-primary"></i>
                                <h6>1. Register once</h6>
                                <p>Create one account with your name, email, password, and role.</p>
                            </div>
                            <div class="access-card">
                                <i class="fa-solid fa-user-nurse text-primary"></i>
                                <h6>2. Choose your login</h6>
                                <p>After registration, sign in through pharmacist or procurement login.</p>
                            </div>
                            <div class="access-card">
                                <i class="fa-solid fa-id-badge text-primary"></i>
                                <h6>3. Enter the system</h6>
                                <p>Your account name will now be the one that appears inside the dashboard.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="visual h-100">
                        <div class="visual-panel">
                            <div class="floating-card">
                                <div class="label">Ready for operations</div>
                                <h5>Built to reduce stock surprises</h5>
                                <p>
                                    See low stock, expired items, and medicine movements before they affect patient care.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <section id="features" class="mt-4 mt-md-5">
                <div class="row g-3 g-md-4">
                    <div class="col-md-4">
                        <div class="feature">
                            <i class="fa-solid fa-boxes-stacked"></i>
                            <h6>Inventory Control</h6>
                            <p>Manage medicine stock, batch numbers, unit price, and expiry dates in one place.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature">
                            <i class="fa-solid fa-clipboard-check"></i>
                            <h6>Request Workflow</h6>
                            <p>Pharmacists can request medicines while procurement reviews, approves, and distributes.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature">
                            <i class="fa-solid fa-chart-line"></i>
                            <h6>Expiry Alerts</h6>
                            <p>Highlight expired or expiring medicines early so teams can act before losses happen.</p>
                        </div>
                    </div>
                </div>
            </section>

            @include('partials.footer', [
                'footerClass' => 'hero-footer',
            ])
        </main>
    </div>
</body>
</html>
