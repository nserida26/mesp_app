<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        return view('admin.permissions.index', [
            'permissions' => Permission::orderBy('name')->paginate(30),
        ]);
    }

    public function create()
    {
        return view('admin.permissions.form', ['permission' => null]);
    }

    public function store(Request $request)
    {
        Permission::create($request->validate(['name' => 'required|string|max:100|unique:permissions,name']));

        return redirect()->route('admin.permissions.index')->with('success', 'Permission creee.');
    }

    public function show(Permission $permission)
    {
        return redirect()->route('admin.permissions.edit', $permission);
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.form', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $permission->update($request->validate(['name' => 'required|string|max:100|unique:permissions,name,' . $permission->id]));

        return redirect()->route('admin.permissions.index')->with('success', 'Permission mise a jour.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return back()->with('success', 'Permission supprimee.');
    }
}
