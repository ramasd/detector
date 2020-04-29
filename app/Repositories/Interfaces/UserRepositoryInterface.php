<?php

namespace App\Repositories\Interfaces;

use App\User;

interface UserRepositoryInterface
{
    /**
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * @param int $recordsPerPage
     * @return mixed
     */
    public function paginateUser($recordsPerPage);

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes);

    /**
     * @param int $id
     * @return mixed
     */
    public function findOrFail(int $id);

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
