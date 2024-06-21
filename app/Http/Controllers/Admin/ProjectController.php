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


        // $userIdsWithPrices = [];
        // foreach ($request->user_ids as $userId => $price) {
        //     $userIdsWithPrices[$userId] = ['price' => $price];
        // }
        // $project->users()->attach($userIdsWithPrices);

        // $projectWithUsers = Project::with('users')->find($project->id);

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

        // التحقق من أن user_ids موجودة ومصفوفة
        if ($request->has('user_ids') && is_array($request->user_ids)) {
            $userIdsWithPrices = [];
            foreach ($request->user_ids as $user) {
                $userIdsWithPrices[$user['user_id']] = ['price' => $user['price']];
            }
            $project->users()->attach($userIdsWithPrices);
        } else {
            return response()->json(['message' => 'Invalid user_ids format'], 400);
        }

        $projectWithUsers = Project::with('users')->find($project->id);

        return response()->json([
            'data' => new ProjectResource($projectWithUsers),
            'message' => "Users added to Project Successfully."
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

        // البحث عن المشروع بناءً على الرقم المعرف
        $project = Project::findOrFail($id);

        // التأكد مما إذا كان المشروع موجودًا
        if (!$project) {
            return response()->json([
                'message' => 'Project not found.'
            ], 404);
        }

        // تحديث بيانات المشروع باستخدام البيانات المرسلة في الطلب
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

        // التعامل مع الصور المرفقة إذا كان هناك
        if ($request->hasFile('imgProject')) {

            // حذف الصور القديمة إذا كانت موجودة
            if ($project->imgProject) {
                $oldImgProjects = json_decode($project->imgProject, true);
                foreach ($oldImgProjects as $oldImgProject) {
                    if (\Storage::disk('public')->exists($oldImgProject)) {
                        \Storage::disk('public')->delete($oldImgProject);
                    }
                }
            }

            // رفع الصور الجديدة وتخزين مساراتها
            $imgProjectPaths = [];
            foreach ($request->file('imgProject') as $imgProject) {
                $imgProjectPath = $imgProject->store(Project::storageFolder, 'public');
                $imgProjectPaths[] = $imgProjectPath;
            }
            $project->imgProject = json_encode($imgProjectPaths);

        } elseif ($request->has('imgProject') && $request->imgProject === null) {
            // حالة إذا تم حذف الصورة
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

        // حفظ التغييرات
        $project->save();

        // إعداد الاستجابة بنجاح التحديث
        return response()->json([
            'data' => new ProjectResource($project),
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

