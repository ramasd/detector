<?php

namespace App\Repositories;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\User;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var User
     */
    protected $user;

    /**
     * UserRepository constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->user->all();
    }

    /**
     * @param int $recordsPerPage
     * @return mixed
     */
    public function paginateUser($recordsPerPage)
    {
        return $this->user->orderBy('id', 'desc')->paginate($recordsPerPage);
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        return $this->user->create($attributes);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findOrFail(int $id)
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
        return $this->user->findOrFail($id)->update($attributes);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        return $this->user->findOrFail($id)->delete();
    }
}
