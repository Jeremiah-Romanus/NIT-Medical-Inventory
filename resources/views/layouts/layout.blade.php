<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - NIT Medical Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --bg: #07111f;
            --panel: rgba(10, 18, 33, 0.88);
            --panel-2: rgba(15, 26, 45, 0.96);
            --border: rgba(255, 255, 255, 0.10);
            --text: #eaf1ff;
            --muted: #9db2d4;
            --brand: #60a5fa;
            --brand-2: #6ee7b7;
            --warn: #fbbf24;
            --danger: #fb7185;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(96, 165, 250, 0.18), transparent 30%),
                radial-gradient(circle at bottom right, rgba(110, 231, 183, 0.12), transparent 30%),
                linear-gradient(160deg, #050816 0%, #09111e 100%);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        a {
            text-decoration: none;
        }

        .app-shell {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            position: fixed;
            inset: 0 auto 0 0;
            padding: 22px 18px;
            background: linear-gradient(180deg, rgba(8, 15, 28, 0.98), rgba(12, 21, 39, 0.98));
            border-right: 1px solid var(--border);
            overflow-y: auto;
            z-index: 20;
        }

        .brand {
            padding: 18px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border);
            margin-bottom: 18px;
        }

        .brand h1 {
            font-size: 1.1rem;
            margin: 0;
            font-weight: 800;
            letter-spacing: 0.02em;
        }

        .brand p {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 0.9rem;
        }

        .brand .chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 12px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(96, 165, 250, 0.12);
            color: #cce2ff;
            font-size: 0.82rem;
        }

        .nav-group {
            margin-top: 18px;
        }

        .nav-label {
            color: var(--muted);
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            padding: 0 12px;
            margin-bottom: 10px;
        }

        .side-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 14px;
            border-radius: 16px;
            color: rgba(234, 241, 255, 0.88);
            transition: all 0.2s ease;
            margin-bottom: 8px;
            border: 1px solid transparent;
        }

        .side-link i {
            width: 18px;
            text-align: center;
            color: var(--brand);
        }

        .side-link:hover,
        .side-link.active {
            background: rgba(96, 165, 250, 0.12);
            border-color: rgba(96, 165, 250, 0.2);
            color: white;
        }

        .side-footer {
            margin-top: 18px;
            padding: 16px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border);
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, var(--brand), var(--brand-2));
            color: #04101f;
            font-weight: 900;
        }

        .profile h6 {
            margin: 0;
            font-weight: 700;
            font-size: 0.95rem;
        }

        .profile p {
            margin: 3px 0 0;
            color: var(--muted);
            font-size: 0.82rem;
        }

        .logout-btn {
            width: 100%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            padding: 11px 14px;
            border-radius: 14px;
            border: 1px solid rgba(251, 113, 133, 0.26);
            background: rgba(251, 113, 133, 0.09);
            color: #ffd7df;
            font-weight: 700;
        }

        .content-shell {
            margin-left: 280px;
            flex: 1;
            min-width: 0;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            padding: 22px 28px;
            background: rgba(7, 17, 31, 0.75);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .page-head h2 {
            margin: 0;
            font-size: 1.45rem;
            font-weight: 800;
        }

        .page-head p {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 0.92rem;
        }

        .status-pill {
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(96, 165, 250, 0.12);
            color: #d8ebff;
            border: 1px solid rgba(96, 165, 250, 0.14);
            font-weight: 700;
            font-size: 0.88rem;
        }

        .content-area {
            padding: 28px;
        }

        .card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 24px;
            box-shadow: 0 18px 60px rgba(0, 0, 0, 0.28);
            color: var(--text);
        }

        .card-header {
            background: rgba(255, 255, 255, 0.03);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            padding: 18px 20px;
            border-top-left-radius: 24px !important;
            border-top-right-radius: 24px !important;
        }

        .card-title {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 800;
        }

        .card-body {
            color: var(--text);
        }

        .table {
            color: var(--text);
        }

        .table thead th {
            background: rgba(255, 255, 255, 0.03);
            border-bottom-color: rgba(255, 255, 255, 0.08);
            color: #c8d8f0;
            font-weight: 700;
        }

        .table td,
        .table th {
            border-color: rgba(255, 255, 255, 0.08);
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: rgba(96, 165, 250, 0.06);
        }

        .alert {
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .alert-success {
            background: rgba(110, 231, 183, 0.10);
            color: #d8fff0;
        }

        .alert-danger {
            background: rgba(251, 113, 133, 0.12);
            color: #ffdce3;
        }

        .alert-warning {
            background: rgba(251, 191, 36, 0.12);
            color: #fff0c7;
        }

        .alert-info {
            background: rgba(96, 165, 250, 0.12);
            color: #d8ebff;
        }

        .badge {
            border-radius: 999px;
            padding: 0.45rem 0.75rem;
        }

        .btn {
            border-radius: 14px;
            font-weight: 700;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--brand), var(--brand-2));
            border: 0;
            color: #07111f;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.10);
        }

        .btn-warning {
            background: linear-gradient(135deg, #fbbf24, #fb923c);
            color: #1b1200;
            border: 0;
        }

        .btn-info {
            background: linear-gradient(135deg, #38bdf8, #60a5fa);
            color: #08111f;
            border: 0;
        }

        .btn-danger {
            background: linear-gradient(135deg, #fb7185, #ef4444);
            border: 0;
        }

        .form-control,
        .form-select {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: var(--text);
            border-radius: 14px;
        }

        .form-control:focus,
        .form-select:focus {
            background: rgba(255, 255, 255, 0.06);
            color: var(--text);
            border-color: rgba(96, 165, 250, 0.5);
            box-shadow: 0 0 0 0.18rem rgba(96, 165, 250, 0.14);
        }

        .modal-content {
            background: #0d1727;
            color: var(--text);
            border: 1px solid var(--border);
            border-radius: 24px;
        }

        .modal-header,
        .modal-footer {
            border-color: rgba(255, 255, 255, 0.08);
        }

        @media (max-width: 991.98px) {
            .sidebar {
                width: 88px;
                padding: 16px 10px;
            }

            .content-shell {
                margin-left: 88px;
            }

            .brand h1,
            .brand p,
            .brand .chip,
            .side-link span,
            .nav-label,
            .profile,
            .logout-btn span {
                display: none;
            }

            .side-link {
                justify-content: center;
                padding: 14px 10px;
            }

            .side-footer {
                padding: 12px;
            }
        }

        @media (max-width: 767.98px) {
            .sidebar {
                display: none;
            }

            .content-shell {
                margin-left: 0;
            }

            .topbar {
                padding: 16px 18px;
            }

            .content-area {
                padding: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="brand">
                <h1><i class="fa-solid fa-hospital"></i> NIT Medical</h1>
                <p>Inventory control made simple.</p>
                <div class="chip">
                    <i class="fa-solid fa-shield-heart"></i>
                    Role-based access
                </div>
            </div>

            <div class="nav-group">
                <div class="nav-label">Navigation</div>
                <a href="{{ auth()->user()->role === 'pharmacist' ? route('pharmacist.dashboard') : route('procurement.dashboard') }}" class="side-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>

                @if(auth()->user()->role === 'pharmacist')
                    <a href="{{ route('pharmacist.stock') }}" class="side-link {{ request()->routeIs('pharmacist.stock') ? 'active' : '' }}">
                        <i class="fa-solid fa-boxes-stacked"></i>
                        <span>Inventory</span>
                    </a>
                    <a href="{{ route('pharmacist.request') }}" class="side-link {{ request()->routeIs('pharmacist.request') ? 'active' : '' }}">
                        <i class="fa-solid fa-clipboard-list"></i>
                        <span>Requests</span>
                    </a>
                    <a href="{{ route('pharmacist.expiry') }}" class="side-link {{ request()->routeIs('pharmacist.expiry') ? 'active' : '' }}">
                        <i class="fa-solid fa-calendar-days"></i>
                        <span>Expiry</span>
                    </a>
                @else
                    <a href="{{ route('procurement.stock') }}" class="side-link {{ request()->routeIs('procurement.stock') ? 'active' : '' }}">
                        <i class="fa-solid fa-boxes-stacked"></i>
                        <span>Inventory</span>
                    </a>
                    <a href="{{ route('procurement.requests') }}" class="side-link {{ request()->routeIs('procurement.requests') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Approvals</span>
                    </a>
                    <a href="{{ route('procurement.distribution') }}" class="side-link {{ request()->routeIs('procurement.distribution') ? 'active' : '' }}">
                        <i class="fa-solid fa-truck"></i>
                        <span>Distribution</span>
                    </a>
                    <a href="{{ route('procurement.reports') }}" class="side-link {{ request()->routeIs('procurement.reports') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-column"></i>
                        <span>Reports</span>
                    </a>
                @endif
            </div>

            <div class="side-footer">
                <div class="profile">
                    <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div>
                        <h6>{{ auth()->user()->name }}</h6>
                        <p>{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="content-shell">
            <div class="topbar">
                <div class="page-head">
                    <h2>@yield('page-title', 'Dashboard')</h2>
                    @hasSection('page-subtitle')
                        <p>@yield('page-subtitle')</p>
                    @endif
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="status-pill d-none d-md-inline-flex">
                        <i class="fa-solid fa-circle-nodes me-2"></i>
                        Live inventory workspace
                    </span>
                </div>
            </div>

            <div class="content-area">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
