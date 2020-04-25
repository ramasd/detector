<?php

namespace App\Services\Interfaces;

interface LogServiceInterface
{
    /**
     * @return mixed
     */
    public function index();

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
