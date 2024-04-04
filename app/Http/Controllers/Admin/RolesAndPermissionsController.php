<?php

namespace App\Http\Controllers\Admin;

use Log;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Http\Requests\PermissionRequest;
use App\Http\Resources\RoleUserResource;
use App\Http\Resources\PermissionResource;

class RolesAndPermissionsController extends Controller
{
    public function showAllRoles()
    {
        $this->authorize('manage_users');
        $Roles= Role::with('users')->get();
        return response()->json([
            'data' =>RoleResource::collection($Roles),
            'message' => "Show All Roles Successfully."
        ]);
    }

    public function createRole(RoleRequest $request)
    {
        $this->authorize('manage_users');
        $Role =Role::create ([
            'name'  => $request->name,
        ]);
        return response()->json([
            'data' =>new RoleResource($Role),
            'message' => "Create Role Successfully."
        ]);
    }


    public function showRole(string $id)
    {
        $this->authorize('manage_users');
        $Role =Role::with('users')->find($id);
        if (!$Role) {
            return response()->json([
                'message' => "Role not found."
            ], 404);
        }
        return response()->json([
         'data' =>new RoleResource($Role),
         'message' => " Show Role By Id Successfully."
     ]);
    }

    public function editRole(string $id)
    {
        $this->authorize('manage_users');
        $Role =Role::with('users')->find($id);
        if (!$Role) {
            return response()->json([
                'message' => "Role not found."
            ], 404);
        }
        return response()->json([
         'data' =>new RoleResource($Role),
         'message' => " Edit Role By Id Successfully."
     ]);
    }


    public function updateRole(RoleRequest $request, string $id)
    {
        $this->authorize('manage_users');
        $Role =Role::findOrFail($id);
        if (!$Role) {
            return response()->json([
                'message' => "Role not found."
            ], 404);
        }
        $Role->update([
         'name'  => $request->name,
        ]);
        return response()->json([
            'data' =>new RoleResource($Role),
            'message' => " Update Role By Id Successfully."
        ]);
    }


    public function deleteRole(string $id)
    {
        $this->authorize('manage_users');
        $Role =Role::find($id);
        if (!$Role) {
            return response()->json([
                'message' => "Role not found."
            ], 404);
        }
        $Role->delete($id);
        return response()->json([
            'data' =>new RoleResource($Role),
            'message' => " Delete Role By Id Successfully."
        ]);

    }

    public function showDeletedRole()
    {
        $this->authorize('manage_users');
        $Roles=Role::onlyTrashed()->with('users')->get();
        return response()->json([
            'data' =>RoleResource::collection($Roles),
            'message' => "Show Deleted Role Successfully."
        ]);
    }


    public function restoreRole($id)
    {
        $this->authorize('manage_users');
        $Role=Role::withTrashed()->where('id',$id)->restore();
        return response()->json([
            'message' => " Restore Role By Id Successfully."
        ]);
    }

    public function forceDeleteRole($id)
    {
        $this->authorize('manage_users');
        $Role=Role::withTrashed()->where('id',$id)->first();
        if (!$Role) {
            return response()->json([
                'message' => "Role not found."
            ], 404);
        }
        $Role->forceDelete();
        return response()->json([
            'message' => " Force Delete Role By Id Successfully."
        ]);
    }


    public function showAllPermissions()
    {
        $this->authorize('manage_users');
        $Permission= Permission::with('users')->get();
        return response()->json([
            'data' => PermissionResource::collection($Permission),
            'message' => "Show All  Permissions Successfully."
        ]);
    }

    public function createPermission(Request $request)
    {
        $this->authorize('manage_users');
        $Permission = Permission::create ([
            'name'  => $request->name,
        ]);
        return response()->json([
            'data' =>new PermissionResource($Permission),
            'message' => "Create  Permission Successfully."
        ]);
    }


    public function showPermission(string $id)
    {
        $this->authorize('manage_users');
        $Permission = Permission::with('users')->find($id);
        if (!$Permission) {
            return response()->json([
                'message' => "Permission not found."
            ], 404);
        }
        return response()->json([
         'data' =>new PermissionResource($Permission),
         'message' => " Show  Permission By Id Successfully."
     ]);
    }

    public function editPermission(string $id)
    {
        $this->authorize('manage_users');
        $Permission = Permission::with('users')->find($id);
        if (!$Permission) {
            return response()->json([
                'message' => "Permission not found."
            ], 404);
        }
        return response()->json([
         'Data' =>new PermissionResource($Permission),
         'message' => " Edit  Permission By Id Successfully."
     ]);
    }


    public function updatePermission(Request $request, string $id)
    {
        $this->authorize('manage_users');
        $Permission = Permission::findOrFail($id);
        if (!$Permission) {
            return response()->json([
                'message' => "Permission not found."
            ], 404);
        }
        $Permission->update([
         'name'  => $request->name,
        ]);
        return response()->json([
            'data' =>new  PermissionResource($Permission),
            'message' => " Update  Permission By Id Successfully."
        ]);
    }


