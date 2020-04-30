<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Project;
use App\Services\Interfaces\ProjectServiceInterface;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    /**
     * @var ProjectServiceInterface
     */
    protected $projectService;

    /**
     * @var string
     */
    protected $hash = "hJ7YF4TnVR30UkR1D8PW";

    /**
     * ProjectController constructor.
     * @param ProjectServiceInterface $projectServiceInterface
     */
    public function __construct(ProjectServiceInterface $projectServiceInterface)
    {
        $this->middleware('auth');
        $this->middleware('role:admin', ['only' => ['checkProjects']]);
        $this->projectService = $projectServiceInterface;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $projects = $this->projectService->index();

        return view('projects.index', compact('projects'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
        $this->projectService->store($attributes);

        return redirect()->route('projects.index')->with('success', 'Project has been created successfully!');
    }

    /**
     * @param Project $project
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Project $project)
    {
        $this->authorize('show', $project);

        $project = $this->projectService->findProjectById($project->id);

        return view('projects.show', compact('project'));
    }

    /**
     * @param Project $project
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Project $project)
    {
        $this->authorize('edit', $project);

        $project = $this->projectService->findProjectById($project->id);

        return view('projects.edit', compact('project'));
    }

    /**
     * @param UpdateProjectRequest $request
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $this->authorize('update', $project);

        $this->projectService->getAttributesAndUpdateProject($project, $request->all(), $request->status);

        return redirect()->route('projects.index')->with('success', 'Project has been updated successfully!');
    }

    /**
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $this->projectService->delete($project->id);

        return redirect()->route('projects.index')->with('success', 'Project has been deleted successfully!');
    }

    /**
     * @param $hash
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function checkProjects($hash)
    {
        if(!$hash || $hash != $this->hash){
            abort(403);
        }

        // Getting session start time
        $startTime = $this->projectService->getSessionStartTime();

        $quantity = config('project.settings.quantity');
        $projects = $this->projectService->getProjectsForCheck($quantity);

        $this->projectService->checkProjectsAndSendEmails($projects, $quantity);

        // Getting session duration time
        $totalDuration  = $this->projectService->getSessionTotalDuration($startTime);

        Log::info('Number of projects per session: ' . count($projects));
        Log::info('Session duration time: ' . $totalDuration . ' ms');

        return response('Projects checked successfully!');
    }
}
