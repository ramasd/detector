<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateLogRequest;
use App\Log;
use App\Project;
use App\Services\Interfaces\LogServiceInterface;

class LogController extends Controller
{
    /**
     * @var LogServiceInterface
     */
    protected $logService;

    /**
     * LogController constructor.
     * @param LogServiceInterface $logServiceInterface
     */
    public function __construct(LogServiceInterface $logServiceInterface)
    {
        $this->logService = $logServiceInterface;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $logs = $this->logService->index();

        return view('logs.index', compact('logs'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $log = Log::findOrFail($id);
        $projects = Project::all();

        return view('logs.edit', compact('log', 'projects'));
    }

    /**
     * @param UpdateLogRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateLogRequest $request, $id)
    {
        $attributes = $request->all();
        $this->logService->update($attributes, $id);

        return redirect()->route('logs.index')->with('success', 'Log has been updated successfully!');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->logService->delete($id);

        return redirect()->route('logs.index')->with('success', 'Log has been deleted successfully!');
    }
}
