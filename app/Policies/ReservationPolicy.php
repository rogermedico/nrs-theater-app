<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReservationPolicy
{
    use HandlesAuthorization;

    public function show(User $user, User $reservationsUser)
    {
        if ($user->isAdmin())
        {
            return true;
        }

        return $user->id === $reservationsUser->id;
    }

//    public function edit(User $user, User $editedUser)
//    {
//        if($user->isAdmin())
//        {
//            return true;
//        }
//
//        return $user->id === $editedUser->id;
//    }

//    public function updateProfile(User $user, User $updatedUser)
//    {
//        if($user->isAdmin())
//        {
//            return true;
//        }
//
//        return $user->id === $updatedUser->id;
//    }
//
//    public function updatePassword(User $user, User $updatedUser)
//    {
//        if($user->isAdmin())
//        {
//            return true;
//        }
//
//        return $user->id === $updatedUser->id;
//    }
//
    public function delete(User $user, Reservation $reservation)
    {
        if($user->isAdmin())
        {
            return true;
        }

        return $user->id === $reservation->user_id;
    }
}
