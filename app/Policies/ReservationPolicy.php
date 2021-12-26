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

    public function edit(User $user, Reservation $reservation)
    {
        if($user->isAdmin())
        {
            return true;
        }

        return $user->id === $reservation->user_id;
    }

    public function update(User $user, Reservation $reservation)
    {
        if($user->isAdmin())
        {
            return true;
        }

        return $user->id === $reservation->user_id;
    }

    public function delete(User $user, Reservation $reservation)
    {
        if($user->isAdmin())
        {
            return true;
        }

        return $user->id === $reservation->user_id;
    }
}
