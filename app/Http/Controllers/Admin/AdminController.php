<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\AdminRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminResource;

class AdminController extends Controller
{
    public function showAll()
    {
        $this->authorize('manage_users');
        $admins = User::get();
        return response()->json([
            'data' =>AdminResource::collection($admins),
            'message' => "Show All Admins Successfully."
        ]);
    }

    public function create(AdminRequest $request)
    {
        $this->authorize('manage_users');
        $admin =User::create ([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_admin' => $request->is_admin,
        ]);
        return response()->json([
         'message' => "Admin Created Successfully."
     ]);
    }

    public function edit(string $id)
    {
        $this->authorize('manage_users');
        $admin = User::find($id);

        if (!$admin) {
            return response()->json([
                'message' => "Admin not found."
            ], 404);
        }
           return response()->json([
            'data' =>new AdminResource($admin),
            'message' => " Edit Admin By Id Successfully."
        ]);
    }


    public function update(Request $request, string $id)
    {
        $this->authorize('manage_users');
        $admin =User::find($id);

        if (!$admin) {
         return response()->json([
             'message' => "Admin not found."
         ], 404);
     }
        $admin->update([
         'name' => $request->name,
         'email' => $request->email,
         'password' => bcrypt($request->password),
         'is_admin' => $request->is_admin,
         ]);

        return response()->json([
         'message' => " Update Admin By Id Successfully."
     ]);
    }


    public function destroy(string $id){
        $this->authorize('manage_users');
        $admin =User::find($id);
        if (!$admin) {
            return response()->json([
                'message' => "Admin not found."
            ], 404);
        }

        $admin->delete($id);
        return response()->json([
            'data' =>new AdminResource($admin),
            'message' => " Soft Delete Admin By Id Successfully."
        ]);
    }

    public function showDeleted(){
        $this->authorize('manage_users');
        $admins=User::onlyTrashed()->with('user')->get();
        return response()->json([
            'data' =>AdminResource::collection($admins),
            'message' => "Show Deleted Admin Successfully."
        ]);
    }

    public function restore(string $id){
        $this->authorize('manage_users');
        $admin=User::withTrashed()->where('id',$id)->restore();
        return response()->json([
            'message' => " Restore Admin By Id Successfully."
        ]);
    }
    public function forceDelete(string $id){
        $this->authorize('manage_users');
        $admin=User::withTrashed()->where('id',$id)->first();
        if (!$admin) {
            return response()->json([
                'message' => "Admin not found."
            ], 404);
        }

            $admin->forceDelete();
        return response()->json([
            'message' => " Force Delete Admin By Id Successfully."
        ]);
    }
}
