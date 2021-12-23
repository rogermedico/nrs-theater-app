<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function updateProfile(User $user, User $updatedUser)
    {
        if($user->isAdmin())
        {
            return true;
        }

        return $user->id === $updatedUser->id;
    }

    public function updatePassword(User $user, User $updatedUser)
    {
        if($user->isAdmin())
        {
            return true;
        }

        return $user->id === $updatedUser->id;
    }

    public function delete(User $user, User $updatedUser)
    {
        if($user->isAdmin())
        {
            return true;
        }

        return $user->id === $updatedUser->id;
    }
}
