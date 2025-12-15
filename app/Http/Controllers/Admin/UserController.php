<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    // REMOVE THE __construct METHOD ENTIRELY

    public function index()
    {
        // Add authorization check at the start of each method instead
        if (!auth()->user()->canManageUsers()) {
            abort(403, 'Unauthorized action.');
        }
        
        $users = User::orderBy('name')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        if (!auth()->user()->canManageUsers()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->canManageUsers()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:super_admin,admin,employee'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User successfully created');
    }

    public function show(User $user)
    {
        if (!auth()->user()->canManageUsers()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        if (!auth()->user()->canManageUsers()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if (!auth()->user()->canManageUsers()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:super_admin,admin,employee'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User successfully updated');
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->canManageUsers()) {
            abort(403, 'Unauthorized action.');
        }
        
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User successfully deleted');
    }
}