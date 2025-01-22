<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('name', 'password'))) {
   
            $user = Auth::user();

            if (!$user->active) {
                Auth::logout(); 
                return back()->withErrors([
                    'name' => 'Your account is not active.',
                ]);
            }


if ($user->role === \App\Enums\Role::Admin->value) {
    return redirect()->route('admin.dashboard');
}

return redirect()->route('Users.dashboard');

        }

        return back()->withErrors([
            'name' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('jobs.welcome');
    }
}
