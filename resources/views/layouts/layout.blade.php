<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('app.name')) - {{ __('app.name') }}</title>
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
            --panel: #ffffff;
            --panel-2: #f3f9ff;
            --border: #cfe5f5;
            --text: #0f172a;
            --muted: #64748b;
            --brand: #8fd3ff;
            --brand-2: #60bdf5;
            --warn: #d97706;
            --danger: #dc2626;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--text);
            background: #f7fbff;
            font-family: "Inter", sans-serif;
        }

        a {
            text-decoration: none;
        }

        .page-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .app-shell {
            display: flex;
            flex: 1;
            min-height: 0;
            align-items: flex-start;
        }

        .sidebar {
            flex: 0 0 280px;
            width: 280px;
            position: sticky;
            top: 76px;
            height: calc(100vh - 76px);
            padding: 22px 18px;
            background: #ffffff;
            border-right: 1px solid var(--border);
            box-shadow: 0 18px 55px rgba(37, 99, 235, 0.06);
            overflow-y: auto;
            z-index: 20;
        }

        .sidebar-backdrop {
            display: none;
        }

        .brand {
            padding: 12px 0 18px;
            border-radius: 0;
            background: transparent;
            border: 0;
            border-bottom: 1px solid var(--border);
            margin-bottom: 18px;
        }

        .brand-head {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            width: 42px;
            height: 42px;
            object-fit: contain;
            flex: 0 0 auto;
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
            background: rgba(37, 99, 235, 0.10);
            color: #0f172a;
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
            color: var(--text);
            transition:
                transform 0.22s ease-in-out,
                padding-left 0.22s ease-in-out,
                background-color 0.22s ease-in-out,
                border-color 0.22s ease-in-out,
                color 0.22s ease-in-out;
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
            background: rgba(143, 211, 255, 0.18);
            border-color: rgba(143, 211, 255, 0.42);
            color: var(--text);
        }

        .side-link:hover {
            padding-left: 18px;
            transform: translateX(2px);
        }

        .side-footer {
            margin-top: 18px;
            padding: 16px 0 0;
            border-radius: 0;
            background: transparent;
            border: 0;
            border-top: 1px solid var(--border);
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
            background: #60bdf5;
            color: white;
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
            border: 1px solid rgba(220, 38, 38, 0.18);
            background: rgba(220, 38, 38, 0.06);
            color: #b91c1c;
            font-weight: 700;
        }

        .content-shell {
            flex: 1;
            min-width: 0;
            min-height: 0;
            display: flex;
            flex-direction: column;
        }

        .content-area {
            flex: 1;
            min-height: 0;
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
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--border);
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
            background: rgba(37, 99, 235, 0.08);
            color: #0f172a;
            border: 1px solid rgba(37, 99, 235, 0.12);
            font-weight: 700;
            font-size: 0.88rem;
        }

        .content-area {
            padding: 28px;
            flex: 1;
        }

        .alerts-panel {
            margin-bottom: 24px;
            padding: 0 0 20px;
            border-radius: 0;
            background: transparent;
            border: 0;
            border-bottom: 1px solid var(--border);
            box-shadow: none;
        }

        .alerts-panel h5 {
            margin: 0 0 16px;
            font-size: 1rem;
            font-weight: 800;
        }

        .alert-feed {
            display: grid;
            gap: 12px;
        }

        .alert-item {
            padding: 14px 16px;
            border-radius: 18px;
            display: flex;
            gap: 12px;
            align-items: flex-start;
            border: 1px solid transparent;
        }

        .alert-item i {
            margin-top: 2px;
        }

        .alert-item strong {
            display: block;
            margin-bottom: 4px;
        }

        .alert-item p {
            margin: 0;
            color: inherit;
            font-size: 0.92rem;
        }

        .alert-item.info {
            background: rgba(37, 99, 235, 0.08);
            color: #1d4ed8;
            border-color: rgba(37, 99, 235, 0.12);
        }

        .alert-item.warning {
            background: rgba(245, 158, 11, 0.1);
            color: #92400e;
            border-color: rgba(245, 158, 11, 0.14);
        }

        .alert-item.danger {
            background: rgba(220, 38, 38, 0.08);
            color: #991b1b;
            border-color: rgba(220, 38, 38, 0.12);
        }

        .card {
            background: transparent;
            border: 0;
            border-bottom: 1px solid var(--border);
            border-radius: 0;
            box-shadow: none;
            color: var(--text);
            transition:
                transform 0.25s ease-in-out,
                box-shadow 0.25s ease-in-out,
                border-color 0.25s ease-in-out,
                background-color 0.25s ease-in-out;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 0.85rem 1.8rem rgba(15, 23, 42, 0.10);
            background: rgba(255, 255, 255, 0.64);
            border-color: rgba(74, 174, 240, 0.26);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border);
            padding: 18px 20px;
            border-top-left-radius: 0 !important;
            border-top-right-radius: 0 !important;
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
            background: transparent;
            border-bottom-color: var(--border);
            color: #334155;
            font-weight: 700;
        }

        .table td,
        .table th {
            border-color: var(--border);
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: rgba(143, 211, 255, 0.08);
        }

        .alert {
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .alert-success {
            background: rgba(14, 165, 168, 0.09);
            color: #0f766e;
        }

        .alert-danger {
            background: rgba(220, 38, 38, 0.08);
            color: #991b1b;
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.10);
            color: #92400e;
        }

        .alert-info {
            background: rgba(37, 99, 235, 0.08);
            color: #1d4ed8;
        }

        .badge {
            border-radius: 999px;
            padding: 0.45rem 0.75rem;
        }

        .btn {
            border-radius: 14px;
            font-weight: 700;
            transition:
                transform 0.22s ease-in-out,
                background-color 0.22s ease-in-out,
                border-color 0.22s ease-in-out,
                box-shadow 0.22s ease-in-out,
                color 0.22s ease-in-out;
        }

        .btn:hover,
        .logout-btn:hover,
        .locale-btn:hover {
            transform: scale(1.025);
            box-shadow: 0 0.45rem 1rem rgba(13, 110, 253, 0.14);
        }

        .btn:active,
        .logout-btn:active,
        .locale-btn:active {
            transform: scale(0.97);
            box-shadow: 0 0.2rem 0.45rem rgba(13, 110, 253, 0.12);
        }

        .btn-primary {
            background: #4aaef0;
            border: 0;
            color: white;
        }

        .btn-secondary {
            background: #eaf6ff;
            border-color: #cfe5f5;
            color: #0f172a;
        }

        .btn-warning {
            background: #f59e0b;
            color: white;
            border: 0;
        }

        .btn-info {
            background: #0ea5e9;
            color: white;
            border: 0;
        }

        .btn-danger {
            background: #ef4444;
            border: 0;
        }

        .btn-outline-primary {
            border-color: #cfe5f5;
            color: #0f172a;
        }

        .form-control,
        .form-select {
            background: #ffffff;
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 14px;
        }

        .form-control:focus,
        .form-select:focus {
            background: #ffffff;
            color: var(--text);
            border-color: rgba(37, 99, 235, 0.45);
            box-shadow: 0 0 0 0.18rem rgba(37, 99, 235, 0.10);
        }

        .modal-content {
            background: #ffffff;
            color: var(--text);
            border: 1px solid var(--border);
            border-radius: 24px;
        }

        .modal-header,
        .modal-footer {
            border-color: var(--border);
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
            transition:
                transform 0.22s ease-in-out,
                background-color 0.22s ease-in-out,
                border-color 0.22s ease-in-out,
                box-shadow 0.22s ease-in-out,
                color 0.22s ease-in-out;
            cursor: pointer;
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
        }

        .table-filter-input {
            max-width: 280px;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 991.98px) {
            .sidebar {
                width: 280px;
                flex: 0 0 auto;
                position: fixed;
                top: 76px;
                bottom: 104px;
                left: 0;
                height: auto;
                padding: 22px 18px;
                transform: translateX(-100%);
                transition: transform 0.25s ease;
                z-index: 1050;
            }

            .content-shell {
                margin-left: 0;
            }

            body.sidebar-open .sidebar {
                transform: translateX(0);
            }

            .sidebar-backdrop {
                display: block;
                position: fixed;
                inset: 76px 0 104px 0;
                background: rgba(15, 23, 42, 0.26);
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.25s ease;
                z-index: 1040;
            }

            body.sidebar-open .sidebar-backdrop {
                opacity: 1;
                pointer-events: auto;
            }
        }

        @media (max-width: 767.98px) {
            .content-shell {
                margin-left: 0;
            }

            .sidebar {
                bottom: 118px;
            }

            .topbar {
                padding: 16px 18px;
            }

            .content-area {
                padding: 18px;
            }

            .sidebar-backdrop {
                inset: 76px 0 118px 0;
            }

        }
    </style>
