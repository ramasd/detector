<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    /**
     * @var UserRepository
     */
    protected $user;

    /**
     * UserService constructor.
     * @param UserRepository $user
     */
    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    /**
     * @return \App\User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return $this->user->all()->sortDesc();
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function store(array $attributes)
    {
        return $this->user->create($attributes);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findUserById(int $id)
    {
        return $this->user->findOrFail($id);
    }

    /**
     * @param array $attributes
     * @param int $id
     * @return mixed
     */
    public function update(array $attributes, int $id)
    {
        return $this->user->update($attributes, $id);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        return $this->user->delete($id);
    }
}
