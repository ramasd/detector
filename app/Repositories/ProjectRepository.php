<?php

namespace App\Repositories;

use App\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;

class ProjectRepository implements ProjectRepositoryInterface
{
    /**
     * @var Project
     */
    protected $project;

    /**
     * ProjectRepository constructor.
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * @return Project[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->project->all();
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        return $this->project->create($attributes);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findOrFail(int $id)
    {
        return $this->project->findOrFail($id);
    }

    /**
     * @param array $attributes
     * @param $id
     * @return mixed
     */
    public function update(array $attributes, int $id)
    {
        return $this->project->findOrFail($id)->update($attributes);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete(int $id)
    {
        return $this->project->findOrFail($id)->delete();
    }

    /**
     * @param $quantity
     * @return mixed
     */
    public function getProjectsForCheck(int $quantity)
    {
        return $this->project->active()->notChecked()->checkTime()->take($quantity)->get();
    }

    /**
     * @param $project
     * @return mixed
     */
    public function projectHasLog(Project $project)
    {
        return $project->logs()->exists();
    }
}
