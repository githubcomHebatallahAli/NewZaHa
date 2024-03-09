<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use App\Http\Requests\PermissionRequest;
use App\Http\Resources\PermissionResource;

class RolePermissionController extends Controller
{
    public function showAllRoles()
    {
        $Roles= Role::with('users')->get();
        return response()->json([
            'Role' =>RoleResource::collection($Roles),
            'message' => "Show All Roles Successfully."
        ], 200);
    }

    public function createRole(RoleRequest $request)
    {
        $Role =Role::create ([
            'name'  => $request->name,
        ]);
        return response()->json([
            'Role' =>RoleResource::collection($Roles),
            'message' => "Create Role Successfully."
        ], 201);
    }


    public function showRole(string $id)
    {
        $Role =Role::with('user')->find($id);
        return response()->json([
         'Role' =>new RoleResource($Role),
         'message' => " Show Role By Id Successfully."
     ], 200);
    }

    public function editRole(string $id)
    {
        $Role =Role::with('user')->find($id);
        return response()->json([
         'Role' =>new RoleResource($Role),
         'message' => " Edit Role By Id Successfully."
     ], 200);
    }


    public function updateRole(RoleRequest $request, string $id)
    {
        $Role =Role::findOrFail($id);
        $Role->update([
         'name'  => $request->name,
        ]);
        return response()->json([
            'Role' =>new RoleResource($Role),
            'message' => " Update Role By Id Successfully."
        ], 200);
    }


    public function destroyRole(string $id)
    {
        $Role =Role::find($id);
        $Role->delete($id);
        return response()->json([
            'Role' =>new RoleResource($Role),
            'message' => " Delete Role By Id Successfully."
        ], 200);

    }

    public function showAll()
    {
        $Permission= Permission::with('users')->get();
        return response()->json([
            ' Permission' => PermissionResource::collection($Permission),
            'message' => "Show All  Permissions Successfully."
        ], 200);
    }

    public function create( PermissionRequest $request)
    {
        $Permission = Permission::create ([
            'name'  => $request->name,
        ]);
        return response()->json([
            ' Permission' => PermissionResource::collection($Permission),
            'message' => "Create  Permission Successfully."
        ], 201);
    }


    public function show(string $id)
    {
        $Permission = Permission::with('user')->find($id);
        return response()->json([
         ' Permission' =>new  PermissionResource($Permission),
         'message' => " Show  Permission By Id Successfully."
     ], 200);
    }

    public function edit(string $id)
    {
        $Permission = Permission::with('user')->find($id);
        return response()->json([
         ' Permission' =>new  PermissionResource($Permission),
         'message' => " Edit  Permission By Id Successfully."
     ], 200);
    }


    public function update( PermissionRequest $request, string $id)
    {
        $Permission = Permission::findOrFail($id);
        $Permission->update([
         'name'  => $request->name,
        ]);
        return response()->json([
            ' Permission' =>new  PermissionResource($Permission),
            'message' => " Update  Permission By Id Successfully."
        ], 200);
    }


    public function destroy(string $id)
    {
        $Permission = Permission::find($id);
        $Permission->delete($id);
        return response()->json([
            'Permission' =>new  PermissionResource($Permission),
            'message' => " Delete  Permission By Id Successfully."
        ], 200);
    }

    public function assignRoleToPermission(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);
        $permissionId = $request->input('permission_id');
        $permission = Permission::findOrFail($permissionId);
        $role->permissions()->syncWithoutDetaching([$permission->id]);

        return response()->json(['message' => 'Permission assigned to role successfully'], 200);
    }

    public function assignRoleToPermissions(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);
        $permissionIds = $request->input('permission_ids');
        $permissions = Permission::findOrFail($permissionIds);
        $role->permissions()->syncWithoutDetaching($permissions->pluck('id')->toArray());

        return response()->json(['message' => 'Permissions assigned to role successfully'], 200);
    }


    public function revokeRoleFromPermission(Request $request, $permissionId)
    {
        $permission = Permission::findOrFail($permissionId);
        $roleId = $request->input('role_id');
        $role = Role::findOrFail($roleId);
        $role->permissions()->detach($permission->id);

        return response()->json(['message' => 'Role revoked from permission successfully'], 200);
    }

    public function revokeRoleFromPermissions(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);
        $permissionIds = $request->input('permission_ids');
        // Detach the role from multiple permissions
        $role->permissions()->detach($permissionIds);

        return response()->json(['message' => 'Role revoked from permissions successfully'], 200);
    }

    
}

