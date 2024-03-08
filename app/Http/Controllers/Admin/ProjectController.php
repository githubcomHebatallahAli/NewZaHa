<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;

class ProjectController extends Controller
{
    public function showAll()
    {
        $projects= Project::with('users')->get();
        return response()->json([
            'project' =>ProjectResource::collection($projects),
            'message' => "Show All Projects Successfully."
        ], 200);
    }

    public function create(ProjectRequest $request)
    {
        $project = Project::create($request->all());
        $project->users()->attach($request->user_id, [
            'numberSales' => $request->numberSales,
            'price' => $request->price,
            'startingDate' => $request->startingDate,
            'endingDate' => $request->endingDate,
            'nameOfTeam' => $request->nameOfTeam,
        ]);
        $project->addMediaFromRequest('imgProject')->toMediaCollection('Projects');
        $project->addMediaFromRequest('url')->toMediaCollection('Projects');

        return response()->json([
            'project' =>ProjectResource::collection($projects),
            'message' => "Create Project Successfully."
        ], 201);
    }


    public function show(string $id)
    {
        $project =Project::with('user')->find($id);
        return response()->json([
         'project' =>new ProjectResource($project),
         'message' => " Show Project By Id Successfully."
     ], 200);
    }

    public function edit(string $id)
    {
        $project =Project::with('user')->find($id);
        return response()->json([
         'project' =>new ProjectResource($project),
         'message' => " Edit Project By Id Successfully."
     ], 200);
    }


    public function update(ProjectRequest $request, string $id)
    {
        $project->update($request->all());
        $project->users()->syncWithoutDetaching([$request->user_id => [
            'numberSales' => $request->numberSales,
            'price' => $request->price,
            'startingDate' => $request->startingDate,
            'endingDate' => $request->endingDate,
            'nameOfTeam' => $request->nameOfTeam,
        ]]);
        $project->clearMediaCollection('Projects');
        if ($request->hasFile('imgProject')) {
        $project->addMediaFromRequest('imgProject')->toMediaCollection('Projects');
    }
    if ($request->hasFile('url')) {
        $project->addMediaFromRequest('url')->toMediaCollection('Projects');
    }
        return response()->json([
            'project' =>new ProjectResource($project),
            'message' => " Update Project By Id Successfully."
        ], 200);
    }


    public function destroy(string $id)
    {
        $Project =Project::find($id);
        $Project->delete($id);
        return response()->json([
            'Project' =>new ProjectResource($Project),
            'message' => " Soft Delete Project By Id Successfully."
        ], 200);

    }
    public function showDeleted()
    {
        $Projects=Project::onlyTrashed()->with('user')->get();
        return response()->json([
            'Project' =>ProjectResource::collection($Projects),
            'message' => "Show Deleted Project Successfully."
        ], 200);
    }


    public function restore($id)
    {
        $Project=Project::withTrashed()->where('id',$id)->restore();
        return response()->json([
            'message' => " Restore Project By Id Successfully."
        ], 200);
    }

    public function forceDelete($id)
    {
        $Project=Project::withTrashed()->where('id',$id)->first();
        if ($Project) {
            $Project->getMedia('Projects')->each(function ($media) {
                $media->delete();
            });
            $Project->forceDelete();
        return response()->json([
            'message' => " Force Delete Project By Id Successfully."
        ], 200);
    }
    }
}
