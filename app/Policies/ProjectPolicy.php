<?php

namespace App\Policies;

use App\Project;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
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
     * @param Project $project
     * @return bool
     */
    public function show(User $user, Project $project)
    {
        return $user->id === $project->user_id;
    }

    /**
     * @param User $user
     * @param Project $project
     * @return bool
     */
    public function edit(User $user, Project $project)
    {
        return $user->id === $project->user_id;
    }

    /**
     * Determine if the given project can be updated by the user.
     *
     * @param User $user
     * @param Project $project
     * @return bool
     */
    public function update(User $user, Project $project)
    {
        return $user->id === $project->user_id;
    }

    /**
     * Determine if the given project can be deleted by the user.
     * @param User $user
     * @param Project $project
     * @return bool
     */
    public function delete(User $user, Project $project)
    {
        return $user->id === $project->user_id;
    }
}
