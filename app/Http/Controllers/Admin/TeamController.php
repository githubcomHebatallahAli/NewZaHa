<?php

namespace App\Http\Controllers\Admin;

use App\Models\Team;
use App\Http\Requests\TeamRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;

class TeamController extends Controller
{
    public function showAll()
    {
         $Teams = Team::with('user')->get();

            return response()->json([
            'data' =>TeamResource::collection($Teams),
            'message' => "Show All Teams Successfully."
        ]);
    }

    public function create(TeamRequest $request)
    {

           $Team =Team::create ([
                'job' => $request->job,
                'skills' => $request->skills,
                'numProject' => $request->numProject,
                'address' => $request->address,
                'phoneNumber' => $request->phoneNumber,
                'qualification' => $request->qualification,
                'user_id' => $request->user_id,
            ]);
            if ($request->hasFile('photo')) {
            $Team->addMediaFromRequest('photo')->toMediaCollection('Teams');
            }
            if ($request->hasFile('photo')) {
            $Team->addMediaFromRequest('imgIDCard')->toMediaCollection('Teams');
            }
           $Team->save();
           return response()->json([
            'data' =>new TeamResource($Team),
            'message' => "Team Created Successfully."
        ]);
        }
    

    public function show(string $id)
    {

       $Team =Team::with('user')->find($id);
       if (!$Team) {
        return response()->json([
            'message' => "Job not found."
        ], 404);
    }
       return response()->json([
        'data' =>new TeamResource($Team),
        'message' => " Show Team By Id Successfully."
    ]);

    }

    public function edit(string $id)
    {

       $Team =Team::with('user')->find($id);
       if (!$Team) {
        return response()->json([
            'message' => "Job not found."
        ], 404);
    }
       return response()->json([
        'data' =>new TeamResource($Team),
        'message' => " Edit Team By Id Successfully."
    ]);
    }


    public function update(TeamRequest $request, string $id)
    {

       $Team =Team::findOrFail($id);
       if (!$Team) {
        return response()->json([
            'message' => "Job not found."
        ], 404);
    }
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

    $Team =Team::find($id);
    if (!$Team) {
        return response()->json([
            'message' => "Job not found."
        ], 404);
    }
    $Team->delete($id);
    return response()->json([
        'data' =>new TeamResource($Team),
        'message' => " Soft Delete Team By Id Successfully."
    ]);
}
public function showDeleted(){

    $Teams=Team::onlyTrashed()->with('user')->get();
    return response()->json([
        'data' =>TeamResource::collection($Teams),
        'message' => "Show Deleted Team Successfully."
    ]);
}

public function restore(string $id){

    $Team=Team::withTrashed()->where('id',$id)->restore();
    return response()->json([
        'message' => " Restore Team By Id Successfully."
    ]);
}

public function forceDelete(string $id){

    $Team=Team::withTrashed()->where('id',$id)->first();
    if (!$Team) {
        return response()->json([
            'message' => "Job not found."
        ], 404);
    }
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
