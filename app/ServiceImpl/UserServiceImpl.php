<?php

namespace App\ServiceImpl;

use App\Contracts\UserService;
use Illuminate\Http\Request;
use App\User;
use App\Role;

use App\Exceptions\TaskException;
use JWTAuth;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

use App\Utils\Response;
use App\Utils\Utils;
use App\Setting;

use Hash;


class UserServiceImpl implements UserService
{


    public function createUser(Request $request): array
    {

        $user = new User([
            'name' => $request->input('name'),
            'password' => bcrypt($request->input('password')),
            'email' => $request->input('email'),
        ]);
        $response = new Response();
        if ($user->save()) {;
            $response->setMessage("Signup successful");
            $response->setStatus(Response::$success);
            return $response->toArray();
        }

        $response->setMessage("Signup Failed");
        $response->setStatus(Response::$failed);
        return $response->toArray();
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $response = new Response();
        try {
            if (!$token = JWTAuth::attempt($credentials)) {

                $response->setMessage("Invalid credentials");
                $response->setStatus(2);
                return $response->toArray();
            }
        } catch (JWTException $e) {
            $response->setMessage("Could not create token");
            $response->setStatus(2);
            return $response->toArray();
        }
        $response->setMessage("Login Successful");
        $response->setStatus(Response::$success);

        $responseArray = $response->toArray();
        $responseArray['access_token'] = $token;
        $responseArray['user'] = User::where('email', Auth::user()->email)->get()->first();

        return $responseArray;
    }

    public function logout()
    {
        $token = JWTAuth::getToken();
        $response = new Response();
        JWTAuth::invalidate($token);

        $response->setMessage("Logout Successful");
        $response->setStatus(Response::$success);
        return $response->toArray();
    }

    public function change_password(Request $request)
    {
        $user = Auth::user();

        if (!Hash::check($request->input('old_password'), $user->password)) {
            throw new TaskException('Incorrect password');
        }

        $user->password = bcrypt($request->input('new_password'));

        $response = new Response();
        if ($user->save()) {
            $token = JWTAuth::getToken();
            JWTAuth::invalidate($token);

            $newToken = JWTAuth::fromUser($user);
            $response->setMessage("Password Changed Successfully");
            $response->setStatus(Response::$success);
            $responseArray = $response->toArray();
            $responseArray['access_token'] = $newToken;
            return $responseArray;
        } else throw new TaskException("Couldn't change password");
    }

    public function refresh_token($token)
    {
        $newToken = JWTAuth::refresh($token);

        $response = new Response();
        $response->setMessage("Token refreshed");
        $response->setStatus(Response::$success);
        $responseArray = $response->toArray();
        $responseArray['access_token'] = $newToken;
        return $responseArray;
    }

    public function editUser(Request $request)
    {
        $user = Auth::user();

        $response = new Response();
        $user->name = $request->input('name');

        if ($user->save()) {
            $response->setMessage("Success");
            $response->setStatus(Response::$success);

            $responseArray = $response->toArray();
            $responseArray['user'] = User::where('email', Auth::user()->email)->get()->first();

            return $responseArray;
        } else throw new TaskException("Couldn't edit user details");
    }
}
