<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
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
        $this->projectService->store($attributes);

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

        if (!count($projects)) {
            return redirect()->route('logs.index')->with('success', 'Nothing to be check!');
        }

        $this->projectService->checkProjectsAndSendEmails($projects, $quantity);

        return redirect()->route('logs.index')->with('success', 'Logs created successfully!');
    }
}
