<?php

namespace App\Repositories;

use App\Log;
use App\Repositories\Interfaces\LogRepositoryInterface;

class LogRepository implements LogRepositoryInterface
{
    /**
     * @var Log
     */
    protected $log;

    /**
     * LogRepository constructor.
     * @param Log $log
     */
    public function __construct(Log $log)
    {
        $this->log = $log;
    }

    /**
     * @return Log[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->log->all();
    }

    /**
     * @param array $attributes
     * @param int $id
     * @return mixed
     */
    public function update(array $attributes, int $id)
    {
        return $this->log->findOrFail($id)->update($attributes);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        return $this->log->findOrFail($id)->delete();
    }
}
