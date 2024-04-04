<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Requests\AdminRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminResource;

class AdminController extends Controller
{
    public function showAll()
    {
        $this->authorize('manage_users');
         $admins = Admin::with('user')->get();

            return response()->json([
            'data' =>AdminResource::collection($admins),
            'message' => "Show All admins Successfully."
        ]);
    }

    public function create(AdminRequest $request)
    {
        $this->authorize('manage_users');
           $admin =Admin::create ([
                'job' => $request->job,
                'user_id' => $request->user_id,
            ]);
            if ($request->hasFile('photo')) {
                $admin->addMediaFromRequest('photo')->toMediaCollection('Admins');
            }
           $admin->save();
           return response()->json([
            'data' =>new AdminResource($admin),
            'message' => "Admin Created Successfully."
        ]);


        }


    public function show(string $id)
    {
        $this->authorize('manage_users');
       $admin =Admin::with('user')->find($id);
       if (!$admin) {
        return response()->json([
            'message' => "Admin not found."
        ], 404);
    }
       return response()->json([
        'data' =>new AdminResource($admin),
        'message' => " Show Admin By Id Successfully."
    ]);

    }

    public function edit(string $id)
    {
        $this->authorize('manage_users');
       $admin =Admin::with('user')->find($id);
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

    public function update(AdminRequest $request, string $id)
    {
        $this->authorize('manage_users');
       $admin =Admin::findOrFail($id);
       if (!$admin) {
        return response()->json([
            'message' => "Admin not found."
        ], 404);
    }
       $admin->update([
        'job' => $request->job,
        'user_id' => $request->user_id,
        ]);
        $admin->clearMediaCollection('Admins');
        if ($request->hasFile('photo')) {
            $admin->addMedia($request->file('photo'))->toMediaCollection('Admins');
        }

       $admin->save();
       return response()->json([
        'data' =>new AdminResource($admin),
        'message' => " Update Admin By Id Successfully."
    ]);
}

public function destroy(string $id){
    $this->authorize('manage_users');
    $admin =Admin::find($id);
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
    $admins=Admin::onlyTrashed()->with('user')->get();
    return response()->json([
        'data' =>AdminResource::collection($admins),
        'message' => "Show Deleted Admin Successfully."
    ]);
}

public function restore(string $id){
    $this->authorize('manage_users');
    $admin=Admin::withTrashed()->where('id',$id)->restore();
    return response()->json([
        'message' => " Restore Admin By Id Successfully."
    ]);
}

public function forceDelete(string $id){
    $this->authorize('manage_users');
    $admin=Admin::withTrashed()->where('id',$id)->first();
    if (!$admin) {
        return response()->json([
            'message' => "Admin not found."
        ], 404);
    }

    if ($admin) {
        $admin->getMedia('Admins')->each(function ($media) {
            $media->delete();
        });
        $admin->forceDelete();
    return response()->json([
        'message' => " Force Delete Admin By Id Successfully."
    ]);
}
}
}
