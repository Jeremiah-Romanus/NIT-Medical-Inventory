<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NIT Medical Inventory</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @include('partials.footer-styles')
    @include('partials.site-header-styles')
    <style>
        :root {
            --bg: #f4fafe;
            --panel: rgba(255, 255, 255, 0.94);
            --panel-border: rgba(143, 211, 255, 0.35);
            --text: #0f172a;
            --muted: #64748b;
            --accent: #8fd3ff;
            --accent-2: #4aaef0;
        }

        body {
            min-height: 100vh;
            margin: 0;
            color: var(--text);
            background: #f7fbff;
            font-family: "Inter", sans-serif;
        }

        .page-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .shell {
            flex: 1;
            display: flex;
            align-items: center;
            padding: 16px 18px 12px;
        }

        .hero {
            width: 100%;
            max-width: 1180px;
            margin: 0 auto;
        }

        .hero-layout {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            gap: 28px;
        }

        .hero-copy {
            flex: 1 1 52%;
            width: 100%;
            padding: 6px 0 0;
            text-align: left;
        }

        .hero-copy h1 {
            font-size: clamp(1.65rem, 3vw, 3.15rem);
            line-height: 1.06;
            font-weight: 800;
            margin: 0 0 14px;
            max-width: none;
        }

        .hero-copy h1 .accent {
            color: #1692de;
            white-space: nowrap;
        }

        .hero-copy p {
            max-width: 54ch;
            font-size: 0.97rem;
            line-height: 1.78;
            color: var(--muted);
            margin: 0;
        }

        .cta-row {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 28px;
        }

        .btn-hero {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 20px;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 700;
            border: 1px solid transparent;
            font-size: 0.95rem;
        }

        .btn-hero.primary {
            background: var(--accent-2);
            color: #ffffff;
            box-shadow: none;
        }

        .btn-hero.secondary {
            background: rgba(255, 255, 255, 0.92);
            border-color: var(--panel-border);
            color: var(--text);
        }

        .visual-wrap {
            position: relative;
            min-height: 320px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1 1 48%;
            width: 100%;
        }

        .visual-circle {
            width: min(100%, 430px);
            aspect-ratio: 1 / 1;
            border-radius: 50%;
            background: #eff8ff;
            border: 4px solid rgba(74, 174, 240, 0.65);
            box-shadow: 0 28px 50px rgba(74, 174, 240, 0.16);
            display: grid;
            place-items: center;
            overflow: hidden;
            position: relative;
        }

        .visual-circle img {
            width: 76%;
            height: 76%;
            margin: auto;
            object-fit: contain;
        }

        .welcome-footer {
            width: 100%;
            margin-top: auto;
        }

        .welcome-footer .shared-footer {
            width: 100%;
            border-radius: 0;
            margin: 0;
            padding-left: max(18px, calc((100vw - 1180px) / 2 + 18px));
            padding-right: max(18px, calc((100vw - 1180px) / 2 + 18px));
        }

        @media (min-width: 768px) {
            .shell {
                padding-top: 14px;
            }

            .hero-layout {
                flex-direction: row;
                align-items: center;
                gap: 44px;
            }

            .hero-copy h1 {
                max-width: none;
            }

            .visual-wrap {
                min-height: 360px;
                margin-top: 0;
            }
        }

        @media (max-width: 767.98px) {
            .shell {
                padding: 12px 18px 8px;
            }

            .visual-wrap {
                min-height: 280px;
            }

            .visual-circle {
                width: min(100%, 320px);
            }
        }
    </style>
</head>
<body>
    <div class="page-shell">
        @include('partials.site-header')

        <div class="shell">
        <main class="hero">
            <div class="hero-layout">
                <div class="hero-copy">
                        <h1><span class="accent">Medical Inventory</span><br>System</h1>
                        <p>
                            A simple and secure workspace for tracking medicine stock, managing expiry dates,
                            and connecting pharmacy with procurement through one clear workflow.
                        </p>

                    <div class="cta-row">
                        <a href="{{ route('login') }}" class="btn-hero primary">
                            <i class="fa-solid fa-right-to-bracket"></i>
                            Login Now
                        </a>
                    </div>
                </div>

                <div class="visual-wrap">
                    <div class="visual-circle">
                        <img src="{{ asset('images/NIT_logoBg.png') }}" alt="NIT Logo">
                    </div>
                </div>
            </div>
        </main>
        </div>

        <div class="welcome-footer">
            @include('partials.footer', ['footerClass' => 'standalone-footer page-footer'])
        </div>
    </div>
    @include('partials.site-header-script')
</body>
</html>
