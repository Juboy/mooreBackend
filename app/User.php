<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Role;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'id'
    ];

    public function tasks()
    {
        return $this->HasMany('App\Task');
    }
    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    public function hasRole($role): bool
    {
        if (is_array($role)) {
            return $this->roles()->whereIn('authority', $role)->first() != null;
        }
        return $this->roles()->where('authority', $role)->first() != null;
    }
}
