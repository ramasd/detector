<?php

namespace App\Services;

use App\Mail\ProjectErrorMail;
use App\Mail\ProjectNoErrorMail;
use App\Project;
use App\Repositories\ProjectRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class ProjectService
{
    /**
     * @var ProjectRepository
     */
    protected $project;

    /**
     * ProjectService constructor.
     * @param ProjectRepository $project
     */
    public function __construct(ProjectRepository $project)
    {
        $this->project = $project;
    }

    /**
     * @return Project[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return $this->project->all()->sortDesc();
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function store(array $attributes)
    {
        $attributes['user_id'] = auth()->id();
        $attributes['last_check'] = Carbon::now();

        return $this->project->create($attributes);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findProjectById(int $id)
    {
        return $this->project->findOrFail($id);
    }

    /**
     * @param array $attributes
     * @param int $id
     * @return mixed
     */
    public function update(array $attributes, int $id)
    {
        return $this->project->update($attributes, $id);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        return $this->project->delete($id);
    }

    /**
     * @param int $quantity
     * @return mixed
     */
    public function getProjectsForCheck(int $quantity)
    {
        return $this->project->getProjectsForCheck($quantity);
    }

    /**
     * @param string $url
     * @return array
     */
    public function getRequestData(string $url)
    {
        $data = [];

        $response = Http::get($url);

        $data['status'] = $response->status();
        if (isset($response->transferStats)) {
            $data['response_time'] = $response->transferStats->getHandlerStat('namelookup_time') + $response->transferStats->getHandlerStat('connect_time');
            $data['load_time'] = $response->transferStats->getHandlerStat('total_time');
            $data['server_ip'] = $response->transferStats->getHandlerStat('primary_ip');
        }
        $data['redirect_detected'] = $response->redirect();
        $data['server_error'] = $response->serverError();
        $data['client_error'] = $response->clientError();

        return $data;
    }

    /**
     * @param Project $project
     * @return array
     */
    public function tryToGetRequestData(Project $project)
    {
        try {
            $request_data = $this->getRequestData($project->url);
        } catch (\Exception $e) {
            $request_data = ['error_message' => $e->getMessage()];
        }

        return $request_data;
    }

    /**
     * @param Project $project
     * @return array|mixed
     */
    public function getProjectLatestLogData(Project $project)
    {
        $project_latest_log_data = [];

        if ($this->project->projectHasLog($project)) {
            $latest_data = $project->logs()->latest('created_at')->firstOrFail()->data;
            $project_latest_log_data = json_decode($latest_data, true);
        }

        return $project_latest_log_data;
    }

    /**
     * @param array $project_latest_log_data
     * @return mixed|null
     */
    public function getProjectLatestLogStatusOrError(array $project_latest_log_data)
    {
        $latest_status = null;

        if (isset($project_latest_log_data['status'])) {
            $latest_status = $project_latest_log_data['status'];
        }
        if (isset($project_latest_log_data['error_message'])) {
            $latest_status = $project_latest_log_data['error_message'];
        }

        return $latest_status;
    }

    /**
     *
     */
    public function resetChecked()
    {
        Project::query()->update(['checked' => null]);
    }

    /**
     * @param array $request_data
     * @param $latest_status
     * @param Project $project
     */
    public function sendEmailIfStatusChange(array $request_data, Project $project, $latest_status)
    {
        if (isset($request_data['status'])) {
            if ($request_data['status'] != $latest_status AND $request_data['status'] >= 400) {
                Mail::to('receiver@receiver.com')->send(new ProjectErrorMail($project, $request_data));
            }
            if (!is_null($latest_status) AND $request_data['status'] != $latest_status AND $request_data['status'] < 400) {
                Mail::to('receiver@receiver.com')->send(new ProjectNoErrorMail($project, $request_data));
            }
        }
        if (isset($request_data['error_message'])) {
            if ($request_data['error_message'] != $latest_status) {
                Mail::to('receiver@receiver.com')->send(new ProjectErrorMail($project, $request_data));
            }
        }
    }
}
