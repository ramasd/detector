<?php

namespace App\Services\Interfaces;

use App\Project;

interface ProjectServiceInterface
{
    /**
     * @return Project[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index();

    /**
     * @param array $attributes
     * @return mixed
     */
    public function store(array $attributes);

    /**
     * @param int $id
     * @return mixed
     */
    public function findProjectById(int $id);

    /**
     * @param array $attributes
     * @param int $id
     * @return mixed
     */
    public function updateProject(array $attributes, int $id);

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);

    /**
     * @param int $quantity
     * @return mixed
     */
    public function getProjectsForCheck(int $quantity);

    /**
     * @param string $url
     * @return array
     */
    public function getRequestData(string $url);

    /**
     * @param Project $project
     * @return array
     */
    public function tryToGetRequestData(Project $project);

    /**
     * @param Project $project
     * @return array|mixed
     */
    public function getProjectLatestLogData(Project $project);

    /**
     * @param array $project_latest_log_data
     * @return mixed|null
     */
    public function getProjectLatestLogStatusOrError(array $project_latest_log_data);

    /**
     *
     */
    public function resetProjectsChecked();

    /**
     * @param array $request_data
     * @param $latest_status
     * @param Project $project
     */
    public function sendEmailIfStatusChange(array $request_data, Project $project, $latest_status);

    /**
     * @param $projects
     * @param $quantity
     */
    public function checkProjectsAndSendEmails($projects, $quantity);
}
