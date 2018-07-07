<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Auth\Passwords\CanResetPassword;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable implements JWTSubject
{
   use canResetPassword, EntrustUserTrait;
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
