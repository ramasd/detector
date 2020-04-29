<?php

namespace App\Services\Interfaces;

interface UserServiceInterface
{
    /**
     * @param int $recordsPerPage
     * @return \App\User[]|\Illuminate\Database\Eloquent\Collection|mixed
     */
    public function index($recordsPerPage = 50);

    /**
     * @param array $attributes
     * @return mixed
     */
    public function store(array $attributes);

    /**
     * @param int $id
     * @return mixed
     */
    public function findUserById(int $id);

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
