<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\UserProject;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;

class ProjectController extends Controller
{
    public function showAll()
    {
        $this->authorize('manage_users');
        $projects= Project::with('users')->get();
        return response()->json([
            'data' =>ProjectResource::collection($projects),
            'message' => "Show All Projects Successfully."
        ]);
    }

    public function create(ProjectRequest $request)
    {
        $this->authorize('manage_users');
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
        'data' =>new ProjectResource($projectWithPivot),
        'message' => "Create Project Successfully."
    ]);

}

    public function show(string $id)
    {
        $this->authorize('manage_users');
        $project =Project::with('users')->find($id);
    if (!$project) {
        return response()->json([
            'message' => 'Project not found.'
        ], 404);
    }
    return response()->json([
        'data' => new ProjectResource($project),
        'message' => "Show Project By Id Successfully."
    ]);

    }

    public function edit(string $id)
    {
        $this->authorize('manage_users');
        $project =Project::with('users')->find($id);
        if (!$project) {
            return response()->json([
                'message' => 'Project not found.'
            ], 404);
        }
        return response()->json([
         'data' =>new ProjectResource($project),
         'message' => " Edit Project By Id Successfully."
     ]);
    }


    public function update(ProjectRequest $request, string $id)
    {
        $this->authorize('manage_users');
        $project =Project::findOrFail($id);
        if (!$project) {
            return response()->json([
                'message' => 'Project not found.'
            ], 404);
        }
        $project->update([
            'nameProject' => $request->nameProject,
            'skills' => $request->skills,
            'description' => $request->description,
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
            'data' =>new ProjectResource($projectWithPivot),
            'message' => " Update Project By Id Successfully."
        ]);
    }


    public function destroy(string $id)
    {
        $this->authorize('manage_users');
        $Project =Project::find($id);
        if (!$Project) {
            return response()->json([
                'message' => "Project not found."
            ], 404);
        }
        $Project->delete($id);
        return response()->json([
            'data' =>new ProjectResource($Project),
            'message' => " Soft Delete Project By Id Successfully."
        ]);

    }
    public function showDeleted()
    {
        $this->authorize('manage_users');
        $Projects=Project::onlyTrashed()->with('users')->get();
        return response()->json([
            'data' =>ProjectResource::collection($Projects),
            'message' => "Show Deleted Project Successfully."
        ]);
    }


    public function restore(string $id)
    {
        $this->authorize('manage_users');
        $Project=Project::withTrashed()->where('id',$id)->restore();
        return response()->json([
            'message' => " Restore Project By Id Successfully."
        ]);
    }

    public function forceDelete(string $id)
    {
        $this->authorize('manage_users');
        $Project=Project::withTrashed()->where('id',$id)->first();
        if (!$Project) {
            return response()->json([
                'message' => "Project not found."
            ], 404);
        }
        if ($Project) {
            $Project->getMedia('Projects')->each(function ($media) {
                $media->delete();
            });
            $Project->forceDelete();
        return response()->json([
            'message' => " Force Delete Project By Id Successfully."
        ]);
    }
    }
}
