<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {
        return $user->isAdmin();
    }

    public function edit(User $user, User $editedUser): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $editedUser->id;
    }

    public function updateProfile(User $user, User $updatedUser): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $updatedUser->id;
    }

    public function updatePassword(User $user, User $updatedUser): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $updatedUser->id;
    }

    public function showReservations(User $user, User $showReservationsForUser): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $showReservationsForUser->id;
    }

    public function delete(User $user, User $userToDelete): bool
    {
        if ($user->isAdmin() && !$userToDelete->isAdmin()) {
            return true;
        }

        return $user->id === $userToDelete->id;
    }
}
