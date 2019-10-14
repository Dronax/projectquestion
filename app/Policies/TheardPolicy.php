<?php

namespace App\Policies;

use App\User;
use App\Theard;
use Illuminate\Auth\Access\HandlesAuthorization;

class TheardPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the theard.
     *
     * @param  \App\User  $user
     * @param  \App\Theard  $theard
     * @return mixed
     */
    public function view(User $user, Theard $theard)
    {
        //
    }

    /**
     * Determine whether the user can create theards.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the theard.
     *
     * @param  \App\User  $user
     * @param  \App\Theard  $theard
     * @return mixed
     */
    public function update(User $user, Theard $theard)
    {
        return $theard->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the theard.
     *
     * @param  \App\User  $user
     * @param  \App\Theard  $theard
     * @return mixed
     */
    public function delete(User $user, Theard $theard)
    {
        //
    }
}
