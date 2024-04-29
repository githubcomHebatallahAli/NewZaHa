<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobPolicy
{
    use HandlesAuthorization;
    public function create(User $user): bool
    {
        return true;
    }


    public function show(User $user, Job $job): bool
    {
        return $user->isAdmin == 1 || $user->id === $job->user_id;
    }

    public function edit(User $user, Job $job): bool
    {
        return $user->isAdmin == 1 || $user->id === $job->user_id;
    }


    public function update(User $user, Job $job): bool
    {
        return $user->isAdmin == 1 || $user->id === $job->user_id;
    }


    public function forceDelete(User $user, Job $job): bool
    {
        return $user->isAdmin == 1 || $user->id === $job->user_id;
    }
}
