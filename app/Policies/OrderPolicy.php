<?php

namespace App\Policies;
use App\Models\User;

use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy

{
    use HandlesAuthorization;

    // public function showAll(User $user): bool
    // {
    //     return $user->isAdmin == 1 || $user->id ;
    // }
    public function showAll(User $user, User $requestedUser)
    {

        if ($user->isAdmin()) {
            return true;
        }
        return $user->id === $requestedUser->id;
    }




    // public function showAll(User $user)
    // {
    //     return $user->isAdmin();
    // }


    public function create(User $user): bool
    {
        return true;
    }


    public function show(User $user, Order $order): bool
    {
        return $user->isAdmin == 1 || $user->id === $order->user_id;
    }

    public function edit(User $user, Order $order): bool
    {
        // return $user->id === $order->user_id;
        return $user->isAdmin == 1 || $user->id === $order->user_id;
    }


    public function update(User $user, Order $order): bool
    {
        return $user->isAdmin == 1 || $user->id === $order->user_id;
    }


    public function forceDelete(User $user, Order $order): bool
    {
        return $user->isAdmin == 1 || $user->id === $order->user_id;
    }
}