</head>
<body x-data="{ sidebarOpen: false }" :class="{ 'sidebar-open': sidebarOpen }">
    <div class="page-shell">
    @include('partials.site-header')
    <div class="app-shell">
        <div class="sidebar-backdrop" @@click="sidebarOpen = false"></div>
        <aside class="sidebar">
                <div class="brand">
                <div class="brand-head">
                    <img src="{{ asset('images/NIT_logoBg.png') }}" alt="NIT Logo" class="brand-logo">
                    <div>
                        <h1>{{ __('app.name') }}</h1>
                        <p>{{ __('app.subtitle') }}</p>
                    </div>
                </div>
                <div class="chip">
                    <i class="fa-solid fa-shield-heart"></i>
                    {{ __('nav.role_badge') }}
                </div>
            </div>

            <div class="nav-group">
                <div class="nav-label">{{ __('nav.dashboard') }}</div>
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->user()->role === 'pharmacist' ? route('pharmacist.dashboard') : route('procurement.dashboard')) }}" class="side-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>{{ __('nav.dashboard') }}</span>
                </a>

                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.users') }}" class="side-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                        <i class="fa-solid fa-users-gear"></i>
                        <span>{{ __('nav.users') }}</span>
                    </a>
                    <a href="{{ route('admin.audit-trail') }}" class="side-link {{ request()->routeIs('admin.audit-trail') ? 'active' : '' }}">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>Audit Trail</span>
                    </a>
                    <a href="{{ route('medicines.index') }}" class="side-link {{ request()->routeIs('medicines.*', 'procurement.stock') ? 'active' : '' }}">
                        <i class="fa-solid fa-boxes-stacked"></i>
                        <span>{{ __('nav.inventory') }}</span>
                    </a>
                    <a href="{{ route('procurement.requests') }}" class="side-link {{ request()->routeIs('procurement.requests') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ __('nav.approvals') }}</span>
                    </a>
                    <a href="{{ route('procurement.distribution') }}" class="side-link {{ request()->routeIs('procurement.distribution') ? 'active' : '' }}">
                        <i class="fa-solid fa-truck"></i>
                        <span>{{ __('nav.distribution') }}</span>
                    </a>
                    <a href="{{ route('procurement.reports') }}" class="side-link {{ request()->routeIs('procurement.reports') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-column"></i>
                        <span>{{ __('nav.reports') }}</span>
                    </a>
                @elseif(auth()->user()->role === 'pharmacist')
                    <a href="{{ route('pharmacist.stock') }}" class="side-link {{ request()->routeIs('pharmacist.stock') ? 'active' : '' }}">
                        <i class="fa-solid fa-boxes-stacked"></i>
                        <span>{{ __('nav.inventory') }}</span>
                    </a>
                    <a href="{{ route('pharmacist.request') }}" class="side-link {{ request()->routeIs('pharmacist.request') ? 'active' : '' }}">
                        <i class="fa-solid fa-clipboard-list"></i>
                        <span>{{ __('nav.requests') }}</span>
                    </a>
                    <a href="{{ route('pharmacist.expiry') }}" class="side-link {{ request()->routeIs('pharmacist.expiry') ? 'active' : '' }}">
                        <i class="fa-solid fa-calendar-days"></i>
                        <span>{{ __('nav.expiry') }}</span>
                    </a>
                @else
                    <a href="{{ route('procurement.stock') }}" class="side-link {{ request()->routeIs('procurement.stock') ? 'active' : '' }}">
                        <i class="fa-solid fa-boxes-stacked"></i>
                        <span>{{ __('nav.inventory') }}</span>
                    </a>
                    <a href="{{ route('procurement.requests') }}" class="side-link {{ request()->routeIs('procurement.requests') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ __('nav.approvals') }}</span>
                    </a>
                    <a href="{{ route('procurement.distribution') }}" class="side-link {{ request()->routeIs('procurement.distribution') ? 'active' : '' }}">
                        <i class="fa-solid fa-truck"></i>
                        <span>{{ __('nav.distribution') }}</span>
                    </a>
                    <a href="{{ route('procurement.reports') }}" class="side-link {{ request()->routeIs('procurement.reports') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-column"></i>
                        <span>{{ __('nav.reports') }}</span>
                    </a>
                @endif
            </div>

            <div class="side-footer">
                <div class="profile">
                    <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div>
                        <h6>{{ auth()->user()->name }}</h6>
                        <p>
                            @switch(auth()->user()->role)
                                @case('admin')
                                    {{ __('role.admin') }}
                                    @break
                                @case('procurement')
                                    {{ __('role.procurement') }}
                                    @break
                                @default
                                    {{ __('role.pharmacist') }}
                            @endswitch
                        </p>
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}" class="side-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-pen"></i>
                    <span>{{ __('nav.profile') }}</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>{{ __('nav.logout') }}</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="content-shell">
            <div class="topbar">
                <div class="page-head">
                    <button class="btn btn-outline-primary d-lg-none me-2" @@click="sidebarOpen = !sidebarOpen">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <h2>@yield('page-title', __('nav.dashboard'))</h2>
                    @hasSection('page-subtitle')
                        <p>@yield('page-subtitle')</p>
                    @endif
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="locale-switcher">
                        <a href="{{ route('locale.switch', 'en') }}" class="locale-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                            <span>🇬🇧</span> EN
                        </a>
                        <a href="{{ route('locale.switch', 'sw') }}" class="locale-btn {{ app()->getLocale() === 'sw' ? 'active' : '' }}">
                            <span>🇹🇿</span> SW
                        </a>
                    </div>
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

                @if(request()->routeIs('*.dashboard') && isset($sharedAlerts) && $sharedAlerts->isNotEmpty())
                    <div class="alerts-panel">
                        <h5><i class="fa-solid fa-bell me-2"></i>{{ __('dashboard.system_alerts') }}</h5>
                        <div class="alert-feed">
                            @foreach($sharedAlerts as $alert)
                                <div class="alert-item {{ $alert['type'] }}">
                                    <i class="{{ $alert['icon'] }}"></i>
                                    <div>
                                        <strong>{{ $alert['title'] }}</strong>
                                        <p>{{ $alert['message'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>

        </main>
    </div>
    <div class="dashboard-footer-shell">
        @include('partials.footer', [
            'footerClass' => 'page-footer dashboard-footer',
            'footerAlignClass' => 'text-md-end',
        ])
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @include('partials.site-header-script')
    @include('partials.sweetalert')
    @yield('scripts')
</body>
</html>
