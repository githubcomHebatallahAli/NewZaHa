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
         $Teams = Team::with('user')->get();

            return response()->json([
            'Team' =>TeamResource::collection($Teams),
            'message' => "Show All Teams Successfully."
        ], 200);
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
            $Team->addMediaFromRequest('photo')->toMediaCollection('Teams');
            $Team->addMediaFromRequest('imgIDCard')->toMediaCollection('Teams');
           $Team->save();
           return response()->json([
            'Team' =>new TeamResource($Team),
            'message' => "Team Created Successfully."
        ], 200);


        }


    public function show(string $id)
    {
       $Team =Team::with('user')->find($id);
       return response()->json([
        'Team' =>new TeamResource($Team),
        'message' => " Show Team By Id Successfully."
    ], 200);

    }

    public function edit(string $id)
    {
       $Team =Team::with('user')->find($id);
       return response()->json([
        'Team' =>new TeamResource($Team),
        'message' => " Edit Team By Id Successfully."
    ], 200);

    }

    public function update(TeamRequest $request, string $id)
    {
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
        'Team' =>new TeamResource($Team),
        'message' => " Update Team By Id Successfully."
    ], 200);
}

public function destroy(string $id){
    $Team =Team::find($id);
    $Team->delete($id);
    return response()->json([
        'Team' =>new TeamResource($Team),
        'message' => " Soft Delete Team By Id Successfully."
    ], 200);
}
public function showDeleted(){
    $Teams=Team::onlyTrashed()->with('user')->get();
    return response()->json([
        'Team' =>TeamResource::collection($Teams),
        'message' => "Show Deleted Team Successfully."
    ], 200);
}

public function restore(string $id){
    $Team=Team::withTrashed()->where('id',$id)->restore();
    return response()->json([
        'message' => " Restore Team By Id Successfully."
    ], 200);
}
public function forceDelete(string $id){
    $Team=Team::withTrashed()->where('id',$id)->first();
    if ($Team) {
        $Team->getMedia('Teams')->each(function ($media) {
            $media->delete();
        });
        $Team->forceDelete();
    return response()->json([
        'message' => " Force Delete Team By Id Successfully."
    ], 200);
}
}
}
