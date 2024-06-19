<?php

namespace App\Http\Controllers\Admin;

use Storage;
use App\Models\Team;
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
                'name' => $request->name,
                'job' => $request->job,
                'skills' => $request->skills,
                'numProject' => $request->numProject,
                'address' => $request->address,
                'phoneNumber' => $request->phoneNumber,
                'qualification' => $request->qualification,
                'dateOfJoin' => $request->dateOfJoin,
                'salary' => $request->salary,
                'user_id' => $request->user_id
            ]);
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store(Team::storageFolder);
                $Team->photo = $photoPath;
            }

            if ($request->hasFile('imgIDCard')) {
                $imgIDCardPath = $request->file('imgIDCard')->store(Team::storageFolder);
                $Team->imgIDCard = $imgIDCardPath;
            }
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
        $this->authorize('manage_users');
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
        $this->authorize('manage_users');
       $Team =Team::findOrFail($id);
       if (!$Team) {
        return response()->json([
            'message' => "Job not found."
        ], 404);
    }
       $Team->update([
        'name' =>$request->name,
        'job' => $request->job,
        'skills' => $request->skills,
        'numProject' => $request->numProject,
        'address' => $request->address,
        'phoneNumber' => $request->phoneNumber,
        'qualification' => $request->qualification,
        'dateOfJoin' => $request->dateOfJoin,
        'salary' => $request->salary,
        'user_id' => $request->user_id,
        ]);
        if ($request->hasFile('photo')) {
            if ($Team->photo && \Storage::exists($Team->photo)) {
                \Storage::delete($Team->photo);
            }
            $photoPath = $request->file('photo')->store(Team::storageFolder);
            $Team->photo = $photoPath;
        }

        // Handle the imgIDCard update
        if ($request->hasFile('imgIDCard')) {
            if ($Team->imgIDCard && \Storage::exists($Team->imgIDCard)) {
                \Storage::delete($Team->imgIDCard);
            }
            $imgIDCardPath = $request->file('imgIDCard')->store(Team::storageFolder);
            $Team->imgIDCard = $imgIDCardPath;
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
    if (!$Team) {
        return response()->json([
            'message' => "Team not found."
        ], 404);
    }
    // if ($Team) {
    //     $Team->getMedia('Teams')->each(function ($media) {
    //         $media->delete();
    //     });
        $Team->forceDelete();
    return response()->json([
        'message' => " Force Delete Team By Id Successfully."
    ]);
}
}

