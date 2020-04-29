<?php

namespace App\Services;

use App\Log;
use App\Mail\ProjectErrorMail;
use App\Mail\ProjectNoErrorMail;
use App\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Repositories\ProjectRepository;
use App\Services\Interfaces\ProjectServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class ProjectService implements ProjectServiceInterface
{
    /**
     * @var ProjectRepository
     */
    protected $projectRepository;

    /**
     * ProjectService constructor.
     * @param ProjectRepositoryInterface $projectRepositoryInterface
     */
    public function __construct(ProjectRepositoryInterface $projectRepositoryInterface)
    {
        $this->projectRepository = $projectRepositoryInterface;
    }

    /**
     * @return Project[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return $this->projectRepository->getCurrentUserProjects()->sortDesc();
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function store(array $attributes)
    {
        $attributes['user_id'] = auth()->id();

        return $this->projectRepository->create($attributes);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findProjectById(int $id)
    {
        return $this->projectRepository->findOrFail($id);
    }

    /**
     * @param array $attributes
     * @param int $id
     * @return mixed
     */
    public function updateProject(array $attributes, int $id)
    {
        return $this->projectRepository->update($attributes, $id);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        return $this->projectRepository->delete($id);
    }

    /**
     * @param int $quantity
     * @return mixed
     */
    public function getProjectsForCheck(int $quantity)
    {
        return $this->projectRepository->getProjectsForCheck($quantity);
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
        $data['redirect_detected'] = $this->isRedirect($url);
        $data['server_error'] = $response->serverError();
        $data['client_error'] = $response->clientError();

        return $data;
    }

    /**
     * @param $url
     * @return bool
     */
    public function isRedirect($url)
    {
        $response = Http::withOptions(['allow_redirects' => false])->get($url);

        return $response->redirect();
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

        if ($this->projectRepository->projectHasLog($project)) {
            $latest_data = $project->logs()->latest('created_at')->firstOrFail()->data;
            $project_latest_log_data = json_decode($latest_data, true);
        }

        return $project_latest_log_data;
    }

    /**
     * @param array $project_latest_log_data
     * @return mixed|null
     */
    public function getProjectLatestLogStatusOrError($project_latest_log_data)
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
    public function resetProjectsChecked()
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

    /**
     * @param $projects
     * @param $quantity
     */
    public function checkProjectsAndSendEmails($projects, $quantity)
    {
        foreach ($projects as $project) {
            $request_data = $this->tryToGetRequestData($project);
            $project_latest_log_data = $this->getProjectLatestLogData($project);
            $latest_status = $this->getProjectLatestLogStatusOrError($project_latest_log_data);
            $json = json_encode($request_data);

            Log::create([
                'project_id' => $project->id,
                'user_id' => $project->user_id,
                'data' => $json,
            ]);

            $this->projectRepository->update([
                'last_check' => Carbon::now(),
                'checked' => 1,
            ], $project->id);

            $this->sendEmailIfStatusChange($request_data, $project, $latest_status);
        }
        if (count($this->getProjectsForCheck($quantity)) == 0) {
            $this->resetProjectsChecked();
        }
    }

    /**
     * @param $request
     * @param null $status
     * @return mixed
     */
    public function getProjectAttributes($data, $status = null)
    {
        $attributes = $data;
        $attributes['status'] = $status;

        return $attributes;
    }

    /**
     * @param $project
     * @param $data
     * @param null $status
     */
    public function getAttributesAndUpdateProject($project, $data, $status = null)
    {
        $attributes = $this->getProjectAttributes($data, $status);
        $this->updateProject($attributes, $project->id);
    }

    /**
     * @return Carbon
     */
    public function getSessionStartTime()
    {
        return Carbon::now();
    }

    /**
     * @param $sessionStartTime
     * @return mixed
     */
    public function getSessionTotalDuration($sessionStartTime)
    {
        return $sessionStartTime->diffInMilliseconds(Carbon::now());
    }
}
