<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminResource;

class AdminController extends Controller
{
    public function showAll()
    {
         $admins = Admin::with('user')->get();
            return response()->json([
            'admin' =>AdminResource::collection($admins),
            'message' => "Show All admins Successfully."
        ], 200);
    }

    public function create(AdminRequest $request)
    {
           $admin =Admin::create ([
                'job' => $request->job,
                'imgAdmin' => $request->file('imgAdmin')->store('Admin'),
                'user_id' => $request->user_id,
            ]);
           $admin->save();
           return response()->json([
            'admin' =>new AdminResource($admin),
            'message' => "Admin Created Successfully."
        ], 200);


        }


    public function show(string $id)
    {
       $admin =Admin::with('user')->find($id);
       return response()->json([
        'admin' =>new AdminResource($admin),
        'message' => " Show Admin By Id Successfully."
    ], 200);

    }

    public function edit(string $id)
    {
       $admin =Admin::with('user')->find($id);
       return response()->json([
        'admin' =>new AdminResource($admin),
        'message' => " Edit Admin By Id Successfully."
    ], 200);

    }

    public function update(AdminRequest $request, string $id)
    {
       $admin =Admin::findOrFail($id);
       $admin->update([
        'phone' => $request->phone,
        'message' => $request->message,
        'user_id' => $request->user_id,
        ]);

       $admin->save();
       return response()->json([
        'admin' =>new AdminResource($admin),
        'message' => " Update Admin By Id Successfully."
    ], 200);
}

public function destroy(string $id){
    $admin =Admin::find($id);
    $admin->delete($id);
    return response()->json([
        'admin' =>new AdminResource($admin),
        'message' => " Soft Delete Admin By Id Successfully."
    ], 200);
}
public function showDeleted(){
    $admins=Admin::onlyTrashed()->with('user')->get();
    return response()->json([
        'admin' =>AdminResource::collection($admins),
        'message' => "Show Deleted Admin Successfully."
    ], 200);
}

public function restore(string $id){
    $admin=Admin::withTrashed()->where('id',$id)->restore();
    return response()->json([
        'message' => " Restore Admin By Id Successfully."
    ], 200);
}
public function forceDelete(string $id){
    $admin=Admin::withTrashed()->where('id',$id)->forceDelete();
    return response()->json([
        'message' => " Force Delete Admin By Id Successfully."
    ], 200);
}
}
