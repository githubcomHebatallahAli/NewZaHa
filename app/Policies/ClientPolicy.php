<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Client;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
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


        public function show(User $user, Client $client): bool
        {
            return true;
        }

        public function edit(User $user, Client $client): bool
        {
            return $user->id === $client->user_id;
        }


        public function update(User $user, Client $client): bool
        {
            return $user->id === $client->user_id;
        }


        public function forceDelete(User $user, Client $client): bool
        {
            return $user->isAdmin == 1 || $user->id === $client->user_id;
        }
    }

