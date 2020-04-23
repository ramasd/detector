<?php

namespace App\Repositories;

use App\Project;

class ProjectRepository
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
    public function findOrFail($id)
    {
        return $this->project->findOrFail($id);
    }

    /**
     * @param array $attributes
     * @param $id
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        return $this->project->findOrFail($id)->update($attributes);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->project->findOrFail($id)->delete();
    }

    /**
     * @param $quantity
     * @return mixed
     */
    public function getProjectsForCheck($quantity)
    {
        return $this->project->active()->notChecked()->checkTime()->take($quantity)->get();
    }

    /**
     * @param $project
     * @return mixed
     */
    public function projectHasLog($project)
    {
        return $project->logs()->exists();
    }
}
