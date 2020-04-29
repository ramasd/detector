<?php

namespace App\Repositories\Interfaces;

use App\Log;

interface LogRepositoryInterface
{
    /**
     * @return Log[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all();

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
     * @param int $recordsPerPage
     * @return mixed
     */
    public function getCurrentUserLogs($recordsPerPage);

    /**
     * @param $data
     * @return mixed
     */
    public function jsonToArr($data);

    /**
     * @param int $id
     * @return mixed
     */
    public function findOrFail(int $id);
}
