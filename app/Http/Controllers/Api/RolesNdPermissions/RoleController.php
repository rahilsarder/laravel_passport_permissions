<?php

namespace App\Http\Controllers\Api\RolesNdPermissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();

        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',

        ]);

        $role = Role::create(['name' => $request->name]);

        return response()->json($role);
    }

    public function givePermissionToRole(Request $request)
    {
        $request->validate([
            'role' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::findByName($request->role);

        $role->givePermissionTo($request->permission);

        return response()->json($role);
    }
}
