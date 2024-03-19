<?php

namespace App\Http\Controllers\Admin;

use App\Models\Team;
use Illuminate\Http\Request;
use App\Http\Requests\TeamRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;

class TeamController extends Controller
{
    public function showAll()
    {
        $this->authorize('manage_users');
         $Teams = Team::with('user')->get();

            return response()->json([
            'data' =>TeamResource::collection($Teams),
            'message' => "Show All Teams Successfully."
        ]);
    }

    public function create(TeamRequest $request)
    {
        $this->authorize('manage_users');
           $Team =Team::create ([
                'job' => $request->job,
                'skills' => $request->skills,
                'numProject' => $request->numProject,
                'address' => $request->address,
                'phoneNumber' => $request->phoneNumber,
                'qualification' => $request->qualification,
                'user_id' => $request->user_id,
            ]);
            $Team->addMediaFromRequest('photo')->toMediaCollection('Teams');
            $Team->addMediaFromRequest('imgIDCard')->toMediaCollection('Teams');
           $Team->save();
           return response()->json([
            'data' =>new TeamResource($Team),
            'message' => "Team Created Successfully."
        ]);
        }


    public function show(string $id)
    {
        $this->authorize('manage_users');
       $Team =Team::with('user')->find($id);
       return response()->json([
        'data' =>new TeamResource($Team),
        'message' => " Show Team By Id Successfully."
    ]);

    }

    public function edit(string $id)
    {
        $this->authorize('manage_users');
       $Team =Team::with('user')->find($id);
       return response()->json([
        'data' =>new TeamResource($Team),
        'message' => " Edit Team By Id Successfully."
    ]);
    }

    
    public function update(TeamRequest $request, string $id)
    {
        $this->authorize('manage_users');
       $Team =Team::findOrFail($id);
       $Team->update([
        'job' => $request->job,
        'skills' => $request->skills,
        'numProject' => $request->numProject,
        'address' => $request->address,
        'phoneNumber' => $request->phoneNumber,
        'qualification' => $request->qualification,
        'user_id' => $request->user_id,
        ]);
        $Team->clearMediaCollection('Teams');
        if ($request->hasFile('photo')) {
            $Team->addMedia($request->file('photo'))->toMediaCollection('Teams');
        }
        if ($request->hasFile('imgIDCard')) {
            $Team->addMedia($request->file('imgIDCard'))->toMediaCollection('Teams');
        }
       $Team->save();
       return response()->json([
        'data' =>new TeamResource($Team),
        'message' => " Update Team By Id Successfully."
    ]);
}

public function destroy(string $id){
    $this->authorize('manage_users');
    $Team =Team::find($id);
    $Team->delete($id);
    return response()->json([
        'data' =>new TeamResource($Team),
        'message' => " Soft Delete Team By Id Successfully."
    ]);
}
public function showDeleted(){
    $this->authorize('manage_users');
    $Teams=Team::onlyTrashed()->with('user')->get();
    return response()->json([
        'data' =>TeamResource::collection($Teams),
        'message' => "Show Deleted Team Successfully."
    ]);
}

public function restore(string $id){
    $this->authorize('manage_users');
    $Team=Team::withTrashed()->where('id',$id)->restore();
    return response()->json([
        'message' => " Restore Team By Id Successfully."
    ]);
}

public function forceDelete(string $id){
    $this->authorize('manage_users');
    $Team=Team::withTrashed()->where('id',$id)->first();
    if ($Team) {
        $Team->getMedia('Teams')->each(function ($media) {
            $media->delete();
        });
        $Team->forceDelete();
    return response()->json([
        'message' => " Force Delete Team By Id Successfully."
    ]);
}
}
}
