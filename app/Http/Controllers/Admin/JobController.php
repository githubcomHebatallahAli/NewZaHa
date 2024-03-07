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
        $Jobs = Job::with('user')->get();
        return response()->json([
            'Jobs' => JobResource::collection($Jobs),
            'message' => "Show All Job Successfully."
        ], 200);
    }


    public function create(JobRequest $request)
    {
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
            'Job' =>new JobResource($Job),
            'message' => "Job Created Successfully."
        ], 200);

        }


    public function show(string $id)
    {
        $Jobs = Job::with('user')->find($id);
        return response()->json([
            'Jobs' =>new JobResource($Jobs),
            'message' => "Show Job By Id Successfully."
        ], 200);
    }

    public function edit(string $id)
    {
        $Jobs = Job::with('user')->find($id);
        return response()->json([
            'Jobs' =>new JobResource($Jobs),
            'message' => "Edit Job By ID Successfully."
        ], 200);

    }

    public function update(JobRequest $request, string $id)
    {
       $Job =Job::findOrFail($id);
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
        'Job' =>new JobResource($Job),
        'message' => " Update  Best Job By Id Successfully."
    ], 200);
}

public function destroy(string $id){
    $Job =Job::find($id);
    $Job->delete($id);
    return response()->json([
        'Job' =>new JobResource($Job),
        'message' => " Soft Delete Job By Id Successfully."
    ], 200);
}
public function showDeleted(){
    $Jobs=Job::onlyTrashed()->get();
    return response()->json([
        'Job' =>JobResource::collection($Jobs),
        'message' => "Show Deleted Job Successfully."
    ], 200);
}

public function restore(string $id){
    $Job=Job::withTrashed()->where('id',$id)->restore();
    return response()->json([
        'message' => " Restore Job By Id Successfully."
    ], 200);
}
public function forceDelete(string $id){
    $Job=Job::withTrashed()->where('id',$id)->forceDelete();
    return response()->json([
        'message' => " Force Delete Job By Id Successfully."
    ], 200);
}
}
