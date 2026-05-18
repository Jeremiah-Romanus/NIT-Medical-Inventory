<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class AuthController extends Controller
{
    protected array $roles = ['pharmacist', 'procurement'];
    protected int $maxLoginAttempts = 5;
    protected int $lockoutSeconds = 300;

    /**
     * Show the login selector page
     */
    public function showLoginSelection()
    {
        return view('auth.login-selector');
    }

    /**
     * Show the login form
     */
    public function showLogin(Request $request, string $role)
    {
        $role = $this->normalizeRole($role);
        $throttleKey = $this->throttleKey($request, $role);
        $isLocked = RateLimiter::tooManyAttempts($throttleKey, $this->maxLoginAttempts);
        $lockoutSecondsRemaining = $isLocked ? RateLimiter::availableIn($throttleKey) : 0;

        return view('auth.login', compact('role', 'isLocked', 'lockoutSecondsRemaining'));
    }

    /**
     * Show the registration form
     */
    public function showRegister()
    {
        return view('auth.register', ['roles' => $this->roles]);
    }

    /**
     * Handle login request
     */
    public function login(Request $request, string $role)
    {
        $role = $this->normalizeRole($role);

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $throttleKey = $this->throttleKey($request, $role);

        if (RateLimiter::tooManyAttempts($throttleKey, $this->maxLoginAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = (int) ceil($seconds / 60);

            throw ValidationException::withMessages([
                'email' => "Too many login attempts. This account is locked for {$minutes} minutes. Please try again later.",
            ]);
        }

        $credentials['role'] = $role;

        if (Auth::attempt($credentials)) {
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', 'digits:9', 'regex:/^[0-9]{9}$/'],
            'email' => 'required|email|max:255|unique:users,email',
            'role' => 'required|in:pharmacist,procurement',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->max(12)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'phone.digits' => 'Phone number must contain exactly 9 digits after +255.',
            'phone.regex' => 'Phone number must contain digits only.',
        ]);

        $validated['phone'] = '+255' . $validated['phone'];

        if (User::where('phone', $validated['phone'])->exists()) {
            throw ValidationException::withMessages([
                'phone' => 'This phone number is already registered.',
            ]);
        }

        User::create($validated);

        return redirect()
            ->route('login.role', $validated['role'])
            ->with('success', 'Registration completed. You can now log in.');
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
        }

        return '/dashboard';
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

    protected function normalizeRole(string $role): string
    {
        $role = strtolower($role);

        abort_unless(in_array($role, $this->roles, true), 404);

        return $role;
    }

    protected function throttleKey(Request $request, string $role): string
    {
        return Str::transliterate($role . '|' . $request->ip());
    }
}
