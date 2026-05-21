<html>
<head>
    <style>
        body { font-family: Inter, sans-serif; color: #0f172a; }
        .container { max-width: 600px; margin: 0 auto; padding: 24px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; }
        .code { display: inline-flex; letter-spacing: 0.2em; background: #f8fafc; color: #0f172a; padding: 14px 20px; border-radius: 14px; font-size: 1.5rem; font-weight: 700; }
        .footer { color: #64748b; font-size: 0.95rem; margin-top: 24px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Password reset OTP</h1>
        <p>Hello {{ $user->name }},</p>
        <p>Use the code below to reset your password for your NIT Medical Inventory account.</p>
        <div class="code">{{ $otp }}</div>
        <p>This OTP is valid for 10 minutes.</p>
        <p class="footer">If you did not request a password reset, ignore this email.</p>
    </div>
</body>
</html>
