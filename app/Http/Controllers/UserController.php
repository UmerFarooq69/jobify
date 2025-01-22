<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        $users = User::where('role', '!=', 'admin')->get();
        return view('Users.index', compact('users'));
    }

    public function create(){
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'nullable|in:admin,user',
            'active' => 'nullable|boolean',
        ]);

        $role = $request->has('role') && $request->role === 'admin' ? 'admin' : 'user';
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'active' => $request->input('active', true),  
            'role' => $role,
        ]);
        return redirect()->route('jobs.welcome')->with('success', 'User created successfully!');
    }       
    public function toggleStatus(User $user)
    {
        $user->active = !$user->active;
        $user->save();
    
        return redirect()->back()->with('success', 'User status updated successfully.');
    }
}
