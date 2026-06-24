<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f7fbff;
            font-family: Inter, Arial, sans-serif;
            color: #0f172a;
        }

        .wrap {
            max-width: 640px;
            margin: 0 auto;
            padding: 32px 18px;
        }

        .card {
            background: #ffffff;
            border: 1px solid #dbeafe;
            border-radius: 22px;
            padding: 32px;
            box-shadow: 0 18px 48px rgba(15, 23, 42, 0.08);
        }

        .eyebrow {
            color: #2563eb;
            font-weight: 700;
            font-size: 0.9rem;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        h1 {
            margin: 0 0 14px;
            font-size: 1.8rem;
        }

        p {
            margin: 0 0 14px;
            line-height: 1.7;
            color: #334155;
        }

        .code {
            display: inline-block;
            margin: 18px 0;
            padding: 16px 22px;
            border-radius: 16px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #0f172a;
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: 0.28em;
        }

        .note {
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <div class="eyebrow">Password Reset</div>
            <h1>Your OTP code</h1>
            <p>Hello {{ $user->name }},</p>
            <p>Use the one-time code below to reset your NIT Medical Inventory password.</p>
            <div class="code">{{ $otp }}</div>
            <p>This code expires in 10 minutes.</p>
            <div class="note">
                If you did not request this change, you can safely ignore this email.
            </div>
        </div>
    </div>
</body>
</html>