    public function deletePermission(string $id)
    {
        $this->authorize('manage_users');
        $Permission = Permission::find($id);
        if (!$Permission) {
            return response()->json([
                'message' => "Permission not found."
            ], 404);
        }
        $Permission->delete($id);
        return response()->json([
            'data' =>new  PermissionResource($Permission),
            'message' => "Soft Delete Permission By Id Successfully."
        ]);
    }

    public function showDeletedPermission()
    {
        $Permissions=Permission::onlyTrashed()->with('users')->get();
        return response()->json([
            'data' =>PermissionResource::collection($Permissions),
            'message' => "Show Deleted Permission Successfully."
        ]);
    }


    public function restorePermission($id)
    {
        $this->authorize('manage_users');
        $Permission=Permission::withTrashed()->where('id',$id)->restore();
        return response()->json([
            'message' => " Restore Permission By Id Successfully."
        ]);
    }

    public function forceDeletePermission($id)
    {
        $this->authorize('manage_users');
        $Permission=Permission::withTrashed()->where('id',$id)->first();
        if (!$Permission) {
            return response()->json([
                'message' => "Permission not found."
            ], 404);
        }
        $Permission->forceDelete();
        return response()->json([
            'message' => " Force Delete Permission By Id Successfully."
        ]);
    }



    public function assignRoleToPermissions(PermissionRequest $request, Role $role)
    {
        $this->authorize('manage_users');
        $permission = Permission::find($request->input('permission_id'));
        try {
            $role->permissions()->attach($permission);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }


        return response()->json(['message' => 'Permission assigned successfully']);
    }



    public function revokeRoleFromPermission(Request $request, $permissionId ,$roleId)
    {
        $this->authorize('manage_users');
        $role = Role::findOrFail($roleId);
        $permission = permission::findOrFail($permissionId);

        $permission->roles()->detach($role);

        return response()->json(['message' => 'Role Revoke From Permission Successfully']);
    }


    public function revokeRoleFromPermissions(Request $request, $roleId)
    {
        $this->authorize('manage_users');
        $role = Role::findOrFail($roleId);
        $permissionIds = $request->input('permission_id');
        $role->permissions()->detach($permissionIds);

        return response()->json(['message' => 'Role revoked from permissions successfully'], 200);
    }

    public function showAllRolesWithPermissions(){
        $this->authorize('manage_users');
        $Roles= Role::with('permissions')->get();
        return response()->json([
            'data' => RoleResource::collection($Roles),
            'message' => "Show All Roles With Permissions Successfully."
        ]);
    }


    public function assignRoleToUser(Request $request, $roleId, $userId)
{
    $this->authorize('manage_users');
    $role = Role::findOrFail($roleId);
    $user = User::findOrFail($userId);

    $user->roles()->attach($role);

    return response()->json(['message' => 'Role Assigned To User Successfully']);
}


    public function revokeRoleFromUser(Request $request, $roleId, $userId)
{
    $this->authorize('manage_users');
    $role = Role::findOrFail($roleId);
    $user = User::findOrFail($userId);
    $user->roles()->detach($role);

    return response()->json(['message' => 'Role Revoke from User Successfully']);
}



public function assignPermissionsToUser(PermissionRequest $request, User $user)
{
    $this->authorize('manage_users');
    $permission = Permission::find($request->input('permission_id'));
    try {
        $user->permissions()->attach($permission);
    } catch (\Exception $e) {
        \Log::error($e->getMessage());
    }
    return response()->json([
        'data' =>  PermissionResource::collection($permission),
        'message' => 'Permissions Assigned To User successfully']);
}

public function revokePermissionFromUser(Request $request, $permissionId, $userId)
{
    $this->authorize('manage_users');
    $permission = Permission::findOrFail($permissionId);
    $user = User::findOrFail($userId);

    $user->permissions()->detach($permission);

    return response()->json([
        'message' => 'Permission Revoke From User Successfully']);
}

public function revokeUserFromPermissions(Request $request, $userId)
{
    $this->authorize('manage_users');
    $user = User::findOrFail($userId);
    $permissionIds = $request->input('permission_id');
    $user->permissions()->detach($permissionIds);

    return response()->json([
        'message' => 'User revoked from permissions successfully']);
}

public function showAllRolesWithUsers(){
    $this->authorize('manage_users');
    $Roles= Role::with('users')->get();
    return response()->json([
        'data' =>  RoleUserResource::collection($Roles),
        'message' => "Show All Users With Permissions Successfully."
    ]);
}

public function showAllPermissionsWithUsers(){
    $this->authorize('manage_users');
    $Permissions= Permission::with('users')->get();

    return response()->json([
        'data' => PermissionResource::collection($Permissions),
        'message' => "Show All Users With Permissions Successfully."
    ]);

}
}
