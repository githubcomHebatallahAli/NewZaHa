<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;

class PermissionsController extends Controller
{
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
}
