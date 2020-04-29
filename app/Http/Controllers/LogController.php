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
        $this->middleware('auth');
        $this->logService = $logServiceInterface;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $logs = $this->logService->index();
        $this->logService->logsDataFromJsonToArr($logs);

        return view('logs.index', compact('logs'));
    }

    public function show(int $id)
    {
        $log = $this->logService->findLogById($id);
        $this->logService->logDataFromJsonToArr($log);

        return view('logs.show', compact('log'));
    }

    /**
     * @param Log $log
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Log $log)
    {
        $this->authorize('edit', $log);

        $log = Log::findOrFail($log->id);
        $projects = Project::all();

        return view('logs.edit', compact('log', 'projects'));
    }

    /**
     * @param UpdateLogRequest $request
     * @param Log $log
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateLogRequest $request, Log $log)
    {
        $this->authorize('update', $log);

        $attributes = $request->all();
        $this->logService->update($attributes, $log->id);

        return redirect()->route('logs.index')->with('success', 'Log has been updated successfully!');
    }

    /**
     * @param Log $log
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Log $log)
    {
        $this->authorize('delete', $log);

        $this->logService->delete($log->id);

        return redirect()->route('logs.index')->with('success', 'Log has been deleted successfully!');
    }
}
