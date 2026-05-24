<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;
use Throwable;
use App\Models\User;

class AuthController extends Controller
{
    protected array $roles = ['admin', 'pharmacist', 'procurement'];
    protected int $maxLoginAttempts = 5;
    protected int $lockoutSeconds = 300;

    /**
     * Show the login selector page
     */
    public function showLogin(Request $request)
    {
        $throttleKey = $this->throttleKey($request);
        $isLocked = RateLimiter::tooManyAttempts($throttleKey, $this->maxLoginAttempts);
        $lockoutSecondsRemaining = $isLocked ? RateLimiter::availableIn($throttleKey) : 0;

        return view('auth.login', compact('isLocked', 'lockoutSecondsRemaining'));
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendPasswordOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if ($user) {
            if (! $this->mailIsConfigured()) {
                throw ValidationException::withMessages([
                    'email' => 'Mailtrap SMTP settings are not complete yet. Add your Mailtrap host, username, and password in the .env file, then try again.',
                ]);
            }

            $otp = random_int(100000, 999999);
            $cacheKey = 'password-otp:' . strtolower($user->email);
            Cache::put($cacheKey, $otp, now()->addMinutes(10));

            try {
                Mail::send('emails.password-otp', ['user' => $user, 'otp' => $otp], function ($message) use ($user) {
                    $message->to($user->email, $user->name)
                        ->subject('Your NIT Medical Inventory password reset OTP');
                });
            } catch (Throwable $exception) {
                report($exception);

                throw ValidationException::withMessages([
                    'email' => 'The reset email could not be sent right now. Please confirm your Mailtrap SMTP credentials and try again.',
                ]);
            }
        }

        return redirect()
            ->route('password.reset', ['email' => $validated['email']])
            ->with('status', 'If this email is registered, an OTP has been sent.');
    }

    public function showResetPassword(Request $request)
    {
        $email = $request->query('email');

        return view('auth.reset-password', compact('email'));
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->max(12)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => 'No account found for this email address.',
            ]);
        }

        $cacheKey = 'password-otp:' . strtolower($validated['email']);
        $otp = Cache::get($cacheKey);

        if (! $otp || (string) $otp !== $validated['otp']) {
            throw ValidationException::withMessages([
                'otp' => 'The OTP code is invalid or has expired.',
            ]);
        }

        $user->password = Hash::make($validated['password']);
        $user->setRememberToken(Str::random(60));
        $user->save();

        Cache::forget($cacheKey);

        return redirect()
            ->route('login')
            ->with('success', 'Your password has been updated. You can now login.');
    }

    /**
     * Show the registration form
     */
    public function showRegister()
    {
        return redirect()
            ->route('login')
            ->with('warning', 'Account registration is currently managed by the system administrator.');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'login' => 'required|string',
            'password' => 'required',
        ]);

        $throttleKey = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($throttleKey, $this->maxLoginAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = (int) ceil($seconds / 60);

            throw ValidationException::withMessages([
                'email' => "Too many login attempts. This account is locked for {$minutes} minutes. Please try again later.",
            ]);
        }

        $identifier = trim($validated['login']);

        $user = User::query()
            ->where('email', $identifier)
            ->orWhere('name', $identifier)
            ->first();

        if ($user && Auth::attempt(['email' => $user->email, 'password' => $validated['password']])) {
            $request->session()->regenerate();
            RateLimiter::clear($throttleKey);
            return redirect()
                ->intended($this->redirectPath())
                ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        RateLimiter::hit($throttleKey, $this->lockoutSeconds);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records for this account type.',
        ])->onlyInput('email');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        return redirect()
            ->route('login')
            ->with('warning', 'Account registration is disabled. Please ask the administrator to create or assign your account.');
    }

    /**
     * Redirect path based on user role
     */
    protected function redirectPath()
    {
        $user = auth()->user();

        if ($user->role === 'pharmacist') {
            return '/pharmacist/dashboard';
        } elseif ($user->role === 'procurement') {
            return '/procurement/dashboard';
        } elseif ($user->role === 'admin') {
            return '/admin/dashboard';
        }

        return '/home';
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function throttleKey(Request $request): string
    {
        $identifier = strtolower((string) $request->input('login', $request->input('email', 'guest')));

        return Str::transliterate($identifier . '|' . $request->ip());
    }

    protected function mailIsConfigured(): bool
    {
        $host = (string) config('mail.mailers.smtp.host');
        $username = (string) config('mail.mailers.smtp.username');
        $password = (string) config('mail.mailers.smtp.password');

        if ($host === '' || $username === '' || $password === '') {
            return false;
        }

        return ! in_array($username, ['your_mailtrap_username', 'null'], true)
            && ! in_array($password, ['your_mailtrap_password', 'null'], true);
    }
}
