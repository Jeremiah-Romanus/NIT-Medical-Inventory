<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - NIT Medical Inventory</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @include('partials.footer-styles')
    @include('partials.site-header-styles')
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            background: #f7fbff;
            font-family: "Inter", sans-serif;
            color: #0f172a;
        }

        .page-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .form-stage {
            flex: 1;
            display: grid;
            place-items: center;
            padding: 28px 18px;
        }

        .form-panel {
            width: min(560px, 100%);
            background: #ffffff;
            border: 1px solid rgba(143, 211, 255, 0.35);
            border-radius: 24px;
            padding: 36px;
            box-shadow: 0 18px 56px rgba(15, 23, 42, 0.08);
        }

        .form-panel h2 {
            margin-bottom: 12px;
            font-weight: 800;
        }

        .form-panel p {
            color: #64748b;
        }

        .form-control {
            border-radius: 14px;
            border: 1px solid rgba(143, 211, 255, 0.45);
            padding: 0.95rem 1rem;
        }

        .btn-primary {
            border-radius: 14px;
            background: #4aaef0;
            color: #ffffff;
            border: 0;
            font-weight: 700;
        }

        .help-link {
            display: inline-flex;
            gap: 10px;
            align-items: center;
            margin-top: 16px;
            color: #0f172a;
            text-decoration: none;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="page-shell">
        @include('partials.site-header')

        <div class="form-stage">
            <div class="form-panel">
                <h2>Reset password</h2>
                <p>Enter the OTP sent to your registered email, then choose a new password.</p>

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update', $role) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Registered email address</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $email ?? '') }}" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="otp" class="form-label">OTP code</label>
                        <input type="text" name="otp" id="otp" class="form-control" value="{{ old('otp') }}" required maxlength="6">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">New password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Update password</button>
                </form>

                <a href="{{ route('login.role', $role) }}" class="help-link">
                    <i class="fa-solid fa-arrow-left"></i>Return to login
                </a>
            </div>
        </div>

        @include('partials.footer', ['footerClass' => 'standalone-footer'])
    </div>

    @include('partials.site-header-script')
    @include('partials.sweetalert')
</body>
</html>
