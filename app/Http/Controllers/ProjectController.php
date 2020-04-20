<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Log;
use App\Project;
use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class ProjectController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $projects = Project::all();

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
        $projects = Project::all();

        foreach ($projects as $project) {
            try {
                $request_data = $this->getRequestData($project->url);
                $json = $this->arrayToJson($request_data);
            } catch (ConnectionException $e) {
                $json = 'URL is invalid';
            }

            Log::create([
                'project_id' => $project->id,
                'data' => $json,
            ]);

            $project->update([
                'last_check' => Carbon::now()->addHours(3)
            ]);
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
     * @param $array
     * @return false|string
     */
    public function arrayToJson($array)
    {
        return response()->json($array)->getContent();
    }
}
