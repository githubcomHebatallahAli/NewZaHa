<?php

namespace App\Http\Controllers\User;

use App\Models\Job;
use App\Models\User;
use App\Mail\NewJobMail;
use App\Mail\JobUpdatedMail;
use App\Mail\WelcomeJobMail;
use App\Http\Requests\JobRequest;
use App\Http\Resources\JobResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Notifications\NewJobNotification;
use App\Notifications\JobUpdatedNotification;

class JobUserController extends Controller
{
    public function create(JobRequest $request)
    {
        $this->authorize('create', Job::class);

           $job =Job::create ([
                'realName' =>$request->realName,
                'address'  => $request->address,
                'phoneNumber'  => $request->phoneNumber,
                'qualification'  => $request->qualification,
                'job'  => $request->job,
                'yearsOfExperience'  => $request->yearsOfExperience,
                'skills'  => $request->skills,
                'user_id' => $request->user()->id,
            ]);
            $job->user->notify(new NewJobNotification($job));
            $admins = User::where('isAdmin', 1)->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new NewJobMail($job));
            }
            // Mail::to($job->user->email)->send(new WelcomeJobMail($job));
           $job->save();
           return response()->json([
            'data' =>new JobResource($job),
            'message' => "Job Created Successfully."
        ]);

        }





    public function show(string $id)
    {
        $job = Job::with('user')->find($id);
        $this->authorize('show', $job);
        if (!$job) {
            return response()->json([
                'message' => "Job not found."
            ], 404);
        }
        return response()->json([
            'data' =>new JobResource($job),
            'message' => "Show Job By Id Successfully."
        ]);
    }

    public function edit(string $id)
    {
        $jobs = Job::with('user')->find($id);
        $this->authorize('edit', $jobs);
        if (!$jobs) {
            return response()->json([
                'message' => "Job not found."
            ], 404);
        }
        return response()->json([
            'data' =>new JobResource($jobs),
            'message' => "Edit Job By ID Successfully."
        ]);

    }

    public function update(JobRequest $request, string $id)
    {
       $job =Job::findOrFail($id);
       $this->authorize('update', $job);
       if (!$job) {
        return response()->json([
            'message' => "Job not found."
        ], 404);
    }

       $job->update([
        'realName' =>$request->realName,
        'address'  => $request->address,
        'phoneNumber'  => $request->phoneNumber,
        'qualification'  => $request->qualification,
        'job'  => $request->job,
        'yearsOfExperience'  => $request->yearsOfExperience,
        'skills'  => $request->skills,
        'user_id' => $request->user()->id,
        ]);
        $job->user->notify(new JobUpdatedNotification($job));
        $admins = User::where('isAdmin', 1)->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new JobUpdatedMail($job));
        }
        Mail::to($job->user->email)->send(new WelcomeJobMail($job));

       $job->save();
       return response()->json([
        'data' =>new JobResource($job),
        'message' => " Update Job By Id Successfully."
    ]);
}

public function forceDelete(string $id){

    $job = Job::withTrashed()->where('id', $id)->first();
    if (!$job) {
        return response()->json([
            'message' => "Job not found."
        ], 404);
    }
    $this->authorize('forceDelete', $job);
    $job->forceDelete();
    return response()->json([
        'message' => " Force Delete Job By Id Successfully."
    ]);
}

}
