<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;
    public function showAll(User $user): bool
    {
        return true;
    }


    public function create(User $user): bool
    {
        return true;
    }


    public function show(User $user, Comment $comment): bool
    {
        return $user->isAdmin == 1 || $user->id === $comment->user_id;
    }

    public function edit(User $user, Comment $comment): bool
    {
        return $user->isAdmin == 1 || $user->id === $comment->user_id;
    }


    public function update(User $user, Comment $comment): bool
    {
        return $user->isAdmin == 1 || $user->id === $comment->user_id;
    }


    public function forceDelete(User $user, Comment $comment): bool
    {
        return $user->isAdmin == 1 || $user->id === $comment->user_id;
    }
}
