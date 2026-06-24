<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\AuditTrail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminUserController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['admin', 'pharmacist', 'procurement'])],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
        ]);

        AuditTrail::record(
            'user.created',
            $request->user(),
            $user->name,
            [],
            ['email' => $user->email, 'role' => $user->role]
        );

        return back()->with('success', 'User created successfully.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->role === 'admin') {
            throw ValidationException::withMessages([
                'user' => 'Administrator accounts cannot be deleted.',
            ]);
        }

        if ((int) $request->user()->id === (int) $user->id) {
            throw ValidationException::withMessages([
                'user' => 'You cannot delete your own account while logged in.',
            ]);
        }

        $user->delete();

        AuditTrail::record(
            'user.deleted',
            $request->user(),
            $user->name,
            ['email' => $user->email, 'role' => $user->role],
            []
        );

        return back()->with('success', 'User deleted successfully.');
    }
}
