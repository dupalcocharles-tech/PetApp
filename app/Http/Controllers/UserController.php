<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ==========================
    // Display all users
    // ==========================
    public function index() {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // ==========================
    // Show create form
    // ==========================
    public function create() {
        return view('users.create');
    }

    // ==========================
    // Store new user
    // ==========================
    public function store(Request $request) {
        $request->validate([
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'user_type' => 'required|in:pet_owner,clinic_staff,admin',
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password), // ✅ hashed correctly
            'user_type' => $request->user_type
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    // ==========================
    // Show single user
    // ==========================
    public function show($id) {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    // ==========================
    // Edit form
    // ==========================
    public function edit($id) {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // ==========================
    // Update user
    // ==========================
    public function update(Request $request, $id) {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|unique:users,username,'.$id,
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|min:6',
            'user_type' => 'required|in:pet_owner,clinic_staff,admin',
        ]);

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'user_type' => $request->user_type
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // ==========================
    // Delete user
    // ==========================
    public function destroy($id) {
        User::destroy($id);
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
