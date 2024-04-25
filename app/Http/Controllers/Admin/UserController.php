<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\Auth\RegisterRequest;

class UserController extends Controller
{
    public function showAll()
    {
        $this->authorize('manage_users');
        $users = User::get();
        return response()->json([
            'data' => UserResource::collection($users),
            'message' => "Show All Users Successfully."
        ]);
    }

    public function create(RegisterRequest $request)
    {
        $this->authorize('manage_users');
           $user =User::create ([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);
           $user->save();
           return response()->json([
            'data' =>new UserResource($user),
            'message' => "User Created Successfully."
        ]);
}

public function show(string $id)
{
    $this->authorize('manage_users');
   $User =User::find($id);
   if (!$User) {
    return response()->json([
        'message' => "User not found."
    ], 404);
}
   return response()->json([
    'data' =>new UserResource($User),
    'message' => " Show User By Id Successfully."
]);

}

public function edit(string $id)
{
    $this->authorize('manage_users');
   $User =User::find($id);
   if (!$User) {
    return response()->json([
        'message' => "User not found."
    ], 404);
}
   return response()->json([
    'data' =>new UserResource($User),
    'message' => " Edit User By Id Successfully."
]);

}

public function update(RegisterRequest $request, string $id)
{
    $this->authorize('manage_users');
   $user =User::findOrFail($id);
   if (!$user) {
    return response()->json([
        'message' => "User not found."
    ], 404);
}
   $user->update([
    'name' => $request->name,
    'email' => $request->email,
    'password' => $request->password,
    ]);

   $user->save();
   return response()->json([
    'data' =>new UserResource($user),
    'message' => " Update User By Id Successfully."
]);
}

public function destroy(string $id){
    $this->authorize('manage_users');
    $User =User::find($id);
    if (!$User) {
        return response()->json([
            'message' => "User not found."
        ], 404);
    }

    $User->delete($id);
    return response()->json([
        'data' =>new UserResource($User),
        'message' => " Soft Delete User By Id Successfully."
    ]);
}
public function showDeleted(){
    $this->authorize('manage_users');
    $Users=User::onlyTrashed()->get();
    return response()->json([
        'data' =>UserResource::collection($Users),
        'message' => "Show Deleted User Successfully."
    ]);
}

public function restore(string $id){
    $this->authorize('manage_users');
    $User=User::withTrashed()->where('id',$id)->restore();
    return response()->json([
        'message' => " Restore User By Id Successfully."
    ]);
}

public function forceDelete(string $id){
    $this->authorize('manage_users');
    $User=User::withTrashed()->where('id',$id)->first();
    if (!$User) {
        return response()->json([
            'message' => "User not found."
        ], 404);
    }

        $User->forceDelete();
    return response()->json([
        'message' => " Force Delete User By Id Successfully."
    ]);
}
}


