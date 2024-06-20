<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\UserProject;
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
        ]);

        $userProject = UserProject::create([
            'user_id' => $request->user_id,
            'project_id' => $project->id,
            'numberSales' => $request->numberSales,
            'price' => $request->price,
            'urlProject' => $request->urlProject,
            'startingDate' => $request->startingDate,
            'endingDate' => $request->endingDate,
            'nameOfTeam' => $request->nameOfTeam,
        ]);

        if ($request->hasFile('imgProject')) {
            $imgProjectPaths = [];
            foreach ($request->file('imgProject') as $imgProject) {
                $imgProjectPath = $imgProject->store(UserProject::storageFolder);
                $imgProjectPaths[] = $imgProjectPath;
            }
            $userProject->imgProject = json_encode($imgProjectPaths);
            $userProject->save();
        }

        $project->users()->sync([$request->user_id]);

        $projectWithPivot = Project::with(['users' => function ($query) use ($request) {
            $query->where('user_id', $request->user_id);
        }])->find($project->id);

        return response()->json([
            'data' => new ProjectResource($projectWithPivot),
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

        $userProject = UserProject::where('project_id', $project->id)
                                  ->where('user_id', $request->user_id)
                                  ->firstOrFail();

        $userProject->update([
            'numberSales' => $request->numberSales,
            'price' => $request->price,
            'urlProject' => $request->urlProject,
            'startingDate' => $request->startingDate,
            'endingDate' => $request->endingDate,
            'nameOfTeam' => $request->nameOfTeam,
        ]);
// التحقق من وجود صور جديدة
if ($request->hasFile('imgProject')) {
    // حذف الصور القديمة إذا كانت موجودة
    if ($userProject->imgProject) {
        $oldImgProjects = json_decode($userProject->imgProject, true);
        foreach ($oldImgProjects as $oldImgProject) {
            if (\Storage::disk('public')->exists($oldImgProject)) {
                \Storage::disk('public')->delete($oldImgProject);
            }
        }
    }

    // رفع الصور الجديدة
    $imgProjectPaths = [];
    foreach ($request->file('imgProject') as $imgProject) {
        $imgProjectPath = $imgProject->store(UserProject::storageFolder, 'public');
        $imgProjectPaths[] = $imgProjectPath;
    }
    $userProject->imgProject = json_encode($imgProjectPaths);

} elseif ($request->has('imgProject') && $request->imgProjects === null) {
    // حذف الصور القديمة إذا تم تعيين الحقل imgProjects إلى null
    if ($userProject->imgProject) {
        $oldImgProjects = json_decode($userProject->imgProject, true);
        foreach ($oldImgProjects as $oldImgProject) {
            if (\Storage::disk('public')->exists($oldImgProject)) {
                \Storage::disk('public')->delete($oldImgProject);
            }
        }
        $userProject->imgProject = null;
    }
}

$userProject->save();

    $project->users()->sync([$request->user_id]);

    $projectWithPivot = Project::with(['users' => function ($query) use ($request) {
        $query->where('user_id', $request->user_id);
    }])->find($project->id);

    return response()->json([
        'data' => new ProjectResource($projectWithPivot),
        'message' => "Update Project Successfully."
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

