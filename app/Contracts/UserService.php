<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface UserService
{
    public function createUser(Request $request);

    public function login(Request $request);

    public function logout();

    public function change_password(Request $request);

    public function refresh_token($token);

    public function editUser(Request $request);
}
