<?php

namespace App\Repositories\Interfaces;

use App\Project;

Interface ProjectRepositoryInterface
{
    /**
     * @return Project[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes);

    /**
     * @param $id
     * @return mixed
     */
    public function findOrFail(int $id);

    /**
     * @param array $attributes
     * @param $id
     * @return mixed
     */
    public function update(array $attributes, int $id);

    /**
     * @param $id
     * @return mixed
     */
    public function delete(int $id);

    /**
     * @param $quantity
     * @return mixed
     */
    public function getProjectsForCheck(int $quantity);

    /**
     * @param $project
     * @return mixed
     */
    public function projectHasLog(Project $project);
}
