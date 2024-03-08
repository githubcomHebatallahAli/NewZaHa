<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;

class RolePermissionController extends Controller
{
    public function showAll()
    {
        $Roles= Role::with('users')->get();
        return response()->json([
            'Role' =>RoleResource::collection($Roles),
            'message' => "Show All Roles Successfully."
        ], 200);
    }

    public function create(RoleRequest $request)
    {
        $Role =Role::create ([
            'name'  => $request->name,
        ]);
        return response()->json([
            'Role' =>RoleResource::collection($Roles),
            'message' => "Create Role Successfully."
        ], 201);
    }


    public function show(string $id)
    {
        $Role =Role::with('user')->find($id);
        return response()->json([
         'Role' =>new RoleResource($Role),
         'message' => " Show Role By Id Successfully."
     ], 200);
    }

    public function edit(string $id)
    {
        $Role =Role::with('user')->find($id);
        return response()->json([
         'Role' =>new RoleResource($Role),
         'message' => " Edit Role By Id Successfully."
     ], 200);
    }


    public function update(RoleRequest $request, string $id)
    {
        $Role->update($request->all());
        $Role->users()->syncWithoutDetaching([$request->user_id => [
            'numberSales' => $request->numberSales,
            'price' => $request->price,
            'startingDate' => $request->startingDate,
            'endingDate' => $request->endingDate,
            'nameOfTeam' => $request->nameOfTeam,
        ]]);
        $Role->clearMediaCollection('Roles');
        if ($request->hasFile('imgRole')) {
        $Role->addMediaFromRequest('imgRole')->toMediaCollection('Roles');
    }
    if ($request->hasFile('url')) {
        $Role->addMediaFromRequest('url')->toMediaCollection('Roles');
    }
        return response()->json([
            'Role' =>new RoleResource($Role),
            'message' => " Update Role By Id Successfully."
        ], 200);
    }


    public function destroy(string $id)
    {
        $Role =Role::find($id);
        $Role->delete($id);
        return response()->json([
            'Role' =>new RoleResource($Role),
            'message' => " Soft Delete Role By Id Successfully."
        ], 200);

    }
}
