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
     * @return mixed
     */
    public function index()
    {
        return $this->logRepository->getCurrentUserLogs()->sortDesc();
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
}
