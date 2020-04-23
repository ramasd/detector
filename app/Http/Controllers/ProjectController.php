<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Log;
use App\Services\ProjectService;
use Carbon\Carbon;

class ProjectController extends Controller
{
    /**
     * @var ProjectService
     */
    protected $projectService;

    /**
     * ProjectController constructor.
     * @param ProjectService $projectService
     */
    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $projects = $this->projectService->index();

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
        $attributes = $request->all();
        $this->projectService->create($attributes);

        return redirect()->route('projects.index')->with('success', 'Project has been created successfully!');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $project = $this->projectService->findProjectById($id);

        return view('projects.show', compact('project'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $project = $this->projectService->findProjectById($id);

        return view('projects.edit', compact('project'));
    }

    /**
     * @param UpdateProjectRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateProjectRequest $request, $id)
    {
        $attributes = $request->all();
        $attributes['status'] = $request->status;

        $this->projectService->update($attributes, $id);

        return redirect()->route('projects.index')->with('success', 'Project has been updated successfully!');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->projectService->delete($id);

        return redirect()->route('projects.index')->with('success', 'Project has been deleted successfully!');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkProjects()
    {
        $quantity = config('project.settings.quantity');

        $projects = $this->projectService->getProjectsForCheck($quantity);

        foreach ($projects as $project) {

            $request_data = $this->projectService->tryToGetRequestData($project);

            $project_latest_log_data = $this->projectService->getProjectLatestLogData($project);

            $latest_status = $this->projectService->getProjectLatestLogStatusOrError($project_latest_log_data);

            $json = json_encode($request_data);

            Log::create([
                'project_id' => $project->id,
                'data' => $json,
            ]);

            $this->projectService->update([
                'last_check' => Carbon::now()->addHours(3),
                'checked' => 1,
            ], $project->id);

            $this->projectService->sendEmailIfStatusChange($request_data, $latest_status, $project);
        }

        if (count($this->projectService->getProjectsForCheck($quantity)) == 0) {
            $this->projectService->resetChecked();

            return redirect()->route('logs.index')->with('success', 'All projects are checked!');
        }

        return redirect()->route('logs.index')->with('success', 'Logs created successfully!');
    }
}
