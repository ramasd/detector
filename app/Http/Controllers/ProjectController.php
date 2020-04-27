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

        $attributes = $request->all();
        $attributes['status'] = $request->status;
        $this->projectService->updateProject($attributes, $project->id);

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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkProjects()
    {
        $quantity = config('project.settings.quantity');
        $projects = $this->projectService->getProjectsForCheck($quantity);

        if (!count($projects)) {
            return redirect()->route('logs.index')->with('success', 'Nothing to be check!');
        }

        $this->projectService->checkProjectsAndSendEmails($projects, $quantity);

        return redirect()->route('logs.index')->with('success', 'Logs created successfully!');
    }
}
