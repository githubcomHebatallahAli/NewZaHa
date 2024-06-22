<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\UserProject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function showAll()
    {
        $this->authorize('manage_users');
        $projects= Project::with('users')->get();
        return response()->json([
            'data' =>ProjectResource::collection($projects),
            'message' => "Show All Users With Projects Successfully."
        ]);
    }

    public function create(ProjectRequest $request)
    {
        $this->authorize('manage_users');

        $project = Project::create([
            'nameProject' => $request->nameProject,
            'skills' => $request->skills,
            'description' => $request->description,
            'numberOfSales' => $request->numberOfSales,
            'saleType'=> $request->saleType,
            'urlProject' => $request->urlProject,
            'startingDate' => $request->startingDate,
            'endingDate' => $request->endingDate,
            'team' => $request->team,

        ]);
        if ($request->hasFile('imgProject')) {
            $imgProjectPaths = [];
            foreach ($request->file('imgProject') as $imgProject) {
                $imgProjectPath = $imgProject->store(Project::storageFolder);
                $imgProjectPaths[] = $imgProjectPath;
            }
             $project->imgProject = json_encode($imgProjectPaths);
             $project->save();
        }

        return response()->json([
            'data' => new ProjectResource($project),
            'message' => "Create Project Successfully."
        ]);
    }

    public function addUsersToProject(Request $request, $projectId)
    {
        $this->authorize('manage_users');

        $project = Project::find($projectId);

        if (!$project) {
            return response()->json([
                'message' => 'Project not found'
            ], 404);
        }

        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'price' => 'required|integer',
        ]);

        $user_id = $request->input('user_id');
        $price = $request->input('price');

        $project->users()->attach($user_id, ['price' => $price]);

        $projectWithUsers = Project::with('users')->find($project->id);

        return response()->json([
            'data' => new ProjectResource($projectWithUsers),
            'message' => "User added to Project Successfully."
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

        $project = Project::findOrFail($id);

        if (!$project) {
            return response()->json([
                'message' => 'Project not found.'
            ], 404);
        }

        $project->update([
            'nameProject' => $request->nameProject,
            'skills' => $request->skills,
            'description' => $request->description,
            'numberOfSales' => $request->numberOfSales,
            'saleType'=> $request->saleType,
            'urlProject' => $request->urlProject,
            'startingDate' => $request->startingDate,
            'endingDate' => $request->endingDate,
            'team' => $request->team,
        ]);
        if ($request->hasFile('imgProject')) {

            if ($project->imgProject) {
                $oldImgProjects = json_decode($project->imgProject, true);
                foreach ($oldImgProjects as $oldImgProject) {
                    if (\Storage::disk('public')->exists($oldImgProject)) {
                        \Storage::disk('public')->delete($oldImgProject);
                    }
                }
            }

            $imgProjectPaths = [];
            foreach ($request->file('imgProject') as $imgProject) {
                $imgProjectPath = $imgProject->store(Project::storageFolder, 'public');
                $imgProjectPaths[] = $imgProjectPath;
            }
            $project->imgProject = json_encode($imgProjectPaths);

        } elseif ($request->has('imgProject') && $request->imgProject === null) {

            if ($project->imgProject) {
                $oldImgProjects = json_decode($project->imgProject, true);
                foreach ($oldImgProjects as $oldImgProject) {
                    if (\Storage::disk('public')->exists($oldImgProject)) {
                        \Storage::disk('public')->delete($oldImgProject);
                    }
                }
                $project->imgProject = null;
            }
        }
        $project->save();
        return response()->json([
            'data' => new ProjectResource($project),
            'message' => "Update Project Successfully."
        ]);
    }

    public function updateUsersInProject(Request $request, $projectId)
    {
        $this->authorize('manage_users');

        $project = Project::find($projectId);

        if (!$project) {
            return response()->json([
                'message' => 'Project not found'
            ], 404);
        }


        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'price' => 'required|integer',
        ]);

        $userId = $request->input('user_id');
        $price = $request->input('price');

        $project->users()->syncWithoutDetaching([
            $userId => ['price' => $price]]);

        $projectWithUsers = Project::with('users')->find($project->id);

        return response()->json([
            'data' => new ProjectResource($projectWithUsers),
            'message' => "User updated in Project Successfully."
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
            $Project->forceDelete();
        return response()->json([
            'message' => " Force Delete Project By Id Successfully."
        ]);
    }
    }

