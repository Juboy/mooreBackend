<?php

namespace App\Http\Controllers;

use App\Exceptions\TaskException;
use Illuminate\Http\Request;
use App\Contracts\UserService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function store(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required',
            'email' => 'bail|required|unique:users',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            throw new TaskException($validator->errors()->first());
        }

        return $this->userService->createUser($request);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            throw new TaskException($validator->errors()->first());
        }

        return $this->userService->login($request);
    }

    public function logout(Request $request): array
    {
        return $this->userService->logout();
    }

    public function refresh(Request $request): array
    {
        return $this->userService->refresh_token($request->input('token'));
    }

    public function edit(Request $request): array
    {
        return $this->userService->editUser($request);
    }
    public function change_password(Request $request): array
    {
        return $this->userService->change_password($request);
    }
}
