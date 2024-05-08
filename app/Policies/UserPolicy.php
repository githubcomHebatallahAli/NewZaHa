<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function showAllOrders(User $user, $id)
    {
        if ($user->isAdmin == 1) {
            return true;
        }

        if ($id instanceof User) {
            $id = $id->id;
        }

        return $user->id == $id;
    }
}





