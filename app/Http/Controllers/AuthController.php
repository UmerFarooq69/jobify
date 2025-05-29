<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt authentication
        if (Auth::attempt($request->only('name', 'password'))) {
            $user = Auth::user();

            // Check if user account is active
            if (!$user->active) {
                Auth::logout();
                return back()->withErrors([
                    'name' => 'Your account is not active. Please contact support.',
                ]);
            }

            // Regenerate session for security
            $request->session()->regenerate();

            // Redirect based on role
            return match ($user->role) {
                Role::Admin->value => redirect()->route('admin.dashboard'),
                default => redirect()->route('Users.dashboard'),
            };
        }

        // Return an error message if authentication fails
        return back()->withErrors([
            'name' => 'Invalid credentials. Please try again.',
        ]);
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('jobs.index')->with('success', 'You have been logged out successfully.');
    }
}
