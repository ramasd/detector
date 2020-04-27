<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Project;
use App\Services\Interfaces\ProjectServiceInterface;

class ProjectController extends Controller
{
    /**
     * @var ProjectServiceInterface
     */
    protected $projectService;

    /**
     * ProjectController constructor.
     * @param ProjectServiceInterface $projectServiceInterface
     */
    public function __construct(ProjectServiceInterface $projectServiceInterface)
    {
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function checkProjects()
    {
        $quantity = config('project.settings.quantity');
        $projects = $this->projectService->getProjectsForCheck($quantity);

        if (!count($projects)) {
            return response('Nothing to be check!');
        }

        $this->projectService->checkProjectsAndSendEmails($projects, $quantity);

        return response('Logs created successfully!');
    }
}
