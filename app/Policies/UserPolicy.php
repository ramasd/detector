<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
     * @param User $current_user
     * @param User $user
     * @return bool
     */
    public function show(User $current_user, User $user)
    {
        $admin = false;

        foreach ($current_user->roles as $role) {
            if ($role->name == "admin") {
                $admin = true;
            }
        }
        return $current_user->id === $user->id OR $admin;
    }

    /**
     * @param User $current_user
     * @param User $user
     * @return bool
     */
    public function edit(User $current_user, User $user)
    {
        return $current_user->id === $user->id;
    }

    /**
     * @param User $current_user
     * @param User $user
     * @return bool
     */
    public function update(User $current_user, User $user)
    {
        return $current_user->id === $user->id;
    }
}
