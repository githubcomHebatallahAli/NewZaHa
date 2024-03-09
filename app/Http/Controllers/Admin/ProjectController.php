<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\UserProject;
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
    $project = Project::create([
        'nameProject' => $request->nameProject,
        'skills' => $request->skills,
        'description' => $request->description,

    ]);
    $userProject = UserProject::create([
        'user_id' => $request->user_id,
        'project_id' => $project->id,
        'numberSales' => $request->numberSales,
        'price' => $request->price,
        'startingDate' => $request->startingDate,
        'endingDate' => $request->endingDate,
        'nameOfTeam' => $request->nameOfTeam,
    ]);
    $project->users()->sync([$request->user_id]);

    $project->addMediaFromRequest('imgProject')->toMediaCollection('Projects');
    $project->addMediaFromRequest('url')->toMediaCollection('Projects');

    $projectWithPivot = Project::with(['users' => function ($query) use ($request) {
        $query->where('user_id', $request->user_id);
    }])->find($project->id);
    return response()->json([
        'project' =>new ProjectResource($projectWithPivot),
        'message' => "Create Project Successfully."
    ], 201);
    }


    public function show(string $id)
    {
        $project =Project::with('users')->find($id);
        return response()->json([
         'project' =>new ProjectResource($project),
         'message' => " Show Project By Id Successfully."
     ], 200);
    }

    public function edit(string $id)
    {
        $project =Project::with('users')->find($id);
        return response()->json([
         'project' =>new ProjectResource($project),
         'message' => " Edit Project By Id Successfully."
     ], 200);
    }


    public function update(ProjectRequest $request, string $id)
    {
        $project =Project::findOrFail($id);
        $project->update([
            'nameProject' => $request->nameProject,
            'skills' => $request->skills,
        ]);
        UserProject::updateOrCreate([
            'user_id' => $request->user_id,
            'project_id' => $project->id,
            'numberSales' => $request->numberSales,
            'price' => $request->price,
            'startingDate' => $request->startingDate,
            'endingDate' => $request->endingDate,
            'nameOfTeam' => $request->nameOfTeam,
        ]);
        $project->users()->sync([$request->user_id]);
        $project->clearMediaCollection('Projects');
        if ($request->hasFile('imgProject')) {
        $project->addMediaFromRequest('imgProject')->toMediaCollection('Projects');
    }
    if ($request->hasFile('url')) {
        $project->addMediaFromRequest('url')->toMediaCollection('Projects');
    }
    $projectWithPivot = Project::with(['users' => function ($query) use ($request) {
        $query->where('user_id', $request->user_id);
    }])->find($project->id);
        return response()->json([
            'project' =>new ProjectResource($projectWithPivot),
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
        $Projects=Project::onlyTrashed()->with('users')->get();
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
