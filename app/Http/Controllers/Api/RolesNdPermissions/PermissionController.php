<?php

namespace App\Http\Controllers\Api\RolesNdPermissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $user = Auth::user();


        if (!$user->hasRole(['Super Admin', 'Write'])) {
            return response()->json([
                'message' => 'You are not authorized to access this resource',
            ], 403);
        }
        return response()->json(Permission::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        $permission = Permission::create($request->all());

        return response()->json($permission);
    }
}
