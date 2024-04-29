<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Http\Requests\RoleRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;

class RolesController extends Controller
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
}
