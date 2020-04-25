<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * @var UserServiceInterface
     */
    protected $userService;

    /**
     * UserController constructor.
     * @param UserServiceInterface $userServiceInterface
     */
    public function __construct(UserServiceInterface $userServiceInterface)
    {
        $this->userService = $userServiceInterface;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users = $this->userService->index();

        return view('users.index', compact('users'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * @param StoreUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreUserRequest $request)
    {
        $attributes = $request->except('password');
        $attributes['password'] = Hash::make($request->password);
        $this->userService->store($attributes);

        return redirect()->route('users.index')->with('success', 'User has been created successfully!');
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(int $id)
    {
        $user = $this->userService->findUserById($id);

        return view('users.show', compact('user'));
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $id)
    {
        $user = $this->userService->findUserById($id);

        return view('users.edit', compact('user'));
    }

    /**
     * @param UpdateUserRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserRequest $request, int $id)
    {
        $attributes = $request->except(['status', 'password']);
        $attributes['status'] = $request->status;

        if (!is_null($request->password)) {
            $attributes['password'] = Hash::make($request->password);
        }

        $this->userService->update($attributes, $id);

        return redirect()->route('users.index')->with('success', 'User has been updated successfully!');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        $this->userService->delete($id);

        return redirect()->route('users.index')->with('success', 'User has been deleted successfully!');
    }
}
