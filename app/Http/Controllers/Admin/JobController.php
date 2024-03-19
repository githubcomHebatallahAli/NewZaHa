<?php

namespace App\Http\Controllers\Admin;

use App\Models\Job;
use Illuminate\Http\Request;
use App\Http\Requests\JobRequest;
use App\Http\Resources\JobResource;
use App\Http\Controllers\Controller;

class JobController extends Controller
{
    public function showAll()
    {
        $this->authorize('manage_users');
        $Jobs = Job::with('user')->get();
        return response()->json([
            'data' => JobResource::collection($Jobs),
            'message' => "Show All Job Successfully."
        ]);
    }


    public function create(JobRequest $request)
    {
        $this->authorize('manage_users');
           $Job =Job::create ([
                'address'  => $request->address,
                'phoneNumber'  => $request->phoneNumber,
                'qualification'  => $request->qualification,
                'job'  => $request->job,
                'yearsOfExperience'  => $request->yearsOfExperience,
                'skills'  => $request->skills,
                'user_id' => $request->user_id,
            ]);
           $Job->save();
           return response()->json([
            'data' =>new JobResource($Job),
            'message' => "Job Created Successfully."
        ]);
        }


    public function show(string $id)
    {
        $this->authorize('manage_users');
        $Jobs = Job::with('user')->find($id);
        if (!$Jobs) {
            return response()->json([
                'message' => "Job not found."
            ], 404);
        }
        return response()->json([
            'data' =>new JobResource($Jobs),
            'message' => "Show Job By Id Successfully."
        ]);
    }

    public function edit(string $id)
    {
        $this->authorize('manage_users');
        $Jobs = Job::with('user')->find($id);
        if (!$Jobs) {
            return response()->json([
                'message' => "Job not found."
            ], 404);
        }
        return response()->json([
            'data' =>new JobResource($Jobs),
            'message' => "Edit Job By ID Successfully."
        ]);

    }

    public function update(JobRequest $request, string $id)
    {
        $this->authorize('manage_users');
       $Job =Job::findOrFail($id);
       if (!$Job) {
        return response()->json([
            'message' => "Job not found."
        ], 404);
    }

       $Job->update([
        'address'  => $request->address,
        'phoneNumber'  => $request->phoneNumber,
        'qualification'  => $request->qualification,
        'job'  => $request->job,
        'yearsOfExperience'  => $request->yearsOfExperience,
        'skills'  => $request->skills,
        'user_id' => $request->user_id,
        ]);

       $Job->save();
       return response()->json([
        'data' =>new JobResource($Job),
        'message' => " Update Job By Id Successfully."
    ]);
}

public function destroy(string $id){
    $this->authorize('manage_users');
    $Job =Job::find($id);
    if (!$Job) {
        return response()->json([
            'message' => "Job not found."
        ], 404);
    }

    $Job->delete($id);
    return response()->json([
        'data' =>new JobResource($Job),
        'message' => " Soft Delete Job By Id Successfully."
    ]);
}

public function showDeleted(){
    $this->authorize('manage_users');
    $Jobs=Job::onlyTrashed()->get();
    return response()->json([
        'data' =>JobResource::collection($Jobs),
        'message' => "Show Deleted Job Successfully."
    ]);
}

public function restore(string $id){
    $this->authorize('manage_users');
    $Job=Job::withTrashed()->where('id',$id)->restore();
    return response()->json([
        'message' => " Restore Job By Id Successfully."
    ]);
}
public function forceDelete(string $id){
    $this->authorize('manage_users');
    $Job=Job::withTrashed()->where('id',$id)->forceDelete();
    return response()->json([
        'message' => " Force Delete Job By Id Successfully."
    ]);
}
}
