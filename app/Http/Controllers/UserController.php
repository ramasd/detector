<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\Interfaces\UserServiceInterface;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

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
        $this->middleware('auth');
        $this->middleware('role:admin', ['except' => ['show', 'edit', 'update']]);

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
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(User $user)
    {
        $this->authorize('show', $user);

        $user = $this->userService->findUserById($user->id);

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
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $attributes = $request->except(['status', 'password']);
        $attributes['status'] = $request->status;

        if (!is_null($request->password)) {
            $attributes['password'] = Hash::make($request->password);
        }

        $this->userService->update($attributes, $user->id);

        return redirect()->back()->with('success', 'User has been updated successfully!');
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

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginAs($id)
    {
        Auth::loginUsingId($id);

        return Redirect::home();
    }
}
