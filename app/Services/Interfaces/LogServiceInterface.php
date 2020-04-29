<?php

namespace App\Services\Interfaces;

interface LogServiceInterface
{
    /**
     * @param int $recordsPerPage
     * @return mixed
     */
    public function index($recordsPerPage = 50);

    /**
     * @param array $attributes
     * @param int $id
     * @return mixed
     */
    public function update(array $attributes, int $id);

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);

    /**
     * @param $log
     * @return mixed
     */
    public function logDataFromJsonToArr($log);

    /**
     * @param $logs
     */
    public function logsDataFromJsonToArr($logs);

    /**
     * @param int $id
     * @return mixed
     */
    public function findLogById(int $id);
}
