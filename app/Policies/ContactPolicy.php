<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Contact;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactPolicy
{
    use HandlesAuthorization;


        public function create(User $user): bool
        {
            return true;
        }


        public function show(User $user, Contact $contact): bool
        {
            return $user->isAdmin == 1 || $user->id === $contact->user_id;
        }

        public function edit(User $user, Contact $contact): bool
        {
            return $user->isAdmin == 1 || $user->id === $contact->user_id;
        }


        public function update(User $user, Contact $contact): bool
        {
            return $user->isAdmin == 1 || $user->id === $contact->user_id;
        }


        public function forceDelete(User $user, Contact $contact): bool
        {
            return $user->isAdmin == 1 || $user->id === $contact->user_id;
        }
    }

