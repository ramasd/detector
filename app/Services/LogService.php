<?php

namespace App\Services;

use App\Repositories\LogRepository;

class LogService
{
    /**
     * @var LogRepository
     */
    protected $log;

    /**
     * LogService constructor.
     * @param LogRepository $log
     */
    public function __construct(LogRepository $log)
    {
        $this->log = $log;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return $this->log->all()->sortDesc();
    }

    /**
     * @param array $attributes
     * @param int $id
     * @return mixed
     */
    public function update(array $attributes, int $id)
    {
        return $this->log->update($attributes, $id);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        return $this->log->delete($id);
    }
}
