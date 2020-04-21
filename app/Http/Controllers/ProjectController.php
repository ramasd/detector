<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Log;
use App\Mail\ProjectErrorMail;
use App\Mail\ProjectNoErrorMail;
use App\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class ProjectController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $projects = Project::all()->sortByDesc('id');

        return view('projects.index', compact('projects'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * @param StoreProjectRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProjectRequest $request)
    {
        Project::create([
            'name' => $request->name,
            'url' => $request->url,
            'status' => $request->status,
            'user_id' => auth()->id(),
            'check_frequency' => 20,
            'last_check' => Carbon::now(),
        ]);

        return redirect()->route('projects.index')->with('success', 'Project has been created successfully!');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $project = Project::findOrFail($id);

        return view('projects.show', compact('project'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $project = Project::findOrFail($id);

        return view('projects.edit', compact('project'));
    }

    /**
     * @param UpdateProjectRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateProjectRequest $request, $id)
    {
        $project = Project::findOrFail($id);

        $project->update([
            'name' => $request->name,
            'url' => $request->url,
            'status' => $request->status,
        ]);

        return redirect()->route('projects.index')->with('success', 'Project has been updated successfully!');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project has been deleted successfully!');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkProjects()
    {
        $projects = Project::active()->notChecked()->checkTime()->take(config('project.quantity'))->get();
dd($projects);
        foreach ($projects as $project) {
            try {
                $request_data = $this->getRequestData($project->url);
            } catch (\Exception $e) {
                $request_data = ['error_message' => $e->getMessage()];
            }

            $project_last_log_data = $this->getProjectLastLogData($project);

            $last_status = $this->getProjectLastLogStatusOrError($project_last_log_data);

            $json = json_encode($request_data);

            Log::create([
                'project_id' => $project->id,
                'data' => $json,
            ]);

            $project->update([
                'last_check' => Carbon::now()->addHours(3),
                'checked' => 1,
            ]);

            if (isset($request_data['status'])) {
                if ($request_data['status'] != $last_status AND $request_data['status'] >= 400) {
                    Mail::to('receiver@receiver.com')->send(new ProjectErrorMail($project));
                }
                if ($last_status AND $request_data['status'] != $last_status AND $request_data['status'] < 400) {
                    Mail::to('receiver@receiver.com')->send(new ProjectNoErrorMail($project));
                }
            }
            if (isset($request_data['error_message'])) {
                if ($request_data['error_message'] != $last_status) {
                    Mail::to('receiver@receiver.com')->send(new ProjectErrorMail($project));
                }
            }
        }

        if (count(Project::active()->notChecked()->checkTime()->get()) == 0) {
            Project::query()->update(['checked' => null]);
            return redirect()->route('logs.index')->with('success', 'All projects are checked!');
        }

        return redirect()->route('logs.index')->with('success', 'Logs created successfully!');
    }

    /**
     * @param $url
     * @return array
     */
    public function getRequestData($url)
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
     * @param $project
     * @return array|mixed
     */
    public function getProjectLastLogData($project)
    {
        $project_last_log_data = [];

        if ($this->projectHasLog($project)) {
            $last_data = $project->logs()->latest('created_at')->firstOrFail()->data;
            $project_last_log_data = json_decode($last_data, true);
        }

        return $project_last_log_data;
    }

    /**
     * @param $project
     * @return mixed
     */
    public function projectHasLog($project)
    {
        return $project->logs()->exists();
    }

    /**
     * @param $project_last_log_data
     * @return |null
     */
    public function getProjectLastLogStatusOrError($project_last_log_data)
    {
        $last_status = null;

        if (isset($project_last_log_data['status'])) {
            $last_status = $project_last_log_data['status'];
        }
        if (isset($project_last_log_data['error_message'])) {
            $last_status = $project_last_log_data['error_message'];
        }

        return $last_status;
    }

}
