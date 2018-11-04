<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;

class User extends Authenticatable
{
    use Notifiable;

    private $userRoles=[];

    public function __construct()
    {
        $this->userRoles = config('constants.userRoles');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function isSuperAdmin() {
        return Auth::user()->role == $this->userRoles['superAdmin'];
    }

    public function isAdmin() {
        return Auth::user()->role == $this->userRoles['admin'];
    }

    public function isUser() {
        return Auth::user()->role == $this->userRoles['user'];
    }
}
