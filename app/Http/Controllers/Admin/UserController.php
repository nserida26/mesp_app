<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('manage users');

        $users = User::with('roles')
            ->when($request->q, fn($q, $search) =>
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
            )
            ->when($request->role, fn($q, $role) =>
                $q->whereHas('roles', fn($r) => $r->where('name', $role))
            )
            ->latest()
            ->paginate(20);

        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $this->authorize('manage users');
        $roles = Role::orderBy('name')->get();
        return view('admin.users.form', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->authorize('manage users');

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'roles'    => ['required', 'array'],
            'roles.*'  => ['string', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $user->syncRoles($validated['roles']);

        return redirect()->route('admin.users.index')
            ->with('success', "Utilisateur {$user->name} créé avec succès.");
    }

    public function edit(User $user)
    {
        $this->authorize('manage users');
        $roles = Role::orderBy('name')->get();
        return view('admin.users.form', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('manage users');

        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'roles' => ['required', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['string', 'min:8', 'confirmed'];
        }

        $validated = $request->validate($rules);

        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
            ...($request->filled('password') ? ['password' => bcrypt($validated['password'])] : []),
        ]);

        $user->syncRoles($validated['roles']);

        return redirect()->route('admin.users.index')
            ->with('success', "Utilisateur {$user->name} mis à jour.");
    }

    public function destroy(User $user)
    {
        $this->authorize('manage users');

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Utilisateur $name supprimé.");
    }
}
