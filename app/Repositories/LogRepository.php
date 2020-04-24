<?php

namespace App\Repositories;

use App\Log;

class LogRepository
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
     * @param $id
     * @return mixed
     */
    public function findOrFail($id)
    {
        return $this->log->findOrFail($id);
    }

    /**
     * @param array $attributes
     * @param $id
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        return $this->log->findOrFail($id)->update($attributes);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->log->findOrFail($id)->delete();
    }
}
