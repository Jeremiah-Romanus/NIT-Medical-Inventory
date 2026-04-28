<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    protected array $roles = ['pharmacist', 'procurement'];

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
    public function showLogin(string $role)
    {
        $role = $this->normalizeRole($role);

        return view('auth.login', compact('role'));
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

        $credentials['role'] = $role;

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended($this->redirectPath());
        }

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
            'email' => 'required|email|max:255|unique:users,email',
            'role' => 'required|in:pharmacist,procurement',
            'password' => 'required|string|min:8|confirmed',
        ]);

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
}
