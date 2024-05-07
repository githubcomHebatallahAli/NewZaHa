<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;


    // public function showAllOrders(User $user, User $requestedUser)
    // {




    // }

    public function showAllOrders(User $user, $id)
    {
        // Check if the user is an admin
        if ($user->isAdmin == 1) {
            // Admins can see all orders
            return true;
        }

        // Regular users can see their own orders
        return $user->id == $id;
    }
    }





