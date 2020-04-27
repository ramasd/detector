<?php

namespace App\Policies;

use App\Log;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LogPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param User $user
     * @param Log $log
     * @return bool
     */
    public function edit(User $user, Log $log)
    {
        return $user->id === $log->user_id;
    }

    /**
     * Determine if the given log can be updated by the user.
     *
     * @param User $user
     * @param Log $log
     * @return bool
     */
    public function update(User $user, Log $log)
    {
        return $user->id === $log->user_id;
    }

    /**
     * Determine if the given log can be deleted by the user.
     *
     * @param User $user
     * @param Log $log
     * @return bool
     */
    public function delete(User $user, Log $log)
    {
        return $user->id === $log->user_id;
    }
}
