<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    // List + search
    public function index(Request $request)
    {
        $query = User::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('id')->paginate(5);

        return view('admin_dashboard.tables.user_management', compact('users'));
    }



    public function view() { $users = User::orderBy('id')->paginate(5); return view('admin_dashboard.tables.user_management', compact('users')); }
    // Show single user details
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin_dashboard.tables.show', compact('user'));
    }

    // Show edit form
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin_dashboard.tables.edit', compact('user'));
    }

    // Handle update
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,$id",
            'role' => 'nullable|string',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $user->update($request->only(['name','email','role','status']));

        return redirect()->route('admin.users.index')
                         ->with('success', 'User updated successfully.');
    }

    // Delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'User deleted successfully.');
    }
}
