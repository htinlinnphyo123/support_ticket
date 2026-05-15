<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\Organisation;
use App\Models\User;
use App\Services\UserService;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function index()
    {
        $users = $this->userService->getAllUsers(auth()->user());

        return Inertia::render('Users/Index', [
            'users' => UserResource::collection($users),
        ]);
    }

    public function create()
    {
        return Inertia::render('Users/Create', [
            'organisations' => Organisation::all(['id', 'name']),
        ]);
    }

    public function store(UserCreateRequest $request)
    {
        $this->userService->createUser($request->validated(), $request->user());

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load('organisation');

        return Inertia::render('Users/Show', [
            'user' => (new UserResource($user))->resolve(),
        ]);
    }

    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'user' => $user,
            'organisations' => Organisation::all(['id', 'name']),
        ]);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $this->userService->updateUser($user, $request->validated(), $request->user());

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $this->userService->deleteUser($user);

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
