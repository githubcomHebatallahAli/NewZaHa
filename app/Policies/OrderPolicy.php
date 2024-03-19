<?php

namespace App\Policies;
use App\Models\User;

use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy

{
    use HandlesAuthorization;
    public function create(User $user): bool
    {
        return true;
    }


    public function show(User $user, Order $order): bool
    {
        return true;
    }

    public function edit(User $user, Order $order): bool
    {
        return $user->id === $order->user_id;
    }


    public function update(User $user, Order $order): bool
    {
        return $user->id === $order->user_id;
    }


    public function forceDelete(User $user, Order $order): bool
    {
        return $user->isAdmin == 1 || $user->id === $order->user_id;
    }
}
