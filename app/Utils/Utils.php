<?php

namespace App\Utils;

use App\Exceptions\EventorException;
use App\User;
use Illuminate\Support\Facades\Auth;

class Utils
{

    public static $pageLimit = 10;
    public static function hasRole(...$role)
    {
        $user = Auth::user();

        if ($user != null && $user->hasRole($role)) {
            return true;
        }
        throw new EventorException("Unauthorized");
    }

    public static function isRole(...$role)
    {
        $user = Auth::user();

        if ($user != null && $user->hasRole($role)) {
            return true;
        }
        return false;
    }
}
