<?php

namespace App\Services;

use App\Repositories\Interfaces\LogRepositoryInterface;
use App\Repositories\LogRepository;
use App\Services\Interfaces\LogServiceInterface;

class LogService implements LogServiceInterface
{
    /**
     * @var LogRepository
     */
    protected $logRepository;

    /**
     * LogService constructor.
     * @param LogRepositoryInterface $logRepositoryInterface
     */
    public function __construct(LogRepositoryInterface $logRepositoryInterface)
    {
        $this->logRepository = $logRepositoryInterface;
    }

    /**
     * @param int $recordsPerPage
     * @return mixed
     */
    public function index($recordsPerPage = 50)
    {
        return $this->logRepository->getCurrentUserLogs($recordsPerPage);
    }

    /**
     * @param array $attributes
     * @param int $id
     * @return mixed
     */
    public function update(array $attributes, int $id)
    {
        return $this->logRepository->update($attributes, $id);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        return $this->logRepository->delete($id);
    }

    /**
     * @param $log
     * @return mixed
     */
    public function logDataFromJsonToArr($log)
    {
        $log['data'] = $this->logRepository->jsonToArr($log->data);

        return $log;
    }

    /**
     * @param $logs
     */
    public function logsDataFromJsonToArr($logs)
    {
        foreach ($logs as $key => $log) {
            $logs[$key]['data'] = $this->logDataFromJsonToArr($log);
        }

        return $logs;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findLogById(int $id)
    {
        return $this->logRepository->findOrFail($id);
    }
}
