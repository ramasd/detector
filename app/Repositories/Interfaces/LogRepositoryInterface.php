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
}
